<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Honor_absence extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Honor_absence_model', 'hm', TRUE);

        $this->properti = $this->property->get();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->dept = $this->load->library('dept_lib');
        $this->employee = $this->load->library('employee_lib');
        $this->model = new Honor_absences();
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
        $data['main_view'] = 'honor_absence_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('payroll_reference/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
        $p = new Period();
        $p->get();
        
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
            $this->table->set_heading('No', 'NIP', 'Name', 'Department', 'Worked Hours', 'Experience', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, $this->employee->get_nip($res->employee_id), $this->employee->get_name($res->employee_id), $this->dept->get_name($res->dept), $res->hours, $res->work_time,
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
        $data['main_view'] = 'honor_absence_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all();
	// ---------------------------------------- //
        $result = $this->hm->search($this->employee->get_id_by_nip($this->input->post('tnip')),  $this->input->post('cdept'))->result();
  
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'NIP', 'Name', 'Department', 'Worked Hours', 'Experience', '#');
//
        $i = 0;
        foreach ($result as $res)
        {
            $this->table->add_row
            (
                ++$i, $this->employee->get_nip($res->employee_id), $this->employee->get_name($res->employee_id), $this->dept->get_name($res->dept), $res->hours, $res->work_time,
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
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'honor_absence_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        $this->load->view('honor_absence_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'honor_absence_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
         
        $data['dept'] = $this->dept->combo(); 
        
	// Form validation
        $this->form_validation->set_rules('tnip', 'Employee Nip', 'required|numeric|callback_valid_absence');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('thour', 'Attendance Hours', 'required|numeric');
        $this->form_validation->set_rules('ctime', 'Experience', 'required');
        
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->employee_id  = $this->employee->get_id_by_nip($this->input->post('tnip'));
            $this->model->dept         = $this->input->post('cdept');
            $this->model->hours        = $this->input->post('thour');
            $this->model->work_time    = $this->input->post('ctime');
            
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add');
            echo 'true';
        }
        else
        { 
            //$this->load->view('honor_absence_form', $data); 
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
    
    function import()
    {
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'attendance_form';
	$data['form_action'] = site_url($this->title.'/import_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        $data['error']  = '';
        $this->load->view('attendance_import', $data);
    }
    
    function import_process()
    {
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'attendance_import';
	$data['form_action'] = site_url($this->title.'/import_process');
        $data['error'] = null;
	
        $this->form_validation->set_rules('userfile', 'Import File', '');
        
        if ($this->form_validation->run($this) == TRUE)
        {
             // ==================== upload ========================
            
            $config['upload_path']   = './uploads/';
            $config['file_name']     = 'honor_attendance';
            $config['allowed_types'] = 'csv';
            $config['overwrite']     = TRUE;
            $config['max_size']	     = '1000';
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            
            if ( !$this->upload->do_upload("userfile"))
            { 
               $data['error'] = $this->upload->display_errors(); 
               $this->load->view('attendance_import', $data);
            }
            else
            { 
               // success page 
              $this->import_attendance($config['file_name'].'.csv');
              $info = $this->upload->data(); 
              
              $this->session->set_flashdata('message', "One $this->title data successfully imported!");
              redirect($this->title.'/import');
            }                
        }
        else { $this->load->view('attendance_import', $data); }
        
    }
    
    private function import_attendance($filename)
    {
        $this->load->helper('file');
        $att = new Honor_attendance_lib();
        $csvreader = new CSVReader();
        $filename = './uploads/'.$filename;
        
        $result = $csvreader->parse_file($filename);
        
        foreach($result as $res)
        {
           if(isset($res['CODE']) && isset($res['DEPT']) && isset($res['HOURS']) && isset($res['TIME']))
           {  
             if ($this->validation_import($res['CODE'],$res['DEPT'],$res['HOURS'],$res['TIME']) == TRUE)
             {  
//                echo 'benar <br>' ;
               $att->save($this->employee->get_id_by_att($res['CODE']), $this->dept->get_id($res['DEPT']), intval($res['HOURS']), $res['TIME']); 
             }  
//             else { echo 'salah<br>'; }
           } 
//           else { echo 'salah format <br>'; }
        }
    }
    
    private function validation_import($code=null,$dept=null,$hours=null,$time=null)
    {
        $res[0] = FALSE;
        $res[1] = FALSE;
        $res[2] = FALSE;
        $res[3] = FALSE;
        
        if ($this->employee->get_id_by_att($code)){ $res[0] = TRUE;}
        if ($this->dept->get_id($dept)){ $res[1] = TRUE; }
        if ($hours > 0){ $res[2] = TRUE; }
        
        switch ($time)
	{
           case '0': $res[3] = TRUE; break; case '1-5': $res[3] = TRUE; break; case '>5':  $res[3] = TRUE; break;
           default: $res[3] = FALSE;
	}
        
        if ($res[0] == TRUE && $res[1] == TRUE && $res[2] == TRUE && $res[3] == TRUE){ return TRUE; }else { return FALSE; }
    }
    
    function update($uid)
    {
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'honor_absence_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo();
        
        $this->model->where('id', $uid)->get();
        
        $data['default']['employee']  = $this->employee->get_name($this->model->employee_id);
        $data['default']['nip']       = $this->employee->get_nip($this->model->employee_id);
        $data['default']['dept']      = $this->model->dept;
        $data['default']['hour']      = $this->model->hours;
        $data['default']['time']      = $this->model->work_time;
        
	$this->session->set_userdata('curid', $this->model->id);
        $this->load->view('honor_absence_update', $data);
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
        $this->form_validation->set_rules('tnip', 'Employee Nip', 'required|numeric|callback_validating_absence');
        $this->form_validation->set_rules('thour', 'Attendance Hours', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();
            
            $this->model->hours     = $this->input->post('thour');
            $this->model->dept      = $this->input->post('cdept');
            $this->model->work_time = $this->input->post('ctime');
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            
            echo 'true'; 
        }
        else
        {
//            $this->load->view('honor_absence_update', $data);
           echo validation_errors();
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
        }
        
        $this->session->unset_userdata('curid');
    }
    
    public function valid_absence($nip)
    {
        $employee = $this->employee->get_id_by_nip($nip);
        
        $this->model->where('dept', $this->input->post('cdept'));
        $val = $this->model->where('employee_id', $employee)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_absence', "Employee [$nip] already registered..!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function validating_absence($nip)
    {
        $employee = $this->employee->get_id_by_nip($nip);
        
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $this->model->where('dept', $this->input->post('cdept'));
        $val = $this->model->where('employee_id', $employee)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_absence', "Employee [$nip] already registered..!");
            return FALSE;
        }
        else {  return TRUE; }
    }
     
    public function valid_period($month=0,$year=0)
    {
        $p = new Period();
        $p->get();

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_year($year=0)
    {
        $p = new Period();
        $p->get();

        if ( intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_year', "Invalid Year.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    
    public function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all(); 
        
        $this->load->view('honor_absence_report_panel', $data);
    }
    
    public function report_process()
    {
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        
        $data['month'] = $this->input->post('cmonth');
        $data['year'] = $this->input->post('tyear');
                
        $data['results'] = $this->hm->search(null, $this->input->post('cdept'),  $this->input->post('ctime'))->result();
        
        $this->load->view('honor_absence_report', $data);
    }

}

?>