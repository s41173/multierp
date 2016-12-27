<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Accountc extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Account_model', '', TRUE);
        
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
        $this->product = $this->load->library('products_lib');
        $this->classification = $this->load->library('classification_lib');
        $this->city = $this->load->library('city_lib');
        $this->account = $this->load->library('account_lib');

        $this->model = new Account();
    }

    private $properti, $modul, $title, $model, $account;
    private $vendor,$user,$tax,$journal,$product,$currency,$unit,$classification,$city;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last_account();
    }

    function get_last_account()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['classification'] = $this->classification->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $accounts = $this->model->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_account');
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
            $this->table->set_heading('No', 'Cur', 'Code', 'Name', 'Sub Classification', 'Classification', 'Action');

            $i = 0 + $offset;
            foreach ($accounts as $account)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $account->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                $account->classification->get();

                $this->table->add_row
                (
                    ++$i, $account->currency, $account->code, $account->name, $account->classification->name, $account->classification->type,
                    anchor($this->title.'/cost/'.$account->id,'<span>cost</span>',array('class' => 'cost', 'title' => '')).' '.
                    anchor('ledger/get/'.$account->code,'<span>invoice</span>',array('class' => 'invoice', 'title' => '')).' '.
                    anchor($this->title.'/update/'.$account->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$account->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    private function get_search($code=null,$name=null,$class=null)
    {
        if ($code){ $this->model->where('code', $code); }
        elseif ($name){ $this->model->where('name', $name); }
        elseif ($class) { $this->model->where('classification_id', $class); }
        return $this->model->get();
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'account_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['classification'] = $this->classification->combo_all();

        $accounts = $this->get_search($this->input->post('tcode'), $this->input->post('tname'), $this->input->post('cclassification'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Cur', 'Code', 'Name', 'Sub Classification', 'Classification', 'Action');

        $i = 0;
        foreach ($accounts as $account)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $account->id,'checked'=> FALSE, 'style'=> 'margin:0px');
            $account->classification->get();

            $this->table->add_row
            (
                ++$i, $account->currency, $account->code, $account->name, $account->classification->name, $account->classification->type,
                anchor($this->title.'/ledger/'.$account->code,'<span>invoice</span>',array('class' => 'invoice', 'title' => '')).' '.
                anchor($this->title.'/cost/'.$account->id,'<span>cost</span>',array('class' => 'cost', 'title' => '')).' '.
                anchor($this->title.'/update/'.$account->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$account->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }
    
    private function get_balance($acc=null)
    {
        $ps = new Period();
        $gl = new Gl();
        $ps->get();
        
        $gl->where('approved', 1);
        $gl->where('MONTH(dates)', $ps->month);
        $gl->where('YEAR(dates)', $ps->year)->get();
        
        $this->load->model('Account_model','am',TRUE);
        $val = $this->am->get_balance($acc,$ps->month,$ps->year)->row_array();
        return $val['vamount'];
    }

    private function get_cost($acc=null,$month=0)
    {
        $ps = new Period();
        $bl = new Balance();
        $ps->get();
        
        $bl->where('account_id', $acc);
        $bl->where('month', $month);
        $num = $bl->where('year', $ps->year)->count();

        $val = null;
        if ( $num > 0)
        {
           $bl->where('account_id', $acc);
           $bl->where('month', $month);
           $bl->where('year', $ps->year)->get(); 
            
           $val[0] = get_month($month);
           $val[1] = $ps->year;
           $val[2] = $bl->beginning + $this->get_balance($acc);
        }
        else
        {
           $val[0] = get_month($month);
           $val[1] = $ps->year;
           $val[2] = 0; 
        }

        return $val;
    }

    function cost($acc = null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Account Balance '.ucwords($this->modul['title']);
        $data['h2title'] = 'Account Balance '.$this->modul['title'];
        $data['main_view'] = 'account_balance';
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['accname'] = $this->account->get_name($acc);
        $data['acccur'] = $this->account->get_cur($acc);

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('Month', 'Year', 'Budget');
        
        $account = null;
        for ($x=1; $x<=12; $x++)
        {
           $account[$x] = $this->get_cost($acc,$x);
           $this->table->add_row
           (
               $account[$x][0], $account[$x][1], number_format($account[$x][2])
           );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('account_balance', $data);
    }

    function get_list($code=null,$currency=null,$target='titem')
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['main_view'] = 'vendor_list';
        $data['currency'] = $this->currency->combo();
        $data['link'] = array('link_back' => anchor($this->title.'/get_list','<span>back</span>', array('class' => 'back')));

        $currency = $this->input->post('ccurrency');
        $code = $this->input->post('tcode');

        if($code){ $this->model->where('code', $code); }
        elseif ($currency){ $this->model->where('currency', $currency); }
        $accounts = $this->model->order_by('code','asc')->get();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Name', 'Cur', 'Action');

        $i = 0;
        foreach ($accounts as $account)
        {
           $datax = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$account->code.'\',\''.$target.'\')'
                         );

            $this->table->add_row
            (
                ++$i, $account->code, $account->name, $account->currency,
                form_button($datax)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('account_list', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $bl = new Balance();

        if ( $this->cek_relation($uid) == TRUE && $this->valid_default($uid) == TRUE )
        {
            $bl->where('account_id', $uid)->get();
            $bl->delete_all();
            
            $this->model->where('id', $uid)->get();
            $this->model->delete();
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    private function cek_relation($id)
    {
        $tl = new Transaction();
        $res = $tl->where('account_id', $id)->count();
        if ($res == 0){ return TRUE; }else { return FALSE; }
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['classification'] = $this->classification->combo_all();
        $data['city'] = $this->city->combo();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('account_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['classification'] = $this->classification->combo_all();
        $data['city'] = $this->city->combo();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_name');
        $this->form_validation->set_rules('tno', 'No', 'required|numeric');
        $this->form_validation->set_rules('tcode', 'Code', 'required|numeric|callback_valid_code');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('cclassification', 'Classification', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $this->model->classification_id  = $this->input->post('cclassification');
            $this->model->currency           = $this->input->post('ccurrency');
            $this->model->code               = $this->input->post('tno').'-'.$this->input->post('tcode');
            $this->model->name               = $this->input->post('tname');
            $this->model->alias              = $this->input->post('talias');
            $this->model->acc_no             = $this->input->post('taccno');
            $this->model->bank               = $this->input->post('tbank');
            $this->model->city               = $this->input->post('ccity');
            $this->model->phone              = $this->input->post('tphone');
            $this->model->fax                = $this->input->post('tfax');
            $this->model->zip                = $this->input->post('tzip');
            $this->model->contact            = $this->input->post('tcontact');
            $this->model->balance_phone      = $this->input->post('tbalancephone');
            $this->model->status             = $this->input->post('cactive');
            
            if ($this->input->post('cclassification') == 7 || $this->input->post('cclassification') == 8)
            { $this->model->bank_stts = 1; }
            else { $this->model->bank_stts  = $this->input->post('cbank'); }
            
            $this->model->save();
            $this->create_balance($this->input->post('tno').'-'.$this->input->post('tcode'));

//            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
            echo 'true';
        }
        else
        {
//              $this->load->view('account_form', $data);
            echo validation_errors();
        }

    }
    
    private function create_balance($code=null)
    {
        $bl = new Balance();
        $ps = new Period();
        $ps->get();
        
        $this->model->where('code', $code)->get();
        
        $bl->account_id = $this->model->id;
        $bl->beginning = 0;
        $bl->end = 0;
        $bl->month = $ps->month;
        $bl->year = $ps->year;
        $bl->save();
    }

    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['classification'] = $this->classification->combo();
        $data['city'] = $this->city->combo();
        $data['user'] = $this->session->userdata("username");

        $acc = $this->model->where('id', $uid)->get();

        $data['default']['classification'] = $acc->classification_id;
        $data['default']['currency']       = $acc->currency;

        $code = explode('-', $acc->code);
        $data['default']['no']             = $code[0];
        $data['default']['code']           = $code[1];
        
        $data['default']['name']           = $acc->name;
        $data['default']['alias']          = $acc->alias;
        $data['default']['accno']          = $acc->acc_no;
        $data['default']['bank']           = $acc->bank;
        $data['default']['city']           = $acc->city;
        $data['default']['phone']          = $acc->phone;
        $data['default']['zip']            = $acc->zip;
        $data['default']['contact']        = $acc->contact;
        $data['default']['fax']            = $acc->fax;
        $data['default']['balancephone']   = $acc->balance_phone;
        
        $stts = FALSE; if($acc->status){ $stts = TRUE; }
        $data['default']['status'] = $stts;
        
        $bank = FALSE; if($acc->bank_stts){ $bank = TRUE; }
        $data['default']['bank'] = $bank;
        

	$this->session->set_userdata('curid', $acc->id);
        $this->load->view('account_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('account/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['classification'] = $this->classification->combo();
        $data['city'] = $this->city->combo();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validation_name');
        $this->form_validation->set_rules('tno', 'No', 'required|numeric');
        $this->form_validation->set_rules('tcode', 'Code', 'required|numeric|callback_validation_code|callback_valid_default');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('cclassification', 'Classification', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->classification_id  = $this->input->post('cclassification');
            $this->model->currency           = $this->input->post('ccurrency');
            $this->model->code               = $this->input->post('tno').'-'.$this->input->post('tcode');
            $this->model->name               = $this->input->post('tname');
            $this->model->alias              = $this->input->post('talias');
            $this->model->acc_no             = $this->input->post('taccno');
            $this->model->bank               = $this->input->post('tbank');
            $this->model->city               = $this->input->post('ccity');
            $this->model->phone              = $this->input->post('tphone');
            $this->model->fax                = $this->input->post('tfax');
            $this->model->zip                = $this->input->post('tzip');
            $this->model->contact            = $this->input->post('tcontact');
            $this->model->balance_phone      = $this->input->post('tbalancephone');
            $this->model->status             = $this->input->post('cactive');
            
            if ($this->input->post('cclassification') == 7 || $this->input->post('cclassification') == 8)
            { $this->model->bank_stts = 1; }
            else { $this->model->bank_stts  = $this->input->post('cbank'); }
            
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true';
        }
        else
        {
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->load->view('account_update', $data);
//            echo validation_errors();
        }
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
    
    public function valid_default($name=null)
    {
        $val = $this->model->where('id', $this->session->userdata('curid'))->get();

        if ($val->default == 1)
        {
            $this->form_validation->set_message('valid_default', "Default Account - [Can't Changed]..!");
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
        $this->load->view('account_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);
       $account = $this->Account_model->get_account_by_no($po)->row();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $po;
       $data['logo'] = $this->properti['logo'];
       $data['podate'] = tgleng($account->dates);
       $data['vendor'] = $account->prefix.' '.$account->name;
       $data['address'] = $account->address;
       $data['city'] = $account->city;
       $data['phone'] = $account->phone1;
       $data['phone2'] = $account->phone2;
       $data['desc'] = $account->desc;
       $data['user'] = $this->user->get_username($account->user);
       $data['currency'] = $account->currency;
       $data['docno'] = $account->docno;
       $data['log'] = $this->session->userdata('log');

       $data['cost'] = $account->costs;
       $data['p2'] = $account->p2;
       $data['p1'] = $account->p1;

       $data['items'] = $this->Account_item_model->get_last_item($po)->result();

       // property display
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       if ($account->approved != 1){ $this->load->view('rejected', $data); }
       else
       { if ($type) { $this->load->view('account_invoice_blank', $data); } else { $this->load->view('account_invoice', $data); } }

   }

   function print_expediter($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Expediter'.$this->modul['title'];

       $account = $this->Account_model->get_account_by_no($po)->row();

       $data['pono'] = $po;
       $data['podate'] = tgleng($account->dates);
       $data['vendor'] = $account->prefix.' '.$account->name;
       $data['address'] = $account->address;
       $data['shipdate'] = tgleng($account->shipping_date);
       $data['city'] = $account->city;
       $data['phone'] = $account->phone1;
       $data['phone2'] = $account->phone2;
       $data['desc'] = $account->desc;
       $data['user'] = $this->user->get_username($account->user);
       $data['currency'] = $this->currency->get_code($account->currency);
       $data['docno'] = $account->docno;

       $data['cost'] = $account->costs;
       $data['p2'] = $account->p2;
       $data['p1'] = $account->p1;

       $data['items'] = $this->Account_item_model->get_last_item($po)->result();

       // property display
       $data['p_name'] = $this->properti['name'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('account_expediter', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('account/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('account_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur = $this->input->post('ccurrency');
        $status = $this->input->post('cstatus');

        $data['currency'] = $cur;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        // assets
        $data['kas'] = $this->Account_model->report($cur,$status,7)->result();
        $data['bank'] = $this->Account_model->report($cur,$status,8)->result();
        $data['piutangusaha'] = $this->Account_model->report($cur,$status,20)->result();
        $data['piutangnonusaha'] = $this->Account_model->report($cur,$status,27)->result();
        $data['persediaan'] = $this->Account_model->report($cur,$status,14)->result();
        $data['biayadimuka'] = $this->Account_model->report($cur,$status,13)->result();
        $data['investasipanjang'] = $this->Account_model->report($cur,$status,29)->result();
        $data['hartatetapwujud'] = $this->Account_model->report($cur,$status,26)->result();
        $data['hartatetaptakwujud'] = $this->Account_model->report($cur,$status,30)->result();
        $data['hartalain'] = $this->Account_model->report($cur,$status,31)->result();
        
        // kewajiban
        $data['hutangusaha'] = $this->Account_model->report($cur,$status,10)->result();
        $data['pendapatandimuka'] = $this->Account_model->report($cur,$status,34)->result();
        $data['hutangjangkapanjang'] = $this->Account_model->report($cur,$status,35)->result();
        $data['hutangnonusaha'] = $this->Account_model->report($cur,$status,32)->result();
        $data['hutanglain'] = $this->Account_model->report($cur,$status,36)->result();
        
        // modal & laba
        $data['modal'] = $this->Account_model->report($cur,$status,22)->result();
        $data['laba'] = $this->Account_model->report($cur,$status,18)->result();
        
        // income
        $data['income'] = $this->Account_model->report($cur,$status,16)->result();
        $data['otherincome'] = $this->Account_model->report($cur,$status,37)->result();
        $data['outincome'] = $this->Account_model->report($cur,$status,21)->result();
        
        // biaya
        $data['biayausaha'] = $this->Account_model->report($cur,$status,15)->result();
        $data['biayausahalain'] = $this->Account_model->report($cur,$status,17)->result();
        $data['biayaoperasional'] = $this->Account_model->report($cur,$status,19)->result();
        $data['biayanonoperasional'] = $this->Account_model->report($cur,$status,24)->result();
        $data['pengeluaranluarusaha'] = $this->Account_model->report($cur,$status,25)->result();
        
        
        $this->load->view('account_report', $data); 
    }


// ====================================== REPORT =========================================

}

?>