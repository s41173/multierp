<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tuition_over_trans extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Tuition_over_trans_model', 'to', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency    = $this->load->library('currency_lib');
        $this->unit        = $this->load->library('unit_lib');
        $this->vendor      = $this->load->library('vendor_lib');
        $this->user        = $this->load->library('admin_lib');
        $this->journal     = new Journalgl_lib();
        $this->category    = $this->load->library('categories_lib');
        $this->account     = $this->load->library('account_lib');
        $this->student     = new Student_lib();
        $this->dept        = new Dept_lib();
        $this->fee         = new Regcost_lib();
        $this->financial   = new Financial_lib();
        $this->grade       = new Grade_lib();
        $this->foundation  = new Foundation_lib();
        $this->overlib     = new Tuition_over_lib();
        $this->scholarship = new Scholarship_trans_lib();
        
        $this->model       = new Tuition_over_transs();
    }

    private $properti, $modul, $title, $account, $student, $dept, $overlib, $fee, $grade, $scholarship;
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
        $data['main_view'] = 'over_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['type'] = $this->overlib->combo_all();
        $data['dept'] = $this->dept->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $overs = $this->model->order_by('dates','desc')->get($this->modul['limit'], $offset);
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
            $this->table->set_heading('No', 'Code', 'Date', 'Dept', 'Grade', 'Student', 'Type', 'Tuition Fee', 'Amount', 'Action');

            $i = 0 + $offset;
            foreach ($overs as $over)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $over->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'TO-0'.$over->id, tglin($over->dates), $this->dept->get_name($this->student->get_dept($over->student)), $this->grade->get_name($this->student->get_grade($over->student)), $this->student->get_name($over->student), $this->overlib->get_name($over->tuition_over_id), $this->fee->get_name($over->fee), number_format($this->fee->get_amount($over->fee)),
                    anchor($this->title.'/confirmation/'.$over->id,'<span>update</span>',array('class' => $this->post_status($over->status), 'title' => 'edit / update')).' '.
                    anchor($this->title.'/add_trans/'.$over->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$over->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{  $data['message'] = "No $this->title data was found!"; }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    private function get_search($date,$type)
    {
        if($date){ $this->model->where('dates', $date); }
        elseif($type){ $this->model->where('tuition_over_id', $type); }
        return $this->model->where('status',1)->get();
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'over_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo();
        $data['type'] = $this->overlib->combo_all();

        $overs = $this->get_search($this->input->post('tdate'), $this->input->post('ctype'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Dept', 'Grade', 'Student', 'Type', 'Tuition Fee', 'Action');

        $i = 0;
        foreach ($overs as $over)
        {
            $this->table->add_row
            (
                ++$i, 'TO-0'.$over->id, tglin($over->dates), $this->dept->get_name($this->student->get_dept($over->student)), $this->grade->get_name($this->student->get_grade($over->student)), $this->student->get_name($over->student), $this->overlib->get_name($over->tuition_over_id), $this->fee->get_name($over->fee), number_format($this->fee->get_amount($over->fee)),
                anchor($this->title.'/confirmation/'.$over->id,'<span>update</span>',array('class' => $this->post_status($over->status), 'title' => 'edit / update')).' '.
                anchor($this->title.'/add_trans/'.$over->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$over->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $this->acl->otentikasi2($this->title);
        $over = $this->model->where('id',$pid)->get();
        
        if ($this->valid_over($over->student) == FALSE){ $this->session->set_flashdata('message', "Transaction rollback..!"); }
        else 
        {
           if ($over->status == 0){ $stts = 1; }else{ $stts = 0; }
           $over->status = $stts;
           $over->save();
        }
        redirect($this->title);    
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->model->where('id',$uid)->get();        
        $this->model->delete(); 

        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");

        $data['type'] = $this->overlib->combo();
        $data['fee'] = $this->fee->combo();
        $this->load->view('over_form', $data);
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
        $data['type'] = $this->overlib->combo();
        
	// Form validation
        $this->form_validation->set_rules('ctype', 'Over Payment Type', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tsid', 'Student ID', 'required|callback_valid_over|callback_valid_student');
        $this->form_validation->set_rules('tdept', 'Department', 'required');
        $this->form_validation->set_rules('tgrade', 'Grade', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('cfee', 'Tuition Fee', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->tuition_over_id = $this->input->post('ctype');
            $this->model->dates           = $this->input->post('tdate');
            $this->model->student         = $this->input->post('tsid');
            $this->model->desc            = $this->input->post('tnote');
            $this->model->fee             = $this->input->post('cfee');
            $this->model->log             = $this->session->userdata('log');
            $this->model->status          = 0;
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/');
            echo 'true';
        }
        else
        {
//              $this->load->view('over_form', $data);
            echo validation_errors();
        }

    }

    function add_trans($pid=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $over = $this->model->where('id', $pid)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = ' Update '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);
        $data['type'] = $this->overlib->combo();
        $data['fee'] = $this->fee->combo();

        $data['default']['type']        = $over->tuition_over_id;
        $data['default']['date']        = $over->dates;
        $data['default']['studentname'] = $this->student->get_name($over->student);
        $data['default']['sid']         = $over->student;
        $data['default']['dept']        = $this->dept->get_name($this->student->get_dept($over->student));
        $data['default']['grade']       = $this->grade->get_name($this->student->get_grade($over->student));
        $data['default']['note']        = $over->desc;
        $data['default']['fee']         = $over->fee;
        
        $this->load->view('over_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process($pid=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('ctype', 'Over Payment Type', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tsid', 'Student ID', 'required');
        $this->form_validation->set_rules('tdept', 'Department', 'required');
        $this->form_validation->set_rules('tgrade', 'Grade', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('cfee', 'Tuition Fee', 'required');

        if ($this->form_validation->run($this) == TRUE)
        { 
            $this->model->where('id',$pid)->get();
            
            $this->model->tuition_over_id = $this->input->post('ctype');
            $this->model->dates           = $this->input->post('tdate');
            $this->model->desc            = $this->input->post('tnote');
            $this->model->fee             = $this->input->post('cfee');
            $this->model->log             = $this->session->userdata('log');
            
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
    
    public function valid_over($student)
    {
        $stts = $this->scholarship->valid_trans($student, $this->financial->get());

        if ($stts == FALSE)
        {
            $this->form_validation->set_message('valid_over', "Invalid Transaction..!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_student($student)
    {
        $val = $this->model->where('student', $student)->where('status', 1)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_student', "Invalid Student..!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));
     
        $data['type'] = $this->overlib->combo_all();
        $data['dept'] = $this->dept->combo_all();
        
        $this->load->view('over_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end   = $this->input->post('tend');
        $type  = $this->input->post('ctype');
        $dept  = $this->input->post('cdept');
        
        $this->model->where_between('dates', $start, $end);

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tglin(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['result'] = $this->to->report($start,$end,$dept,$type)->result();
       
        $page = 'over_report'; 
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
        
    }

// ====================================== REPORT =========================================
    
}

?>