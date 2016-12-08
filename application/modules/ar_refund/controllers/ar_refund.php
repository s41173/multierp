<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_refund extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ar_refund_model', '', TRUE);
        $this->load->model('Ar_refund_trans_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->load->library('bank');
        $this->customer = $this->load->library('customer_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->cek = $this->load->library('checkout');
        $this->sales = $this->load->library('sales_lib');
        $this->over = $this->load->library('soverpayment');

    }

    private $properti, $modul, $title;
    private $customer,$user,$journal,$journalgl,$cek,$sales,$over,$currency;

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
        $data['main_view'] = 'ar_refund_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $appayments = $this->Ar_refund_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Ar_refund_model->count_all_num_rows();

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
                
                $this->table->add_row
                (
                    ++$i, 'CF-00'.$appayment->no, tgleng($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount),
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
        $data['main_view'] = 'ar_refund_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $appayments = $this->Ar_refund_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Cust', 'ACC', 'Check No', 'Total', 'Action');

        $i = 0;
        foreach ($appayments as $appayment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CF-00'.$appayment->no, tgleng($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount),
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
        $appayment = $this->Ar_refund_model->get_ar_refund_by_id($pid)->row();

        if ($appayment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($appayment->dates,$appayment->currency); 
            $total = $appayment->amount;
//
            if ($total == 0)
            {
              $this->session->set_flashdata('message', "RF-00$appayment->no has no value..!");
              redirect($this->title);
            }
            elseif ($this->valid_period($appayment->dates != TRUE) )
            {
                $this->session->set_flashdata('message', "Invalid Period..!");
                redirect($this->title);
            }
            elseif ($this->valid_check_no($appayment->no,$pid) == FALSE )
            {
                $this->session->set_flashdata('message', "RF-00$appayment->no check no registered..!");
                redirect($this->title);
            }
            else
            {
                $this->do_confirmation($appayment->no); // fungsi untuk remove over payment table

                $data = array('approved' => 1);
                $this->Ar_refund_model->update_id($pid, $data);

                //  create journal
                $this->journal->create_journal($appayment->dates, $appayment->currency,
                                              'Payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name.' - '.$this->acc($appayment->acc).' - '.$this->bank->get_bank_name($appayment->bank),
                                              'RF', $appayment->no, 'AP', $appayment->amount);
                
                 $cm = new Control_model();
        
                 $bank     = $cm->get_id(22);
                 $kas      = $cm->get_id(13);
                 $kaskecil = $cm->get_id(14);
                 $refund   = $cm->get_id(34);
                 $account  = 0;
               
                 $this->journalgl->new_journal('0000'.$appayment->no, $appayment->dates,'CD', $appayment->currency, 'Payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name.' - '.$this->acc($appayment->acc).' - '.$this->bank->get_bank_name($appayment->bank), $appayment->amount, $this->session->userdata('log'));
                 $dpid = $this->journalgl->get_journal_id('CD','0000'.$appayment->no);
               
                 switch ($appayment->acc) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }              
                 
                 $this->journalgl->add_trans($dpid,$refund,$appayment->amount,0); // refund ( debit )
                 $this->journalgl->add_trans($dpid,$account,0,$appayment->amount); // kas, bank, kas kecil ( kredit )

               $this->session->set_flashdata('message', "$this->title RF-00$appayment->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
            }
        }

    }

    private function do_confirmation($no)
    {
        $val = $this->Ar_refund_trans_model->get_last_item($no)->result();
        foreach ($val as $value){ $this->over->delete_by_no($value->no); }
    }

    private function undo_confirmation($no,$cust,$cur)
    {
        $val = $this->Ar_refund_trans_model->get_last_item($no)->result();

        foreach ($val as $value)
        { $this->over->add_undo($value->no, $cust, $value->sales_no, $value->ar_payment, $value->balance, $value->over, $cur); }
    }

    private function get_trans_code($po)
    {
        $val = $this->Ar_refund_trans_model->get_last_item($po)->result();
        $ress=null;

        foreach ($val as $res)
        { $ress = $ress.'SOV-00'.$res->no.','; }

        return $ress;
    }

    private function valid_check_no($no=null,$pid=null)
    {
        $val = $this->Ar_refund_model->get_ar_refund_by_no($no)->row();
        if ($val->check_no != null)
        {
            if ($this->Ar_refund_model->cek_no($val->check_no,$pid) == FALSE)
            { return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
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
        $appayment = $this->Ar_refund_model->get_ar_refund_by_no($po)->row();

        if ( $appayment->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - RF-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }


//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $appayment = $this->Ar_refund_model->get_ar_refund_by_id($uid)->row();

        if ($appayment->approved != 1)
        {
            $this->Ar_refund_trans_model->delete_payment($po); // model to delete appayment item
            $this->Ar_refund_model->delete($uid); // memanggil model untuk mendelete data
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else
        {
            if ( $this->journal->cek_approval('RF',$po) == TRUE || $this->valid_period($appayment->dates == TRUE) ) // cek journal harian sudah di approve atau belum
            {
                $this->undo_confirmation($po,$appayment->customer,$appayment->currency);
                $this->journal->remove_journal('RF',$po); // delete journal
                
                $this->journalgl->remove_journal('CD', '0000'.$po); // journal gl

                $this->Ar_refund_trans_model->delete_payment($po); // model to delete appayment item
                $this->Ar_refund_model->delete($uid); // memanggil model untuk mendelete data
                $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            }
            else
            { $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!"); }
        }

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
        
        $this->load->view('ar_refund_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'appayment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Ar_refund_model->counter_no();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer');
//        $this->form_validation->set_rules('tcheck', 'Check No', 'callback_valid_check|callback_valid_check_no');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_date|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tnotes', 'Notes', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')),
                               'no' => $this->Ar_refund_model->counter_no(), 'notes' => $this->input->post('tnotes'),
                               'check_no' => null, 'dates' => $this->input->post('tdate'),
                               'currency' => $this->input->post('ccurrency'), 'acc' => $this->input->post('cacc'),
                               'amount' => 0, 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
//
            $this->Ar_refund_model->add($appayment);

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$data['code'].'/');
//            echo 'true';
        }
        else
        {
              $this->load->view('ar_refund_form', $data);
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

        $appayment = $this->Ar_refund_model->get_ar_refund_by_no($po)->row();
        
        $data['currency'] = $this->currency->combo();
        $data['sover'] = $this->over->combo_all($appayment->customer,$appayment->currency);
        $data['bank'] = $this->bank->combo_all();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $data['venid'] = $appayment->customer;

        $data['default']['customer'] = $appayment->name;
        $data['default']['date'] = $appayment->dates;
        $data['default']['currency'] = $appayment->currency;
        $data['default']['check'] = $appayment->check_no;
        $data['default']['balance'] = $appayment->amount;
        $data['default']['acc'] = $this->acc($appayment->acc);
        $data['default']['notes'] = $appayment->notes;

        $data['default']['user'] = $this->user->get_username($appayment->user);

//        ============================ Check  =========================================

        $data['default']['bank']  = $appayment->bank;
        $data['default']['due']  = $appayment->due;
        $data['default']['balancecek']  = $appayment->amount;

//        ============================ Check  =========================================

//        ============================ Item  =========================================
        $items = $this->Ar_refund_trans_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Sales / AR Payment', 'Amount', 'Action');

//        $this->db->select('id, ap_payment, code, no, notes, amount');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, 'SOV-00'.$item->no, 'SO-00'.$item->sales_no.' / CR-00'.$item->ar_payment, number_format($item->over),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('ar_refund_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('cover', 'Transaction', 'required|callback_valid_refund_trans['.$po.']');

        if ($this->form_validation->run($this) == TRUE)
        {
            $over = $this->over->get_by_no($this->input->post('cover'));
            $pitem = array('ar_refund' => $po, 'no' => $over->no, 'sales_no' => $over->sales_no, 
                           'ar_payment' => $over->ar_payment, 'balance' => $over->balance, 'over' => $over->over);

            $this->Ar_refund_trans_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $totals = $this->Ar_refund_trans_model->total($po);
        $appayment = array('amount' => $totals['over']);
	$this->Ar_refund_model->update($po, $appayment);
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Ar_refund_trans_model->delete($id); // memanggil model untuk mendelete data
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
        $this->form_validation->set_rules('tcheck', 'Check No', '');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_date|callback_valid_period');
        $this->form_validation->set_rules('cbank', 'Bank', 'callback_valid_check');
        $this->form_validation->set_rules('tdue', 'Due Date', 'callback_valid_check_due');


        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('log' => $this->session->userdata('log'), 'dates' => $this->input->post('tdate'), 'bank' => $this->input->post('cbank'),
                               'due' => setnull($this->input->post('tdue')), 'check_no' => $this->cek_null($this->input->post('tcheck')));

            $this->Ar_refund_model->update($po, $appayment);
            echo 'true';
        }
        else { echo validation_errors(); }
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

    public function valid_refund_trans($so,$ar_refund)
    {
        if ($this->Ar_refund_trans_model->valid_trans($so,$ar_refund) == FALSE)
        {
            $this->form_validation->set_message('valid_refund_trans', "SOV already registered..!");
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

       $appayment = $this->Ar_refund_model->get_ar_refund_by_no($po)->row();
//
       $data['pono'] = $po;
       $data['acc'] = strtoupper($this->acc($appayment->acc));
       $data['podate'] = tgleng($appayment->dates);
       $data['bank'] = $this->bank->get_bank_name($appayment->bank);
       $data['notes'] = $appayment->notes;
       $data['customer'] = $appayment->prefix.' '.$appayment->name;
       $data['ven_bank'] = $this->customer->get_customer_bank($appayment->customer);
       $data['amount'] = number_format($appayment->amount);
//
       $data['items'] = $this->Ar_refund_trans_model->get_last_item($po)->result();

       $terbilang = $this->load->library('terbilang');
       if ($appayment->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount)); }

       
//
//       // property display
//       $data['paddress'] = $this->properti['address'];
//       $data['p_phone1'] = $this->properti['phone1'];
//       $data['p_phone2'] = $this->properti['phone2'];
//       $data['p_city'] = ucfirst($this->properti['city']);
//       $data['p_zip'] = $this->properti['zip'];
//       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('ar_refund_invoice', $data);
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

        $this->load->view('ar_refund_report_panel', $data);
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
        $data['reports'] = $this->Ar_refund_model->report($customer,$start,$end,$acc,$cur)->result();

        $total = $this->Ar_refund_model->total($customer,$start,$end,$acc,$cur);
        $data['total'] = $total['amount'];

        $this->load->view('ar_refund_report', $data);

    }

//    ================================ REPORT =====================================

}

?>