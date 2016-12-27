<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categoriesc extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->ap       = $this->load->library('ap_lib');
        $this->cost     = new Cost_lib();
        $this->category = new Categories_lib();

        $this->model = new Categories();
    }

    private $properti, $modul, $title, $model, $account,$ap,$cost;
    private $user,$journal,$currency,$category;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'categories_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['category'] = $this->category->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $categoriess = $this->model->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

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
            $this->table->set_heading('No', 'Desc', 'Action');

            $i = 0 + $offset;
            foreach ($categoriess as $categories)
            {
                $this->table->add_row
                (
                    ++$i, $categories->name,
                    anchor($this->title.'/delete/'.$categories->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'categories_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_categories');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name = $this->input->post('tname');

            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title);
            echo 'true';
        }
        else
        {
//               $this->load->view('template', $data);
            echo validation_errors();
        }

    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        if ( $this->cost->cek_relation($uid,'category') == TRUE )
        {
            $this->model->where('id', $uid)->get();
            $this->model->delete();
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    public function valid_categories($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_categories', "Invalid Cost..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_account($acc)
    {
        $val = $this->model->where('account_id', $this->account->get_id_code($acc))->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_account', "Invalid Account..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validation_categories($acc)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('account_id', $this->account->get_id_code($acc))->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_categories', "Invalid Account..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function report()
    {
        $data['categoriess'] = $this->model->get();
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $this->load->view('categories_report', $data);
    }

}

?>