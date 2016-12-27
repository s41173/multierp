<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_installment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ar_installment_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->user = $this->load->library('admin_lib');
        $this->sales = new Sales_lib();

    }

    private $properti, $modul, $title;
    private $user,$sales,$journal,$currency,$unit;

    function index()
    {
        $this->get_last_ar_installment();
    }

    function get_last_ar_installment()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ar_installment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $ar_installments = $this->Ar_installment_model->get_last_ar_installment($this->modul['limit'], $offset)->result();
        $num_rows = $this->Ar_installment_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_ar_installment');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Sales - No', 'AR - Payment', 'Amount');

            $i = 0 + $offset;
            foreach ($ar_installments as $ar_installment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ar_installment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'IAS-00'.$ar_installment->no, tgleng($ar_installment->dates), 'SO-00'.$ar_installment->sales_no, 'CR-00'.$ar_installment->ar_payment, number_format($ar_installment->amount)
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
        $data['main_view'] = 'ar_installment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $ar_installments = $this->Ar_installment_model->search($this->input->post('tsales'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Sales - No', 'AR - Payment', 'Amount');

        $i = 0;
        foreach ($ar_installments as $ar_installment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ar_installment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'IAS-00'.$ar_installment->no, tgleng($ar_installment->dates), 'SO-00'.$ar_installment->sales_no, 'CR-00'.$ar_installment->ar_payment, number_format($ar_installment->amount)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function get_so_currency($no)
    { $res = $this->sales->get_so($no); return $res->currency; }


    function confirmation($pid)
    {
        $ar_installment = $this->Ar_installment_model->get_ar_installment_by_id($pid)->row();

        if ($ar_installment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!");
           redirect($this->title);
        }
        elseif ($this->valid_sales($ar_installment->sales_no,$ar_installment->no) == FALSE)
        { $this->session->set_flashdata('message', "Sales Order Can't 0 & Registered..!"); redirect($this->title); }
        else
        {
           $this->sales->delete_so_item($ar_installment->sales_no); // delete so item

           $sales = array('docno' => '', 'dates' => $ar_installment->dates, 'log' => 0, 'status' => 0, 'approved' => 0,
                         'tax' => 0, 'costs' => 0, 'p1' => 0, 'p2' => 0, 'total' => 0, 'discount' => 0, 'discount_desc' => '',
                         'notes' => '', 'desc' => '', 'shipping_date' => $ar_installment->dates);
           $this->sales->update($ar_installment->sales_no,$sales); // update so

           //  create journal
           $this->create_so_journal($ar_installment->dates, $ar_installment->currency, 'SO-00'.$ar_installment->sales_no.'-'.$ar_installment->notes, 'SJ',
                                    $ar_installment->sales_no, 'AR', $ar_installment->total, $ar_installment->dp);

           // create SAJ journal
           $this->journal->create_journal($ar_installment->dates, $ar_installment->currency, 'SAJ-00'.$ar_installment->no.'-'.$ar_installment->notes, 'SAJ',
                                          $ar_installment->no, 'AP', $ar_installment->total);
           
           $data = array('approved' => 1);
           $this->Ar_installment_model->update_id($pid, $data);

           $this->session->set_flashdata('message', "SAJ-00$ar_installment->no confirmed..!");
           redirect($this->title);
        }

    }

    
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

        $this->load->view('ar_installment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $cur = $this->input->post('ccurrency');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['reports'] = $this->Ar_installment_model->report($start,$end,$cur)->result();
        $total           = $this->Ar_installment_model->total($start,$end,$cur);
        $data['total'] = $total['amount'];
        
        $this->load->view('ar_installment_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>
