<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payroll extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Payroll_model','pm',TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency   = $this->load->library('currency_lib');
        $this->user       = $this->load->library('admin_lib');
        $this->journal    = $this->load->library('journal_lib');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->dept       = $this->load->library('dept_lib');
        $this->trans      = new Payroll_trans_lib();
        $this->foundation = new Foundation_lib();
        $this->division   = new Division_lib();
        
        $this->model = new Payrolls();

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency, $foundation, $division;
    private $user,$dept,$model,$journal,$journalgl,$trans;

    function index()
    {
        $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);
        $this->session->unset_userdata('payid');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'payroll_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last');
        $data['link'] = array('link_back' => anchor('payroll_reference/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
        $ps = new Period();
        $ps->get();

	// ---------------------------------------- //
        $result = $this->pm->get($this->modul['limit'], $offset)->result();
        $num_rows = $this->pm->count();

        $atts = array('width'=> '500','height'=> '300',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 500)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 300)/2)+\'');

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
            $this->table->set_heading('No', 'Code', 'Cur', 'Acc', 'Period', 'Date', 'Notes', 'Honor', 'Salary', 'Loan', 'Deduction', 'Balance', 'Action');

            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $res->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'PYJ-00'.$res->id, $res->currency, ucfirst($res->acc), $res->month.'-'.$res->year, tglin($res->dates), ucfirst($res->notes), number_format($res->total_honor), number_format($res->total_salary), number_format($res->total_loan), number_format($res->total_other), number_format($res->balance),
                    anchor('payroll_trans/get/'.$res->id,'<span>update</span>',array('class' => 'cost1', 'title' => 'Transaction')).'&nbsp;'.
                    anchor($this->title.'/update/'.$res->id,'<span>update</span>',array('class' => 'update', 'title' => 'Edit Transaction')).'&nbsp;'.
                    anchor($this->title.'/confirmation/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->approved), 'title' => 'edit / update')).''.
                    anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // ===== chart  =======
        $data['graph'] = $this->chart($this->input->post('ccurrency'));
        

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }


    private function chart($cur='IDR')
    {
        $ps = new Period();
        $ps->get();
        $year = $ps->year;

        $arpData[0][1] = 'January';
        $arpData[0][2] = $this->pm->total_chart('01',$year,$cur);

        $arpData[1][1] = 'February';
        $arpData[1][2] = $this->pm->total_chart('02',$year,$cur);

        $arpData[2][1] = 'March';
        $arpData[2][2] = $this->pm->total_chart('03',$year,$cur);

        $arpData[3][1] = 'April';
        $arpData[3][2] = $this->pm->total_chart('04',$year,$cur);

        $arpData[4][1] = 'May';
        $arpData[4][2] = $this->pm->total_chart('05',$year,$cur);

        $arpData[5][1] = 'June';
        $arpData[5][2] = $this->pm->total_chart('06',$year,$cur);

        $arpData[6][1] = 'July';
        $arpData[6][2] = $this->pm->total_chart('07',$year,$cur);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->pm->total_chart('08',$year,$cur);

        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->pm->total_chart('09',$year,$cur);

        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->pm->total_chart('10',$year,$cur);

        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->pm->total_chart('11',$year,$cur);

        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->pm->total_chart('12',$year,$cur);

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Payroll", "98%", 400, false, false) ;
        return $graph;
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'payroll_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $atts = array('width'=> '400','height'=> '220',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        $result = $this->pm->search($this->input->post('cmonth'), $this->input->post('tyear'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Acc', 'Period', 'Date', 'Notes', 'Honor', 'Salary', 'Loan', 'Deduction', 'Balance', 'Action');

        $i = 0;
        foreach ($result as $res)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $res->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'PYJ-00'.$res->id, $res->currency, ucfirst($res->acc), $res->month.'-'.$res->year, tglin($res->dates), ucfirst($res->notes), number_format($res->total_honor), number_format($res->total_salary), number_format($res->total_loan), number_format($res->total_other), number_format($res->balance),
                anchor('payroll_trans/get/'.$res->id,'<span>update</span>',array('class' => 'cost1', 'title' => 'Transaction')).'&nbsp;'.
                anchor($this->title.'/confirmation/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->approved), 'title' => 'edit / update')).''.
                anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$atts).' '.
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        
        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    { if ($val == 0) {$class = "notapprove"; }elseif ($val == 1){$class = "approve"; } return $class; }

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
            $total = $sales->balance;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); 
              redirect($this->title);
            }
            else
            {
               //  create journal
               $this->create_journal($id);
               
               $this->model->approved = 1;
               $this->model->save();

               $this->session->set_flashdata('message', "$this->title CD-000$sales->id confirmed..!"); // set flash data message dengan session
               redirect($this->title);
                
            }
        }

    }
    
    // journal
    private function create_journal($id=0)
    {
        $res = $this->model->where('id', $id)->get();
        $cm = new Control_model();
        
        $bank         = $cm->get_id(22);
        $kas          = $cm->get_id(13);
        $kaskecil     = $cm->get_id(14);
        $payroll      = $cm->get_id(37);
        $overtime     = $cm->get_id(39);
        $bonus        = $cm->get_id(44);
        $consumption  = $cm->get_id(45);
        $transport    = $cm->get_id(46);
        $late         = $cm->get_id(40);
        $loan         = $cm->get_id(38);
        $tax          = $cm->get_id(42);
        $insurance    = $cm->get_id(43);
        $other_charge = $cm->get_id(41);
        $account  = 0;
        
        $this->journalgl->new_journal('00000'.$res->id, $res->dates,'CD', $res->currency, 'Payroll Payment : '.tglmonth($res->dates).' - '.  ucfirst($res->acc), $res->balance, $this->session->userdata('log'));
        $dpid = $this->journalgl->get_journal_id('CD','00000'.$res->id);

        switch ($res->acc) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }              
        
        //debit
        $this->journalgl->add_trans($dpid,$payroll, intval($res->total_honor+$res->total_salary), 0); // payroll (debit)
        if ($res->total_consumption > 0){ $this->journalgl->add_trans($dpid,$consumption,$res->total_consumption, 0); } // konsumsi (debit) 
        if ($res->total_transportation > 0){ $this->journalgl->add_trans($dpid,$transport,$res->total_transportation, 0); } // transport (debit) 
        if ($res->total_overtime > 0){ $this->journalgl->add_trans($dpid,$overtime,$res->total_overtime, 0); } // overtime (debit)
        if ($res->total_bonus > 0){ $this->journalgl->add_trans($dpid,$bonus,$res->total_bonus, 0); } // bonus (debit) 
        
        // kredit
        if ($res->total_loan > 0){ $this->journalgl->add_trans($dpid,$loan, 0, $res->total_loan); } // loan
        if ($res->total_late > 0){ $this->journalgl->add_trans($dpid,$late, 0, $res->total_late); } // late
        if ($res->total_tax > 0){ $this->journalgl->add_trans($dpid,$tax, 0, $res->total_tax); } // tax
        if ($res->total_insurance > 0){ $this->journalgl->add_trans($dpid,$insurance, 0, $res->total_insurance); } // insurance
        if ($res->total_other > 0){ $this->journalgl->add_trans($dpid,$other_charge, 0, $res->total_other); } // other charge
        if ($res->balance > 0) {$this->journalgl->add_trans($dpid,$account,0,$res->balance); } // kas, bank, kas kecil ( kredit )
        
    }
    
//    ===================== approval ===========================================    

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('payroll_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'tuition_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
 
	// Form validation
        $this->form_validation->set_rules('ccur', 'Currency', 'required');
        $this->form_validation->set_rules('tdate', 'Transaction Date', 'required|callback_valid_period|callback_valid_payroll');
        $this->form_validation->set_rules('cacc', 'Account Type', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('cmonth', 'Month', 'required|callback_valid_payroll['.$this->input->post('tyear').']');
        $this->form_validation->set_rules('tyear', 'Year', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {   
            $this->model->currency = $this->input->post('ccur');
            $this->model->month    = $this->input->post('cmonth');
            $this->model->year     = $this->input->post('tyear');
            $this->model->dates    = $this->input->post('tdate');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->acc      = $this->input->post('cacc');
            $this->model->log      = $this->session->userdata('log');  
            
            $this->model->save();    
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            echo 'true';
        }
        else
        {
//              $this->load->view('tuition_form', $data);
            echo validation_errors();
        }

    }
    
    function update($uid)
    {
       $this->acl->otentikasi2($this->title);

       $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
       $data['h2title'] = 'Create New '.$this->modul['title'];
       $data['form_action'] = site_url($this->title.'/update_process/'.$uid);
       $data['currency'] = $this->currency->combo();
       $data['user'] = $this->session->userdata("username");
       
       $value = $this->model->where('id', $uid)->get();
       
       $data['default']['dates'] = $value->dates;
       $data['default']['notes'] = $value->notes;
       $data['default']['acc']   = $value->acc;
        
       $this->load->view('payroll_update', $data); 
    }
    
    function update_process($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'tuition_form';
	$data['form_action'] = site_url($this->title.'/update_process/'.$uid);
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
 
	// Form validation
        $this->form_validation->set_rules('tdate', 'Transaction Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('cacc', 'Account Type', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required|callback_valid_confirmation['.$uid.']');
        
        if ($this->form_validation->run($this) == TRUE)
        {   
            $value = $this->model->where('id', $uid)->get();
            
            $value->dates    = $this->input->post('tdate');
            $value->notes    = $this->input->post('tnote');
            $value->acc      = $this->input->post('cacc');
            
            $value->save();    
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            echo 'true';
        }
        else
        {
//              $this->load->view('tuition_form', $data);
            echo validation_errors();
        }

    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $sales = $this->model->where('id', $uid)->get();

        if ( $this->valid_period($sales->dates) == TRUE )
        {
            $this->journalgl->remove_journal('CD', '00000'.$uid); // journal gl
            
            $tt = new Payroll_trans_lib(); // hapus payroll transaction
            $tt->delete($uid);
            
            $this->model->where('id', $uid)->get(); // hapus payroll
            $this->model->delete();

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "1 $this->title can't removed, invalid period..!"); }

        redirect($this->title);
    }
    
// ===================================== PRINT ===========================================
   
   function honor_invoice($id=0)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];
        
        //properti
        $data['company'] = ucfirst($this->properti['name']);
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        // JOURNAL
        $val = $this->model->where('id', $id)->get();
        $data['pono']           = 'PYJ-00'.$id;
        $data['date']           = tglin($val->dates);
        $data['cur']            = $val->currency;
        $data['period']         = get_month($val->month).' - '.$val->year;
        $data['user']           = $this->session->userdata('username');
        $data['log']            = $this->model->log;
        $data['director']       = $this->foundation->get_name(1);
        $data['coordinator']    = $this->foundation->get_name(7);
        
        // SMP - TRANS
        $smptrans = $this->trans->get_amount($id, 6, 'honor')->row_array();

        $data['smp_consumption'] = intval($smptrans['consumption']);
        $data['smp_transportation'] = intval($smptrans['transportation']);
        $data['smp_overtime'] = intval($smptrans['overtime']);
        $data['smp_bonus'] = intval($smptrans['bonus']);
        $data['smp_principal'] = intval($smptrans['principal']);
        $data['smp_principal_helper'] = intval($smptrans['principal_helper']);
        $data['smp_head_department'] = intval($smptrans['head_department']);
        $data['smp_home_room'] = intval($smptrans['home_room']);
        $data['smp_picket'] = intval($smptrans['picket']);
        $data['smp_late'] = intval($smptrans['late']);
        $data['smp_loan'] = intval($smptrans['loan']);
        $data['smp_tax'] = intval($smptrans['tax']);
        $data['smp_insurance'] = intval($smptrans['insurance']);
        $data['smp_other_discount'] = intval($smptrans['other_discount']);
        $data['smp_amount'] = intval($smptrans['amount']);
        
        $data['smp_salary'] = intval($smptrans['basic_salary']+$smptrans['experience']);
        $data['smp_tunjangan'] = intval($smptrans['principal_helper']+$smptrans['head_department']+$smptrans['home_room']);
        $data['smp_total'] = $data['smp_salary'] + $data['smp_tunjangan'] + $smptrans['picket'] + $smptrans['principal'];
        
        // SMA - TRANS
        $smatrans = $this->trans->get_amount($id, 3, 'honor')->row_array();

        $data['sma_consumption'] = intval($smatrans['consumption']);
        $data['sma_transportation'] = intval($smatrans['transportation']);
        $data['sma_overtime'] = intval($smatrans['overtime']);
        $data['sma_bonus'] = intval($smatrans['bonus']);
        $data['sma_principal'] = intval($smatrans['principal']);
        $data['sma_principal_helper'] = intval($smatrans['principal_helper']);
        $data['sma_head_department'] = intval($smatrans['head_department']);
        $data['sma_home_room'] = intval($smatrans['home_room']);
        $data['sma_picket'] = intval($smatrans['picket']);
        $data['sma_late'] = intval($smatrans['late']);
        $data['sma_loan'] = intval($smatrans['loan']);
        $data['sma_tax'] = intval($smatrans['tax']);
        $data['sma_insurance'] = intval($smatrans['insurance']);
        $data['sma_other_discount'] = intval($smatrans['other_discount']);
        $data['sma_amount'] = intval($smatrans['amount']);
        
        $data['sma_salary'] = intval($smatrans['basic_salary']+$smatrans['experience']);
        $data['sma_tunjangan'] = intval($smatrans['principal_helper']+$smatrans['head_department']+$smatrans['home_room']);
        $data['sma_total'] = $data['sma_salary'] + $data['sma_tunjangan'] + $smatrans['picket'] + $smatrans['principal'];
        
         // STM - TRANS
        $stmtrans = $this->trans->get_amount($id, 4, 'honor')->row_array();

        $data['stm_consumption'] = intval($stmtrans['consumption']);
        $data['stm_transportation'] = intval($stmtrans['transportation']);
        $data['stm_overtime'] = intval($stmtrans['overtime']);
        $data['stm_bonus'] = intval($stmtrans['bonus']);
        $data['stm_principal'] = intval($stmtrans['principal']);
        $data['stm_principal_helper'] = intval($stmtrans['principal_helper']);
        $data['stm_head_department'] = intval($stmtrans['head_department']);
        $data['stm_home_room'] = intval($stmtrans['home_room']);
        $data['stm_picket'] = intval($stmtrans['picket']);
        $data['stm_late'] = intval($stmtrans['late']);
        $data['stm_loan'] = intval($stmtrans['loan']);
        $data['stm_tax'] = intval($stmtrans['tax']);
        $data['stm_insurance'] = intval($stmtrans['insurance']);
        $data['stm_other_discount'] = intval($stmtrans['other_discount']);
        $data['stm_amount'] = intval($stmtrans['amount']);
        
        $data['stm_salary'] = intval($stmtrans['basic_salary']+$stmtrans['experience']);
        $data['stm_tunjangan'] = intval($stmtrans['principal_helper']+$stmtrans['head_department']+$stmtrans['home_room']);
        $data['stm_total'] = $data['stm_salary'] + $data['stm_tunjangan'] + $stmtrans['picket'] + $stmtrans['principal'];
        
        // SMK - TRANS
        $smktrans = $this->trans->get_amount($id, 5, 'honor')->row_array();

        $data['smk_consumption'] = intval($smktrans['consumption']);
        $data['smk_transportation'] = intval($smktrans['transportation']);
        $data['smk_overtime'] = intval($smktrans['overtime']);
        $data['smk_bonus'] = intval($smktrans['bonus']);
        $data['smk_principal'] = intval($smktrans['principal']);
        $data['smk_principal_helper'] = intval($smktrans['principal_helper']);
        $data['smk_head_department'] = intval($smktrans['head_department']);
        $data['smk_home_room'] = intval($smktrans['home_room']);
        $data['smk_picket'] = intval($smktrans['picket']);
        $data['smk_late'] = intval($smktrans['late']);
        $data['smk_loan'] = intval($smktrans['loan']);
        $data['smk_tax'] = intval($smktrans['tax']);
        $data['smk_insurance'] = intval($smktrans['insurance']);
        $data['smk_other_discount'] = intval($smktrans['other_discount']);
        $data['smk_amount'] = intval($smktrans['amount']);
        
        $data['smk_salary'] = intval($smktrans['basic_salary']+$smktrans['experience']);
        $data['smk_tunjangan'] = intval($smktrans['principal_helper']+$smktrans['head_department']+$smktrans['home_room']);
        $data['smk_total'] = $data['smk_salary'] + $data['smk_tunjangan'] + $smktrans['picket'] + $smktrans['principal'];
        
        // SUMMARY
        $data['consumption'] = intval($smptrans['consumption'] + $smatrans['consumption'] + $stmtrans['consumption'] + $smktrans['consumption']);
        $data['overtime'] = intval($smptrans['overtime'] + $smatrans['overtime'] + $stmtrans['overtime'] + $smktrans['overtime']);
        $data['bonus'] = intval($smptrans['bonus'] + $smatrans['bonus'] + $stmtrans['bonus'] + $smktrans['bonus']);
        $data['transport'] = intval($smptrans['transportation'] + $smatrans['transportation'] + $stmtrans['transportation'] + $smktrans['transportation']);
        $data['loan'] = intval($smptrans['loan'] + $smatrans['loan'] + $stmtrans['loan'] + $smktrans['loan']);
        $data['tax'] = intval($smptrans['tax'] + $smatrans['tax'] + $stmtrans['tax'] + $smktrans['tax']);
        $data['insurance'] = intval($smptrans['insurance'] + $smatrans['insurance'] + $stmtrans['insurance'] + $smktrans['insurance']);
        $data['other'] = intval($smptrans['other_discount'] + $smatrans['other_discount'] + $stmtrans['other_discount'] + $smktrans['other_discount']);
        $data['late'] = intval($smptrans['late'] + $smatrans['late'] + $stmtrans['late'] + $smktrans['late']);
        $data['amount'] = intval($smptrans['amount'] + $smatrans['amount'] + $stmtrans['amount'] + $smktrans['amount']);

        $this->load->view('payroll_honor_recap', $data);
   } 
    
   function salary_invoice($id=0)
   {
      $this->acl->otentikasi2($this->title);

      $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
      $data['h2title'] = 'Print Invoice'.$this->modul['title'];
        
      //properti
      $data['company'] = ucfirst($this->properti['name']);
      $data['address'] = $this->properti['address'];
      $data['phone1']  = $this->properti['phone1'];
      $data['phone2']  = $this->properti['phone2'];
      $data['fax']     = $this->properti['fax'];
      $data['website'] = $this->properti['sitename'];
      $data['email']   = $this->properti['email'];
        
      // JOURNAL
      $val = $this->model->where('id', $id)->get();
      $data['pono']           = 'PYJ-00'.$id;
      $data['date']           = tglin($val->dates);
      $data['cur']            = $val->currency;
      $data['period']         = get_month($val->month).' - '.$val->year;
      $data['user']           = $this->session->userdata('username');
      $data['log']            = $this->model->log;
      $data['director']       = $this->foundation->get_name(1);
      $data['manager']        = $this->foundation->get_name(8);
      $data['payroll_id']     = $id;
      
      // TRANSACTION
      $data['results'] = $this->division->get();
      
      $this->load->view('payroll_salary_recap', $data);
   }
   
   function finance_invoice($id=0)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];
        
        //properti
        $data['company'] = ucfirst($this->properti['name']);
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $val = $this->model->where('id', $id)->get();

        $data['pono']           = 'PYJ-00'.$id;
        $data['date']           = tglin($val->dates);
        $data['cur']            = $val->currency;
        $data['notes']          = $val->notes;
        $data['period']         = get_month($val->month).' - '.$val->year;
        $data['acc']            = ucfirst($val->acc);
        $data['honor']          = number_format($val->total_honor);
        $data['salary']         = number_format($val->total_salary);
        $data['bonus']          = number_format($val->total_bonus);
        $data['consumption']    = number_format($val->total_consumption);
        $data['transportation'] = number_format($val->total_transportation);
        $data['overtime']       = number_format($val->total_overtime);
        $data['late']           = number_format($val->total_late);
        $data['loan']           = number_format($val->total_loan);
        $data['insurance']      = number_format($val->total_insurance);
        $data['tax']            = number_format($val->total_tax);
        $data['other']          = number_format($val->total_other);
        $data['balance']        = number_format($val->balance);
        $data['user']           = $this->session->userdata('username');
        $data['log']            = $this->model->log;
        
        $this->load->view('receipt', $data);
   }

   function invoice($id=0)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];

        $data['pono'] = $id;
        $this->load->view('payroll_invoice_form', $data);
   }
   
// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('sales/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('payroll_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur  = $this->input->post('ccurrency');
        $year = $this->input->post('tyear');

        $data['currency'] = $cur;
        $data['year']     = $year;
        $data['rundate']  = tgleng(date('Y-m-d'));
        $data['log']      = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['payroll'] = $this->pm->report($cur,$year)->result();

        $this->load->view('payroll_report', $data); 
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
    
    public function valid_payroll($month,$year)
    {   
        $this->model->where('month', $month);
        $val = $this->model->where('year', $year)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_payroll', "Payroll period ".$month." - ".$year." : already registered..!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_confirmation($notes=null,$uid)
    {   
        $val = $this->model->where('id', $uid)->get();

        if ($val->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Payroll period ".$val->month." - ".$val->year." : already approved..!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
}

?>