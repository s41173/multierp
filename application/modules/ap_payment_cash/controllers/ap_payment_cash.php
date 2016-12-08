<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap_payment_cash extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Appaymentcash_model', '', TRUE);
        $this->load->model('Payment_trans_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->load->library('bank');
        $this->vendor = $this->load->library('vendor_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->cek = $this->load->library('checkout');
        $this->ap = $this->load->library('ap_lib');

    }

    private $properti, $modul, $title;
    private $vendor,$user,$journal,$cek,$ap,$currency;

    public $atts = array('width'=> '500','height'=> '300',
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
        $data['main_view'] = 'appayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $appayments = $this->Appaymentcash_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Appaymentcash_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Type', 'Date', 'Vendor', 'ACC', 'Check No', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($appayments as $appayment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CG-00'.$appayment->no, $this->get_type($appayment->tax), tgleng($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount),
                    anchor($this->title.'/confirmation/'.$appayment->id,'<span>update</span>',array('class' => $this->post_status($appayment->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$appayment->no,'<span>print</span>',$this->atts).' '.
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
        $data['main_view'] = 'appayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $appayments = $this->Appaymentcash_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('cacc'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Type', 'Date', 'Vendor', 'ACC', 'Check No', 'Total', 'Action');

        $i = 0;
        foreach ($appayments as $appayment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CG-00'.$appayment->no, $this->get_type($appayment->tax), tgleng($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount),
                anchor($this->title.'/confirmation/'.$appayment->id,'<span>update</span>',array('class' => $this->post_status($appayment->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$appayment->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$appayment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$appayment->id.'/'.$appayment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function get_type($val)
    { if ($val == 0) {$val = 'Non Tax';} else{$val = 'Tax';} return $val; }

    private function acc($val=null)
    { switch ($val) { case 'bank': $val = 'Bank'; break; case 'cash': $val = 'Cash'; break; case 'pettycash': $val = 'Petty Cash'; break; } return $val; }
//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $appayment = $this->Appaymentcash_model->get_ap_payment_cash_by_id($pid)->row();

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
              $this->session->set_flashdata('message', "CG-00$appayment->no has no value..!"); // cek payment punya 0 value
              redirect($this->title);
            }
            elseif ($this->cek_po_settled($appayment->no) == FALSE )
            {
                $this->session->set_flashdata('message', "CG-00$appayment->no has been settled..!"); // cek po sudah settled atau belum
                redirect($this->title);
            }
            elseif ($this->valid_check_no($appayment->no,$pid) == FALSE )
            {
                $this->session->set_flashdata('message', "CG-00$appayment->no check no registered..!"); // cek po sudah settled atau belum
                redirect($this->title);
            }
            else
            {
                $this->settled_po($appayment->no); // fungsi untuk mensettled kan semua po

                $data = array('approved' => 1);
                $this->Appaymentcash_model->update_id($pid, $data);

                //  create journal
                $this->journal->create_journal($appayment->dates, $appayment->currency,
                        'Payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name.' - '.$this->acc($appayment->acc).' - '.$this->bank->get_bank_name($appayment->bank),
                        'CG', $appayment->no, 'AP', $appayment->amount);

               $this->session->set_flashdata('message', "$this->title CG-00$appayment->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
            }
        }

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
        $val = $this->Appaymentcash_model->get_ap_payment_cash_by_no($no)->row();
        if ($val->check_no != null)
        {
            if ($this->Appaymentcash_model->cek_no($val->check_no,$pid) == FALSE)
            { return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

    private function settled_po($no)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $data = array('status' => 1);

        foreach ($vals as $val)
        {  $this->ap->settled_ap($val->no,$data); }
    }

    private function unsettled_po($no)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $data = array('status' => 0);

        foreach ($vals as $val)
        {  $this->ap->settled_ap($val->no,$data); }
    }

    private function cek_po_settled($no)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $res = FALSE;

        foreach ($vals as $val)
        {
            if ($this->ap->cek_settled($val->no) == FALSE)
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
        $appayment = $this->Appaymentcash_model->get_ap_payment_cash_by_no($po)->row();

        if ( $appayment->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - CG-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }


//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);

        if ( $this->journal->cek_approval('CG',$po) == TRUE ) // cek journal harian sudah di approve atau belum
        {
            $this->unsettled_po($po);
            $this->journal->remove_journal('CG',$po); // delete journal

            $this->Payment_trans_model->delete_payment($po); // model to delete appayment item
            $this->Appaymentcash_model->delete($uid); // memanggil model untuk mendelete data

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            redirect($this->title);
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!");
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
        
        $this->load->view('appayment_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'appayment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Appaymentcash_model->counter_no();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
//        $this->form_validation->set_rules('tcheck', 'Check No', 'callback_valid_check|callback_valid_check_no');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('rtype', 'Tax Type', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')),
                               'no' => $this->Appaymentcash_model->counter_no(), 'docno' => $this->get_docno($this->input->post('rtype')),
                               'check_no' => null, 'dates' => $this->input->post('tdate'),
                               'currency' => $this->input->post('ccurrency'), 'acc' => $this->input->post('cacc'),
                               'amount' => 0, 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
//
            $this->Appaymentcash_model->add($appayment);

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$data['code'].'/');
//            echo 'true';
        }
        else
        {
              $this->load->view('appayment_form', $data);
//            echo validation_errors();
        }

    }

    private function get_docno($type)
    { if ($type == 0) {  $no = $this->Appaymentcash_model->counter_docno(); } else { $no = 0; } return $no; }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        
        $data['currency'] = $this->currency->combo();
        $data['bank'] = $this->bank->combo_all();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $appayment = $this->Appaymentcash_model->get_ap_payment_cash_by_no($po)->row();

        $data['venid'] = $appayment->vendor;

        $data['default']['vendor'] = $appayment->name;
        $data['default']['date'] = $appayment->dates;
        $data['default']['currency'] = $appayment->currency;
        $data['default']['check'] = $appayment->check_no;
        $data['default']['balance'] = $appayment->amount;
        $data['default']['acc'] = $appayment->acc;
        $data['default']['docno'] = $appayment->docno;

        if ($appayment->tax == 0) {$appayment->tax = 'Non Tax';} else { $appayment->tax = 'Tax';}
        $data['default']['type'] = $appayment->tax;

        $data['default']['user'] = $this->user->get_username($appayment->user);

//        ============================ Check  =========================================

           $data['default']['bank']  = $appayment->bank;
           $data['default']['due']  = $appayment->due;
           $data['default']['balancecek']  = $appayment->amount;

//        ============================ Check  =========================================

//        ============================ Item  =========================================
        $items = $this->Payment_trans_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Amount', 'Action');

//        $this->db->select('id, ap_payment, code, no, notes, amount');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->code.'-00'.$item->no, number_format($item->amount),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('appayment_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('titem', 'Transaction', 'required|callback_valid_gj');

        if ($this->form_validation->run($this) == TRUE)
        {
            $ap = $this->ap->get_ap($this->input->post('titem'));

            $pitem = array('ap_payment' => $po, 'code' => 'GJ', 'no' => $this->input->post('titem'), 'amount' => $ap->amount);
            $this->Payment_trans_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $totals = $this->Payment_trans_model->total($po);
        $appayment = array('amount' => $totals['amount']);
	$this->Appaymentcash_model->update($po, $appayment);
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

        $this->form_validation->set_rules('tcheck', 'Check No', 'callback_valid_check');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('cbank', 'Bank', 'callback_valid_check');
        $this->form_validation->set_rules('tdue', 'Due Date', 'callback_valid_check');


        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('log' => $this->session->userdata('log'), 'dates' => $this->input->post('tdate'), 'bank' => $this->input->post('cbank'),
                               'due' => $this->input->post('tdue'), 'check_no' => $this->cek_null($this->input->post('tcheck')));

            $this->Appaymentcash_model->update($po, $appayment);


//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('appayment_transform', $data);
            echo validation_errors();
        }
    }


    public function valid_gj($no)
    {
        if ($this->Payment_trans_model->get_item_based_po($no,'GJ') == FALSE)
        {
            $this->form_validation->set_message('valid_gj', "GJ already registered to journal.!");
            return FALSE;
        }
        else { return TRUE; }
    }


    public function valid_vendor($name)
    {
        if ($this->vendor->valid_vendor($name) == FALSE)
        {
            $this->form_validation->set_message('valid_vendor', "Invalid Vendor.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }


    function valid_check($val)
    {
        $acc = $this->input->post('tacc');

        if ($acc == 'bank')
        {
            if ($val == null) { $this->form_validation->set_message('valid_check', "Check No / Field Required..!"); return FALSE; }
            else { return TRUE; }
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

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        $data['pono'] = $po;
        $this->load->view('payment_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Receipt'.$this->modul['title'];

       $appayment = $this->Appaymentcash_model->get_ap_payment_cash_by_no($po)->row();
//
       $data['pono'] = $po;
       $data['acc'] = strtoupper($this->acc($appayment->acc));
       $data['podate'] = tgleng($appayment->dates);
       $data['bank'] = $this->bank->get_bank_name($appayment->bank);
       $data['docno'] = $appayment->docno;
       $data['vendor'] = $appayment->prefix.' '.$appayment->name;
       $data['ven_bank'] = $this->vendor->get_vendor_bank($appayment->vendor);
       $data['amount'] = number_format($appayment->amount);
//
       $data['items'] = $this->Payment_trans_model->get_po_details($po)->result();

       $terbilang = $this->load->library('terbilang');
       if ($appayment->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount)); }

       if ($type)
       { if ($appayment->acc == 'bank'){ $this->load->view('bank_invoice_blank', $data); } else { $this->load->view('cash_invoice_blank', $data); } }
       else { $this->load->view('appayment_invoice', $data); }
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

        $this->load->view('appayment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $acc = $this->input->post('cacc');
        $cur = $this->input->post('ccurrency');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['acc'] = $this->acc($acc);
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Appaymentcash_model->report($vendor,$start,$end,$acc,$cur)->result();

        $total = $this->Appaymentcash_model->total($vendor,$start,$end,$acc,$cur);
        $data['total'] = $total['amount'];

        if ($this->input->post('cformat') == 0){  $this->load->view('appayment_report', $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view('appayment_report', $data, TRUE));
        }

    }

//    ================================ REPORT =====================================

}

?>