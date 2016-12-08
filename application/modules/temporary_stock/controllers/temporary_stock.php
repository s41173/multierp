<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Temporary_stock extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Temporary_stock_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->city = new City_lib();
        $this->brand = new Brand();
        $this->category = new Category_lib();
        $this->unit = new Unit_lib();
        $this->product = new Products_lib();

        $this->load->library('currency_lib');
    }

    private $properti, $modul, $title;
    private $brand, $category, $unit, $product;

    function index()
    { $this->get_last_temporary_stock(); }

    function get_last_temporary_stock()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'temporary_stock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main','<span>back</span>', array('class' => 'back')));

        $data['brand']    = $this->brand->combo_all();
        $data['category'] = $this->category->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $temporary_stocks = $this->Temporary_stock_model->get_last_temporary_stock($this->modul['limit'], $offset)->result();
        $num_rows = $this->Temporary_stock_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_temporary_stock');
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
            $this->table->set_heading('#','No', 'Code', 'Product', 'Qty', 'Action');

            $i = 0 + $offset;
            foreach ($temporary_stocks as $temporary_stock)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $temporary_stock->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, 'TPRO-0'.$temporary_stock->id, $this->product->get_name($temporary_stock->product), $temporary_stock->qty.' '.$temporary_stock->unit,
                    anchor($this->title.'/delete/'.$temporary_stock->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'temporary_stock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['brand']    = $this->brand->combo_all();
        $data['category'] = $this->category->combo_all();

	// ---------------------------------------- //
        $temporary_stocks = $this->Temporary_stock_model->search($this->product->get_id($this->input->post('tproduct')))->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Code', 'Product', 'Qty', 'Action');

        $i = 0;
        foreach ($temporary_stocks as $temporary_stock)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $temporary_stock->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                form_checkbox($datax), ++$i, 'TPRO-0'.$temporary_stock->id, $this->product->get_name($temporary_stock->product), $temporary_stock->qty.' '.$temporary_stock->unit,
                anchor($this->title.'/delete/'.$temporary_stock->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
	$this->load->view('template', $data);
    }

    function get_list($cur='IDR')
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'temporary_stock_list';
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['brand']    = $this->brand->combo_all();
        $data['category'] = $this->category->combo_all();

        $brand = $this->input->post('cbrand');
        $category = $this->input->post('ccategory');

        $temporary_stocks = $this->Temporary_stock_model->get_list_temporary_stock($cur,$brand,$category)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Product', 'Qty', 'Action');

            $i = 0;
            foreach ($temporary_stocks as $temporary_stock)
            {
               $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$this->product->get_name($temporary_stock->product).'\',\'tproduct\')');

                $this->table->add_row
                (
                    ++$i, 'TPRO-0'.$temporary_stock->id, $this->product->get_name($temporary_stock->product), $temporary_stock->qty.' '.$temporary_stock->unit,
                    form_button($datax)
                );
            }

            $data['table'] = $this->table->generate();

            $this->load->view('temporary_stock_list', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        if ( $this->cek_qty($uid) == TRUE )
        {
           $this->Temporary_stock_model->delete($uid);
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "$this->title qty not 0..!"); }
        redirect($this->title);
    }

    private function cek_qty($uid)
    {
        $qty = $this->Temporary_stock_model->get_temporary_stock_by_id($uid);
        $qty = $qty->qty;
        if ($qty > 0) { return FALSE;} else { return TRUE; }
    }

    //    ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $this->load->view('temporary_stock_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Temporary_stock_model->report($this->product->get_id($this->input->post('tproduct')))->result();

        $this->load->view('temporary_stock_report', $data);

    }

//    ================================ REPORT =====================================

}

?>