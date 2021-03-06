<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reststock extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Reststock_model', 'model', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->load->library('unit_lib');
        $this->product = new Products_lib();
        $this->user = new Admin_lib();
        $this->wt = $this->load->library('warehouse_transaction');
        $this->warehouse = new Warehouse_lib();
    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title, $currency, $stockvalue=0;
    private $user,$product,$wt,$warehouse;

    function index()
    {
        $this->get_last_reststock();
    }

    function get_last_reststock()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'reststock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        $data['warehouse'] = $this->warehouse->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $reststocks = $this->model->get($this->modul['limit'], $offset)->result();
        $num_rows = $this->model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_reststock');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Dates', 'Warehouse', 'Product', 'Qty', 'Action');

            $i = 0 + $offset;
            foreach ($reststocks as $reststock)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $reststock->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'RS-0'.$reststock->id, $reststock->currency, tglin($reststock->dates), $this->warehouse->get_name($reststock->warehouse_id), $this->product->get_name($reststock->product), $reststock->qty.' '.$this->product->get_unit($reststock->product),
                    anchor($this->title.'/update/'.$reststock->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$reststock->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'reststock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['warehouse'] = $this->warehouse->combo_all();

        $reststocks = $this->model->search($this->input->post('cware'), $this->input->post('tsearch'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Dates', 'Warehouse', 'Product', 'Qty', 'Action');

        $i = 0;
        foreach ($reststocks as $reststock)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $reststock->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'RS-0'.$reststock->id, $reststock->currency, tglin($reststock->dates), $this->warehouse->get_name($reststock->warehouse_id), $this->product->get_name($reststock->product), $reststock->qty.' '.$this->product->get_unit($reststock->product),
                anchor($this->title.'/update/'.$reststock->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$reststock->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        
        $this->model->delete($uid);
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        $data['warehouse'] = $this->warehouse->combo();
        
        $this->load->view('reststock_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'reststock_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['warehouse'] = $this->warehouse->combo();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('cware', 'Warehouse', 'required');
        $this->form_validation->set_rules('titem', 'Product', 'required|callback_valid_product');
        $this->form_validation->set_rules('tqty', 'Qty', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $reststock = array('dates' => $this->input->post('tdate'), 'warehouse_id' => $this->input->post('cware'), 
                               'product' => $this->product->get_id($this->input->post('titem')),
                               'currency' => $this->product->get_currency($this->input->post('titem')),
                               'qty' => $this->input->post('tqty'), 'userid' => $this->user->get_userid($this->session->userdata('username')), 
                              );
            
            $this->model->add($reststock);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add/');
//            echo 'true';
        }
        else
        {
              $this->load->view('reststock_form', $data);
//            echo validation_errors();
        }

    }

    function update($id=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Update '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/');
        $data['warehouse'] = $this->warehouse->combo();

        $reststock = $this->model->get_stock_by_id($id)->row();

        $data['default']['date'] = $reststock->dates;
        $data['default']['warehouse'] = $reststock->warehouse_id;
        $data['default']['product'] = $this->product->get_name($reststock->product);
        $data['default']['qty'] = $reststock->qty;
        $data['default']['currency'] = $reststock->currency;
        $data['user'] = $this->user->get_username($reststock->userid);
        
        $this->session->set_userdata('curid', $reststock->id);

        $this->load->view('reststock_form', $data);
    }



    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);
//        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('cware', 'Warehouse', 'required');
        $this->form_validation->set_rules('titem', 'Product', 'required|callback_valid_product');
        $this->form_validation->set_rules('tqty', 'Qty', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

           $reststock = array('dates' => date('Y-m-d'), 'qty' => $this->input->post('tqty'), 
                              'userid' => $this->user->get_userid($this->session->userdata('username')),
                              );

            $this->model->update($this->session->userdata('curid'), $reststock);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('reststock_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_product($product)
    {
        $product = $this->product->get_id($product);
        if ($this->model->valid_product($product) == FALSE)
        {
            $this->form_validation->set_message('valid_product', "Product already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['warehouse'] = $this->warehouse->combo_all();
        $this->load->view('reststock_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $warehouse = $this->input->post('cware');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['reports'] = $this->model->report($warehouse,$start,$end)->result();
        
        $this->load->view('reststock_report', $data);
    }


// ====================================== REPORT =========================================

}

?>