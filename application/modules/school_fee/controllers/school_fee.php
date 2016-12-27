<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class School_fee extends MX_Controller
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
        $this->dept = $this->load->library('dept_lib');
        $this->level = new Level_lib();

        $this->model = new Fee();
    }

    private $properti, $modul, $title, $account, $dept;
    private $user,$journalgl,$currency,$model,$level;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last();
    }
    
    private function def($val){ if ($val == 0){return 'N';}else{ return 'Y'; }}

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'fee_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('students','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        $data['level'] = $this->level->combo_all();
        
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
            

            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Name', 'Department', 'Grade', 'Registration', 'Development', 'School', 'OSIS', 'Computer', 'Practical', 'Others', 'Aid', 'P1', 'Default', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, $cost->name, $this->dept->get_name($cost->dept_id), $this->grade($cost->grade), number_format($cost->registration), number_format($cost->development), number_format($cost->school), number_format($cost->osis), number_format($cost->computer), number_format($cost->practice), number_format($cost->others), number_format($cost->aid), number_format($cost->p1), $this->def($cost->default),
                    anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'fee_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        $data['level'] = $this->level->combo_all();

	// ---------------------------------------- //
        if ($this->input->post('cgrade'))
        { $costs = $this->model->where('dept_id', $this->input->post('cdept'))->where('grade', $this->input->post('cgrade'))->get();}
        else { $costs = $this->model->where('dept_id', $this->input->post('cdept'))->get(); }
        
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Name', 'Department', 'Grade', 'Registration', 'Development', 'School', 'OSIS', 'Computer', 'Practical', 'Others', 'Aid', 'P1', 'Default', 'Action');

        $i = 0;
        foreach ($costs as $cost)
        {
            $this->table->add_row
            (
                ++$i, $cost->name, $this->dept->get_name($cost->dept_id), $this->grade($cost->grade), number_format($cost->registration), number_format($cost->development), number_format($cost->school), number_format($cost->osis), number_format($cost->computer), number_format($cost->practice), number_format($cost->others), number_format($cost->aid), number_format($cost->p1), $this->def($cost->default),
                anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    
    private function grade($val=0)
    {
        switch ($val) 
        {
          case 0: $val = '-'; break;  
          case 1: $val = 'I'; break;
          case 2: $val = 'II'; break;
          case 3: $val = 'III'; break;
        }
        return $val;
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'fee_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['dept'] = $this->dept->combo();
        $data['level'] = $this->level->combo();
        
        $this->load->view('fee_form', $data);
    }
   
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'fee_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['dept'] = $this->dept->combo();
        $data['level'] = $this->level->combo();
        
	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('cdept', 'Department', 'required|callback_valid_fee');
        $this->form_validation->set_rules('treg', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tdev', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tschool', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tosis', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tcom', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tother', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('taid', 'Foundation Aid', 'required|numeric');
        $this->form_validation->set_rules('tp1', 'P1', 'required|numeric');
        $this->form_validation->set_rules('cdef', 'Default', 'callback_valid_default');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name         = $this->input->post('tname');
            $this->model->dept_id      = $this->input->post('cdept');
            $this->model->grade        = $this->input->post('cgrade');
            $this->model->registration = $this->input->post('treg');
            $this->model->development  = $this->input->post('tdev');
            $this->model->school       = $this->input->post('tschool');
            $this->model->osis         = $this->input->post('tosis');
            $this->model->practice     = $this->input->post('tpractice');
            $this->model->computer     = $this->input->post('tcom');
            $this->model->others       = $this->input->post('tother');
            $this->model->aid          = $this->input->post('taid');
            $this->model->p1           = $this->input->post('tp1');
            $this->model->default      = $this->input->post('cdef');
            
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/');
            echo 'true';
        }
        else
        {
//               $this->load->view('fee_form', $data);
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
        $data['main_view'] = 'fee_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));

        $data['dept'] = $this->dept->combo();
        $data['level'] = $this->level->combo();
        $cost = $this->model->where('id', $uid)->get();
        
        $data['default']['name']     = $cost->name;
        $data['default']['dept']     = $cost->dept_id;
        $data['default']['grade']    = $cost->grade;
        $data['default']['reg']      = $cost->registration;
        $data['default']['dev']      = $cost->development;
        $data['default']['school']   = $cost->school;
        $data['default']['osis']     = $cost->osis;
        $data['default']['com']      = $cost->computer;
        $data['default']['practice'] = $cost->practice;
        $data['default']['other']    = $cost->others;
        $data['default']['aid']      = $cost->aid;
        $data['default']['p1']       = $cost->p1;
        $data['default']['def']      = $cost->default;
        
	$this->session->set_userdata('curid', $cost->id);
        $this->load->view('fee_form', $data);
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
                
        $data['results'] = $this->model->order_by('dept_id','desc')->get();
        
        $this->load->view('fee_report', $data);
    }
    
    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'fee_form';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $data['dept'] = $this->dept->combo();
        $data['level'] = $this->level->combo();
        
	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('cdept', 'Department', 'required|callback_validating_fee['.$this->input->post('cgrade').']');
        $this->form_validation->set_rules('treg', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tdev', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tschool', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tosis', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tcom', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tother', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('taid', 'Aid Foundation', 'required|numeric');
        $this->form_validation->set_rules('tp1', 'P1', 'required|numeric');
        $this->form_validation->set_rules('cdef', 'Default', 'callback_validating_default');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id',$this->session->userdata('curid'))->get();
            
            $this->model->name         = $this->input->post('tname');
            $this->model->dept_id      = $this->input->post('cdept');
            $this->model->grade        = $this->input->post('cgrade');
            $this->model->registration = $this->input->post('treg');
            $this->model->development  = $this->input->post('tdev');
            $this->model->school       = $this->input->post('tschool');
            $this->model->osis         = $this->input->post('tosis');
            $this->model->practice     = $this->input->post('tpractice');
            $this->model->computer     = $this->input->post('tcom');
            $this->model->others       = $this->input->post('tother');
            $this->model->aid          = $this->input->post('taid');
            $this->model->p1           = $this->input->post('tp1');
            $this->model->default      = $this->input->post('cdef');
            
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->session->unset_userdata('curid');
            echo 'true';
        }
        else
        {
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->load->view('fee_form', $data);
            echo validation_errors();
        }
    }
    
    public function valid_default($def)
    {
        $grade = $this->input->post('cgrade');
        $dept = $this->input->post('cdept');
        
        $this->model->where('grade', $grade);
        $this->model->where('default', $def);
        $val = $this->model->where('dept_id', $dept)->count();
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_default', "Invalid Default..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_default($def)
    {
        $grade = $this->input->post('cgrade');
        $dept = $this->input->post('cdept');
        
        $this->model->where('grade', $grade);
        $this->model->where('default', $def);
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('dept_id', $dept)->count();
        if ($val > 0)
        {
            $this->form_validation->set_message('validating_default', "Invalid Default..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_fee($dept)
    {
        $grade = $this->input->post('cgrade');
        $name  = $this->input->post('tname');
        
//        $this->model->where('grade', $grade);
        $val = $this->model->where('name', $name)->count();
//        $val = $this->model->where('dept_id', $dept)->count();
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_fee', "Invalid Fee..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_fee($dept,$grade)
    {
        $name  = $this->input->post('tname');
        $this->model->where_not_in('id', $this->session->userdata('curid'));
//        $this->model->where('grade', $grade);
//        $this->model->where('name', $name);
        $val = $this->model->where('name', $name)->count();
        
        if ($val > 0)
        {
            $this->form_validation->set_message('validating_fee', "Invalid Fee..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
   

}

?>