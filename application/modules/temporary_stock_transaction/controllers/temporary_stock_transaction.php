<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Temporary_stock_transaction extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Temporary_stock_transaction_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->unit = $this->load->library('unit_lib');
        
        $this->product       = $this->load->library('products_lib');
        $this->user          = $this->load->library('admin_lib');
        $this->stock         = $this->load->library('temporary_stock');
    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title;
    private $user,$product,$stock,$unit;

    function index()
    {
        $this->get_last_temporary_stock_transaction();
    }

    function get_last_temporary_stock_transaction()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'temporary_stock_transaction_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $temporary_stock_transactions = $this->Temporary_stock_transaction_model->get_last_temporary_stock_transaction($this->modul['limit'], $offset)->result();
        $num_rows = $this->Temporary_stock_transaction_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_temporary_stock_transaction');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Type', 'Product', 'Qty', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($temporary_stock_transactions as $temporary_stock_transaction)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $temporary_stock_transaction->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'TSTR-00'.$temporary_stock_transaction->id, tgleng($temporary_stock_transaction->dates), strtoupper($temporary_stock_transaction->type), $this->product->get_name($temporary_stock_transaction->product), $temporary_stock_transaction->qty.' '.$temporary_stock_transaction->unit, $temporary_stock_transaction->log,
                    anchor($this->title.'/confirmation/'.$temporary_stock_transaction->id,'<span>update</span>',array('class' => $this->post_status($temporary_stock_transaction->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$temporary_stock_transaction->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/delete/'.$temporary_stock_transaction->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'temporary_stock_transaction_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $temporary_stock_transactions = $this->Temporary_stock_transaction_model->search($this->product->get_id($this->input->post('tproduct')),
                                                                                         $this->input->post('tdate'), $this->input->post('ctype'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Type', 'Product', 'Qty', 'Log', 'Action');

        $i = 0;
        foreach ($temporary_stock_transactions as $temporary_stock_transaction)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $temporary_stock_transaction->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'TSTR-00'.$temporary_stock_transaction->id, tgleng($temporary_stock_transaction->dates), strtoupper($temporary_stock_transaction->type), $this->product->get_name($temporary_stock_transaction->product), $temporary_stock_transaction->qty.' '.$temporary_stock_transaction->unit, $temporary_stock_transaction->log,
                anchor($this->title.'/confirmation/'.$temporary_stock_transaction->id,'<span>update</span>',array('class' => $this->post_status($temporary_stock_transaction->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$temporary_stock_transaction->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/delete/'.$temporary_stock_transaction->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

        $stocks = $this->Temporary_stock_transaction_model->get_list()->result();

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
        $this->load->view('temporary_stock_transaction_list', $data);
        
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
        $temporary_stock_transaction = $this->Temporary_stock_transaction_model->get_temporary_stock_transaction_by_id($pid)->row();

        if ($temporary_stock_transaction->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
//            cek qty di product 
            if ($this->cek_qty($temporary_stock_transaction->product,$temporary_stock_transaction->qty,$temporary_stock_transaction->unit,$temporary_stock_transaction->type) == FALSE)
            {
              $this->session->set_flashdata('message', "$this->title request wrong qty..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Temporary_stock_transaction_model->update($pid, $data);

                //  update qty
               $this->update_qty($temporary_stock_transaction->id,$temporary_stock_transaction->type);

               $this->session->set_flashdata('message', "$this->title TSTR-00$temporary_stock_transaction->id confirmed..!");
               redirect($this->title);
            }
        }

    }

    function cek_qty($product,$qty,$unit,$type)
    {
        if ($type == 'out')
        {
            $stockqty = $this->stock->get_qty($product,$unit);
            if ($qty > $stockqty){ return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

    private function update_qty($id,$type=null)
    {
        $val = $this->Temporary_stock_transaction_model->get_temporary_stock_transaction_by_id($id)->row();

        if ($type == 'out'){ echo $this->stock->min_qty($val->product,$val->qty,$val->unit); }
        elseif ($type == 'in'){ echo $this->stock->add_qty($val->product,$val->qty,$val->unit); }
    }

    private function rollback_qty($id,$type=null)
    {
        $val = $this->Temporary_stock_transaction_model->get_temporary_stock_transaction_by_id($id)->row();
        if ($type == 'out'){ $this->stock->add_qty($val->product,$val->qty,$val->unit); }
        elseif ($type == 'in'){ $this->stock->min_qty($val->product,$val->qty,$val->unit); }
    }

//    ===================== approval ===========================================


    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Temporary_stock_transaction_model->get_temporary_stock_transaction_by_id($uid)->row();

        if ( $val->approved == 1 ) // cek journal harian sudah di approve atau belum
        {
            $this->rollback_qty($uid,$val->type);
            $this->Temporary_stock_transaction_model->delete($uid);
        }
        else
        { $this->Temporary_stock_transaction_model->delete($uid); }

        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);

    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['user'] = $this->session->userdata("username");
        $data['unit'] = $this->unit->combo();

        
        $this->load->view('temporary_stock_transaction_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'temporary_stock_transaction_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['user'] = $this->session->userdata("username");
        $data['unit'] = $this->unit->combo();

	// Form validation
        $this->form_validation->set_rules('tproduct', 'Product', 'required|callback_valid_product');
        $this->form_validation->set_rules('ctype', 'Type', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('cunit', 'Unit', 'required');
        $this->form_validation->set_rules('tstaff', 'Workshop Staff', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $temporary_stock_transaction = array('product' => $this->product->get_id($this->input->post('tproduct')), 'type' => $this->input->post('ctype'),
                                                 'approved' => 0, 'staff' => $this->input->post('tstaff'), 'dates' => $this->input->post('tdate'),
                                                 'unit' => $this->input->post('cunit'), 'qty' => $this->input->post('tqty'),
                                                 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Temporary_stock_transaction_model->add($temporary_stock_transaction);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title);
            echo 'true';
        }
        else 
        {
            //  $this->load->view('temporary_stock_transaction_form', $data);
            echo validation_errors();
        }

    }


    public function valid_product($product)
    {
        $product = $this->product->get_id($product);
        $type = $this->input->post('ctype');
        $unit = $this->input->post('cunit');
        
        if ($type == 'out')
        {
            if ( $this->stock->valid_product($product,$unit) == FALSE || $this->stock->valid_qty($product,$unit) == FALSE )
            { $this->form_validation->set_message('valid_product', "Invalid / Qty.!");  return FALSE; }
            else
            { return TRUE; }
        }
        else { return TRUE; }
    }

    public function valid_qty($qty)
    {
        $pro = $this->product->get_details($this->input->post('tproduct'));
        $pqty = $pro->qty;
        if ($pqty - $qty < 0) { $this->form_validation->set_message('valid_qty', "Qty Not Enough..!"); return FALSE; } else { return TRUE; }
    }


// ===================================== PRINT ===========================================
  
   function print_invoice($pid=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $stock = $this->Temporary_stock_transaction_model->get_temporary_stock_transaction_by_id($pid)->row();

       $data['no'] = $pid;
       $data['podate'] = tgleng($stock->dates);
       $data['user'] = $this->user->get_username($stock->user);
       $data['type'] = strtoupper($stock->type);
       $data['product'] = $this->product->get_name($stock->product);
       $data['qty'] = $stock->qty.' '.$stock->unit;
       $data['staff'] = $stock->staff;
       

//       $data['items'] = $this->Temporary_stock_transaction_item_model->report($po)->result();

       $this->load->view('temporary_stock_transaction_invoice', $data);
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
        
        $this->load->view('temporary_stock_transaction_report_panel', $data);
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

        $data['reports'] = $this->Temporary_stock_transaction_model->report($start,$end,$type)->result();
        
        $this->load->view('temporary_stock_transaction_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>