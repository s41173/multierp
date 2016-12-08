<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tuition_transaction extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Students_model','',TRUE);
        $this->load->model('Tuition_trans_model','tm',TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency   = $this->load->library('currency_lib');
        $this->user       = new Admin_lib();
        $this->journal    = $this->load->library('journal_lib');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->tuition    = new Tuition_lib();
        $this->student    = new Student_lib();
        $this->dept       = new Dept_lib();
        $this->paymentstatus = new Payment_status_lib();
        $this->year        = new Financial_lib();
        $this->fee         = new Regcost_lib();
        $this->grade       = new Grade_lib();
        $this->scholarship = new Scholarship_trans_lib();
        $this->over        = new Tuition_over_lib();
        
        $this->model = new Tuitiontrans();

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency,$model,$fee,$grade,$scholarship,$over;
    private $user,$tax,$journal,$tuition,$student,$dept,$paymentstatus,$year;
    
    private $atts = array('width'=> '800','height'=> '600',
                          'scrollbars' => 'yes','status'=> 'yes',
                          'resizable'=> 'yes','screenx'=> '0',
                          'screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                          'screeny'=> '0','class'=> 'print fancymini','title'=> '', 
                          'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');
    
    private $atts2 = array('width'=> '500','height'=> '300',
                          'scrollbars' => 'yes','status'=> 'yes',
                          'resizable'=> 'yes','screenx'=> '0',
                          'screenx' => '\'+((parseInt(screen.width) - 500)/2)+\'',
                          'screeny'=> '0','class'=> 'delete','title'=> 'print', 
                          'screeny' => '\'+((parseInt(screen.height) - 300)/2)+\'');

    function index(){ $this->get_last(); }

    function get_last($no=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'tuition_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last');
        $data['link'] = array('link_back' => anchor('tuitions','<span>back</span>', array('class' => 'back')));

        $data['dept'] = $this->dept->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
        if ($no)
        { $saless = $this->model->where('tuition', $no)->order_by('id', 'desc')->get(); $num_rows = $this->model->where('tuition', $no)->count(); }
        else { $saless = $this->model->order_by('id', 'desc')->get($this->modul['limit'], $offset); $num_rows = $this->model->count(); }

   
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
            $this->table->set_heading('No', 'Transcode', 'Period', 'Date', 'Fee Type', 'Student', 'Department', 'Grade', 'Balance', 'Month', 'Aid', 'Type', 'User', 'Action');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, '0'.$sales->id, $sales->financial_year, tglin($this->tuition->get_journal_dates($sales->tuition)), $this->fee->get_name($sales->fee_type), strtoupper($this->student->get_name($sales->student)), $this->dept->get_name($this->student->get_dept($sales->student)), $this->grade->get_name($this->student->get_grade($sales->student)), number_format($sales->amount), $this->paymentstatus->months_name($this->month_stts($sales->month)), $this->aid_status($sales->scholarship), $this->stts($sales->type), $this->user->get_username($sales->user),
                    anchor($this->title.'/invoice/'.$sales->id,'<span>print</span>',$this->atts).' '.
                    anchor_popup($this->title.'/void/'.$sales->id.'/'.$sales->tuition,'<span>print</span>',$this->atts2)
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
    
    private function aid_status($val){ if ($val == 1){ $res = 'Y'; }else{ $res='N'; } return $res; }

    private function search_criteria($date=null,$dept=null,$type=null,$value=null,$ptype=null,$fee=null)
    {
        if ($date){ return $this->tm->search($date,$dept,$ptype,$fee)->result(); }
        elseif ($value)
        {
           if ($type == 0) { $this->model->where('student', $this->student->get_id_by_no($value)); }
           else { $this->model->where('student', $this->student->get_id_by_name($value,$dept)); }
        }
        return $this->model->get();
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'tuition_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();

        $saless = $this->search_criteria($this->input->post('tdate'), $this->input->post('cdept'), $this->input->post('ctype'),$this->input->post('tvalue'),
                                         $this->input->post('cptype'), $this->input->post('cfee'));
//        $saless = $this->model->where('tuition',  $this->tuition->get_journal_no($this->input->post('tdate')))->get();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
         $this->table->set_heading('No', 'Transcode', 'Period', 'Date', 'Fee Type', 'Student', 'Department', 'Grade', 'Balance', 'Month', 'Aid', 'Type', 'User', 'Action');

        $i = 0;
        foreach ($saless as $sales)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, '0'.$sales->id, $sales->financial_year, tglin($this->tuition->get_journal_dates($sales->tuition)), $this->fee->get_name($sales->fee_type), strtoupper($this->student->get_name($sales->student)), $this->dept->get_name($this->student->get_dept($sales->student)), $this->grade->get_name($this->student->get_grade($sales->student)), number_format($sales->amount), $this->paymentstatus->months_name($this->month_stts($sales->month)), $this->aid_status($sales->scholarship), $this->stts($sales->type), $this->user->get_username($sales->user),
                anchor($this->title.'/invoice/'.$sales->id,'<span>print</span>',$this->atts).' '.
                anchor_popup($this->title.'/void/'.$sales->id.'/'.$sales->tuition,'<span>print</span>',$this->atts2)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

//    ===================== approval ===========================================

    private function month_stts($val)
    {
        $res=0;
        switch ($val)
        {
          case 'p1':$res = 1; break;
          case 'p2':$res = 2; break;
          case 'p3':$res = 3; break;
          case 'p4':$res = 4; break;
          case 'p5':$res = 5; break;
          case 'p6':$res = 6; break;
          case 'p7':$res = 7; break;
          case 'p8':$res = 8; break;
          case 'p9':$res = 9; break;
          case 'p10':$res = 10; break;
          case 'p11':$res = 11; break;
          case 'p12':$res = 12; break;
        }
        return $res;
    }
    
    private function stts($val=0)
    { 
        if ($val==0){ return 'B';} elseif($val==1){return 'N';} elseif($val==2){return 'F';}
    }
    
//    ===================== approval ===========================================

    function void($uid,$po)
    {
        $data['form_action'] = site_url($this->title.'/void_process/'.$uid.'/'.$po);
        $value = $this->model->where('id', $uid)->get();
        
        $data['user'] = $this->user->get_username($value->user);
        $data['log'] = $value->log;
        $data['date'] = tglin($value->dates);
        
        $this->load->view('tuition_void', $data);
    }
    
    function void_process($uid,$po)
    {
        if ( $this->valid_tuition_no($po) == TRUE )
        {
            $this->form_validation->set_rules('tdesc', 'Void Description', 'required');
            $value = $this->model->where('id', $uid)->get();
            
            if ($this->form_validation->run($this) == TRUE)
            {
              $void = new Void_lib();
              $void->save('tuition', 'TJ-00'.$value->tuition, $value->dates, $this->input->post('tdesc'), $this->user->get_userid($this->session->userdata('username')), $this->session->userdata('log'));
             
              $this->delete($uid, $po);
              $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
              echo 'true';
            }
            else{ echo validation_errors(); }
        }
        else 
        { $this->session->set_flashdata('message', "1 $this->title rollback..!"); echo 'true';  }
    }
    
    private function delete($uid,$po)
    {
        $value = $this->model->where('id', $uid)->get();

        $this->tuition->update_balance($po, $value->school_fee, $value->practical, 
                                       $value->computer, $value->osis, $value->cost, 
                                       $value->aid_foundation, 1);
            
        $this->paymentstatus->remove($value->student,$value->financial_year,$value->month);
            
        // cek scholarship
        if ($value->scholarship == 1){ $this->scholarship->add_period($value->student, $value->financial_year); }
//            
        $value->delete();
//
//            $this->journal->remove_journal('CSJ',$po); // delete journal
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
    }

    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['dept'] = $this->dept->combo();
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        $data['year'] = $this->year->combo_active();
        $data['transdate'] = null;
        
        $this->load->view('tuition_form', $data);
        
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'tuition_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['dept'] = $this->dept->combo();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
        $data['year'] = $this->year->combo_active();

        // create tuition & journal gl
        if ($this->input->post('tdate')){ $this->tuition->create_journal($this->input->post('tdate'),$this->input->post('ccur'),$this->session->userdata('log')); }
        
	// Form validation
        $this->form_validation->set_rules('tid', 'Student', 'required');
//        $this->form_validation->set_rules('tdate', 'Transaction Date', 'required|callback_start_valid_period|callback_valid_period|callback_valid_tuition['.$this->input->post('ccur').']');
        $this->form_validation->set_rules('tdate', 'Transaction Date', 'required|callback_valid_period|callback_valid_tuition['.$this->input->post('ccur').']');
        $this->form_validation->set_rules('creceipt', 'Receipt Type', 'required');
        $this->form_validation->set_rules('tperiod', 'Payment Periode', 'required|numeric|callback_valid_get_period');
        $this->form_validation->set_rules('tschool', 'School Fee', 'required|numeric');
        $this->form_validation->set_rules('tosis', 'OSIS', 'required|numeric');
        $this->form_validation->set_rules('tcom', 'Computer Fee', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Practice Fee', 'required|numeric');
        $this->form_validation->set_rules('tcost', 'Cost Fee', 'required|numeric');
        $this->form_validation->set_rules('tbos', 'Aid - BOS Fee', 'required|numeric');
        $this->form_validation->set_rules('tfound', 'Aid - Foundation Fee', 'required|numeric|callback_valid_aid');
        $this->form_validation->set_rules('ttotal', 'Total', 'required|numeric');
        
      //  $this->form_validation->set_rules('cgrade', 'Grade Level', 'required');
        $this->form_validation->set_rules('tfee', 'Fee Type', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {   
            $this->model->tuition = $this->tuition->get_journal_no($this->input->post('tdate'),$this->input->post('ccur'));
            $this->model->student = $this->input->post('tid');
            $this->model->dates = $this->input->post('tdate');
            $this->model->receipt_type = $this->input->post('creceipt');
            $this->model->school_fee = $this->input->post('tschool');
            $this->model->practical = $this->input->post('tpractice');
            $this->model->computer = $this->input->post('tcom');
            $this->model->osis = $this->input->post('tosis');
            $this->model->cost = $this->input->post('tcost');
            $this->model->aid_foundation = $this->input->post('tfound');
            $this->model->aid_goverment  = $this->input->post('tbos');
            $this->model->amount = $this->input->post('ttotal');
            $this->model->month = 'p'.$this->input->post('tperiod');
            $this->model->type = $this->paymentstatus->get_period_type($this->input->post('tperiod'),$this->input->post('cyear'));
            $this->model->financial_year = $this->input->post('cyear');
            $this->model->scholarship = $this->input->post('caid');
            $this->model->log = $this->session->userdata('log');
            $this->model->user = $this->user->get_userid($this->session->userdata('username'));
            $this->model->fee_type = $this->input->post('tfeeid');
            
            if ($this->input->post('caid') == 1){ $this->scholarship->min_period($this->input->post('tid'), $this->input->post('cyear')); }
            
            $this->tuition->update_balance($this->model->tuition, $this->input->post('tschool'),
                                           $this->input->post('tpractice'), $this->input->post('tcom'), $this->input->post('tosis'),
                                           $this->input->post('tcost'), $this->input->post('tfound')
                                          );
            
//            $year = new Financial_lib();
            $this->paymentstatus->create($this->input->post('tid'),$this->input->post('cyear'),$this->model->month,$this->input->post('tdate'));
            $this->model->save();
                    
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/');
            echo 'true';
        }
        else
        {
//              $this->load->view('tuition_form', $data);
            echo validation_errors();
        }

    }

    public function valid_tuition($date,$cur)
    {
        $no = $this->tuition->get_journal_no($date,$cur);
        if ($this->tuition->cek_approval($no) == FALSE)
        {
            $this->form_validation->set_message('valid_tuition', "Tuition TJ-00$no No already approved.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_tuition_no($no)
    {
        if ($this->tuition->cek_approval($no) == FALSE)
        {
            $this->form_validation->set_message('valid_tuition_no', "Tuition TJ-00$no No already approved.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_aid($found)
    {
        if ($found > 0)
        {
            if (!$this->input->post('caid')){ $this->form_validation->set_message('valid_aid', "Select Aid Type..!"); return FALSE; }
            else { return TRUE; }
        }
        else { return TRUE; }
    }
    
    public function valid_get_period($val)
    {
        if ($val == 0){ $this->form_validation->set_message('valid_get_period', "Invalid Period Payment..!"); return FALSE; }
        else { return TRUE; }
    }
    
    public function start_valid_period($date=null)
    {   
        if ($this->input->post('tperiod') == 1)
        {
            $monthrules = $this->paymentstatus->months_from_period($this->input->post('tperiod')); // 7
            $month = date('n', strtotime($date)); // 12

            if (intval($month) < intval($monthrules))
            {
                $this->form_validation->set_message('start_valid_period', "Invalid Start Period.!");
                return FALSE;
            }
            else {  return TRUE; } 
        }
        else { return TRUE; }
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

// ===================================== PRINT ===========================================
   
   function invoice($id=null)
   {
      $data['pono'] = $id;
      $this->load->view('tuition_invoice_form', $data);
   }
   
   function print_invoice($id,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Card Invoice'.$this->modul['title'];
       $trans = $this->model->where('id', $id)->get();

       $data['name'] = strtoupper($this->student->get_name($trans->student));
       $data['year'] = $trans->financial_year;
       $data['transcode'] = '0'.$id;
       $data['dates'] = $trans->dates;
       $data['month'] = $this->paymentstatus->months_name($this->month_stts($trans->month));
       $data['tuition'] = $this->fee->get_name($trans->fee_type);
       $data['school'] = $trans->school_fee;
       $data['osis'] = $trans->osis;
       $data['computer'] = $trans->computer;
       $data['practical'] = $trans->practical;
       $data['cost'] = $trans->cost;
       $data['aid'] = intval($trans->aid_foundation+$trans->aid_government);
       $data['amount'] = $trans->amount;
       $data['currency'] = $this->tuition->get_journal_currency($trans->tuition);
       $data['user'] = $this->user->get_username($trans->user);
       
       $terbilang = $this->load->library('terbilang');
       if ($this->tuition->get_journal_currency($trans->tuition) == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($trans->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($trans->amount)); }
       
       $data[$trans->month]['amount1'] = number_format($trans->school_fee + $trans->cost + $trans->osis);       
       $data[$trans->month]['amount2'] = number_format($trans->practical + $trans->computer);
       $data[$trans->month]['total'] = number_format($trans->amount);
       $data[$trans->month]['dates'] = tglin($this->tuition->get_journal_dates($trans->tuition));
       $data[$trans->month]['log'] = $this->stts($trans->type).' / '.$trans->log;
       
//       echo $trans->month;
       
       $year = new Financial_lib();

       // property display
       $data['logo'] = $this->properti['logo'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];
       

       $cr = new Card_lib();
       $card = $cr->get($this->student->get_dept($trans->student));
       
       if ($type == 'format'){ $this->load->view($card, $data); }else{$this->load->view('tuition_invoice', $data); } 
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

// ===================================== REPOT ===========================================
   function report()
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('sales/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['dept']     = $this->dept->combo_all();
        $data['period']   = $this->year->combo_all();
        $data['usercombo']  = $this->user->combo_criteria('ar');
        
        $this->load->view('tuition_report_panel', $data);
   }
   
   function report_process()
   {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $status = $this->input->post('cstatus');
        $dept = $this->input->post('cdept');
        $fee = $this->input->post('cfee');
        $period = $this->input->post('cperiod');
        $user = $this->input->post('cuser');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['tuitions'] = $this->tm->report($start,$end,$dept,$status,$fee,$period,$user)->result();
        
        if ($this->input->post('creport') == 0){ $this->load->view('tuition_report', $data); }
        elseif ($this->input->post('creport') == 1){ $this->load->view('tuition_summary', $data); }
        elseif ($this->input->post('creport') == 2){ $this->load->view('tuition_pivot', $data); }
        
   }

   
// ===================================== REPOT ===========================================
   
   
// ==================================== FUNGSI DETAIL POP UP RECAPITULATION ===============
   
    function get_list($dept,$grade,$monthperiod,$financialyear,$type=null,$scholar=null,$jenis=null)
    {
       $this->acl->otentikasi1($this->title);
       $data['fee'] = $this->fee->get_by_criteria($dept, $this->grade->get_level($grade));
       $data['dept'] = $dept;
       $data['grade'] = $grade;
       $data['monthperiod'] = $monthperiod;
       $data['year'] = $financialyear;
       $data['type'] = $type;
       $data['scholar'] = $scholar;
       $data['jenis'] = $jenis;
       
       if (!$jenis){ $this->load->view('tuition_list', $data);   }
       elseif ($jenis == 'osis'){ $this->load->view('tuition_list_osis', $data);   }
       elseif ($jenis == 'computer'){ $this->load->view('tuition_list_computer', $data); }
       elseif ($jenis == 'praktek'){ $this->load->view('tuition_list_praktek', $data); }
    }
    
    function get_details($dept,$grade,$monthperiod,$financial)
    {
       $this->acl->otentikasi1($this->title); 
       $year = $this->paymentstatus->year_name($monthperiod, $financial);
       $month = $this->paymentstatus->months_from_period($monthperiod);
       //$data['source'] = site_url()."/$this->title/get_json_details/$dept/$grade/$month/$year";
       
       $data['result'] = $this->tm->monthly_report_based_financial($dept,$grade,$month,$year,$financial)->result();    
       $this->load->view('tuition_list_grid', $data);  
    }
   
// ===================================== AJAX ============================================
   
   function autocomplete($filter='after')
   {
        
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('students')->like('name',$keyword,$filter)->where('active', 1)->get();
      
      // format keluaran di dalam array
      foreach($data->result() as $row)
      {
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>$row->name.' - '.$row->nisn,
            'data'   => $row->students_id.'|'.$row->nisn.'|'.$this->dept->get_name($row->dept_id).'|'.
                        $this->grade->get_name($row->grade_id).'|'.
                        $row->dept_id.'|'.$row->grade_id
         );
      }

      // minimal PHP 5.2
      echo json_encode($arr);
    }   
      
   function get_student_id()
   {
//       $dept = $this->input->post('dept');
//       $type = $this->input->post('type');
       $value = $this->input->post('value');
       
       $vid = 0;
       $st = new Student_lib();
       
       $vid = $st->get_id_by_no($value); 
       $nis = $st->get_nisn($vid);
       $name = $st->get_name($vid);
       $dept = $st->get_dept($vid);
       $grade = $st->get_grade($vid);
       echo $vid.'|'.$nis.'|'.$name.'|'.$this->dept->get_name($dept).'|'.$this->grade->get_name($grade).'|'.
            $dept.'|'.$grade;
   }

   function get_fee_type()
   { 
      $grade = $this->input->post("cgrade");
      $dept = $this->input->post("cdept");
      $level = $this->grade->get_level($grade);
      $sid = $this->input->post('tsid');
      $year = $this->input->post('cyear');
      $fee = new Regcost_lib();
      
      // mencari beasiswa
      if ($this->scholarship->valid_trans($sid, $year) == FALSE)
      {
        echo $this->scholarship->get_fee($this->scholarship->get_scholarship_id($sid, $year)).'|'. 
         $this->fee->get_name($this->scholarship->get_fee($this->scholarship->get_scholarship_id($sid, $year)));
      }
      elseif ($this->over->cek_student_active($sid) == FALSE) // mencari tuition over payment
      {
          echo $this->over->get_fee($sid).'|'.$this->fee->get_name($this->over->get_fee($sid));
      }
      else 
      {
         // mencari default value
        $val = $this->db->from('grade')->where('dept_id', $dept)->where('grade_id', $grade)->where('level',$level)->get()->row();  
        echo $val->fee.'|'.$fee->get_name($val->fee);
      } 
   }
   
   function get_fee()
   {
      $data = $this->db->from('reg_cost')->where('id', $this->input->post("cfee"))->get()->row();
      if (!$data){ echo "0|0|0|0|0"; }
      else { echo $data->school.'|'.$data->osis.'|'.$data->computer.'|'.$data->practice.'|'.$data->aid; }
   }
}

?>