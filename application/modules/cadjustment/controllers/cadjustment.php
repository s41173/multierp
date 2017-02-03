<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cadjustment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Cadjustment_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib(); 
        $this->customer = new Customer_lib();
        $this->user     = new Admin_lib();
        $this->tax      = new Tax_lib();
        $this->journal  = new Journal_lib();
        $this->ar       = new Ar_payment();
        $this->contract = new Contract_lib();
        $this->journalgl = new Journalgl_lib();

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency, $journalgl;
    private $customer,$user,$tax,$journal,$ar,$contract;

    function index()
    {
        $this->get_last_cadjustment();
    }
    
//    ============ AJAX =================
    
    function get_contract_balance()
    {
       $co = $this->input->post('co');
       if ($co)
       {
         $val = $this->contract->get_contract_details($co);  
         echo $val->balance;
       }
    }

    function get_last_cadjustment()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cadjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last_cadjustment');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $cadjustments = $this->Cadjustment_model->get_last_contract($this->modul['limit'], $offset)->result();
        $num_rows = $this->Cadjustment_model->count_all_num_rows();

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_cadjustment');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($cadjustments as $cadjustment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cadjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'COA-00'.$cadjustment->no, tglin($cadjustment->dates), $cadjustment->notes, number_format($cadjustment->total),
                    anchor($this->title.'/confirmation/'.$cadjustment->id,'<span>update</span>',array('class' => $this->post_status($cadjustment->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$cadjustment->id,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/add_trans/'.$cadjustment->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cadjustment->id.'/'.$cadjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $year = date('Y');

        $arpData[0][1] = 'January';
        $arpData[0][2] = $this->Cadjustment_model->total_chart('01',$year,$cur);

        $arpData[1][1] = 'February';
        $arpData[1][2] = $this->Cadjustment_model->total_chart('02',$year,$cur);

        $arpData[2][1] = 'March';
        $arpData[2][2] = $this->Cadjustment_model->total_chart('03',$year,$cur);

        $arpData[3][1] = 'April';
        $arpData[3][2] = $this->Cadjustment_model->total_chart('04',$year,$cur);

        $arpData[4][1] = 'May';
        $arpData[4][2] = $this->Cadjustment_model->total_chart('05',$year,$cur);

        $arpData[5][1] = 'June';
        $arpData[5][2] = $this->Cadjustment_model->total_chart('06',$year,$cur);

        $arpData[6][1] = 'July';
        $arpData[6][2] = $this->Cadjustment_model->total_chart('07',$year,$cur);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->Cadjustment_model->total_chart('08',$year,$cur);

        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->Cadjustment_model->total_chart('09',$year,$cur);

        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->Cadjustment_model->total_chart('10',$year,$cur);

        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->Cadjustment_model->total_chart('11',$year,$cur);

        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->Cadjustment_model->total_chart('12',$year,$cur);

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Cadjustment", "98%", 400, false, false) ;
        return $graph;
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'cadjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        $cadjustments = $this->Cadjustment_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Total', 'Action');

        $i = 0;
        foreach ($cadjustments as $cadjustment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cadjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'COA-00'.$cadjustment->no, tglin($cadjustment->dates), $cadjustment->notes, number_format($cadjustment->total),
                anchor($this->title.'/confirmation/'.$cadjustment->id,'<span>update</span>',array('class' => $this->post_status($cadjustment->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$cadjustment->id,'<span>print</span>',$atts).' '.
                anchor($this->title.'/add_trans/'.$cadjustment->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$cadjustment->id.'/'.$cadjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    function confirmation($pid)
    {
        $this->acl->otentikasi_admin($this->title);
        $cadjustment = $this->Cadjustment_model->get_contract_adjustment_by_id($pid)->row();

        if ($cadjustment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $total = $cadjustment->total;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                //  create journal
                if ($this->create_journal($pid) == TRUE){
                   // update contract balance 
                   $this->contract->update_balance($cadjustment->contract_no, $cadjustment->total, 0);
                
                   $data = array('approved' => 1);
                   $this->Cadjustment_model->update_id($pid, $data);
                   $this->session->set_flashdata('message', "$this->title COA-00$cadjustment->no confirmed..!");
                }else{$this->session->set_flashdata('message', "$this->title COA-00$cadjustment->no can't confirmed..!"); }
               redirect($this->title);
            }
        }

    }
    
    private function create_journal($pid)
    {
        $this->db->trans_start();
        $cadjustment = $this->Cadjustment_model->get_contract_adjustment_by_id($pid)->row(); 
        $contract = $this->contract->get_contract_details($cadjustment->contract_no);
        
        //  create journal        
        $cm = new Control_model();
        
        if ($contract->type == 'tax'){ $ar = $cm->get_id(56); }else{ $ar = $cm->get_id(57); } 
        $discount = $cm->get_id(4);

        $this->journalgl->new_journal('0'.$cadjustment->no,$cadjustment->dates,'COA',$contract->currency, 'Contract Adjustment : CO-00'.$contract->no, $cadjustment->total, $this->session->userdata('log'));
        $dpid = $this->journalgl->get_journal_id('COA','0'.$cadjustment->no);

        $this->journalgl->add_trans($dpid,$discount,$cadjustment->total,0); // diskon D
        $this->journalgl->add_trans($dpid,$ar,0,$cadjustment->total); // piutang kontrak tax / non
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){ return FALSE; }else { return TRUE; }
    }

    private function cek_journal($date,$currency)
    {
        if ($this->journal->valid_journal($date,$currency) == FALSE)
        {
           $this->session->set_flashdata('message', "Journal for [".tgleng($date)."] - ".$currency." approved..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($pid=null,$page=null)
    {
        $cadjustment = $this->Cadjustment_model->get_contract_adjustment_by_id($pid)->row();

        if ( $cadjustment->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - SO-00$cadjustment->no approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================

    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $cadjustment = $this->Cadjustment_model->get_contract_adjustment_by_id($uid)->row();
        
        if ($cadjustment->approved == 1){ $this->rollback($uid, $po); }else { $this->remove($uid, $po); }
        redirect($this->title);
    }

    private function rollback($uid,$po)
    {
      // upgrade contract balance
      $cadjustment = $this->Cadjustment_model->get_contract_adjustment_by_id($uid)->row();
      $this->contract->update_balance($cadjustment->contract_no, $cadjustment->total, 1);  
      
      $this->journalgl->remove_journal('COA', '0'.$po); // journal gl  
     
      $data = array('approved' => 0);
      $this->Cadjustment_model->update_id($uid, $data);  
      
      $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");
    }
    
    private function remove($uid,$po)
    {
       $this->Cadjustment_model->delete($uid); // memanggil model untuk mendelete data 
       $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Cadjustment_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('cadjustment_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cadjustment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Cadjustment_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'COA - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_date');
        $this->form_validation->set_rules('tcontract', 'Order Contract', 'required|callback_valid_contract');
        $this->form_validation->set_rules('tcobalance', 'Contract Balance', 'required');
        $this->form_validation->set_rules('ttotal', 'Adjustment Amount', 'required|numeric|callback_valid_balance');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $cadjustment = array( 'contract_no' => $this->input->post('tcontract'),
                           'no' => $this->input->post('tno'), 'docno' => $this->input->post('tdocno'),
                           'total' => $this->input->post('ttotal'),'dates' => $this->input->post('tdate'),
                           'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                           'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Cadjustment_model->add($cadjustment);
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add/');
            echo 'true';
        }
        else
        {
//              $this->load->view('cadjustment_form', $data);
            echo validation_errors();
        }

    }

    function add_trans($pid=null)
    {
        $this->acl->otentikasi2($this->title);

        $cadjustment = $this->Cadjustment_model->get_contract_adjustment_by_id($pid)->row();
        $year  = date('Y', strtotime($cadjustment->dates));
        
        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$cadjustment->no.'/'.$year);
        $data['currency'] = $this->currency->combo();
        $data['tax'] = $this->tax->combo();
        $data['user'] = $this->session->userdata("username");

        
        $data['code'] = $cadjustment->no;

        $data['default']['contract'] = $cadjustment->contract_no;
        $data['default']['date'] = $cadjustment->dates;
        $data['default']['note'] = $cadjustment->notes;
        $data['default']['desc'] = $cadjustment->desc;
        $data['default']['user'] = $this->user->get_username($cadjustment->user);
        $data['default']['docno'] = $cadjustment->docno;
        $data['default']['total'] = $cadjustment->total;
        
        $this->load->view('cadjustment_transform', $data);
    }

    private function update_trans($po,$year)
    {
        $totals = $this->Cadjustment_item_model->total($po,$year);
        $cadjustment = array('tax' => $totals['tax'], 'total' => $totals['amount'], 'discount' => $totals['discount_amount'], 'p2' => $totals['amount']);
        $pid = $this->Cadjustment_model->get_cadjustment_by_no($po,$year)->row();
	$this->Cadjustment_model->update_id($pid->id, $cadjustment);
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
	$data['link'] = array('link_back' => anchor('cadjustment/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tno', 'COA - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_date');
        $this->form_validation->set_rules('tcontract', 'Order Contract', 'required|callback_valid_contract');
        $this->form_validation->set_rules('tcobalance', 'Contract Balance', 'required');
        $this->form_validation->set_rules('ttotal', 'Adjustment Amount', 'required|numeric|callback_valid_balance');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $cadjustment = array( 'no' => $this->input->post('tno'), 'docno' => $this->input->post('tdocno'),
                           'total' => $this->input->post('ttotal'),'dates' => $this->input->post('tdate'),
                           'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                           'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));

            $this->Cadjustment_model->update_id($pid, $cadjustment);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('cadjustment_transform', $data);
            echo validation_errors();
        }
    }

  
    public function valid_contract($contract)
    {
        if ($this->contract->cek_approval_contract($contract) == FALSE)
        {
            $this->form_validation->set_message('valid_contract', "Invalid Contract Order.!");
            return FALSE;
        }
        else{ return TRUE;}
    }
    
    public function valid_balance($total)
    {
        $cototal = $this->input->post('tcobalance');
        if ($total > $cototal)
        {
            $this->form_validation->set_message('valid_balance', "Invalid Adjustment Balance.!");
            return FALSE;
        }
        else{ return TRUE;}
    }

    public function valid_no($no)
    {
        if ($this->Cadjustment_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_date($date)
    {
        $cur = $this->input->post('ccurrency');
        if ($this->journal->valid_journal($date,$cur) == FALSE)
        {
            $this->form_validation->set_message('valid_date', "Journal [ ".tgleng($date)." ] - ".$cur." already approved.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================

   function print_invoice($pid=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];

       $cadjustment = $this->Cadjustment_model->get_contract_adjustment_by_id($pid)->row();
       $year  = date('Y', strtotime($cadjustment->dates));
       
        // properti
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];

       $data['pono'] = 'COA-00'.$cadjustment->no;
       $data['contract'] = 'CO-00'.$cadjustment->contract_no;
       $data['podate'] = tglincomplete($cadjustment->dates);
       $data['docno'] = $cadjustment->docno;
       $data['desc'] = $cadjustment->desc;
       $data['notes'] = $cadjustment->notes;
       $data['desc'] = $cadjustment->desc;
       $data['user'] = $this->user->get_username($cadjustment->user);
       $data['total'] = $cadjustment->total;
       $data['log'] = $cadjustment->log;
       if ($cadjustment->approved == 1){ $data['stts'] = 'Y'; }else { $data['stts'] = 'N'; }

       $this->load->view('cadjustment_invoice', $data);
       
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('cadjustment/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('cadjustment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['cadjustments'] = $this->Cadjustment_model->report($start,$end)->result();
        $total = $this->Cadjustment_model->total($start,$end);
        
        $data['total'] = $total['total'];

        if ($type == 'sum') {  $this->load->view('cadjustment_report', $data); }
        elseif ($type == 'pivot') {  $this->load->view('cadjustment_pivot', $data); }
        
    }


// ====================================== REPORT =========================================

}

?>