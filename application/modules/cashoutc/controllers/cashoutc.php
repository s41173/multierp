<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cashoutc extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account = new Account_lib();
        $this->vendor = $this->load->library('vendor_lib');
        $this->load->library('terbilang');

        $this->model = new Cashout();
        $this->load->model('Cashout_trans_model', 'transmodel', TRUE);
    }

    private $properti, $modul, $title,$model;
    private $vendor,$user,$cash,$currency,$account,$journalgl;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
       $this->get_last_cash();
    }

    function get_last_cash()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cash_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $this->model->order_by("dates", "desc");
        $this->model->get($this->modul['limit'], $offset);

        $cashs = $this->model;
        $num_rows = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_cash');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Acc', 'Balance', 'Action');

            $i = 0 + $offset;
            foreach ($cashs as $cash)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cash->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CD-0000'.$cash->no, $cash->currency, tgleng($cash->dates), $this->vendor->get_vendor_name($cash->vendor), $cash->notes, $this->get_acc($cash->acc), number_format($cash->amount),
                    anchor($this->title.'/confirmation/'.$cash->id,'<span>update</span>',array('class' => $this->post_status($cash->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$cash->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$cash->no.'/'.$cash->code,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cash->id.'/'.$cash->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
    
    private function get_acc($val)
    {
        return $this->account->get_code($val).' : '.$this->account->get_name($val);
    }

    private function get_search($no=null,$dates=null)
    {
        if ($no){ $this->model->where('no', $no); }
        elseif ($dates) { $this->model->where('dates', $dates); }
        return $this->model->get();
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'cash_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $cashs = $this->get_search($this->input->post('tno'), $this->input->post('tdate'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Acc', 'Balance', 'Action');

        $i = 0;
        foreach ($cashs as $cash)
        {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $cash->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CD-0000'.$cash->no, $cash->currency, tgleng($cash->dates), $this->vendor->get_vendor_name($cash->vendor), $cash->notes, $this->get_acc($cash->acc), number_format($cash->amount),
                anchor($this->title.'/confirmation/'.$cash->id,'<span>update</span>',array('class' => $this->post_status($cash->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$cash->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$cash->no.'/'.$cash->code,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$cash->id.'/'.$cash->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }


        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class;
    }

    function confirmation($pid)
    {
        $this->acl->otentikasi3($this->title);
        $cash = $this->model->where('id', $pid)->get();

        if ($cash->approved == 1) { $this->session->set_flashdata('message', "$this->title already approved..!"); }
        elseif ($cash->amount == 0){ $this->session->set_flashdata('message', "$this->title has no value..!"); }
        elseif ($this->valid_period($cash->dates) == FALSE ){ $this->session->set_flashdata('message', "$this->title has invalid period..!"); }
        else
        {
            // tambah fungsi calculate balance account
            //$this->calculate_account_balance($cash->id);
            $this->model->approved = 1;
            $this->model->save();
            $this->model->clear();
            $cash1 = $this->model->where('id', $pid)->get();
            $transs = $this->transmodel->get_last_item($pid)->result();
            
            
             $cm = new Control_model();
       
             $account  = $cash1->acc;
               
             $this->journalgl->new_journal('0000'.$cash1->no, $cash1->dates,'CD', $cash1->currency, 'Payment to : '.$this->vendor->get_vendor_name($cash1->vendor), $cash1->amount, $this->session->userdata('log'));
             $dpid = $this->journalgl->get_journal_id('CD','0000'.$cash1->no);
                          
             foreach ($transs as $trans) 
             {
                 $this->journalgl->add_trans($dpid,$trans->account_id,$trans->balance,0); // kas, bank, kas kecil ( debit )
             }
             $this->journalgl->add_trans($dpid,$account,0,$cash1->amount);
             
             $this->session->set_flashdata('message', "$this->title CD-0000$cash->no confirmed..!");
        }
        redirect($this->title);
    }


//    ===================== approval ===========================================


    function delete($uid,$po=null)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->model->where('id', $uid)->get();

        if ( $this->valid_period($this->model->dates) == TRUE )
        { 
           if ($val->approved == 1) 
           {
              $this->journalgl->remove_journal('CD', '0000'.$po); 
              $val->approved = 0;
              $val->save();
              $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");
           }
           else
           {
             $this->transmodel->delete_po($uid);
             $this->model->delete(); 
             $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
           }
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, invalid period..!");
        } 
        
        redirect($this->title);
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
        $data['code'] = $this->counter();
        $data['user'] = $this->session->userdata("username");
        $data['account'] = $this->account->combo_asset();
        
        $this->load->view('cash_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cash_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->counter();
        $data['user'] = $this->session->userdata("username");
        $data['account'] = $this->account->combo_asset();

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required');
        $this->form_validation->set_rules('tno', 'No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('cacc', 'Account', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->vendor   = $this->vendor->get_vendor_id($this->input->post('tvendor'));
            $this->model->no       = $this->input->post('tno');
            $this->model->acc      = $this->input->post('cacc');
            $this->model->dates    = $this->input->post('tdate');
            $this->model->currency = $this->input->post('ccurrency');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->desc     = $this->input->post('tdesc');
            $this->model->log      = $this->session->userdata('log');

            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('cash_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $cash = $this->model->where('no',$po)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$cash->id);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$cash->id);
        $data['currency'] = $this->currency->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");
        $data['account'] = $this->account->combo_asset();

        $data['default']['date'] = $cash->dates;
        $data['default']['vendor'] = $this->vendor->get_vendor_name($cash->vendor);
        $data['default']['currency'] = $cash->currency;
        $data['default']['note'] = $cash->notes;
        $data['default']['desc'] = $cash->desc;
        $data['default']['acc'] = $cash->acc;
        $data['default']['balance'] = $cash->amount;

//        ============================ Item  =========================================
        $items = $this->transmodel->get_last_item($cash->id)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        $this->table->set_heading('No', 'Account', 'Balance', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            
            $this->table->add_row
            (
                ++$i, $this->account->get_name($item->account_id), number_format($item->balance),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po.'/'.$cash->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('cash_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
//        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('titem', 'Item Name', 'required');
        $this->form_validation->set_rules('tdebit', 'Debit', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($po) == TRUE)
        {
            $pitem = array('cash_id' => $po, 
                           'account_id' => $this->account->get_id_code($this->input->post('titem')),
                           'balance' => $this->input->post('tdebit'));
            
            $this->transmodel->add($pitem);
            $this->update_trans($po);
            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $total = $this->transmodel->total($po);
        $this->model->where('id', $po)->get();
        $this->model->amount = $total['balance'];
        $this->model->save();
    }

    function delete_item($id,$po,$jid)
    {
        $this->acl->otentikasi2($this->title);

        if ( $this->valid_confirmation($jid) == TRUE )
        {
            $this->transmodel->delete($id);
            $this->update_trans($jid);
            $this->session->set_flashdata('message', "1 item successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "Journal approved, can't deleted..!"); }
        redirect($this->title.'/add_trans/'.$po);
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($jid=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$jid);
	$data['link'] = array('link_back' => anchor('journal/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('cacc', 'Account', 'required');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($jid) == TRUE)
        {
            $this->model->where('id',$jid)->get();

            $this->model->dates    = $this->input->post('tdate');
            $this->model->acc      = $this->input->post('cacc');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->desc     = $this->input->post('tdesc');
            $this->model->log      = $this->session->userdata('log');

            $this->model->save();

//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('journal_transform', $data);
            echo validation_errors();
        }
    }


    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_no($no)
    {
        $val = $this->model->where('no', $no)->count();
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_confirmation($id)
    {
        $val = $this->model->where('id', $id)->get();

        if ($val->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Can't change value - Journal approved..!.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================

   function invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);
       $cash = $this->model->where('no', $po)->get();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['p_name'] = $this->properti['name'];
       $data['pono'] = $po;
       $data['podate'] = tgleng($cash->dates);
       $data['vendor'] = $this->vendor->get_vendor_name($cash->vendor);
       $data['desc'] = $cash->desc;
       $data['notes'] = $cash->notes;
       $data['user'] = $this->user->get_username($cash->user);
       $data['currency'] = $cash->currency;
       $data['acc'] = $this->get_acc($cash->acc);
       $data['log'] = $this->session->userdata('log');
       $data['amount'] = $cash->amount;
       
       if ($cash->currency == 'IDR'){ $data['terbilang'] = $this->terbilang->baca($cash->amount).' Rupiah'; }
       else { $data['terbilang'] = $this->terbilang->baca($cash->amount); }

       $data['items'] = $this->transmodel->get_last_item($cash->id)->result();

       $this->load->view('cash_invoice', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('journal/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('cash_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cust = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->get_report_search($cust,$cur,$start,$end);
        
        $data['total'] = 0;
        $this->load->view('cash_report', $data); 
        
    }
    
    private function get_report_search($cust=null,$cur,$start,$end)
    {
       if ($cust) { $this->model->where('vendor', $this->vendor->get_vendor_id($cust)); }
       elseif ($start != '' || $end != '') { $this->model->where_between('dates', "'".$start."'", "'".$end."'"); }
       $this->model->where('currency', $cur);
       return $this->model->where('approved', 1)->get();
    }


// ====================================== REPORT =========================================

}

?>