<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_in extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Stock_in_model', '', TRUE);
        $this->load->model('Stock_in_item_model', 'items_model', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib();
        $this->unit = new Unit_lib();
        $this->vendor = new Vendor_lib();
        $this->user = new Admin_lib();
        $this->purchase = new Purchase_lib();
        $this->product = new Products_lib(); 
        $this->wt = new Warehouse_transaction(); 
        $this->opname = new Opname();
        $this->pr = new Purchase_return();
        $this->ap = new Ap_payment_lib();
        $this->journalgl = new Journalgl_lib();
    }
    
    private $properti, $modul, $title, $journalgl;
    private $vendor,$user,$purchase,$product,$wt,$opname,$currency,$unit,$pr;

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
        $data['main_view'] = 'stockin_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $stockins = $this->Stock_in_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Stock_in_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Date', 'Purchase', 'Staff', 'Action');

            $i = 0 + $offset;
            foreach ($stockins as $stockin)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $stockin->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'BTB-00'.$stockin->no, tglin($stockin->dates), 'PO-00'.$stockin->purchase, $stockin->staff,
                    anchor($this->title.'/confirmation/'.$stockin->id.'/'.$stockin->purchase,'<span>update</span>',array('class' => $this->post_status($stockin->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$stockin->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$stockin->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$stockin->id.'/'.$stockin->purchase,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'stockin_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $stockins = $this->Stock_in_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Purchase', 'Staff', 'Action');

        $i = 0;
        foreach ($stockins as $stockin)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $stockin->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'BTB-00'.$stockin->no, tglin($stockin->dates), 'PO-00'.$stockin->purchase, $stockin->staff,
                anchor($this->title.'/confirmation/'.$stockin->id.'/'.$stockin->purchase,'<span>update</span>',array('class' => $this->post_status($stockin->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$stockin->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$stockin->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$stockin->id.'/'.$stockin->purchase,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function cek_count_item($stockin,$po)
    {
        $val1 = $this->items_model->get_last_item($stockin)->num_rows();
        $val2 = $this->Stock_in_model->get_purchase_list($po)->num_rows();
        if ($val1 != $val2){ return FALSE; } else { return TRUE; }
    }

    function confirmation($pid,$po)
    {
        $stockin = $this->Stock_in_model->get_stock_in_by_id($pid)->row();
        $purchase = $this->purchase->get_po($po);

        if ($stockin->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!");
        }
        elseif($this->valid_period($stockin->dates) == FALSE){ $this->session->set_flashdata('message', "Invalid Period..!"); }
        elseif($this->cek_count_item($stockin->no,$po) == FALSE){ $this->session->set_flashdata('message', "Item List Not Match with PO-00".$po."..!"); }
        else
        {
           // add qty
           $result = $this->items_model->get_last_item($stockin->no)->result();
           foreach ($result as $res)
           {
               $this->product->add_qty($res->product,$res->qty, $this->purchase->get_buying_price($res->product,$stockin->purchase) * $res->qty);
               $this->product->add_stock($res->product,$stockin->dates,$res->qty, $this->purchase->get_buying_price($res->product,$stockin->purchase));
           }

           // add wt
           $this->add_warehouse_transaction($po);
           
           // add journal
           $this->create_po_journal($stockin->dates, 'IDR', 'BTB-00'.$stockin->no, $stockin->no);
           
           // update purchase stock-in stts
           $datax = array('stock_in_stts' => 1);
           $this->purchase->settled_po($po, $datax);

           //edit approvaal
           $data = array('approved' => 1);
           $this->Stock_in_model->update($pid, $data);
           $this->session->set_flashdata('message', "$this->title BTB-00$stockin->no confirmed..!");
        }

        redirect($this->title);
    }
    
    private function create_po_journal($date,$currency,$code,$no)
    {
        $amount = $this->wt->get_amount('BTB-00'.$no);
        
        $cm = new Control_model();
        
        $stock    = $cm->get_id(10);
        $piutang  = $cm->get_id(47);
        
        $this->journalgl->new_journal('0'.$no,$date,'STI',$currency,$code,$amount, $this->session->userdata('log'));
        $jid = $this->journalgl->get_journal_id('STI','0'.$no);

        $this->journalgl->add_trans($jid,$piutang, 0, $amount); // kurang piutang persediaan
        $this->journalgl->add_trans($jid,$stock, $amount, 0); // tambah persediaan
    }

    function add_warehouse_transaction($po)
    {
        $val  = $this->Stock_in_model->get_stock_in_by_po($po)->row();
        $purchase = $this->purchase->get_po($val->purchase);
        $list = $this->items_model->get_last_item($val->no)->result();
        $prolib = new Products_lib();
        
        foreach ($list as $value)
        {
           $price = $this->purchase->get_buying_price($value->product,$val->purchase); 
           $this->wt->add( $val->dates,
                           'BTB-00'.$val->no,
                           $purchase->currency,
                           $value->product,
                           $value->qty, 0,
                           $price, // price
                           $value->qty * $price, // amount
                           $this->session->userdata('log'));
        }
    }

    private function del_warehouse_transaction($po=0)
    {
        $val  = $this->Stock_in_model->get_stock_in_by_po($po)->row();
        $list = $this->items_model->get_last_item($val->no)->result();

        foreach ($list as $value)
        {
           $this->wt->remove($val->dates, 'BTB-00'.$val->no, $value->product);
//           $this->product->min_qty($value->name,$value->qty);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $stockin = $this->Stock_in_model->get_stock_in_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - BTB-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }

//    ===================== approval ===========================================
    
    function cek_stock($po)
    {
        $val  = $this->Stock_in_model->get_stock_in_by_no($po)->row();
        $list = $this->items_model->get_last_item($po)->result();
        
        $result = FALSE;
        foreach ($list as $value)
        {
           if ($this->product->valid_stock($value->product,$val->dates,$value->qty) == FALSE)
           {
               break;
               $result = FALSE;
           }
           else { return TRUE; }
        } 
        return $result;
    }

    function delete($uid,$purchase)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Stock_in_model->get_stock_in_by_id($uid)->row();
        
        
        if ($this->pr->cek_relation($val->purchase,'purchase') == TRUE && $this->ap->cek_relation_trans($val->purchase,'no','PO') == TRUE) 
        {
          if ($val->approved == 1 )
          { 
             if ($this->cek_stock($val->no) != TRUE)
             { $this->session->set_flashdata('message', "1 $this->title cannot deleted, invalid stock ..!");  }
             else
             {
               $this->rollback($uid, $purchase); $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");   
             }
          }
          else 
          {
              $this->remove($uid, $po); $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
          }
        }
        else { $this->session->set_flashdata('message', "1 $this->title cannot deleted related to another component ..!"); }
        
        redirect($this->title);
    }
    
    private function cek_qty_product($po)
    {
       $result = $this->items_model->get_last_item($po)->result();
       $hasil = FALSE;
       foreach ($result as $res)
       {
           $qtypro = $this->product->get_qty($res->product);
           $resultqty = intval($qtypro-$res->qty);
           if ($resultqty < 0){ $hasil = FALSE; break; }else { $hasil = TRUE; }
       }
       return $hasil;
    }
    
    private function rollback($uid,$purchase)
    {
        $val = $this->Stock_in_model->get_stock_in_by_id($uid)->row();
        $pdate = $this->purchase->get_po($val->purchase); // purchase date
        
        $result = $this->items_model->get_last_item($val->no)->result();
        foreach ($result as $res)
        { 
            $this->product->min_qty($res->product,$res->qty, $this->purchase->get_buying_price($this->product->get_name($res->product),$val->purchase) * $res->qty);                
            $this->product->min_stock($res->product,$val->dates,$val->dates,$res->qty);
        }
        // remove wt
        $this->del_warehouse_transaction($val->purchase);
        
        // remove journal
        $this->journalgl->remove_journal('STI', '0'.$val->no); // journal gl
        
                // update purchase stock-in stts
        $datax = array('stock_in_stts' => 0);
        $this->purchase->settled_po($val->purchase, $datax);
        
         //edit approvaal
         $data = array('approved' => 0);
         $this->Stock_in_model->update($uid, $data);
    }
    
    private function remove($uid,$po)
    {
       $val = $this->Stock_in_model->get_stock_in_by_id($uid)->row(); 
       $this->items_model->delete_po($val->no); $this->Stock_in_model->delete($uid); 
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");

        $data['code'] = $this->Stock_in_model->counter();
        $this->load->view('stockin_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'stockin_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->input->post('tno');
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'No', 'required|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tpo', 'Purchase', 'required|callback_valid_po');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tstaff', 'Vendor / Staff', 'required');
        $this->form_validation->set_rules('tdesc', 'Desc', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $stockin = array('no' => $this->input->post('tno'), 'dates' => $this->input->post('tdate'),
                             'purchase' => $this->input->post('tpo'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                             'staff' => $this->input->post('tstaff'), 'desc' => setnull($this->input->post('tdesc')), 'log' => $this->session->userdata('log'));
//
            $this->Stock_in_model->add($stockin);

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$data['code'].'/');
//            echo 'true';
        }
        else
        {
              $this->load->view('stockin_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $stockin = $this->Stock_in_model->get_stock_in_by_no($po)->row();
        
        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po.'/'.$stockin->purchase);
        $data['unit'] = $this->unit->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");       

        $data['default']['date'] = $stockin->dates;
        $data['po'] = $stockin->purchase;
        $data['default']['desc'] = $stockin->desc;
        $data['default']['staff'] = $stockin->staff;
        $data['default']['user'] = $this->user->get_username($stockin->user);

//        ============================ Item  =========================================
        $items = $this->items_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Name', 'Qty', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
               ++$i, 'PRO-0'.$item->id.'-'.$this->product->get_name($item->product), $item->qty.' '.$this->product->get_unit($item->product),
               anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('stockin_transform', $data);
    }

    //    ======================  Item Transaction   ===============================================================

    function add_item($po=null,$purchase=0)
    {
//        $this->cek_confirmation($po,'add_trans');

        $this->form_validation->set_rules('titem', 'Item Name', 'required|callback_valid_product['.$purchase.']|callback_valid_item['.$po.']');
//        $this->form_validation->set_rules('titem', 'Item Name', 'required|callback_valid_product['.$purchase.']');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($po) == TRUE)
        {
            $pitem = array('stock_in' => $po, 'product' => $this->product->get_id($this->input->post('titem')), 'qty' => $this->input->post('tqty'));
            $this->items_model->add($pitem);
            echo 'true';
        }
        else{ echo validation_errors(); }
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);

        $this->items_model->delete($id); // memanggil model untuk mendelete data
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
	$data['link'] = array('link_back' => anchor('stockin','<span>back</span>', array('class' => 'back')));

	// Form validation

        $this->form_validation->set_rules('tno', 'No', 'required|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdesc', 'Desc', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $stockin = array('dates' => $this->input->post('tdate'), 'staff' => $this->input->post('tstaff'),
                             'desc' => setnull($this->input->post('tdesc')), 'log' => $this->session->userdata('log'));

            $this->Stock_in_model->update_no($po, $stockin);

//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {  echo validation_errors(); }
    }

    public function valid_product($product,$purchase)
    {
        $purchaseitem = new Purchase_item();

        if ($purchaseitem->valid_item($purchase,$this->product->get_id($product)) == FALSE)
        {
            $this->form_validation->set_message('valid_product', "Product [ $product ] Not Listed in PO-00$purchase ..!");
            return FALSE;
        }
        else { return TRUE; }
    }

    public function valid_item($product,$stockin)
    {
        $product = $this->product->get_id($product);
        if ($this->items_model->valid_item($stockin,$product) == FALSE)
        {
            $this->form_validation->set_message('valid_item', "Product already registered ...!");
            return FALSE;
        }
        else { return TRUE; }
    }

    public function valid_po($no)
    {
        if ($this->Stock_in_model->valid_po($no) == FALSE)
        {
            $this->form_validation->set_message('valid_po', "PO already registered..!");
            return FALSE;
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
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_confirmation($po)
    {
        $stockin = $this->Stock_in_model->get_stock_in_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - BTB-00$po approved..!");
           return FALSE;
        }
        else { return TRUE; }
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

    public function valid_no($no)
    {
        if ($this->Stock_in_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "BTB already registered..!");
            return FALSE;
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================

   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $stockin = $this->Stock_in_model->get_stock_in_by_no($po)->row();
//
       $data['no'] = $po;
       $data['po'] = 'PO-00'.$stockin->purchase;
       $data['podate'] = tglin($stockin->dates);
       $data['staff'] = $stockin->staff;
       $data['user'] = $this->user->get_username($stockin->user);

       $venid = $this->purchase->get_po($stockin->purchase);
       $data['vendor'] = $this->vendor->get_vendor_name($venid->vendor);
//
       $data['items'] = $this->items_model->get_last_item($po)->result();


       $this->load->view('stockin_invoice', $data);
       
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

        $this->load->view('stockin_report_panel', $data);
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
        $data['reports'] = $this->Stock_in_model->report($start,$end)->result();
        $data['reports_trans'] = $this->Stock_in_model->report_transaction($start,$end)->result();

        if ($this->input->post('ctype') == 'sum') { $this->load->view('stockin_report', $data); } 
        elseif($this->input->post('ctype') == 'trans'){ $this->load->view('stockin_report_trans', $data); }
        else { $this->load->view('stockin_report_details', $data); }

        

    }

//    ================================ REPORT =====================================

}

?>