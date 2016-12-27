<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_adjustment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Sales_adjustment_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib();
        $this->unit = $this->load->library('unit_lib');
        $this->product = $this->load->library('products_lib');
        $this->user = $this->load->library('admin_lib');
        $this->sales = new Sales_lib();
        $this->journal = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');

    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title;
    private $user,$product,$sales,$journal,$currency,$unit,$journalgl;

    function index()
    {
        $this->get_last_sales_adjustment();
    }

    function get_last_sales_adjustment()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $sales_adjustments = $this->Sales_adjustment_model->get_last_sales_adjustment($this->modul['limit'], $offset)->result();
        $num_rows = $this->Sales_adjustment_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_sales_adjustment');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Sales', 'Currency', 'Notes', 'DP', 'Total', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($sales_adjustments as $sales_adjustment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'SAJ-00'.$sales_adjustment->no, tgleng($sales_adjustment->dates), 'SO-00'.$sales_adjustment->sales_no, $sales_adjustment->currency, $sales_adjustment->notes, number_format($sales_adjustment->dp), number_format($sales_adjustment->total), $sales_adjustment->log,
                    anchor($this->title.'/confirmation/'.$sales_adjustment->id,'<span>update</span>',array('class' => $this->post_status($sales_adjustment->approved), 'title' => 'edit / update')).' '.
                    anchor($this->title.'/add_trans/'.$sales_adjustment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$sales_adjustment->id.'/'.$sales_adjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'sales_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $sales_adjustments = $this->Sales_adjustment_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Sales', 'Currency', 'Notes', 'DP', 'Total', 'Log', 'Action');

        $i = 0;
        foreach ($sales_adjustments as $sales_adjustment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'SAJ-00'.$sales_adjustment->no, tgleng($sales_adjustment->dates), 'SO-00'.$sales_adjustment->sales_no, $sales_adjustment->currency, $sales_adjustment->notes, number_format($sales_adjustment->dp), number_format($sales_adjustment->total), $sales_adjustment->log,
                anchor($this->title.'/confirmation/'.$sales_adjustment->id,'<span>update</span>',array('class' => $this->post_status($sales_adjustment->approved), 'title' => 'edit / update')).' '.
                anchor($this->title.'/add_trans/'.$sales_adjustment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$sales_adjustment->id.'/'.$sales_adjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

        $stocks = $this->Sales_adjustment_model->get_list($this->input->post('tno'))->result();

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
        $this->load->view('sales_adjustment_list', $data);
        
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
        $sales_adjustment = $this->Sales_adjustment_model->get_sales_adjustment_by_id($pid)->row();

        if ($sales_adjustment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!");
           redirect($this->title);
        }
        elseif ($this->valid_sales($sales_adjustment->sales_no,$sales_adjustment->no) == FALSE) 
        { $this->session->set_flashdata('message', "Sales Order Can't 0 & Registered..!"); redirect($this->title); }
        else
        {
           $this->sales->delete_so_item($sales_adjustment->sales_no); // delete so item

           $sales = array('docno' => '', 'dates' => $sales_adjustment->dates, 'log' => 0, 'status' => 0, 'approved' => 0,
                         'tax' => 0, 'costs' => 0, 'p1' => 0, 'p2' => 0, 'total' => 0, 'discount' => 0, 'discount_desc' => '',
                         'notes' => '', 'desc' => '', 'shipping_date' => $sales_adjustment->dates);
           $this->sales->update($sales_adjustment->sales_no,$sales); // update so

           //  create journal
           $this->create_so_journal($sales_adjustment->dates, $sales_adjustment->currency, 'SO-00'.$sales_adjustment->sales_no.'-'.$sales_adjustment->notes, 'SJ',
                                    $sales_adjustment->sales_no, 'AR', $sales_adjustment->total, $sales_adjustment->dp);

           // create SAJ journal
           $this->journal->create_journal($sales_adjustment->dates, $sales_adjustment->currency, 'SAJ-00'.$sales_adjustment->no.'-'.$sales_adjustment->notes, 'SAJ',
                                          $sales_adjustment->no, 'AP', $sales_adjustment->total);
           
           $data = array('approved' => 1);
           $this->Sales_adjustment_model->update_id($pid, $data);

           $this->session->set_flashdata('message', "SAJ-00$sales_adjustment->no confirmed..!");
           redirect($this->title);
        }

    }

    private function create_so_journal($date,$currency,$code,$codetrans,$no,$type,$amount,$p1)
    {
        $amount = -$amount;
        if ($p1 > 0)
        {
           $p1 = -$p1;
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount);
           $this->journal->create_journal($date,$currency,$code.' (Cash) ','DS',$no,'AP', $p1);
        }
        else { $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount); }
    }
    
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Sales_adjustment_model->get_sales_adjustment_by_id($uid)->row();

        if ( $val->approved == 1 ) // cek journal harian sudah di approve atau belum
        {
            $this->journal->remove_journal('SAJ',$po); // delete journal
            $this->Sales_adjustment_model->delete($uid);
        }
        else
        { $this->Sales_adjustment_model->delete($uid); }

        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Sales_adjustment_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
        
        $this->load->view('sales_adjustment_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_adjustment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Sales_adjustment_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'SAJ - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tdocno', 'Document No', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales_adjustment = array('no' => $this->input->post('tno'), 'approved' => 0, 'docno' => $this->input->post('tdocno'),
                                      'currency' => $this->input->post('ccurrency'), 'dates' => $this->input->post('tdate'),
                                      'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                                      'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Sales_adjustment_model->add($sales_adjustment);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('sales_adjustment_form', $data);
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

        $sales_adjustment = $this->Sales_adjustment_model->get_sales_adjustment_by_no($po)->row();

        $data['default']['date'] = $sales_adjustment->dates;
        $data['desc'] = $sales_adjustment->desc;
        $data['default']['note'] = $sales_adjustment->notes;
        $data['default']['currency'] = $sales_adjustment->currency;
        $data['default']['docno'] = $sales_adjustment->docno;
        $data['default']['sales'] = $sales_adjustment->sales_no;
        $data['default']['total'] = $sales_adjustment->total;
        $data['default']['dp'] = $sales_adjustment->dp;
        $data['user'] = $this->user->get_username($sales_adjustment->user);

        $this->load->view('sales_adjustment_transform', $data);
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
        $this->form_validation->set_rules('tno', 'SAJ - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tsales', 'Sales No', 'required|callback_valid_sales['.$po.']');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('tdocno', 'Document No', '');
        $this->form_validation->set_rules('ttotal', 'Total Amount', 'required|numeric|callback_valid_total');

        if ($this->form_validation->run($this) == TRUE)
        {
             $sales_adjustment = array('docno' => $this->input->post('tdocno'), 'sales_no' => $this->input->post('tsales'),
                                       'dates' => $this->input->post('tdate'), 'total' => $this->input->post('ttotal'), 'dp' => $this->input->post('tdp'),
                                       'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                                       'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));

            $this->Sales_adjustment_model->update($po, $sales_adjustment);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('sales_adjustment_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_no($no)
    {
        if ($this->Sales_adjustment_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_confirmation($po)
    {
        $stockin = $this->Sales_adjustment_model->get_sales_adjustment_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - BPBG-00$po approved..!");
           return FALSE;
        }
        else { return TRUE; }
    }

    public function valid_sales($so,$po)
    {
        if ($so == 0)
        { $this->form_validation->set_message('valid_sales', "Sales Order can't 0..!"); return FALSE; }
        else
        {
            if ( $this->Sales_adjustment_model->valid_sales($so,$po) == FALSE )
            { $this->form_validation->set_message('valid_sales', "Sales Order SO-00$po Registered..!"); return FALSE; }
            else { return TRUE; }
        }
    }

    public function valid_total($val)
    {
        if ($val == 0) { $this->form_validation->set_message('valid_total', "Sales Order Total Should Not Be 0..!"); return FALSE; }
        else { return TRUE; }
    }


// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

        $this->load->view('sales_adjustment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $cur = $this->input->post('ccurrency');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['reports'] = $this->Sales_adjustment_model->report($start,$end,$cur)->result();
        $total = $this->Sales_adjustment_model->total($start,$end,$cur);
        $data['total'] = $total['total'];
        
        $this->load->view('sales_adjustment_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>
