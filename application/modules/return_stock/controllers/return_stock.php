<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Return_stock extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Return_stock_model', '', TRUE);
        $this->load->model('Return_stock_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->product = new Products_lib();
        $this->user = new Admin_lib();
        $this->stock_out_item = new Stock_out_item();
        $this->wt = new Warehouse_transaction();
        $this->journalgl = new Journalgl_lib();
    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title, $journalgl;
    private $user,$product,$stock_out_item,$wt;

    function index()
    {
        $this->get_last_return_stock();
    }

    function get_last_return_stock()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'return_stock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $return_stocks = $this->Return_stock_model->get_last_return_stock($this->modul['limit'], $offset)->result();
        $num_rows = $this->Return_stock_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_return_stock');
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
            $this->table->set_heading('No', 'Code', 'Stock Out', 'Date', 'Notes', 'Staff', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($return_stocks as $return_stock)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $return_stock->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'BPB-00'.$return_stock->no, 'BPBG-00'.$return_stock->stock_out, tgleng($return_stock->dates), $return_stock->desc, $return_stock->staff, $return_stock->log,
                    anchor($this->title.'/confirmation/'.$return_stock->id,'<span>update</span>',array('class' => $this->post_status($return_stock->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$return_stock->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$return_stock->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$return_stock->id.'/'.$return_stock->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'return_stock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $return_stocks = $this->Return_stock_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Stock Out', 'Date', 'Notes', 'Staff', 'Log', 'Action');

        $i = 0;
        foreach ($return_stocks as $return_stock)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $return_stock->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'BPB-00'.$return_stock->no, 'BPBG-00'.$return_stock->stock_out, tgleng($return_stock->dates), $return_stock->desc, $return_stock->staff, $return_stock->log,
                anchor($this->title.'/confirmation/'.$return_stock->id,'<span>update</span>',array('class' => $this->post_status($return_stock->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$return_stock->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$return_stock->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$return_stock->id.'/'.$return_stock->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class;
    }

    function confirmation($pid)
    {
        $return_stock = $this->Return_stock_model->get_return_stock_by_id($pid)->row();

        if ($return_stock->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!");
           redirect($this->title);
        }
        elseif($this->valid_period($return_stock->dates) == FALSE){ $this->session->set_flashdata('message', "Invalid Period..!"); }
        else
        {
//            cek qty di product 

            if ($this->cek_qty($return_stock->no) == FALSE)
            {
              $this->session->set_flashdata('message', "$this->title request wrong qty..!");
              redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Return_stock_model->update_id($pid, $data);

                //  update qty
               $this->update_qty($return_stock->no);

               // add wt
               $this->add_warehouse_transaction($return_stock->no);
               
               // create journal
               $balance = $this->Return_stock_item_model->total($return_stock->no);
               $this->create_po_journal($return_stock->dates, 'IDR', 'BPB-00'.$return_stock->no, $return_stock->no, $balance); // create journal

               $this->session->set_flashdata('message', "$this->title BPB-00$return_stock->no confirmed..!");
               redirect($this->title);
            }
        }   

    }
    
    private function create_po_journal($date,$currency,$code,$no,$amount)
    {
        $cm = new Control_model();
        
        $stock    = $cm->get_id(10); // stock
        $hpp      = $cm->get_id(20); // hpp
        
        $this->journalgl->new_journal('0'.$no,$date,'RST',$currency,$code,$amount, $this->session->userdata('log'));
        $jid = $this->journalgl->get_journal_id('RST','0'.$no);

        $this->journalgl->add_trans($jid,$hpp, 0, $amount); // kurang biaya 1 (hpp)
        $this->journalgl->add_trans($jid,$stock, $amount, 0); // tambah persediaan
    }

    private function add_warehouse_transaction($po)
    {
        $val  = $this->Return_stock_model->get_return_stock_by_no($po)->row();
        $list = $this->Return_stock_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->add($val->dates, 'BPB-00'.$po, 
                          $this->stock_out_item->get_currency($po),
                          $value->product,
                          $value->qty, 0,
                          $value->price,
                          $value->price*$value->qty,
                          $this->session->userdata('log'));

//           $this->product->add_qty($value->name,$value->qty);
        }
    }

    private function del_warehouse_transaction($po=0)
    {
        $val  = $this->Return_stock_model->get_return_stock_by_no($po)->row();
        $list = $this->Return_stock_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->remove($val->dates, 'BPB-00'.$po, $value->product);
//           $this->product->min_qty($value->name,$value->qty);
        }
    }

    function cek_qty($no)
    { $val = $this->Return_stock_item_model->get_last_item($no)->num_rows(); if ($val > 0){ return TRUE;} else { return FALSE;} }

    private function update_qty($no)
    {
        $value  = $this->Return_stock_model->get_return_stock_by_no($no)->row();
        $val = $this->Return_stock_item_model->get_last_item($no)->result();
        foreach ($val as $res)
        {
            $this->product->add_qty($res->product,$res->qty, $res->price*$res->qty);
            $this->product->add_stock($res->product,$value->dates,$res->qty, $res->price); 
        }
    }

    private function rollback_qty($no)
    {
        $value  = $this->Return_stock_model->get_return_stock_by_no($no)->row();
        $val = $this->Return_stock_item_model->get_last_item($no)->result();
        foreach ($val as $res)
        {
            $this->product->min_qty($res->product, $res->qty);
            $this->product->min_stock($res->product,$value->dates,$value->dates,$res->qty);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $return_stock = $this->Return_stock_model->get_return_stock_by_no($po)->row();

        if ( $return_stock->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - BPB-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================
    
    function cek_stock($po)
    {
        $val  = $this->Return_stock_model->get_return_stock_by_no($po)->row();
        $list = $this->Return_stock_item_model->get_last_item($po)->result();
        
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

    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Return_stock_model->get_return_stock_by_id($uid)->row();
        
        if ($this->valid_period($val->dates) == TRUE)
        {
          if ( $val->approved == 1 ) // cek journal harian sudah di approve atau belum
          {
              if ($this->cek_stock($po) != TRUE){ $this->session->set_flashdata('message', "Invalid Stock..!"); }
              else
              {
                $this->rollback($uid, $po);
                $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");  
              }
          }
          else
          {
              $this->remove($uid, $po);
              $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
          }  
        }
        else { $this->session->set_flashdata('message', "Invalid Period..!"); }
        
        redirect($this->title);
    }
    
    private function rollback($uid,$po)
    {
      $this->rollback_qty($po);
      $this->del_warehouse_transaction($po); // wt
      $this->journalgl->remove_journal('RST', '0'.$po); // journal gl 
    
      //edit approvaal
      $data = array('approved' => 0);
      $this->Return_stock_model->update_id($uid, $data);
    }
    
    private function remove($uid,$po)
    {
       $this->Return_stock_item_model->delete_po($po);
       $this->Return_stock_model->delete($uid); 
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        
        $data['code'] = $this->Return_stock_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('return_stock_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'return_stock_form';
	$data['form_action'] = site_url($this->title.'/add_process');

        $data['code'] = $this->Return_stock_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'BPB - No', 'required|numeric');
        $this->form_validation->set_rules('tbpbg', 'BPBG - No', 'required|numeric|callback_valid_stockout');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Workshop Staff', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $return_stock = array('no' => $this->input->post('tno'), 'stock_out' => $this->input->post('tbpbg'), 'approved' => 0, 
                                  'staff' => $this->input->post('tstaff'), 'desc' => $this->input->post('tnote'),
                                  'dates' => $this->input->post('tdate'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                                  'log' => $this->session->userdata('log'));
            
            $this->Return_stock_model->add($return_stock);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
        }
        else{ $this->load->view('return_stock_form', $data); }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        $data['code'] = $po;

        $return_stock = $this->Return_stock_model->get_return_stock_by_no($po)->row();

        $data['combo'] = $this->stock_out_item->combo($return_stock->stock_out);

        $data['stockout'] = $return_stock->stock_out;
        $data['default']['date'] = $return_stock->dates;
        $data['default']['staff'] = $return_stock->staff;
        $data['default']['note'] = $return_stock->desc;
        $data['user'] = $this->user->get_username($return_stock->user);

//        ============================ Purchase Item  =========================================
        $items = $this->Return_stock_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Return Qty', 'Amount', 'Balance', 'Desc', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), $item->qty.' '.$this->product->get_unit($item->product), number_format($item->price), number_format($item->qty*$item->price),  $item->desc,
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('return_stock_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('cproduct', 'Product', 'required|callback_valid_product['.$po.']');
        $this->form_validation->set_rules('tout', 'Out Qty', 'required|numeric');
        $this->form_validation->set_rules('treturn', 'Return Qty', 'required|numeric|callback_valid_qty');
        $this->form_validation->set_rules('tdesc', 'Desc', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('product' => $this->input->post('cproduct'), 'return_stock' => $po,
                           'qty' => $this->input->post('treturn'), 'price' => intval($this->input->post('treturn')*$this->input->post('tamount')),
                           'desc' => $this->input->post('tdesc'));

            $this->Return_stock_item_model->add($pitem);
            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Return_stock_item_model->delete($id); 
        $this->session->set_flashdata('message', "1 item successfully removed..!");
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
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tno', 'BPB - No', 'required|numeric');
        $this->form_validation->set_rules('tbpbg', 'BPBG - No', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Workshop Staff', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $return_stock = array('staff' => $this->input->post('tstaff'), 'desc' => $this->input->post('tnote'),
                                  'dates' => $this->input->post('tdate'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                                  'log' => $this->session->userdata('log'));

            $this->Return_stock_model->update($po, $return_stock);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('return_stock_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_stockout($no)
    {
        if ($this->Return_stock_model->valid_stockout($no) == FALSE)
        {
            $this->form_validation->set_message('valid_stockout', "Stock Out No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_qty($return)
    {
        $out = $this->input->post('tout');
        if ($out - $return < 0) { $this->form_validation->set_message('valid_qty', "Wrong Return Qty..!"); return FALSE; } else { return TRUE; }
    }

    public function valid_product($product,$stockout)
    {
       if ($this->Return_stock_item_model->valid_product($product,$stockout) == FALSE)
       {
          $this->form_validation->set_message('valid_product', "Product Already Listed..");
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
        $stockin = $this->Return_stock_model->get_return_stock_by_no($po)->row();

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

       $return_stock = $this->Return_stock_model->get_return_stock_by_no($po)->row();

       $data['no'] = $po;
       $data['stockout'] = $return_stock->stock_out;
       $data['podate'] = tgleng($return_stock->dates);
       $data['user'] = $this->user->get_username($return_stock->user);
       $data['staff'] = $return_stock->staff;

       $data['items'] = $this->Return_stock_item_model->report($po)->result();

       $this->load->view('return_stock_invoice', $data);
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
        
        $this->load->view('return_stock_report_panel', $data);
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

        $data['reports'] = $this->Return_stock_model->report($start,$end)->result();
        
        if ($this->input->post('ctype') == 0){ $this->load->view('return_stock_report', $data); }
        elseif ($this->input->post('ctype') == 1){ $this->load->view('return_stock_report_details', $data); }
    }


// ====================================== REPORT =========================================
    
// ====================================== AJAX =========================================
   
    function get_stock_out_qty()
    {
        $stockout = $this->input->post('stockout');
        $pro = $this->input->post('product');
//        echo $pro.'|'.$stockout;
        echo $this->stock_out_item->get_qty($pro, $stockout).'|'.$this->stock_out_item->get_price($pro, $stockout);
    }

}

?>