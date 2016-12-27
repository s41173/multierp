<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Exstock extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Exstock_model', '', TRUE);
        $this->load->model('Exstock_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->load->library('unit_lib');
        $this->product      = new Products_lib();
        $this->user         = new Admin_lib(); 
        $this->return_stock = new Return_stock_lib();
        $this->wt           = new Warehouse_transaction();
        $this->vendor       = new Vendor_lib();
        $this->opname       = new Opname();

    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title,$currency;
    private $user,$product,$return_stock,$wt,$vendor,$opname;

    function index()
    {
        $this->get_last_exstock();
    }

    function get_last_exstock()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'exstock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $exstocks = $this->Exstock_model->get_last_exstock($this->modul['limit'], $offset)->result();
        $num_rows = $this->Exstock_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_exstock');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Vendor', 'Notes', 'Type', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($exstocks as $exstock)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $exstock->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'EX-00'.$exstock->no, tgleng($exstock->dates), $exstock->currency, $this->vendor->get_vendor_name($exstock->vendor), $exstock->desc, $exstock->type, $exstock->log,
                    anchor($this->title.'/confirmation/'.$exstock->id,'<span>update</span>',array('class' => $this->post_status($exstock->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$exstock->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$exstock->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$exstock->id.'/'.$exstock->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'exstock_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $exstocks = $this->Exstock_model->search($this->input->post('tno'), $this->input->post('tdate'), $this->input->post('ctype'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Vendor', 'Notes', 'Type', 'Log', 'Action');

        $i = 0;
        foreach ($exstocks as $exstock)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $exstock->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'EX-00'.$exstock->no, tgleng($exstock->dates), $exstock->currency, $this->vendor->get_vendor_name($exstock->vendor), $exstock->desc, $exstock->type, $exstock->log,
                anchor($this->title.'/confirmation/'.$exstock->id,'<span>update</span>',array('class' => $this->post_status($exstock->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$exstock->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$exstock->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$exstock->id.'/'.$exstock->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

        $stocks = $this->Exstock_model->get_list()->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Action');

        $i = 0;
        foreach ($stocks as $stock)
        {
          $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$stock->no.'\',\'tref\')');
          $this->table->add_row( ++$i, 'EX-00'.$stock->no, tgleng($stock->dates), $stock->desc, form_button($datax) );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('exstock_list', $data);
        
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
        $exstock = $this->Exstock_model->get_exstock_by_id($pid)->row();

        if ($exstock->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
//            cek qty di product 
            if ($this->cek_qty($exstock->no) == FALSE)
            {
              $this->session->set_flashdata('message', "$this->title request wrong qty..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Exstock_model->update_id($pid, $data);

                //  update qty
               $this->update_qty($exstock->no,$exstock->type);

               // add wt
               $this->add_warehouse_transaction($exstock->no,$exstock->type);

               $this->session->set_flashdata('message', "$this->title BPBG-00$exstock->no confirmed..!");
               redirect($this->title);
            }
        }

    }

    private function add_warehouse_transaction($po,$type=null)
    {
        $val  = $this->Exstock_model->get_exstock_by_no($po)->row();
        $list = $this->Exstock_item_model->get_last_item($po)->result();

        if ($type == 'OUT'){ foreach ($list as $value){ $this->wt->add($val->dates, 'EX-00'.$po, $val->currency, $value->product, 0, $value->qty, $this->product->get_price($value->product), $this->product->get_price($value->product)*$value->qty,  $this->session->userdata('log'));} }
        elseif ($type == 'IN'){ foreach ($list as $value){ $this->wt->add($val->dates, 'EX-00'.$po, $val->currency, $value->product, $value->qty, 0, $this->product->get_price($value->product), $this->product->get_price($value->product)*$value->qty, $this->session->userdata('log'));} }
    }

    private function del_warehouse_transaction($po=0)
    {
        $val  = $this->Exstock_model->get_exstock_by_no($po)->row();
        $list = $this->Exstock_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->remove($val->dates, 'EX-00'.$po, $value->product);
//           $this->product->min_qty($value->name,$value->qty);
        }
    }

    function cek_qty($no)
    {
        $val = $this->Exstock_item_model->get_last_item($no)->result();
        $result = null;
        foreach ($val as $res)
        {
            if ($this->product->valid_qty($res->product,$res->qty) == FALSE){ break; $result = FALSE; } else { $result = TRUE; }
        }
        return $result;
    }

    private function update_qty($no,$type=null)
    {
        $val = $this->Exstock_item_model->get_last_item($no)->result();
        if ($type == 'OUT'){ foreach ($val as $res) { $this->product->min_qty($this->product->get_name($res->product), $res->qty); } }
        elseif ($type == 'IN'){ foreach ($val as $res) { $this->product->add_qty($this->product->get_name($res->product), $res->qty); } }  
    }

    private function rollback_qty($no,$type=null)
    {
        $val = $this->Exstock_item_model->get_last_item($no)->result();
        if ($type == 'OUT'){ foreach ($val as $res) { $this->product->add_qty($this->product->get_name($res->product), $res->qty); } }
        elseif ($type == 'IN'){ foreach ($val as $res) { $this->product->min_qty($this->product->get_name($res->product), $res->qty); } }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $exstock = $this->Exstock_model->get_exstock_by_no($po)->row();

        if ( $exstock->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - BPBG-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Exstock_model->get_exstock_by_id($uid)->row();

        if ( $val->approved == 1 ) // cek journal harian sudah di approve atau belum
        {
            $this->rollback_qty($po,$val->type);
            $this->del_warehouse_transaction($po);
            $this->Exstock_item_model->delete_po($po);
            $this->Exstock_model->delete($uid);
        }
        else
        {
           $this->Exstock_item_model->delete_po($po);
           $this->Exstock_model->delete($uid);
        }

        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);

    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Exstock_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
        
        $this->load->view('exstock_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'exstock_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Exstock_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();

	// Form validation
        $this->form_validation->set_rules('tno', 'EX - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required|callback_valid_opname');
        $this->form_validation->set_rules('tref', 'Ref No', 'numeric|callback_valid_ref');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $exstock = array('no' => $this->input->post('tno'), 'vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')),
                             'approved' => 0, 'type' => $this->input->post('ctype'), 'ref' => $this->input->post('tref'),
                             'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'), 'currency' => $this->input->post('ccurrency'),
                             'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Exstock_model->add($exstock);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('exstock_form', $data);
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

        $exstock = $this->Exstock_model->get_exstock_by_no($po)->row();

        $data['default']['date'] = $exstock->dates;
        $data['default']['vendor'] = $this->vendor->get_vendor_name($exstock->vendor);
        $data['default']['note'] = $exstock->desc;
        $data['default']['currency'] = $exstock->currency;
        $data['ref'] = 'EX-00'.$exstock->ref;
        $data['type'] = $exstock->type;
        $data['user'] = $this->user->get_username($exstock->user);

//        ============================ Purchase Item  =========================================
        $items = $this->Exstock_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Qty', 'Desc', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), $item->qty.' '.$this->product->get_unit($item->product), $item->desc,
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('exstock_transform', $data);
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
            $pitem = array('product' => $this->product->get_id($this->input->post('tproduct')), 'exstock' => $po,
                           'qty' => $this->input->post('tqty'), 'desc' => $this->input->post('tdesc'));

            $this->Exstock_item_model->add($pitem);
            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Exstock_item_model->delete($id); // memanggil model untuk mendelete data
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
        $this->form_validation->set_rules('tno', 'EX - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $exstock = array('dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'),
                             'user' => $this->user->get_userid($this->input->post('tuser')),'log' => $this->session->userdata('log'));

            $this->Exstock_model->update($po, $exstock);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('exstock_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_ref($ref)
    {
        $type = $this->input->post('ctype');
        if ($type == 'IN')
        {
            if (!$ref)
            { $this->form_validation->set_message('valid_ref', "Ref No Required.!");  return FALSE; }
            else
            {
               if ($this->Exstock_model->get_exstock_by_ref($ref) == FALSE) { $this->form_validation->set_message('valid_ref', "Invalid Ref No..!"); return FALSE; }
               else { return TRUE; }
            }
        }
        else { return TRUE; }
    }

    public function valid_no($no)
    {
        if ($this->Exstock_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_qty($qty)
    {
        $pro = $this->product->get_details($this->input->post('tproduct'));
        $pqty = $pro->qty;

        if ($pqty - $qty < 0) { $this->form_validation->set_message('valid_qty', "Qty Not Enough..!"); return FALSE; } else { return TRUE; }
    }

    public function valid_confirmation($po)
    {
        $stockin = $this->Exstock_model->get_exstock_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - EX-00$po approved..!");
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

// ===================================== PRINT ===========================================
  
   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $exstock = $this->Exstock_model->get_exstock_by_no($po)->row();

       $data['no'] = $po;
       $data['podate'] = tgleng($exstock->dates);
       $data['user'] = $this->user->get_username($exstock->user);
       $data['type'] = $exstock->type;

       $data['items'] = $this->Exstock_item_model->report($po)->result();

       $this->load->view('exstock_invoice', $data);
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
        
        $this->load->view('exstock_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['reports'] = $this->Exstock_model->report($start,$end,$type)->result();
        
        $this->load->view('exstock_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>