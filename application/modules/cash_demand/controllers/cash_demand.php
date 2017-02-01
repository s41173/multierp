<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cash_demand extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Cash_demand_trans_model', 'transmodel', TRUE);
        $this->load->model('Cash_demand_model', 'demandmodel', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->vendor = new Vendor_lib();
        $this->user = new Admin_lib();
        $this->tax = $this->load->library('tax_lib');
        $this->journalgl = new Journalgl_lib();
        $this->product = $this->load->library('products_lib');
        $this->cost = $this->load->library('cost_lib');
        $this->category = $this->load->library('categories_lib');
        $this->account = $this->load->library('account_lib');        
//        $this->financial = new Financial_lib();
        
        $this->model = new Cash();
    }

    private $properti, $modul, $title, $cost, $account, $financial;
    private $vendor,$user,$tax,$journalgl,$product,$currency,$unit,$model,$category;

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
        $data['main_view'] = 'cash_demand_view';
        $data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();
//        $data['year'] = $this->financial->combo_active();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $aps = $this->model->order_by('dates','desc')->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

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
            $this->table->set_heading('No', 'Code', 'Vendor', 'Cur', 'Date', 'Notes', 'Acc', 'Balance', 'Action');

            $i = 0 + $offset;
            foreach ($aps as $ap)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'RC-00'.$ap->no, $this->vendor->get_vendor_name($ap->vendor),  $ap->currency, tglin($ap->dates), $ap->notes, ucfirst($ap->acc), number_format($ap->amount),
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

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    private function get_search($no,$date,$vendor=null)
    {
        if ($no){ $this->model->where('no', $no); }
        elseif($date){ $this->model->where('dates', $date); }
        elseif($vendor){ $this->model->where('vendor', $this->vendor->get_vendor_id($vendor)); }
        return $this->model->get();
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'cash_demand_view';
	    $data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();
//        $data['year'] = $this->financial->combo_active();

        $aps = $this->get_search($this->input->post('tno'), $this->input->post('tdate'), $this->input->post('tvendor'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Vendor', 'Cur', 'Date', 'Notes', 'Acc', 'Balance', 'Action');

        $i = 0 ;
        foreach ($aps as $ap)
        {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');
            
            $this->table->add_row
            (
                ++$i, 'RC-00'.$ap->no, $this->vendor->get_vendor_name($ap->vendor),  $ap->currency, tglin($ap->dates), $ap->notes, ucfirst($ap->acc), number_format($ap->amount),
                anchor($this->title.'/confirmation/'.$ap->id,'<span>update</span>',array('class' => $this->post_status($ap->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$ap->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$ap->id.'/'.$ap->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function get_list_transaction($vendor=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_list';
        $data['form_action'] = site_url($this->title.'/get_list');

        $stocks = $this->demandmodel->get_list_transaction($vendor=null)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Cost', 'Notes', 'Amount');

        $i = 0;
        foreach ($stocks as $stock)
        {
          $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$stock->no.'\',\'tref\')');
          $this->table->add_row( ++$i, 'RC-00'.$stock->no, tglin($stock->dates), $stock->cost, $stock->notes, number_format($stock->amount));
        }

        $data['table'] = $this->table->generate();
        $this->load->view('cash_demand_list', $data);
    }
    
    function get_list($vendor=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_list';
        $data['form_action'] = site_url($this->title.'/get_list');

        $stocks = $this->demandmodel->get_list($vendor=null)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Action');

        $i = 0;
        foreach ($stocks as $stock)
        {
          $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$stock->no.'\',\'tref\')');
          $this->table->add_row( ++$i, 'RC-00'.$stock->no, tglin($stock->dates), form_button($datax) );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('cash_demand_list', $data);
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

               $this->session->set_flashdata('message', "$this->title RC-00$ap->no approved..!"); // set flash data message dengan session
               redirect($this->title);
            }
        }

    }

    private function cek_confirmation($po=null,$page=null)
    {
        $ap = $this->model->where('id', $po)->get();

        if ( $ap->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - RC-00$ap->no approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }

//    ===================== approval ===========================================


    private function create_po_journal($pid,$date,$currency,$code,$codetrans,$no,$type,$amount,$p1,$p2)
    {
        $cm = new Control_model();
        
        $landed   = $cm->get_id(1);
        $discount = $cm->get_id(3);
        $tax      = $cm->get_id(9);
        $stock    = $cm->get_id(10);
        $ap       = $cm->get_id(11);
        $bank     = $cm->get_id(12);
        
        $ap = $this->Apc_model->get_purchase_by_id($pid)->row();
        
        
        if ($p1 > 0)
        {
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount);
           $this->journal->create_journal($date,$currency,$code.' (Cash) ','DP',$no,'AP', $p1);
           
           // create journal- GL
           $this->journalgl->new_journal($no,$date,'PJ',$currency,$code,$amount, $this->session->userdata('log'));
           $this->journalgl->new_journal($no,$date,'CD',$currency,'DP Payment : PJ-00'.$no,$p1, $this->session->userdata('log'));
           
           $jid = $this->journalgl->get_journal_id('PJ',$ap->no);
           $dpid = $this->journalgl->get_journal_id('CD',$ap->no);
           
           $this->journalgl->add_trans($jid,$stock,$ap->total-$ap->tax-$ap->discount,0); // tambah persediaan
           $this->journalgl->add_trans($jid,$ap,0,$ap->p1+$ap->p2); // hutang usaha
           if ($ap->tax > 0){ $this->journalgl->add_trans($jid,$tax,$ap->tax,0); } // pajak pembelian
           if ($ap->costs > 0){ $this->journalgl->add_trans($jid,$landed,$ap->costs,0); } // landed costs
           if ($ap->discount > 0){ $this->journalgl->add_trans($jid,$discount,$ap->discount,0); } // discount
           
           //DP proses
           $this->journalgl->add_trans($dpid,$ap,$ap->p1,0); // potongan hutang usaha
           $this->journalgl->add_trans($dpid,$bank,0,$ap->p1); // potongan bank pembelian
           
        }
        else 
        { 
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount); 
           $this->journalgl->new_journal($no,$date,'PJ',$currency,$code,$amount, $this->session->userdata('log'));
           
           $jid = $this->journalgl->get_journal_id('PJ',$ap->no);
           $dpid = $this->journalgl->get_journal_id('CD',$ap->no);
            
           $this->journalgl->add_trans($jid,$stock,$ap->total-$ap->tax-$ap->discount,0); // tambah persediaan
           $this->journalgl->add_trans($jid,$ap,0,$ap->p1+$ap->p2); // hutang usaha
           if ($ap->tax > 0){ $this->journalgl->add_trans($jid,$tax,$ap->tax,0); } // pajak pembelian
           if ($ap->costs > 0){ $this->journalgl->add_trans($jid,$landed,$ap->costs,0); } // landed costs
           if ($ap->discount > 0){ $this->journalgl->add_trans($jid,$discount,$ap->discount,0); } // discount
        }
    }


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->model->where('id',$uid)->get();

        if ($val->approved == 1)
        { 
            $this->model->approved = 0;
            $this->model->status = 0;
            $this->model->save();
            $this->session->set_flashdata('message', "1 $this->title successfully rollback..!"); redirect($this->title);  
        }
        elseif ($this->valid_period($val->dates) == TRUE ) // cek journal harian sudah di approve atau belum
        {
            $this->transmodel->delete_po($uid); // model to delete item
            $this->model->delete(); // memanggil model untuk mendelete data
            $this->journalgl->remove_journal('RC', $po); // journal gl
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            redirect($this->title);
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!");
           redirect($this->title);
        } 
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
        
        $this->load->view('cash_demand_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cash_demand_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->counter();

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
        $this->form_validation->set_rules('tno', 'Order No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->vendor   = $this->vendor->get_vendor_id($this->input->post('tvendor'));
            $this->model->no       = $this->input->post('tno');
            $this->model->docno    = $this->input->post('tdocno');
            $this->model->dates    = $this->input->post('tdate');
            $this->model->currency = $this->input->post('ccurrency');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->desc     = $this->input->post('tdesc');
            $this->model->user     = intval($this->user->get_userid($this->session->userdata("username")));
            $this->model->log      = $this->session->userdata('log');
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('cash_demand_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $cash = $this->model->where('no', $po)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$cash->id);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$cash->id);
        $data['currency'] = $this->currency->combo();
        $data['code'] = $cash->no;
        $data['user'] = $this->session->userdata("username");
        
        $data['default']['vendor'] = $this->vendor->get_vendor_shortname($cash->vendor);
        $data['default']['date'] = $cash->dates;
        $data['default']['currency'] = $cash->currency;
        $data['default']['note'] = $cash->notes;
        $data['default']['type'] = $cash->type;
        $data['default']['desc'] = $cash->desc;
        $data['default']['user'] = $this->user->get_username($cash->user);
        $data['default']['docno'] = $cash->docno;
        $data['vendorid'] = $cash->vendor;

        $data['default']['balance'] = $cash->amount;

//        ============================ Apc Item  =========================================
        $items = $this->transmodel->get_last_item($cash->id)->result();
        
        $data['cost'] = $this->cost->combo();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Cost Type', 'Notes', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->cost->get_name($item->cost), $item->notes, number_format($item->amount),
                anchor_popup($this->title.'/edit_item/'.$item->id,'<span>print</span>',$this->attsupdate).' '.
                anchor($this->title.'/delete_item/'.$item->id.'/'.$cash->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('cash_demand_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($pid=null)
    {
        $this->cek_confirmation($pid,'add_trans');
        
        $this->form_validation->set_rules('ccost', 'Cost Type', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('cash_demand_id' => $pid, 'cost' => $this->input->post('ccost'),
                           'notes' => $this->input->post('tnotes'),
                           'amount' => $this->input->post('tamount'));
            
            $this->transmodel->add($pitem);
            $this->update_trans($pid);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    
    function edit_item($id)
    {
       $this->acl->otentikasi2($this->title); 
       $val = $this->transmodel->get_by_id($id);  
       $data['form_action_item'] = site_url($this->title.'/edit_item_process/'.$id.'/'.$val->cash_demand_id); 
       
       $data['cost'] = $this->cost->combo();
       
       $data['default']['notes'] = $val->notes;
       $data['default']['amount'] = $val->amount;       
       $data['default']['cost'] = $val->cost;
        
       $this->load->view('apc_update_item', $data); 
    }
    
    function edit_item_process($id,$apc)
    {
        $ap = $this->model->where('id', $apc)->get();
        
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($ap->no) == TRUE)
        {
            $pitem = array('notes' => $this->input->post('tnotes'), 
                           'cost' => $this->input->post('ccost'),
                           'amount' => $this->input->post('tamount'));
            
            $this->transmodel->update($id,$pitem);
            $this->update_trans($apc);
        }
        
        redirect($this->title.'/edit_item/'.$id);
    }
    
    private function update_trans($pid)
    {
        $totals = $this->transmodel->total($pid);
        
        $this->model->where('id', $pid)->get();
        $this->model->amount = $totals['amount'];
        $this->model->save();
    }

    function delete_item($id,$pid)
    {
        $this->cek_confirmation($pid,'add_trans');
        $this->acl->otentikasi2($this->title);
        $no = $this->model->where('id', $pid)->get();
        
        $this->transmodel->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($pid);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$no->no);
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
        $this->form_validation->set_rules('tno', 'Order No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        { 
            $this->model->where('id',$pid)->get();
            
            $this->model->vendor   = $this->vendor->get_vendor_id($this->input->post('tvendor'));
            $this->model->type     = $this->input->post('ctype');
            $this->model->docno    = $this->input->post('tdocno');
            $this->model->dates    = $this->input->post('tdate');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->desc     = $this->input->post('tdesc');
            $this->model->user     = $this->user->get_userid($this->input->post('tuser'));
            $this->model->log      = $this->session->userdata('log');
            
            $this->model->save();

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
       $data['vendor'] = $this->vendor->get_vendor_name($ap->vendor);
       $data['venbank'] = $this->vendor->get_vendor_bank($ap->vendor);
       $data['podate'] = tglin($ap->dates);
       $data['notes'] = $ap->notes;
       $data['acc'] = $ap->acc;
       $data['user'] = $this->user->get_username($ap->user);
       $data['currency'] = $ap->currency;
       $data['docno'] = $ap->docno;
       $data['log'] = $this->session->userdata('log');

       $data['amount'] = $ap->amount;
       $terbilang = $this->load->library('terbilang');
       if ($ap->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)); }
       
       if($ap->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

       $data['items'] = $this->transmodel->get_last_item($ap->id)->result();
       
       $data['accounting'] = $this->properti['accounting'];
       $data['manager']    = $this->properti['manager'];

       $this->load->view('cash_demand_invoice', $data);

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
        
        $this->load->view('cash_demand_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
        $category = null;

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        if ($type == 0){ $data['aps'] = $this->demandmodel->report($cur,$start,$end,$category)->result(); $page = 'cash_demand_report'; }
        elseif ($type == 1){ $data['aps'] = $this->demandmodel->report_category($cur,$start,$end)->result(); $page = 'cash_demand_report_category'; }
        
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
        
    }

// ====================================== REPORT =========================================

}

?>