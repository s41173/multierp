<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_payment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Arpayment_model', '', TRUE);
        $this->load->model('Payment_trans_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib(); 
        $this->load->library('bank');
        $this->customer = new Customer_lib();
        $this->user = new Admin_lib();
        $this->journal = new Journal_lib();
        $this->journalgl = new Journalgl_lib();
        $this->cek = new Checkout();
        $this->sales = new Sales_lib();
        $this->tax = new Tax_lib();
        $this->aris = new Ar_installment();
        $this->over = new Soverpayment();
        $this->account = new Account_lib();
        $this->trans = new Trans_ledger_lib();
    }

    private $properti, $modul, $title, $account, $trans;
    private $customer,$user,$journal,$cek,$sales,$tax,$aris,$over,$currency,$journalgl;

    public $atts = array('width'=> '800','height'=> '600',
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
        $data['main_view'] = 'arpayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $appayments = $this->Arpayment_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Arpayment_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Date', 'Cust', 'ACC', 'Check No', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($appayments as $appayment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $total = $this->Payment_trans_model->total($appayment->no);
                $balance = intval($appayment->amount-$total['cost']-$total['tax2']);
                
                $this->table->add_row
                (
                    ++$i, 'CR-00'.$appayment->no, tglin($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($balance),
                    anchor($this->title.'/confirmation/'.$appayment->id,'<span>update</span>',array('class' => $this->post_status($appayment->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$appayment->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$appayment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$appayment->id.'/'.$appayment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'arpayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $appayments = $this->Arpayment_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Cust', 'ACC', 'Check No', 'Total', 'Action');

       $i = 0;
        foreach ($appayments as $appayment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $total = $this->Payment_trans_model->total($appayment->no);
            $balance = intval($appayment->amount-$total['cost']-$total['tax2']);

            $this->table->add_row
            (
                ++$i, 'CR-00'.$appayment->no, tglin($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($balance),
                anchor($this->title.'/confirmation/'.$appayment->id,'<span>update</span>',array('class' => $this->post_status($appayment->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$appayment->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$appayment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$appayment->id.'/'.$appayment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function acc($val=null)
    { switch ($val) { case 'bank': $val = 'Bank'; break; case 'cash': $val = 'Cash'; break; case 'pettycash': $val = 'Petty Cash'; break; } return $val; }
//    ===================== approval ===========================================

    private function post_status($val)
    { if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class; }

    function confirmation($pid)
    {
        $this->acl->otentikasi_admin($this->title);
        $appayment = $this->Arpayment_model->get_ar_payment_by_id($pid)->row();

        if ($appayment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($appayment->dates,$appayment->currency); // cek apakah journal sudah approved atau belum
            $total = $appayment->amount;
//
            if ($total == 0)
            {
              $this->session->set_flashdata('message', "CR-00$appayment->no has no value..!"); // cek payment punya 0 value
              redirect($this->title);
            }
            elseif ($this->cek_so_settled($appayment->no) == FALSE )
            {
                $this->session->set_flashdata('message', "CR-00$appayment->no has been settled..!"); // cek po sudah settled atau belum
                redirect($this->title);
            }
//            elseif ($this->valid_check_no($appayment->no,$pid) == FALSE )
//            {
//                $this->session->set_flashdata('message', "CR-00$appayment->no check no registered..!"); // cek po sudah settled atau belum
//                redirect($this->title);
//            }
            else
            {
                // create jurnal
                if ($this->create_journal($pid) == TRUE){
                    
                  $this->settled_so($appayment->no,$appayment->dates); // fungsi untuk mensettled kan semua po
                
                  // fungsi kartu piutang
                  $this->trans->add($appayment->acc, 'CR', $appayment->no, $appayment->currency, $appayment->dates, 0, $appayment->amount, $appayment->customer, 'AR');
                    
                   $data = array('approved' => 1);
                   $this->Arpayment_model->update_id($pid, $data);
                   $this->session->set_flashdata('message', "$this->title CR-00$appayment->no confirmed..!");
                }
                else{ $this->session->set_flashdata('message', "$this->title CR-00$appayment->no can't confirmed..!"); }
                
               redirect($this->title);
            }
        }
    }
    
    private function create_journal($pid)
    {
       $this->db->trans_start();
       
       $appayment = $this->Arpayment_model->get_ar_payment_by_id($pid)->row();
       
       //  create journal
       $cm = new Control_model();

       $ar         = $cm->get_id(17); // piutang ppn
       $pph23      = $cm->get_id(54); // pph23
       $cost       = $cm->get_id(53); // biaya trf
       $account    = $appayment->account;

       $sum = $this->Payment_trans_model->total($appayment->no);

       $this->journalgl->new_journal('0'.$appayment->no,$appayment->dates,'CR',$appayment->currency, 'Customer payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name, $appayment->amount, $this->session->userdata('log'));
       $dpid = $this->journalgl->get_journal_id('CR','0'.$appayment->no);

       $this->journalgl->add_trans($dpid,$account,intval($sum['amount']+$sum['tax']),0); // bank masuk
       if ($sum['cost'] > 0){ $this->journalgl->add_trans($dpid,$cost,$sum['cost'],0); }
       if ($sum['tax2'] > 0){ $this->journalgl->add_trans($dpid,$pph23,$sum['tax2'],0); }
       $this->journalgl->add_trans($dpid,$ar,0,$appayment->amount); // kredit piutang
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){ return FALSE; }else { return TRUE; }
    }

    private function get_trans_code($po)
    {
        $val = $this->Payment_trans_model->get_last_item($po)->result();
        $ress=null;

        foreach ($val as $res)
        { $ress = $ress.$res->code.'-00'.$res->no.','; }

        return $ress;
    }

    private function valid_check_no($no=null,$pid=null)
    {
        $val = $this->Arpayment_model->get_ar_payment_by_no($no)->row();
        if ($val->check_no != null)
        {
            if ($this->Arpayment_model->cek_no($val->check_no,$pid) == FALSE)
            { return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

    private function settled_so($no,$dates=null)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $data = array('status' => 1, 'p2' => 0);

        foreach ($vals as $val)
        {
           if ($val->cash == 1) { $this->sales->settled_so($val->sid,$data); }
           
           elseif ($val->cash == 0 || $val->cash == 2) 
           { $this->settled_credit_so($val->sid,intval($val->amount+$val->cost+$val->tax+$val->tax2),$dates,$no); }
         //  elseif ($val->cash == 2) { $this->settled_excess_so($val->sid,intval($val->amount+$val->cost+$val->tax+$val->tax2),$dates,$no); }
        }
    }

    private function settled_credit_so($sid,$amount,$dates,$ar_payment)
    {
        $sales = $this->sales->get_so($sid);
        $p1 = $sales->p1 + $amount;
        $p2 = $sales->p2 - $amount;

        if ($p2 <= 0) { $result = array('p2' => $p2, 'status' => 1); }
        else { $result = array('p2' => $p2); }

        $this->sales->update_id($sid,$result);

         // add ar_installment library
        $this->aris->add($sales->no, $ar_payment, $dates, $amount);
    }

    private function settled_excess_so($sid,$amount,$dates,$ar_payment)
    {
        $sales = $this->sales->get_so($sid);


        $result = array('status' => 1);
        $this->sales->update_id($sid,$result);

         // add ar_over payment library  $cust,$sales,$ar_payment,$balance,$over
        $this->over->add($sales->customer, $sales->no, $ar_payment, $sales->p2, $amount-$sales->p2, $sales->currency);
    }

    private function unsettled_so($no,$dates=null)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $total = $this->Payment_trans_model->total($no);
        $total = intval($total['amount']+$total['tax']+$total['tax2']+$total['cost']);
        $data = array('status' => 0, 'p2' => $total);

        foreach ($vals as $val)
        { 
            if ($val->cash == 1) { $this->sales->settled_so($val->sid,$data); }
            elseif ($val->cash == 0 || $val->cash == 2) { $this->unsettled_credit_so($val->sid,intval($val->amount+$val->cost+$val->tax+$val->tax2),$dates,$no); }
         //   elseif ($val->cash == 2) { $this->unsettled_excess_so($val->sid,intval($val->amount+$val->cost+$val->tax+$val->tax2),$dates,$no); }
        }
    }

    private function unsettled_credit_so($sid,$amount,$dates,$ar_payment)
    {
        $sales = $this->sales->get_so($sid);
//        $p1 = $sales->p1 - $amount;
        $p2 = $sales->p2 + $amount;

        if ($sales->status == 1) { $result = array('p2' => $p2, 'status' => 0); }
        else { $result = array('p2' => $p2, 'status' => 0); }

        $this->sales->update_id($sid,$result);

         // add ar_installment library
        $this->aris->delete($sales->no, $ar_payment, $dates);
    }

    private function unsettled_excess_so($sid,$amount,$dates,$ar_payment)
    {
        $sales = $this->sales->get_so($sid);

        $result = array('status' => 0);
        $this->sales->update_id(id,$result);

         // delete over payment library
        $this->over->delete($sales->no, $ar_payment);
    }

    private function cek_so_settled($no)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $res = FALSE;

        foreach ($vals as $val)
        {
            if ($this->sales->cek_settled($val->sid) == FALSE)
            {
                $res = FALSE;
                break;
            }
            else { $res = TRUE; }
        }

        return $res;
    }

    private function cek_journal($date,$currency)
    {
        if ($this->journal->valid_journal($date,$currency) == FALSE)
        {
           $this->session->set_flashdata('message', "Journal for [".tgleng($date)."] - ".$currency." approved..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $appayment = $this->Arpayment_model->get_ar_payment_by_no($po)->row();

        if ( $appayment->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - CR-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }


//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $appayment = $this->Arpayment_model->get_ar_payment_by_id($uid)->row();

        if ($appayment->approved != 1){ $this->remove($uid, $po); }else{ $this->rollback($uid, $po); }
        redirect($this->title);
    }
    
    private function rollback($uid,$po)
    {
       $appayment = $this->Arpayment_model->get_ar_payment_by_id($uid)->row(); 
       $this->unsettled_so($po,$appayment->dates);

       // hapus kartu piutang
       $this->trans->remove($appayment->dates, 'CR', $appayment->no);
       
       $this->journalgl->remove_journal('CR', '0'.$po); // delete journal gl
       
       $data = array('approved' => 0);
       $this->Arpayment_model->update_id($uid, $data);
       $this->session->set_flashdata('message', "1 $this->title successfully rollback..!"); 
    }
    
    private function remove($uid,$po)
    {
      $this->Payment_trans_model->delete_payment($po); // model to delete appayment item
      $this->Arpayment_model->delete($uid); // memanggil model untuk mendelete data
      $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        $data['code'] = $this->Arpayment_model->counter_no();
        
        $this->load->view('arpayment_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'appayment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->input->post('tno');
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'Order No', 'required|callback_valid_no');
//        $this->form_validation->set_rules('tcheck', 'Check No', 'callback_valid_check|callback_valid_check_no');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_date');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')),
                               'no' => $this->input->post('tno'), 'docno' => $this->input->post('tdocno'),
                               'check_no' => null, 'dates' => $this->input->post('tdate'),
                               'currency' => $this->input->post('ccurrency'), 'acc' => $this->input->post('cacc'),
                               'amount' => 0, 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
//
            $this->Arpayment_model->add($appayment);

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$data['code'].'/');
//            echo 'true';
        }
        else
        {
              $this->load->view('arpayment_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        
        $appayment = $this->Arpayment_model->get_ar_payment_by_no($po)->row();
        
        if ($appayment->acc == 'bank'){ $data['bank'] = $this->account->combo_asset(); }else { $data['bank'] = $this->account->combo_based_classi(7); }
        
        $data['currency'] = $this->currency->combo();
        $data['tax'] = $this->tax->combo();
        
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");
        
        $total = $this->Payment_trans_model->total($po);
        $balance = intval($appayment->amount-$total['cost']-$total['tax2']);
        
        $data['venid'] = $appayment->customer;
        $data['default']['customer'] = $appayment->name;
        $data['default']['date'] = $appayment->dates;
        $data['default']['currency'] = $appayment->currency;
        $data['default']['check'] = $appayment->check_no;
        $data['default']['balance'] = $balance;
        $data['default']['acc'] = $this->acc($appayment->acc);
        $data['default']['docno'] = $appayment->docno;

        $data['default']['user'] = $this->user->get_username($appayment->user);

//        ============================ Check  =========================================
        $data['default']['bank'] = $appayment->account;
        $data['default']['due']  = $appayment->due;
        $data['default']['balancecek']  = $balance;

//        ============================ Check  =========================================

//        ============================ Item  =========================================
        $items = $this->Payment_trans_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Type', 'Cost', 'Tax', 'Other Tax', 'Amount', 'Notes', 'Action');

//        $this->db->select('id, ap_payment, code, no, notes, amount');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->code.'-00'.$item->no, $this->get_cash_status($item->cash), number_format($item->cost), number_format($item->tax), number_format($item->tax2), number_format($item->amount), $item->notes,
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('arpayment_transform', $data);
    }

    private function get_cash_status($val)
    { if ($val == 1){ return 'Cash';}elseif ($val == 0){ return 'Credit'; } elseif ($val == 2){ return 'Excess'; }  }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('titem', 'Transaction', 'required|callback_valid_po['.$po.']');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales = $this->sales->get_so($this->input->post('titem'));

            if ($this->input->post('ctype') == 1)
            {
                $pitem = array('ar_payment' => $po, 'code' => 'SO', 'no' => $sales->no, 'sid' => $this->input->post('titem'), 'cash' => $this->input->post('ctype'),
                               'tax' => $sales->tax, 'cost' => 0, 'notes' => $this->input->post('tnotes'),
                               'amount' => intval($sales->total+$sales->costs-$sales->tax)
//                               'amount' => $this->calculate_amount($sales->total,$sales->tax,$sales->costs,$sales->p1, $this->input->post('ctax'))
                    );
            }
            else
            {
                $type = $this->get_status_payment(
                        $this->calculate_amount($sales->total,$sales->tax,$sales->costs,$sales->p1, $this->input->post('ctax')),
                        $this->calculate_credit_tax($this->input->post('tamount'), $this->input->post('ctax')) );

                $pitem = array('ar_payment' => $po, 'code' => 'SO', 'no' => $sales->no, 'sid' => $this->input->post('titem'), 'cash' => $type, 'notes' => $this->input->post('tnotes'),
                               'amount' => intval($this->input->post('tamount')-$this->input->post('ttax2')-$this->input->post('tcost')), 
                               'tax' => $this->input->post('ttax'), 'cost' => $this->input->post('tcost'),'tax2' => $this->input->post('ttax2'));
            }

            $this->Payment_trans_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function get_status_payment($val1=0,$val2=0)
    {
        $res = $val2 - $val1;
        if ($res < 0){ return 0;} elseif( $res > 0 ){ return 2;} elseif($res == 0){ return 1;}
    }

    private function calculate_credit_tax($amount,$tax)
    {
        $net = $amount * $tax;
        $res = $amount - $net;
        return $res;
    }

    private function update_trans($po)
    {
        $totals = $this->Payment_trans_model->total($po);
        $res = intval($totals['cost']+$totals['tax']+$totals['tax2']+$totals['amount']);
        
        $appayment = array('amount' => $res);
	$this->Arpayment_model->update($po, $appayment);
    }

    private function calculate_amount($gross,$tax,$costs,$p1,$potongan)
    {
        $res = $gross-$tax;
        $pajakpotongan = $res*$potongan;
        $hpp = $res-$pajakpotongan+$tax+$costs-$p1;
        return $hpp;
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Payment_trans_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer_edit['.$po.']');
        $this->form_validation->set_rules('tcheck', 'Check No', '');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_date');
        $this->form_validation->set_rules('cbank', 'Bank', 'callback_valid_check');
        $this->form_validation->set_rules('tdue', 'Due Date', 'callback_valid_check_due');


        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')),
                               'log' => $this->session->userdata('log'), 'dates' => $this->input->post('tdate'), 
                               'account' => $this->input->post('cbank'),
                               'due' => setnull($this->input->post('tdue')), 'check_no' => $this->cek_null($this->input->post('tcheck')));

            $this->Arpayment_model->update($po, $appayment);
            echo 'true';
        }
        else { echo validation_errors(); }
    }


    public function valid_po($no,$arpayment)
    {
        if ($this->Payment_trans_model->get_item_based_po($arpayment,$no,'SO') == FALSE)
        {
            $this->form_validation->set_message('valid_po', "SO already registered to journal.!");
            return FALSE;
        }
        else { return TRUE; }
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


    public function valid_customer($name)
    {
        if ($this->customer->valid_customer($name) == FALSE)
        {
            $this->form_validation->set_message('valid_customer', "Invalid Customer.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function valid_customer_edit($val,$po)
    {
        $num = $this->Payment_trans_model->get_last_item($po)->num_rows();
        
        if ($num > 0)
        {
          $this->form_validation->set_message('valid_customer_edit', "Please Remove Transaction Item First..!");
          return FALSE;
        }
        else { return TRUE; }
    }
    
    function valid_no($val)
    {
        if ($this->Arpayment_model->valid_no($val) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No Already Registered..!"); return FALSE; 
        }
        else { return TRUE; }
    }
    
    function valid_check($val)
    {
        $acc = $this->input->post('tacc');

        if ($acc == 'Bank')
        {
            if ($val == null) { $this->form_validation->set_message('valid_check', "Check No / Field Required..!"); return FALSE; }
            else { return TRUE; }
        }
        else { return TRUE; }
    }

    function valid_check_due($due)
    {
        if ($this->input->post('tcheck') != "")
        {
            if ($due == ""){  $this->form_validation->set_message('valid_check_due', "Due Date Required..!"); return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================
    
   function invoice($po=null)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'tombolprint','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $data['pono'] = $po;
        $this->load->view('appayment_invoice_form', $data);
   }

   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $appayment = $this->Arpayment_model->get_ar_payment_by_no($po)->row();
//
       $data['pono'] = $po;
       $data['acc'] = strtoupper($this->acc($appayment->acc));
       $data['podate'] = tgleng($appayment->dates);
       $data['bank'] = $this->account->get_name($appayment->account);
       $data['docno'] = $appayment->docno;
       $data['customer'] = $appayment->prefix.' '.$appayment->name;
       $data['ven_bank'] = $this->customer->get_customer_bank($appayment->customer);
       $data['check'] = $appayment->check_no;
       $data['checkdue'] = $appayment->due;
       
       $amt = $this->Payment_trans_model->total($po);
       $data['amount'] = number_format(intval($amt['amount']+$amt['tax']));
       
       $data['items'] = $this->Payment_trans_model->get_details_based_id($po)->result();

       $terbilang = $this->load->library('terbilang');
       if ($appayment->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca(intval($amt['amount']+$amt['tax']))).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca(intval($amt['amount']))); }

       $this->load->view('arpayment_invoice', $data);
   }

// ===================================== PRINT ===========================================

    private function cek_null($val=null)
    { if ($val) { return $val; } else { return NULL; } }


    //    ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $this->load->view('arpayment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $customer = $this->input->post('tcustomer');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $acc = $this->input->post('cacc');
        $cur = $this->input->post('ccurrency');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['acc'] = $acc;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        

        
        if ($this->input->post('ctype') == 0){ $data['reports'] = $this->Arpayment_model->report($customer,$start,$end,$acc,$cur)->result(); $this->load->view('arpayment_report', $data); }
        elseif ($this->input->post('ctype') == 1){ $data['reports'] = $this->Payment_trans_model->report($customer,$start,$end,$acc,$cur)->result(); $this->load->view('arpayment_summary', $data); }
        elseif ($this->input->post('ctype') == 2){ $data['reports'] = $this->Payment_trans_model->report($customer,$start,$end,$acc,$cur)->result(); $this->load->view('arpayment_pivot', $data); }

        

    }

//    ================================ REPORT =====================================

}

?>