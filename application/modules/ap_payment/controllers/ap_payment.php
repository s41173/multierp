<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap_payment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Appayment_model', '', TRUE);
        $this->load->model('Payment_trans_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->load->library('bank');
        $this->vendor = new Vendor_lib();
        $this->user = $this->load->library('admin_lib');
        $this->journal   = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->cek = $this->load->library('checkout');
        $this->purchase = $this->load->library('purchase');
        $this->printing = new Printing_lib();
        $this->purchase_return = $this->load->library('purchase_return');
        $this->account = new Account_lib();
        $this->ledger = new Cash_ledger_lib();
        $this->trans = new Trans_ledger_lib();

    }

    private $properti, $modul, $title, $printing, $trans;
    private $vendor,$user,$journal,$cek,$purchase,$purchase_return, $currency, $account,$ledger;

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
        $data['main_view'] = 'appayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $appayments = $this->Appayment_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows   = $this->Appayment_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Type', 'Tax Type', 'Date', 'Vendor', 'ACC', 'Check No', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($appayments as $appayment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CD-0'.$appayment->no, $this->trans_type($appayment->type), $this->get_type($appayment->tax), tglin($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount+$appayment->over),
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
        
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('tyear'));
        
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function trans_type($val)
    {
        if($val == 'PO'){ return 'PURCHASE'; }else { return 'PRINTING'; }
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'appayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();

        $appayments = $this->Appayment_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Type', 'Tax Type', 'Date', 'Vendor', 'ACC', 'Check No', 'Total', 'Action');

        $i = 0;
        foreach ($appayments as $appayment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CD-0'.$appayment->no, $this->trans_type($appayment->type), $this->get_type($appayment->tax), tglin($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount+$appayment->over),
                anchor($this->title.'/confirmation/'.$appayment->id,'<span>update</span>',array('class' => $this->post_status($appayment->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$appayment->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$appayment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$appayment->id.'/'.$appayment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('tyear'));
        $this->load->view('template', $data);
    }
    
    public function chart($cur='IDR')
    {
        $fusion = $this->load->library('fusioncharts');
        $chart  = base_url().'public/flash/Column3D.swf';
        
        $ps = new Period();
        $ps->get();
        $py = new Payment_status_lib();
        
        if ($this->input->post('ccurrency')){ $cur = $this->input->post('ccurrency'); }else { $cur = 'IDR'; }
        if ($this->input->post('tyear')){ $year = $this->input->post('tyear'); }else { $year = $ps->year; }
        
        $arpData[0][1] = 'January';
        $arpData[0][2] =  $this->Appayment_model->total_chart($cur,1,$year);
//
        $arpData[1][1] = 'February';
        $arpData[1][2] =  $this->Appayment_model->total_chart($cur,2,$year);
//
        $arpData[2][1] = 'March';
        $arpData[2][2] =  $this->Appayment_model->total_chart($cur,3,$year);
//
        $arpData[3][1] = 'April';
        $arpData[3][2] =  $this->Appayment_model->total_chart($cur,4,$year);
//
        $arpData[4][1] = 'May';
        $arpData[4][2] =  $this->Appayment_model->total_chart($cur,5,$year);
//
        $arpData[5][1] = 'June';
        $arpData[5][2] =  $this->Appayment_model->total_chart($cur,6,$year);
//
        $arpData[6][1] = 'July';
        $arpData[6][2] =  $this->Appayment_model->total_chart($cur,7,$year);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->Appayment_model->total_chart($cur,8,$year);
        
        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->Appayment_model->total_chart($cur,9,$year);
//        
        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->Appayment_model->total_chart($cur,10,$year);
//        
        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->Appayment_model->total_chart($cur,11,$year);
//        
        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->Appayment_model->total_chart($cur,12,$year);

        $strXML1 = $fusion->setDataXML($arpData,'','') ;
        $graph   = $fusion->renderChart($chart,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
        
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
        $appayment = $this->Appayment_model->get_ap_payment_by_id($pid)->row();
        if ($appayment->type == 'PO'){ $code = 'PO'; }else { $code = 'CP'; }

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
            if ($total <= 0)
            {
              $this->session->set_flashdata('message', "CD-00$appayment->no has no value..!"); // cek payment punya 0 value
              redirect($this->title);
            }
            elseif ($this->cek_po_settled($appayment->no,$code) == FALSE || $this->cek_pr_settled($appayment->no) == FALSE)
            {
                $this->session->set_flashdata('message', "Transaction $appayment->no has been settled..!"); // cek po sudah settled atau belum
                redirect($this->title);
            }
            elseif ($this->valid_check_no($appayment->no,$pid) == FALSE )
            {
                $this->session->set_flashdata('message', "CD-00$appayment->no check no registered..!"); // validasi no check
                redirect($this->title);
            }
            else
            {
               $this->settled_po($appayment->no,$code); // fungsi untuk mensettled kan semua po
                
                // membuat kartu hutang
                $this->trans->add($appayment->acc, 'CD', $appayment->no, $appayment->currency, $appayment->dates, intval($appayment->amount+$appayment->discount-$appayment->late), 0, $appayment->vendor, 'AP'); 
               
               $data = array('approved' => 1);
               $this->Appayment_model->update_id($pid, $data);
                
               $cm = new Control_model();
               
               if ($appayment->post_dated == 1){ $account = $cm->get_id(48); }else { $account = $appayment->account; } // bank atau giro
               
               $ap   = $cm->get_id(11);
               $cost = $cm->get_id(5); // biaya denda
               $discount = $cm->get_id(3); // potongan pembelian
               
               $this->journalgl->new_journal('0'.$appayment->no,$appayment->dates,'CD',$appayment->currency, 'Payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name.' - '.$this->acc($appayment->acc), $appayment->amount, $this->session->userdata('log'));
               $dpid = $this->journalgl->get_journal_id('CD','0'.$appayment->no);
               
               // cash ledger
               $this->ledger->add($appayment->acc, "CD-00".$appayment->no, $appayment->currency, $appayment->dates, 0, $appayment->amount);
               
               if ($appayment->late > 0){ $this->journalgl->add_trans($dpid,$cost,$appayment->late,0); } // denda keterlambatan
               $this->journalgl->add_trans($dpid,$ap,intval($appayment->amount-$appayment->late),0); // hutang usaha
               $this->journalgl->add_trans($dpid,$account,0,$appayment->amount); // kas, bank, kas kecil
               
               if ($appayment->discount > 0)
               {
                  $this->journalgl->new_journal('0'.$appayment->no,$appayment->dates,'PD',$appayment->currency, 'Purchase Discount : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name.' - '.$this->acc($appayment->acc), $appayment->amount, $this->session->userdata('log'));
                  $pdid = $this->journalgl->get_journal_id('PD','0'.$appayment->no);
                  
                  $this->journalgl->add_trans($pdid,$ap,$appayment->discount,0); // hutang usaha
                  $this->journalgl->add_trans($pdid,$discount,0,$appayment->discount); // potongan pembelian
               }
               
               $this->session->set_flashdata('message', "$this->title CD-00$appayment->no confirmed..!"); // set flash data message dengan session
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
        $val = $this->Appayment_model->get_ap_payment_by_no($no)->row();
        if ($val->check_no != null)
        {
            if ($this->Appayment_model->cek_no($val->check_no,$pid) == FALSE)
            { return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

    private function settled_po($no,$code='PO')
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,$code)->result();
        
        if ($code == 'PO')
        {
            foreach ($vals as $val)
            {
                $p2 = $this->purchase->get_po($val->no);
                $p2 = $p2->p2;

                if ($val->amount - $p2 >= 0)
                {
                   $data = array('status' => 1,'p2' => $p2-$val->amount);
                   $this->purchase->settled_po($val->no,$data);
                }
                else
                {
                    $datax = array('p2' => $p2-$val->amount);
                    $this->purchase->settled_po($val->no,$datax);
                }
            }
        }
        else
        {
            foreach ($vals as $val)
            {
                $p2 = $this->printing->get_po($val->no);
                $p2 = $p2->p2;

                if ($val->amount - $p2 >= 0)
                {
                   $data = array('status' => 1,'p2' => $p2-$val->amount);
                   $this->printing->settled_po($val->no,$data);
                }
                else
                {
                    $datax = array('p2' => $p2-$val->amount);
                    $this->printing->settled_po($val->no,$datax);
                }
            }
        }
        
    }

    private function settled_pr($no)
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,'PR')->result();
        $data = array('status' => 1);

        foreach ($vals as $val)
        {  $this->purchase_return->settled_pr($val->no,$data); }
    }

    private function unsettled_po($no,$code)
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,$code)->result();
        
        if ($code == 'PO')
        {
            foreach ($vals as $val)
            {
                $p2 = $this->purchase->get_po($val->no);
                $p2 = $p2->p2;
                $data = array('status' => 0, 'p2'=> $val->amount+$p2);
                $this->purchase->settled_po($val->no,$data);
            }
        }
        else
        {
            foreach ($vals as $val)
            {
                $p2 = $this->printing->get_po($val->no);
                $p2 = $p2->p2;
                $data = array('status' => 0, 'p2'=> $val->amount+$p2);
                $this->printing->settled_po($val->no,$data);
            }
        } 
    }

    private function unsettled_pr($no)
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,'PR')->result();
        $data = array('status' => 0);

        foreach ($vals as $val)
        {  $this->purchase_return->settled_pr($val->no,$data); }
    }

    private function cek_po_settled($no,$code='PO')
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,$code)->result();
        $res = FALSE;
        
        if ($code == 'PO')
        {
            foreach ($vals as $val)
            { if ($this->purchase->cek_settled($val->no) == FALSE){ $res = FALSE;    break; } else { $res = TRUE; } }
        }
        else
        {
            foreach ($vals as $val)
            { if ($this->printing->cek_settled($val->no) == FALSE){ $res = FALSE;   break; } else { $res = TRUE; } }
        }

        return $res;
    }

    private function cek_pr_settled($no)
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,'PR')->result();
        $num  = $this->Payment_trans_model->get_last_trans($no,'PR')->num_rows();
        $res = TRUE;

        if ($num > 0)
        {
           foreach ($vals as $val)
           {
              if ($this->purchase_return->cek_settled($val->no) == FALSE)
              {
                  $res = FALSE;
                  break;
              }
              else { $res = TRUE; }
           }
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
        $appayment = $this->Appayment_model->get_ap_payment_by_no($po)->row();

        if ( $appayment->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - CD-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }


//    ===================== approval ===========================================

    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $appayment = $this->Appayment_model->get_ap_payment_by_no($po)->row();
        if ($appayment->type == 'PO'){ $code = 'PO'; }else { $code = 'CP'; }
        
        if ( $this->valid_period($appayment->dates) == TRUE && $this->valid_credit_over($appayment->no) == TRUE ) // cek journal harian sudah di approve atau belum
        {
            if ($appayment->approved == 1){ $this->rollback($uid, $po,$code); $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");  }
            else {  $this->remove($uid, $po, $code); $this->session->set_flashdata('message', "1 $this->title successfully removed..!");  }
        }
        else
        {  $this->session->set_flashdata('message', "1 $this->title can't removed, transaction has related to another component..!"); } 

        redirect($this->title);
    }
    
    private function rollback($uid,$po,$code)
    {
       $this->unsettled_po($po,$code);
//       $this->unsettled_pr($po);
       $this->journalgl->remove_journal('CD', '0'.$po);
       $this->journalgl->remove_journal('PD', '0'.$po);
       
       // rollback kartu hutang 
      $appayment = $this->Appayment_model->get_ap_payment_by_id($uid)->row();
      $this->trans->remove($appayment->dates, 'CD', $appayment->no);
      $this->ledger->remove($appayment->dates, "CD-00".$appayment->no); // remove cash ledger
       
       $data = array('approved' => 0);
       $this->Appayment_model->update_id($uid, $data); 
    }
    
    private function remove($uid,$po,$code)
    {
       // remove cash ledger
       $val = $this->Appayment_model->get_ap_payment_by_no($po)->row();
       $this->ledger->remove($val->dates, "CD-00".$val->no); 
        
       $this->Payment_trans_model->delete_payment($po); // model to delete appayment item
       $this->Appayment_model->delete($uid); // memanggil model untuk mendelete data 
    }

    function get_voucher_no()
    {
      echo $this->Appayment_model->counter_voucher_no($this->input->post('ctype')); 
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
        $data['code'] = $this->Appayment_model->counter_no();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
//        $this->form_validation->set_rules('tcheck', 'Check No', 'callback_valid_check|callback_valid_check_no');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ctype', 'Tax Type', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('trate', 'Rate', 'required|numeric|callback_valid_rate');
        $this->form_validation->set_rules('tvoucherno', 'Voucher No', 'required|numeric|callback_valid_voucher['.$this->input->post('ctype').']');

        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')), 'voucher_no' => $this->input->post('tvoucherno'),
                               'no' => $this->Appayment_model->counter_no(), 'docno' => $this->get_docno($this->input->post('ctype')),
                               'check_no' => null, 'dates' => $this->input->post('tdate'), 'tax' => $this->input->post('ctype'), 'type' => $this->input->post('ctranstype'),
                               'currency' => $this->input->post('ccurrency'), 'acc' => $this->input->post('cacc'), 'rate' => $this->input->post('trate'),
                               'amount' => 0, 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
//
            $this->Appayment_model->add($appayment);

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
    { if ($type == 0) {  $no = $this->Appayment_model->counter_docno(); } else { $no = 0; } return $no; }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $appayment = $this->Appayment_model->get_ap_payment_by_no($po)->row();
        
        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po.'/'.$appayment->type);
        $data['form_action_return'] = site_url($this->title.'/add_return/'.$po);
        
        $data['currency'] = $this->currency->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");
        
        // account list
        if ($appayment->acc == 'bank'){  $acc = $this->account->combo_asset(); }
        else{  $acc = $this->account->combo_based_classi(7); }
        
        $data['bank'] = $acc;
        $data['venid'] = $appayment->vendor;

        $data['default']['vendor'] = $appayment->name;
        $data['default']['date'] = $appayment->dates;
        $data['default']['transtype'] = $appayment->type;
        $data['default']['currency'] = $appayment->currency;
        $data['default']['check'] = $appayment->check_no;
        $data['default']['check_type'] = $appayment->check_type;
        $data['default']['balance'] = $appayment->amount;
        $data['default']['tdiscount'] = $appayment->discount;
        $data['default']['late'] = $appayment->late;
        $data['default']['acc'] = $appayment->acc;
        $data['default']['docno'] = $appayment->docno;
        $data['default']['rate'] = $appayment->rate;
        $data['default']['no'] = $appayment->no;
        $data['default']['status'] = $appayment->post_dated;
        $data['default']['bank'] = $appayment->account;
        
        $data['default']['checkacc'] = $appayment->check_acc;
        $data['default']['checkaccno'] = $appayment->check_acc_no;

        $data['default']['type'] = $appayment->tax;
        $data['default']['voucher'] = $appayment->voucher_no;

        $data['default']['user'] = $this->user->get_username($appayment->user);

//        ============================ Check  =========================================

        $data['default']['due']  = $appayment->due;
        $data['default']['balancecek']  = $appayment->amount+$appayment->over;

//        ============================ Check  =========================================

//        ============================ Item  =========================================
        $items = $this->Payment_trans_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Discount', 'Amount', 'Action');

//        $this->db->select('id, ap_payment, code, no, notes, amount');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->code.'-00'.$item->no, number_format($item->discount), number_format($item->amount),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('appayment_transform', $data);
    }

//    ======================  Item Transaction   ===============================================================

    function add_item($po=null,$type=null)
    {
        $this->cek_confirmation($po,'add_trans');

        $this->form_validation->set_rules('tnominal', 'Nominal', 'required|numeric');
        $this->form_validation->set_rules('tdiscount', 'Discount', 'required|numeric');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('titem', 'Transaction', 'required');
        
        
        if( strpos($this->input->post('titem'), ',') !== false ) {
            
            $polist = explode(',', $this->input->post('titem'));
                        
            foreach ($polist as $poitem) {
                
               $result = false; 
               $validation = $this->valid_po($poitem,$po); // harus ada validasi
               
               if ($this->form_validation->run($this) == TRUE)
               {
                     if ($type == 'PO'){ $code = 'PO';
                        $purchase = $this->purchase->get_po($poitem);
                        $amount = intval($purchase->p2);
                     }else{ $code = 'CP'; 
                        $purchase = $this->printing->get_po($poitem);
                        $amount = intval($purchase->p2);
                     }
         
                     $pitem = array('ap_payment' => $po, 'code' => $code, 'no' => $poitem, 'discount' => $this->input->post('tdiscount'), 'amount' => $this->calculate_rate($po,$amount));
                     $this->Payment_trans_model->add($pitem);
                     $this->update_trans($po,$code);
                     $result = 'true';
               }
               else{ $result = validation_errors();  break; }
            }
            echo $result;
        }else{
           
            $this->form_validation->set_rules('titem', 'Transaction', 'required|callback_valid_po['.$po.']');   
            if ($this->form_validation->run($this) == TRUE)
            {
                  if ($type == 'PO'){ $code = 'PO'; }else{ $code = 'CP'; }
                  $amount = $this->input->post('tamount');

                  $pitem = array('ap_payment' => $po, 'code' => $code, 'no' => $this->input->post('titem'), 'discount' => $this->input->post('tdiscount'), 'amount' => $this->calculate_rate($po,$amount));
                  $this->Payment_trans_model->add($pitem);
                  $this->update_trans($po,$code);
                  echo 'true';
            }
            else{  echo validation_errors(); }
        }
    }
    
    private function update_trans($po,$code='PO')
    {
        $totals = $this->Payment_trans_model->total($po,$code);
        $res = $totals['amount'];
        
        $val = $this->Appayment_model->get_ap_payment_by_no($po)->row();
        $res = $res+$val->late;
        
        $appayment = array('amount' => $res, 'discount' => $totals['discount']);
	$this->Appayment_model->update($po, $appayment);
    }

    private function calculate_rate($po,$amount)
    {
        $rate = $this->Appayment_model->get_ap_payment_by_no($po)->row();
        $rate = $rate->rate;
        return $rate*$amount;
    }

    function add_return($po=null)
    {
        $this->cek_confirmation($po,'add_trans');

        $this->form_validation->set_rules('treturn', 'Return Transaction', 'required|callback_valid_pr');

        if ($this->form_validation->run($this) == TRUE)
        {
            $purchase = $this->purchase_return->get_pr($this->input->post('treturn'));

            $pitem = array('ap_payment' => $po, 'code' => 'PR', 'no' => $this->input->post('treturn'), 'amount' => $purchase->balance);
            $this->Payment_trans_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
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
//        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('appayment/','<span>back</span>', array('class' => 'back')));

	// Form validation

        $this->form_validation->set_rules('tno', 'Order No', 'callback_valid_confirmation');
        $this->form_validation->set_rules('tcheck', 'Check No', 'callback_valid_check');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('cbank', 'Bank', 'required');
        $this->form_validation->set_rules('tdue', 'Due Date', 'callback_valid_check');
        $this->form_validation->set_rules('tbalancecek', 'Cheque Balance', 'required|numeric');
        $this->form_validation->set_rules('tvoucherno', 'Voucher No', 'required|numeric|callback_validating_voucher['.$po.']');


        if ($this->form_validation->run($this) == TRUE)
        {   
            
            $appayment = array('log' => $this->session->userdata('log'), 'acc' => $this->input->post('cacc'), 'dates' => $this->input->post('tdate'), 'account' => $this->input->post('cbank'), 'late' => $this->input->post('tlate'),
                               'due' => setnull($this->input->post('tdue')), 'post_dated' => $this->input->post('cpost'), 'voucher_no' => $this->input->post('tvoucherno'),
                               'check_acc' => $this->input->post('tcheckacc'), 'check_acc_no' => $this->input->post('tcheckaccno'), 'tax' => $this->input->post('ctype'),
                               'check_type' => $this->input->post('ccheck_type'), 'check_no' => $this->cek_null($this->input->post('tcheck')));

            $this->Appayment_model->update($po, $appayment);
            
            $val = $this->Appayment_model->get_ap_payment_by_no($po)->row();
            if ($val->type == 'PO'){ $code = 'PO'; }else{ $code = 'CP'; }
            $this->update_trans($po,$code);
            
            if ($this->input->post('tbalancecek') > $val->amount)
            {
              $appayment1 = array('over' => intval($this->input->post('tbalancecek')-$val->amount), 'over_stts' => 1); 
            }
            else{ $appayment1 = array('over' => 0, 'over_stts' => 0); }
            
            // cash ledger
//            $this->ledger->add($val->acc, "CD-00".$val->no, $val->currency, $val->dates, 0, $val->amount);
            
            $this->Appayment_model->update($po, $appayment1);   
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('appayment_transform', $data);
            echo validation_errors();
        }
    }
    
    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
           if (cek_previous_period($month, $year) == TRUE){ return TRUE; }
           else { $this->form_validation->set_message('valid_period', "Invalid Period.!"); return FALSE; }
        }
        else {  return TRUE; }
    }

    public function valid_rate($rate)
    {
        if ($rate == 0)
        {
            $this->form_validation->set_message('valid_rate', "Rate can't 0.!");
            return FALSE;
        }
        else { return TRUE; }
    }

    public function valid_confirmation($no)
    {
        $val = $this->Appayment_model->get_ap_payment_by_no($no)->row();

        if ($val->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Can't change value - Order approved..!.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_po($no,$po)
    {
        $appayment = $this->Appayment_model->get_ap_payment_by_no($po)->row();
        if ($appayment->type == 'PO'){ $code = 'PO'; }else { $code = 'CP'; }
        
        if ($this->Payment_trans_model->get_item_based_po($no,$code,$po) == FALSE)
        {
            $this->form_validation->set_message('valid_po', "$code already registered to journal.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_voucher($voucher,$type)
    {
        if ($this->Appayment_model->valid_voucher($voucher,$type) == FALSE)
        {
            $this->form_validation->set_message('valid_voucher', "Voucher No already registered..!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function validating_voucher($voucher,$po)
    {
        $type = $this->input->post('ctype');
        if ($this->Appayment_model->validating_voucher($voucher,$type,$po) == FALSE)
        {
            $this->form_validation->set_message('validating_voucher', "Voucher No already registered..!");
            return FALSE;
        }
        else { return TRUE; }
    }

    public function valid_pr($no)
    {
        if ($this->Payment_trans_model->get_item_based_po($no,'PR') == FALSE)
        {
            $this->form_validation->set_message('valid_pr', "PR already registered to journal.!");
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
    
    function valid_credit_over($no)
    {
        $val = $this->Appayment_model->get_ap_payment_by_no($no)->row();

        if ($val->credit_over == 1)
        {
           $this->form_validation->set_message('valid_credit_over', "Transaction Has Credited To Another Transaction..!"); return FALSE;
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================

   function invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $appayment = $this->Appayment_model->get_ap_payment_by_no($po)->row();
       if ($appayment->type == 'PO'){ $code = 'PO'; }else { $code = 'CP'; }
//
       $data['pono'] = $po;
       $data['acc'] = strtoupper($this->acc($appayment->acc));
       $data['podate'] = tgleng($appayment->dates);
       $data['bank'] = $this->account->get_code($appayment->account).' : '.$this->account->get_name($appayment->account);
       $data['docno'] = $appayment->docno;
       $data['vendor'] = $appayment->prefix.' '.$appayment->name;
       $data['ven_bank'] = $this->vendor->get_vendor_bank($appayment->vendor);
       $data['amount'] = number_format($appayment->amount);
       $data['late'] = number_format($appayment->late);
       $data['over'] = number_format($appayment->over);
       $data['check'] = $appayment->check_no;
       
       $data['checkacc'] = $appayment->check_acc;
       $data['checkaccno'] = $appayment->check_acc_no;
       
       if ($appayment->tax == 1){ $data['type'] = 'Tax'; }else { $data['type'] = 'Non'; }
       $data['voucher'] = $appayment->voucher_no;
       
       if ($appayment->check_no){ $data['check_type'] = $appayment->check_type; }else { $data['check_type'] = ""; }
       $data['due'] = isset($appayment->due) ? tglin($appayment->due) : '';
//
       if ($code == 'PO'){ $data['items'] = $this->Payment_trans_model->get_po_details($po)->result(); }
       else { $data['items'] = $this->Payment_trans_model->get_printing_details($po)->result(); }
       

       $terbilang = $this->load->library('terbilang');
       if ($appayment->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount+$appayment->over)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount+$appayment->over)); }

       
//
//       // property display
//       $data['paddress'] = $this->properti['address'];
//       $data['p_phone1'] = $this->properti['phone1'];
//       $data['p_phone2'] = $this->properti['phone2'];
//       $data['p_city'] = ucfirst($this->properti['city']);
//       $data['p_zip'] = $this->properti['zip'];
//       $data['p_npwp'] = $this->properti['npwp'];
       
       $data['accounting'] = $this->properti['accounting'];
       $data['manager'] = $this->properti['manager'];

       $this->load->view('appayment_invoice', $data); 
       
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
        $data['acc'] = $acc;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Appayment_model->report($vendor,$start,$end,$acc,$cur)->result();

        $total = $this->Appayment_model->total($vendor,$start,$end,$acc,$cur);
        $data['total'] = $total['amount'];
        
        if ($this->input->post('cformat') == 0){  $this->load->view('appayment_report', $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view('appayment_report', $data, TRUE));
        }

        

    }

//    ================================ REPORT =====================================
    
//    ================================ AJAX =====================================
    
    function get_po()
    {
       if ($this->input->post('po')) 
       {
          if( strpos($this->input->post('po'), ',' ) !== false ) { echo '0';}else{
            $purchase = $this->purchase->get_po($this->input->post('po'));
            echo intval($purchase->p2);  
          }
       }
       else { echo '0'; }
    }
    
    function get_printing()
    {
       if ($this->input->post('po')) 
       {
          $purchase = $this->printing->get_po($this->input->post('po'));
          echo intval($purchase->p2);
       }
       else { echo '0'; }
    }
    
    function payable()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/payable_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();
        $this->load->view('payable_report_panel', $data);
    }
    
    function payable_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cust = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
//        $duestart = $this->input->post('tduestart');
//        $dueend = $this->input->post('tdueend');
        $type = $this->input->post('ctype');
        $transtype = $this->input->post('ctrans');

        $data['currency'] = $cur;
        $data['start'] = tglin($start);
        $data['end'] = tglin($end);
//        $data['duestart'] = tglin($duestart);
//        $data['dueend'] = tglin($dueend);
        
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        
        // properti
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        

        $trans = new Trans_ledger_lib();
        $data['customer'] = $cust;
        $data['open'] = $trans->get_sum_transaction_open_balance_ap(null, $cur, $start, $this->vendor->get_vendor_id($cust), 'AP', $transtype);
        $data['trans'] = $trans->get_transaction_ap(null, $cur, $start, $end, $this->vendor->get_vendor_id($cust), 'AP', $transtype)->result();
        
        if ($type == 0){ $page = 'payable_card'; }elseif ($type == 1){ $page = 'payable_card_pivot'; }
        
        $this->load->view($page, $data);
    }
    

}

?>