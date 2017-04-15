<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_out extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Stock_out_model', '', TRUE);
        $this->load->model('Stock_out_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->load->library('unit_lib');
        $this->product = new Products_lib();
        $this->user = new Admin_lib();
        $this->return_stock = new Return_stock_lib();
        $this->wt = $this->load->library('warehouse_transaction');
        $this->opname = $this->load->library('opname');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->warehouse = new Warehouse_lib();
        $this->reststock = new Reststock_lib();
        $this->stocktemp = new Stock_out_temp_lib();
    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title, $currency, $stockvalue=0,$reststock;
    private $user,$product,$return_stock,$wt,$opname,$journalgl,$warehouse,$stocktemp;

    function index()
    {
        $this->get_last_stockout();
    }

    function get_last_stockout()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'stockout_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $stockouts = $this->Stock_out_model->get_last_stockout($this->modul['limit'], $offset)->result();
        $num_rows = $this->Stock_out_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_stockout');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Notes', 'Staff', 'Amount', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($stockouts as $stockout)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $stockout->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'BPBG-00'.$stockout->no, tglin($stockout->dates), $stockout->currency, $stockout->desc, $stockout->staff, number_format($stockout->balance), $stockout->log,
                    anchor($this->title.'/confirmation/'.$stockout->id,'<span>update</span>',array('class' => $this->post_status($stockout->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$stockout->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$stockout->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$stockout->id.'/'.$stockout->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
    
    private function get_total($no){ return $this->Stock_out_item_model->total($no); }
    
    private function update_balance($no)
    {
       $data = array('balance' => $this->get_total($no));
       $this->Stock_out_model->update($no, $data); 
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'stockout_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $stockouts = $this->Stock_out_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
       $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Notes', 'Staff', 'Amount', 'Log', 'Action');

        $i = 0;
        foreach ($stockouts as $stockout)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $stockout->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'BPBG-00'.$stockout->no, tglin($stockout->dates), $stockout->currency, $stockout->desc, $stockout->staff, number_format($stockout->balance), $stockout->log,
                anchor($this->title.'/confirmation/'.$stockout->id,'<span>update</span>',array('class' => $this->post_status($stockout->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$stockout->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$stockout->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$stockout->id.'/'.$stockout->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

        $stocks = $this->Stock_out_model->get_list($this->input->post('tno'))->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Staff', 'Action');

        $i = 0;
        foreach ($stocks as $stock)
        {
          $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$stock->no.'\',\'tbpbg\')');
          $this->table->add_row( ++$i, 'BPBG-00'.$stock->no, tgleng($stock->dates), $stock->desc, $stock->staff, form_button($datax) );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('stockout_list', $data);
        
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
        $stockout = $this->Stock_out_model->get_stockout_by_id($pid)->row();

        if ($stockout->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
//            cek qty di product 

            if ($this->cek_qty($stockout->no) == FALSE)
            {
              $this->session->set_flashdata('message', "$this->title request wrong qty..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $this->update_qty($stockout->no); //  update qty
                $this->add_warehouse_transaction($stockout->no); // add wt
                $this->create_po_journal($stockout->dates, $stockout->currency, 'BPBG-00'.$stockout->no, $stockout->no, $stockout->balance); // create journal
             
                $data = array('approved' => 1);
                $this->Stock_out_model->update_id($pid, $data);

                $this->session->set_flashdata('message', "$this->title BPBG-00$stockout->no confirmed..!");
                redirect($this->title);
            }
        }

    }
    
    private function calculate_stock($so)
    {
        $val = $this->Stock_out_item_model->get_last_item($so)->result();
        $this->stockvalue = 0;
        $this->stockid = null;
        
        foreach ($val as $res)
        {  $this->get_stock($res->product, $res->qty, $so);  }
    }
    
    private function get_stock($pid,$qty=0,$so) //FIFO / LIFO
    {
        if ($qty > 0){ $this->stock($pid,$qty,$so); }
    }
    
    private function stock($pid,$req,$so)
    {
        $res = $this->stocktemp->get_first_stock($pid);  
        $stockout = $this->Stock_out_model->get_stockout_by_no($so)->row();

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
    
    
    private function create_po_journal($date,$currency,$code,$no,$amount)
    {
        $cm = new Control_model();
        
        $stock    = $cm->get_id(10); // stock
        $hpp      = $cm->get_id(20); // hpp
        
        $this->journalgl->new_journal('0'.$no,$date,'STO',$currency,$code,$amount, $this->session->userdata('log'));
        $jid = $this->journalgl->get_journal_id('STO','0'.$no);

        $this->journalgl->add_trans($jid,$stock, 0, $amount); // kurang persediaan
        $this->journalgl->add_trans($jid,$hpp, $amount, 0); // tambah biaya 1 (hpp)
    }

    private function add_warehouse_transaction($po)
    {
        $val  = $this->Stock_out_model->get_stockout_by_no($po)->row();
        $list = $this->Stock_out_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->add( $val->dates,
                           'BPBG-00'.$po,
                           $val->currency,
                           $value->product,
                           0, $value->qty,
                           $value->price,
                           $value->qty * $value->price,
                           $this->session->userdata('log'));

//           $this->product->add_qty($value->name,$value->qty);
        }
    }

    private function del_warehouse_transaction($po=0)
    {
        $val  = $this->Stock_out_model->get_stockout_by_no($po)->row();
        $list = $this->Stock_out_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->remove($val->dates, 'BPBG-00'.$po, $value->product);
        }
    }

    function cek_qty($no)
    {
        $val = $this->Stock_out_item_model->get_last_item($no)->result();
        $result = null;
        foreach ($val as $res)
        {
            if ($this->product->valid_qty($res->product,$res->qty) == FALSE){ break; $result = FALSE; } else { $result = TRUE; }
        }
        return $result;
    }

    private function update_qty($so)
    {
        $val = $this->Stock_out_item_model->get_last_item($so)->result();
        foreach ($val as $res)
        {
            $this->product->min_qty($res->product,$res->qty, 0);
        }
    }
    
    private function add_reststock($cur,$date,$warehouse,$so)
    {
        $val = $this->Stock_out_item_model->get_last_item($so)->result();
        foreach ($val as $res)
        {
            $this->reststock->add($cur, $date, $warehouse, $res->product, $res->qty, $this->user->get_userid($this->session->userdata('username')));
        }
    }

    private function rollback_qty($so)
    {
        $sales = $this->Stock_out_model->get_stockout_by_no($so)->row();
        $val = $this->Stock_out_item_model->get_last_item($so)->result();

        foreach ($val as $res)
        {
            $this->product->add_qty($res->product,$res->qty);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $stockout = $this->Stock_out_model->get_stockout_by_no($po)->row();

        if ( $stockout->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - BPBG-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Stock_out_model->get_stockout_by_id($uid)->row();

        if ($this->cek_relation($po) == TRUE)
        {
            if ( $val->approved == 1 ) // cek journal harian sudah di approve atau belum
            {
                $this->rollback($uid, $po);
                $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");
            }
            else
            {
               $this->remove($uid, $po);
               $this->session->set_flashdata('message', "1 $this->title successfully remove..!");
            }  
        }
        else { $this->session->set_flashdata('message', "1 $this->title related to another component..!"); }
        redirect($this->title);
    }
    
    private function rollback($uid,$po)
    {
       // remove journal
       $this->journalgl->remove_journal('STO', '0'.$po); // journal gl 
        
       $this->rollback_qty($po);
       $this->stocktemp->rollback_stock($po); 
       $this->del_warehouse_transaction($po);
       $data = array('approved' => 0);
       $this->Stock_out_model->update_id($uid, $data);
//                $this->journalgl->remove_journal('STO', $po); // journal gl
    }
    
    private function remove($uid,$po)
    {
       $this->Stock_out_item_model->delete_po($po);
       $this->Stock_out_model->delete($uid); 
    }

    private function cek_relation($id=null)
    {
        $return = $this->return_stock->cek_relation($id, $this->title);
        if ($return == TRUE) { return TRUE; } else { return FALSE; }
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Stock_out_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
        
        $this->load->view('stockout_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'stockout_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['warehouse'] = $this->warehouse->combo();
        $data['code'] = $this->Stock_out_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'BPBG - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Workshop Staff', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $stockout = array('no' => $this->input->post('tno'), 'approved' => 0, 'staff' => $this->input->post('tstaff'), 
                              'currency' => $this->input->post('ccurrency'),
                              'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'), 
                              'user' => $this->user->get_userid($this->input->post('tuser')),
                              'log' => $this->session->userdata('log'));
            
            $this->Stock_out_model->add($stockout);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('stockout_form', $data);
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

        $stockout = $this->Stock_out_model->get_stockout_by_no($po)->row();

        $data['default']['date'] = $stockout->dates;
        $data['default']['staff'] = $stockout->staff;
        $data['default']['note'] = $stockout->desc;
        $data['default']['currency'] = $stockout->currency;
        $data['user'] = $this->user->get_username($stockout->user);

//        ============================ Purchase Item  =========================================
        $items = $this->Stock_out_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Qty', 'Desc', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), $item->qty.' '.$this->product->get_unit($item->product), $item->desc, number_format($item->price),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('stockout_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('tproduct', 'Item Name', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric|callback_valid_qty');
        $this->form_validation->set_rules('tdesc', 'Desc', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $product = $this->product->get_id($this->input->post('tproduct'));
            $this->get_stock($product, $this->input->post('tqty'), $po);
            
//            echo $this->stockvalue;
            
            $pitem = array('product' => $product, 'stock_out' => $po, 'price' => intval($this->stockvalue/$this->input->post('tqty')),
                           'qty' => $this->input->post('tqty'), 'desc' => $this->input->post('tdesc'));

            $this->Stock_out_item_model->add($pitem);
            $this->update_balance($po);
            
            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->stocktemp->rollback_stock($po);
        $this->Stock_out_item_model->delete($id); // memanggil model untuk mendelete data
        $this->update_balance($po);
        
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
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tno', 'BPBG - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Workshop Staff', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $stockout = array('staff' => $this->input->post('tstaff'),
                              'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                              'log' => $this->session->userdata('log'));

            $this->Stock_out_model->update($po, $stockout);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('stockout_transform', $data);
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
    
    public function valid_no($no)
    {
        if ($this->Stock_out_model->valid_no($no) == FALSE)
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

    public function valid_qty($qty)
    {
        $pro = $this->product->get_details($this->product->get_id($this->input->post('tproduct')));
        $pqty = $pro->qty;

        if ($pqty - $qty < 0) { $this->form_validation->set_message('valid_qty', "Qty Not Enough..!"); return FALSE; } else { return TRUE; }
    }

    public function valid_confirmation($po)
    {
        $stockin = $this->Stock_out_model->get_stockout_by_no($po)->row();

        if ($stockin->approved == 1)
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

       $stockout = $this->Stock_out_model->get_stockout_by_no($po)->row();

       $data['no'] = $po;
       $data['podate'] = tgleng($stockout->dates);
       $data['user'] = $this->user->get_username($stockout->user);
       $data['staff'] = $stockout->staff;
       $data['currency'] = $stockout->currency;

       $data['items'] = $this->Stock_out_item_model->report($po)->result();

       $this->load->view('stockout_invoice', $data);
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
        
        $this->load->view('stockout_report_panel', $data);
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
        $type = $this->input->post('ctype');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Stock_out_model->report($start,$end)->result();
        $data['reports_category'] = $this->Stock_out_model->report_category($start,$end)->result();
        
        if ($type == 0){ $this->load->view('stockout_report', $data);}
        elseif ($type == 1){ $this->load->view('stockout_report_details', $data);}
        elseif ($type == 2){ $this->load->view('stockout_report_category', $data);}
        elseif ($type == 3){ $this->load->view('stockout_report_pivot', $data);}
        
    }


// ====================================== REPORT =========================================

}

?>