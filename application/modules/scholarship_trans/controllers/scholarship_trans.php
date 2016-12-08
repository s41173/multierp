<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scholarship_trans extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Scholarship_trans_model', 'sm', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->vendor = $this->load->library('vendor_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = new Journalgl_lib();
        $this->category = $this->load->library('categories_lib');
        $this->account = $this->load->library('account_lib');
        $this->student = new Student_lib();
        $this->dept    = new Dept_lib();
        $this->scholarshiplib = new Scholarship_trans_lib();
        $this->fee       = new Regcost_lib();
        $this->financial = new Financial_lib();
        $this->grade     = new Grade_lib();
        $this->foundation = new Foundation_lib();
        $this->payment = new Payment_status_lib();
        $this->over = new Tuition_over_lib();
        
        $this->model = new Scholarship_transs();
    }

    private $properti, $modul, $title, $account, $student, $dept, $scholarshiplib, $fee, $grade, $over;
    private $vendor,$user,$journal,$currency,$unit,$model,$category, $financial, $foundation, $payment;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
      $this->get_last();
//      $data = null;  
//      $this->load->view('grid', $data);  
    }
    
    function generate_json()
    {
        $this->db->select('id, name');
        $this->db->from('brand'); 
        $this->db->order_by('name', 'desc'); 
        $data = $this->db->get()->result(); 
        
        
//        $id = array("1", "2", "3");
//	$name = array("None", "Keihin", "Kitako");
//	$data = array();
//	$i=0;
//	while($i < count($name))
//	{    
//	  $row = array();
//	  $productindex = $i;
//	  $row["id"] = $id[$i];
//	  $row["name"] = $name[$productindex];
//	  $data[$i] = $row;
//	  $i++;
//	}
	 
//	header("Content-type: application/json"); 
	echo "{\"datax\":" .json_encode($data). "}"; 
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'scholarship_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['type'] = $this->scholarshiplib->combo_all();
        $data['dept'] = $this->dept->combo_all();
        $data['finance'] = $this->financial->combo_active();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $result = $this->model->order_by('dates','desc')->get($this->modul['limit'], $offset);
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
            $this->table->set_heading('No', 'Code', 'Financial', 'Scholarship', 'Date', 'Dept', 'Student', 'Request', 'Period', 'Until', 'Amount', 'Status', 'Action');

            $i = 0 + $offset;
            foreach ($result as $res)
            {   
                $this->table->add_row
                (
                    ++$i, 'SCT-'.$res->id, $res->financial_year, $this->scholarshiplib->get_name($res->scholarship_id), tglin($res->dates), $this->dept->get_name($this->student->get_dept($res->student)), $this->student->get_name($res->student), $res->request, $res->period, $this->get_until($res->until), number_format(intval($res->period*$this->scholarshiplib->get_fee_type($res->scholarship_id))), $this->status($res->status),
                    anchor($this->title.'/confirmation/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->approved), 'title' => 'edit / update')).' '.    
                    anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{ $data['message'] = "No $this->title data was found!"; }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function get_until($month)
    {
        $monthname = $this->payment->months_name($month);
        $year = $this->payment->year_name($month, $this->financial->get());
        return $monthname.'-'.$year;
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'scholarship_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['type'] = $this->scholarshiplib->combo_all();
        $data['dept'] = $this->dept->combo_all();
        $data['finance'] = $this->financial->combo_active();

        $result = $this->sm->search($this->input->post('cfinancial'), $this->input->post('cdept'), 
                                          $this->input->post('clevel'), $this->input->post('ctype'), 
                                          $this->input->post('cstts'), $this->input->post('cperiod'), 
                                          $this->input->post('cuntil'))->result() ;
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Financial', 'Scholarship', 'Date', 'Dept', 'Student', 'Request', 'Period', 'Until', 'Amount', 'Status', 'Action');

        $i = 0;
        foreach ($result as $res)
        {   
            $this->table->add_row
            (
                ++$i, 'SCT-'.$res->id, $res->financial_year, $this->scholarshiplib->get_name($res->scholarship_id), tglin($res->dates), $this->dept->get_name($this->student->get_dept($res->student)), $this->student->get_name($res->student), $res->request, $res->period, $this->get_until($res->until), number_format(intval($res->period*$this->scholarshiplib->get_fee_type($res->scholarship_id))), $this->status($res->status),
                anchor($this->title.'/confirmation/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->approved), 'title' => 'edit / update')).' '.    
                anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'IN'; break; case 1: $val = 'AC'; break; case 2; $val = 'DC'; break; } return $val; }
//    ===================== scholarshipproval ===========================================

    private function post_status($val)
    { if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class;}

    function confirmation($pid)
    {
        $this->acl->otentikasi3($this->title);
        $scholarship = $this->model->where('id',$pid)->get();
        if ($scholarship->approved == 1){ $this->rollback($pid); }

        if ($scholarship->scholarshipproved == 1){ $this->session->set_flashdata('message', "$this->title already approved..!");}
        elseif ($this->student->cek_active($scholarship->student) == FALSE){  $this->session->set_flashdata('message', "$this->title failure [Students Non Active]..!"); }
        elseif ($this->valid_period($scholarship->dates) == FALSE){ $this->session->set_flashdata('message', "Invalid Period..!"); }
        elseif ($this->over->cek_student_active($scholarship->student) == FALSE){ $this->session->set_flashdata('message', "Invalid Student [ Over Payment Status ]..!"); }
        else
        {
            $scholarship->approved = 1;
            $scholarship->status = 1;
            $scholarship->save();
            $scholarship->clear();

            $this->session->set_flashdata('message', "$this->title MT-00$pid confirmed..!"); // set flash data message dengan session 
            
        }
        redirect($this->title);    
    }
    
    function rollback($pid)
    {
        $this->acl->otentikasi3($this->title);
        $scholarship = $this->model->where('id',$pid)->get();
        
        $scholarship->approved = 0;
        $scholarship->approved = 0;
        $scholarship->save();
        $scholarship->clear();  
        $this->session->set_flashdata('message', "Rollback success [ Students ".  $this->student->get_nisn($scholarship->student)." Was Rollback ]..!");
        redirect($this->title);
    }
    
   
//    ===================== scholarshipproval ===========================================

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->model->where('id',$uid)->get();
        
        if ($val->approved == 1){ $this->rollback($uid);}
        else
        {
           $val->delete(); 
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
           redirect($this->title);
        }
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        $data['default']['financial'] = $this->financial->get();
        
        $this->load->view('scholarship_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'scholarship_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo();
        
	// Form validation
        $this->form_validation->set_rules('tvalue', 'Student', 'required');
        $this->form_validation->set_rules('tfinanceyear', 'Financial Year', 'required');
        $this->form_validation->set_rules('tsid', 'Student-ID', 'required|callback_valid_scholarship');
        $this->form_validation->set_rules('cscholarship', 'Scholarship Type', 'required');
        $this->form_validation->set_rules('tdate', 'Dates', 'required|callback_valid_period');
        $this->form_validation->set_rules('tdept', 'Department', 'required');
        $this->form_validation->set_rules('tgrade', 'Grade', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstart', 'Start Period', 'required|numeric');
        $this->form_validation->set_rules('tend', 'End Period', 'required|numeric');
        $this->form_validation->set_rules('tuntilmonth', 'Until Month', 'required');
        $this->form_validation->set_rules('tmonth', 'Scholarship Month', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $year = $this->financial->get();
            
            $this->model->scholarship_id = $this->input->post('cscholarship');
            $this->model->student        = $this->input->post('tsid');
            $this->model->dates          = $this->input->post('tdate');
            $this->model->financial_year = $year;
            $this->model->start          = $this->input->post('tstart');
            $this->model->request        = $this->input->post('tmonth');
            $this->model->period         = $this->input->post('tmonth');
            $this->model->until          = intval($this->input->post('tend'));
            $this->model->desc           = $this->input->post('tnote');
            $this->model->status         = 0;
            $this->model->approval       = 0;
            
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
        if ($scholarship->approved == 1){ redirect($this->title.'/void/'.$pid); }
        
        $data['type'] = $this->scholarshiplib->combo();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = ' Update '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);

        $data['default']['financial']    = $this->financial->get();
        $data['default']['studentname']  = $this->student->get_name($scholarship->student);
        $data['default']['sid']          = $scholarship->student;
        $data['default']['date']         = $scholarship->dates;
        $data['default']['scholar']      = $scholarship->scholarship_id;
        $data['default']['scholarmonth'] = $this->scholarshiplib->get_period($scholarship->scholarship_id);
        $data['default']['request']      = $scholarship->request;
        $data['default']['start']        = $scholarship->start;
        $data['default']['startmonth']   = $this->payment->months_name($scholarship->start).'-'.$this->payment->year_name($scholarship->start, $this->financial->get());
        $data['default']['dept']         = $this->dept->get_name($this->student->get_dept($scholarship->student));
        $data['default']['grade']        = $this->grade->get_name($this->student->get_grade($scholarship->student));
        $data['default']['note']         = $scholarship->desc;
        $data['default']['until']        = $scholarship->until;
        $data['default']['untilmonth']   = $this->payment->months_name($scholarship->until).'-'.$this->payment->year_name($scholarship->until, $this->financial->get());
        
        $this->load->view('scholarship_update', $data);
    }
 
    // Fungsi update untuk mengupdate db
    function update_process($pid=null)
    {
        $this->acl->otentikasi2($this->title);
//        $this->cek_confirmation($pid,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tvalue', 'Student', 'required');
        $this->form_validation->set_rules('tfinanceyear', 'Financial Year', 'required');
        $this->form_validation->set_rules('tsid', 'Student-ID', 'required|callback_valid_scholarship');
        $this->form_validation->set_rules('cscholarship', 'Scholarship Type', 'required');
        $this->form_validation->set_rules('tdate', 'Dates', 'required|callback_valid_period');
        $this->form_validation->set_rules('tdept', 'Department', 'required');
        $this->form_validation->set_rules('tgrade', 'Grade', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstart', 'Start Period', 'required|numeric');
        $this->form_validation->set_rules('tend', 'End Period', 'required|numeric');
        $this->form_validation->set_rules('tuntilmonth', 'Until Month', 'required');
        $this->form_validation->set_rules('tmonth', 'Scholarship Month', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        { 
            $value = $this->model->where('id', $pid)->get();
            
            $year = $this->financial->get();
            
            $value->scholarship_id = $this->input->post('cscholarship');
            $value->student        = $this->input->post('tsid');
            $value->dates          = $this->input->post('tdate');
            $value->financial_year = $year;
            $value->start          = $this->input->post('tstart');
            $value->request        = $this->input->post('tmonth');
            $value->period         = $this->input->post('tmonth');
            $value->until          = intval($this->input->post('tend'));
            $value->desc           = $this->input->post('tnote');
            
            $value->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$pid);
            echo 'true';
        }
        else
        {
//            $this->load->view('purchase_transform', $data);
            echo validation_errors();
        }
    }

    function void($pid=null)
    {
        $this->acl->otentikasi3($this->title);
        $scholarship = $this->model->where('id', $pid)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = ' Update '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/void_process/'.$pid);

        $data['default']['financial']    = $this->financial->get();
        $data['default']['studentname']  = $this->student->get_name($scholarship->student);
        $data['default']['sid']          = $scholarship->student;
        $data['default']['date']         = date('Y-m-d');
        $data['default']['scholar']      = $this->scholarshiplib->get_name($scholarship->scholarship_id);
        $data['default']['scholarmonth'] = $this->scholarshiplib->get_period($scholarship->scholarship_id);
        $data['default']['request']      = $scholarship->request;
        $data['default']['start']        = $scholarship->start;
        $data['default']['startmonth']   = $this->payment->months_name($scholarship->start).'-'.$this->payment->year_name($scholarship->start, $this->financial->get());
        $data['default']['dept']         = $this->dept->get_name($this->student->get_dept($scholarship->student));
        $data['default']['grade']        = $this->grade->get_name($this->student->get_grade($scholarship->student));
        $data['default']['note']         = $scholarship->void_desc;
        $data['default']['until']        = $scholarship->until;
        $data['default']['untilmonth']   = $this->payment->months_name($scholarship->until).'-'.$this->payment->year_name($scholarship->until, $this->financial->get());
        
        $this->load->view('scholarship_void', $data); 
    }
    
    function void_process($pid=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/void_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tvalue', 'Student', 'required');
        $this->form_validation->set_rules('tfinanceyear', 'Financial Year', 'required');
        $this->form_validation->set_rules('tsid', 'Student-ID', 'required');
        $this->form_validation->set_rules('tdate', 'Dates', 'required');
        $this->form_validation->set_rules('tdept', 'Department', 'required');
        $this->form_validation->set_rules('tgrade', 'Grade', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required|callback_valid_void['.$pid.']');
        $this->form_validation->set_rules('tstart', 'Start Period', 'required|numeric');
        $this->form_validation->set_rules('tend', 'End Period', 'required|numeric');
        $this->form_validation->set_rules('tuntilmonth', 'Until Month', 'required');
        $this->form_validation->set_rules('tmonth', 'Scholarship Month', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        { 
            $res = $this->model->where('id', $pid)->get();
            $res->void_date  = $this->input->post('tdate');
            $res->request    = $this->input->post('tmonth');
            $res->period     = $this->input->post('tmonth');
            $res->until      = intval($this->input->post('tend'));
            $res->void_desc  = $this->input->post('tnote');
//            
            $res->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$pid);
            echo 'true';
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
    
    public function valid_void($notes,$pid)
    {
        $res = $this->model->where('id', $pid)->get();
        if ($res->void_desc){ $this->form_validation->set_message('valid_void', "Void Already Created..!"); return FALSE; }
        else{ return TRUE; }
    }
    
    public function valid_scholarship($student)
    {
        $year = $this->financial->get();
        $this->model->where('student', $student);
        $this->model->where('financial_year', $year);
        $val = $this->model->where('status', 1)->count();
        
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_scholarship', "Invalid Scholarship - [Student Still Active].!");
            return FALSE;
        }
        else{ return TRUE; }
    }


// ===================================== PRINT ===========================================
    

   function invoice($pid=null)
   {
       $this->acl->otentikasi2($this->title);
       $scholarship = $this->model->where('id', $pid)->get();
       
       // property
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
      // property
        
       $data['pono']     = $pid;
       $data['letterno'] = $this->dept->get_name($this->student->get_dept($scholarship->student)).'/'.$scholarship->financial_year.'/'.get_month_romawi(date('n', strtotime($scholarship->dates))).'/000'.$pid;
       $data['dates']   = tglin($scholarship->dates);
       $data['tanggal'] = date('d', strtotime($scholarship->dates));
       $data['bulan'] = get_month_indo(date('n', strtotime($scholarship->dates)));
       $data['tahun'] = date('Y', strtotime($scholarship->dates));
       
       $data['studentname'] = strtoupper($this->student->get_name($scholarship->student));
       $data['nis']         = $this->student->get_nisn($scholarship->student);
       $data['dept']        = $this->dept->get_name($this->student->get_dept($scholarship->student));
       $data['grade']       = $this->grade->get_name($this->student->get_grade($scholarship->student));
       $data['saddress']    = $this->student->get_address($scholarship->student);
       $data['year']        = $scholarship->financial_year;
       $data['scholarship'] = $this->scholarshiplib->get_name($scholarship->scholarship_id);
       $data['teacher']     = $this->grade->get_instructor($this->student->get_grade($scholarship->student));
       $data['start']       = $this->payment->months_name($scholarship->start).'-'.$this->payment->year_name($scholarship->start,$scholarship->financial_year); 
       $data['end']         = $this->payment->months_name($scholarship->until).'-'.$this->payment->year_name($scholarship->until,$scholarship->financial_year);
       $data['amount']      = intval($scholarship->period*$this->scholarshiplib->get_fee_type($scholarship->scholarship_id));
       
       if ($scholarship->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;
       
       $data['chairman'] =  $this->foundation->get_name(1);
       $data['coordinator'] =  $this->foundation->get_name(7);
       
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

        $data['type'] = $this->scholarshiplib->combo_all();
        $data['dept'] = $this->dept->combo_all();
        $data['finance'] = $this->financial->combo_active();
        
        $this->load->view('scholarship_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $data['start'] = $this->input->post('tstart');
        $data['end'] = $this->input->post('tend');
        $data['rundate'] = tglin(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
       
        $data['result'] = $this->sm->search($this->input->post('cfinancial'), $this->input->post('cdept'), 
                                          $this->input->post('clevel'), $this->input->post('ctype'), 
                                          $this->input->post('cstts'), $this->input->post('cperiod'), 
                                          $this->input->post('cuntil'), $this->input->post('tstart'),
                                          $this->input->post('tend'))->result() ;
        
        $page = 'scholarship_report'; 
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }  
        elseif ($this->input->post('cformat') == 2)
        {
           $this->load->view('scholarship_pivot', $data); 
        }
    }

// ====================================== REPORT =========================================

    
// =======================================  AJAX =========================================
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
    
}

?>