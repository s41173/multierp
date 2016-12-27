<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Contract_model', '', TRUE);
        $this->load->model('Phase_model', 'pm', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib();
        $this->customer = new Customer_lib();
        $this->user = new Admin_lib();
        $this->sales = new Sales_lib();
        $this->nsales = new Nsales();
        $this->phase = new Phase_lib();
        $this->journalgl = new Journalgl_lib();
        
        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';
        
    }

    private $properti, $modul, $title, $phase, $journalgl;
    private $customer,$user,$currency,$sales,$nsales;
    
    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');
    
    private  $atts2 = array('width'=> '500','height'=> '400',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'details','title'=> 'void', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {  
        $this->get_last_contract();
    }

    function get_last_contract()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'contract_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $contracts = $this->Contract_model->get_last_contract($this->modul['limit'], $offset)->result();
        $num_rows = $this->Contract_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_contract');
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
            $this->table->set_heading('No', 'Type', 'Code', 'Cur', 'Doc-No', 'Customer', 'Deal', 'Start', 'End', 'Amount', 'Balance',  '#', 'Action');

            $i = 0 + $offset;
            foreach ($contracts as $contract)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $contract->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, $contract->type, 'CO-00'.$contract->no, $contract->currency, $contract->docno, $contract->prefix.' '.$contract->name, tglin($contract->deal_dates), tglin($contract->dates), tglin($contract->due), number_format($contract->tax+$contract->amount), number_format($contract->balance), $this->status($contract->status),
                    anchor($this->title.'/void/'.$contract->id.'/'.$contract->no.'/'.$contract->type,'<span>print</span>',$this->atts2).'&nbsp;'.
                    anchor($this->title.'/confirmation/'.$contract->id,'<span>update</span>',array('class' => $this->post_status($contract->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$contract->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$contract->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$contract->id.'/'.$contract->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
        
        $data['graph'] = $this->chart();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'contract_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('contract/','<span>back</span>', array('class' => 'back')));

        $contracts = $this->Contract_model->search($this->input->post('tno'), $this->input->post('tcust'), 
                                                   $this->input->post('tdate'), $this->input->post('tdue'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Type', 'Code', 'Cur', 'Doc-No', 'Customer', 'Deal', 'Start', 'End', 'Amount', 'Balance',  '#', 'Action');

        $i = 0;
        foreach ($contracts as $contract)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $contract->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, $contract->type, 'CO-00'.$contract->no, $contract->currency, $contract->docno, $contract->prefix.' '.$contract->name, tglin($contract->deal_dates), tglin($contract->dates), tglin($contract->due), number_format($contract->tax+$contract->amount), number_format($contract->balance), $this->status($contract->status),
                anchor($this->title.'/void/'.$contract->id.'/'.$contract->no.'/'.$contract->type,'<span>print</span>',$this->atts2).'&nbsp;'.
                anchor($this->title.'/confirmation/'.$contract->id,'<span>update</span>',array('class' => $this->post_status($contract->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$contract->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$contract->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$contract->id.'/'.$contract->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    public function chart()
    { 
        $ps = new Period();
        $ps->get();
//        
        if ($this->input->post('ctype')){ $type = $this->input->post('ctype'); }else { $type = '0'; }
        if ($this->input->post('tyear')){ $year = $this->input->post('tyear'); }else { $year = $ps->year; }
        
        
        
        $arpData[0][1] = 'January';
        $arpData[0][2] = $this->Contract_model->total(1,$year,$type);
//
        $arpData[1][1] = 'February';
        $arpData[1][2] = $this->Contract_model->total(2,$year,$type);
//
        $arpData[2][1] = 'March';
        $arpData[2][2] = $this->Contract_model->total(3,$year,$type);
//
        $arpData[3][1] = 'April';
        $arpData[3][2] = $this->Contract_model->total(4,$year,$type);
//
        $arpData[4][1] = 'May';
        $arpData[4][2] = $this->Contract_model->total(5,$year,$type);
//
        $arpData[5][1] = 'June';
        $arpData[5][2] = $this->Contract_model->total(6,$year,$type);
//
        $arpData[6][1] = 'July';
        $arpData[6][2] = $this->Contract_model->total(7,$year,$type);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->Contract_model->total(8,$year,$type);
        
        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->Contract_model->total(9,$year,$type);
//        
        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->Contract_model->total(10,$year,$type);
//        
        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->Contract_model->total(11,$year,$type);
//        
        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->Contract_model->total(12,$year,$type);

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Sales", "98%", 400, false, false) ;
        return $graph;
        
    }
    
    function get_list($type='tax')
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'contract_list';

        $customers = $this->Contract_model->get_contract_list($type)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Customer', 'Amount', 'Balance', 'Action');

            $i = 0;
            foreach ($customers as $res)
            {
               $data = array(
                                'name' => 'button',
                                'type' => 'button',
                                'content' => 'Select',
                                'onclick' => 'setvalue(\''.$res->no.'\',\'tcust\')'
			     );

                $this->table->add_row
                (
                    ++$i, 'CO-00'.$res->no, $res->prefix.' '.$res->name, number_format(intval($res->tax+$res->amount)), number_format($res->balance),
                    form_button($data)
                );
            }

            $data['table'] = $this->table->generate();
            $this->load->view('contract_list', $data);
    }
    
//    ===================== approval ===========================================

    private function status($val){ if ($val == 0){ return 'C'; }elseif($val == 2){return 'D';} else{ return 'S'; } }
    
    private function phase_status($val){ if ($val == 0){ return 'PENDING'; }elseif($val == 2){return 'PAID';} else{ return 'COLLECTABLE'; } }
    
    private function post_status($val)
    {
       if ($val == 1) {$class = "approve"; }
       else{$class = "notapprove"; }
       return $class;
    }

    function confirmation($pid)
    { 
      $this->acl->otentikasi_admin($this->title);  
      $this->create_journal($pid);
      $contract = array('approved' => 1);
      $this->Contract_model->update_id($pid, $contract);
      $this->session->set_flashdata('message', "1 $this->title approved..!");
      redirect($this->title); 
    }
    
    private function create_journal($pid)
    {
        $ap1 = $this->Contract_model->get_contract_by_id($pid)->row();
        //  create journal gl
                
        $cm = new Control_model();
        
        if ($ap1->type == 'tax'){ $ar = $cm->get_id(17); $sales = $cm->get_id(19); }else { $ar = $cm->get_id(55); $sales = $cm->get_id(56); }
        $tax   = $cm->get_id(18); // tax
        
        $this->journalgl->new_journal('0'.$ap1->no,$ap1->dates,'CO',$ap1->currency,$ap1->notes,$ap1->amount, $this->session->userdata('log'));
        $dpid = $this->journalgl->get_journal_id('CO','0'.$ap1->no);
        
        if ($ap1->tax > 0){ $this->journalgl->add_trans($dpid,$tax,0,$ap1->tax); } // hutang ppn
        $this->journalgl->add_trans($dpid,$sales,0,$ap1->amount); // penjualan
        $this->journalgl->add_trans($dpid,$ar,$ap1->balance,0); // piutang / kas
    }


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $contract = $this->Contract_model->get_contract_by_no($po)->row();
        
        if ($contract->approved == 1)
        { if ($this->valid_sales($po) == TRUE){ $this->rollback($uid, $po); }
          else { $this->session->set_flashdata('message', "Can't Rollback - Contract Sales Already Approved.!"); }
        }
        else { $this->remove($uid, $po); }
        
        redirect($this->title);
    }
    
    private function rollback($uid,$po)
    {
      $this->journalgl->remove_journal('CO', '0'.$po); // journal gl    
      $contract = array('approved' => 0);
      $this->Contract_model->update_id($uid, $contract); 
      $this->session->set_flashdata('message', "1 $this->title has been rollback..!");
    }
    
    private function remove($uid,$po)
    {
      $this->pm->delete_po($po);     
      $this->Contract_model->delete($uid);  
      $this->session->set_flashdata('message', "1 $this->title has been removed..!");
    }

    private function cek_confirmation($po,$page=null)
    {
       $val = $this->Contract_model->get_contract_by_no($po)->row();
       if ($val->status == 1)
       {
          $this->session->set_flashdata('message', "1 $this->title can't removed, contract approved..!");
          if ($page){ redirect($this->title.'/'.$page.'/'.$po.'/'); } else { redirect($this->title); }  
       }
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Contract_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('contract_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'contract_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Contract_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tcust', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'CO - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdocno', 'Document No', 'required|callback_valid_docno');
        $this->form_validation->set_rules('tdealdate', 'Deal Date', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tdue', 'Due Date', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $contract = array('customer' => $this->customer->get_customer_id($this->input->post('tcust')), 'no' => $this->input->post('tno'),
                              'docno' => $this->input->post('tdocno'),
                              'deal_dates' => $this->input->post('tdealdate'), 'dates' => $this->input->post('tdate'), 'due' => $this->input->post('tdue'),  'currency' => $this->input->post('ccurrency'),
                              'notes' => $this->input->post('tnote'), 'user' => $this->user->get_userid($this->session->userdata('username')),
                              'staff' => $this->input->post('tuser') ,'log' => $this->session->userdata('log'));

            $this->Contract_model->add($contract);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));

//            echo 'true';
        }
        else
        {
              $this->load->view('contract_form', $data);
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
        $data['currency'] = $this->currency->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");
        $data['phase'] = combo_number();

        $contract = $this->Contract_model->get_contract_by_no($po)->row();

        $data['default']['customer'] = $this->customer->get_customer_shortname($contract->customer);
        $data['default']['dealdate'] = $contract->deal_dates;
        $data['default']['docno'] = $contract->docno;
        $data['default']['date'] = $contract->dates;
        $data['default']['type'] = $contract->type;
        $data['default']['due'] = $contract->due;
        $data['default']['currency'] = $contract->currency;
        $data['default']['note'] = $contract->notes;
        $data['default']['user'] = $contract->staff;
        $data['default']['amount'] = $contract->amount;
        $data['default']['tax'] = $contract->tax;
        $data['default']['balance'] = $contract->balance;
        
        //        ============================ Phase Item  =========================================
        $items = $this->pm->get_last($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Phase', 'Date', 'Amount', 'Status', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->no, tglin($item->dates), number_format($item->amount), $this->phase_status($item->status),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$item->contract,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('contract_transform', $data);
    }

//    ======================  Item Transaction   ===============================================================

    function void($id,$no,$type)
    {
       $this->acl->otentikasi2($this->title); 
       $data['form_action_item'] = site_url($this->title.'/void_process/'.$id.'/'.$no.'/'.$type); 
       
       $val = $this->Contract_model->get_contract_by_id($id)->row();  
       
       $data['default']['customer'] = $this->customer->get_customer_name($val->customer);
       $data['default']['docno'] = $val->docno;
       $data['default']['no'] = $val->no;
       $data['default']['dates'] = tglin($val->deal_dates);
       $data['default']['type'] = $val->type;
       $data['default']['amount'] = $val->amount;
       $data['default']['tax'] = $val->tax;
       $data['default']['balance'] = $val->balance;       
       $data['default']['desc'] = $val->void_desc; 
       $data['default']['voiddate'] = $val->void_date; 
        
       $this->load->view('contract_void', $data);  
    }
    
    function void_process($id,$no,$type)
    {
       $this->acl->otentikasi2($this->title); 
       $data['form_action_item'] = site_url($this->title.'/void_process/'.$id.'/'.$no.'/'.$type); 
       
       if ($type == 'tax'){ $sales_tot = $this->sales->get_sum_sales_contract($no); }
       elseif ($type == 'non'){ $sales_tot = $this->nsales->get_sum_sales_contract($no); }
        
       $this->form_validation->set_rules('tamount', 'Contract Amount', 'required|numeric');
       $this->form_validation->set_rules('ttax', 'Contract Tax', 'required|numeric');
       $this->form_validation->set_rules('tdesc', 'Void Description', 'required');
       $this->form_validation->set_rules('tvoiddate', 'Void Date', 'required');
       $this->form_validation->set_rules('tdocno', 'Document No', 'required');
       
       if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($no) == FALSE)
       {
           $total = intval($this->input->post('tamount')+$this->input->post('ttax'));
           
           if ($total < $sales_tot){ $this->session->set_flashdata('message', "Invalid Total [ Contract Amount < Sales Total ]..!"); }
           else
           {
                        
             $contract = array('amount' => $this->input->post('tamount'), 'tax' => $this->input->post('ttax'), 'docno' => $this->input->post('tdocno'), 'approved' => 0,
                               'balance' => $total-$sales_tot, 'void' => 1, 'void_desc' => $this->input->post('tdesc'), 'void_date' => $this->input->post('tvoiddate'));
             $this->Contract_model->update($no, $contract); 
             $this->session->set_flashdata('message', "One Contract Successfull Updated..!");  
           }
       }
       else{ $this->session->set_flashdata('message', "Form Validation Error..!");  }
       
       redirect($this->title.'/void/'.$id.'/'.$no.'/'.$type); 
    }
    
    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('cpart', 'Part', 'required|numeric|callback_valid_part['.$po.']');
        $this->form_validation->set_rules('tpartdate', 'Part Date', 'required|callback_valid_date['.$po.']');
        $this->form_validation->set_rules('tpartamount', 'Amount', 'required|numeric|callback_valid_amount['.$po.']');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('no' => $this->input->post('cpart'), 'contract' => $po, 'dates' => $this->input->post('tpartdate'),
                           'amount' => $this->input->post('tpartamount'), 'status' => 0);
            $this->pm->add($pitem);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->pm->delete($id); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 phase successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }
//    ==========================================================================================

   function invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);
       $ap = $this->Contract_model->get_contract_by_id($po)->row();
       
       // properti
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $data['company'] = $this->properti['name']; 

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $ap->no;
       $data['podate'] = tglin($ap->dates);
       $data['dealdate'] = tglin($ap->deal_dates);
       $data['due'] = tglin($ap->due);
       $data['docno'] = $ap->docno;
       $data['customer'] = $this->customer->get_customer_name($ap->customer);
       $data['type'] = strtoupper($ap->type);
       $data['notes'] = $ap->notes;
       $data['user'] = $this->user->get_username($ap->user);
       $data['currency'] = $ap->currency;
       $data['log'] = $this->session->userdata('log');

       $data['tax'] = $ap->tax;
       $data['amount'] = $ap->amount;
       $data['balance'] = $ap->balance;
       
       // void
       if ($ap->void_desc == ""){ $data['void_date'] = null; }else { $data['void_date'] = $ap->void_date; }
       $data['void_desc'] = $ap->void_desc;
       
       if($ap->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

       $data['items'] = $this->sales->get_sales_contract($ap->no);
       
       // item phase
       $data['items1'] = $this->phase->get_last($ap->no)->result();
       
       $this->load->view('contract_invoice', $data);

   }
    
    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('contract/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcust', 'Customer', 'required');
        $this->form_validation->set_rules('tno', 'CO - No', 'required|numeric|callback_valid_contract');
        $this->form_validation->set_rules('tdocno', 'Document No', 'required');
        $this->form_validation->set_rules('tdealdate', 'Deal Date', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tdue', 'Due Date', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');

        if ($this->form_validation->run($this) == TRUE && $this->valid_void($po) == TRUE)
        {
            $contract = array('customer' => $this->customer->get_customer_id($this->input->post('tcust')),
                              'type' => $this->input->post('ctype'), 'docno' => $this->input->post('tdocno'),
                              'deal_dates' => $this->input->post('tdealdate'), 'dates' => $this->input->post('tdate'), 'due' => $this->input->post('tdue'),  'currency' => $this->input->post('ccurrency'),
                              'amount' => $this->input->post('tamount'), 'tax' => $this->input->post('ttax'), 'balance' => $this->input->post('tbalance'),
                              'notes' => $this->input->post('tnote'), 'user' => $this->user->get_userid($this->session->userdata('username')),
                              'staff' => $this->input->post('tuser'), 'log' => $this->session->userdata('log'));

            $this->Contract_model->update($po, $contract);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('contract_transform', $data);
            echo validation_errors();
        }
    }
    
    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('contract_report_panel', $data);
    }
    
    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $duestart = $this->input->post('tduestart');
        $dueend = $this->input->post('tdueend');
        $type = $this->input->post('ctype');

        $data['currency'] = $cur;
        $data['start'] = tglin($start);
        $data['end'] = tglin($end);
        $data['duestart'] = tglin($duestart);
        $data['dueend'] = tglin($dueend);
        
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['contract'] = $this->Contract_model->report($vendor,$cur,$start,$end,$duestart,$dueend)->result();
        
        if ($type == 0){ $page = 'contract_report'; }elseif ($type == 1){ $page = 'contract_pivot'; }
        
        $this->load->view($page, $data);
    }
    
    public function valid_confirmation($no)
    {
        $val = $this->Contract_model->get_contract_by_no($no)->row();

        if ($val->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Can't change value - Order approved..!.!");
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

    public function valid_amount($amount,$co)
    {
        $val = $this->Contract_model->get_contract_by_no($co)->row();
        $total = intval($val->amount + $val->tax);
        
        $phase = $this->pm->total($co);
        
        if (intval($phase+$amount) > $total)
        {
            $this->form_validation->set_message('valid_amount', "Invalid Phase Amount...!!.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_part($part,$co)
    {
        $val = $this->pm->valid_part($part,$co);
        
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_part', "Invalid Phase Part...!!.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_date($date,$co)
    {
        $val = $this->Contract_model->get_contract_by_no($co)->row();
        
        if ($date < $val->deal_dates)
        {
            $this->form_validation->set_message('valid_date', "Invalid Phase Date...!!.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_contract($val)
    {
        $val = $this->Contract_model->get_contract_by_no($val)->row();
        if ($val->approved == 1)
        {
            $this->form_validation->set_message('valid_contract', "Contract Approved.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_sales($contract)
    {
        $val = $this->sales->valid_sales_contract($contract);
        if ($val == TRUE)
        {
            $this->form_validation->set_message('valid_sales', "Contract Sales Already Approved.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_void($no)
    {
        $val = $this->Contract_model->get_contract_by_no($no)->row();
        if ($val->void == 1)
        {
            $this->form_validation->set_message('valid_void', "Order already voided..!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_no($no)
    {
        if ($this->Contract_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_docno($no)
    {
        if ($this->Contract_model->valid_docno($no) == FALSE)
        {
            $this->form_validation->set_message('valid_docno', "Order - Document No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    // ========================== RECEIVABLE CARD ===================================
    
    function receivable()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/receivable_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();
        $this->load->view('receivable_report_panel', $data);
    }
    
    function receivable_process()
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
        $trans = $this->input->post('ctrans');

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
        $data['open'] = $trans->get_sum_transaction_open_balance(null, $cur, $start, $this->customer->get_customer_id($cust), 'AR', $trans);
        $data['trans'] = $trans->get_transaction(null, $cur, $start, $end, $this->customer->get_customer_id($cust), 'AR', $trans)->result();
        
        if ($type == 0){ $page = 'contract_card'; }elseif ($type == 1){ $page = 'contract_card_pivot'; }
        
        $this->load->view($page, $data);
    }


}

?>