<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_over_payment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Sales_over_payment_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->user = $this->load->library('admin_lib');
        $this->sales = $this->load->library('sales_lib');
        $this->customer = $this->load->library('customer_lib');

    }

    private $properti, $modul, $title;
    private $user,$sales,$customer,$currency,$unit;

    function index()
    {
        $this->get_last_sover_payment();
    }

    function get_last_sover_payment()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sover_payment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $sover_payments = $this->Sales_over_payment_model->get_last_sover_payment($this->modul['limit'], $offset)->result();
        $num_rows = $this->Sales_over_payment_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_sover_payment');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination
            

            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Customer', 'Sales - No', 'AR - Payment', 'Currency', 'Amount', 'Over Balance');

            $i = 0 + $offset;
            foreach ($sover_payments as $sover_payment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sover_payment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'SOV-00'.$sover_payment->no, $this->customer->get_customer_shortname($sover_payment->customer), 'SO-00'.$sover_payment->sales_no, 'CR-00'.$sover_payment->ar_payment,
                    $this->get_so_currency($sover_payment->sales_no), number_format($sover_payment->balance), number_format($sover_payment->over)
                );
            }

            $data['table'] = $this->table->generate();
        }
        else { $data['message'] = "No $this->title data was found!"; }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'sover_payment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $sover_payments = $this->Sales_over_payment_model->search($this->input->post('tsales'), $this->customer->get_customer_id($this->input->post('tcustomer')))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Customer', 'Sales - No', 'AR - Payment', 'Currency', 'Amount', 'Over Balance');

        $i = 0;
        foreach ($sover_payments as $sover_payment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sover_payment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'SOV-00'.$sover_payment->no, $this->customer->get_customer_shortname($sover_payment->customer), 'SO-00'.$sover_payment->sales_no, 'CR-00'.$sover_payment->ar_payment,
                $this->get_so_currency($sover_payment->sales_no), number_format($sover_payment->balance), number_format($sover_payment->over)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function get_so_currency($no)
    { $res = $this->sales->get_so($no); return $res->currency; }

    
//    ===================== approval ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

        $this->load->view('sover_payment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        
        $cur = $this->input->post('ccurrency');


        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['reports'] = $this->Sales_over_payment_model->report($cur)->result();
        $total           = $this->Sales_over_payment_model->total($cur);
        $data['total']   = $total['over'];
        
        $this->load->view('sover_payment_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>
