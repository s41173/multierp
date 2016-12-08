<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grade extends MX_Controller
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
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account  = $this->load->library('account_lib');
        $this->faculty  = new Faculty_lib();
        $this->dept = new Dept_lib();
        $this->fee = new Regcost_lib();
        $this->employee = new Employee_lib();
        $this->level = new Level_lib();

        $this->load->model('Grade_model', 'model', TRUE);
    }

    private $properti, $modul, $title, $account, $fee, $employee;
    private $user,$journalgl,$currency,$faculty,$dept, $level;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last();
    }
    
    function active($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        
        $value = $this->model->get_grade_by_id($uid)->row();
        if ($value->active == 0){ $val = '1'; }else{ $val = '0'; }
        
        $res = array('active' => $val);
        $this->model->update($uid,$res);
//        
        redirect($this->title);
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'grade_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('students','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        $data['faculty'] = $this->faculty->combo_code();
        $data['dept'] = $this->dept->combo_all();
        $data['level'] = $this->level->combo_all();

	// ---------------------------------------- //
        $costs = $this->model->get($this->modul['limit'], $offset)->result();
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
            $this->table->set_heading('No', 'Department', 'Faculty', 'Level', 'Name', 'Capacity', 'Filled', 'Practice', 'Instructor', 'Fee Type', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, $this->dept->get_name($cost->dept_id), $this->faculty->get_name($cost->faculty_id), $cost->level, $cost->name, $cost->capacity, $this->filled($cost->grade_id), $this->stts($cost->practice), $cost->instructor, $this->fee->get_name($cost->fee),
                    anchor_popup($this->title.'/details/'.$cost->grade_id,'<span>print</span>',array('class' => 'details1', 'title' => '')).' &nbsp;'.    
                    anchor($this->title.'/active/'.$cost->grade_id,'<span>update</span>',array('class' => $this->post_status($cost->active), 'title' => 'edit / update')).' '.
                    anchor($this->title.'/update/'.$cost->grade_id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cost->grade_id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
    
    function filled($uid)
    {
        $st = new Student_lib();
        return $st->total_student_active($uid);
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'grade_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['faculty'] = $this->faculty->combo_all();
        $data['dept'] = $this->dept->combo_all();
        $data['level'] = $this->level->combo_all();

        $dept = $this->input->post('cdept');
        $level = $this->input->post('clevel');
        $faculty = $this->input->post('cfaculty');
        
	// ---------------------------------------- //
        $costs = $this->model->search($dept,$level,$faculty)->result();
	    
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Department', 'Faculty', 'Level', 'Name', 'Capacity', 'Filled', 'Practice', 'Instructor', 'Fee Type', 'Action');

        $i = 0;
        foreach ($costs as $cost)
        {
            $this->table->add_row
            (
                ++$i, $this->dept->get_name($cost->dept_id), $this->faculty->get_name($cost->faculty_id), $cost->level, $cost->name, $cost->capacity, $this->filled($cost->grade_id), $this->stts($cost->practice), $cost->instructor, $this->fee->get_name($cost->fee),
                anchor_popup($this->title.'/details/'.$cost->grade_id,'<span>print</span>',array('class' => 'details1', 'title' => '')).' &nbsp;'.    
                anchor($this->title.'/active/'.$cost->grade_id,'<span>update</span>',array('class' => $this->post_status($cost->active), 'title' => 'edit / update')).' '.
                anchor($this->title.'/update/'.$cost->grade_id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$cost->grade_id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        
        $data['table'] = $this->table->generate();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function details($uid)
    {
       $this->acl->otentikasi1($this->title); 
       $value = $this->model->get_grade_by_id($uid)->row();
       $st = new Student_lib();
       
        // properti
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $data['company'] = $this->properti['name']; 
        $data['dept'] = $this->dept->get_name($value->dept_id);
        $data['grade'] = $value->name;
        $data['faculty'] = $this->faculty->get_name($value->faculty_id);
        
        $year = new Financial_lib();
        $data['year'] = $year->get();
        
        $data['results'] = $st->get_by_grade($uid);
        $this->load->view('grade_details', $data);
    }
    
     private function post_status($val=null)
    { $class = 'notapprove'; if ($val) {$class = "approve"; }elseif ($val == 1){$class = "notapprove"; } return $class; }
    
    private function stts($val)
    { if ($val == 0){ return "N"; } else{ return 'Y'; } }
   
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'fee_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['dept'] = $this->dept->combo();
        $data['fee'] = $this->fee->combo();
        $data['level'] = $this->level->combo();
        
        $this->load->view('grade_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cost_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('clevel', 'Level', 'required');
        $this->form_validation->set_rules('cfee', 'Fee Type', 'required');
        $this->form_validation->set_rules('cgrade', 'Grade', 'required|callback_valid_grade');
        $this->form_validation->set_rules('cfaculty', 'Faculty', 'required');
        $this->form_validation->set_rules('tno', 'No', 'required');
        $this->form_validation->set_rules('tcapacity', 'Capacity', 'required|numeric');
        $this->form_validation->set_rules('tinstructor', 'Instructor', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $val = array('name' => $this->input->post('cgrade').'-'.$this->faculty->get_code($this->input->post('cfaculty')).'-'.$this->input->post('tno'),
                         'level' => $this->input->post('clevel'),
                         'fee' => $this->input->post('cfee'),
                         'instructor' => $this->input->post('tinstructor'),
                         'faculty_id' => $this->input->post('cfaculty'),
                         'desc' => $this->input->post('tdesc'), 'capacity' => $this->input->post('tcapacity'),
                         'dept_id' => $this->input->post('cdept'), 'practice' => $this->input->post('cstts'));
            
            $this->model->add($val);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/');
            echo 'true';
        }
        else
        {
//               $this->load->view('template', $data);
            echo validation_errors();
        }

    }
    
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'fee_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));

        $grade = $this->model->get_grade_by_id($uid)->row();
        
        $data['dept'] = $this->dept->combo();
        $data['fee'] = $this->fee->combo_criteria($grade->dept_id);
        $data['level'] = $this->level->combo();
//        $data['fee'] = $this->fee->combo();
        $data['faculty'] = $this->faculty->combo_criteria($grade->dept_id);
        $res = explode('-', $grade->name);
        
        $data['default']['dept']       = $this->dept->get_name($grade->dept_id);
        $data['default']['name']       = $grade->name;
        $data['default']['level']      = $grade->level;
        $data['default']['grade']      = $res[0];
        $data['default']['no']         = $res[2];
        $data['default']['faculty']    = $this->faculty->get_code($grade->faculty_id);
        $data['default']['fee']        = $grade->fee;
        $data['default']['instructor'] = $grade->instructor;
        $data['default']['desc']       = $grade->desc;
        $data['default']['stts']       = $grade->practice;
        $data['default']['capacity']   = $grade->capacity;
        
	$this->session->set_userdata('curid', $grade->grade_id);
        $this->load->view('grade_update', $data);
    }
    
    function update_process()
    {
       $this->acl->otentikasi2($this->title);

       $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
       $data['h2title'] = $this->modul['title'];
       $data['main_view'] = 'cost_view';
       $data['form_action'] = site_url($this->title.'/update_process');

	// Form validation
        $this->form_validation->set_rules('cfee', 'Fee Type', 'required');
        $this->form_validation->set_rules('cgrade', 'Grade', 'required|callback_validating_grade');
        $this->form_validation->set_rules('cfaculty', 'Faculty', 'required');
        $this->form_validation->set_rules('tno', 'No', 'required');
        $this->form_validation->set_rules('clevel', 'Level', 'required|numeric');
        $this->form_validation->set_rules('tinstructor', 'Instructor', 'required');
        $this->form_validation->set_rules('tcapacity', 'Capacity', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            
            $val = array('name' => $this->input->post('cgrade').'-'.$this->input->post('cfaculty').'-'.$this->input->post('tno'),
                         'fee' => $this->input->post('cfee'),
                         'level' => $this->input->post('clevel'),
                         'instructor' => $this->input->post('tinstructor'),
                         'faculty_id' => $this->faculty->get_id_by_code($this->input->post('cfaculty')),
                         'desc' => $this->input->post('tdesc'), 'capacity' => $this->input->post('tcapacity'),
                         'practice' => $this->input->post('cstts'));
            
            $this->model->update($this->session->userdata('curid'),$val);
            
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->session->unset_userdata('curid');
            echo 'true';
        }
        else
        {
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->load->view('grade_update', $data);
            echo validation_errors();
        }
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $st = new Student_lib();
        if ($st->cek_relation($uid, 'grade_id') == TRUE)
        {
            $this->model->delete($uid);
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "$this->title related to another component..!"); }        
        redirect($this->title);
    }

    public function valid_grade($grade)
    {
        $name = $grade.'-'.$this->input->post('cfaculty').'-'.$this->input->post('tno');
        if ($this->model->valid_grade($name) == FALSE)
        {
            $this->form_validation->set_message('valid_grade', "Invalid Grade..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_grade($grade)
    {
        $name = $grade.'-'.$this->input->post('cfaculty').'-'.$this->input->post('tno');
        if ($this->model->validating_grade($name,$this->session->userdata('curid')) == FALSE)
        {
            $this->form_validation->set_message('validating_grade', "Invalid Grade..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
   
    function report()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        
        $data['dept'] = $this->dept->combo_all();
        $data['fee'] = $this->fee->combo();
        
        $this->load->view('grade_report_panel', $data);
    }
    
    function report_process()
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
        
        $dept    = $this->input->post('cdept');
        $level   = $this->input->post('clevel');
        $faculty = $this->input->post('cfaculty');
        $fee     = $this->input->post('cfee');
                
        $data['results'] = $this->model->search($dept,$level,$faculty,$fee)->result();
        
        $this->load->view('grade_report', $data);
    }

}

?>