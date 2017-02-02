<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ap_trans_model', '', TRUE);
        $this->load->model('Ap_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->vendor = new Vendor_lib();
        $this->user = new Admin_lib();
        $this->tax = $this->load->library('tax_lib');
        $this->product = $this->load->library('products_lib');
        $this->cost = new Cost_lib();
        $this->category = $this->load->library('categories_lib');
        $this->ps = new Period_lib();
        $this->model = new Apmodel();
        $this->bank = new Bank();
        $this->ledger = new Cash_ledger_lib();
        $this->account = new Account_lib();
        $this->journalgl = new Journalgl_lib();
        $this->demand = new Cash_demand_lib();
        $this->trans = new Trans_ledger_lib();
    }

    private $properti, $modul, $title, $cost, $bank,$ps, $model, $ledger, $account, $demand;
    private $vendor,$user,$tax,$journal,$journalgl,$product,$currency,$unit,$category,$trans;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');
    
     private  $attsupdate = array('width'=> '600','height'=> '300',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 600)/2)+\'',
                      'screeny'=> '0','class'=> 'edit','title'=> '', 'screeny' => '\'+((parseInt(screen.height) - 300)/2)+\'');
    
    function index()
    {
       $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();
        $data['year'] = null;
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $aps = $this->Ap_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Ap_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Type', 'Cur', 'Date', 'Vendor', 'Notes', 'Acc', 'Balance', '#', 'Action');

            $i = 0 + $offset;
            foreach ($aps as $ap)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'DJ-00'.$ap->no, $ap->type, $ap->currency, tglin($ap->dates), $this->vendor->get_vendor_name($ap->vendor), $ap->notes, ucfirst($ap->acc), number_format($ap->amount), $this->status($ap->status),
                    anchor($this->title.'/confirmation/'.$ap->id,'<span>update</span>',array('class' => $this->post_status($ap->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$ap->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$ap->id.'/'.$ap->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
        
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('cyear'));

        // Load absen view dengan melewatkan var $data sbgai parameter
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
        $arpData[0][2] = $this->Ap_model->total_chart(1,$year,$cur);
//
        $arpData[1][1] = 'February';
        $arpData[1][2] = $this->Ap_model->total_chart(2,$year,$cur);
//
        $arpData[2][1] = 'March';
        $arpData[2][2] = $this->Ap_model->total_chart(3,$year,$cur);
//
        $arpData[3][1] = 'April';
        $arpData[3][2] = $this->Ap_model->total_chart(4,$year,$cur);
//
        $arpData[4][1] = 'May';
        $arpData[4][2] = $this->Ap_model->total_chart(5,$year,$cur);
//
        $arpData[5][1] = 'June';
        $arpData[5][2] = $this->Ap_model->total_chart(6,$year,$cur);
//
        $arpData[6][1] = 'July';
        $arpData[6][2] = $this->Ap_model->total_chart(7,$year,$cur);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->Ap_model->total_chart(8,$year,$cur);
        
        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->Ap_model->total_chart(9,$year,$cur);
//        
        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->Ap_model->total_chart(10,$year,$cur);
//        
        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->Ap_model->total_chart(11,$year,$cur);
//        
        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->Ap_model->total_chart(12,$year,$cur);

        $strXML1 = $fusion->setDataXML($arpData,'','') ;
        $graph   = $fusion->renderChart($chart,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
        
    }
    
    private function get_search($no,$date,$type)
    {
        if ($no){ $this->model->where('no', $no); }
        elseif($date){ $this->model->where('dates', $date); }
        elseif($type){ $this->model->where('type', $type); }
        return $this->model->get();
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'ap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

        $aps = $this->get_search($this->input->post('tno'), $this->input->post('tdate'),  $this->input->post('ctype'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Type', 'Cur', 'Date', 'Vendor', 'Notes', 'Acc', 'Balance', '#', 'Action');

        $i = 0;
        foreach ($aps as $ap)
        {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'DJ-00'.$ap->no, $ap->type, $ap->currency, tglin($ap->dates), $this->vendor->get_vendor_name($ap->vendor), $ap->notes, ucfirst($ap->acc), number_format($ap->amount), $this->status($ap->status),
                anchor($this->title.'/confirmation/'.$ap->id,'<span>update</span>',array('class' => $this->post_status($ap->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$ap->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$ap->id.'/'.$ap->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('cyear'));
        $this->load->view('template', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'D'; break; case 1: $val = 'S'; break; } return $val; }
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
        $ap = $this->model->where('id',$pid)->get();

        if ($ap->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
//            $this->cek_journal($ap->dates,$ap->currency); // cek apakah journal sudah approved atau belum
            $total = $ap->amount;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $this->model->approved = 1;
                $this->model->status = 1;
                $this->model->save();
                $this->model->clear();
                
                $ap1 = $this->model->where('id',$pid)->get();
                
                // add cash ledger
                $this->ledger->add($ap1->acc, "DJ-00".$ap1->no, $ap1->currency, $ap1->dates, 0, $ap1->amount);
                
                // kurangi hutang
//                $this->trans->add($ap1->acc, 'DJ', $ap1->no, $ap1->currency, $ap1->dates, $ap1->amount, 0, $ap1->vendor, 'AP'); 
                
                //  create journal gl
                
                $cm = new Control_model();
                
                if ($ap1->post_dated == 1){ $account = $cm->get_id(48); }else { $account  = $ap1->account; }                
                
                // create journal- GL
                $this->journalgl->new_journal('0'.$ap1->no,$ap1->dates,'DJ',$ap1->currency,$ap1->notes,$ap1->amount, $this->session->userdata('log'));
                
                $transs = $this->Ap_trans_model->get_last_item($pid)->result(); 
                $dpid = $this->journalgl->get_journal_id('DJ','0'.$ap1->no);
                
                foreach ($transs as $trans) 
                {
//                    $this->cost->get_acc($trans->cost);
                    $this->journalgl->add_trans($dpid,$this->cost->get_acc($trans->cost),$trans->amount,0); // biaya
                }
                
                $this->journalgl->add_trans($dpid,$account,0,$ap1->amount); // kas, bank, kas kecil

               $this->session->set_flashdata('message', "$this->title DJ-00$ap1->no confirmed..!"); // set flash data message dengan session
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
        $ap = $this->model->where('id', $po)->get();

        if ( $ap->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - DJ-00$ap->no approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }

//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->model->where('id',$uid)->get();

        if ($val->approved == 1){ $this->void($uid,$po); }
        elseif ( $this->valid_period($val->dates) == TRUE ) // cek journal harian sudah di approve atau belum
        {
             // remove cash ledger
            $this->ledger->remove($val->dates, "DJ-00".$val->no);
            
            $this->Ap_trans_model->delete_po($uid); // model to delete item
            $this->model->delete(); // memanggil model untuk mendelete data

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            redirect($this->title);
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, invalid period..!");
           redirect($this->title);
        } 
    }
    
    private function void($uid,$po)
    {
       $val = $this->model->where('id',$uid)->get();
       if ($this->valid_period($val->dates) == TRUE)
       {
           $this->journalgl->remove_journal('DJ', '0'.$po); // journal gl
           
           // remove cash ledger
           $this->ledger->remove($val->dates, "DJ-00".$val->no);
           
           // remove kartu hutang
//           $this->trans->remove($val->dates, 'DJ', $val->no);
           
           $val->approved = 0;
           $val->status = 0;
           $val->post_dated_stts = 0;
           $val->save();
           $this->session->set_flashdata('message', "1 $this->title successfull voided..!");  
       }
       else { $this->session->set_flashdata('message', "Invalid Period..!");   }
       redirect($this->title);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Ap_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('ap_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'purchase_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Ap_model->counter();

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
        $this->form_validation->set_rules('tdemand', 'Cash-Demand', 'callback_valid_demand');
        $this->form_validation->set_rules('tno', 'DJ - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');
        $this->form_validation->set_rules('ctype', 'Type', 'required');
        $this->form_validation->set_rules('tvoucherno', 'Voucher No', 'required|numeric|callback_valid_voucher');

        if ($this->form_validation->run($this) == TRUE)
        {
            $trans = array('vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')), 'demand' => $this->input->post('tdemand'),
                           'type' => $this->input->post('ctype'), 'voucher_no' => $this->input->post('tvoucherno'),
                           'no' => $this->input->post('tno'), 'status' => 0, 'docno' => $this->input->post('tdocno'),
                           'dates' => $this->input->post('tdate'), 'acc' => $this->input->post('cacc'), 
                           'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 
                           'desc' => $this->input->post('tdesc'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                           'log' => $this->session->userdata('log'),
                           'check_no' => $this->input->post('tcheck'), 'bank' => $this->input->post('cbank'), 
                           'due' => $this->input->post('tdate')
                          );
            
            $this->Ap_model->add($trans);
            
            // add demand trans
            $this->add_demand_trans($this->input->post('tno'));
               
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('ap_form', $data);
//            echo validation_errors();
        }

    }
    
    private function add_demand_trans($no)
    {
        if ($no)
        {
          $ap = $this->model->where('no',$no)->get();
          $result = $this->demand->get_by_no($ap->demand);

          foreach ($result as $res)
          {
            $pitem = array('ap_id' => $ap->id, 'cost' => $res->cost,
                       'notes' => $res->notes,
                       'staff' => '',
                       'amount' => $res->amount);

            $this->Ap_trans_model->add($pitem);
            $this->update_trans($ap->id);
          }  
        }
    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $ap = $this->model->where('no', $po)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$ap->id);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$ap->id);
        $data['currency'] = $this->currency->combo();
        $data['code'] = $ap->no;
        $data['user'] = $this->session->userdata("username");
        
        
        if ($ap->acc == 'bank'){ $data['bank'] = $this->account->combo_asset(); }
        else { $data['bank'] = $this->account->combo_based_classi(7); }
        

        $data['default']['vendor'] = $this->vendor->get_vendor_shortname($ap->vendor);
        $data['default']['date'] = $ap->dates;
        $data['default']['currency'] = $ap->currency;
        $data['default']['acc'] = $ap->acc;
        $data['default']['note'] = $ap->notes;
        $data['default']['desc'] = $ap->desc;
        $data['default']['user'] = $this->user->get_username($ap->user);
        $data['default']['docno'] = $ap->docno;

        $data['default']['check'] = $ap->check_no; 
        $data['default']['check_type'] = $ap->check_type; 
        $data['default']['bank']  = $ap->account;
        $data['default']['due']   = $ap->due;
        
        $data['default']['post']   = $ap->post_dated;
        $data['default']['balance'] = $ap->amount;
        
        $data['default']['type'] = $ap->type;
        $data['default']['voucherno'] = $ap->voucher_no;

//        ============================ Apc Item  =========================================
        $items = $this->Ap_trans_model->get_last_item($ap->id)->result();
        
        $data['cost'] = $this->cost->combo();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Cost Type', 'Notes', 'Staff', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
              ++$i, $this->cost->get_name($item->cost), $item->notes, $item->staff, number_format($item->amount),
              anchor_popup($this->title.'/print_item/'.$item->id,'<span>print</span>',$this->atts).' '.
              anchor_popup($this->title.'/edit_item/'.$item->id,'<span>print</span>',$this->attsupdate).' '.
              anchor($this->title.'/delete_item/'.$item->id.'/'.$ap->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('apc_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================
    
    function edit_item($id)
    {
       $this->acl->otentikasi2($this->title); 
       $val = $this->Ap_trans_model->get_by_id($id);  
       $data['form_action_item'] = site_url($this->title.'/edit_item_process/'.$id.'/'.$val->ap_id); 
       
       $data['cost'] = $this->cost->combo();
       
       $data['default']['notes'] = $val->notes;
       $data['default']['staff'] = $val->staff;
       $data['default']['amount'] = $val->amount;       
       $data['default']['cost'] = $val->cost;
        
       $this->load->view('apc_update_item', $data); 
    }
    
    function edit_item_process($id,$apc)
    {
        $ap = $this->model->where('id', $apc)->get();
        
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($ap->no) == TRUE)
        {
            $pitem = array('notes' => $this->input->post('tnotes'), 
                           'cost' => $this->input->post('ccost'),
                           'staff' => $this->input->post('tstaff'),
                           'amount' => $this->input->post('tamount'));
            
            $this->Ap_trans_model->update($id,$pitem);
            $this->update_trans($apc);
        }
        
        redirect($this->title.'/edit_item/'.$id);
    }
    
    function add_item($pid=null)
    {
        $this->cek_confirmation($pid,'add_trans');
        
        $this->form_validation->set_rules('ccost', 'Cost Type', 'required');
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('ap_id' => $pid, 'cost' => $this->input->post('ccost'),
                           'notes' => $this->input->post('tnotes'),
                           'staff' => $this->input->post('tstaff'),
                           'amount' => $this->input->post('tamount'));
            
            $this->Ap_trans_model->add($pitem);
            $this->update_trans($pid);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($pid)
    {
        $totals = $this->Ap_trans_model->total($pid);
        
        $this->model->where('id', $pid)->get();
        $this->model->amount = $totals['amount'];
        $this->model->save();
    }

    function delete_item($id,$pid)
    {
        $this->cek_confirmation($pid,'add_trans');
        $this->acl->otentikasi2($this->title);
        $no = $this->model->where('id', $pid)->get();
        
        $this->Ap_trans_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($pid);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$no->no);
    }
    
    function print_item($id)
    {
//        $this->cek_confirmation($pid,'add_trans');
        $this->acl->otentikasi1($this->title);
        $terbilang = $this->load->library('terbilang');
        
        $value = $this->Ap_trans_model->get_by_id($id);
        $ap = $this->model->where('id', $value->ap_id)->get();
        
        $data['pono'] = $ap->no;
        $data['staff'] = $value->staff;
        $data['currency'] = $ap->currency;
        $data['notes'] = $value->notes;
        $data['cost'] = $value->cost;
        $data['amount'] = $value->amount;
        $data['user'] = $this->user->get_username($ap->user);
        
        if ($ap->currency == 'IDR')
        { $data['terbilang'] = ucwords($terbilang->baca($value->amount)).' Rupiah'; }
        else { $data['terbilang'] = ucwords($terbilang->baca($value->amount)); }
        
        if ($ap->acc == 'pettycash'){ $this->load->view('apc_receipt', $data); }
        else
        {
           if ($ap->approved == 1){ $this->load->view('apc_receipt', $data); }
           else { $this->load->view('rejected', $data); } 
        } 
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($pid=null)
    {
        $this->acl->otentikasi2($this->title);
//        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

	// Form validation
        
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
        $this->form_validation->set_rules('tno', 'DJ - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');
        $this->form_validation->set_rules('tcheck', 'Check No', '');
        $this->form_validation->set_rules('cbank', 'Bank', '');
        $this->form_validation->set_rules('tdue', 'Due Date', '');
        $this->form_validation->set_rules('ctype', 'Type', 'required');
        $this->form_validation->set_rules('tvoucherno', 'Voucher No', 'required|numeric|callback_validation_voucher['.$pid.']');

        if ($this->form_validation->run($this) == TRUE)
        { 
            $this->model->where('id',$pid)->get();
       
            // ledger
//            $this->ledger->add($this->input->post('cacc'), "DJ-00".$this->input->post('tno'), $this->model->currency, $this->input->post('tdate'), 0, $this->model->amount);
            
            $this->model->vendor   = $this->vendor->get_vendor_id($this->input->post('tvendor'));
            $this->model->no       = $this->input->post('tno');
            $this->model->type       = $this->input->post('ctype');
            $this->model->voucher_no = $this->input->post('tvoucherno');
            $this->model->status   = 0;
            $this->model->docno    = $this->input->post('tdocno');
            $this->model->dates    = $this->input->post('tdate');
            $this->model->acc      = $this->input->post('cacc');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->desc     = $this->input->post('tdesc');
            $this->model->user     = $this->user->get_userid($this->input->post('tuser'));
            $this->model->log      = $this->session->userdata('log');
            $this->model->check_no = $this->input->post('tcheck');
            $this->model->check_type = $this->input->post('ccheck_type');
            $this->model->account  = $this->input->post('cbank');
            $this->model->due      = setnull($this->input->post('tdue'));
            $this->model->post_dated = $this->input->post('cpost');
            
            
            $this->model->save();

            // tambah hutang
//            $ap1 = $this->model->where('id',$pid)->get();
//            $this->trans->add($ap1->acc, 'DJ', $ap1->no, $ap1->currency, $ap1->dates, 0, $ap1->amount, $ap1->vendor, 'AP'); 
            
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('purchase_transform', $data);
            echo validation_errors();
        }
    }
    
    function valid_check($val)
    {
        $acc = $this->input->post('cacc');

        if ($acc == 'bank')
        {
            if ($val == null) { $this->form_validation->set_message('valid_check', "Check No / Field Required..!"); return FALSE; }
            else { return TRUE; }
        }
        else { return TRUE; }
    }
    
    function valid_demand($val)
    {
        if ($val)
        {
            if ($this->model->where('demand',$val)->count() > 0) { $this->form_validation->set_message('valid_demand', "Check Demand Already Registered..!"); return FALSE; }
            else { return TRUE; }
        }
        else { return TRUE; }
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
    
    public function valid_vendor($name)
    {
        if ($this->vendor->valid_vendor($name) == FALSE)
        {
            $this->form_validation->set_message('valid_vendor', "Invalid Vendor.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

   public function valid_no($no)
   {
        if ($this->Ap_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
   }
   
   public function valid_voucher($no)
   {
        $type = $this->input->post('ctype');
        if ($this->Ap_model->valid_voucher($no,$type) == FALSE)
        {
            $this->form_validation->set_message('valid_voucher', "Voucher No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
   }
   
   public function validation_voucher($no,$id)
   {
        $type = $this->input->post('ctype');
        
        if ($this->Ap_model->validating_voucher($no,$type,$id) == FALSE)
        {
            $this->form_validation->set_message('validation_voucher', "Voucher No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
   }

    public function valid_confirmation($no)
    {
        $ap = $this->model->where('no', $no)->get();

        if ($ap->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Can't change value - Order approved..!.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_rate($rate)
    {
        if ($rate == 0)
        {
            $this->form_validation->set_message('valid_rate', "Rate can't 0..!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================
    

   function invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);
       $ap = $this->model->where('no', $po)->get();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $po;
       $data['podate'] = tgleng($ap->dates);
       $data['vendor'] = $this->vendor->get_vendor_name($ap->vendor);
       $data['venbank'] = $this->vendor->get_vendor_bank($ap->vendor);
       $data['notes'] = $ap->notes;
       $data['demand'] = $ap->demand;
       $data['acc'] = ucfirst($ap->acc);
       $data['user'] = $this->user->get_username($ap->user);
       $data['currency'] = $ap->currency;
       $data['docno'] = $ap->docno;
       $data['log'] = $this->session->userdata('log');
       
       $data['check'] = $ap->check_no; 
       $data['bank']  = $this->account->get_code($ap->account).' : '.$this->account->get_name($ap->account);
       $data['due']   = $ap->due;
       
       $data['type'] = $ap->type;
       $data['voucherno'] = $ap->voucher_no;

       $data['amount'] = $ap->amount;
       $terbilang = $this->load->library('terbilang');
       if ($ap->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)); }
       
       if($ap->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

       $data['items'] = $this->Ap_trans_model->get_last_item($ap->id)->result();
       
       $data['accounting'] = $this->properti['accounting'];
       $data['manager'] = $this->properti['manager'];

//       if ($ap->approved != 1){ $this->load->view('rejected', $data); }
//       else { $this->load->view('apc_invoice', $data); }
       $this->load->view('apc_invoice', $data);

   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['category'] = $this->category->combo_all();
        
        $this->load->view('apc_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
        $category = null;
        $acc      = $this->input->post('cacc');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['account'] = ucfirst($acc);
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $data['bank'] = $this->bank->combo_all();

//        Property Details
        $data['company'] = $this->properti['name'];

        if ($type == 0){ $data['aps'] = $this->Ap_model->report($vendor,$cur,$start,$end,$category,$acc)->result(); $page = 'apc_report'; }
        elseif ($type == 1){ $data['aps'] = $this->Ap_model->report($vendor,$cur,$start,$end,$category,$acc)->result(); $page = 'apc_report_details'; }
        elseif ($type == 2) { $data['aps'] = $this->Ap_model->report_category($vendor,$cur,$start,$end,$category,$acc)->result(); $page = 'apc_report_category'; }
        elseif ($type == 3) { $data['aps'] = $this->Ap_model->report_category($vendor,$cur,$start,$end,$category,$acc)->result(); $page = 'apc_pivot'; }
        
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
        
    }


// ====================================== REPORT =========================================
    
// ================ AJAX ====================
    
   function get_voucher_no()
   { echo $this->Ap_model->counter_voucher($this->input->post('ctype')); }

}

?>