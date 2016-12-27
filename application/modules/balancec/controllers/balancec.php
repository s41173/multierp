<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Balancec extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Account_model', 'am', TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->classification = $this->load->library('classification_lib');
        $this->account = $this->load->library('account_lib');
        $this->journal_gl = $this->load->library('journalgl_lib');
        $this->balancelib = new Balance_account_lib();

        $this->model = new Account();
    }

    private $properti, $modul, $title, $model, $account,$balancelib;
    private $user,$currency,$classification,$city,$journal_gl;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->fill_balance();  
      $this->get_last_balance();
    }
    
    private function fill_balance()
    {
       $ps = new Period();
       $bl = new Balance();
       $ps->get(); 
       
       if ($bl->where('month', $ps->start_month)->where('year', $ps->start_year)->count() == 0)
       {
          $accounts = $this->model->get();
          foreach ($accounts as $account){ $this->balancelib->fill($account->id, $ps->month, $ps->year, 0, 0); } 
       }
       
       $bl->where('account_id IS NULL')->delete();
    }
    
    private function get_balance($acc=null)
    {
        $ps = new Period();
        $bl = new Balance();
        $ps->get();
        
        $bl->where('account_id', $acc);
        $bl->where('month', $ps->start_month);
        $bl->where('year', $ps->start_year)->get(); 
        return $bl->beginning;
    }

    function get_last_balance()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'balance_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['prevperiod'] = $this->previous_month();

        $data['classification'] = $this->classification->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $accounts = $this->am->get_begin_saldo_account()->result();
       
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Cur', 'Code', 'Name', 'Beginning Balance', 'Action');

        $i = 0 + $offset;
        foreach ($accounts as $account)
        {
            $this->table->add_row
            (
                ++$i, $account->currency, $account->code, $account->name, number_format($this->get_balance($account->id)),
                anchor($this->title.'/update/'.$account->id,'<span>cost</span>',array('class' => 'cost', 'title' => ''))
            );
        }

        $data['table'] = $this->table->generate();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function update($uid)
    {
        $this->acl->otentikasi2($this->title);
        $acc = $this->model->where('id', $uid)->get();

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'balance_update';
	$data['form_action'] = site_url($this->title.'/update_process/'.$uid);
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));

        $data['user'] = $this->session->userdata("username");

        $data['default']['code'] = $acc->code;
        $data['default']['name'] = $acc->name;
        $data['default']['balance'] = $this->get_balance($uid);
        
        $this->load->view('balance_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process($acid=0)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'balance_update';
	$data['form_action'] = site_url($this->title.'/update_process/'.$acid);
	$data['link'] = array('link_back' => anchor('balance/','<span>back</span>', array('class' => 'back')));

        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tbalance', 'Balance', 'required|numeric|callback_valid_setting['.$acid.']');

        if ($this->form_validation->run($this) == TRUE)
        {                    
            $ps = new Period();
            $bl = new Balance();
            $ps->get();

            $bl->where('account_id', $acid);
            $bl->where('month', $ps->month);
            $bl->where('year', $ps->year)->get();
                        
            $bl->beginning = $this->input->post('tbalance');
            $bl->vamount = $this->journal_gl->calculate_account_amount($acid, $this->input->post('tbalance'));
            $bl->end = $this->input->post('tbalance');
            $bl->save();
            $this->update_historical();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$acid);
//            echo 'true';
        }
        else
        {
            $this->session->set_flashdata('message', "Balance can't change,Period is not appropriate..!");
            redirect($this->title.'/update/'.$acid);
//            $this->load->view('balance_update', $data);
//            echo validation_errors();
        }
    }
    
    private function update_historical()
    {
        $bl = new Balance();
        $ps = new Period();
        $ps->get();
        $val = 0;
        
        $bl->select_sum('vamount');
        $bl->where('month', $ps->month);
        $bl->where('year', $ps->year)->get();
        $val = $bl->vamount;
        $bl->clear();        
        
        $bls = new Balance();
        $bls->where('account_id', 23);
        $bls->where('month', $ps->month);
        $bls->where('year', $ps->year)->get();
        
        $bls->beginning = $val;
        $bls->vamount = 0;
        $bls->save();
    }
    
    // fungsi validasi berlaku jika period sesuai dengan tanggal start
    public function valid_setting($val,$acid)
    {
        $ps = new Period();
        $ps->get();
        
        if ($acid == 23)
        {
           $this->form_validation->set_message('valid_setting', "Balance can't change..!");
           return FALSE; 
        }
        elseif ( $ps->month != $ps->start_month || $ps->year != $ps->start_year )
        {
           $this->form_validation->set_message('valid_setting', "Period is not appropriate..!");
           return FALSE; 
        }
        else { return TRUE; }
        
    }

    public function valid_name($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_name', "Invalid Name..!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function validation_name($name)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_name', "Invalid Name..!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function validation_code($no)
    {
        $code = $this->input->post('tno').'-'.$no;
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('code', $code)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_code', "Invalid Code..!");
            return FALSE;
        }
        else{ return TRUE; }
    }


    public function valid_code($no)
    {
        $code = $this->input->post('tno').'-'.$no;
        $val = $this->model->where('code', $code)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_code', "Account No already registered.!");
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
        $this->load->view('balance_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);
       $balance = $this->Account_model->get_balance_by_no($po)->row();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $po;
       $data['logo'] = $this->properti['logo'];
       $data['podate'] = tgleng($balance->dates);
       $data['vendor'] = $balance->prefix.' '.$balance->name;
       $data['address'] = $balance->address;
       $data['city'] = $balance->city;
       $data['phone'] = $balance->phone1;
       $data['phone2'] = $balance->phone2;
       $data['desc'] = $balance->desc;
       $data['user'] = $this->user->get_username($balance->user);
       $data['currency'] = $balance->currency;
       $data['docno'] = $balance->docno;
       $data['log'] = $this->session->userdata('log');

       $data['cost'] = $balance->costs;
       $data['p2'] = $balance->p2;
       $data['p1'] = $balance->p1;

       $data['items'] = $this->Account_item_model->get_last_item($po)->result();

       // property display
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       if ($balance->approved != 1){ $this->load->view('rejected', $data); }
       else
       { if ($type) { $this->load->view('balance_invoice_blank', $data); } else { $this->load->view('balance_invoice', $data); } }

   }

   function print_expediter($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Expediter'.$this->modul['title'];

       $balance = $this->Account_model->get_balance_by_no($po)->row();

       $data['pono'] = $po;
       $data['podate'] = tgleng($balance->dates);
       $data['vendor'] = $balance->prefix.' '.$balance->name;
       $data['address'] = $balance->address;
       $data['shipdate'] = tgleng($balance->shipping_date);
       $data['city'] = $balance->city;
       $data['phone'] = $balance->phone1;
       $data['phone2'] = $balance->phone2;
       $data['desc'] = $balance->desc;
       $data['user'] = $this->user->get_username($balance->user);
       $data['currency'] = $this->currency->get_code($balance->currency);
       $data['docno'] = $balance->docno;

       $data['cost'] = $balance->costs;
       $data['p2'] = $balance->p2;
       $data['p1'] = $balance->p1;

       $data['items'] = $this->Account_item_model->get_last_item($po)->result();

       // property display
       $data['p_name'] = $this->properti['name'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('balance_expediter', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('balance/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('balance_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
        $status = $this->input->post('cstatus');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['balances'] = $this->Account_model->report($vendor,$cur,$start,$end,$status)->result();
        $total = $this->Account_model->total($vendor,$cur,$start,$end,$status);
        
        $data['total'] = $total['total'] - $total['tax'];
        $data['tax'] = $total['tax'];
        $data['p1'] = $total['p1'];
        $data['p2'] = $total['p2'];
        $data['costs'] = $total['costs'];
        $data['ptotal'] = $total['total'] + $total['costs'];


        if ($type == 'detail')
        { $this->load->view('balance_report_details', $data); }
        else {  $this->load->view('balance_report', $data); }
        
    }
    
    private function previous_month()
    {
        $ps = new Period();
        $ps = $ps->get();
        
        $prevmonth = 0;
        $prevyear = 0;
        
        if ($ps->start_month == 1){ $prevmonth = 12; $prevyear = intval($ps->start_year-1); }
        else { $prevmonth = intval($ps->start_month-1); $prevyear = $ps->start_year; }
        
        $totalday = get_total_days($prevmonth);
        
        return $totalday.'-'.$prevmonth.'-'.$prevyear;
    }


// ====================================== REPORT =========================================

}

?>