<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inactive extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Inactive_model', 'im', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account  = $this->load->library('account_lib');
        $this->dept = new Dept_lib();
        $this->faculty = new Faculty_lib();
        $this->student = new Student_lib();
        $this->finance = $this->load->library('financial_lib');
        $this->grade   = new Grade_lib();
        $this->model = new Inactives();
        $this->recap = new Student_recap_trans_lib();
    }

    private $properti, $modul, $title, $account,$dept,$faculty;
    private $user,$journalgl,$currency,$student,$finance,$grade,$model,$recap;

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
      $data = $this->db->from('students')->where('active', 0)->like('name',$keyword,'after')->get();

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
        $data['main_view'] = 'student_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor('students','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade'] = $this->grade->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
	// ---------------------------------------- //
        $costs = $this->model->where('active', 0)->get($this->modul['limit'], $offset);
        $num_rows = $this->model->where('active', 0)->count();

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
            $this->table->set_heading('#','No', 'NISN', 'Name', 'Dept', 'Faculty', 'Grade', 'Status', 'Out', '#');
//
            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cost->students_id,'checked'=> FALSE, 'style'=> 'margin:0px');       
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $cost->nisn, $this->student->get_name($cost->students_id), $this->dept->get_name($this->student->get_dept($cost->students_id)), $this->faculty->get_name($this->student->get_faculty($cost->students_id)), $this->grade->get_name($this->student->get_grade($cost->students_id)), strtoupper($cost->status), tglin($cost->resign),
                    anchor_popup($this->title.'/details/'.$cost->students_id,'<span>print</span>',array('class' => 'print', 'title' => ''), $this->atts)
                );
            }
//
            $data['table'] = $this->table->generate();
            
              //          fasilitas check all
            $js = "onClick='cekall($i)'";
            $sj = "onClick='uncekall($i)'";
            $data['radio1'] = form_radio('ceks', 'accept1', FALSE, $js).'Check';
            $data['radio2'] = form_radio('ceks', 'accept2', FALSE, $sj).'Uncheck';
            
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
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade'] = $this->grade->combo_all();
        
	// ---------------------------------------- //
        if ($this->input->post('tvalue'))
        {
            if ($this->input->post('ctype')== 0){ $type = 'nisn'; }else { $type = 'name'; }
            $costs = $this->model->where($type, $this->input->post('tvalue'))->where('active', 0)->get();
        }
        else
        {
            $costs = $this->im->search($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'))->result();   
        }
  
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'NISN', 'Name', 'Dept', 'Faculty', 'Grade', 'Status', 'Out', '#');
//
        $i = 0;
        foreach ($costs as $cost)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cost->students_id,'checked'=> FALSE, 'style'=> 'margin:0px');       
            $this->table->add_row
            (
                form_checkbox($datax), ++$i, $cost->nisn, $this->student->get_name($cost->students_id), $this->dept->get_name($this->student->get_dept($cost->students_id)), $this->faculty->get_name($this->student->get_faculty($cost->students_id)), $this->grade->get_name($this->student->get_grade($cost->students_id)), strtoupper($cost->status), tglin($cost->resign),
                anchor_popup($this->title.'/details/'.$cost->students_id,'<span>print</span>',array('class' => 'print', 'title' => ''), $this->atts)
            );
        }
//
        $data['table'] = $this->table->generate();
        
        //          fasilitas check all
        $js = "onClick='cekall($i)'";
        $sj = "onClick='uncekall($i)'";
        $data['radio1'] = form_radio('ceks', 'accept1', FALSE, $js).'Check';
        $data['radio2'] = form_radio('ceks', 'accept2', FALSE, $sj).'Uncheck';
        
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
    
    function add($dept=null,$faculty=null,$grade=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept']    = $this->dept->combo(); 
        $data['faculty'] = $this->faculty->combo();
        $data['grade']   = $this->grade->combo(); 
        
        $data['default']['dept']    = $dept;
        $data['default']['faculty'] = $faculty;
        $data['default']['grade']   = $grade;
        
        $this->load->view('student_form', $data);
    }
    
    function delete_all()
    {
       $this->acl->otentikasi_admin($this->title);
       $cek = $this->input->post('cek');
       
      if($cek)
      {
        $jumlah = count($cek);
        for ($i=0; $i<$jumlah; $i++)
        {   
//           echo $cek[$i].'<br>'; 
            $this->delete($cek[$i],'all');
        }
        $this->session->set_flashdata('message', "$jumlah $this->title selected successfully removed..!");
      }
      else
      { $this->session->set_flashdata('message', "No $this->title selected..!!"); }
      redirect($this->title);
    }
    
    function delete($uid,$type='non')
    {
        $this->acl->otentikasi_admin($this->title);
        
        $py = new Payment_status_lib(); // hapus status payment status
        $py->delete($uid);
        
        $value = $this->model->where('students_id', $uid)->get(); // hapus di recap trans
    //    $this->recap->remove($uid, $value->dept_id, $value->grade_id, $value->joined, 'in', date('n', strtotime($value->joined)), date('Y', strtotime($value->joined)));
        
        $this->student->remove($uid); // hapus siswa
        
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
        if ($type == 'non'){ redirect($this->title); }
    }
    
    private function add_recap($student)
    {
       $recap = new Student_recap_trans_lib();
       $dept = $this->student->get_dept($student);
       $grade = $this->student->get_grade($student);
       
       // add trans recap
       $ps = new Period();
       $ps->get();
       $recap->add_trans($dept, $grade, date('Y-m-d'), 'in', 1, $ps->month, $ps->year, 'DEL:'.date('d-m-Y'), 'Reactivate');
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
                
        $data['students'] = $this->model->report($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'))->result();
        
        $this->load->view('student_report', $data);
    }

}

?>