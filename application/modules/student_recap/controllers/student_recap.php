<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student_recap extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Recap_model', 'rm', TRUE);
        
        $this->properti = $this->property->get();
        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->dept = new Dept_lib();
        $this->grade = new Grade_lib();
        $this->employee = $this->load->library('employee_lib');
        $this->model = new Recaps();
        $this->student = new Student_lib();
        
        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';
        
    }

    private $properti, $modul, $title,$dept,$employee,$grade;
    private $user,$currency,$model,$student;

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
        $data['main_view'] = 'recap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('registration_reference/','<span>back</span>', array('class' => 'back')));
        
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
            $this->table->set_heading('No', 'Period', 'Department', 'Grade', 'Amount', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, get_month($res->month).' - '.$res->year, $this->dept->get_name($res->dept_id), $this->grade->get_name($res->grade_id), $res->qty,
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
//
            $data['table'] = $this->table->generate();
            
            // ===== chart  =======
            $data['graph'] = $this->chart($this->input->post('cdept'));
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
//
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function chart($dept)
    {
        $ps = new Period();
        $recap = new Student_recap_lib();
        $ps->get();
        $year = new Financial_lib();
        $py = new Payment_status_lib();
        
        if ($this->input->post('cdept')){ $dept = $this->input->post('cdept'); }else { $dept = null; }
        
        $arpData[0][1] = 'July';
        $arpData[0][2] = $recap->get_total($dept, null, 7, $py->year_name(1, $year->get()));

        $arpData[1][1] = 'August';
        $arpData[1][2] = $recap->get_total($dept, null, 8, $py->year_name(2, $year->get()));

        $arpData[2][1] = 'September';
        $arpData[2][2] = $recap->get_total($dept, null, 9, $py->year_name(3, $year->get()));

        $arpData[3][1] = 'October';
        $arpData[3][2] = $recap->get_total($dept, null, 10, $py->year_name(4, $year->get()));

        $arpData[4][1] = 'November';
        $arpData[4][2] = $recap->get_total($dept, null, 11, $py->year_name(5, $year->get()));

        $arpData[5][1] = 'December';
        $arpData[5][2] = $recap->get_total($dept, null, 12, $py->year_name(6, $year->get()));

        $arpData[6][1] = 'January';
        $arpData[6][2] = $recap->get_total($dept, null, 1, $py->year_name(7, $year->get()));
//
        $arpData[7][1] = 'February';
        $arpData[7][2] = $recap->get_total($dept, null, 2, $py->year_name(8, $year->get()));

        $arpData[8][1] = 'March';
        $arpData[8][2] = $recap->get_total($dept, null, 3, $py->year_name(9, $year->get()));

        $arpData[9][1] = 'April';
        $arpData[9][2] = $recap->get_total($dept, null, 4, $py->year_name(10, $year->get()));

        $arpData[10][1] = 'May';
        $arpData[10][2] = $recap->get_total($dept, null, 5, $py->year_name(11, $year->get()));
//
        $arpData[11][1] = 'June';
        $arpData[11][2] = $recap->get_total($dept, null, 6, $py->year_name(12, $year->get()));

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'recap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all();
	// ---------------------------------------- //
                
        $result = $this->rm->search($this->input->post('cdept'),$this->input->post('cmonth'),$this->input->post('tyear'))->result();
  
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
       $this->table->set_heading('No', 'Period', 'Department', 'Grade', 'Amount', '#');
        $i = 0;
        foreach ($result as $res)
        {
            $this->table->add_row
            (
                ++$i, get_month($res->month).' - '.$res->year, $this->dept->get_name($res->dept_id), $this->grade->get_name($res->grade_id), $res->qty,
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        $data['table'] = $this->table->generate();
        // ===== chart  =======
        $data['graph'] = $this->chart($this->input->post('cdept'));
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
        
        $data['dept'] = $this->dept->combo_all();
        $data['year'] = date('Y');
        $this->load->view('recap_form', $data);
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
        $this->form_validation->set_rules('cdept', 'Department', 'required|callback_valid_recap');
        $this->form_validation->set_rules('cgrade', 'Grade', 'required');
        $this->form_validation->set_rules('cmonth', 'Month Period', 'required');
        $this->form_validation->set_rules('tyear', 'Year Period', 'required|numeric');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->dept_id      = $this->input->post('cdept');
            $this->model->grade_id     = $this->input->post('cgrade');
            $this->model->month        = $this->input->post('cmonth');
            $this->model->year         = $this->input->post('tyear');
            $this->model->qty          = $this->input->post('tqty');
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
        $data['default']['registration'] = $this->model->registration;
        $data['default']['development']  = $this->model->development;
        $data['default']['school']       = $this->model->school;
        $data['default']['osis']         = $this->model->osis;
        $data['default']['computer']     = $this->model->computer;
        $data['default']['practice']     = $this->model->practice;
        $data['default']['other']       = $this->model->others;
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
    
    public function valid_recap($dept)
    {
        $month = $this->input->post('cmonth');
        $year = $this->input->post('tyear');
        $grade = $this->input->post('cgrade');
        $val = $this->model->where('dept_id', $dept)->where('month', $month)->where('year', $year)->where('grade_id', $grade)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_recap', "Recap type - already registered..!");
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
    
    function report()
    {
       $this->acl->otentikasi2($this->title);

       $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
       $data['h2title'] = $this->modul['title'];
       $data['form_action'] = site_url($this->title.'/report_process');
        
       $data['dept'] = $this->dept->combo_all();
       $data['year'] = date('Y');
       $this->load->view('recap_report_panel', $data);
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
        
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
                
        $dept = $this->input->post('cdept');
        $year = $this->input->post('tyear');
        
        if ($dept){ $data['results'] = $this->model->where('dept_id', $dept)->where('year', $year)->order_by('month', 'asc')->order_by('year', 'asc')->get(); }
        else { $data['results'] = $this->model->where('year',$year)->order_by('month', 'asc')->order_by('year', 'asc')->get(); }

        $this->load->view('recap_report', $data);
    }
    
    // ==================================   Recap Trans  ============================================
    
    function trans()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'].':Transaction';
        $data['main_view'] = 'recap_trans_view';
	$data['form_action'] = site_url($this->title.'/trans_search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $transmodel = new Recap_trans_model();
        $data['dept'] = $this->dept->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
	// ---------------------------------------- //
        $result = $transmodel->get(30, $offset)->result();
        $num_rows = $transmodel->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/trans');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = 30;
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
            $this->table->set_heading('No', 'Date', 'Department', 'Student', 'Grade', 'Type', 'Qty', 'Period', 'Transcode', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, tglin($res->dates), $this->dept->get_name($res->dept_id), $this->student->get_name($res->student_id), $this->grade->get_name($res->grade_id), strtoupper($res->type), $res->qty, get_month($res->month).' - '.$res->year, $res->transcode,   
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
//
            $data['table'] = $this->table->generate();
        }
        else{ $data['message'] = "No $this->title data was found!";}
//
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function trans_search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'].':Transaction';
        $data['main_view'] = 'recap_trans_view';
	$data['form_action'] = site_url($this->title.'/trans_search');
        $data['link'] = array('link_back' => anchor($this->title.'/trans','<span>back</span>', array('class' => 'back')));
        
        $transmodel = new Recap_trans_model();
        $data['dept'] = $this->dept->combo_all();
        
        $dept = $this->input->post('cdept');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
        
	// ---------------------------------------- //
        $result = $transmodel->search($dept,$start,$end,$type)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Date', 'Department', 'Student', 'Grade', 'Type', 'Qty', 'Period', 'Transcode', '#');
//
        $i = 0;
        foreach ($result as $res)
        {
            $this->table->add_row
            (
                ++$i, tglin($res->dates), $this->dept->get_name($res->dept_id), $this->student->get_name($res->student_id), $this->grade->get_name($res->grade_id), strtoupper($res->type), $res->qty, get_month($res->month).' - '.$res->year, $res->transcode,   
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
//
        $data['table'] = $this->table->generate();
//
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function trans_report()
    {
       $this->acl->otentikasi2($this->title);

       $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
       $data['h2title'] = $this->modul['title'];
       $data['form_action'] = site_url($this->title.'/trans_report_process');
        
       $data['dept'] = $this->dept->combo_all();
       $data['year'] = date('Y');
       $this->load->view('trans_report_panel', $data);
    }
    
    function trans_report_process()
    {
       $this->acl->otentikasi2($this->title);

       $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
       $data['h2title'] = $this->modul['title'];
       $data['form_action'] = site_url($this->title.'/trans_report_process');
       
       $transmodel = new Recap_trans_model();
       
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
       
       $data['dept'] = $this->dept->combo_all();
       $data['year'] = date('Y');
       
       $dept = $this->input->post('cdept');
       $start = $this->input->post('tstart');
       $end = $this->input->post('tend');
        
	// ---------------------------------------- //
       $data['results'] = $transmodel->search($dept,$start,$end)->result();
       $this->load->view('trans_report', $data);
    }
    
}

?>