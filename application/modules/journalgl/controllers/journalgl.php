<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Journalgl extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Journal_model', 'jmodel', TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->journaltype = new Journaltype_lib();
        $this->account = $this->load->library('account_lib');
        $this->classi = $this->load->library('classification_lib');
        $this->ledger = new Ledger_lib();

        $this->model = new Gl();
        $this->mitem = new Transaction();
    }

    private $properti, $modul, $title,$model,$mitem,$journaltype;
    private $vendor,$user,$journal,$currency,$account,$classi,$ledger;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
       $this->ledger->set_profit_loss(); 
       $this->get_last_journal();
    }

    function get_last_journal()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'journal_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['jurnaltype'] = $this->journaltype->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $this->model->order_by("dates", "desc");
        $this->model->order_by("id", "desc");
        $this->model->get($this->modul['limit'], $offset);

        $journals = $this->model;
        $num_rows = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_journal');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Balance', 'Action');

            $i = 0 + $offset;
            foreach ($journals as $journal)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $journal->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, $journal->code.'-'.$journal->no, $journal->currency, tglin($journal->dates), $journal->notes, number_format($journal->balance),
                    anchor($this->title.'/confirmation/'.$journal->id,'<span>update</span>',array('class' => $this->post_status($journal->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$journal->no.'/'.$journal->code,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$journal->no.'/'.$journal->code,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$journal->id.'/'.$journal->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    private function get_search($no=null,$ref=null,$dates=null)
    {
        if ($no){ $this->model->where('no', $no); }
        elseif ($ref){ $this->model->where('code', $ref); }
        elseif ($dates) { $this->model->where('dates', $dates); }
        return $this->model->get();
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'journal_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['jurnaltype'] = $this->journaltype->combo_all();
        $journals = $this->get_search($this->input->post('tno'), $this->input->post('cref'), $this->input->post('tdate'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Balance', 'Action');

        $i = 0;
        foreach ($journals as $journal)
        {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $journal->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, $journal->code.'-'.$journal->no, $journal->currency, tglin($journal->dates), $journal->notes, number_format($journal->balance),
                anchor($this->title.'/confirmation/'.$journal->id,'<span>update</span>',array('class' => $this->post_status($journal->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$journal->no.'/'.$journal->code,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$journal->no.'/'.$journal->code,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$journal->id.'/'.$journal->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }


    function get_list($currency=null,$vendor=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['main_view'] = 'vendor_list';
        $data['currency'] = $this->currency->combo();
        $data['link'] = array('link_back' => anchor($this->title.'/get_list','<span>back</span>', array('class' => 'back')));

        $currency = $this->input->post('ccurrency');
        $vendor = $this->vendor->get_vendor_id($this->input->post('tvendor'));

        $journals = $this->Purchase_model->get_journal_list($currency,$vendor)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Cur', 'Notes', 'Total', 'Balance', 'Action');

        $i = 0;
        foreach ($journals as $journal)
        {
           $datax = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$journal->no.'\',\'titem\')'
                         );

            $this->table->add_row
            (
                ++$i, 'PO-00'.$journal->no, tgleng($journal->dates), $journal->currency, $journal->notes, number_format($journal->total,3), number_format($journal->p2,3),
                form_button($datax)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('journal_list', $data);
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
        $this->acl->otentikasi3($this->title);
        $journal = $this->model->where('id', $pid)->get();
        $ps = new Period();

        if ($journal->approved == 1) { $this->session->set_flashdata('message', "$this->title already approved..!"); }
        elseif ($journal->balance == 0){ $this->session->set_flashdata('message', "$this->title has no value..!"); }
        elseif ($this->valid_period($journal->dates) == FALSE ){ $this->session->set_flashdata('message', "$this->title has invalid period..!"); }
        else
        {
            if ($this->cek_cf($pid) == TRUE){$this->model->cf = 1;}else{$this->model->cf = 0;}
            $this->model->approved = 1;
            $this->model->save();
            $this->ledger->set_profit_loss($journal->currency);
            $this->session->set_flashdata('message', "$this->title GJ-0$journal->no confirmed..!");
        }
        redirect($this->title);
    }
    
    private function cek_cf($pid)
    {
       $ac = new Account_lib();
       $result = $this->mitem->where('gl_id', $pid)->get();
       $res = FALSE;
       foreach ($result as $val){ if ($ac->get_classi($val->account_id) == 7 || $ac->get_classi($val->account_id) == 8){ $res = TRUE; break; } }
       return $res;
    }


//    ===================== approval ===========================================


    function delete($uid,$po=null)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->model->where('id', $uid)->get();
        $cur = $this->model->currency;

        if ( $this->valid_period($this->model->dates) == TRUE )
        { 
           if ($val->approved == 1) 
           {
               $val->approved = 0;
               $val->save();
               $this->ledger->set_profit_loss($cur);
               $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");
           }
           else
           {
              $this->mitem->where('gl_id', $uid)->get();
              $this->mitem->delete_all();
              $val->delete();
              $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
           }
        }
        else{ $this->session->set_flashdata('message', "1 $this->title can't removed, invalid period..!");} 
        
        redirect($this->title);
    }

    private function counter()
    {
        $res = 0;
        if ( $this->model->count() > 0 )
        {
           $this->model->select_max('no');
           $this->model->where('code', 'GJ')->get();
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
        $data['jurnaltype'] = $this->journaltype->combo();
        
        $this->load->view('journal_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'journal_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        $data['jurnaltype'] = $this->journaltype->combo();

	// Form validation
        $this->form_validation->set_rules('tno', 'No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->no       = $this->input->post('tno');
            $this->model->code     = $this->input->post('ctype');
            $this->model->dates    = $this->input->post('tdate');
            $this->model->currency = $this->input->post('ccurrency');
            $this->model->docno    = $this->input->post('tdocno');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->desc     = $this->input->post('tdesc');
            $this->model->log      = $this->session->userdata('log');

            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno').'/'.$this->input->post('ctype'));
//            echo 'true';
        }
        else
        {
              $this->load->view('journal_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null,$code=null)
    {
        $this->acl->otentikasi2($this->title);

        $this->model->where('no',$po);
        $journal = $this->model->where('code',$code)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$journal->id);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$journal->id);
        $data['currency'] = $this->currency->combo();
        $data['code'] = $po;
        $data['codetrans'] = $code;
        $data['user'] = $this->session->userdata("username");
        $data['jurnaltype'] = $this->journaltype->combo();

        $data['default']['date'] = $journal->dates;
        $data['default']['currency'] = $journal->currency;
        $data['default']['note'] = $journal->notes;
        $data['default']['desc'] = $journal->desc;
        $data['default']['docno'] = $journal->docno;
        $data['default']['balance'] = $journal->balance;

//        ============================ Item  =========================================
        $items = $this->mitem->where('gl_id', $journal->id)->get();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        $this->table->set_heading('No', 'Account', 'Debit', 'Credit', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            
            $this->table->add_row
            (
                ++$i, $this->account->get_name($item->account_id), number_format($item->debit), number_format($item->credit),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po.'/'.$journal->id.'/'.$code,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $res = $this->get_debit_credit($journal->id);
        $data['debit']   = number_format($res[0]);
        $data['credit']  = number_format($res[1]);
        $data['balance'] = number_format($res[2]);

        $data['table'] = $this->table->generate();
        
        $this->load->view('journal_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
//        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('titem', 'Item Name', 'required');
        $this->form_validation->set_rules('tdebit', 'Debit', 'required|numeric');
        $this->form_validation->set_rules('tcredit', 'Credit', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($po) == TRUE)
        {
            $this->mitem->gl_id = $po;
            $this->mitem->account_id = $this->account->get_id_code($this->input->post('titem'));
            $this->mitem->debit = $this->input->post('tdebit');
            $this->mitem->credit = $this->input->post('tcredit');
            $this->mitem->vamount = $this->calculate_vamount($this->account->get_id_code($this->input->post('titem')), $this->input->post('tdebit'), $this->input->post('tcredit'));

            $this->mitem->save();
            $this->update_trans($po);
            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function calculate_vamount($acc,$debit=0,$credit=0)
    {
        $type = $this->classi->get_type($this->account->get_classi($acc));
        $res = 0;

        if ($type == 'harta'){ $res = 0 + $debit - $credit; }
        elseif ($type == 'kewajiban'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'modal'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'pendapatan'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'biaya'){ $res = 0 + $debit - $credit; }
        return $res;
    }

    private function update_trans($po)
    {
        if ($this->cek_balance($po) == TRUE)
        {
            $this->mitem->select_sum('debit');
            $this->mitem->where('gl_id',$po)->get();

            $this->model->where('id', $po)->get();
            $this->model->balance = $this->mitem->debit;
        }
        else
        {
            $this->model->where('id', $po)->get();
            $this->model->balance = 0;
        }

        $this->model->save();
    }

    private function cek_balance($id)
    {
        $this->mitem->select_sum('debit');
        $this->mitem->select_sum('credit');
        $this->mitem->where('gl_id',$id)->get();
        $debit = intval($this->mitem->debit);
        $credit = intval($this->mitem->credit);
        if ($debit!=$credit){ return FALSE; } else{ return TRUE; }
    }

    private function get_debit_credit($id)
    {
        $this->mitem->select_sum('debit');
        $this->mitem->select_sum('credit');
        $this->mitem->where('gl_id',$id)->get();
        $debit = intval($this->mitem->debit);
        $credit = intval($this->mitem->credit);

        $res = null;
        $res[0] = $debit;
        $res[1] = $credit;
        $res[2] = $debit-$credit;
        return $res;
    }

    function delete_item($id,$po,$jid,$code)
    {
        $this->acl->otentikasi2($this->title);

        if ( $this->valid_confirmation($jid) == TRUE )
        {
            $this->mitem->where('id',$id)->get();
            $this->mitem->delete();
            $this->update_trans($jid);
            $this->session->set_flashdata('message', "1 item successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "Journal approved, can't deleted..!"); }
        redirect($this->title.'/add_trans/'.$po.'/'.$code);
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

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($jid) == TRUE)
        {
            $this->model->where('id',$jid)->get();

            $this->model->dates    = $this->input->post('tdate');
            $this->model->docno    = $this->input->post('tdocno');
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
        $this->model->where('code', 'GJ');
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
    

   function invoice($po=null,$code=null)
   {
        $this->acl->otentikasi1($this->title);

        $journal = $this->model->where('no',$po)->where('code',$code)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['code'] = $po;
        $data['codetrans'] = $code;
        $data['user'] = $this->session->userdata("username");

        $data['dates'] = $journal->dates;
        $data['currency'] = $journal->currency;
        $data['notes'] = $journal->notes;
        $data['desc'] = $journal->desc;
        $data['docno'] = $journal->docno;
        $data['balance'] = $journal->balance;
        
        $res = $this->get_debit_credit($journal->id);
        $data['debit']   = $res[0];
        $data['credit']  = $res[1];
        $data['balances'] = number_format($res[2]);

//        ============================ Item  =========================================
        $data['items'] = $this->mitem->where('gl_id', $journal->id)->order_by('id', 'asc')->get();

        $this->load->view('journal_invoice', $data);

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
        
        $data['jurnaltype'] = $this->journaltype->combo_all();
        $data['currency'] = $this->currency->combo();
        
        $this->load->view('journal_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $journal = $this->input->post('cjournal');
        $type = $this->input->post('ctype');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        
        $model = new Journal_model();
        $data['journals'] = $this->jmodel->search($cur,$journal,$start,$end)->result();

        $this->load->view('journal_report', $data); 
        
    }


// ====================================== REPORT =========================================

}

?>