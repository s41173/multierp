<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Asset extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');

        $this->load->model('Asset_model','am',TRUE);
        $this->account = new Account_lib();
        $this->asset = new Asset_lib();
        $this->group = new Group_asset_lib();
        $this->trans = new Asset_trans_lib();
    }

    private $properti, $modul, $title, $model, $account;
    private $user,$currency,$group,$asset, $trans;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {   
      $this->get_last();
    }
    
    function get_period_group($group){
        $groups = $this->group->get_details($group);
        echo $groups->period;
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'asset_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $data['group'] = $this->group->combo_all();
        
	$uri_seament = 3;
        $offset = $this->uri->segment($uri_seament);

	// ---------------------------------------- //
        $costs = $this->am->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->am->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_seament'] = $uri_seament;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination
            
            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Name', 'Group', 'Period', 'Purchase Price', 'Residual', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                if ($cost->status == 1) {$class = "approve"; }else{$class = "notapprove"; }
       
                $this->table->add_row
                (
                    ++$i, $cost->code, $cost->name, $this->group->get_name($cost->group_id), tglin($cost->purchase_date).' : '.tglin($cost->end_date), num_format($cost->amount), num_format($cost->residual),
                    anchor($this->title.'/publish/'.$cost->id,'<span>update</span>',array('class' => $class, 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$cost->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{ $data['message'] = "No $this->title data was found!"; }

	$this->load->view('template', $data);
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'asset_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['group'] = $this->group->combo_all();

        $asset = $this->am->search($this->input->post('cgroup'), $this->input->post('cstatus'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Name', 'Group', 'Period', 'Purchase Price', 'Residual', 'Action');

        $i = 0;
        foreach ($asset as $cost)
        {
            if ($cost->status == 1) {$class = "approve"; }else{$class = "notapprove"; }

            $this->table->add_row
            (
                ++$i, $cost->code, $cost->name, $this->group->get_name($cost->group_id), tglin($cost->purchase_date).' : '.tglin($cost->end_date), num_format($cost->amount), num_format($cost->residual),
                anchor($this->title.'/publish/'.$cost->id,'<span>update</span>',array('class' => $class, 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$cost->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }
    
   function invoice($uid=null)
   {
       $this->acl->otentikasi2($this->title);
       $asset = $this->am->get_by_id($uid)->row();
       $data['h2title'] = 'Fixed Asset'.$this->modul['title'];

       $data['code'] = $asset->code;
       $data['name'] = $asset->name;
       $data['group'] = $this->group->get_name($asset->group_id);
       $data['purchase'] = tglin($asset->purchase_date);
       $data['amount'] = num_format(floatval($asset->amount-$asset->residual));

       $data['items'] = $this->trans->get($uid);
       $this->load->view('asset_invoice', $data);
   }
    
    function publish($pid)
    { 
      $result = $this->am->get_by_id($pid)->row();    
      if ($result->status == 0){ $val = array('status' => 1); $stts = 'published';  }else{ $val = array('status' => 0); $stts = 'unpublished'; }
       
      $this->am->update_id($pid, $val);
      $this->session->set_flashdata('message', "1 $this->title ".$stts."..!");
      redirect($this->title); 
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['group'] = $this->group->combo_all();
        
        $this->load->view('asset_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cost_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcode', 'Code', 'required|callback_valid_code');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_name');
        $this->form_validation->set_rules('cgroup', 'Group', 'required');
        $this->form_validation->set_rules('tdate', 'Purchase Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tperiod', 'Period', 'required|numeric');
        
        $this->form_validation->set_rules('tamount', 'Purchase Amount', 'required|numeric');
        $this->form_validation->set_rules('tresidual', 'Residual Amount', 'required|numeric|callback_valid_residual');
        $this->form_validation->set_rules('tcost', 'Monthly Cost', 'required|numeric');
        $this->form_validation->set_rules('ttotalmonth', 'Total Months', 'required|numeric');
        
        $this->form_validation->set_rules('titem', 'Accumulation Account', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', '');

        if ($this->form_validation->run($this) == TRUE)
        {        
            $int = $this->input->post('ttotalmonth');
            $end = date('Y-m-d', strtotime($this->input->post('tdate'). ' + '.$int.' month'));
            $date=date_create(date('Y', strtotime($end))."-".date('n', strtotime($end))."-".get_total_days(date('n', strtotime($end))));
            $end_date = date_format($date,"Y-m-d");
            
            $groupasset = array('code' => $this->input->post('tcode'), 'name' => $this->input->post('tname'), 'group_id' => $this->input->post('cgroup'),
                                'purchase_date' => $this->input->post('tdate'), 'end_date' => $end_date, 'amount' => $this->input->post('tamount'),
                                'residual' => $this->input->post('tresidual'), 'monthly_cost' => $this->input->post('tcost'), 'total_month' => $this->input->post('ttotalmonth'),
                                'account' => $this->account->get_id_code($this->input->post('titem')),
                                'description' => $this->input->post('tdesc'));
            
            $this->am->add($groupasset);
            echo 'true'; 
        }
        else{echo validation_errors(); }
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->trans->delete_asset($uid);
        $this->am->delete($uid);
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        redirect($this->title);
    }
    
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));
        
        $data['group'] = $this->group->combo_all();
        $asset = $this->am->get_by_id($uid)->row();
        
        $data['default']['code']     = $asset->code;
        $data['default']['name']     = $asset->name;
        $data['default']['group']    = $asset->group_id;
        $data['default']['desc'] = $asset->description;
        $data['default']['date'] = $asset->purchase_date;
        $data['default']['period'] = intval($asset->total_month/12);
        $data['default']['amount'] = $asset->amount;
        $data['default']['residual'] = $asset->residual;
        $data['default']['cost'] = $asset->monthly_cost;
        $data['default']['totalmonth'] = $asset->total_month;
        $data['default']['account']  = $this->account->get_code($asset->account);
        
	$this->session->set_userdata('curid', $asset->id);
        $this->load->view('asset_update', $data);
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
        
	// Form validation
        $this->form_validation->set_rules('tcode', 'Code', 'required|callback_validating_code');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validating_name');
        $this->form_validation->set_rules('cgroup', 'Group', 'required');
        $this->form_validation->set_rules('tdate', 'Purchase Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tperiod', 'Period', 'required|numeric');
        
        $this->form_validation->set_rules('tamount', 'Purchase Amount', 'required|numeric');
        $this->form_validation->set_rules('tresidual', 'Residual Amount', 'required|numeric|callback_valid_residual');
        $this->form_validation->set_rules('tcost', 'Monthly Cost', 'required|numeric');
        $this->form_validation->set_rules('ttotalmonth', 'Total Months', 'required|numeric');
        
        $this->form_validation->set_rules('titem', 'Accumulation Account', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $int = $this->input->post('ttotalmonth');
            $end = date('Y-m-d', strtotime($this->input->post('tdate'). ' + '.$int.' month'));
            $date=date_create(date('Y', strtotime($end))."-".date('n', strtotime($end))."-".get_total_days(date('n', strtotime($end))));
            $end_date = date_format($date,"Y-m-d");
            
            $groupasset = array('code' => $this->input->post('tcode'), 'name' => $this->input->post('tname'), 'group_id' => $this->input->post('cgroup'),
                                'purchase_date' => $this->input->post('tdate'), 'end_date' => $end_date, 'amount' => $this->input->post('tamount'),
                                'residual' => $this->input->post('tresidual'), 'monthly_cost' => $this->input->post('tcost'), 'total_month' => $this->input->post('ttotalmonth'),
                                'account' => $this->account->get_id_code($this->input->post('titem')),
                                'description' => $this->input->post('tdesc'));
            
            $this->am->update_id($this->session->userdata('curid'),$groupasset);
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
        }
        else
        {
            $this->session->set_flashdata('message', validation_errors());
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->load->view('account_update', $data);
        }
    }
    
    public function valid_residual($residual)
    {
        $purchase = $this->input->post('tamount');
        if ($residual > $purchase)
        {
            $this->form_validation->set_message('valid_residual', "Invalid Residual Amount..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            if (cek_previous_period($month, $year) == TRUE){ return TRUE; }
            else { $this->form_validation->set_message('valid_period', "Invalid Period.!"); return FALSE; }
        }
        else {  return TRUE; }
    }

    public function valid_code($code)
    {
        $val = $this->am->valid('code',$code);

        if ($val == FALSE)
        {
            $this->form_validation->set_message('valid_code', "Invalid Code..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_code($code)
    {
        $val = $this->am->validating('code',$code,$this->session->userdata('curid'));

        if ($val == FALSE)
        {
            $this->form_validation->set_message('validating_code', "Invalid Code..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_name($name)
    {
        $val = $this->am->validating('name',$name,$this->session->userdata('curid'));

        if ($val == FALSE)
        {
            $this->form_validation->set_message('validating_name', "Invalid Name..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_name($name)
    {
        $val = $this->am->valid('name',$name);

        if ($val == FALSE)
        {
            $this->form_validation->set_message('valid_name', "Invalid Name..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validation_cost($acc)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('account_id', $acc)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_cost', "Invalid Account..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function report()
    {
        $data['costs'] = $this->model->get();
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $this->load->view('cost_report', $data);
    }

}

?>