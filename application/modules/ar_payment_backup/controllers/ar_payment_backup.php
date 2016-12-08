<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_payment_backup extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Arpaymentbackup_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->load->library('currency');
        $this->load->library('bank');
        $this->customer = $this->load->library('customer');
        $this->user = $this->load->library('admin');
        $this->journal = $this->load->library('journal');
        $this->cek = $this->load->library('checkout');
        $this->sales = $this->load->library('sales');
        $this->tax = $this->load->library('tax');

    }

    private $properti, $modul, $title;
    private $customer,$user,$journal,$cek,$sales,$tax;

    public $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
        $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'arpayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $appayments = $this->Arpaymentbackup_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Arpaymentbackup_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Cust', 'ACC', 'Check No', 'Total');

            $i = 0 + $offset;
            foreach ($appayments as $appayment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CR-00'.$appayment->no, tgleng($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount)
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'arpayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $appayments = $this->Arpaymentbackup_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tstart'), $this->input->post('tend'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Cust', 'ACC', 'Check No', 'Total');

        $i = 0;
        foreach ($appayments as $appayment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CR-00'.$appayment->no, tgleng($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function acc($val=null)
    { switch ($val) { case 'bank': $val = 'Bank'; break; case 'cash': $val = 'Cash'; break; case 'pettycash': $val = 'Petty Cash'; break; } return $val; }
//    ===================== approval ===========================================

    private function post_status($val)
    { if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class; }

    //    ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $this->load->view('arpayment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $customer = $this->input->post('tcustomer');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $acc = $this->input->post('cacc');
        $cur = $this->input->post('ccurrency');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['acc'] = $acc;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Arpayment_model->report($customer,$start,$end,$acc,$cur)->result();

        $total = $this->Arpayment_model->total($customer,$start,$end,$acc,$cur);
        $data['total'] = $total['amount'];

        $this->load->view('arpayment_report', $data);

    }

//    ================================ REPORT =====================================

}

?>