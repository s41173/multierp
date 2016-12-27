<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Foundation extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->dept = $this->load->library('dept_lib');
        $this->employee = $this->load->library('employee_lib');
        $this->model = new Foundations();
    }

    private $properti, $modul, $title,$dept,$employee;
    private $user,$currency,$model;

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
        $data['main_view'] = 'foundation_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
	// ---------------------------------------- //
        $result = $this->model->get($this->modul['limit'], $offset);
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
//            
//
            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Name', 'Role', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, ucfirst($res->name), strtoupper($this->get_role($res->role)),
                    anchor($this->title.'/update/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
//
            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
//
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
        
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'regcost_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $this->load->view('foundation_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'honorfee_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
         
        $data['dept'] = $this->dept->combo(); 
        
	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('crole', 'Role', 'required|callback_valid_role');
               
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name  = $this->input->post('tname');
            $this->model->role  = $this->input->post('crole');
//            
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add');
            echo 'true';
        }
        else
        { 
            //$this->load->view('honorfee_form', $data); 
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
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'regcost_update';
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['dept'] = $this->dept->combo(); 
        
        $this->model->where('id', $uid)->get();
        
        $data['default']['name'] = $this->model->name;
        $data['default']['role'] = $this->get_role($this->model->role);
        
	$this->session->set_userdata('curid', $this->model->id);
        $this->load->view('foundation_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_form';
	$data['form_action'] = site_url($this->title.'/update_process');

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('trole', 'Role', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();
            
            $this->model->name = $this->input->post('tname');
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            
            echo 'true'; 
        }
        else
        {
//            $this->load->view('honorfee_update', $data);
           echo validation_errors();
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
        }
        
        $this->session->unset_userdata('curid');
    }
    
    public function valid_role($val)
    {
        $val = $this->model->where('role', $val)->count();
        if ($val > 0){ $this->form_validation->set_message('valid_role', "Role - already registered..!"); return FALSE; }
        else { return TRUE; }
    }
    
    function get_role($val)
    {
        $res = null;
        switch ($val)
        {
            case 1: $res = 'CHAIRMAN'; break;
            case 2: $res = 'VICE CHAIRMAN'; break;
            case 3: $res = 'SECRETARY'; break;
            case 4: $res = 'DEPUTY SECRETARY'; break;
            case 5: $res = 'TREASURE'; break;
            case 6: $res = 'DEPUTY TREASURE'; break;
            case 7: $res = 'COORDINATOR'; break;
            case 8: $res = 'FINANCE MANAGER'; break;
            default: $res = NULL;
        }
        return $res;
    }
    
    public function report()
    {
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
                
        $data['results'] = $this->model->get();
        
        $this->load->view('foundation_report', $data);
    }

}

?>