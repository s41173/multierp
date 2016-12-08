<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assembly extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Assembly_model', '', TRUE);
        $this->load->model('Assembly_trans_in_model', 'inm', TRUE);
        $this->load->model('Assembly_trans_out_model', 'outm', TRUE);
        $this->load->model('A_cost_model', 'costmodel', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->vendor = $this->load->library('vendor_lib');
        $this->user = $this->load->library('admin_lib');
        $this->tax = $this->load->library('tax_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->product = $this->load->library('products_lib');
        $this->gproduct = $this->load->library('gproducts');
        $this->tproduct = $this->load->library('temporary_stock');
        $this->category = $this->load->library('gcategory');
        $this->wt = $this->load->library('warehouse_transaction');
        $this->tt = $this->load->library('temporary_stock_transaction');

    }

    private $properti, $modul, $title, $category;
    private $vendor,$user,$tax,$journal,$product,$gproduct,$tproduct,$wt,$tt,$currency,$unit,$journalgl;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
        $this->get_last_assembly();
    }

    function get_last_assembly()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'assembly_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $assemblys = $this->Assembly_model->get_last_assembly($this->modul['limit'], $offset)->result();
        $num_rows = $this->Assembly_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_assembly');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Project', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($assemblys as $assembly)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $assembly->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'AP-00'.$assembly->no, $assembly->currency, tgleng($assembly->dates), $assembly->notes, 'PRO-00'.$assembly->project, number_format($assembly->total),
                    anchor($this->title.'/confirmation/'.$assembly->id,'<span>update</span>',array('class' => $this->post_status($assembly->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$assembly->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$assembly->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$assembly->id.'/'.$assembly->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'assembly_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('assembly/','<span>back</span>', array('class' => 'back')));

        $assemblys = $this->Assembly_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Project', 'Total', 'Action');

        $i = 0;
        foreach ($assemblys as $assembly)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $assembly->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'AP-00'.$assembly->no, $assembly->currency, tgleng($assembly->dates), $assembly->notes, 'PRO-00'.$assembly->project, number_format($assembly->total),
                anchor($this->title.'/confirmation/'.$assembly->id,'<span>update</span>',array('class' => $this->post_status($assembly->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$assembly->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$assembly->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$assembly->id.'/'.$assembly->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
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
        $assembly = $this->Assembly_model->get_assembly_by_id($pid)->row();

        if ($assembly->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); 
           redirect($this->title);
        }
        elseif ($this->cek_trans($assembly->no) == FALSE )
        {
           $this->session->set_flashdata('message', "$this->title doesn't have transaction..!"); 
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($assembly->dates,$assembly->currency);
            $total = $assembly->total;
//
            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!");
              redirect($this->title);
            }
            else
            {
                // update stock + warehouse transaction
                // ======  production stock =============
                $this->change_prostock($assembly->no);

                // ======  rest stock =============
                $this->change_reststock($assembly->no);

                $data = array('approved' => 1);
                $this->Assembly_model->update_id($pid, $data);

                //  create journal
                $this->create_po_journal($assembly->dates, $assembly->currency, 'AP-00'.$assembly->no.'-'.$assembly->notes, 'AJ',
                                         $assembly->no, 'AP', $assembly->total, 0,0);
                
                // create journal gl
                $cm = new Control_model();
        
                $stock    = $cm->get_id(10);
                $stock2   = $cm->get_id(15);
                $bank     = $cm->get_id(28);
                $cost     = $cm->get_id(27);
                $hpp      = $cm->get_id(25);
                $ass      = $cm->get_id(26);
                
                $this->journalgl->new_journal($assembly->no,$assembly->dates,'AP',$assembly->currency, 'AP-0'.$assembly->no.'-'.$assembly->notes, $assembly->total, $this->session->userdata('log'));
                $jid = $this->journalgl->get_journal_id('AP',$assembly->no);
                
                $this->journalgl->add_trans($jid,$stock,0,$assembly->total-$assembly->costs); // stock - 
                $this->journalgl->add_trans($jid,$stock2,$assembly->total-$assembly->costs,0); // stock2 + 
                $this->journalgl->add_trans($jid,$cost,$assembly->costs,0); // cost +
                $this->journalgl->add_trans($jid,$bank,0, $assembly->costs); // bank -  
                $this->journalgl->add_trans($jid,$hpp,$assembly->total,0); // hpp +
                $this->journalgl->add_trans($jid,$ass,0,$assembly->total); // assemblies -  
//
               $this->session->set_flashdata('message', "$this->title AP-00$assembly->no confirmed..!");
               redirect($this->title);
            }
        }

    }

    private function change_prostock($po)
    {
        $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();
        $val = $this->inm->get_last_item($po)->result();

        foreach ($val as $res)
        {
           if ($res->warehouse == 0)
           {
               $this->product->min_qty($this->product->get_name($res->product),$res->qty, $res->amount); // product qty
               $this->wt->add($assembly->dates, 'AP-00'.$po, $assembly->currency, $res->product, 0, $res->qty, 0, 0, $this->session->userdata('log')); // wt
               $this->product->min_stock($res->product,$res->p_dates,$res->qty,$po,'A');
           }
           elseif ($res->warehouse == 1)
           {
               $this->tproduct->min_qty($res->product, $res->qty, $this->product->get_unit($res->product));
               $this->tt->add($assembly->dates, $res->product, $res->qty, $this->product->get_unit($res->product), 'out',
                              $this->session->userdata('username'), $this->session->userdata('username'), 1, $this->session->userdata('log'));
           }
        }

        // update g product
        $this->gproduct->add_qty($assembly->product, $assembly->qty);
    }

    private function unchange_prostock($po)
    {
        $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();
        $val = $this->inm->get_last_item($po)->result();

        foreach ($val as $res)
        {
           if ($res->warehouse == 0)
           {
               $this->product->add_qty($this->product->get_name($res->product),$res->qty, $res->amount); // product qty
               $this->wt->remove($assembly->dates, 'AP-00'.$po, $res->product); // wt
               $this->product->add_stock($res->product, $res->p_dates, $res->qty, $res->amount);
               $this->product->clean_assembly_stock_temp($po);
           }
           elseif ($res->warehouse == 1)
           {
               $this->tproduct->add_qty($res->product, $res->qty, $this->product->get_unit($res->product));
               $this->tt->add($assembly->dates, $res->product, $res->qty, $this->product->get_unit($res->product), 'in',
                              $this->session->userdata('username'), $this->session->userdata('username'), 1, $this->session->userdata('log'));
           }
        }

        // update g product
        $this->gproduct->min_qty($assembly->product, $assembly->qty);
    }

    private function change_reststock($po)
    {
        $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();
        $val = $this->outm->get_last_item($po)->result();

        foreach ($val as $res)
        {
               $this->tproduct->add_qty($res->product, $res->qty, $this->product->get_unit($res->product));
               $this->tt->add($assembly->dates, $res->product, $res->qty, $this->product->get_unit($res->product), 'in',
                              $this->session->userdata('username'), $this->session->userdata('username'), 1, $this->session->userdata('log'));
         
        }
    }

    private function unchange_reststock($po)
    {
        $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();
        $val = $this->outm->get_last_item($po)->result();

        foreach ($val as $res)
        {
               $this->tproduct->min_qty($res->product, $res->qty, $this->product->get_unit($res->product));
               $this->tt->add($assembly->dates, $res->product, $res->qty, $this->product->get_unit($res->product), 'out',
                              $this->session->userdata('username'), $this->session->userdata('username'), 1, $this->session->userdata('log'));

        }
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
        $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();

        if ( $assembly->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - AP-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }

    private function cek_trans($po)
    {
        $val = $this->inm->get_last_item($po)->num_rows();
        if ($val > 0){ return TRUE; } else { return FALSE; }
    }


//    ===================== approval ===========================================


    private function create_po_journal($date,$currency,$code,$codetrans,$no,$type,$amount,$p1,$p2)
    {
        if ($p1 > 0)
        {
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount);
           $this->journal->create_journal($date,$currency,$code.' (Cash) ','DP',$no,'AP', $p1);
        }
        else { $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount); }
    }


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Assembly_model->get_assembly_by_id($uid)->row();

        if ($val->approved == 1)
        {
            if ( $this->journal->cek_approval('AJ',$po) == TRUE ) // cek journal harian sudah di approve atau belum
            {
                $this->unchange_prostock($po);
                $this->unchange_reststock($po);

                $this->inm->delete_assembly($po);
                $this->outm->delete_assembly($po);
                $this->costmodel->delete_assembly($po);
                $this->Assembly_model->delete($uid); // memanggil model untuk mendelete data

                $this->journal->remove_journal('AJ',$po); // delete journal
                $this->journalgl->remove_journal('AP', $po); // delete journalgl
                $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            }
            else
            {
               $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!");
            } 
        }
        else
        {
            $this->inm->delete_assembly($po);
            $this->outm->delete_assembly($po);
            $this->costmodel->delete_assembly($po);
            $this->Assembly_model->delete($uid); 
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Assembly_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('assembly_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'assembly_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Assembly_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'PA - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tproject', 'Project', 'numeric');
        $this->form_validation->set_rules('tproduct', 'Product', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $assembly = array('no' => $this->input->post('tno'), 'dates' => $this->input->post('tdate'), 'docno' => $this->input->post('tdocno'),
                              'currency' => $this->input->post('ccurrency'), 'project' => $this->input->post('tproject'),
                              'product' => $this->gproduct->get_id($this->input->post('tproduct')), 'qty' => $this->input->post('tqty'),
                              'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'),
                              'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'));
            
            $this->Assembly_model->add($assembly);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('assembly_form', $data);
//            echo validation_errors();
        }

    }

    function get_product($cur='IDR')
    {
       $data['category'] = $this->category->combo_all();
       $wr  = $this->input->post('cwarehouse');
       $cat = $this->input->post('ccategory');

       $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
       $this->table->set_template($tmpl);
       $this->table->set_empty("&nbsp;");
       $this->table->set_heading('No', 'Code', 'Name / Model', 'Qty', 'Action');

       if (!$wr)
       {
           $products = $this->product->get_all()->result();
           $ware = 0;
           $pre = 'PRO-0';

           $i = 0;
           foreach ($products as $product)
           {
             $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$this->product->get_name($product->id).'|'.$ware.'\',\'titemproduct\')');

             $this->table->add_row
             (
               ++$i, $pre.$product->id, $this->product->get_name($product->id), $product->qty.' '.$product->unit,
               form_button($datax)
             );
           }

       }
       else
       {
           $products = $this->tproduct->get_all()->result();
           $ware = 1;
           $pre = 'TPRO-0';

           $i = 0;
           foreach ($products as $product)
           {
             $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$this->product->get_name($product->product).'|'.$ware.'\',\'titemproduct\')');

             $this->table->add_row
             (
               ++$i, $pre.$product->id, $this->product->get_name($product->product), $product->qty.' '.$product->unit,
               form_button($datax)
             );
           }
       }

       $data['table'] = $this->table->generate();
       $this->load->view('assembly_list', $data);
    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        $data['form_action_rest'] = site_url($this->title.'/add_rest_item/'.$po);
        $data['form_action_cost'] = site_url($this->title.'/add_cost/'.$po);
        $data['currency'] = $this->currency->combo();
        $data['unit'] = $this->unit->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();

        $data['default']['date'] = $assembly->dates;
        $data['default']['docno'] = $assembly->docno;
        $data['default']['currency'] = $assembly->currency;
        $data['default']['project'] = $assembly->project;
        $data['default']['product'] = $this->gproduct->get_name($assembly->product);
        $data['default']['qty'] = $assembly->qty;
        $data['default']['user'] = $this->user->get_username($assembly->user);
        $data['default']['total'] = $assembly->total;
        $data['default']['costs'] = $assembly->costs;
        $data['default']['instock'] = $assembly->total-$assembly->costs;
        $data['default']['note'] = $assembly->notes;
        $data['default']['desc'] = $assembly->desc;
        
//        ============================ Assembly Item  =========================================
        $items = $this->inm->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Item Name', 'Wh', 'Qty', 'Unit', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), $this->wr_name($item->warehouse), $item->qty, $this->product->get_unit($item->product), number_format($item->amount),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        $data['table'] = $this->table->generate();

//        =================================================================================
        
        //        ============================ Cost Type  =========================================
        $costs = $this->costmodel->get_last($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Notes', 'Amount', 'Action');

        $i = 0;
        foreach ($costs as $cost)
        {
            $this->table->add_row
            (
                ++$i, $cost->notes, number_format($cost->amount),
                anchor($this->title.'/delete_cost/'.$cost->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        $data['table3'] = $this->table->generate();

//        =================================================================================

//        ============================ Rest Item  =========================================
        $restitems = $this->outm->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Item Name', 'Qty', 'Unit', 'Action');

        $i = 0;
        foreach ($restitems as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), $item->qty, $item->unit,
                anchor($this->title.'/delete_rest_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        $data['table2'] = $this->table->generate();

//        =================================================================================
        
        $this->load->view('assembly_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {        
        $this->form_validation->set_rules('titem', 'Item Name', 'required|callback_valid_confirmation_item['.$po.']');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $res = $this->split_wh($this->input->post('titem'));
            
            $pitem = array('product' => $this->product->get_id($res[0]), 'p_dates' => $this->get_stock_dates($this->product->get_id($res[0])),
                           'assembly' => $po, 'qty' => $this->input->post('tqty'), 'warehouse' => $res[1],
                           'amount' => $this->get_amount($this->product->get_id($res[0]),$this->input->post('tqty'),$res[1]));
            $this->inm->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{ echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $totals = $this->inm->total($po);
        $totalcosts = $this->costmodel->total($po);
        $assembly = array('total' => $totals['amount']+$totalcosts, 'costs' => $totalcosts);
	$this->Assembly_model->update($po, $assembly);
    }
    
    private function get_stock_dates($product)
    {
       $val = $this->product->get_first_stock($product); 
       return $val->dates;;
    }

    private function get_amount($product,$qty,$wh)
    {
        $hpp = $this->product->get_first_stock($product);
        $hpp = intval($hpp->amount);
        if ($wh == 0) { $res = $hpp * $qty; }
        elseif ($wh == 1) { $res = $this->tproduct->get_price($product) * $qty; }
        return $res;
    }

    private function split_wh($val){ $res = explode("|",$val); return $res; }

    private function  wr_name($val) 
    { if ($val == 0){ $val = 'production'; } elseif ($val == 1) { $val = 'rest'; } return $val; }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->inm->delete($id);
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 item successfully removed..!");
        redirect($this->title.'/add_trans/'.$po);
    }

//    ==========================================================================================

//    ======================  Item Transaction   ===============================================================

    function add_rest_item($po=null)
    {

        $this->form_validation->set_rules('titem', 'Item Name', 'required|callback_valid_confirmation_item['.$po.']');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('cunit', 'Unit', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('product' => $this->product->get_id($this->input->post('titem')), 'assembly' => $po, 
			'qty' => $this->input->post('tqty'), 'unit' => $this->input->post('cunit'));
            $this->outm->add($pitem);
//            $this->update_trans($po);

            echo 'true';
        }
        else{ echo validation_errors(); }
    }

    function delete_rest_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);

        $this->outm->delete($id);
        $this->session->set_flashdata('message', "1 item successfully removed..!");
        redirect($this->title.'/add_trans/'.$po);
    }

//    ==========================================================================================
    
    //    ======================  Cost Transaction   ===============================================================

    function add_cost($po=null)
    {

        $this->form_validation->set_rules('tnotes', 'Cost Name', 'required|callback_valid_confirmation_item['.$po.']');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('notes' => $this->input->post('tnotes'), 'no' => $po, 'amount' => $this->input->post('tamount'));
            $this->costmodel->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{ echo validation_errors(); }
    }

    function delete_cost($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);

        $this->costmodel->delete($id);
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 item successfully removed..!");
        redirect($this->title.'/add_trans/'.$po);
    }

//    ==========================================================================================

    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('assembly/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tno', 'Order No', 'required|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tproject', 'Project', 'numeric');
        $this->form_validation->set_rules('tproduct', 'Product', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        $this->form_validation->set_rules('tcosts', 'Cost', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $assembly = array('dates' => $this->input->post('tdate'), 'docno' => $this->input->post('tdocno'),
                              'currency' => $this->input->post('ccurrency'), 'project' => $this->input->post('tproject'),
                              'product' => $this->gproduct->get_id($this->input->post('tproduct')), 'qty' => $this->input->post('tqty'),
                              'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'),
                              'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'));

            $this->Assembly_model->update($po, $assembly);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('assembly_transform', $data);
            echo validation_errors();
        }
    }
    
    // ===================================== VALIDATION ===========================================

    public function valid_no($no)
    {
        if ($this->Assembly_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
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

    public function valid_confirmation($no)
    {
        $assembly = $this->Assembly_model->get_assembly_by_no($no)->row();
        if ( $assembly->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Order No already approved.!");
           return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_confirmation_item($val,$no)
    {
        $assembly = $this->Assembly_model->get_assembly_by_no($no)->row();
        if ( $assembly->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation_item', "Order No already approved.!");
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

        //Set heading untuk table
//        $this->table->set_heading('Name', 'Action');
        $this->table->add_row('<h3>Faktur Pembelian</h3>', anchor_popup($this->title.'/print_invoice/'.$po,'Preview',$atts));
        $this->table->add_row('<h3>Expediter Status</h3>', anchor_popup($this->title.'/print_expediter/'.$po,'Preview',$atts));
//        $data['table'] = $this->table->generate();

        $data['pono'] = $po;
        $this->load->view('assembly_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);
       $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $po;
       $data['logo'] = $this->properti['logo'];
       $data['podate'] = tgleng($assembly->dates);
       $data['desc'] = $assembly->desc;
       $data['notes'] = $assembly->notes;
       $data['user'] = $this->user->get_username($assembly->user);
       $data['currency'] = $this->currency->get_code($assembly->currency);
       $data['project'] = 'PRJ-00'.$assembly->project;
       $data['docno'] = $assembly->docno;
       $data['product'] = $this->gproduct->get_name($assembly->product);
       $data['qty'] = $assembly->qty;
       $data['unit'] = $this->gproduct->get_unit($assembly->product);
       $data['log'] = $this->session->userdata('log');
       $data['total'] = $assembly->total;

       if ($assembly->approved == 1){ $stts = 'Approved'; } else{ $stts = 'Not Approved'; }
       $data['status'] = $stts;

       $data['items'] = $this->inm->get_last_item($po)->result();
       $data['outitems'] = $this->outm->get_last_item($po)->result();

       // property display
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       if ($assembly->approved != 1){ $this->load->view('rejected', $data); }
       else
       { if ($type) { $this->load->view('assembly_invoice_blank', $data); } else { $this->load->view('assembly_invoice', $data); } }

   }

   function print_expediter($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Expediter'.$this->modul['title'];

       $assembly = $this->Assembly_model->get_assembly_by_no($po)->row();

       $data['pono'] = $po;
       $data['podate'] = tgleng($assembly->dates);
       $data['vendor'] = $assembly->prefix.' '.$assembly->name;
       $data['address'] = $assembly->address;
       $data['shipdate'] = tgleng($assembly->shipping_date);
       $data['city'] = $assembly->city;
       $data['phone'] = $assembly->phone1;
       $data['phone2'] = $assembly->phone2;
       $data['desc'] = $assembly->desc;
       $data['user'] = $this->user->get_username($assembly->user);
       $data['currency'] = $this->currency->get_code($assembly->currency);
       $data['docno'] = $assembly->docno;

       $data['cost'] = $assembly->costs;
       $data['p2'] = $assembly->p2;
       $data['p1'] = $assembly->p1;

       $data['items'] = $this->Assembly_item_model->get_last_item($po)->result();

       // property display
       $data['p_name'] = $this->properti['name'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('assembly_expediter', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('assembly/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('assembly_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['assemblys'] = $this->Assembly_model->report($cur,$start,$end)->result();
        $total = $this->Assembly_model->total($cur,$start,$end);
        
        $data['total'] = $total['total'];
        $this->load->view('assembly_report', $data);

    }


// ====================================== REPORT =========================================

}

?>