<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Opname extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Opname_model', '', TRUE);
        $this->load->model('Opname_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->load->library('currency_lib');
        $this->load->library('unit_lib');
        $this->product      = new Products_lib();
        $this->user         = new Admin_lib();
        $this->return_stock = new Return_stock_lib();
        $this->wt           = new Warehouse_transaction();
    }

    private $atts = array('width'=> '500','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 500)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    private $properti, $modul, $title;
    private $user,$product,$return_stock,$wt;

    function index()
    {
        $this->get_last_opname();
    }

    function get_last_opname()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'opname_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $opnames = $this->Opname_model->get_last_opname($this->modul['limit'], $offset)->result();
        $num_rows = $this->Opname_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_opname');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($opnames as $opname)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $opname->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'SOP-00'.$opname->no, tglin($opname->dates), $opname->desc, $opname->log,
                    anchor($this->title.'/confirmation/'.$opname->id,'<span>update</span>',array('class' => $this->post_status($opname->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/prints/'.$opname->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$opname->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$opname->id.'/'.$opname->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'opname_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $opnames = $this->Opname_model->search($this->input->post('tno'), $this->input->post('tdate'), $this->input->post('ctype'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Log', 'Action');

        $i = 0;
        foreach ($opnames as $opname)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $opname->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'SOP-00'.$opname->no, tglin($opname->dates), $opname->desc, $opname->log,
                anchor($this->title.'/confirmation/'.$opname->id,'<span>update</span>',array('class' => $this->post_status($opname->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/prints/'.$opname->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$opname->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$opname->id.'/'.$opname->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }


    function get_list($date=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_list';
        $data['form_action'] = site_url($this->title.'/get_list');

        $stocks = $this->Opname_model->get_list($date)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Action');

        $i = 0;
        foreach ($stocks as $stock)
        {
          $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$stock->dates.'\',\'tbegin\')');
          $this->table->add_row( ++$i, 'SOP-00'.$stock->no, tgleng($stock->dates), $stock->desc, form_button($datax) );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('opname_list', $data);
        
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
        $opname = $this->Opname_model->get_opname_by_id($pid)->row();

        if ($opname->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
           $data = array('approved' => 1);
           $this->Opname_model->update_id($pid, $data);
           $this->session->set_flashdata('message', "$this->title SOP-00$opname->no confirmed..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $opname = $this->Opname_model->get_opname_by_no($po)->row();

        if ( $opname->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - SOP-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Opname_model->get_opname_by_id($uid)->row();

        $this->Opname_item_model->delete_po($po);
        $this->Opname_model->delete($uid);

        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Opname_model->counter();
        $data['user'] = $this->session->userdata("username");

        $data['date'] = date('Y-m-d');
        
        $this->load->view('opname_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'opname_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Opname_model->counter();
        $data['user'] = $this->session->userdata("username");

        $data['date'] = date('Y-m-d');

	// Form validation
        $this->form_validation->set_rules('tno', 'SOP - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_date|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('tsupervisor', 'Supervisor', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $opname = array('no' => $this->input->post('tno'),
                            'approved' => 0, 'supervisor' => $this->input->post('tsupervisor'),
                            'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'), 
                            'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Opname_model->add($opname);
            $this->add_list($this->input->post('tno'));
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('opname_form', $data);
//            echo validation_errors();
        }

    }

    private function add_list($po=null)
    {
        $products = $this->product->get_all()->result();
        foreach ($products as $value)
        {
          $item = array('opname' => $po, 
                        'product' => $value->id,
                        'end' => $this->product->get_qty($value->id),
                        'physical' => $this->product->get_qty($value->id),
                        'difference' => 0);
          $this->Opname_item_model->add($item);
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

        $opname = $this->Opname_model->get_opname_by_no($po)->row();

        $data['default']['date'] = $opname->dates;
        $data['default']['note'] = $opname->desc;
        $data['user'] = $this->user->get_username($opname->user);
        $data['supervisor'] = $opname->supervisor;


        // get product list
        $items = $this->Opname_item_model->get_last_item($po)->result();

//        ============================ Purchase Item  =========================================

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'End', 'Physical', 'Difference', 'Action');

        $attributes = array('class' => 'ajaxform3');

        $i = 0;
        foreach ($items as $item)
        {
//            form_open('opname/edit_item/'.$item->id.'/'.$item->end.'/'.$i.'/',$attributes);

            $data1 = array('name' => 'tpi'.$i, 'value' => $item->physical, 'maxlength' => '5', 'size' => '2');
            $data2 = array('name' => 'tdif'.$i, 'readonly' => 'readonly', 'value' => $item->difference, 'maxlength' => '5', 'size' => '2');

            $this->table->add_row
            (
                form_open('opname/edit_item/'.$item->id.'/'.$item->end.'/'.$i.'/',$attributes).
                ++$i,
                  $this->product->get_name($item->product),
                  $item->end.' '.$this->product->get_unit($item->product),
                  form_input($data1).' '.$this->product->get_unit($item->product),
                  form_input($data2).' '.$this->product->get_unit($item->product),
                  form_submit('mysubmit', 'SUBMIT').form_hidden('tpo', $po).
                form_close()
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('opname_transform', $data);
    }

    function edit_item($id,$end,$i)
    {
        $this->form_validation->set_rules("tpo", $this->title.' Order', 'required|callback_valid_confirmation');

        if ($this->form_validation->run($this) == TRUE)
        {
            $phsic = $this->input->post("tpi$i");
            $res = $phsic - $end;
            $pitem = array('physical' => $phsic, 'difference' => $res);
            $this->Opname_item_model->update($id, $pitem);
        }
        redirect($this->title.'/add_trans/'.$this->input->post('tpo'));
    }


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
        $this->form_validation->set_rules('tno', 'SOP - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tsupervisor', 'Supervisor', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $opname = array('desc' => $this->input->post('tnote'), 'supervisor' => $this->input->post('tsupervisor'),
                            'user' => $this->user->get_userid($this->input->post('tuser')),'log' => $this->session->userdata('log'));

            $this->Opname_model->update($po, $opname);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('opname_transform', $data);
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
        if ($this->Opname_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_date($date)
    {
        if ($this->Opname_model->valid_date($date) == FALSE)
        {
            $this->form_validation->set_message('valid_date', "Order already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_begindate($begindate)
    {
        if (!$begindate){ $begindate = NULL;}

        if ($this->Opname_model->valid_begindate($begindate) == FALSE)
        {
            $this->form_validation->set_message('valid_begindate', "Begin date [ ".$begindate." ] already registered.!");
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
        $stockin = $this->Opname_model->get_opname_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - SOP-00$po approved..!");
           return FALSE;
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================

   function prints($po=null)
   {
       $this->acl->otentikasi($this->title);       
       $data['h2title'] = 'Print Invoice'.$this->modul['title'];
       $data['pono'] = $po;
       $data['form_action'] = site_url($this->title.'/print_invoice/'.$po.'/');
       $this->load->view('opname_invoice_form', $data);
   }

   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);
       $data['h2title'] = 'Print Invoice'.$this->modul['title'];
       $opname = $this->Opname_model->get_opname_by_no($po)->row();

        if ( $opname->approved != 1 )
        {
           $this->session->set_flashdata('message', "Can't print invoice value - SOP-00$po not approved..!");
           redirect($this->title.'/prints/'.$po);
        }
        else
        {
           $data['no'] = $po;
           $data['podate'] = tgleng($opname->dates);
           $data['supervisor'] = $opname->supervisor;
           $data['user'] = $this->user->get_username($opname->user);
           $data['rundate'] = tgleng(date('Y-m-d'));
           $data['log'] = $this->session->userdata('log');

           $product = $this->product->get_id($this->input->post('tproduct'));
           $data['reports'] = $this->Opname_item_model->report($po,$product)->result();
           $this->load->view('opname_invoice_report', $data);
        }
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
        
        $this->load->view('opname_report_panel', $data);
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

        $data['reports'] = $this->Opname_model->report($start,$end,$type)->result();
        
        $this->load->view('opname_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>