<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_adjustment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ar_adjustment_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->product = $this->load->library('products_lib');
        $this->user = $this->load->library('admin_lib');
        $this->sales = $this->load->library('sales_lib');
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
        $this->get_last_ar_adjustment();
    }

    function get_last_ar_adjustment()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ar_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $ar_adjustments = $this->Ar_adjustment_model->get_last_ar_adjustment($this->modul['limit'], $offset)->result();
        $num_rows = $this->Ar_adjustment_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_ar_adjustment');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Sales', 'Currency', 'Notes', 'Total', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($ar_adjustments as $ar_adjustment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ar_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'ARJ-00'.$ar_adjustment->no, tgleng($ar_adjustment->dates), 'SO-00'.$ar_adjustment->sales_no, $ar_adjustment->currency, $ar_adjustment->notes, number_format($ar_adjustment->total), $ar_adjustment->log,
                    anchor($this->title.'/confirmation/'.$ar_adjustment->id,'<span>update</span>',array('class' => $this->post_status($ar_adjustment->approved), 'title' => 'edit / update')).' '.
                    anchor($this->title.'/add_trans/'.$ar_adjustment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$ar_adjustment->id.'/'.$ar_adjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'ar_adjustment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $ar_adjustments = $this->Ar_adjustment_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Sales', 'Currency', 'Notes', 'Total', 'Log', 'Action');

        $i = 0;
        foreach ($ar_adjustments as $ar_adjustment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ar_adjustment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'ARJ-00'.$ar_adjustment->no, tgleng($ar_adjustment->dates), 'SO-00'.$ar_adjustment->sales_no, $ar_adjustment->currency, $ar_adjustment->notes, number_format($ar_adjustment->total), $ar_adjustment->log,
                anchor($this->title.'/confirmation/'.$ar_adjustment->id,'<span>update</span>',array('class' => $this->post_status($ar_adjustment->approved), 'title' => 'edit / update')).' '.
                anchor($this->title.'/add_trans/'.$ar_adjustment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$ar_adjustment->id.'/'.$ar_adjustment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

        $stocks = $this->Ar_adjustment_model->get_list($this->input->post('tno'))->result();

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
        $this->load->view('ar_adjustment_list', $data);
        
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
        $ar_adjustment = $this->Ar_adjustment_model->get_ar_adjustment_by_id($pid)->row();

        if ($ar_adjustment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!");
           redirect($this->title);
        }
        elseif ($this->valid_sales($ar_adjustment->sales_no,$ar_adjustment->no) == FALSE)
        { $this->session->set_flashdata('message', "Sales Order Can't 0 & Registered..!"); redirect($this->title); }
        else
        {
           $sales = array('status' => 1);
           $this->sales->update($ar_adjustment->sales_no,$sales); // update so

           // create SAJ journal
           $this->journal->create_journal($ar_adjustment->dates, $ar_adjustment->currency, 'ARJ-00'.$ar_adjustment->no.'-'.$ar_adjustment->notes, 'ARJ',
                                          $ar_adjustment->no, 'AP', $ar_adjustment->total);
           
           $data = array('approved' => 1);
           $this->Ar_adjustment_model->update_id($pid, $data);

           $this->session->set_flashdata('message', "ARJ-00$ar_adjustment->no confirmed..!");
           redirect($this->title);
        }

    }
    
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Ar_adjustment_model->get_ar_adjustment_by_id($uid)->row();

        if ( $val->approved == 1 ) // cek journal harian sudah di approve atau belum
        {
           $sales = array('status' => 0);
           $this->sales->update($val->sales_no,$sales); // update so

            $this->journal->remove_journal('ARJ',$po); // delete journal
            $this->Ar_adjustment_model->delete($uid);
        }
        else
        { $this->Ar_adjustment_model->delete($uid); }

        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Ar_adjustment_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();
        
        $this->load->view('ar_adjustment_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ar_adjustment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Ar_adjustment_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'ARJ - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $ar_adjustment = array('no' => $this->input->post('tno'), 'approved' => 0, 
                                   'currency' => $this->input->post('ccurrency'), 'dates' => $this->input->post('tdate'),
                                   'notes' => $this->input->post('tnote'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                                   'log' => $this->session->userdata('log'));
            
            $this->Ar_adjustment_model->add($ar_adjustment);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('ar_adjustment_form', $data);
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

        $ar_adjustment = $this->Ar_adjustment_model->get_ar_adjustment_by_no($po)->row();

        $data['default']['date'] = $ar_adjustment->dates;
        $data['default']['note'] = $ar_adjustment->notes;
        $data['default']['currency'] = $ar_adjustment->currency;
        $data['default']['sales'] = $ar_adjustment->sales_no;
        $data['default']['total'] = $ar_adjustment->total;
        $data['user'] = $this->user->get_username($ar_adjustment->user);

        $this->load->view('ar_adjustment_transform', $data);
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
        $this->form_validation->set_rules('tno', 'ARJ - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tsales', 'Sales No', 'required|callback_valid_sales['.$po.']');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('ttotal', 'Total Amount', 'required|numeric|callback_valid_total');

        if ($this->form_validation->run($this) == TRUE)
        {
             $ar_adjustment = array('sales_no' => $this->input->post('tsales'),
                                    'dates' => $this->input->post('tdate'), 'total' => $this->input->post('ttotal'),
                                    'notes' => $this->input->post('tnote'),
                                    'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));

            $this->Ar_adjustment_model->update($po, $ar_adjustment);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('ar_adjustment_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_no($no)
    {
        if ($this->Ar_adjustment_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_confirmation($po)
    {
        $stockin = $this->Ar_adjustment_model->get_ar_adjustment_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - ARJ-00$po approved..!");
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
            if ( $this->Ar_adjustment_model->valid_sales($so,$po) == FALSE )
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

        $this->load->view('ar_adjustment_report_panel', $data);
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

        $data['reports'] = $this->Ar_adjustment_model->report($start,$end,$cur)->result();
        $total = $this->Ar_adjustment_model->total($start,$end,$cur);
        $data['total'] = $total['total'];
        
        $this->load->view('ar_adjustment_report_details', $data);
    }


// ====================================== REPORT =========================================

}

?>
