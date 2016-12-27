<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Students extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Students_model', 'model', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account  = $this->load->library('account_lib');
        $this->dept = new Dept_lib();
        $this->faculty = $this->load->library('faculty_lib');
        $this->student = new Student_lib();
        $this->finance = $this->load->library('financial_lib');
        $this->grade   = new Grade_lib();
        $this->recap = new Student_recap_trans_lib();
    }

    private $properti, $modul, $title, $account,$dept,$faculty,$recap;
    private $user,$journalgl,$currency,$student,$finance,$grade;

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
      $data = $this->db->from('students')->like('name',$keyword,'after')->where('active', 1)->get();

      // format keluaran di dalam array
      foreach($data->result() as $row)
      { 
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>$row->name.' - '.$row->nisn,
            'data'   =>$this->dept->get_name($row->dept_id).'|'.$this->grade->get_name($row->grade_id).'|'.$row->dept_id.'|'.$row->grade_id.'|'.$row->students_id
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
        $data['main_view'] = 'student_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_select'] = site_url($this->title.'/select');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade'] = $this->grade->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
	// ---------------------------------------- //
        $costs = $this->model->get($this->modul['limit'], $offset,  $this->finance->get())->result();
        $num_rows = $this->model->count($this->finance->get());

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
            $this->table->set_heading('#','No', 'NISN', 'Name', 'Dept', 'Faculty / Grade', 'Genre', 'Joined', '#');
//
            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cost->students_id,'checked'=> FALSE, 'style'=> 'margin:0px');   
                $this->table->add_row
                (
                   
                    form_checkbox($datax), ++$i, $cost->nisn, strtoupper($cost->name), $cost->dept, $cost->faculty.' / '.$cost->grade, strtoupper($cost->genre), tglin($cost->joined),
                    anchor_popup($this->title.'/details/'.$cost->students_id,'<span>print</span>',array('class' => 'print', 'title' => ''), $this->atts).' '.    
                    anchor($this->title.'/update/'.$cost->students_id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                    anchor($this->title.'/inactive/'.$cost->students_id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'student_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_select'] = site_url($this->title.'/select');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade'] = $this->grade->combo_all();
        
	// ---------------------------------------- //
        $costs = $this->model->search($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'),
                                      $this->input->post('tvalue'),$this->input->post('ctype'))->result();
  
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'NISN', 'Name', 'Dept', 'Faculty / Grade', 'Genre', '#');
//
        $i = 0;
        foreach ($costs as $cost)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cost->students_id,'checked'=> FALSE, 'style'=> 'margin:0px');   
            $this->table->add_row
            (

                form_checkbox($datax), ++$i, $cost->nisn, strtoupper($cost->name), $cost->dept, $cost->faculty.' / '.$cost->grade, strtoupper($cost->genre),
                anchor_popup($this->title.'/details/'.$cost->students_id,'<span>print</span>',array('class' => 'print', 'title' => ''), $this->atts).' '.    
                anchor($this->title.'/update/'.$cost->students_id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                anchor($this->title.'/inactive/'.$cost->students_id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
//
        $data['table'] = $this->table->generate();
        
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function post_status($val=null)
    { $class = 'notapprove'; if ($val) {$class = "approve"; }elseif ($val == 1){$class = "notapprove"; } return $class; }
    
    
    function details($id)
    {
        $st = new Student();
        $st->where('students_id', $id)->get();
        $data['name'] = $st->name;
        $data['nis']  = $st->nisn;
        $data['dept'] = $this->dept->get_name($st->dept_id);
        $data['faculty'] = $this->faculty->get_code($st->faculty);
        $data['grade'] = $this->grade->get_name($st->grade_id);
        $data['genre'] = $st->genre;
        
        $data['company'] = $this->properti['name'];
                
        $this->load->view('student_invoice', $data);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_form';
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['default']['nis'] = '0'.$this->counter();
        $data['dept']    = $this->dept->combo(); 
        $data['faculty'] = $this->faculty->combo();
        $data['grade']   = $this->grade->combo(); 
        
        $data['form_action'] = site_url($this->title.'/add_process/');
        
        $this->load->view('student_form', $data);
    }
    
    private function counter()
    {
        $o = new Student();
        $o->select_max('students_id')->get();
        return intval($o->students_id+1);
    }
    
    function add_process()
    {
//        
	// Form validation
        $this->form_validation->set_rules('tnis', 'NISN', 'required|numeric|callback_valid_nisn');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_name['.$this->input->post('tnis').']');
        $this->form_validation->set_rules('cfaculty', 'Faculty', 'required');
        $this->form_validation->set_rules('cgrade', 'Grade', 'required');
        $this->form_validation->set_rules('cgenre', 'Genre', 'required');
        $this->form_validation->set_rules('tdate', 'Join Date', 'required|callback_valid_period');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $st = new Student();
            
            $st->nisn = $this->input->post('tnis');
            $st->name = $this->input->post('tname');
            $st->dept_id  = $this->input->post('cdept');
            $st->faculty  = $this->input->post('cfaculty');
            $st->grade_id = $this->input->post('cgrade');
            $st->genre    = $this->input->post('cgenre');
            $st->joined   = $this->input->post('tdate');
            $st->resign   = intval(date('Y', strtotime($this->input->post('tdate')))+20).'-12-31';
            $st->active   = 1;
            
            $st->save();
            
            // add trans recap
            $ps = new Period();
            $ps->get();
            $this->recap->add_trans($this->student->get_id_by_no($this->input->post('tnis')),$this->input->post('cdept'), $this->input->post('cgrade'), $this->input->post('tdate'), 'in', 1, $ps->month, $ps->year, 'REG:'.tglin($this->input->post('tdate')), 'Registration');
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/'.$this->input->post('cdept').'/'.$this->input->post('cfaculty').'/'.$this->input->post('cgrade'));
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
        $this->model->delete($uid);
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        
        redirect($this->title);
    }
    
    function select()
    {
        if ($this->input->post('submit') == 'TRANSFER')
        { $this->transfer(); }  else{  $this->inactive_all(); } 
    }
    
    function inactive_all()
    {
      $this->acl->otentikasi_admin($this->title);
      
      $inv = new Inactive_lib();  
      $cek = $this->input->post('cek');
      $st = new Student();

      if($cek)
      {
        $jumlah = count($cek);
        for ($i=0; $i<$jumlah; $i++)
        {
            $name = $st->where('students_id',$cek[$i])->get()->name;
            $val = array('active' => 0);
            $st->where('students_id ', $cek[$i])->update($val, TRUE);
            $inv->add($cek[$i], $cek[$i], 'INV', $this->session->userdata('log'));
//            $this->remove_recap($cek[$i]);
            $this->session->set_flashdata('message', "$jumlah $this->title successfully inactived..!");
        }
      }
      else
      { $this->session->set_flashdata('message', "No $this->title Selected..!!"); }
      redirect($this->title);
    }
    
    function transfer()
    {
      $cek = $this->input->post('cek');
      $st = new Student();
      
      $dept   = $this->input->post('cdept');
      $fac    = $this->input->post('cfaculty');
      $grade  = $this->input->post('cgrade');

      if($cek)
      {
        $jumlah = count($cek);
        for ($i=0; $i<$jumlah; $i++)
        {
            //$name = $st->where('students_id',$cek[$i])->get()->name;
            $val = array('dept_id' => $dept, 'faculty' => $fac, 'grade_id' => $grade);
            $st->where('students_id ', $cek[$i])->update($val, TRUE);

            $this->session->set_flashdata('message', "$jumlah $this->title successfully transfered..!");
        }
      }
      else
      { $this->session->set_flashdata('message', "No $this->title Selected..!!"); }
      redirect($this->title);
    }
    
    
    function update($uid)
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept']    = $this->dept->combo(); 
        $data['faculty'] = $this->faculty->combo();
        $data['grade']   = $this->grade->combo(); 
        
        $st = new Student();
        $st->where('students_id', $uid)->get();
        
        $data['default']['name']    = $st->name;
        $data['default']['nis']     = $st->nisn;
        $data['default']['dept']    = $st->dept_id;
        $data['default']['faculty'] = $st->faculty;
        $data['default']['grade']   = $st->grade_id;
        $data['default']['genre']   = $st->genre;

	$this->session->set_userdata('curid', $st->students_id);
        $this->load->view('student_update', $data);
    }
    
    function inactive($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        
        $inv = new Inactive_lib();
        $st = new Student();
        $name = $st->where('students_id',$uid)->get()->name;
        $val = array('active' => 0);
        $st->where('students_id ', $uid)->update($val, TRUE);
        
        $inv->add($uid, $uid, 'INV', $this->session->userdata('log'));
        $this->session->set_flashdata('message', "1 $this->title [$name] successfully inactived..!");
//        $this->remove_recap($uid);
        redirect($this->title);
    }
    
    private function remove_recap($student)
    {
       $dept = $this->student->get_dept($student);
       $grade = $this->student->get_grade($student);
       
       // add trans recap
       $ps = new Period();
       $ps->get();
       $this->recap->min_trans($dept, $grade, date('Y-m-d'), 'out', 1, $ps->month, $ps->year, 'DEL:'.date('d-m-Y'), 'Inactive');
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_update';
	$data['form_action'] = site_url($this->title.'/update_process');
        
        $data['dept']    = $this->dept->combo(); 
        $data['faculty'] = $this->faculty->combo();
        $data['grade']   = $this->grade->combo(); 

	// Form validation
        $this->form_validation->set_rules('tnis', 'NISN', 'required|numeric|callback_validating_nisn');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validating_name['.$this->input->post('tnis').']');
        $this->form_validation->set_rules('cfaculty', 'Faculty', 'required');
        $this->form_validation->set_rules('cgrade', 'Grade', 'required');
        $this->form_validation->set_rules('cgenre', 'Genre', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $st = new Student();
            
            $val = array('nisn' => $this->input->post('tnis'), 'name' => $this->input->post('tname'),
                         'dept_id' => $this->input->post('cdept'), 'faculty' => $this->input->post('cfaculty'),
                         'grade_id' => $this->input->post('cgrade'), 'genre' => $this->input->post('cgenre'));
            
            $st->where('students_id ', $this->session->userdata('curid'))->update($val, TRUE);
                        
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true'; 
        }
        else
        {
            $this->load->view('student_update', $data);
        }
        
       
    }

    public function valid_nisn($nis)
    {
        $st = new Student();
        $val = $st->where('nisn', $nis)->where('active', 1)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_nisn', "Student [$nis] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_nisn($nis)
    {
        $st = new Student();
        $st->where_not_in('students_id', $this->session->userdata('curid'));
        $val = $st->where('nisn', $nis)->where('active', 1)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_nisn', "NISN [$nis] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
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
    
    public function valid_name($name,$nis)
    {
        $st = new Student();
        $st->where('name', $name);
        $val = $st->where('nisn', $nis)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_name', "Student [$nis - $name] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_name($name,$nis)
    {
        $st = new Student();
        $st->where_not_in('students_id', $this->session->userdata('curid'));
        $st->where('name', $name);
        $val = $st->where('nisn', $nis)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_name', "Student [$nis - $name] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept']    = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade']   = $this->grade->combo_all();
        $data['cyear']   = $this->finance->combo();
        
        $this->load->view('student_report_panel', $data);
    }
    
    public function report_process()
    {
        $data['department'] = $this->dept->get_name($this->input->post('cdept'));
        $data['faculty']    = $this->faculty->get_name($this->input->post('cfaculty'));
        $data['grade']      = $this->grade->get_name($this->input->post('cgrade'));
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
                
        $data['students'] = $this->model->report($this->input->post('cdept'))->result();
        
        if ($this->input->post('cformat') == 0){ $this->load->view('student_report', $data); }
        elseif ($this->input->post('cformat') == 1) 
        {
           $pdf = new Pdf();
           $pdf->create($this->load->view('student_report', $data, TRUE),'Students');
        }
        elseif ($this->input->post('cformat') == 2) 
        {
          $excel = new Excel_lib();
          $query = $this->model->report($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'));
          $excel->create($query, 'division');  
        }
        elseif ($this->input->post('cformat') == 3) { $this->load->view('student_summary', $data); }
        elseif ($this->input->post('cformat') == 4) { $this->load->view('student_pivot', $data); }
        
    }

}

?>