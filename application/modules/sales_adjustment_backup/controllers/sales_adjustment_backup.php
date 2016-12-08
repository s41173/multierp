<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_adjustment_backup extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Sales_adjustment_backup_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->load->library('currency');
        $this->load->library('unit');
        $this->product = $this->load->library('products');
        $this->user = $this->load->library('admin');
        $this->sales = $this->load->library('sales');
        $this->journal = $this->load->library('journal');

    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title;
    private $user,$product,$sales,$journal;

    function index()
    {
        $this->get_last_sales_adjustment();
    }

    function get_last_sales_adjustment()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $sales_adjustments = $this->Sales_adjustment_backup_model->get_last_sales_adjustment($this->modul['limit'], $offset)->result();
        $num_rows          = $this->Sales_adjustment_backup_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_sales_adjustment');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Sales', 'Currency', 'Notes', 'DP', 'Total', 'Log');

            $i = 0 + $offset;
            foreach ($sales_adjustments as $sales_adjustment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'SAJ-00'.$sales_adjustment->no, tgleng($sales_adjustment->dates), 'SO-00'.$sales_adjustment->sales_no, $sales_adjustment->currency, $sales_adjustment->notes, number_format($sales_adjustment->dp), number_format($sales_adjustment->total), $sales_adjustment->log
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
        $data['main_view'] = 'sales_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $sales_adjustments = $this->Sales_adjustment_backup_model->search($this->input->post('tno'), $this->input->post('tstart'), $this->input->post('tend'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Sales', 'Currency', 'Notes', 'DP', 'Total', 'Log');

        $i = 0;
        foreach ($sales_adjustments as $sales_adjustment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
               ++$i, 'SAJ-00'.$sales_adjustment->no, tgleng($sales_adjustment->dates), 'SO-00'.$sales_adjustment->sales_no, $sales_adjustment->currency, $sales_adjustment->notes, number_format($sales_adjustment->dp), number_format($sales_adjustment->total), $sales_adjustment->log
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }


// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

        $this->load->view('sales_adjustment_report_panel', $data);
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

        $data['reports'] = $this->Sales_adjustment_backup_model->report($start,$end,$cur)->result();
        $total = $this->Sales_adjustment_backup_model->total($start,$end,$cur);
        $data['total'] = $total['total'];
        
        $this->load->view('sales_adjustment_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>
