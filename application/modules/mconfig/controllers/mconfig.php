<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mconfig extends MX_Controller
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
        $this->journal = $this->load->library('journal_lib');
        $this->account  = new Account_lib();
        $this->ap       = $this->load->library('ap_lib');
        $this->category = $this->load->library('categories_lib');
        $this->dept = new Dept_lib();

        $this->model = new Mconfigs();
    }

    private $properti, $modul, $title, $model, $account,$ap;
    private $user,$journal,$currency,$category,$dept;

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
        $data['main_view'] = 'mutation_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $costs = $this->model->get($this->modul['limit'], $offset);
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
            
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Department', 'Account', 'Type', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, $this->dept->get_name($cost->dept_id), $this->account->get_code($cost->account).' : '.$this->account->get_name($cost->account), strtoupper($cost->type),
                    anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{ $data['message'] = "No $this->title data was found!";}
	$this->load->view('mconfig_view', $data);
    }

    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'mconfig_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('cdept', 'Department', 'required|callback_valid_config');
        $this->form_validation->set_rules('titem', 'Account Name', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->dept_id     = $this->input->post('cdept');
            $this->model->account     = $this->account->get_id_code($this->input->post('titem'));
            $this->model->desc        = $this->input->post('tdesc');
            $this->model->type        = $this->input->post('ctype');

            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            
            echo 'true';
        }
        else
        {
            echo validation_errors();
        }

    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        $this->model->where('id', $uid)->get();
        $this->model->delete();
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }
    
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();

        $cost = $this->model->where('id', $uid)->get();
        $data['default']['dept']    = $cost->dept_id;
        $data['default']['account'] = $this->account->get_code($cost->account);
        $data['default']['desc']    = $cost->desc;
        $data['default']['type']    = $cost->type;

	$this->session->set_userdata('curid', $cost->id);
        $this->load->view('mconfig_view', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'mutation_view';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('account/','<span>back</span>', array('class' => 'back')));
        
        $data['category'] = $this->category->combo();

	// Form validation
        $this->form_validation->set_rules('cdept', 'Department', 'required|callback_validating_config');
        $this->form_validation->set_rules('titem', 'Account Name', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->dept_id     = $this->input->post('cdept');
            $this->model->account     = $this->account->get_id_code($this->input->post('titem'));
            $this->model->desc        = $this->input->post('tdesc');
            $this->model->type        = $this->input->post('ctype');
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true';
        }
        else
        {
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->load->view('account_update', $data);
//            echo validation_errors();
        }
    }

    public function valid_config($dept)
    {
        $this->model->where('type', $this->input->post('ctype'));
        $val = $this->model->where('dept_id', $dept)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_config', "Invalid Configuration..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
     
    public function validating_config($dept)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $this->model->where('type', $this->input->post('ctype'));
        $val = $this->model->where('dept_id', $dept)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_config', "Invalid Configuration..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function report()
    {
        $data['costs'] = $this->model->get();
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $this->load->view('cost_report', $data);
    }

}

?>