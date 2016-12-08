<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_return extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Sales_return_model', '', TRUE);
        $this->load->model('Sales_return_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->unit = $this->load->library('unit_lib');
        $this->currency = $this->load->library('currency_lib');
        
        $this->vendor = $this->load->library('vendor_lib');
        $this->user = $this->load->library('admin_lib');
        $this->tax = $this->load->library('tax_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->product = $this->load->library('products_lib');
        $this->purchase = $this->load->library('purchase');
        $this->sitem = $this->load->library('csales_item');
        $this->wt = $this->load->library('warehouse_transaction');
        $this->sales = $this->load->library('csales');
        $this->ar = $this->load->library('car_payment');

    }

    private $properti, $modul, $title;
    private $vendor,$user,$tax,$journal,$product,$purchase,$sitem,$wt,$unit,$currency,$journalgl,$sales,$ar;

    private $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
        $this->get_last_sales_return();
    }

    function get_last_sales_return()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_return_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $sales_returns = $this->Sales_return_model->get_last_sales_return($this->modul['limit'], $offset)->result();
        $num_rows = $this->Sales_return_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_sales_return');
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
            $this->table->set_heading('No', 'Code', 'C-Sales', 'Date', 'Notes', 'Total', 'Balance', '#', 'Action');

            $i = 0 + $offset;
            foreach ($sales_returns as $sales_return)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales_return->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'SR-00'.$sales_return->no, 'CSO-00'.$sales_return->sales, tgleng($sales_return->dates), $sales_return->notes, number_format($sales_return->total + $sales_return->costs), number_format($sales_return->balance), $this->status($sales_return->status),
                    anchor($this->title.'/confirmation/'.$sales_return->id,'<span>update</span>',array('class' => $this->post_status($sales_return->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$sales_return->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$sales_return->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$sales_return->id.'/'.$sales_return->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'sales_return_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('sales_return/','<span>back</span>', array('class' => 'back')));

        $sales_returns = $this->Sales_return_model->search($this->input->post('tno'), $this->input->post('tpo'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'C-Sales', 'Date', 'Notes', 'Total', 'Balance', '#', 'Action');

        $i = 0;
        foreach ($sales_returns as $sales_return)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales_return->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'SR-00'.$sales_return->no, 'CSO-00'.$sales_return->sales, tgleng($sales_return->dates), $sales_return->notes, number_format($sales_return->total + $sales_return->costs), number_format($sales_return->balance), $this->status($sales_return->status),
                anchor($this->title.'/confirmation/'.$sales_return->id,'<span>update</span>',array('class' => $this->post_status($sales_return->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$sales_return->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$sales_return->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$sales_return->id.'/'.$sales_return->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function get_list($currency=null,$vendor=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'vendor_list';

        $sales_returns = $this->Sales_return_model->get_sales_return_list($currency,$vendor)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Total', 'Balance', 'Action');

        $i = 0;
        foreach ($sales_returns as $sales_return)
        {
           $data = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$sales_return->no.'\',\'treturn\')'
                         );

            $this->table->add_row
            (
                ++$i, 'PR-00'.$sales_return->no, tgleng($sales_return->dates), $sales_return->notes, number_format($sales_return->total), number_format($sales_return->balance),
                form_button($data)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('sales_return_list', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'C'; break; case 1: $val = 'S'; break; } return $val; }
    
//    ===================== approval ===========================================

    private function post_status($val)
    { if ($val == 0) {$class = "notapprove"; }elseif ($val == 1){$class = "approve"; } return $class; }

    function confirmation($pid)
    {
        $sales_return = $this->Sales_return_model->get_sales_return_by_id($pid)->row();
        $sales = $this->sales->get_so($sales_return->no);

        if ($sales_return->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!");
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($sales_return->dates,$sales->currency);
            $total = $sales_return->total;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!");
              redirect($this->title);
            }
            else if ( $this->validation_qty($sales_return->no) == FALSE )
            {
              $this->session->set_flashdata('message', "Invalid Return Qty..!"); 
              redirect($this->title);
            }
            else
            {
                $sales1 = $this->sales->get_so($sales_return->no);

                //  create journal
                $this->journal->create_journal( $sales_return->dates,
                                                $sales->currency,
                                                'SR-00'.$sales_return->no.'-'.$sales_return->notes,
                                                'SRJ', $sales_return->no, 'AP',
                                                $sales_return->balance);
                
                //create gl journal
                 $cm = new Control_model();
        
                 $landed   = $cm->get_id(1);
                 $tax      = $cm->get_id(9);
                 $stock    = $cm->get_id(10);
                 $sales    = $cm->get_id(19);
                 $sr       = $cm->get_id(24);
                 $hpp      = $cm->get_id(20);
                 $bank     = $cm->get_id(21);
                 
                 $this->journalgl->new_journal($sales_return->no,$sales_return->dates,'SR',$sales1->currency,
                                               'SR-00'.$sales_return->no.'-'.$sales_return->notes, $sales_return->balance, 
                                               $this->session->userdata('log'));
                 
                 $jid = $this->journalgl->get_journal_id('SR',$sales_return->no);
                 
                 if ($sales_return->cash == 0)
                 {
                    $this->journalgl->add_trans($jid,$stock,$this->get_stock_value($sales_return->no),0); // tambah persediaan
                    $this->journalgl->add_trans($jid,$hpp,0, $this->get_stock_value($sales_return->no)); // kurang hpp
                    $this->journalgl->add_trans($jid,$sales,$sales_return->balance,0); // kurang penjualan
                    $this->journalgl->add_trans($jid,$sr,0, $sales_return->balance); // tambah retur
                    
                    $datax = array('approved' => 1, 'status' => 0);
                 }
                 elseif ($sales_return->cash == 1)
                 {
                    $this->journalgl->add_trans($jid,$stock,$this->get_stock_value($sales_return->no),0); // tambah persediaan
                    $this->journalgl->add_trans($jid,$hpp,0, $this->get_stock_value($sales_return->no)); // kurang hpp
                    $this->journalgl->add_trans($jid,$sales,$sales_return->balance,0); // kurang penjualan
                    $this->journalgl->add_trans($jid,$bank,0, $sales_return->balance); // kurang bank
                    
                    $datax = array('approved' => 1, 'status' => 1);
                 }
//                 
                 $this->Sales_return_model->update_id($pid, $datax);
                 
                // create warehouse transaction
                $this->add_warehouse_transaction($sales_return->no);
//
               $this->session->set_flashdata('message', "SR-00$sales_return->no confirmed..!");
               redirect($this->title);
            }
        }

    }
    
    private function get_stock_value($srno)
    {
        $saless = $this->Sales_return_item_model->get_last_item($srno)->result();
        $val = 0;
        foreach ($saless as $value)
        {
            $val = $val + $value->qty * $this->product->get_out_stock($this->product->get_id($value->name),$srno);
        }
        return $val;  
    }

    private function add_warehouse_transaction($po=0)
    {
        $val  = $this->Sales_return_model->get_sales_return_by_no($po)->row();
        $list = $this->Sales_return_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->product->add_qty($value->name,$value->qty, $value->qty * $this->product->get_out_stock($this->product->get_id($value->name),$po));
           $this->product->min_stock_temp($this->product->get_id($value->name),$value->qty, $po);
           $this->wt->add($val->dates, 'SR-00'.$po, $this->product->get_id($value->name), 0, $value->qty, $this->session->userdata('log'));
        }
    }

    private function del_warehouse_transaction($po=0)
    {
        $val  = $this->Sales_return_model->get_sales_return_by_no($po)->row();
        $list = $this->Sales_return_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->product->min_qty($value->name, $value->qty, $value->qty * $this->product->get_out_stock($this->product->get_id($value->name),$po));
           $this->product->add_stock_temp($this->product->get_id($value->name),$value->qty,$po);
           $this->wt->remove($val->dates, 'SR-00'.$po, $this->product->get_id($value->name));
        }
    }

    private function validation_qty($po=0)
    {
       $val = $this->Sales_return_item_model->get_last_item($po)->result();
       foreach ($val as $res)
       {
           if ( $this->valid_stock($res->name,$res->qty) == FALSE){ return FALSE; break; } else { return TRUE; }
       }
    }

    private function valid_stock($pname,$qty)
    {
        $val = $this->product->get_details($pname);
        if ($qty > $val->qty){ return FALSE;} else { return TRUE;}
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
        $sales_return = $this->Sales_return_model->get_sales_return_by_no($po)->row();

        if ( $sales_return->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - PO-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $pr = $this->Sales_return_model->get_sales_return_by_no($po)->row();

        if ( $this->journal->cek_approval('SRJ',$po) == TRUE && $this->valid_period($pr->dates) == TRUE && $this->ar->cek_relation_trans($po,'no','SR') == TRUE ) // cek journal harian sudah di approve atau belum
        {
            $this->journal->remove_journal('SRJ',$po); // delete journal
            $this->journalgl->remove_journal('SR', $po); // journal gl
            $this->del_warehouse_transaction($po); // delete wt

            $this->Sales_return_item_model->delete_po($po); // model to delete sales_return item
            $this->Sales_return_model->delete($uid); // memanggil model untuk mendelete data

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
        if ($this->input->get('ctype')){ $data['st'] = 1; }else { $data['st'] = 0; }
        
        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process/'.$data['st']);
        $data['form_action_get'] = site_url($this->title.'/add');
        $data['code'] = $this->Sales_return_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('sales_return_form', $data);
    }

    function add_process($st=0)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_return_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	
        $data['code'] = $this->Sales_return_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();

	// Form validation
        $this->form_validation->set_rules('tpo', 'SO', 'required');
        $this->form_validation->set_rules('tno', 'SR - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales_return = array('sales' => $this->input->post('tpo'), 'no' => $this->input->post('tno'), 'status' => 0, 'docno' => $this->input->post('tdocno'),
                              'dates' => $this->input->post('tdate'), 'notes' => $this->input->post('tnote'), 'cash' => $st,
                              'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Sales_return_model->add($sales_return);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('sales_return_form', $data);
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
        $data['unit'] = $this->unit->combo();
        $data['tax'] = $this->tax->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $sales_return = $this->Sales_return_model->get_sales_return_by_no($po)->row();

        $data['product'] = $this->sitem->combo($sales_return->sales);

        $data['po'] = $sales_return->sales;
        $data['default']['date'] = $sales_return->dates;
        $data['default']['note'] = $sales_return->notes;
        $data['default']['user'] = $this->user->get_username($sales_return->user);
        $data['default']['docno'] = $sales_return->docno;

        $data['default']['tax']      = $sales_return->tax;
        $data['default']['totaltax'] = $sales_return->total;
        $data['default']['balance']  = $sales_return->total+$sales_return->costs;
        $data['default']['costs']    = $sales_return->costs;

//        ============================ Purchase Item  ===============================================
        $sitems = $this->sitem->get_last_item($sales_return->sales)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Item Name', 'Qty', 'Unit Price', 'Tax', 'Amount');

        $i = 0;
        foreach ($sitems as $sitem)
        {
            $this->table->add_row
            ( ++$i, $this->product->get_name($sitem->product), $sitem->qty, number_format($sitem->price), number_format($sitem->tax), number_format($sitem->amount+$sitem->tax));
        }

        $data['table2'] = $this->table->generate();

//        ============================ Sales_return Item  =========================================
        $items = $this->Sales_return_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Item Name', 'Qty', 'Unit', 'Unit price', 'Tax', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->name, $item->qty, $item->unit, number_format($item->price), number_format($item->tax), number_format($item->amount+$item->tax),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('sales_return_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        $pr = $this->Sales_return_model->get_sales_return_by_no($po)->row();
        $purchase = $this->purchase->get_po($pr->sales);
        
        $this->form_validation->set_rules('cproduct', 'Product', 'required|callback_valid_item['.$po.']|callback_valid_stock_transaction['.$purchase->dates.']');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('treturn', 'Return', 'required|numeric|callback_valid_qty');
        $this->form_validation->set_rules('tamount', 'Unit Price', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sitem = array('name' => $this->product->get_name($this->input->post('cproduct')), 'sales_return_id' => $po, 'qty' => $this->input->post('treturn'),
                           'unit' => $this->product->get_unit($this->input->post('cproduct')),
                           'price' => $this->input->post('tamount'), 'amount' => $this->input->post('treturn') * $this->input->post('tamount'),
                           'tax' => $this->tax->calculate($this->input->post('ctax'),$this->input->post('treturn'),$this->input->post('tamount')));
            $this->Sales_return_item_model->add($sitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $totals = $this->Sales_return_item_model->total($po);
        $sales_return = array('tax' => $totals['tax'], 'total' => $totals['amount'] + $totals['tax']);
	$this->Sales_return_model->update($po, $sales_return);
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Sales_return_item_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po);
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
	$data['link'] = array('link_back' => anchor('sales_return/','<span>back</span>', array('class' => 'back')));

	// Form validation

        $this->form_validation->set_rules('tpo', 'PO', 'required');
        $this->form_validation->set_rules('tno', 'PR - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');
        $this->form_validation->set_rules('tcosts', 'Landed Cost', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales_returns = $this->Sales_return_model->get_sales_return_by_no($po)->row();

            $sales_return = array('log' => $this->session->userdata('log'), 'docno' => $this->input->post('tdocno'),
                                     'dates' => $this->input->post('tdate'), 'notes' => $this->input->post('tnote'),
                                     'user' => $this->user->get_userid($this->input->post('tuser')), 'costs' => $this->input->post('tcosts'),
                                     'balance' => $this->input->post('tcosts')+$sales_returns->total
                             );

            $this->Sales_return_model->update($po, $sales_return);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('sales_return_transform', $data);
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

    public function valid_qty($return)
    {
        $qty = $this->input->post('tqty');
        if ($return > $qty)
        {
            $this->form_validation->set_message('valid_qty', "Invalid Return Qty.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function valid_no($no)
    {
        if ($this->Sales_return_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_item($product,$po)
    {
        $product = $this->product->get_name($product);
        if ($this->Sales_return_item_model->valid_item($product,$po) == FALSE)
        {
            $this->form_validation->set_message('valid_item', "Product already listed.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_stock_transaction($product,$date)
    {
        if ($this->product->get_stock($product,$date) == null)
        {
            $this->form_validation->set_message('valid_stock_transaction', "Stock not available..!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================
    
   function invoice($po=null)
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

        $data['pono'] = $po;
        $this->load->view('sales_return_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $sales_return = $this->Sales_return_model->get_sales_return_by_no($po)->row();

       $data['pono'] = $po;
       $data['logo'] = $this->properti['logo'];
       $data['logo'] = $this->properti['logo'];
       $data['podate'] = tgleng($sales_return->dates);
       $data['vendor'] = $sales_return->prefix.' '.$sales_return->name;
       $data['address'] = $sales_return->address;
       $data['city'] = $sales_return->city;
       $data['phone'] = $sales_return->phone1;
       $data['phone2'] = $sales_return->phone2;
       $data['user'] = $this->user->get_username($sales_return->user);
       $data['currency'] = $this->currency->get_code($sales_return->currency);
       $data['docno'] = $sales_return->docno;

       $data['cost'] = $sales_return->costs;
       $data['balance'] = $sales_return->balance;

       $data['items'] = $this->Sales_return_item_model->get_last_item($po)->result();

       // property display
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('sales_return_invoice', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('sales_return/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('sales_return_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $status = $this->input->post('cstatus');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['sales_returns'] = $this->Sales_return_model->report($cur,$start,$end,$status)->result();
        $total = $this->Sales_return_model->total($cur,$start,$end,$status);
        
        $data['total'] = $total['total'] - $total['tax'];
        $data['tax'] = $total['tax'];
        $data['costs'] = $total['costs'];
        $data['balance'] = $total['total'] + $total['costs'];

        $this->load->view('sales_return_report_details', $data);
        
    }


// ====================================== REPORT =========================================

}

?>