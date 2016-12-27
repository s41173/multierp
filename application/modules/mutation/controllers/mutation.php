<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mutation extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Mutation_model', 'mt', TRUE);

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
        $this->mutationlib = new Mutation_lib();
        $this->fee       = new Regcost_lib();
        $this->financial = new Financial_lib();
        $this->grade     = new Grade_lib();
        $this->foundation = new Foundation_lib();
        
        $this->model = new Mutations();
    }

    private $properti, $modul, $title, $account, $student, $dept, $mutationlib, $fee, $grade;
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
        $data['main_view'] = 'mutation_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['type'] = $this->mutationlib->combo_all();
        $data['dept'] = $this->dept->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $mutations = $this->model->order_by('dates','desc')->get($this->modul['limit'], $offset);
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Dept', 'Grade', 'Student', 'Type', 'Period (Months)', 'Amount', 'Stts', 'Action');

            $i = 0 + $offset;
            foreach ($mutations as $mutation)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $mutation->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'MT-'.$mutation->id, $mutation->currency, tglin($mutation->dates), $this->dept->get_name($mutation->dept_id), $this->grade->get_name($mutation->grade_id), $this->student->get_name($mutation->student), $this->mutationlib->get_name($mutation->type), $mutation->receivable, number_format($mutation->amount), $this->status($mutation->settled),
                    anchor($this->title.'/confirmation/'.$mutation->id,'<span>update</span>',array('class' => $this->post_status($mutation->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$mutation->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$mutation->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$mutation->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    private function get_search($no,$date,$type,$stts)
    {
        if ($no){ $this->model->where('id', $no); }
        elseif($date){ $this->model->where('dates', $date); }
        elseif($type){ $this->model->where('type', $type); }
        elseif($stts){ $this->model->where('settled', $stts); }
        return $this->model->get();
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'mutation_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['dept'] = $this->dept->combo();
        $data['type'] = $this->mutationlib->combo_all();

        $mutations = $this->get_search($this->input->post('tno'), $this->input->post('tdate'), $this->input->post('ctype'), $this->input->post('cstts'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Dept', 'Grade', 'Student', 'Type', 'Period (Months)', 'Amount', 'Stts', 'Action');

        $i = 0;
        foreach ($mutations as $mutation)
        {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $mutation->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'MT-'.$mutation->id, $mutation->currency, tglin($mutation->dates), $this->dept->get_name($mutation->dept_id), $this->grade->get_name($mutation->grade_id), $this->student->get_name($mutation->student), $this->mutationlib->get_name($mutation->type), $mutation->receivable, number_format($mutation->amount), $this->status($mutation->settled),
                anchor($this->title.'/confirmation/'.$mutation->id,'<span>update</span>',array('class' => $this->post_status($mutation->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$mutation->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$mutation->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$mutation->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'C'; break; case 1: $val = 'S'; break; } return $val; }
//    ===================== mutationproval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $this->acl->otentikasi3($this->title);
        $mutation = $this->model->where('id',$pid)->get();
        $recaptrans = new Student_recap_trans_lib();
        $ps = new Period(); $ps = $ps->get();

        if ($mutation->mutationproved == 1){ $this->session->set_flashdata('message', "$this->title already approved..!");}
        if ($this->student->cek_active($mutation->student) == FALSE){  $this->session->set_flashdata('message', "$this->title failure [Students Non Active]..!"); }
        if ($this->valid_period($mutation->dates) == FALSE){ $this->session->set_flashdata('message', "Invalid Period..!"); }
        else
        {
            $recaptrans->min_trans($mutation->student, $mutation->dept_id, $mutation->grade_id, $mutation->dates, 'out', 1, $ps->month, $ps->year, 'MT-00'.$pid, $this->mutationlib->get_name($mutation->type));
            $this->student->graduation($mutation->student,0,'mutation',$mutation->dates);
            $mutation->approved = 1;
            $mutation->save();
            $mutation->clear();

            $this->create_journal($pid);
            $this->session->set_flashdata('message', "$this->title MT-00$pid confirmed..!"); // set flash data message dengan session 
            
        }
        redirect($this->title);    
    }
    
    private function create_journal($pid)
    {
        $mutation1 = $this->model->where('id',$pid)->get();
          
        //  create journal
        $this->journal->new_journal($mutation1->id, $mutation1->dates,'MT', $mutation1->currency, 'MT-'.$mutation1->id.'-'.$mutation1->notes, $mutation1->amount, $this->session->userdata('log'));

        //  create journal gl 
        $cm = new Control_model();

        $bank     = $cm->get_id(12);
        $kas      = $cm->get_id(13);
        $kaskecil = $cm->get_id(14);
        $account  = 0;                
                
        // create journal- GL
        $this->journal->new_journal($mutation1->id,$mutation1->dates,'MT',$mutation1->currency,$mutation1->notes,$mutation1->amount, $this->session->userdata('log'));
        switch ($mutation1->acc) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }

        $dpid = $this->journal->get_journal_id('MT',$mutation1->id);
        
        if ($mutation1->settled == 1)
        {
            if ($this->mutationlib->cek_acc($mutation1->dept_id, 'ar') == TRUE)
            {
               $mutation_acc = $this->mutationlib->get_acc($mutation1->dept_id,'ar'); // jenis akun mutasi
               $this->journal->add_trans($dpid,$account,$mutation1->amount,0); // kas, bank, kas kecil 
               $this->journal->add_trans($dpid,$mutation_acc,0,$mutation1->amount); // piutang
            } 
        }
        else
        {
            if ($this->mutationlib->cek_acc($mutation1->dept_id, 'cost') == TRUE && $this->mutationlib->cek_acc($mutation1->dept_id, 'ar') == TRUE)
            {
               $mutation_cost = $this->mutationlib->get_acc($mutation1->dept_id,'cost'); // jenis akun mutasi cost
               $mutation_ar = $this->mutationlib->get_acc($mutation1->dept_id,'ar'); // jenis akun mutas ar
               
               $this->journal->add_trans($dpid,$mutation_cost,$mutation1->amount,0); // beban / biaya 
               $this->journal->add_trans($dpid,$mutation_ar,0,$mutation1->amount); // piutang 
            }
        }
    }
    

    function rollback($pid)
    {
        $this->acl->otentikasi3($this->title);
        $mutation = $this->model->where('id',$pid)->get();
        $recaptrans = new Student_recap_trans_lib();
        $ps = new Period(); $ps = $ps->get();
        
        if ($this->student->cek_active($mutation->student) == FALSE)
        {
            $recaptrans->remove($mutation->student, $mutation->dept_id, $mutation->grade_id, $mutation->dates, 'out', $ps->month, $ps->year);
            
            $this->student->graduation($mutation->student,1,'',intval(date('Y', strtotime($mutation->dates))+20).'-12-31');
            $mutation->approved = 0;
            $mutation->save();
            $mutation->clear();  
            $this->session->set_flashdata('message', "Rollback success [ Students ".  $this->student->get_nisn($mutation->student)." Was Rollback ]..!");
        }
        else { $this->session->set_flashdata('message', "Rollback failure [ Students ".  $this->student->get_nisn($mutation->student)." Not Found In Inactive Module ]..!"); }
        redirect($this->title);
    }
    
    private function cek_journal($date,$currency)
    {
        if ($this->journal->valid_journal($date,$currency) == FALSE)
        {
           $this->session->set_flashdata('message', "Journal for [".tgleng($date)."] - ".$currency." mutationproved..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $mutation = $this->model->where('id', $po)->get();

        if ( $mutation->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - MT-00$mutation->id approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }

//    ===================== mutationproval ===========================================


    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->model->where('id',$uid)->get();
        
        if ($val->approved == 1)
        {
            $this->rollback($uid);
        }

        if ($this->valid_period($val->dates) == TRUE ) // cek journal harian sudah di mutationprove atau belum
        {
            $this->model->delete(); 
            $this->journal->remove_journal('MT',$uid);

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            redirect($this->title);
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, journal mutationproved..!");
           redirect($this->title);
        } 
    }
    
    private function counter()
    {
        $res = 0;
        if ( $this->model->count() > 0 )
        {
           $this->model->select_max('no')->get();
           $res = $this->model->no + 1;
        }
        else{ $res = 1; }
        return $res;
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        $data['dept'] = $this->dept->combo();
        $data['type'] = $this->mutationlib->combo();
        $data['year'] = $this->financial->combo_active();
        
        $this->load->view('mutation_form', $data);
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
        $data['type'] = $this->mutationlib->combo();
        $data['year'] = $this->financial->combo_active();
        
	// Form validation
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('cyear', 'Financial Year', 'required');
        $this->form_validation->set_rules('ctype', 'Mutation Type', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tsince', 'Since Date', 'required|callback_valid_since['.$this->input->post('tdate').']');
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
            $this->model->currency       = $this->input->post('ccurrency');
            $this->model->financial_year = $this->input->post('cyear');
            $this->model->type           = $this->input->post('ctype');
            $this->model->dates          = $this->input->post('tdate');
            $this->model->since          = $this->input->post('tsince');
            $this->model->student        = $this->input->post('tsid');
            $this->model->dept_id        = $this->dept->get_id($this->input->post('tdept'));
            $this->model->grade_id       = $this->grade->get_id($this->input->post('tgrade'));
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
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/');
            echo 'true';
        }
        else
        {
//              $this->load->view('mutation_form', $data);
            echo validation_errors();
        }

    }

    function add_trans($pid=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $mutation = $this->model->where('id', $pid)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = ' Update '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);
        $data['currency'] = $this->currency->combo();
        $data['dept'] = $this->dept->combo();
        $data['type'] = $this->mutationlib->combo();
        $data['year'] = $this->financial->combo_active();
        $data['fee'] = $this->fee->combo_criteria($mutation->dept_id, $this->grade->get_level($mutation->grade_id));

        $data['default']['currency'] = $mutation->currency;
        $data['default']['year']     = $mutation->financial_year;
        $data['default']['type']     = $mutation->type;
        
        $data['default']['date']        = $mutation->dates;
        $data['default']['since']       = $mutation->since;
        $data['default']['studentname'] = $this->student->get_name($mutation->student);
        $data['default']['sid']         = $mutation->student;
        $data['default']['dept']        = $this->dept->get_name($mutation->dept_id);
        $data['default']['grade']       = $this->grade->get_name($mutation->grade_id);
        $data['default']['teacher'] = $mutation->teacher;
        $data['default']['note']    = $mutation->notes;
        $data['default']['amount']  = $mutation->amount;
        $data['default']['period']  = $mutation->receivable;
        $data['default']['fee']  = $mutation->fee_type;
        $data['default']['acc']  = $mutation->acc;
        $data['default']['stts']  = $mutation->settled;
        
        $this->load->view('mutation_update', $data);
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
            $pitem = array('mutation_id' => $pid, 'cost' => $this->input->post('ccost'),
                           'notes' => $this->input->post('tnotes'),
                           'staff' => $this->input->post('tstaff'),
                           'amount' => $this->input->post('tamount'));
            
            $this->transmodel->add($pitem);
            $this->update_trans($pid);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_item($id,$pid)
    {
        $this->cek_confirmation($pid,'add_trans');
        $this->acl->otentikasi2($this->title);
        $no = $this->model->where('id', $pid)->get();
        
        $this->transmodel->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($pid);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$no->no);
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
        $this->form_validation->set_rules('ctype', 'Mutation Type', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tsince', 'Since Date', 'required|callback_valid_since['.$this->input->post('tdate').']');
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
            $this->model->since          = $this->input->post('tsince');
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
    
    public function valid_since($since,$date)
    {
        if ( $since > $date )
        {
            $this->form_validation->set_message('valid_since', "Invalid Since Period.!");
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
    
    public function valid_vendor($name)
    {
        if ($this->vendor->valid_vendor($name) == FALSE)
        {
            $this->form_validation->set_message('valid_vendor', "Invalid Vendor.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function valid_confirmation($no)
    {
        $mutation = $this->model->where('no', $no)->get();

        if ($mutation->mutationproved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Can't change value - Order mutationproved..!.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_rate($rate)
    {
        if ($rate == 0)
        {
            $this->form_validation->set_message('valid_rate', "Rate can't 0..!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================
    

   function invoice($pid=null)
   {
       $this->acl->otentikasi2($this->title);
       $mutation = $this->model->where('id', $pid)->get();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono']     = $pid;
       $data['podate']   = tglin($mutation->dates);
       $data['notes']    = $mutation->notes;
       $data['user']     = $mutation->user;
       $data['currency'] = $mutation->currency;
       $data['log']      = $this->session->userdata('log');
       $data['teacher']  = $mutation->teacher;
       $data['type']     = $this->mutationlib->get_name($mutation->type);
       
       $data['year']        = $mutation->financial_year;
       $data['studentname'] = $this->student->get_name($mutation->student);
       $data['sid']         = $this->student->get_nisn($mutation->student);
       $data['dept']        = $this->dept->get_name($mutation->dept_id);
       $data['grade']       = $this->grade->get_name($mutation->grade_id);
       
       $data['period']      = $mutation->receivable;
       $data['fee']         = $this->fee->get_name($mutation->fee_type);
       $data['amount']      = $mutation->amount;
       $data['manager']     = $this->foundation->get_name(8);
       
       if($mutation->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

       $this->load->view('mutation_invoice', $data);
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
        $data['type'] = $this->mutationlib->combo_all();
        $data['dept'] = $this->dept->combo_all();
        
        $this->load->view('mutation_report_panel', $data);
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
       
        $page = 'mutation_report'; 
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
       $req = intval($this->input->post('request'));
       $ps = new Payment_status_lib();
       echo $ps->get_miss_payment_period($sid, $year, $req);
   }
   
   function calculate_mutation()
   {
       $regcost = new Regcost_lib();
       $period = $this->input->post('period');
       $fee = $regcost->get_amount($this->input->post('fee'));
       echo intval($period*$fee);
   }
    
}

?>