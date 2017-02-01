<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Demand extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Demand_model', '', TRUE);
        $this->load->model('Demand_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->product      = $this->load->library('products_lib');
        $this->user         = $this->load->library('admin_lib');
        $this->return_stock = $this->load->library('return_stock_lib');
        $this->wt           = $this->load->library('warehouse_transaction');
        $this->vendor       = $this->load->library('vendor_lib');
        $this->purchase     = new Purchase_lib();

    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title, $currency, $unit;
    private $user,$product,$return_stock,$wt,$vendor,$purchase;

    function index()
    {
        $this->get_last_demand();
    }

    function get_last_demand()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'demand_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $demands = $this->Demand_model->get_last_demand($this->modul['limit'], $offset)->result();
        $num_rows = $this->Demand_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_demand');
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
            foreach ($demands as $demand)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $demand->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'FPB-00'.$demand->no, tglin($demand->dates), $demand->desc, $demand->log,
                    anchor($this->title.'/confirmation/'.$demand->id,'<span>update</span>',array('class' => $this->post_status($demand->approved), 'title' => 'edit / update')).' &nbsp; |&nbsp; '.
                    anchor($this->title.'/release/'.$demand->id,'<span>update</span>',array('class' => $this->released_status($demand->released), 'title' => 'edit / update')).' &nbsp; '.
                    anchor_popup($this->title.'/print_invoice/'.$demand->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$demand->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$demand->id.'/'.$demand->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'demand_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $demands = $this->Demand_model->search($this->input->post('tno'), $this->input->post('tdate'), $this->input->post('ctype'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Log', 'Action');

        $i = 0;
        foreach ($demands as $demand)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $demand->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'FPB-00'.$demand->no, tgleng($demand->dates), $demand->desc, $demand->log,
                anchor($this->title.'/confirmation/'.$demand->id,'<span>update</span>',array('class' => $this->post_status($demand->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$demand->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$demand->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$demand->id.'/'.$demand->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['main_view'] = 'vendor_list';
        $data['currency'] = $this->currency->combo();
        $data['link'] = array('link_back' => anchor($this->title.'/get_list','<span>back</span>', array('class' => 'back')));


        $demands = $this->Demand_model->get_list()->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Action');

        $i = 0;
        foreach ($demands as $res)
        {
           $datax = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$res->no.'\',\'tdemand\')'
                         );

            $this->table->add_row
            (
                ++$i, 'FPB-00'.$res->no, tglin($res->dates), ucfirst($res->desc),
                form_button($datax)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('demand_list', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }
    
    private function released_status($val)
    {
       if ($val == 0) {$class = "credit"; }
       elseif ($val == 1){$class = "settled"; }
       return $class;
    }

    function confirmation($pid)
    {
        $demand = $this->Demand_model->get_demand_by_id($pid)->row();

        if ($demand->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
           $data = array('approved' => 1);
           $this->Demand_model->update_id($pid, $data);

           $this->session->set_flashdata('message', "$this->title FPB-00$demand->no confirmed..!");
           redirect($this->title);
        }

    }
    
    function release($pid)
    {
        $demand = $this->Demand_model->get_demand_by_id($pid)->row();

        if ($demand->approved == 0)
        {
           $this->session->set_flashdata('message', "$this->title unapproved, can't released..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
           $data = array('released' => 1);
           $this->Demand_model->update_id($pid, $data);

           $this->session->set_flashdata('message', "$this->title FPB-00$demand->no released..!");
           redirect($this->title);
        }

    }


    private function cek_confirmation($po=null,$page=null)
    {
        $demand = $this->Demand_model->get_demand_by_no($po)->row();

        if ( $demand->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - BPBG-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Demand_model->get_demand_by_id($uid)->row();
        
        if ($this->purchase->cek_relation($po, 'demand') == TRUE)
        {
          $this->Demand_item_model->delete_po($po);
          $this->Demand_model->delete($uid);

          $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "1 $this->title related to purchase module..!"); }
        
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Demand_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('demand_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'demand_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Demand_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'FPB - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('tsupervisor', 'Supervisor', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $demand = array('no' => $this->input->post('tno'), 'approved' => 0, 'supervisor' => $this->input->post('tsupervisor'),
                            'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'),
                            'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Demand_model->add($demand);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('demand_form', $data);
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

        $demand = $this->Demand_model->get_demand_by_no($po)->row();

        $data['default']['date'] = $demand->dates;
        $data['default']['note'] = $demand->desc;
        $data['default']['supervisor'] = $demand->supervisor;
        $data['user'] = $this->user->get_username($demand->user);

//        ============================ Purchase Item  =========================================
        $items = $this->Demand_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Qty', 'Desc', 'Date', 'Vendor', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), $item->qty.' '.$this->product->get_unit($item->product), $item->desc, tgleng($item->demand_date), $this->vendor->get_vendor_name($item->vendor),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('demand_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('tproduct', 'Item Name', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('tdesc', 'Desc', 'required');
        $this->form_validation->set_rules('tdemanddate', 'Demand Date', 'required');
        $this->form_validation->set_rules('tvendor', 'Vendor', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('product' => $this->product->get_id($this->input->post('tproduct')), 'demand' => $po,
                           'qty' => $this->input->post('tqty'), 'desc' => $this->input->post('tdesc'), 
                           'demand_date' => $this->input->post('tdemanddate'), 'vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')));

            $this->Demand_item_model->add($pitem);
            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Demand_item_model->delete($id); // memanggil model untuk mendelete data
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
        $this->form_validation->set_rules('tno', 'FPB - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('tsupervisor', 'Supervisor', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $demand = array('no' => $this->input->post('tno'), 'supervisor' => $this->input->post('tsupervisor'),
                            'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'),
                            'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));

            $this->Demand_model->update($po, $demand);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('demand_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_no($no)
    {
        if ($this->Demand_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }


    public function valid_confirmation($po)
    {
        $stockin = $this->Demand_model->get_demand_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - FPB-00$po approved..!");
           return FALSE;
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================
  
   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $demand = $this->Demand_model->get_demand_by_no($po)->row();

       $data['no'] = $po;
       $data['podate'] = tgleng($demand->dates);
       $data['user'] = $this->user->get_username($demand->user);
       $data['supervisor'] = $demand->supervisor;

       $data['items'] = $this->Demand_item_model->report($po)->result();

       $this->load->view('demand_invoice', $data);
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
        
        $this->load->view('demand_report_panel', $data);
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

        $data['reports'] = $this->Demand_model->report($start,$end,$type)->result();
        
        $this->load->view('demand_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>