<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nsales extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Nsales_model', '', TRUE);
        $this->load->model('Nsales_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib(); 
        $this->customer = new Customer_lib();
        $this->user     = new Admin_lib();
        $this->tax      = new Tax_lib();
        $this->journal  = new Journal_lib();
        $this->journalgl = new Journalgl_lib();
        $this->nar      = new Nar_payment();
        $this->contract = new Contract_lib();
        $this->trans    = new Trans_ledger_lib();
    }

    private $properti, $modul, $title, $currency, $trans, $journalgl;
    private $customer,$user,$tax,$journal,$nar,$contract;

    function index()
    { $this->get_last_nsales(); }

    function get_last_nsales()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'nsales_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $saless = $this->Nsales_model->get_last_nsales($this->modul['limit'], $offset)->result();
        $num_rows = $this->Nsales_model->count_all_num_rows();

        $atts = array('width'=> '450','height'=> '300',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_nsales');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Total', 'Balance', '#', 'Action');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'NSO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total + $sales->costs), number_format($sales->p2), $this->status($sales->status),
                    anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$sales->id,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/add_trans/'.$sales->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'nsales_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $atts = array('width'=> '400','height'=> '220',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        $saless = $this->Nsales_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
         $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Total', 'Balance', '#', 'Action');

         $i = 0;
         foreach ($saless as $sales)
         {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'NSO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total + $sales->costs), number_format($sales->p2), $this->status($sales->status),
                anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$sales->id,'<span>print</span>',$atts).' '.
                anchor($this->title.'/add_trans/'.$sales->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
         }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function cek_space($val)
    {  $res = explode("<br />",$val);  if (count($res) == 1) { return $val;  } else { return implode('', $res); } }

    function get_list($currency=null,$customer=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];

        $saless = $this->Nsales_model->get_nsales_list($currency,$customer)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Total', 'Balance', 'Action');

        $i = 0;
        foreach ($saless as $sales)
        {
           $data = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$sales->id.'\',\'titem\')'
                         );

            $this->table->add_row
            (
                ++$i, 'NSO-00'.$sales->no, tgleng($sales->dates), $sales->notes, number_format($sales->total), number_format($sales->p2),
                form_button($data)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('nsales_list', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'C'; break; case 1: $val = 'S'; break; } return $val; }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class;
    }

    function confirmation($pid)
    {
        $this->acl->otentikasi_admin($this->title);
        $sales = $this->Nsales_model->get_nsales_by_id($pid)->row();

        if ($sales->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        elseif ($this->contract->cek_contract_amount($sales->contract_no,intval($sales->p2-$sales->costs)) == FALSE)
        {
           $this->session->set_flashdata('message', "invalid sales amount greater than contract..!"); // set flash data message dengan session 
        }
        else
        {
            $this->cek_journal($sales->dates,$sales->currency); // cek apakah journal sudah approved atau belum
            $total = $sales->total;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                // update contract balance 
                $this->contract->update_balance($sales->contract_no, $sales->total, 0);
                
                // update buku piutang
                $this->trans->add('bank', 'NSO', $sales->no, $sales->currency, $sales->dates, $sales->p2, 0, $sales->customer, 'AR');
                
                $data = array('approved' => 1);
                $this->Nsales_model->update_id($pid, $data);

                //  create journal
                $this->create_journal($pid);

               $this->session->set_flashdata('message', "$this->title NSO-00$sales->no confirmed..!"); // set flash data message dengan session
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
        $sales = $this->Nsales_model->get_nsales_by_id($po)->row();

        if ( $sales->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - NSO-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    private function create_journal($sid)
    {
        $this->db->trans_start();
        
        $ap1 = $this->Nsales_model->get_nsales_by_id($sid)->row();
        //  create journal gl
                
        $cm = new Control_model();
        
        $ar = $cm->get_id(55); // piutang dagang non
        $tax   = $cm->get_id(18); // tax
        $ar_contract = $cm->get_id(57); // piutang kontrak non tax
        $cost = $cm->get_id(58); // pendapatan lain-lain (materai, etc)
        
        $year = date('y', strtotime($ap1->dates));
        $this->journalgl->new_journal('0'.$ap1->no.$year,$ap1->dates,'NSO',$ap1->currency,$ap1->notes,$ap1->total, $this->session->userdata('log'));
        $dpid = $this->journalgl->get_journal_id('NSO','0'.$ap1->no.$year);
        
        $this->journalgl->add_trans($dpid,$ar,$ap1->p2,0); // piutang dagang ppn
        $this->journalgl->add_trans($dpid,$ar_contract,0,intval($ap1->total-$ap1->tax)); // piutang kontrak tax
        if ($ap1->tax > 0){ $this->journalgl->add_trans($dpid,$tax,0,$ap1->tax); } // hutang ppn
        if ($ap1->costs > 0){ $this->journalgl->add_trans($dpid,$cost,0,$ap1->costs); } // pendapatan materai
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){ return FALSE; }else { return TRUE; }
    }


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        
        $sales = $this->Nsales_model->get_nsales_by_id($uid)->row();
        $year  = date('Y', strtotime($sales->dates));

//        if ( $this->nar->cek_relation($po,'no') == TRUE )
//        {
            if ($sales->approved == 1){ $this->rollback($uid, $po); }else{ $this->remove($uid, $po,$year); }
//        }
//        else{ $this->session->set_flashdata('message', "This $this->title related to another component..!"); }
        redirect($this->title);
    }
    
    private function rollback($uid,$po)
    {
      // upgrade contract balance
      $sales = $this->Nsales_model->get_nsales_by_id($uid)->row();
      $this->contract->update_balance($sales->contract_no, $sales->total, 1);    
        
      // rollback kartu piutang
      $this->trans->remove($sales->dates, 'NSO', $sales->no);
      
       // hapus jurnal
      $year = date('y', strtotime($sales->dates));
      $this->journalgl->remove_journal('NSO', '0'.$sales->no.$year); // journal gl  
      
      $data = array('approved' => 0);
      $this->Nsales_model->update_id($uid, $data);
      $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");
    }
    
    private function remove($uid,$po,$year)
    {
      $this->Nsales_item_model->delete_po($po,$year); // model to delete sales item
      $this->Nsales_model->delete($uid); // memanggil model untuk mendelete data  
      $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Nsales_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('nsales_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Nsales_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'NSO - No', 'required|numeric|callback_valid_no['.$this->input->post('tdate').']');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_date');
        $this->form_validation->set_rules('tcontract', 'Order Contract', 'required|callback_valid_contract');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tshipping', 'Shipping', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales = array('customer' => $this->contract->get_contract_customer($this->input->post('tcontract')), 
                           'contract' => 1, 'contract_no' => $this->input->post('tcontract'), 'no' => $this->input->post('tno'), 
                           'status' => 0, 'docno' => $this->input->post('tdocno'),
                           'dates' => $this->input->post('tdate'), 'discount_desc' => $this->input->post('tdisdesc'),  
                           'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 
                           'desc' => $this->input->post('tdesc'), 'shipping_date' => $this->input->post('tshipping'), 
                           'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Nsales_model->add($sales);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->Nsales_model->counter_id());
//            echo 'true';
        }
        else
        {
              $this->load->view('nsales_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($pid=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $sales = $this->Nsales_model->get_nsales_by_id($pid)->row();
        $year  = date('Y', strtotime($sales->dates));

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];        
        $data['form_action'] = site_url($this->title.'/update_process/'.$pid);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$pid);
        $data['currency'] = $this->currency->combo();
        $data['tax'] = $this->tax->combo();
        $data['code'] = $sales->no;
        $data['user'] = $this->session->userdata("username");


        $data['default']['contract'] = $sales->contract_no;
        $data['default']['customer'] = $sales->name;
        $data['default']['date'] = $sales->dates;
        $data['default']['currency'] = $sales->currency;
        $data['default']['note'] = $sales->notes;
        $data['default']['desc'] = $sales->desc;
        $data['default']['shipping'] = $sales->shipping_date;
        $data['default']['user'] = $this->user->get_username($sales->user);
        $data['default']['docno'] = $sales->docno;

        $data['default']['tax'] = $sales->tax;
        $data['default']['discount'] = $sales->discount;
        $data['default']['totaltax'] = $sales->total;
        $data['default']['p1'] = $sales->p1;
        $data['default']['costs'] = $sales->costs;
        $data['default']['balance'] = $sales->p2;

        $data['default']['disdesc'] = $sales->discount_desc;

//        ============================ Nsales Item  =========================================
        $items = $this->Nsales_item_model->get_last_item($sales->no,$year)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Size', 'Coloumn', 'Unit price', 'Discount', 'Tax', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->size.'<sup>'.$item->sup.'</sup>', $item->coloumn, number_format($item->price), number_format($item->discount_amount), number_format($item->tax), number_format($item->amount),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$sales->no.'/'.$year,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('nsales_transform', $data);
    }
    
//    ======================  Item Transaction   ===============================================================

    function add_item($pid)
    {
        $sales = $this->Nsales_model->get_nsales_by_id($pid)->row();
        $this->cek_confirmation($pid,'add_trans');
        $year  = date('Y', strtotime($sales->dates));
        
        $this->form_validation->set_rules('ctype', 'Type', 'required');
        $this->form_validation->set_rules('tsup', 'Sup', '');
        $this->form_validation->set_rules('tsize', 'Size', 'required|numeric');
        $this->form_validation->set_rules('tcoloumn', 'Coloumn', 'required|numeric');
        $this->form_validation->set_rules('tamount', 'Unit Price', 'required');
        $this->form_validation->set_rules('tdiscount', 'Discount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            if ($this->input->post('cdisctype') == 0){ $percentage = $this->input->post('tdiscount'); $discount = $this->input->post('tdiscount'); }else{ $percentage = intval($this->input->post('tdiscountnominal')/$this->input->post('tamount')*100); $discount = $this->input->post('tdiscountnominal'); }
            $res = $this->total($this->input->post('tsize'),$this->input->post('tcoloumn'),$this->input->post('tamount'),$discount, $this->input->post('ctax'),$this->input->post('cdisctype'));

            $pitem = array('nsales_id' => $sales->no, 'year' => $year, 'type' => $this->input->post('ctype'), 'size' => $this->input->post('tsize'), 'sup' => $this->input->post('tsup'), 'coloumn' => $this->input->post('tcoloumn'),
                           'price' => $this->input->post('tamount'), 'discount' => $percentage, 'discount_amount' => $res['discount'],
                           'tax' => $res['tax'], 'amount' => $res['amount']);
                           
            $this->Nsales_item_model->add($pitem);
            $this->update_trans($sales->no,$year);
            
            echo 'true';
//                
//            $sales = $this->Nsales_model->get_nsales_by_no($po)->row();
//            if ($this->contract->cek_contract_amount($sales->contract_no,$res['amount']) == TRUE){
//                
//                $this->Nsales_item_model->add($pitem);
//                $this->update_trans($po,$year);
//                echo 'true';
//            }else{ echo 'invalid sales amount greater than contract..!'; }
        }
        else{   echo validation_errors(); }
    }

    private function total($size,$coloumn,$price,$discount,$tax=0,$type=0)
    {
        $res = $size * $coloumn * $price;
        if ($type == 0){ $discount = $this->calculate_discount($res,$discount); }
        $netto = $res - $discount;
        $tax = ceil($this->tax->calculate_tax($netto,$tax));
        $amount = $netto + $tax;

        $val = array('bruto' => $res, 'discount' => $discount, 'netto' => $netto, 'tax' => $tax, 'amount' => $amount);
        return $val;
    }

    private function calculate_discount($amount,$discount)
    {
        $discount = $discount / 100;
        $discount = $amount * $discount;
        return $discount;
    }

    private function update_trans($po,$year)
    {
        $totals = $this->Nsales_item_model->total($po,$year);
        $sales = array('tax' => $totals['tax'], 'total' => $totals['amount'], 'discount' => $totals['discount_amount'], 'p2' => $totals['amount']);

        $pid = $this->Nsales_model->get_nsales_by_no($po,$year)->row();
	$this->Nsales_model->update_id($pid->id, $sales);
    }

    function delete_item($id,$po,$year)
    {
        $pid = $this->Nsales_model->get_nsales_by_no($po,$year)->row();
        $this->cek_confirmation($pid->id,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Nsales_item_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po,$year);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$pid->id);
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
	$data['link'] = array('link_back' => anchor('sales/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'NSO - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdisdesc', 'Discount Description', '');
        $this->form_validation->set_rules('tshipping', 'Shipping', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tcosts', 'Landed Cost', 'required|numeric');
        $this->form_validation->set_rules('tp1', 'Down Payment', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $saless = $this->Nsales_model->get_nsales_by_id($pid)->row();

            $sales = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')), 
                           'log' => $this->session->userdata('log'), 'docno' => $this->input->post('tdocno'),
                           'dates' => $this->input->post('tdate'),  'currency' => $this->input->post('ccurrency'), 
                           'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                           'shipping_date' => $this->input->post('tshipping'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                           'costs' => $this->input->post('tcosts'), 'p1' => $this->input->post('tp1'), 
                           'discount_desc' => $this->input->post('tdisdesc'), 'p2' => $this->calculate_balance($this->input->post('tcosts'),$saless->total,$this->input->post('tp1')),
                           'status' => $this->get_status($this->calculate_balance($this->input->post('tcosts'),$saless->total,$this->input->post('tp1')))
                             );

            $this->Nsales_model->update_id($pid, $sales);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('sales_transform', $data);
            echo validation_errors();
        }
    }

    private function calculate_balance($cost,$total,$p1)
    {
        $res = $cost + $total;
        $res = $res - $p1;
        return $res;
    }

    private function get_status($p2=null)
    { if ($p2 == 0){ return 1; } else { return 0; } }

    public function valid_contract($contract)
    {
        if ($this->contract->cek_approval_contract($contract) == FALSE)
        {
            $this->form_validation->set_message('valid_contract', "Invalid Contract Order.!");
            return FALSE;
        }
        else{ return TRUE;}
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

    public function valid_no($no,$date)
    {
        $year  = date('Y', strtotime($date));
        if ($this->Nsales_model->valid_no($no,$year) == FALSE)
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
    
   function invoice($pid=null)
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

        $data['pono'] = $pid;
        $this->load->view('nsales_invoice_form', $data);
   }

   function print_invoice($pid=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];

       $sales = $this->Nsales_model->get_sales_by_id($pid)->row();
       $year  = date('Y', strtotime($sales->dates));
       $salesitem = $this->Nsales_item_model->get_last_item($sales->no,$year)->row();
       
       $data['pono'] = '0'.$sales->no.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / P / '.date("Y",strtotime($sales->dates)).' / '.$sales->contract_no;
       $data['podate'] = tglincomplete($sales->dates);
       $data['customer'] = strtoupper($sales->prefix.' '.$sales->name);
       $data['desc'] = $sales->desc;
       $data['notes'] = $sales->notes;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);

       if ($sales->currency == "IDR"){ $data['symbol'] = 'Rp.'; $matauang = 'rupiah'; }else { $data['symbol'] = ''; $matauang = ''; }

       $data['cost'] = $sales->costs;
       $data['p2'] = $sales->p2;
       $data['p1'] = $sales->p1;
       $data['discount'] = $sales->discount;
       $data['discountpercent'] = $salesitem->discount;
       $data['tax'] = $sales->tax;
       $data['bruto'] = $salesitem->size * $salesitem->coloumn * $salesitem->price;
       $data['total'] = $sales->total + $sales->costs-$sales->p1;
       $data['netto'] = $salesitem->size * $salesitem->coloumn * $salesitem->price - $sales->discount;
       
       $data['disdesc'] = $sales->discount_desc;
       $data['sup'] = $salesitem->sup;

       // -------------------------------
       $data['size'] = $salesitem->size;
       $data['coloumn'] = $salesitem->coloumn;
       $data['price'] = $salesitem->price;

       if ($salesitem->type == 0) { $data['size'] = ''; $data['coloumn'] = ''; $data['price'] = ''; }
       else { $data['size'] = $salesitem->size; $data['coloumn'] = $salesitem->coloumn; $data['price'] = number_format($salesitem->price,0,",","."); }
       
       $terbilang = $this->load->library('terbilang');
       $data['terbilang'] = ucwords($terbilang->baca($sales->total+$sales->costs-$sales->p1).' '.$matauang);

       if ($type){ $this->load->view('nsales_invoice_blank', $data); } else { $this->load->view('nsales_invoice', $data); }
       
   }

   function print_nontax_invoice($pid=null,$page=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Non Tax Invoice'.$this->modul['title'];

       $sales = $this->Nsales_model->get_nsales_by_id($pid)->row();

       $data['pono'] = '0'.$sales->no.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / N / '.date("Y",strtotime($sales->dates)).' / '.$sales->contract_no;
       $data['podate'] = tgleng($sales->dates);
       $data['customer'] = strtoupper($sales->prefix.' '.$sales->name);
       $data['desc'] = $sales->desc;
       $data['notes'] = $sales->notes;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);

       if ($sales->currency == "IDR"){ $data['symbol'] = 'Rp.'; $matauang = 'rupiah'; } else { $data['symbol'] = ''; $matauang = ''; }

       $data['cost'] = $sales->costs;
       $data['p2'] = $sales->p2;
       $data['p1'] = $sales->p1;
       $data['total'] = $sales->total - $sales->tax + $sales->costs-$sales->p1;

       $terbilang = $this->load->library('terbilang');
       $data['terbilang'] = ucwords($terbilang->baca($sales->total - $sales->tax +$sales->costs-$sales->p1).' '.$matauang);

       if ($page){ $this->load->view('nsalesnontax_blank', $data); } else { $this->load->view('nsalesnontax', $data); }
   }

   private function get_romawi($val)
   {
       switch ($val)
       {
           case '01': $val = 'I'; break;
           case '02': $val = 'II'; break;
           case '03': $val = 'III'; break;
           case '04': $val = 'IV'; break;
           case '05': $val = 'V'; break;
           case '06': $val = 'VI'; break;
           case '07': $val = 'VII'; break;
           case '08': $val = 'VIII'; break;
           case '09': $val = 'IX'; break;
           case '10': $val = 'X'; break;
           case '11': $val = 'XI'; break;
           case '12': $val = 'XII'; break;
       }
       return $val;
   }

   function print_expediter($pid=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Expediter'.$this->modul['title'];

       $sales = $this->Nsales_model->get_nsales_by_no($po)->row();

       $data['pono'] = '0'.$sales->no.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / N / '.date("Y",strtotime($sales->dates));

       $data['podate'] = tgleng($sales->dates);
       $data['customer'] = $sales->prefix.' '.$sales->name;
       $data['address'] = $sales->address;
       $data['shipdate'] = tgleng($sales->shipping_date);
       $data['city'] = $sales->city;
       $data['phone'] = $sales->phone1;
       $data['phone2'] = $sales->phone2;
       $data['desc'] = $sales->desc;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);
       $data['docno'] = $sales->docno;
       $data['notes'] = $sales->notes;

       $data['cost'] = $sales->costs;
       $data['p2'] = $sales->p2;
       $data['p1'] = $sales->p1;

       $data['items'] = $this->Nsales_item_model->get_last_item($po)->result();

       // property display
       $data['p_name'] = $this->properti['name'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

//       customer
       $customer = $this->customer->get_customer_details($sales->customer);
       $data['c_name']    = strtoupper($customer->prefix.' '.$customer->name);
       $data['c_address'] = strtoupper($customer->address);
       $data['c_city']    = strtoupper($customer->city);
       $data['c_zip']     = strtoupper($customer->zip);
       $data['c_npwp']    = strtoupper($customer->npwp);

       $this->load->view('nsales_expediter', $data);
   }

   function tax_invoice($pid=null,$page=1)
   {
      $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax'.$this->modul['title'];

       $sales = $this->Nsales_model->get_nsales_by_no($po)->row();
       $salesitem = $this->Nsales_item_model->get_last_item($po)->row();
       
       $sales = $this->Nsales_model->get_sales_by_id($pid)->row();
       $year  = date('Y', strtotime($sales->dates));
       $salesitem = $this->Nsales_item_model->get_last_item($sales->no,$year)->row();

       $data['pono'] = '0'.$po.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / N / '.date("Y",strtotime($sales->dates));
       $data['podate'] = tgleng($sales->dates);
       $data['customer'] = strtoupper($sales->prefix.' '.$sales->name);
       $data['desc'] = $sales->desc;
       $data['notes'] = $sales->notes;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);

       $data['cost'] = $sales->costs;
       $data['p2'] = $sales->p2;
       $data['p1'] = $sales->p1;
       $data['discount'] = $sales->discount;
       $data['discountpercent'] = $salesitem->discount;
       $data['tax'] = $sales->tax;
       $data['bruto'] = $salesitem->size * $salesitem->coloumn * $salesitem->price;
       $data['total'] = $sales->total + $sales->costs;
       $data['netto'] = $salesitem->size * $salesitem->coloumn * $salesitem->price - $sales->discount;

//       properti
       $data['name'] = strtoupper($this->properti['name']);
       $data['address'] = strtoupper($this->properti['address']);
       $data['city'] = strtoupper($this->properti['city']);
       $data['zip'] = strtoupper($this->properti['zip']);
       $data['npwp'] = strtoupper($this->properti['npwp']);
       $data['cp'] = strtoupper($this->properti['cp']);
       
//       customer
       $customer = $this->customer->get_customer_details($sales->customer);
       $data['c_name']    = strtoupper($customer->prefix.' '.$customer->name);
       $data['c_address'] = strtoupper($customer->address);
       $data['c_city']    = strtoupper($customer->city);
       $data['c_zip']     = strtoupper($customer->zip);
       $data['c_npwp']    = strtoupper($customer->npwp);

//       product details
       $data['p_name'] = $sales->notes;

       switch ($page) { case 1: $page = 'fakturpajak'; break; case 2: $page = 'fakturpajak2'; break; case 3: $page = 'fakturpajak3'; break; }
       $this->load->view($page, $data);
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
        
        $this->load->view('nsales_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $customer = $this->input->post('tcust');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
        $status = $this->input->post('cstatus');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['saless'] = $this->Nsales_model->report($customer,$cur,$start,$end,$status)->result();
        $total = $this->Nsales_model->total($customer,$cur,$start,$end,$status);
        
        $data['total'] = $total['total'] - $total['tax'] + $total['discount'];
        $data['tax'] = $total['tax'];
        $data['discount'] = $total['discount'];
        $data['p1'] = $total['p1'];
        $data['p2'] = $total['p2'];
        $data['costs'] = $total['costs'];
        $data['ptotal'] = $total['total'] + $total['costs'];


        if ($type == 'detail') { $this->load->view('nsales_report_details', $data); }
        elseif ($type == 'sum') {  $this->load->view('nsales_report', $data); }
        elseif ($type == 'pivot') {  $this->load->view('nsales_pivot', $data); }
        
    }


// ====================================== REPORT =========================================

}

?>