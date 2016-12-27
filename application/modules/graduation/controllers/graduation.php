<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Graduation extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Students_model', 'student_model', TRUE);
        
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->user = new Admin_lib();
        $this->dept = new Dept_lib();
        $this->faculty = new Faculty_lib();
        $this->grade = new Grade_lib();
        $this->payment = new Payment_status_lib();
        $this->financial = new Financial_lib();
        $this->generation = new Generation_lib();
        $this->recaptrans = new Student_recap_trans_lib();
        $this->student = new Student_lib();
        $this->fee = new Regcost_lib;

        $this->model = new Graduations();
    }

    private $properti, $modul, $title, $model, $payment,$financial,$student;
    private $user,$category,$dept,$faculty,$grade,$generation,$recaptrans,$fee;

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
        $data['main_view'] = 'graduation_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all();
        $data['year'] = $this->generation->combo();
        
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
            
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('#','No', 'Generation', 'Dates', 'Student', 'Department', 'Faculty', 'Grade', 'Credit', 'Balance', 'Action');

            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $res->id,'checked'=> FALSE, 'style'=> 'margin:0px');       
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $res->year, tglin($res->dates), strtoupper($this->student->get_name($res->student_id)), $this->dept->get_name($res->dept_id), $this->faculty->get_name($this->student->get_faculty($res->student_id)), $this->grade->get_name($this->student->get_grade($res->student_id)), $res->credit, number_format($res->amount),
                    anchor($this->title.'/confirmation/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/update/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{ $data['message'] = "No $this->title data was found!";}
	$this->load->view('template', $data);
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'graduation_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo_all();
        $data['year'] = $this->generation->combo();

	// ---------------------------------------- //
        if ($this->input->post('ctype'))
        {
          $result = $this->model->where('dept_id', $this->input->post('cdept'))->where('approved', $this->input->post('ctype'))->where('year', $this->input->post('cyear'))->get();
        }
        else{ $result = $this->model->where('dept_id', $this->input->post('cdept'))->get(); }
        

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Generation', 'Dates', 'Student', 'Department', 'Faculty', 'Grade', 'Credit', 'Balance', 'Action');

        $i = 0;
        foreach ($result as $res)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $res->id,'checked'=> FALSE, 'style'=> 'margin:0px');       
            $this->table->add_row
            (
                form_checkbox($datax), ++$i, $res->year, tglin($res->dates), strtoupper($this->student->get_name($res->student_id)), $this->dept->get_name($res->dept_id), $this->faculty->get_name($this->student->get_faculty($res->student_id)), $this->grade->get_name($this->student->get_grade($res->student_id)), $res->credit, number_format($res->amount),
                anchor($this->title.'/confirmation/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/update/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();

	$this->load->view('template', $data);
    }
    
    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }
    
    function confirmation($pid)
    {
        $this->acl->otentikasi3($this->title);
        $value = $this->model->where('id',$pid)->get();
        
        if ($value->approved == 0)
        {
            if ($value->taking_dates != null && $this->valid_period($value->taking_dates) == TRUE)
            {
               $value->approved = 1;
               $value->save();
               $value->clear(); 
               $this->session->set_flashdata('message', "$this->title has been confirmed..!"); // set flash data message dengan session 
            }
            else{ $this->session->set_flashdata('message', "Certificate has not been taken..!"); } 
        }
        redirect($this->title);    
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['dept'] = $this->dept->combo();
        $data['financialyear'] = $this->financial->get();
        $data['generation'] = $this->generation->get_active();
        
        $this->load->view('graduation_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'graduation_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_select'] = site_url($this->title.'/transfer_process/'.$this->input->post('tdate').'/'.$this->generation->get_active().'/'.$this->financial->get());
	$data['link'] = array('link_back' => anchor($this->title.'/add','<span>back</span>', array('class' => 'back')));
        
        $this->form_validation->set_rules('tdate', 'Transfer Date', 'required|callback_valid_period');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $data['dept'] = $this->dept->combo();
            $data['financialyear'] = $this->financial->get();
            $data['generation'] = $this->generation->get_active();
            $data['dates'] = $this->input->post('tdate'); 
            
            $result = $this->student_model->search($this->input->post('cdept'), $this->input->post('cfaculty'), $this->input->post('cgrade'),null,2)->result();
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('#','No', 'NISN', 'Name', 'Dept', 'Faculty', 'Grade', 'Genre');
    //
            $i = 0;
            foreach ($result as $res)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $res->students_id,'checked'=> FALSE, 'style'=> 'margin:0px');   
                $this->table->add_row
                ( form_checkbox($datax), ++$i, $res->nisn, strtoupper($res->name), $res->dept, $res->faculty, $res->grade, strtoupper($res->genre) );
            }
    //
            $data['table'] = $this->table->generate();

             //          fasilitas check all
            $js = "onClick='cekall($i)'";
            $sj = "onClick='uncekall($i)'";
            $data['radio1'] = form_radio('newsletter', 'accept1', FALSE, $js).'Check';
            $data['radio2'] = form_radio('newsletter', 'accept2', FALSE, $sj).'Uncheck';

            $this->load->view('graduation_form', $data);
        }
        else
        {
            $this->session->set_flashdata('message', "Invalid Date..!");
            redirect($this->title.'/add/');
        }

    }
    
    function transfer_process($dates=null,$generation=null,$financial=null)
    {
      $cek = $this->input->post('cek');
      $st = new Student_lib();
      $st_model = new Student();
      $ps = new Period(); $ps = $ps->get();
      

      if($cek)
      {
        $jumlah = count($cek);
        for ($i=0; $i<$jumlah; $i++)
        {   
            $st->graduation($cek[$i],0,'graduation',$dates); // ubah status di student
            
            $st_model->where('students_id', $cek[$i])->get(); // tambah transaksi di recap trans
            $this->recaptrans->min_trans($cek[$i], $st_model->dept_id, $st_model->grade_id, $dates, 'out', 1, $ps->month, $ps->year, 'GR-'.tglin($dates), 'GRADUATION');
            
            // get payment status - credit month
            $credit = $this->payment->get_miss_payment($cek[$i], $financial);
            $fee = $this->fee->get_by_id($this->fee->get_by_student($cek[$i]));
            
            // tambah transaksi di graduation
            $this->model->dept_id = $st_model->dept_id;
            $this->model->financial_year = $financial;
            $this->model->year = $generation;
            $this->model->dates = $dates;
            $this->model->student_id = $cek[$i];
            $this->model->credit = $credit;
            $this->model->school = intval($credit*$fee->school);
            $this->model->computer = intval($credit*$fee->computer);
            $this->model->osis = intval($credit*$fee->osis);
            $this->model->practice = intval($credit*$fee->practice);
            $this->model->cost = intval($credit*$fee->cost);
            $this->model->aid = intval($credit*$fee->aid);
            $this->model->amount = intval($credit*$fee->p1);
            $this->model->save();
            $this->model->clear();
            
        }
        $this->session->set_flashdata('message', "$jumlah $this->title successfully transfered..!");
      }
      else
      { $this->session->set_flashdata('message', "No $this->title selected..!!"); }
      redirect($this->title.'/add');
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
            $this->delete($cek[$i]);
        }
        $this->session->set_flashdata('message', "$jumlah $this->title successfully removed..!");
      }
      else
      { $this->session->set_flashdata('message', "No $this->title selected..!!"); }
      redirect($this->title);
    }
    
    function delete($uid,$type='non')
    {
        $this->acl->otentikasi_admin($this->title);
        $value = $this->model->where('id', $uid)->get();
        $ps = new Period(); $ps = $ps->get();
        
        if ($value->approved == 0)
        {
          $this->student->graduation($value->student_id, 1, '',intval(date('Y', strtotime($value->dates))+20).'-12-31'); // ubah status di student
          $this->recaptrans->remove($value->student_id, $value->dept_id, $this->student->get_grade($value->student_id), $value->dates, 'out', $ps->month, $ps->year); // ubah status di recap trans
        
          // delete graduation
          $this->model->where('id', $uid)->get();
          $this->model->delete();
        
          $this->session->set_flashdata('message', "1 $this->title successfully removed..!");  
        }
        else
        { 
            if ($this->valid_period($value->taking_dates) == TRUE)
            {
               $value->approved = 0;
               $value->save();
               $this->session->set_flashdata('message', "1 $this->title has been rollback..!");  
            }
            else { $this->session->set_flashdata('message', "Invalid Period..!");   }
        }
        
        if ($type == 'non'){ redirect($this->title); }
    }
    
   function invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);
       $value = $this->model->where('id', $po)->get();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono']           = $po;
       $data['log']            = $this->session->userdata('log');
       $data['dept']           = $this->dept->get_name($value->dept_id);
       $data['faculty']        = $this->faculty->get_name($this->student->get_faculty($value->student_id));
       $data['grade']          = $this->grade->get_name($this->student->get_grade($value->student_id));
       $data['financial_year'] = $value->financial_year;
       $data['student']        = $this->student->get_name($value->student_id);
       $data['dates']          = $value->dates;
       $data['credit']         = $value->credit;
       $data['year']           = $value->year;
       $data['user']           = $this->user->get_username($this->session->userdata('username'));
       $data['parent']         = $value->student_parent;
       $data['certificate']    = $value->certificate_code;
       $data['taking']         = tgleng($value->taking_dates);
       $data['user']           = $this->user->get_username($value->user);
       
       $data['school']   = $value->school;
       $data['computer'] = $value->computer;
       $data['osis']     = $value->osis;
       $data['practice'] = $value->practice;
       $data['cost']     = $value->cost;
       $data['aid']      = $value->aid;

       $data['amount'] = $value->amount;
       $terbilang = $this->load->library('terbilang');
       $data['terbilang'] = ucwords($terbilang->baca($value->amount)).' Rupiah';
       
       if($value->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

//       if ($ap->approved != 1){ $this->load->view('rejected', $data); }
//       else { $this->load->view('apc_invoice', $data); }
       $this->load->view('graduation_receipt', $data);

   }
    
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $value = $this->model->where('id', $uid)->get();
        
        $data['default']['dept']           = $this->dept->get_name($value->dept_id);
        $data['default']['financial_year'] = $value->financial_year;
        $data['default']['student']        = $this->student->get_name($value->student_id);
        $data['default']['sid']            = $value->student_id;
        $data['default']['dates']          = $value->dates;
        $data['default']['year']           = $value->year;
        $data['default']['user']           = $this->user->get_username($this->session->userdata('username'));
        $data['default']['takingdates']    = $value->taking_dates;
        $data['default']['parent']         = $value->student_parent;
        $data['default']['certificate']    = $value->certificate_code;

	$this->session->set_userdata('curid', $value->id);
        $this->load->view('graduation_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'graduation_view';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('account/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tfinance', 'Financial Year', 'required|callback_valid_confirmation');
        $this->form_validation->set_rules('tcertificate', 'Certificate code', 'required');
        $this->form_validation->set_rules('ttaking', 'Taking Dates', 'required|callback_valid_period');
        $this->form_validation->set_rules('tparent', 'Student Parent', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->certificate_code   = $this->input->post('tcertificate');
            $this->model->taking_dates       = $this->input->post('ttaking');
            $this->model->student_parent     = $this->input->post('tparent');
            $this->model->user               = $this->user->get_userid($this->session->userdata('username'));
            $this->model->save();
//
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
            echo 'true';
        }
        else
        {
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->load->view('graduation_update', $data);
            echo validation_errors();
        }
    }
    
    public function valid_confirmation($value)
    {
        $val = $this->model->where('id', $this->session->userdata('curid'))->get();

        if ( $val->approved == 1 )
        {
            $this->form_validation->set_message('valid_confirmation', "Transaction Already Approved - [Cannot Changed].!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_payment($financial,$sid)
    {
        $val = $this->payment->get_miss_payment($sid, $financial);

        if ( intval($val) > 0 )
        {
            $this->form_validation->set_message('valid_payment', "Tuition Payment Status Not Completed.!");
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

    public function valid_type($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_type', "Invalid Type..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
     
    public function validating_type($name)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_type', "Invalid Type..!");
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

        $data['dept'] = $this->dept->combo_all();
        $data['year'] = $this->generation->combo_all();
        
        $this->load->view('graduation_report_panel', $data);
    }
    
    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        
        if ($this->input->post('cdept')){ $this->model->where('dept_id', $this->input->post('cdept')); }
        if ($this->input->post('cyear')){ $this->model->where('year', $this->input->post('cyear')); }
        $result = $this->model->get();

        $data['rundate'] = tglin(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['result'] = $result;
       
        if ($this->input->post('cformat') == 0){  $this->load->view('graduation_report', $data); }
        elseif ($this->input->post('cformat') == 1){  $this->load->view('graduation_pivot', $data); }
        
    }

}

?>