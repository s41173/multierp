<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_status extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Payment_status_model', 'model', TRUE);

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
        $this->student = $this->load->library('student_lib');
        $this->finance = new Financial_lib();
        $this->grade = new Grade_lib();
        $this->fee = new Regcost_lib();
        $this->payment = new Payment_status_lib();
    }

    private $properti, $modul, $title, $account,$dept,$faculty,$fee,$payment;
    private $user,$journalgl,$currency,$student,$finance,$grade,$page;

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
//      // cari di database
      $data = $this->db->from('students')->like('name',$keyword,'after')->where('active', 1)->get();

      // format keluaran di dalam array
      foreach($data->result() as $row)
      {
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>  strtoupper($row->name),
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
        $data['main_view'] = 'payment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade'] = $this->grade->combo_all();
        $data['finance_year'] = $this->finance->combo_active();
        $data['currency'] = $this->currency->combo();
        
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
            $this->table->set_heading('No', 'Students', 'Dept', 'Period', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', '#');
//
            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, strtoupper($this->student->get_name($cost->student_id)).' <br> '.$this->student->get_nisn($cost->student_id),
                    $this->dept->get_name($this->student->get_dept($cost->student_id)).' - '.$this->faculty->get_code($this->student->get_faculty($cost->student_id)),
                    $cost->financial_year,
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p1), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p2), 'title' => 'edit / update')),    
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p3), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p4), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p5), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p6), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p7), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p8), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p9), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p10), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p11), 'title' => 'edit / update')),
                    anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p12), 'title' => 'edit / update')),                        
                    anchor_popup($this->title.'/details/'.$cost->id,'<span>print</span>',array('class' => 'details1', 'title' => '')).' '.    
                    anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => ''))
//                    anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
//
            $data['table'] = $this->table->generate();
        }
        else{ $data['message'] = "No $this->title data was found!"; }

        $data['graph'] = $this->chart($this->input->post('cdeptgraph'),  $this->input->post('cyeargraph'));
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'payment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade'] = $this->grade->combo_all();
        $data['finance_year'] = $this->finance->combo_active();
        $data['currency'] = $this->currency->combo();
        $data['default']['deptgraph'] = $this->input->post('cdeptgraph');
        
	// ---------------------------------------- //
        $costs = $this->model->search($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'),
                                      $this->input->post('tvalue'),$this->input->post('ctype'), 
                                      $this->input->post('cyear'), $this->input->post('cstts'))->result();
  
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");
        //Set heading untuk table
        $this->table->set_heading('No', 'Students', 'Dept', 'Period', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', '#');
//
        $i = 0;
        
        foreach ($costs as $cost)
        {
            $this->table->add_row
            (
                ++$i, strtoupper($this->student->get_name($cost->student_id)).'<br>'.$this->student->get_nisn($cost->student_id),
                $this->dept->get_name($this->student->get_dept($cost->student_id)).' - '.$this->faculty->get_code($this->student->get_faculty($cost->student_id)),
                $cost->financial_year,
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p1), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p2), 'title' => 'edit / update')),    
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p3), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p4), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p5), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p6), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p7), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p8), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p9), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p10), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p11), 'title' => 'edit / update')),
                anchor($this->title,'<span>update</span>',array('class' => $this->post_status($cost->p12), 'title' => 'edit / update')),                        
                anchor_popup($this->title.'/details/'.$cost->id,'<span>print</span>',array('class' => 'details1', 'title' => '')).' '.    
               // anchor_popup($this->title.'/prints/'.$cost->student_id,'<span>print</span>',array('class' => 'print', 'title' => '')).' '.    
                anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => ''))
//                anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
//
        $data['table'] = $this->table->generate();
        $data['graph'] = $this->chart($this->input->post('cdeptgraph'));
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function post_status($val=null)
    { $class = 'notapprove'; if ($val) {$class = "approve"; }elseif ($val == 1){$class = "notapprove"; } return $class; }
    
    
    private function tunggakan_chart($dept,$grade,$monthperiod,$year,$mpnow)
    { 
        $payment = new Payment_status_lib();
        if ($monthperiod <= $mpnow){ return $payment->get_miss_recapitulation($dept,$grade,$monthperiod,$year); }
        else { return 0; }
    }
    
    public function chart($dept=null)
    {
        $fusion = new Fusioncharts();
        $chart  = base_url().'public/flash/Column3D.swf';
        
        $ps = new Period();
        $ps->get();
        
        // ============== chart ==============================
        
        if ($this->input->post('cdeptgraph')){ $dept = $this->input->post('cdeptgraph'); }else { $dept = null; }
        if ($this->input->post('cyeargraph')){ $year = $this->input->post('cyeargraph'); }else { $year = $this->finance->get(); }
        if ($year == $this->finance->get()){ $mpnow = $this->payment->months_periode($ps->month); }else{ $mpnow = 12; }
        
        $arpData[0][1] = 'July';
        $arpData[0][2] = $this->tunggakan_chart($dept, null, 1, $year, $mpnow);

        $arpData[1][1] = 'August';
        $arpData[1][2] = $this->tunggakan_chart($dept, null, 2, $year, $mpnow);

        $arpData[2][1] = 'September';
        $arpData[2][2] = $this->tunggakan_chart($dept, null, 3, $year, $mpnow);

        $arpData[3][1] = 'October';
        $arpData[3][2] = $this->tunggakan_chart($dept, null, 4, $year, $mpnow);

        $arpData[4][1] = 'November';
        $arpData[4][2] = $this->tunggakan_chart($dept, null, 5, $year, $mpnow);

        $arpData[5][1] = 'December';
        $arpData[5][2] = $this->tunggakan_chart($dept, null, 6, $year, $mpnow);

        $arpData[6][1] = 'January';
        $arpData[6][2] = $this->tunggakan_chart($dept, null, 7, $year, $mpnow);

        $arpData[7][1] = 'February';
        $arpData[7][2] = $this->tunggakan_chart($dept, null, 8, $year, $mpnow);
        
        $arpData[8][1] = 'March';
        $arpData[8][2] = $this->tunggakan_chart($dept, null, 9, $year, $mpnow);
        
        $arpData[9][1] = 'April';
        $arpData[9][2] = $this->tunggakan_chart($dept, null, 10, $year, $mpnow);
        
        $arpData[10][1] = 'May';
        $arpData[10][2] = $this->tunggakan_chart($dept, null, 11, $year, $mpnow);
        
        $arpData[11][1] = 'Jun';
        $arpData[11][2] = $this->tunggakan_chart($dept, null, 12, $year, $mpnow);

        $strXML1 = $fusion->setDataXML($arpData,'','') ;
        $graph   = $fusion->renderChart($chart,'',$strXML1,"Payment", "98%", 400, false, false) ;
        return $graph;
    }
    
    function details($id)
    {
        $val = $this->model->get_id($id);
        $data['name'] = $this->student->get_name($val->student_id);
        $data['nis']  = $this->student->get_nisn($val->student_id);
        $data['dept']  = $this->dept->get_name($this->student->get_dept($val->student_id));
        $data['faculty'] = $this->faculty->get_code($this->student->get_faculty($val->student_id));
        $data['grade'] = $this->grade->get_name($this->student->get_grade($val->student_id));
        $data['year'] = $this->finance->get();
        
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
        
        $data['p1'] = isset($val->p1) ? $val->p1 : '-';
        $data['p2'] = isset($val->p2) ? $val->p2 : '-';
        $data['p3'] = isset($val->p3) ? $val->p3 : '-';
        $data['p4'] = isset($val->p4) ? $val->p4 : '-';
        $data['p5'] = isset($val->p5) ? $val->p5 : '-';
        $data['p6'] = isset($val->p6) ? $val->p6 : '-';
        $data['p7'] = isset($val->p7) ? $val->p7 : '-';
        $data['p8'] = isset($val->p8) ? $val->p8 : '-';
        $data['p9'] = isset($val->p9) ? $val->p9 : '-';
        $data['p10'] = isset($val->p10) ? $val->p10 : '-';
        $data['p11'] = isset($val->p11) ? $val->p11 : '-';
        $data['p12'] = isset($val->p12) ? $val->p12 : '-';
        $data['sid'] = $val->student_id;
        
        $this->load->view('payment_invoice', $data);
    }
    
    function migration()
    {
        $this->acl->otentikasi_admin($this->title);
        $py = new Payment_status_lib();
        $i = $py->migration();
        $this->cleaning();
        $this->session->set_flashdata('message', "$i students has successfully migrated !");
        redirect($this->title);
    }
    
    function cleaning()
    {
       $st = new Student_lib(); 
       $result = $this->model->get_all()->result();
       $i=1;
       foreach ($result as $res) 
       { if ($st->count_student($res->student_id) == FALSE){ $this->model->delete($res->id);  $i++;} }
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->model->delete($uid);
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        
//        redirect($this->title);
    }
    
    function update($uid)
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'payment_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $val = $this->model->get_id($uid);
        
        $data['name'] = $this->student->get_name($val->student_id);
        $data['nis']  = $this->student->get_nisn($val->student_id);
        $data['dept']  = $this->dept->get_name($this->student->get_dept($val->student_id));
        $data['faculty'] = $this->faculty->get_code($this->student->get_faculty($val->student_id));
        $data['grade'] = $this->grade->get_name($this->student->get_grade($val->student_id));
        $data['year'] = $this->finance->get();
        
        $data['default']['p1'] = $val->p1;
        $data['default']['p2'] = $val->p2;
        $data['default']['p3'] = $val->p3;
        $data['default']['p4'] = $val->p4;
        $data['default']['p5'] = $val->p5;
        $data['default']['p6'] = $val->p6;
        $data['default']['p7'] = $val->p7;
        $data['default']['p8'] = $val->p8;
        $data['default']['p9'] = $val->p9;
        $data['default']['p10'] = $val->p10;
        $data['default']['p11'] = $val->p11;
        $data['default']['p12'] = $val->p12;

	$this->session->set_userdata('curid', $val->id);
        $this->load->view('payment_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'receipt_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('account/','<span>back</span>', array('class' => 'back')));

	// Form validation

        $val = array('p1' => $this->set_null($this->input->post('tp1')), 'p2' => $this->set_null($this->input->post('tp2')), 'p3' => $this->set_null($this->input->post('tp3')),
                     'p4' => $this->set_null($this->input->post('tp4')), 'p5' => $this->set_null($this->input->post('tp5')), 'p6' => $this->set_null($this->input->post('tp6')),
                     'p7' => $this->set_null($this->input->post('tp7')), 'p8' => $this->set_null($this->input->post('tp8')), 'p9' => $this->set_null($this->input->post('tp9')),
                     'p10' => $this->set_null($this->input->post('tp10')), 'p11' => $this->set_null($this->input->post('tp11')), 'p12' => $this->set_null($this->input->post('tp12'))
            );

        $this->model->update($this->session->userdata('curid'), $val);
        $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
        $this->session->unset_userdata('curid');
        echo 'true';
    }

    private function set_null($val){ if ($val){ return $val; } else{ return null; } }
    
    public function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $ps = new Period();
        $ps = $ps->get();
        $py = new Payment_status_lib();
        
        $data['dept']    = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo_all();
        $data['grade']   = $this->grade->combo_all();
        $data['cyear']   = $this->finance->combo();
        $data['default']['year'] = $this->finance->get();
        $data['default']['period'] = $ps->month;
        $data['year'] = date('Y');
        
        $this->load->view('payment_report_panel', $data);
    }
    
    public function report_process()
    {
        $py = new Payment_status_lib();
        $data['department'] = $this->dept->get_name($this->input->post('cdept'));
        $data['faculty']    = $this->faculty->get_name($this->input->post('cfaculty'));
        $data['grade']      = $this->grade->get_name($this->input->post('cgrade'));
        $data['year']       = $this->input->post('cyear');
        $data['log']        = $this->session->userdata('log');
        $data['company']    = $this->properti['name'];
        $data['dept_class'] = $this->dept;
        $data['student_class'] = $this->student;
        $data['period']      = $this->input->post('cperiod');
        $data['monthperiod'] = $py->months_periode($this->input->post('cperiod'));
        
        $tahun = $py->year_name($py->months_periode($this->input->post('cperiod')), $this->input->post('cyear'));
        $data['tahun'] = $tahun;
                
        $data['fee'] = $this->fee->get_by_criteria($this->input->post('cdept'), $this->grade->get_level($this->input->post('cgrade')));
        
        $data['payments'] = $this->model->report($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'), $this->input->post('cyear'), $this->input->post('cperiod'),$tahun)->result();
                
        $this->page=null;
        if ($this->input->post('cformat') == 0)
        {
           if ($this->input->post('ctype') == 0){ $this->load->view('payment_report', $data); } 
           elseif ($this->input->post('ctype') == 1) { $this->load->view('payment_report_finance', $data); }
           elseif ($this->input->post('ctype') == 2) { $this->load->view('payment_report_summary', $data); }
           elseif ($this->input->post('ctype') == 3) { $this->load->view('payment_report_front', $data); }
           elseif ($this->input->post('ctype') == 9) { $this->load->view('pivot_finance', $data); }
           elseif ($this->input->post('ctype') == 10) { $this->load->view('bruto_income', $data); }
           else{ $this->recapitulation($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'), 
                                       $this->input->post('cyear'), $this->input->post('ctype'), $this->input->post('cformat'),
                                       $this->input->post('cperiod')
                                      ); }  
        }
        else
        {
           if ($this->input->post('ctype') == 0){ $this->page = 'payment_report'; } 
           elseif ($this->input->post('ctype') == 1) { $this->page = 'payment_report_finance'; }
           elseif ($this->input->post('ctype') == 2) { $this->load->view('payment_report_summary', $data); }
           elseif ($this->input->post('ctype') == 3) { $this->page = 'payment_report_front'; }
           else{ $this->recapitulation( $this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'), 
                                        $this->input->post('cyear'), $this->input->post('ctype'), $this->input->post('cformat'),
                                        $this->input->post('cperiod')    
                                      ); 
               }   
            
            $pdf = new Pdf();
            $pdf->create($this->load->view($this->page, $data, TRUE));  
        }
                
    }
    
    private function recapitulation($dept=null,$faculty=null,$grade=null,$fyear,$type=0,$format=0,$month=0)
    {
        $ps = new Period();
        $ps->get();
        $py = new Payment_status_lib();
        
        $data['department'] = $this->dept->get_name($dept);
        $data['faculty']    = $this->faculty->get_name($faculty);
        $data['grade']      = $this->grade->get_name($grade);
        $data['year']       = $fyear;
        $data['log']        = $this->session->userdata('log');
        $data['company']    = $this->properti['name'];
        $data['dept']       = $dept;
        $data['facultyid']  = $faculty;
        $data['month']       = $month;
        $data['monthperiod'] = $py->months_periode($month);
        $data['monthname']  = get_month($month).' '.$py->year_name($py->months_periode($month),$fyear);
        
        $page=null;
        if ($type == 4){ $page = 'payment_recap'; }
        elseif ( $type == 5){ $page = 'practice_recap'; }
        elseif ( $type == 6){ $page = 'osis_recap'; }
        elseif ( $type == 7){ $page = 'computer_recap'; }
        elseif ( $type == 8){ $page = 'ar_recap'; }
        
        if ($format == 0){  $this->load->view($page, $data); }
        else
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
    }

    // ========================== DETAIL REKAPITULASI POP UP =================================
    
    function get_details($dept,$grade,$monthperiod,$financial)
    {
       $this->acl->otentikasi1($this->title); 
       $ps = new Payment_status_lib();
       $year = $ps->year_name($monthperiod, $financial);
       $data['period'] = $ps->months_from_period($monthperiod);
       $data['monthperiod'] = $monthperiod;
       //$data['source'] = site_url()."/$this->title/get_json_details/$dept/$grade/$month/$year";
       
       $data['result'] = $this->model->report($dept,null,$grade,$financial)->result();
       $this->load->view('payment_list_grid', $data);  
    }
    
    // =========   Penyesuaian Hutang ========================
    function front_adjustment($dept,$grade,$month,$financial)
    {
       $this->acl->otentikasi1($this->title); 
       $ps = new Payment_status_lib();
       $data['period'] = $month;
       //$data['source'] = site_url()."/$this->title/get_json_details/$dept/$grade/$month/$year";
       
       $data['result'] = $ps->get_front_recapitulation($dept,$grade,$month,$financial,'detail');
       $this->load->view('front_adjustment_grid', $data);  
    }
    
}

?>