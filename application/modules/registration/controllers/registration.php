<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Registration_model', 'regmodel', TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency   = $this->load->library('currency_lib');
        $this->user       = $this->load->library('admin_lib');
        $this->journal    = $this->load->library('journal_lib');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->dept       = new Dept_lib();
        $this->faculty    = new Faculty_lib();
        $this->financial  = new Financial_lib(); 
        $this->student    = new Student_lib();
        $this->payment    = new Payment_type_lib(); 
        $this->uniform    = new Uniform_lib();
        $this->level      = new Level_lib();
        $this->pstatus    = new Payment_status_lib();
        $this->grade      = new Grade_lib();
        $this->recap      = new Student_recap_trans_lib();
        
        $this->model = new Registrations();

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency,$model,$payment,$level,$recap;
    private $user,$journal,$dept,$faculty,$financial, $student, $uniform,$pstatus,$grade;

    function index()
    {
        $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'registration_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last');
        $data['link'] = array('link_back' => anchor('registration_reference','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo_all();
        $data['year'] = $this->financial->combo_active(); 
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $saless = $this->model->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

        
        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'update', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

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
            $this->table->set_heading('No', 'Code', 'Date', 'Department', 'Faculty', 'Student', 'Balance', 'Payment Type', 'Action');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'REG-0'.$sales->no, tglin($sales->dates), $this->dept->get_name($sales->dept_id), $this->faculty->get_name($this->student->get_faculty($sales->student_id)), $this->student->get_name($sales->student_id), number_format($sales->total+$this->uniform->total_by_regid($sales->id)), strtoupper($sales->payment_status),
                    anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approval), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$sales->id,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/update/'.$sales->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // ===== chart  =======
        $data['graph'] = $this->chart();
        

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    private function chart()
    {
        $ps = new Period();
        $ps->get();
        if (!$this->input->post('cyeargraph')){ $year = $ps->year; }else{ $year = $this->input->post('cyeargraph'); }
        
        $i=0;
        foreach($this->dept->get() as $res)
        {
           $arpData[$i][1] = strtoupper($res->name);
           $arpData[$i][2] = $this->model->where('dept_id', $res->dept_id)->where('approval', 1)->count();        
           $i++;
        }
        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'registration_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'update', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        if ($this->input->post('tdate')){ $saless = $this->model->where('dates', $this->input->post('tdate'))->get(); }
        else { $saless = $this->model->get(); }
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Department', 'Faculty', 'Student', 'Balance', 'Payment Type', 'Action');

        $i = 0;
        foreach ($saless as $sales)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'REG-0'.$sales->no, tglin($sales->dates), $this->dept->get_name($sales->dept_id), $this->faculty->get_name($this->student->get_faculty($sales->student_id)), $this->student->get_name($sales->student_id), number_format($sales->total+$this->uniform->total_by_regid($sales->id)), strtoupper($sales->payment_status),
                anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approval), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$sales->id,'<span>print</span>',$atts).' '.
                anchor($this->title.'/update/'.$sales->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $data['graph'] = $this->chart();
        $this->load->view('template', $data);
    }

    function confirmation($uid)
    {
       $st = new Student();
       $val = $this->model->where('id', $uid)->get();
       
       
       if ($this->valid_period($val->dates) == TRUE)
       {           
           // payment status
           $this->pstatus->add_payment($val->student_id, $val->financial_year);
           
           $value = array('joined' => $val->dates, 'resign' => intval(date('Y', strtotime($val->dates))+20).'-12-31', 'active' => 1);            
           $st->where('students_id ', $val->student_id)->update($value, TRUE);

           $val->approval = 1;
           $val->save(); 
           $this->session->set_flashdata('message', "1 $this->title successfully confirmation..!");
       }
       else { $this->session->set_flashdata('message', "Invalid Period..!"); }
       redirect($this->title);
    }
    
    function add()
    {
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'regform';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['level'] = $this->level->combo();
        $data['faculty'] = $this->faculty->combo();
        $data['payment'] = $this->payment->combo();
        $data['default']['nis'] = '0'.intval($this->student->get_max_id()+1);
        $data['default']['regid'] = $this->counter();
        
        $this->load->view('reg_form', $data);
    }
    
    private function counter()
    {
        $this->model->select_max('no')->get();
        return intval($this->model->no+1);
    }
    
    private function max_id()
    {
        $this->model->select_max('id')->get();
        return intval($this->model->id);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
         
        $data['dept'] = $this->dept->combo(); 
        $data['faculty'] = $this->faculty->combo();
        
	// Form validation
        $this->form_validation->set_rules('tname', 'Student Name', 'required');
        $this->form_validation->set_rules('tborn', 'Born Place', 'required');
        $this->form_validation->set_rules('tborndate', 'Born Date', 'required');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
        $this->form_validation->set_rules('tphone', 'Student Phone', 'required|numeric');
        $this->form_validation->set_rules('temail', 'Student Email', 'valid_email');
        $this->form_validation->set_rules('tnis', 'NIS', 'required|callback_valid_nis');
        $this->form_validation->set_rules('tnisn', 'NISN', '');
        $this->form_validation->set_rules('tnpsn', 'NPSN', '');
        $this->form_validation->set_rules('tcertificate', 'Certificate No', 'required');
        $this->form_validation->set_rules('tskhun', 'SKHUN', '');
        
        // father
        $this->form_validation->set_rules('tfname', 'Father Name', 'required');
        $this->form_validation->set_rules('tfjob', 'Father Job', 'required');
        $this->form_validation->set_rules('tfaddress', 'Father Address', 'required');
        $this->form_validation->set_rules('tfphone', 'Father Phone', 'numeric');
        $this->form_validation->set_rules('tfmobile', 'Father Mobile', 'numeric');
        $this->form_validation->set_rules('tfincome', 'Father Income', 'required|numeric');
        
        // mother
        $this->form_validation->set_rules('tmname', 'Mother Name', 'required');
        $this->form_validation->set_rules('tmjob', 'Mother Job', 'required');
        $this->form_validation->set_rules('tmaddress', 'Mother Address', 'required');
        $this->form_validation->set_rules('tmphone', 'Mother Phone', 'numeric');
        $this->form_validation->set_rules('tmmobile', 'Mother Mobile', 'numeric');
        $this->form_validation->set_rules('tmincome', 'Mother Income', 'required|numeric');
        
        // trustee
        $this->form_validation->set_rules('ttrusteename', 'Guardian Name', '');
        $this->form_validation->set_rules('ttrusteejob', 'Guardian Job', '');
        $this->form_validation->set_rules('ttrusteeaddress', 'Guardian Address', '');
        $this->form_validation->set_rules('ttrusteephone', 'Guardian Phone', '');
        
        // registration rules
        $this->form_validation->set_rules('tregid', 'REG-ID', 'required');
        $this->form_validation->set_rules('tregdate', 'Reqistration Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('cfaculty', 'Faculty', 'required');
        
        //registration costs
        $this->form_validation->set_rules('tregfee', 'Registration Fee', 'required|numeric');
        $this->form_validation->set_rules('tdevfee', 'Development Fee', 'required|numeric');
        $this->form_validation->set_rules('tschoolfee', 'School Fee', 'required|numeric');
        $this->form_validation->set_rules('tosisfee', 'OSIS Fee', 'required|numeric');
        $this->form_validation->set_rules('tpracticefee', 'Practice Fee', 'required|numeric');
        $this->form_validation->set_rules('totherfee', 'Other Fee', 'required|numeric');
        $this->form_validation->set_rules('ttotal', 'Total Cost', 'required|numeric|callback_valid_nol');
        
        // score validation
        $this->form_validation->set_rules('tmath', 'Math Score', 'required|numeric');
        $this->form_validation->set_rules('tindo', 'Indonesia Score', 'required|numeric');
        $this->form_validation->set_rules('tphysics', 'Physics Score', 'required|numeric');
        $this->form_validation->set_rules('tenglish', 'English Score', 'required|numeric');
        $this->form_validation->set_rules('tchemical', 'Chemical Score', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {   
            
           // student
           $this->student->add($this->input->post('cdept'), $this->input->post('cfaculty'), strtoupper($this->input->post('tname')), 
                               $this->input->post('tborn'), $this->input->post('tborndate'), $this->input->post('rgenre'),
                               $this->input->post('taddress'), $this->input->post('tzip'), $this->input->post('tphone'), 
                               $this->input->post('tmobile'), $this->input->post('temail'), $this->input->post('creligion'), $this->input->post('rcitizen'),
                               $this->input->post('ccondition'), $this->input->post('tnis'), $this->input->post('tnisn'), $this->input->post('tnpsn'), 
                               $this->input->post('tcertificate'), $this->input->post('tskhun'), 
                   
                               // father
                               $this->input->post('tfname'), $this->input->post('tfjob'),
                               $this->input->post('tfaddress'), $this->input->post('tfphone'), 
                               $this->input->post('tfmobile'), $this->input->post('tfincome'),
                   
                               // mother
                               $this->input->post('tmname'), $this->input->post('tmjob'),
                               $this->input->post('tmaddress'), $this->input->post('tmphone'), 
                               $this->input->post('tmmobile'), $this->input->post('tmincome'),
                   
                               // trustee
                               $this->input->post('ttrusteename'), $this->input->post('ttrusteejob'), $this->input->post('ttrusteeaddress'),
                               $this->input->post('ttrusteephone'),
                   
                               // join / resign , active
                               NULL, NULL, 0
                               ); 
            
            // registration
            $this->model->no               = $this->counter();
            $this->model->dept_id          = $this->input->post('cdept');
            $this->model->level            = $this->input->post('clevel');
            $this->model->student_id       = $this->student->get_max_id();
            $this->model->dates            = $this->input->post('tregdate');
            $this->model->register         = $this->input->post('tregfee');
            $this->model->development      = $this->input->post('tdevfee');
            $this->model->school           = $this->input->post('tschoolfee');
            $this->model->osis             = $this->input->post('tosisfee');
            $this->model->practice         = $this->input->post('tpracticefee');
            $this->model->others           = $this->input->post('totherfee');
            $this->model->total            = $this->input->post('ttotal');
            $this->model->p1               = $this->input->post('tp1');
            $this->model->p2               = $this->input->post('tp2');
            $this->model->financial_year   = $this->financial->get();
            $this->model->notes            = $this->input->post('tnotes');
            $this->model->payment_type     = $this->input->post('cpayment');
            $this->model->log              = $this->session->userdata('log');
            
            // score
            $this->model->math             = $this->input->post('tmath');
            $this->model->indonesia        = $this->input->post('tindo');
            $this->model->physics          = $this->input->post('tphysics');
            $this->model->english          = $this->input->post('tenglish');
            $this->model->chemical         = $this->input->post('tchemical');
            
              $this->model->save();
              
//            // uniform
            
            $this->uniform->add($this->max_id(), 
                                $this->input->post('tunistel'), 
                                $this->input->post('tpractstel'),
                                $this->input->post('tscoutstel'), 
                                $this->input->post('tunischool'), 
                                $this->input->post('tunipractice'), 
                                $this->input->post('tscout'), 
                                $this->input->post('tadd'), 
                                $this->input->post('tunitotal'), 
                                $this->input->post('tunip1'));
              
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add');
            echo 'true';
        }
        else
        { 
//            $this->load->view('reg_form', $data); 
            echo validation_errors();
        }

    }
    
    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $sales = $this->model->where('id', $uid)->get();

        if ( $sales->approval == 0 )
        {
            $this->student->remove($sales->student_id); // delete student
            $this->uniform->remove($uid); //  delete uniform
            $this->model->delete();
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "1 $this->title can't removed, please go to the termination module..!"); }

        redirect($this->title);
    }
    
    private function update_payment_status($po)
    {
        $tt = new Tuitiontrans();
        $transs = $tt->where('registration', $po)->get();
                
        $year = new Financial_lib();
        $ps = new Payment_status_lib();
        
        foreach ($transs as $res){ $ps->remove($res->student,$year->get(),$res->month);  }
    }

    function update($pid=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $st = new Student();
        $val = $this->model->where('id', $pid)->get();
        $st = $st->where('students_id', $val->student_id)->get();
        
        $un = new Uniform();
        $un = $un->where('reg_id', $pid)->get();
        

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Update '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);
        
        $data['dept'] = $this->dept->combo_all();
        $data['level'] = $this->level->combo();
        $data['faculty'] = $this->faculty->combo();
        $data['payment'] = $this->payment->combo();
        
        // -- combo end --------
        
        $data['default']['name'] = strtoupper($st->name);
        $data['default']['born'] = $st->born_place;
        $data['default']['borndate'] = $st->born_date;
        $data['default']['genre'] = $st->genre;
        $data['default']['address'] = $st->address;
        $data['default']['zip'] = $st->zipcode;
        $data['default']['phone'] = $st->phone;
        $data['default']['mobile'] = $st->mobile;
        $data['default']['email'] = $st->email;
        $data['default']['religion'] = $st->religion;
        $data['default']['citizen'] = $st->citizen;
        $data['default']['condition'] = $st->condition;
        $data['default']['nis'] = $st->nisn;
        $data['default']['nisn'] = $st->nisn_national;
        $data['default']['npsn'] = $st->npsn;
        $data['default']['certificate'] = $st->certificateno;
        $data['default']['skhun'] = $st->skhun;
        
        // fathers
        $data['default']['fname'] = $st->fathers_name;
        $data['default']['fjob'] = $st->fathers_job;
        $data['default']['faddress'] = $st->fathers_address;
        $data['default']['fphone'] = $st->fathers_phone;
        $data['default']['fmobile'] = $st->fathers_mobile;
        $data['default']['fincome'] = $st->fathers_income;
        
        // mothers
        $data['default']['mname'] = $st->mothers_name;
        $data['default']['mjob'] = $st->mothers_job;
        $data['default']['maddress'] = $st->mothers_address;
        $data['default']['mphone'] = $st->mothers_phone;
        $data['default']['mmobile'] = $st->mothers_mobile;
        $data['default']['mincome'] = $st->mothers_income;
        
        // trustee
        $data['default']['trusteename'] = $st->trustee_name;
        $data['default']['trusteejob'] = $st->trustee_job;
        $data['default']['trusteeaddress'] = $st->trustee_address;
        $data['default']['trusteephone'] = $st->trustee_phone;
        
        // score
        $data['default']['math'] = $val->math;
        $data['default']['indo'] = $val->indonesia;
        $data['default']['physics'] = $val->physics;
        $data['default']['english'] = $val->english;
        $data['default']['chemical'] = $val->chemical;
        
        // registration
        $data['default']['regid'] = $val->no;
        $data['default']['dept'] = $val->dept_id;
        $data['default']['regdate'] = $val->dates;
        $data['default']['faculty'] = $st->faculty;
        
        $data['default']['regfee'] = $val->register;
        $data['default']['devfee'] = $val->development;
        $data['default']['schoolfee'] = $val->school;
        $data['default']['osisfee'] = $val->osis;
        $data['default']['practicefee'] = $val->practice;
        $data['default']['otherfee'] = $val->others;
        
        $data['default']['notes'] = $val->notes;
        $data['default']['total'] = $val->total;
        $data['default']['p1'] = $val->p1;
        $data['default']['p2'] = $val->p2;
        $data['default']['p2date'] = $val->p2date;
        $data['default']['ptype'] = $val->payment_status;
        $data['default']['payment'] = $val->payment_type;
        
        // uniform
        $data['default']['unischool'] = $un->total_uniform;
        $data['default']['unistel'] = $un->stel_uniform;
        $data['default']['unipractice'] = $un->total_practice;
        $data['default']['practstel'] = $un->stel_practice;
        $data['default']['scout'] = $un->total_scout;
        $data['default']['scoutstel'] = $un->stel_scout;
        $data['default']['add'] = $un->additional;
        $data['default']['unitotal'] = $un->balance;
        $data['default']['unip1'] = $un->p1;
        
        $this->session->set_userdata('regid', $pid); // registration
        $this->session->set_userdata('sid', $val->student_id); // student
        $this->session->set_userdata('uniid', $un->id); // uniform
        
        $this->load->view('reg_update', $data);
    }
    
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
         
        $data['dept'] = $this->dept->combo(); 
        $data['faculty'] = $this->faculty->combo();
        
	// Form validation
        $this->form_validation->set_rules('tname', 'Student Name', 'required');
        $this->form_validation->set_rules('tborn', 'Born Place', 'required');
        $this->form_validation->set_rules('tborndate', 'Born Date', 'required');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
        $this->form_validation->set_rules('tphone', 'Student Phone', 'required|numeric');
        $this->form_validation->set_rules('temail', 'Student Email', 'valid_email');
        $this->form_validation->set_rules('tnis', 'NIS', 'required');
        $this->form_validation->set_rules('tnisn', 'NISN', '');
        $this->form_validation->set_rules('tnpsn', 'NPSN', '');
        $this->form_validation->set_rules('tcertificate', 'Certificate No', 'required');
        $this->form_validation->set_rules('tskhun', 'SKHUN', '');
        
        // father
        $this->form_validation->set_rules('tfname', 'Father Name', 'required');
        $this->form_validation->set_rules('tfjob', 'Father Job', 'required');
        $this->form_validation->set_rules('tfaddress', 'Father Address', 'required');
        $this->form_validation->set_rules('tfphone', 'Father Phone', 'numeric');
        $this->form_validation->set_rules('tfmobile', 'Father Mobile', 'numeric');
        $this->form_validation->set_rules('tfincome', 'Father Income', 'required|numeric');
        
        // mother
        $this->form_validation->set_rules('tmname', 'Mother Name', 'required');
        $this->form_validation->set_rules('tmjob', 'Mother Job', 'required');
        $this->form_validation->set_rules('tmaddress', 'Mother Address', 'required');
        $this->form_validation->set_rules('tmphone', 'Mother Phone', 'numeric');
        $this->form_validation->set_rules('tmmobile', 'Mother Mobile', 'numeric');
        $this->form_validation->set_rules('tmincome', 'Mother Income', 'required|numeric');
        
        // trustee
        $this->form_validation->set_rules('ttrusteename', 'Guardian Name', '');
        $this->form_validation->set_rules('ttrusteejob', 'Guardian Job', '');
        $this->form_validation->set_rules('ttrusteeaddress', 'Guardian Address', '');
        $this->form_validation->set_rules('ttrusteephone', 'Guardian Phone', '');
        
        // registration rules
        $this->form_validation->set_rules('tregid', 'REG-ID', 'required|callback_valid_confirmation['.$this->session->userdata('regid').']');
        $this->form_validation->set_rules('tregdate', 'Reqistration Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('cfaculty', 'Faculty', 'required');
        
        //registration costs
        $this->form_validation->set_rules('tregfee', 'Registration Fee', 'required|numeric');
        $this->form_validation->set_rules('tdevfee', 'Development Fee', 'required|numeric');
        $this->form_validation->set_rules('tschoolfee', 'School Fee', 'required|numeric');
        $this->form_validation->set_rules('tosisfee', 'OSIS Fee', 'required|numeric');
        $this->form_validation->set_rules('tpracticefee', 'Practice Fee', 'required|numeric');
        $this->form_validation->set_rules('totherfee', 'Other Fee', 'required|numeric');
        $this->form_validation->set_rules('ttotal', 'Total Cost', 'required|numeric|callback_valid_nol');
        $this->form_validation->set_rules('cptype', 'Payment Status', 'required|callback_valid_payment');
        
        // score validation
        $this->form_validation->set_rules('tmath', 'Math Score', 'required|numeric');
        $this->form_validation->set_rules('tindo', 'Indonesia Score', 'required|numeric');
        $this->form_validation->set_rules('tphysics', 'Physics Score', 'required|numeric');
        $this->form_validation->set_rules('tenglish', 'English Score', 'required|numeric');
        $this->form_validation->set_rules('tchemical', 'Chemical Score', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {   
            
           // student
           $this->student->update($this->session->userdata('sid'),
                               $this->input->post('cdept'), $this->input->post('cfaculty'), strtoupper($this->input->post('tname')), 
                               $this->input->post('tborn'), $this->input->post('tborndate'), $this->input->post('rgenre'),
                               $this->input->post('taddress'), $this->input->post('tzip'), $this->input->post('tphone'), 
                               $this->input->post('tmobile'), $this->input->post('temail'), $this->input->post('creligion'), $this->input->post('rcitizen'),
                               $this->input->post('ccondition'), $this->input->post('tnis'), $this->input->post('tnisn'), $this->input->post('tnpsn'), 
                               $this->input->post('tcertificate'), $this->input->post('tskhun'), 
                   
                               // father
                               $this->input->post('tfname'), $this->input->post('tfjob'),
                               $this->input->post('tfaddress'), $this->input->post('tfphone'), 
                               $this->input->post('tfmobile'), $this->input->post('tfincome'),
                   
                               // mother
                               $this->input->post('tmname'), $this->input->post('tmjob'),
                               $this->input->post('tmaddress'), $this->input->post('tmphone'), 
                               $this->input->post('tmmobile'), $this->input->post('tmincome'),
                   
                               // trustee
                               $this->input->post('ttrusteename'), $this->input->post('ttrusteejob'), $this->input->post('ttrusteeaddress'),
                               $this->input->post('ttrusteephone'),
                   
                               // join / resign , active
                               NULL, NULL, 0
                               ); 
            
            // registration
            $regist = $this->model->where('id', $this->session->userdata('regid'))->get();
            
            $regist->dept_id          = $this->input->post('cdept');
            $regist->level            = $this->input->post('clevel');
            $regist->dates            = $this->input->post('tregdate');
            $regist->register         = $this->input->post('tregfee');
            $regist->development      = $this->input->post('tdevfee');
            $regist->school           = $this->input->post('tschoolfee');
            $regist->osis             = $this->input->post('tosisfee');
            $regist->practice         = $this->input->post('tpracticefee');
            $regist->others           = $this->input->post('totherfee');
            $regist->total            = $this->input->post('ttotal');
            $regist->p1               = $this->input->post('tp1');
            $regist->p2               = $this->input->post('tp2');
            $regist->p2date           = $this->input->post('tp2date');
            $regist->financial_year   = $this->financial->get();
            $regist->notes            = $this->input->post('tnotes');
            $regist->payment_type     = $this->input->post('cpayment');
            $regist->payment_status   = $this->input->post('cptype');
            $regist->log              = $this->session->userdata('log');
            
            // score
            $regist->math             = $this->input->post('tmath');
            $regist->indonesia        = $this->input->post('tindo');
            $regist->physics          = $this->input->post('tphysics');
            $regist->english          = $this->input->post('tenglish');
            $regist->chemical         = $this->input->post('tchemical');
            
            $regist->save();
              
//            // uniform
            
            $this->uniform->update($this->session->userdata('uniid'),
                                   $this->input->post('tunistel'), 
                                   $this->input->post('tpractstel'),
                                   $this->input->post('tscoutstel'), 
                                   $this->input->post('tunischool'), 
                                   $this->input->post('tunipractice'), 
                                   $this->input->post('tscout'), 
                                   $this->input->post('tadd'), 
                                   $this->input->post('tunitotal'), 
                                   $this->input->post('tunip1'));
             
           $this->session->unset_userdata('sid');
           $this->session->unset_userdata('regid');
           $this->session->unset_userdata('uniid');
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add');
            echo 'true';
        }
        else
        { 
//            $this->load->view('reg_form', $data); 
            echo validation_errors();
        }

    }
    
// ===================================== PRINT ===========================================
    
   function invoice($pid=null)
   {
        $this->acl->otentikasi2($this->title);
        $reg = $this->model->where('id', $pid)->get();
        $st = new Student();
        $st = $st->where('students_id', $reg->student_id)->get();
        
        $un = new Uniform();
        $un = $un->where('reg_id', $pid)->get();

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];
        
        // property
        $data['logo'] = $this->properti['logo'];
        $data['paddress'] = $this->properti['address'];
        $data['p_phone1'] = $this->properti['phone1'];
        $data['p_phone2'] = $this->properti['phone2'];
        $data['p_city'] = ucfirst($this->properti['city']);
        $data['p_zip'] = $this->properti['zip'];
        $data['p_npwp'] = $this->properti['npwp'];
        $data['pname'] = $this->properti['name'];
        
        $data['financial'] = $this->financial->get();
        $data['startdate'] = tglin($this->financial->get_begin());
        $data['noreg'] = $reg->no;
        $data['name'] = strtoupper($st->name);
        $data['address'] = $st->address;
        $data['department'] = $this->dept->get_name($st->dept_id);
        $data['faculty'] = $this->faculty->get_name($st->faculty);
        $data['notes'] = $reg->notes;
        $data['regfee'] = $reg->register;
        $data['devfee'] = $reg->development;
        $data['schoolfee'] = $reg->school;
        $data['osisfee'] = $reg->osis;
        $data['practicefee'] = $reg->practice;
        $data['otherfee'] = $reg->others;
        $data['total'] = $reg->total;
        $data['p1'] = $reg->p1;
        $data['p2'] = $reg->p2;
        $data['p2date'] = $reg->p2date;
        $data['payment'] = $reg->payment_type;
        $data['paid'] = $reg->payment_status;
        $data['log'] = $reg->log;
        
        // uniform
        $data['unistel'] = $un->stel_uniform;
        $data['total_uniform'] = $un->total_uniform;
        $data['practstel'] = $un->stel_practice;
        $data['total_practice'] = $un->total_practice;
        $data['add'] = $un->additional;
        $data['scoutstel'] = $un->stel_scout;
        $data['total_scout'] = $un->total_scout;
        $data['unitotal'] = $un->balance;
        
        $this->load->view('reg_invoice', $data);
   }

   function recap($po=0,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];

       // SMP
       $smp0 = $this->tm->total($po,6,0);
       $data['smp_spp_piutang'] = intval($smp0['school_fee']+$smp0['cost']);
       $data['smp_osis_piutang'] = intval($smp0['osis']);
       $data['smp_com_piutang'] = intval($smp0['computer']);
       $data['smp_praktek_piutang'] = intval($smp0['practical']);
       $data['smp_bantuan_piutang'] = intval($smp0['aid_foundation']);
       $data['smp_total_piutang'] = $smp0['school_fee']+$smp0['cost']+$smp0['osis']+$smp0['computer']+$smp0['practical'];

       $smp1 = $this->tm->total($po,6,1);
       $data['smp_spp_berjalan'] = intval($smp1['school_fee']+$smp1['cost']);
       $data['smp_osis_berjalan'] = intval($smp1['osis']);
       $data['smp_com_berjalan'] = intval($smp1['computer']);
       $data['smp_praktek_berjalan'] = intval($smp1['practical']);
       $data['smp_bantuan_berjalan'] = intval($smp1['aid_foundation']);
       $data['smp_total_berjalan'] = $smp1['school_fee']+$smp1['cost']+$smp1['osis']+$smp1['computer']+$smp1['practical'];
       
       $smp2 = $this->tm->total($po,6,2);
       $data['smp_spp_depan'] = intval($smp2['school_fee']+$smp2['cost']);
       $data['smp_osis_depan'] = intval($smp2['osis']);
       $data['smp_com_depan'] = intval($smp2['computer']);
       $data['smp_praktek_depan'] = intval($smp2['practical']);
       $data['smp_bantuan_depan'] = intval($smp2['aid_foundation']);
       $data['smp_total_depan'] = $smp2['school_fee']+$smp2['cost']+$smp2['osis']+$smp2['computer']+$smp2['practical'];
       // SMP
       
       // SMA
       $sma0 = $this->tm->total($po,3,0);
       $data['sma_spp_piutang'] = intval($sma0['school_fee']+$sma0['cost']);
       $data['sma_osis_piutang'] = intval($sma0['osis']);
       $data['sma_com_piutang'] = intval($sma0['computer']);
       $data['sma_praktek_piutang'] = intval($sma0['practical']);
       $data['sma_bantuan_piutang'] = intval($sma0['aid_foundation']);
       $data['sma_total_piutang'] = $sma0['school_fee']+$sma0['cost']+$sma0['osis']+$sma0['computer']+$sma0['practical'];
       
       $sma1 = $this->tm->total($po,3,1);
       $data['sma_spp_berjalan'] = intval($sma1['school_fee']+$sma1['cost']);
       $data['sma_osis_berjalan'] = intval($sma1['osis']);
       $data['sma_com_berjalan'] = intval($sma1['computer']);
       $data['sma_praktek_berjalan'] = intval($sma1['practical']);
       $data['sma_bantuan_berjalan'] = intval($sma1['aid_foundation']);
       $data['sma_total_berjalan'] = $sma1['school_fee']+$sma1['cost']+$sma1['osis']+$sma1['computer']+$sma1['practical'];
       
       $sma2 = $this->tm->total($po,3,2);
       $data['sma_spp_depan'] = intval($sma2['school_fee']+$sma2['cost']);
       $data['sma_osis_depan'] = intval($sma2['osis']);
       $data['sma_com_depan'] = intval($sma2['computer']);
       $data['sma_praktek_depan'] = intval($sma2['practical']);
       $data['sma_bantuan_depan'] = intval($sma2['aid_foundation']);
       $data['sma_total_depan'] = $sma2['school_fee']+$sma2['cost']+$sma2['osis']+$sma2['computer']+$sma2['practical'];
       // SMA
       
       
       // STM
       $stm0 = $this->tm->total($po,4,0);
       $data['stm_spp_piutang'] = intval($stm0['school_fee']+$stm0['cost']);
       $data['stm_osis_piutang'] = intval($stm0['osis']);
       $data['stm_com_piutang'] = intval($stm0['computer']);
       $data['stm_praktek_piutang'] = intval($stm0['practical']);
       $data['stm_bantuan_piutang'] = intval($stm0['aid_foundation']);
       $data['stm_total_piutang'] = $stm0['school_fee']+$stm0['cost']+$stm0['osis']+$stm0['computer']+$stm0['practical'];
       
       $stm1 = $this->tm->total($po,4,1);
       $data['stm_spp_berjalan'] = intval($stm1['school_fee']+$stm1['cost']);
       $data['stm_osis_berjalan'] = intval($stm1['osis']);
       $data['stm_com_berjalan'] = intval($stm1['computer']);
       $data['stm_praktek_berjalan'] = intval($stm1['practical']);
       $data['stm_bantuan_berjalan'] = intval($stm1['aid_foundation']);
       $data['stm_total_berjalan'] = $stm1['school_fee']+$stm1['cost']+$stm1['osis']+$stm1['computer']+$stm1['practical'];
       
       $stm2 = $this->tm->total($po,4,2);
       $data['stm_spp_depan'] = intval($stm2['school_fee']+$stm2['cost']);
       $data['stm_osis_depan'] = intval($stm2['osis']);
       $data['stm_com_depan'] = intval($stm2['computer']);
       $data['stm_praktek_depan'] = intval($stm2['practical']);
       $data['stm_bantuan_depan'] = intval($stm2['aid_foundation']);
       $data['stm_total_depan'] = $stm2['school_fee']+$stm2['cost']+$stm2['osis']+$stm2['computer']+$stm2['practical'];
       // STM
       
       // SMEA
       $smea0 = $this->tm->total($po,5,0);
       $data['smea_spp_piutang'] = intval($smea0['school_fee']+$smea0['cost']);
       $data['smea_osis_piutang'] = intval($smea0['osis']);
       $data['smea_com_piutang'] = intval($smea0['computer']);
       $data['smea_praktek_piutang'] = intval($smea0['practical']);
       $data['smea_bantuan_piutang'] = intval($smea0['aid_foundation']);
       $data['smea_total_piutang'] = $smea0['school_fee']+$smea0['cost']+$smea0['osis']+$smea0['computer']+$smea0['practical'];
       
       $smea1 = $this->tm->total($po,5,1);
       $data['smea_spp_berjalan'] = intval($smea1['school_fee']+$smea1['cost']);
       $data['smea_osis_berjalan'] = intval($smea1['osis']);
       $data['smea_com_berjalan'] = intval($smea1['computer']);
       $data['smea_praktek_berjalan'] = intval($smea1['practical']);
       $data['smea_bantuan_berjalan'] = intval($smea1['aid_foundation']);
       $data['smea_total_berjalan'] = $smea1['school_fee']+$smea1['cost']+$smea1['osis']+$smea1['computer']+$smea1['practical'];
       
       $smea2 = $this->tm->total($po,5,2);
       $data['smea_spp_depan'] = intval($smea2['school_fee']+$smea2['cost']);
       $data['smea_osis_depan'] = intval($smea2['osis']);
       $data['smea_com_depan'] = intval($smea2['computer']);
       $data['smea_praktek_depan'] = intval($smea2['practical']);
       $data['smea_bantuan_depan'] = intval($smea2['aid_foundation']);
       $data['smea_total_depan'] = $smea2['school_fee']+$smea2['cost']+$smea2['osis']+$smea2['computer']+$smea2['practical'];
       // SMEA
       
//     -----------------------------------------------------------------------------------------
       $sales = $this->model->where('no', $po)->get();
       $data['total'] = intval($sales->total);
       
       //keterangan
       $data['pono'] = $po;
       $data['dates'] = tglincomplete($sales->dates);
       $data['user'] = '';
       $data['cur'] = $this->currency->get_code($sales->currency);
       $data['log'] = $this->session->userdata('log');

       // property display
       $data['logo'] = $this->properti['logo'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];
       $data['pname'] = $this->properti['name'];

//       if ($type){ $this->load->view('sales_invoice_blank', $data); } else { $this->load->view('sales_order_invoice', $data); }
       
       $this->load->view('invoice', $data);
       
   }

   private function get_romawi($val)
   {
       switch ($val)
       {
           case 01: $val = 'I'; break;
           case 02: $val = 'II'; break;
           case 03: $val = 'III'; break;
           case 04: $val = 'IV'; break;
           case 05: $val = 'V'; break;
           case 06: $val = 'VI'; break;
           case 07: $val = 'VII'; break;
           case 08: $val = 'VIII'; break;
           case 09: $val = 'IX'; break;
           case 10: $val = 'X'; break;
           case 11: $val = 'XI'; break;
           case 12: $val = 'XII'; break;
       }
       return $val;
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('sales/','<span>back</span>', array('class' => 'back')));

        $data['dept'] = $this->dept->combo_all();
        $data['year'] = $this->financial->combo_active();
        
        $this->load->view('registration_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        

        $year = $this->input->post('cyear');
        $dept = $this->input->post('cdept');
        $type = $this->input->post('ctype');

        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $data['dept'] = $this->dept->get_name($dept);
        $data['fyear'] = $year;
        
        $data['start'] = $this->input->post('tstart');
        $data['end'] = $this->input->post('tend');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['result'] = $this->regmodel->report($dept, $this->input->post('tstart'), $this->input->post('tend'), $year, $this->input->post('cstts'))->result();
        
        if ($type==0){ $this->load->view('registration_report', $data); }
        elseif ($type==1){ $this->load->view('registration_report_pivot', $data); }
        elseif ($type==2){ $this->load->view('finance_registration_report', $data); }
        elseif ($type==3){ $this->load->view('finance_registration_pivot', $data); }
        elseif ($type==4){ $this->load->view('uniform_report', $data); }
        
    }


// ====================================== REPORT =========================================
    
    public function valid_nol($val)
    {
        if ($val <= 0)
        {
            $this->form_validation->set_message('valid_nol', "Invalid Total...!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_payment($type)
    {
        $p1 = $this->input->post('tp1');
        $p2 = $this->input->post('tp2');
        $total = $this->input->post('ttotal');
        
        $date = $this->input->post('tregdate');
        $p2date = $this->input->post('tp2date');
        
        if ($type == 'credit')
        {
            if (intval($p1+$p2) > $total){ $this->form_validation->set_message('valid_payment', "Invalid Amount...!"); return FALSE; }
            elseif ($p2date < $date){ $this->form_validation->set_message('valid_payment', "Invalid P2 Date...!"); return FALSE; }
            else{ return TRUE; }
        }
        else { return TRUE; }
    }
    
    public function valid_confirmation($type=null,$uid)
    {
        $val = $this->model->where('id',$uid)->get();

        if ($val->approval == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Registration has been approved..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_nis($nis)
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

    
    // replacement 
    
    public function replacement()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'].' : Replacement';
        $data['main_view'] = 'registration_placement_view';
	$data['form_action'] = site_url($this->title.'/replacement_search');
        $data['form_action_select'] = site_url($this->title.'/transfer');
        $data['link'] = array('link_back' => anchor('registration_reference','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo();
        $data['grade'] = $this->grade->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $st = new Student();
        $result = $st->where('grade_id', 0)->where('active', 1)->get($this->modul['limit'], $offset);
        $num_rows = $st->where('grade_id', 0)->where('active', 1)->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/replacement');
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
            $this->table->set_heading('#', 'No', 'NIS', 'Name', 'Department', 'Faculty', 'Gender');

            $i = 0 + $offset;
            foreach ($result as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->students_id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $sales->nisn, strtoupper($sales->name), $this->dept->get_name($sales->dept_id), $this->faculty->get_name($sales->faculty), strtoupper($sales->genre)
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
    
    public function replacement_search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'].' : Replacement';
        $data['main_view'] = 'registration_placement_view';
	$data['form_action'] = site_url($this->title.'/replacement_search');
        $data['form_action_select'] = site_url($this->title.'/transfer');
        $data['link'] = array('link_back' => anchor($this->title.'/replacement','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo_all();
        $data['faculty'] = $this->faculty->combo();
        $data['grade'] = $this->grade->combo();

	// ---------------------------------------- //
        $st = new Student();
        $result = $st->where('grade_id', 0)->where('active', 1)->where('dept_id', $this->input->post('cdept'))->where('faculty', $this->input->post('cfaculty'))->get();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#', 'No', 'NIS', 'Name', 'Department', 'Faculty', 'Gender');

        $i = 0;
        foreach ($result as $sales)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->students_id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                form_checkbox($datax), ++$i, $sales->nisn, strtoupper($sales->name), $this->dept->get_name($sales->dept_id), $this->faculty->get_name($sales->faculty), strtoupper($sales->genre)
            );
        }

        $data['table'] = $this->table->generate();
        
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function transfer()
    {
      $cek = $this->input->post('cek');
      $st = new Student();
      
      $ps = new Period();
      $ps->get();
      
      $dept   = $this->input->post('cdept');
      $fac    = $this->input->post('cfaculty');
      $grade  = $this->input->post('cgrade');

      if($cek)
      {
        $jumlah = count($cek);
        for ($i=0; $i<$jumlah; $i++)
        {
//            $name = $st->where('students_id',$cek[$i])->get()->name;
            $joined = $st->where('students_id', $cek[$i])->get();
            $val = array('dept_id' => $dept, 'faculty' => $fac, 'grade_id' => $grade);
            $st->where('students_id ', $cek[$i])->update($val, TRUE);
            
            $this->recap->add_trans($cek[$i],$dept, $grade, $joined->joined, 'in', 1, $ps->month, $ps->year, 'REG:'.tglin($joined->joined), 'Registration');

            $this->session->set_flashdata('message', "$jumlah $this->title successfully transfered..!");
        }
      }
      else
      { $this->session->set_flashdata('message', "No $this->title Selected..!!"); }
      redirect($this->title);
    }
    
}

?>