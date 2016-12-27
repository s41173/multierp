<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Semesters extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Students_model', 'sm', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->user = $this->load->library('admin_lib');
        $this->dept = $this->load->library('dept_lib');
        $this->faculty = $this->load->library('faculty_lib');
        $this->student = $this->load->library('student_lib');
        $this->finance = $this->load->library('financial_lib');
        $this->grade   = $this->load->library('grade_lib');
        $this->model   = new Semester();
    }

    private $properti, $modul, $title,$dept,$faculty;
    private $user,$student,$finance,$grade,$model;

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
        $data['main_view'] = 'semester_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('academic','<span>back</span>', array('class' => 'back')));
        
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
            $this->table->set_heading('No', 'Semester', 'Description', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, $res->name, $res->desc,
                    anchor($this->title.'/active/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->active), 'title' => 'edit / update')).' '.
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
    
    private function post_status($val=null)
    { $class = 'notapprove'; if ($val) {$class = "approve"; }elseif ($val == 1){$class = "notapprove"; } return $class; }
    
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
	// Form validation
        $this->form_validation->set_rules('tsemester', 'Semester', 'required|numeric|callback_valid_semester');
        $this->form_validation->set_rules('tdesc', 'Description', '');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name = $this->input->post('tsemester');
            $this->model->desc = $this->input->post('tdesc');
            
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title);
             echo 'true';
        }
        else
        {
//               $this->load->view('student_form', $data);
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
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'semester_form';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $this->model->where('id', $uid)->get();
        
        $data['default']['semester'] = $this->model->name;
        $data['default']['desc']       = $this->model->desc;
        $data['default']['status']     = $this->model->active;

	$this->session->set_userdata('curid', $uid);
        $this->load->view('semester_form', $data);
    }
    
    function active($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->model->update('active', 0);
        $this->model->where('id', $uid)->get();
        $this->model->active = 1;
        $this->model->save();
        redirect($this->title);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'semester_form';
	$data['form_action'] = site_url($this->title.'/update_process');
        
        $data['dept']    = $this->dept->combo(); 
        $data['faculty'] = $this->faculty->combo();
        $data['grade']   = $this->grade->combo(); 

	// Form validation
        $this->form_validation->set_rules('tsemester', 'Generation', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', '');
        $this->form_validation->set_rules('cstatus', 'Status', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {   
            $this->model->where('id', $this->session->userdata('curid'))->get();
            
            $this->model->desc = $this->input->post('tdesc');
            $this->model->active = $this->input->post('cstatus');
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true'; 
        }
        else { $this->load->view('semester_form', $data); }
        
       
    }

    public function valid_semester($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_semester', "Semester [$name] - Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function report()
    {
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1'] = $this->properti['phone1'];
        $data['phone2'] = $this->properti['phone2'];
        $data['fax'] = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email'] = $this->properti['email'];
                
        $data['result'] = $this->model->get();
        $this->load->view('semester_report', $data);
    }

}

?>