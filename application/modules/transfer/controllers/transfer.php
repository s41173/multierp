<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transfer extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Transfer_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency  = $this->load->library('currency_lib');
        $this->user      = $this->load->library('admin_lib');
        $this->journal   = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account = new Account_lib();
        $this->ledger  = new Cash_ledger_lib();
    }

    private $properti, $modul, $title, $account, $ledger;
    private $vendor,$user,$journal,$currency,$journalgl;
    
     private  $atts = array('width'=> '800','height'=> '300',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 300)/2)+\'');

    function index()
    {
        $this->get_last_transfer();
    }

    function get_last_transfer()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'transfer_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $transfers = $this->Transfer_model->get_last_transfer($this->modul['limit'], $offset)->result();
        $num_rows = $this->Transfer_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_transfer');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Currency', 'From Acc', 'To Acc', 'Amount', 'Action');

            $i = 0 + $offset;
            foreach ($transfers as $transfer)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $transfer->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'TR-00'.$transfer->no, tgleng($transfer->dates), $transfer->notes, $transfer->currency, $this->acc_type($transfer->from), $this->acc_type($transfer->to), number_format($transfer->amount),
                    anchor($this->title.'/confirmation/'.$transfer->id,'<span>update</span>',array('class' => $this->post_status($transfer->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$transfer->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/update/'.$transfer->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$transfer->id.'/'.$transfer->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'transfer_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('transfer/','<span>back</span>', array('class' => 'back')));

        $transfers = $this->Transfer_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Currency', 'From Acc', 'To Acc', 'Amount', 'Action');

        $i = 0;
        foreach ($transfers as $transfer)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $transfer->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'TR-00'.$transfer->no, tgleng($transfer->dates), $transfer->notes, $transfer->currency, $this->acc_type($transfer->from), $this->acc_type($transfer->to), number_format($transfer->amount),
                anchor($this->title.'/confirmation/'.$transfer->id,'<span>update</span>',array('class' => $this->post_status($transfer->approved), 'title' => 'edit / update')).' '.
                anchor($this->title.'/update/'.$transfer->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$transfer->id.'/'.$transfer->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function status($val=null)
    {
        switch ($val) { case 0: $val = 'debt'; break;  case 1: $val = 'settled'; break; }
        return $val;
    }


    private function acc_type($val=null)
    {
        return $this->account->get_code($val).'-'.$this->account->get_name($val);
    }
//    ===================== transferproval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $transfer = $this->Transfer_model->get_transfer_by_id($pid)->row();

        if ($transfer->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($transfer->dates,$transfer->currency); // cek transferakah journal sudah approved atau belum
            $total = $transfer->amount;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Transfer_model->update_id($pid, $data);

                //  create journal
                
                $cm = new Control_model();
        
                
                $from = $transfer->from; 
                $to = $transfer->to;
                
                 // add cash ledger
                $this->ledger->remove($transfer->dates, "TR-00".$transfer->no);
                $this->ledger->add($this->get_acc_type($transfer->to), "TR-00".$transfer->no, $transfer->currency, $transfer->dates, $transfer->amount, 0);

                
                $this->journalgl->new_journal('0'.$transfer->no,$transfer->dates,'TR',$transfer->currency, 'Transfer from : '.$this->acc_type($transfer->from).' to '.$this->acc_type($transfer->to), $transfer->amount, $this->session->userdata('log'));
                $dpid = $this->journalgl->get_journal_id('TR','0'.$transfer->no);
                
                $this->journalgl->add_trans($dpid,$from,0,$transfer->amount); // from
                $this->journalgl->add_trans($dpid,$to,$transfer->amount,0); // to

               $this->session->set_flashdata('message', "$this->title TR-00$transfer->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
            }
        }

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
        $transfer = $this->Transfer_model->get_transfer_by_no($po)->row();

        if ( $transfer->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - TR-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== transferproval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $transfer = $this->Transfer_model->get_transfer_by_no($po)->row();

        if ($this->valid_period($transfer->dates) == TRUE ) // cek journal harian sudah di approve atau belum
        {
            if ($transfer->approved == 1)
            {
              $this->ledger->remove($transfer->dates, "TR-00".$po); // cash ledger    
              $this->journalgl->remove_journal('TR', '0'.$po);
              $data = array('approved' => 0);
              $this->Transfer_model->update_id($uid, $data);
            }
            else 
            {  $this->ledger->remove($transfer->dates, "TR-00".$po); // cash ledger  
               $this->Transfer_model->delete($uid); 
            }
            
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
        $data['code'] = $this->Transfer_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $data['account'] = $this->account->combo_asset();
        
        $this->load->view('transfer_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'transfer_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Transfer_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['account'] = $this->account->combo_asset();

	// Form validation
        $this->form_validation->set_rules('tno', 'GJ - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('cfrom', 'From', 'required');
        $this->form_validation->set_rules('cto', 'To', 'required|callback_valid_acc');

        if ($this->form_validation->run($this) == TRUE)
        {
            $transfer = array('no' => $this->input->post('tno'), 'from' => $this->input->post('cfrom'), 'to' => $this->input->post('cto'),
                        'dates' => $this->input->post('tdate'), 'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'),
                        'amount' => $this->input->post('tamount'), 'log' => $this->session->userdata('log'));
            
             // add cash ledger
//            $this->ledger->remove($this->input->post('tdate'), "TR-00".$this->input->post('tno'));
//            $this->ledger->add($this->get_acc_type($this->input->post('cfrom')), "TR-00".$this->input->post('tno'), $this->input->post('ccurrency'), $this->input->post('tdate'), 0, $this->input->post('tamount'));
//            $this->ledger->add($this->get_acc_type($this->input->post('cto')), "TR-00".$this->input->post('tno'), $this->input->post('ccurrency'), $this->input->post('tdate'), $this->input->post('tamount'), 0);
            
            $this->Transfer_model->add($transfer);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add/');
//            echo 'true';
        }
        else
        {
              $this->load->view('transfer_form', $data);
//            echo validation_errors();
        }

    }
    
   function invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);
       $ap = $this->Transfer_model->get_transfer_by_no($po)->row();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $po;
       $data['podate'] = tglin($ap->dates);
       $data['notes'] = $ap->notes;
       $data['from'] = $this->acc_type($ap->from);
       $data['to'] = $this->acc_type($ap->to);
       $data['currency'] = $ap->currency;
       $data['log'] = $this->session->userdata('log');

       $data['amount'] = $ap->amount;
       $terbilang = $this->load->library('terbilang');
       if ($ap->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)); }
       
       if($ap->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

//       if ($ap->approved != 1){ $this->load->view('rejected', $data); }
//       else { $this->load->view('apc_invoice', $data); }
       $this->load->view('transfer_invoice', $data);

   }

    function update($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $po;
        $data['account'] = $this->account->combo_asset();

        $transfer = $this->Transfer_model->get_transfer_by_no($po)->row();

        $data['default']['date'] = $transfer->dates;
        $data['default']['currency'] = $transfer->currency;
        $data['default']['note'] = $transfer->notes;
        $data['default']['amount'] = $transfer->amount;
        $data['default']['from'] = $transfer->from;
        $data['default']['to'] = $transfer->to;

        $this->session->set_userdata('po',$po);

        $this->load->view('transfer_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($this->session->userdata('po'),'update');

        $data['currency'] = $this->currency->combo();
        $data['account'] = $this->account->combo_asset();

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('cfrom', 'From', 'required');
        $this->form_validation->set_rules('cto', 'To', 'required|callback_valid_acc');

        if ($this->form_validation->run($this) == TRUE)
        {

            $transfer = array('from' => $this->input->post('cfrom'), 'to' => $this->input->post('cto'),
                              'dates' => $this->input->post('tdate'), 'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'),
                              'amount' => $this->input->post('tamount'), 'log' => $this->session->userdata('log'));

            $this->Transfer_model->update($this->session->userdata('po'), $transfer);
            
            // add cash ledger
            
//            $this->ledger->remove($this->input->post('tdate'), "TR-00".$this->input->post('tno'));
//            $this->ledger->add($this->get_acc_type($this->input->post('cfrom')), "TR-00".$this->input->post('tno'), $this->input->post('ccurrency'), $this->input->post('tdate'), 0, $this->input->post('tamount'));
//            $this->ledger->add($this->get_acc_type($this->input->post('cto')), "TR-00".$this->input->post('tno'), $this->input->post('ccurrency'), $this->input->post('tdate'), $this->input->post('tamount'), 0);
            
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('po'));
            $this->session->unset_userdata('po');
//            echo 'true';
        }
        else
        {
            $this->load->view('transfer_update', $data);
//            echo validation_errors();
        }
    }
    
    private function get_acc_type($account)
    {
       if ($this->account->get_classi($account) == 7) { return 'cash'; }
       elseif ($this->account->get_classi($account) == 8) { return 'bank'; }
       else { return 'bank'; }
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
        else
        {
            return TRUE;
        }
    }

    public function valid_no($no)
    {
        if ($this->Transfer_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_acc($val)
    {
        $from = $this->input->post('cfrom');
        if ( $val == $from )
        {
            $this->form_validation->set_message('valid_acc', "Invalid Account.!");
            return FALSE;
        }
        else { return TRUE; }
    }


//   ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $this->load->view('transfer_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['reports'] = $this->Transfer_model->report($start,$end)->result();

        $this->load->view('transfer_report', $data);

    }

//   ================================ REPORT =====================================

}

?>