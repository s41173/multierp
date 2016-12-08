<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scholarship extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Scholarship_model', 'sm', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib();
        $this->unit = $this->load->library('unit_lib');
        $this->vendor = $this->load->library('vendor_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = new Journalgl_lib();
        $this->category = $this->load->library('categories_lib');
        $this->account = $this->load->library('account_lib');
        $this->student = new Student_lib();
        $this->dept    = new Dept_lib();
        $this->fee       = new Regcost_lib();
        $this->financial = new Financial_lib();
        $this->grade     = new Grade_lib();
        $this->foundation = new Foundation_lib();
        $this->scholarship = new Scholarship_trans_lib();
        
        $this->model = new Scholarships();
    }

    private $properti, $modul, $title, $account, $student, $dept, $fee, $grade, $scholarship;
    private $vendor,$user,$journal,$currency,$unit,$model,$category, $financial, $foundation;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
       $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'scholarship_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all();
        $data['currency'] = $this->currency->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $scholarships = $this->model->order_by('dept_id','asc')->get($this->modul['limit'], $offset);
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Name', 'Dept', 'Level', 'Fee Type', 'Period (Months)', 'Action');

            $i = 0 + $offset;
            foreach ($scholarships as $scholarship)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $scholarship->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'SC-'.$scholarship->id, $scholarship->currency, $scholarship->name, $this->dept->get_name($scholarship->dept_id), $scholarship->level, $this->fee->get_name($scholarship->fee_type), $scholarship->period,
                 //   anchor($this->title.'/update/'.$scholarship->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$scholarship->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('scholarship_view', $data);
    }

    private function get_search($cur,$dept,$grade)
    {
        if ($cur){ $this->model->where('currency', $cur); }
        elseif($dept){ $this->model->where('dept_id', $dept); }
        elseif($grade){ $this->model->where('level', $grade); }
        return $this->model->get();
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'scholarship_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all();
        $data['currency'] = $this->currency->combo_all();

        $scholarships = $this->get_search($this->input->post('ccur'), $this->input->post('cdept'), $this->input->post('cgrade'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Name', 'Dept', 'Level', 'Fee Type', 'Period (Months)', 'Action');

        $i = 0;
        foreach ($scholarships as $scholarship)
        {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $scholarship->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'SC-'.$scholarship->id, $scholarship->currency, $scholarship->name, $this->dept->get_name($scholarship->dept_id), $scholarship->level, $this->fee->get_name($scholarship->fee_type), $scholarship->period,
            //    anchor($this->title.'/update/'.$scholarship->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$scholarship->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('scholarship_view', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'C'; break; case 1: $val = 'S'; break; } return $val; }
//    ===================== scholarshipproval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $this->acl->otentikasi3($this->title);
        $scholarship = $this->model->where('id',$pid)->get();
        $recaptrans = new Student_recap_trans_lib();
        $ps = new Period(); $ps = $ps->get();

        if ($scholarship->scholarshipproved == 1){ $this->session->set_flashdata('message', "$this->title already approved..!");}
        if ($this->student->cek_active($scholarship->student) == FALSE){  $this->session->set_flashdata('message', "$this->title failure [Students Non Active]..!"); }
        if ($this->valid_period($scholarship->dates) == FALSE){ $this->session->set_flashdata('message', "Invalid Period..!"); }
        else
        {
            $recaptrans->min_trans($scholarship->dept_id, $scholarship->grade_id, $scholarship->dates, 'out', 1, $ps->month, $ps->year, 'MT-00'.$pid, $this->scholarshiplib->get_name($scholarship->type));
            $this->student->inactive($scholarship->student);
            $scholarship->approved = 1;
            $scholarship->save();
            $scholarship->clear();

            $this->create_journal($pid);
            $this->session->set_flashdata('message', "$this->title MT-00$pid confirmed..!"); // set flash data message dengan session 
            
        }
        redirect($this->title);    
    }
    

    function rollback($pid)
    {
        $this->acl->otentikasi3($this->title);
        $scholarship = $this->model->where('id',$pid)->get();
        $recaptrans = new Student_recap_trans_lib();
        $ps = new Period(); $ps = $ps->get();
        
        if ($this->student->cek_active($scholarship->student) == FALSE)
        {
            $recaptrans->add_trans($scholarship->dept_id, $scholarship->grade_id, date('Y-m-d'), 'in', 1, $ps->month, $ps->year, 'MT-00'.$pid, 'Rollback :'.$this->scholarshiplib->get_name($scholarship->type));
            $this->student->active($scholarship->student);
            $scholarship->approved = 0;
            $scholarship->save();
            $scholarship->clear();  
            $this->session->set_flashdata('message', "Rollback success [ Students ".  $this->student->get_nisn($scholarship->student)." Was Rollback ]..!");
        }
        else { $this->session->set_flashdata('message', "Rollback failure [ Students ".  $this->student->get_nisn($scholarship->student)." Not Found In Inactive Module ]..!"); }
        redirect($this->title);
    }
    
    private function cek_journal($date,$currency)
    {
        if ($this->journal->valid_journal($date,$currency) == FALSE)
        {
           $this->session->set_flashdata('message', "Journal for [".tgleng($date)."] - ".$currency." scholarshipproved..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $scholarship = $this->model->where('id', $po)->get();

        if ( $scholarship->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - MT-00$scholarship->id approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }

//    ===================== scholarshipproval ===========================================


    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $value = $this->model->where('id',$uid)->get();
        $year = $this->financial->get();
        
        if ($this->scholarship->valid_trans($value->student, $year) == FALSE)
        { $this->session->set_flashdata('message', "1 $this->title student's still active rollback transaction..!");}
        else
        {
           $value->delete(); 
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
        }
        redirect($this->title);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo();
        
        $this->load->view('scholarship_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'purchase_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo();
        
	// Form validation
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('cgrade', 'Grade', 'required');
        $this->form_validation->set_rules('tname', 'Scholarship Name', 'required');
        $this->form_validation->set_rules('cfee', 'Scholarship Type', 'required|callback_valid_type');
        $this->form_validation->set_rules('tperiod', 'Period', 'required|numeric');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->currency       = $this->input->post('ccurrency');
            $this->model->dept_id        = $this->input->post('cdept');
            $this->model->level          = $this->input->post('cgrade');
            $this->model->name           = $this->input->post('tname');
            $this->model->fee_type       = $this->input->post('cfee');
            $this->model->period         = $this->input->post('tperiod');
            $this->model->desc           = $this->input->post('tdesc');
            
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/');
            echo 'true';
        }
        else
        {
//              $this->load->view('scholarship_form', $data);
            echo validation_errors();
        }

    }

    function add_trans($pid=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $scholarship = $this->model->where('id', $pid)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = ' Update '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);
        $data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo();
        $data['type'] = $this->scholarshiplib->combo();
        $data['year'] = $this->financial->combo_active();
        $data['fee'] = $this->fee->combo_criteria($scholarship->dept_id, $this->grade->get_level($scholarship->grade_id));

        $data['default']['currency'] = $scholarship->currency;
        $data['default']['year']     = $scholarship->financial_year;
        $data['default']['type']     = $scholarship->type;
        
        $data['default']['date']    = $scholarship->dates;
        $data['default']['studentname'] = $this->student->get_name($scholarship->student);
        $data['default']['sid'] = $scholarship->student;
        $data['default']['dept'] = $this->dept->get_name($scholarship->dept_id);
        $data['default']['grade'] = $this->grade->get_name($scholarship->grade_id);
        $data['default']['teacher'] = $scholarship->teacher;
        $data['default']['note']    = $scholarship->notes;
        $data['default']['amount']  = $scholarship->amount;
        $data['default']['period']  = $scholarship->receivable;
        $data['default']['fee']  = $scholarship->fee_type;
        $data['default']['acc']  = $scholarship->acc;
        $data['default']['stts']  = $scholarship->settled;
        
        $this->load->view('scholarship_update', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($pid=null)
    {
        $this->cek_confirmation($pid,'add_trans');
        
        $this->form_validation->set_rules('ccost', 'Cost Type', 'required');
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('scholarship_id' => $pid, 'cost' => $this->input->post('ccost'),
                           'notes' => $this->input->post('tnotes'),
                           'staff' => $this->input->post('tstaff'),
                           'amount' => $this->input->post('tamount'));
            
            $this->transmodel->add($pitem);
            $this->update_trans($pid);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($pid=null)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($pid,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('cyear', 'Financial Year', 'required');
        $this->form_validation->set_rules('ctype', 'Scholarship Type', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tsid', 'Student ID', 'required');
        $this->form_validation->set_rules('tdept', 'Department', 'required');
        $this->form_validation->set_rules('tgrade', 'Grade', 'required');
        $this->form_validation->set_rules('tteacher', 'Teacher', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('cfee', 'Tuition Fee', 'required');
        $this->form_validation->set_rules('tperiod', 'Period', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        { 
            $this->model->where('id',$pid)->get();
            
            $this->model->currency       = $this->input->post('ccurrency');
            $this->model->financial_year = $this->input->post('cyear');
            $this->model->type           = $this->input->post('ctype');
            $this->model->dates          = $this->input->post('tdate');
            $this->model->teacher        = $this->input->post('tteacher');
            $this->model->notes          = $this->input->post('tnote');
            $this->model->fee_type       = $this->input->post('cfee');
            $this->model->receivable     = $this->input->post('tperiod');
            $this->model->amount         = $this->input->post('tamount');
            $this->model->user           = $this->session->userdata("username");
            $this->model->log            = $this->session->userdata('log');
            $this->model->acc            = $this->input->post('cacc');
            $this->model->settled        = $this->input->post('cstts');
            
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/add_trans/'.$pid);
//            echo 'true';
        }
        else
        {
//            $this->load->view('purchase_transform', $data);
            echo validation_errors();
        }
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
    
    public function valid_type($type)
    {
        $this->model->where('name', $this->input->post('tname'));
        $val = $this->model->where('fee_type', $type)->count();
        
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_type', "Invalid Scholarship.!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_type($type)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $this->model->where('name', $this->input->post('tname'));
        $val = $this->model->where('fee_type', $type)->count();
        
        if ($val > 0)
        {
            $this->form_validation->set_message('validating_type', "Invalid Scholarship.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

// ===================================== PRINT ===========================================
    

   function invoice($pid=null)
   {
       $this->acl->otentikasi2($this->title);
       $scholarship = $this->model->where('id', $pid)->get();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono']     = $pid;
       $data['podate']   = tglin($scholarship->dates);
       $data['notes']    = $scholarship->notes;
       $data['user']     = $scholarship->user;
       $data['currency'] = $scholarship->currency;
       $data['log']      = $this->session->userdata('log');
       $data['teacher']  = $scholarship->teacher;
       $data['type']     = $this->scholarshiplib->get_name($scholarship->type);
       
       $data['year']        = $scholarship->financial_year;
       $data['studentname'] = $this->student->get_name($scholarship->student);
       $data['sid']         = $this->student->get_nisn($scholarship->student);
       $data['dept']        = $this->dept->get_name($scholarship->dept_id);
       $data['grade']       = $this->grade->get_name($scholarship->grade_id);
       
       $data['period']      = $scholarship->receivable;
       $data['fee']         = $this->fee->get_name($scholarship->fee_type);
       $data['amount']      = $scholarship->amount;
       $data['manager']     = $this->foundation->get_name(8);
       
       if($scholarship->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

       $this->load->view('scholarship_invoice', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();        
        $data['type'] = $this->scholarshiplib->combo_all();
        $data['dept'] = $this->dept->combo_all();
        
        $this->load->view('scholarship_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur   = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end   = $this->input->post('tend');
        $type  = $this->input->post('ctype');
        $acc   = $this->input->post('cacc');
        $dept  = $this->input->post('cdept');
        $grade = $this->input->post('cgrade');
        $stts = $this->input->post('cstts');
        
        $this->model->where_between('dates', $start, $end);

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['account'] = ucfirst($acc);
        $data['rundate'] = tglin(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['result'] = $this->mt->report($cur,$start,$end,$type,$dept,$grade,$acc,$stts)->result();
       
        $page = 'scholarship_report'; 
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
        
    }

// ====================================== REPORT =========================================

   function get_miss_payment()
   {
       $sid = $this->input->post('sid');
       $year = $this->input->post('year');
       $ps = new Payment_status_lib();
       echo $ps->get_miss_payment($sid, $year);
   }
   
   function calculate_scholarship()
   {
       $regcost = new Regcost_lib();
       $period = $this->input->post('period');
       $fee = $regcost->get_amount($this->input->post('fee'));
       echo intval($period*$fee);
   }
   
   // ajax function
   
   function get_combo()
   {
      $level = $this->grade->get_level($this->input->post('cgrade'));
       
      $values = $this->db->from('scholarship')->where('dept_id', $this->input->post("cdept"))->where('level', $level)->get()->result();
       
      echo "<select name=\"cscholarship\" id=\"cscholarship\">";
      if ($values)
      {
          echo "<option value=\"\"> -- Select -- </option>";
          foreach ($values as $val)
          {
             echo "<option value=\"$val->id\"> $val->name </option>";
          }
      }
      else{ echo "<option value=\"\"> -- No List -- </option>"; }
      echo "</select>";
   }
   
   function get_period()
   {
      $sc =  new Scholarship_trans_lib();
      $py = new Payment_status_lib();
      $sc = $sc->get_period($this->input->post('cscholar'));
      $financial = $this->financial->get();
      
      
      // ---------------------- end status ---------------------
      
//       $dates = $this->input->post('tdate');
//       $date=date_create($dates);
//       date_add($date,date_interval_create_from_date_string($sc." month"));
//       
//       $month = get_month(date_format($date,"n"));
//       $year = date_format($date,"Y");
       
       // -------------- payment status ---------------------
       
       $st = $py->get_month_status($this->input->post('sid'), $financial); // get bulan sekarang
       $miss = $py->get_all_miss_payment($this->input->post('sid'), $financial); // get jumlah tunggakan
       
       echo $sc.'|'.$st.'|'.$miss.'|'.$py->months_name($st).'-'.$py->year_name($st, $financial);
   }
   
   function get_end_month()
   {
      $py = new Payment_status_lib(); 
      $end = intval($this->input->post('tstart')-1+$this->input->post('end'));
      
      //echo $py->months_name($end).'-'.$py->year_name($end, $this->input->post('tfinancial'));
      echo $end.'|'.$py->months_name($end).'-'.$py->year_name($end, $this->input->post('tfinancial'));
   }
    
}

?>