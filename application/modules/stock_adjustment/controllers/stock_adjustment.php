<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_adjustment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Stock_adjustment_model', '', TRUE);
        $this->load->model('Stock_adjustment_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib();
        $this->load->library('unit_lib');
        $this->product = new Products_lib();
        $this->user = new Admin_lib();
        $this->return_stock = new Return_stock_lib();
        $this->wt = new Warehouse_transaction();
        $this->opname = new Opname();
        $this->stocktemp = new Stock_adjustment_temp_lib();
        $this->journalgl = new Journalgl_lib();
        $this->account = new Account_lib();
    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title, $stockvalue=0, $stocktemp, $journalgl;
    private $user,$product,$return_stock,$wt,$opname,$currency,$account;

    function index()
    {
        $this->get_last_stock_adjustment();
    }

    function get_last_stock_adjustment()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'stock_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $stock_adjustments = $this->Stock_adjustment_model->get_last_stock_adjustment($this->modul['limit'], $offset)->result();
        $num_rows = $this->Stock_adjustment_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_stock_adjustment');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Notes', 'Staff', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($stock_adjustments as $stock_adjustment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $stock_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'IAJ-00'.$stock_adjustment->no, tglin($stock_adjustment->dates), $stock_adjustment->currency, $stock_adjustment->desc, $stock_adjustment->staff, $stock_adjustment->log,
                    anchor($this->title.'/confirmation/'.$stock_adjustment->id,'<span>update</span>',array('class' => $this->post_status($stock_adjustment->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$stock_adjustment->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$stock_adjustment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$stock_adjustment->id.'/'.$stock_adjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'stock_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $stock_adjustments = $this->Stock_adjustment_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Notes', 'Staff', 'Log', 'Action');

        $i = 0;
        foreach ($stock_adjustments as $stock_adjustment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $stock_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'IAJ-00'.$stock_adjustment->no, tglin($stock_adjustment->dates), $stock_adjustment->currency, $stock_adjustment->desc, $stock_adjustment->staff, $stock_adjustment->log,
                anchor($this->title.'/confirmation/'.$stock_adjustment->id,'<span>update</span>',array('class' => $this->post_status($stock_adjustment->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$stock_adjustment->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$stock_adjustment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$stock_adjustment->id.'/'.$stock_adjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }


    function get_list()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_list';
        $data['form_action'] = site_url($this->title.'/get_list');

        $stocks = $this->Stock_adjustment_model->get_list($this->input->post('tno'))->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Staff', 'Action');

        $i = 0;
        foreach ($stocks as $stock)
        {
          $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$stock->no.'\',\'tbpbg\')');
          $this->table->add_row( ++$i, 'IAJ-00'.$stock->no, tgleng($stock->dates), $stock->desc, $stock->staff, form_button($datax) );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('stock_adjustment_list', $data);
        
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
        $this->acl->otentikasi_admin($this->title);   
        $stock_adjustment = $this->Stock_adjustment_model->get_stock_adjustment_by_id($pid)->row();

        if ($stock_adjustment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $data = array('approved' => 1);
            $this->Stock_adjustment_model->update_id($pid, $data);

            //  update qty
           $this->update_qty($stock_adjustment->no);
           
           // create journal
           $balancein = $this->Stock_adjustment_item_model->total_criteria($stock_adjustment->no,'in');
           $balanceout = $this->Stock_adjustment_item_model->total_criteria($stock_adjustment->no,'out');
           $this->create_po_journal($stock_adjustment->dates, 'IDR', 'IAJ-00'.$stock_adjustment->no, $stock_adjustment->no, $balancein, $balanceout); // create journal

           // add wt
           $this->add_warehouse_transaction($stock_adjustment->no);

           $this->session->set_flashdata('message', "IAJ-00$stock_adjustment->no confirmed..!");
           redirect($this->title);
        }

    }
    
    private function create_po_journal($date,$currency,$code,$no,$amountin,$amountout)
    {
        $item = $this->Stock_adjustment_item_model->get_last_item($no)->result();
        
        $cm = new Control_model();
        
        $stock   = $cm->get_id(10); // stock
        $cost    = $cm->get_id(50); // biaya
//        $income  = $cm->get_id(49); // income
        
        $this->journalgl->new_journal('00'.$no,$date,'IJ',$currency,$code,intval($amountin+$amountout), $this->session->userdata('log'));
        $jid = $this->journalgl->get_journal_id('IJ','00'.$no);
        
        if ($amountin > 0)
        {
            foreach ($item as $res)
            {
               $this->journalgl->add_trans($jid,$res->account, 0, intval($res->qty*$res->price)); // income bertambah 
            } 
            
           $this->journalgl->add_trans($jid,$stock, $amountin, 0); // tambah persediaan 
        }
        
        if ($amountout > 0)
        {
           $this->journalgl->add_trans($jid,$stock, 0, $amountout); // kurang persediaan 
           $this->journalgl->add_trans($jid,$cost, $amountout, 0); // tambah biaya 
        }
        
    }

    private function add_warehouse_transaction($po)
    {
        $val  = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();
        $list = $this->Stock_adjustment_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           if ($value->type == 'out')
           {
                $this->wt->add( $val->dates, 'IAJ-00'.$po, $val->currency, $value->product, 0, $value->qty,
                           $value->price, $value->price*$value->qty,
                           $this->session->userdata('log'));
           }
           else
           {
                $this->wt->add( $val->dates, 'IAJ-00'.$po, $val->currency, $value->product, $value->qty, 0,
                           $value->price, $value->price*$value->qty,
                           $this->session->userdata('log'));
           }
//           $this->product->add_qty($value->name,$value->qty);
        }
    }

    private function del_warehouse_transaction($po=0)
    {
        $val  = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();
        $list = $this->Stock_adjustment_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->remove($val->dates, 'IAJ-00'.$po, $value->product);
//           $this->product->min_qty($value->name,$value->qty);
        }
    }

    private function update_qty($no)
    {
        $val = $this->Stock_adjustment_item_model->get_last_item($no)->result();
        foreach ($val as $res)
        {
            if ($res->type == 'in'){ $this->product->add_qty($res->product, $res->qty); }
            else{ $this->product->min_qty($res->product, $res->qty); }
        }
    }

    private function rollback_qty($no)
    {
        $val = $this->Stock_adjustment_item_model->get_last_item($no)->result();
        foreach ($val as $res)
        {
            if ($res->type == 'out'){ $this->product->add_qty($res->product, $res->qty); }
            else{ $this->product->min_qty($res->product, $res->qty); }
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $stock_adjustment = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();

        if ( $stock_adjustment->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - BPBG-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Stock_adjustment_model->get_stock_adjustment_by_id($uid)->row();

        if ( $val->approved == 1 ){ $this->rollback($uid, $po); }
        else{ $this->remove($uid, $po);}

        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }
    
    private function rollback($uid,$po)
    {
       $this->journalgl->remove_journal('IJ', '00'.$po); // journal gl  
       $this->rollback_qty($po);
       $this->del_warehouse_transaction($po); 
       $data = array('approved' => 0);
       $this->Stock_adjustment_model->update_id($uid, $data);
    }
    
    private function remove($uid,$po)
    {
       $stockadj = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row(); 
       $stockitem = $this->Stock_adjustment_item_model->get_last_item($po)->result();
       
       if ($stockitem)
       {
          foreach($stockitem as $res)
          {   
           if ($res->type == 'out'){ $this->stocktemp->rollback_stock($po); }
           elseif ($res->type == 'in') { $this->stocktemp->min_stock($res->product,$stockadj->dates,$stockadj->dates,$res->qty); }
          } 
       }

       $this->Stock_adjustment_item_model->delete_po($po);
       $this->Stock_adjustment_model->delete($uid); 
    }

    private function cek_relation($id=null)
    { $return = $this->return_stock->cek_relation($id, $this->title); if ($return == TRUE) { return TRUE; } else { return FALSE; } }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Stock_adjustment_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
        
        $this->load->view('stock_adjustment_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'stock_adjustment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Stock_adjustment_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'IAJ - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Workshop Staff', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $stock_adjustment = array('no' => $this->input->post('tno'), 'approved' => 0, 'staff' => $this->input->post('tstaff'), 
                                      'currency' => $this->input->post('ccurrency'), 'dates' => $this->input->post('tdate'),
                                      'desc' => $this->input->post('tnote'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                                      'log' => $this->session->userdata('log'));
            
            $this->Stock_adjustment_model->add($stock_adjustment);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('stock_adjustment_form', $data);
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
        $data['code'] = $po;

        $stock_adjustment = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();

        $data['default']['date'] = $stock_adjustment->dates;
        $data['default']['staff'] = $stock_adjustment->staff;
        $data['default']['note'] = $stock_adjustment->desc;
        $data['default']['currency'] = $stock_adjustment->currency;
        $data['user'] = $this->user->get_username($stock_adjustment->user);

//        ============================ Purchase Item  =========================================
        $items = $this->Stock_adjustment_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Type', 'Qty', 'Price', 'Account', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), strtoupper($item->type), $item->qty.' '.$this->product->get_unit($item->product), number_format($item->price), $this->account->get_code($item->account).' - '.$this->account->get_name($item->account),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po.'/'.$item->type,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('stock_adjustment_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('tproduct', 'Item Name', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('titem', 'Account', 'callback_valid_account');

        if ($this->form_validation->run($this) == TRUE)
        {
            $stockadj = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();
            
            $type = $this->input->post('ctype');
            $qty = $this->input->post('tqty');
//            if ($this->input->post('tqty') < 0){ $type = 'out'; $qty = abs($this->input->post('tqty')); }
//            else{ $type = 'in'; $qty = $this->input->post('tqty'); }
            
            if ($type == 'out')
            {
               $account = 0; 
               $this->get_stock($this->product->get_id($this->input->post('tproduct')), $qty, $po);
               $price = intval($this->stockvalue/$qty);
            }
            elseif ($type == 'in')
            {
                $account = $this->account->get_id_code($this->input->post('titem'));
                $price = $this->input->post('tamount');
                $this->product->add_stock($this->product->get_id($this->input->post('tproduct')), $stockadj->dates, $qty, $this->input->post('tamount'));
//               $price = $this->product->get_unit_cost($this->product->get_id($this->input->post('tproduct')),$stockadj->dates); 
//               $this->stocktemp->add_stock($this->product->get_id($this->input->post('tproduct')),$stockadj->dates,$qty, $price); 
            }
            
            $pitem = array('product' => $this->product->get_id($this->input->post('tproduct')), 'stock_adjustment' => $po,
                           'qty' => $qty, 'type' => $type, 'price' => $price, 'account' => $account);

            $this->Stock_adjustment_item_model->add($pitem);
            echo 'true';
        }
        else{ echo validation_errors(); }
    }

    function delete_item($id,$po,$type)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $stockadj = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();
        $stockitem = $this->Stock_adjustment_item_model->get_item_by_id($id);
        
        if ($type == 'out'){ $this->stocktemp->rollback_stock($po); }
        elseif ($type == 'in'){ $this->stocktemp->min_stock($stockitem->product,$stockadj->dates,$stockadj->dates,$stockitem->qty); }
        
        $this->Stock_adjustment_item_model->delete($id); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }

    private function get_stock($pid,$qty=0,$so) //FIFO / LIFO
    {
        if ($qty > 0){ $this->stock($pid,$qty,$so); }
    }
    
    private function stock($pid,$req,$so)
    {
        $res = $this->stocktemp->get_first_stock($pid);  
        $stockout = $this->Stock_adjustment_model->get_stock_adjustment_by_no($so)->row();

        if ($res != null)
        {
           if($req > $res->qty)
           { 
               $this->stockvalue = $this->stockvalue + intval($res->qty*$res->amount);
               $this->stocktemp->min_stock($pid,$res->dates,$stockout->dates,$res->qty,$so);
               $this->get_stock($pid, intval($req - $res->qty),$so); 
           }
           else 
           { 
               $this->stockvalue = $this->stockvalue + intval($req*$res->amount);
               $this->stocktemp->min_stock($pid,$res->dates,$stockout->dates,$req,$so);
               $this->get_stock($pid, 0,$so); 
           } 
        }
        else{ $this->get_stock($pid, 0,$so); }  
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
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tno', 'IAJ - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Workshop Staff', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $stock_adjustment = array('staff' => $this->input->post('tstaff'),
                              'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                              'log' => $this->session->userdata('log'));

            $this->Stock_adjustment_model->update($po, $stock_adjustment);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('stock_adjustment_transform', $data);
            echo validation_errors();
        }
    }
    
    public function valid_account($acc)
    {
        if ($this->input->post('ctype') == 'in')
        {
            if (!$acc){ $this->form_validation->set_message('valid_account', "Account Chart Required.!"); return FALSE; }
            else { return TRUE; }
        }
        else { return TRUE; }
    }

    public function valid_no($no)
    {
        if ($this->Stock_adjustment_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_opname($desc)
    {
        if ( $this->opname->cek_begindate() == FALSE )
        {
           $this->form_validation->set_message('valid_opname', "Inventory Taking Not Created...!!");
           return FALSE;
        }
        else { return TRUE; }
    }

    public function valid_confirmation($po)
    {
        $stockin = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - BPBG-00$po approved..!");
           return FALSE;
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================
  
   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $stock_adjustment = $this->Stock_adjustment_model->get_stock_adjustment_by_no($po)->row();

       $data['no'] = $po;
       $data['podate'] = tgleng($stock_adjustment->dates);
       $data['user'] = $this->user->get_username($stock_adjustment->user);
       $data['staff'] = $stock_adjustment->staff;
       $data['log'] = $this->session->userdata('log');
       
        // property display
       $data['company'] = $this->properti['name'];
       $data['address'] = $this->properti['address'];
       $data['phone1'] = $this->properti['phone1'];
       $data['phone2'] = $this->properti['phone2'];
       $data['city'] = ucfirst($this->properti['city']);
       $data['zip'] = $this->properti['zip'];
       $data['npwp'] = $this->properti['npwp'];
       $data['website'] = $this->properti['sitename'];
       $data['email'] = $this->properti['email'];

       $data['items'] = $this->Stock_adjustment_item_model->get_last_item($po)->result();

       $this->load->view('stock_adjustment_invoice', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $this->load->view('stock_adjustment_report_panel', $data);
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

        $data['reports'] = $this->Stock_adjustment_model->report($start,$end)->result();
        $data['reports_category'] = $this->Stock_adjustment_model->report_category($start,$end)->result();
        
        if ($this->input->post('ctype') == 0){ $this->load->view('stock_adjustment_report_category', $data);}
        else { $this->load->view('stock_adjustment_report_details', $data); }
    }


// ====================================== REPORT =========================================
    
// ====================================== AJAX =========================================    
   
   function get_price($product)
   {
       
   }

}

?>