<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Regcost extends MX_Controller
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
        $this->model = new Regcosts();
        $this->level = new Level_lib();
    }

    private $properti, $modul, $title,$dept,$employee;
    private $user,$currency,$model,$level;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
       $this->get_last();
    }
    
    function autocomplete()
    {
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('students')->like('name',$keyword,'after')->get();

      // format keluaran di dalam array
      foreach($data->result() as $row)
      {
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>$row->name,
            'data'   =>$row->students_id
         );
      }

      // minimal PHP 5.2
      echo json_encode($arr);
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'regcost_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('registration_reference/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
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
            $this->table->set_heading('No', 'Department', 'Level', 'Registration', 'Development', 'School', 'OSIS', 'Practice', 'Others', 'P1', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, $this->dept->get_name($res->dept_id), $res->level, number_format($res->registration), number_format($res->development), number_format($res->school), number_format($res->osis), number_format($res->practice), number_format($res->others), number_format($res->p1),
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
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'honorfee_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all();
	// ---------------------------------------- //
        $result = $this->hm->search($this->input->post('ctype'),$this->input->post('cdept'))->result();
  
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Department', 'Time Work', 'Amount', '#');
//
        $i = 0;
        foreach ($result as $res)
        {
            $this->table->add_row
            (
                ++$i, $this->dept->get_name($res->dept), $res->work_time, number_format($res->amount),
                anchor($this->title.'/update/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
//
        $data['table'] = $this->table->generate();
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
        
        $data['dept'] = $this->dept->combo();
        $data['level'] = $this->level->combo();
        $this->load->view('regcost_form', $data);
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
        $data['level'] = $this->level->combo();
        
	// Form validation
        $this->form_validation->set_rules('cdept', 'Department', 'required|callback_valid_cost');
        $this->form_validation->set_rules('clevel', 'Level', 'required|numeric');
        $this->form_validation->set_rules('tregistration', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tdevelopment', 'Development', 'required|numeric');
        $this->form_validation->set_rules('tschool', 'School', 'required|numeric');
        $this->form_validation->set_rules('tosis', 'Osis', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Practice', 'required|numeric');
        $this->form_validation->set_rules('tother', 'Other Cost', 'required|numeric');
        $this->form_validation->set_rules('tp1', 'P1', 'required|numeric');
        
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->dept_id      = $this->input->post('cdept');
            $this->model->level        = $this->input->post('clevel');
            $this->model->registration = $this->input->post('tregistration');
            $this->model->development  = $this->input->post('tdevelopment');
            $this->model->school       = $this->input->post('tschool');
            $this->model->osis         = $this->input->post('tosis');
            $this->model->computer     = $this->input->post('tcomputer');
            $this->model->practice     = $this->input->post('tpractice');
            $this->model->others       = $this->input->post('tother');
            $this->model->p1           = $this->input->post('tp1');
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
        
        $data['default']['dept']         = $this->dept->get_name($this->model->dept_id);
        $data['default']['level']        = $this->model->level;
        $data['default']['registration'] = $this->model->registration;
        $data['default']['development']  = $this->model->development;
        $data['default']['school']       = $this->model->school;
        $data['default']['osis']         = $this->model->osis;
        $data['default']['computer']     = $this->model->computer;
        $data['default']['practice']     = $this->model->practice;
        $data['default']['other']        = $this->model->others;
        $data['default']['p1']           = $this->model->p1;
        
	$this->session->set_userdata('curid', $this->model->id);
        $this->load->view('regcost_update', $data);
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
        $this->form_validation->set_rules('tregistration', 'Registration', 'required|numeric');
        $this->form_validation->set_rules('tdevelopment', 'Development', 'required|numeric');
        $this->form_validation->set_rules('tschool', 'School', 'required|numeric');
        $this->form_validation->set_rules('tosis', 'Osis', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Practice', 'required|numeric');
        $this->form_validation->set_rules('tother', 'Other Cost', 'required|numeric');
        $this->form_validation->set_rules('tp1', 'P1', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();
            
            $this->model->registration = $this->input->post('tregistration');
            $this->model->development  = $this->input->post('tdevelopment');
            $this->model->school       = $this->input->post('tschool');
            $this->model->osis         = $this->input->post('tosis');
            $this->model->computer     = $this->input->post('tcomputer');
            $this->model->practice     = $this->input->post('tpractice');
            $this->model->others       = $this->input->post('tother');
            $this->model->p1           = $this->input->post('tp1');
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
    
    public function valid_cost($dept)
    {
        $val = $this->model->where('dept_id', $dept)->where('level', $this->input->post('clevel'))->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_cost', "Cost type - already registered..!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
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
        
        $this->load->view('regcost_report', $data);
    }

}

?>