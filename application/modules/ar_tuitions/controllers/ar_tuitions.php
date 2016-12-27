<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_tuitions extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ar_tuition_model','am',TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency   = $this->load->library('currency_lib');
        $this->user       = $this->load->library('admin_lib');
        $this->journal    = $this->load->library('journal_lib');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->dept       = $this->load->library('dept_lib');
        $this->grade      = $this->load->library('grade_lib');
        $this->payment = $this->load->library('payment_status_lib');
        
        $this->student = new Student_lib();
        $this->model = new Ar_tuition();
        $this->year = new Financial_lib();

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency;
    private $user,$journal,$dept,$student,$model,$grade,$year,$payment;

    function index()
    {
        $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'tuition_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['year'] = $this->year->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $saless = $this->model->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

        $atts = array('width'=> '800','height'=> '500',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 500)/2)+\'');

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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Department', 'Students', 'Balance', 'Academic Year', 'Action');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'TRJ-00'.$sales->id, $sales->currency, tglin($sales->dates), ucfirst($sales->notes), $this->dept->get_name($this->student->get_dept($sales->student_id)), $this->student->get_name($sales->student_id).'<br>'.$this->grade->get_name($this->student->get_grade($sales->student_id)), number_format($sales->amount), $sales->financial_year,
                    anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$sales->id,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        { $data['message'] = "No $this->title data was found!"; }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'tuition_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['year'] = $this->year->combo();

        $atts = array('width'=> '400','height'=> '220',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        if ($this->input->post('cyear')){ $saless = $this->model->where('financial_year', $this->input->post('cyear'))->get(); }
        else { $saless = $this->model->get(); }
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
       $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Department', 'Students', 'Balance', 'Academic Year', 'Action');

        $i = 0;
        foreach ($saless as $sales)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'TRJ-00'.$sales->id, $sales->currency, tglin($sales->dates), ucfirst($sales->notes), $this->dept->get_name($this->student->get_dept($sales->student_id)), $this->student->get_name($sales->student_id).'<br>'.$this->grade->get_name($this->student->get_grade($sales->student_id)), number_format($sales->amount), $sales->financial_year,
                anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$sales->id,'<span>print</span>',$atts).' '.
                anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($id)
    {
        $sales = $this->model->where('id', $id)->get();

        if ($sales->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); 
           redirect($this->title);
        }
        else
        {
            if ($sales->amount == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); 
              redirect($this->title);
            }
            else
            {
                // edit payment status lib
               $this->payment->remove($sales->student_id,$this->year->get(),'p'.$sales->month);
                
                //  create journal 
               $this->create_journal($id, $sales->currency, $sales->dates, $sales->notes, $sales->amount);
              
               // approval
               $this->model->approved = 1;
               $this->model->save();

               $this->session->set_flashdata('message', "$this->title TJ-00$sales->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
                
            }
        }

    }
    
//    ===================== approval ===========================================
    
    private function create_journal($no,$cur,$dates,$notes, $total=0)
    {
        $cm = new Control_model();
        
        $kas   = $cm->get_id(13);
        $retur = $cm->get_id(36);
        
//        journal
        $this->journalgl->new_journal($no,$dates,'TRJ',$cur,$notes,$total, $this->session->userdata('log'));
        $jid = $this->journalgl->get_journal_id('TRJ',$no);
//
        $this->journalgl->add_trans($jid,$retur, $total, 0);
        $this->journalgl->add_trans($jid,$kas, 0, $total);
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
        $data['year'] = $this->year->get();
        
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

	// Form validation
        $this->form_validation->set_rules('tid', 'Student', 'required');
        $this->form_validation->set_rules('tdate', 'Transaction Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tperiod', 'Payment Periode', 'required|numeric');
        $this->form_validation->set_rules('tschool', 'School Fee', 'required|numeric');
        $this->form_validation->set_rules('tosis', 'OSIS', 'required|numeric');
        $this->form_validation->set_rules('tcom', 'Computer Fee', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Practice Fee', 'required|numeric');
        $this->form_validation->set_rules('tcost', 'Cost Fee', 'required|numeric');
        $this->form_validation->set_rules('ttotal', 'Total', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {   
            $this->model->student_id = $this->input->post('tid');
            $this->model->dates      = $this->input->post('tdate');
            $this->model->notes      = $this->input->post('tnotes');
            $this->model->currency   = $this->input->post('ccur');
            $this->model->school_fee = $this->input->post('tschool');
            $this->model->practical  = $this->input->post('tpractice');
            $this->model->computer   = $this->input->post('tcom');
            $this->model->osis       = $this->input->post('tosis');
            $this->model->cost       = $this->input->post('tcost');
            $this->model->acc        = $this->input->post('cacc');
            $this->model->amount     = $this->input->post('ttotal');
            $this->model->month      = $this->input->post('tperiod');
            $this->model->financial_year = $this->year->get();
            $this->model->log        = $this->session->userdata('log');
            
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
    
    function delete($uid,$po=0)
    {
        $this->acl->otentikasi_admin($this->title);
        $sales = $this->model->where('id', $uid)->get();

        if ( $this->valid_period($sales->dates) == TRUE )
        {
            $this->journalgl->remove_journal('TRJ', $uid); // journal gl
            $this->model->delete();

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "1 $this->title can't removed, invalid period..!"); }

        redirect($this->title);
    }

// ===================================== PRINT ===========================================
    
   function invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);
       $ap = $this->model->where('id', $po)->get();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $po;
       $data['podate'] = tgleng($ap->dates);
       $data['notes'] = $ap->notes;
       $data['acc'] = $ap->acc; 
       $data['currency'] = $ap->currency;
       $data['log'] = $this->session->userdata('log');

       $terbilang = $this->load->library('terbilang');
       if ($ap->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)); }
       
       if($ap->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

       $data['students']     = $this->student->get_name($ap->student_id);
       $data['dept']         = $this->dept->get_name($this->student->get_dept($ap->student_id));
       $data['amount']       = $ap->amount;
       $data['finance_year'] = $ap->financial_year;
       $data['month']        = $this->months_periode_name($ap->month).' - '.date('Y');
       $data['user']         = $this->session->userdata('username');
       
       $data['pname'] = $this->properti['name'];

       $this->load->view('ar_tuition_invoice', $data);

   }

    private function months_periode_name($month)
    {
        $res=0;
        switch ($month) 
        {
            case 7:$res='January'; break;
            case 8:$res='February'; break;
            case 9:$res='March'; break;
            case 10:$res='April'; break;
            case 11:$res='May'; break;
            case 12:$res='June'; break;
            case 1:$res='July'; break;
            case 2:$res='August'; break;
            case 3:$res='September'; break;
            case 4:$res='October'; break;
            case 5:$res='November'; break;
            case 6:$res='December'; break;
        }
        return $res;
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

        $data['currency'] = $this->currency->combo();
        $data['dept']     = $this->dept->combo_all();
        $data['year']     = $this->year->combo();
        
        $this->load->view('tuition_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $dept = $this->input->post('cdept');
        $year = $this->input->post('cyear');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $data['dept'] = $this->dept->get_name($dept);
        $data['year'] = $year;

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['tuitions'] = $this->am->report($cur,$dept,$start,$end,$year)->result();
        $data['totals'] = $this->am->total($cur,$dept,$start,$end,$year);
        
        $this->load->view('tuition_report', $data);
        
    }


// ====================================== REPORT =========================================

// ======================================= COST ==========================================
    
    
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


}

?>