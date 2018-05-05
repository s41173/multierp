<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groupasset extends MX_Controller
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

        $this->load->model('Group_model','gm',TRUE);
        $this->account = new Account_lib();
        $this->asset = new Asset_lib();
    }

    private $properti, $modul, $title, $model, $account;
    private $user,$currency,$category,$asset;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'group_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $costs = $this->gm->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->gm->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
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
            $this->table->set_heading('No', 'Code', 'Name', 'Period', 'Acc-Accumulation', 'Acc-Depreciation', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                if ($cost->status == 1) {$class = "approve"; }else{$class = "notapprove"; }
       
                $this->table->add_row
                (
                    ++$i, $cost->code, $cost->name, $cost->period, $this->account->get_combination($cost->acc_accumulation), $this->account->get_combination($cost->acc_depreciation),
                    anchor($this->title.'/publish/'.$cost->id,'<span>update</span>',array('class' => $class, 'title' => 'edit / update')).' '.
                    anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{ $data['message'] = "No $this->title data was found!"; }

	$this->load->view('template', $data);
    }
    
    function publish($pid)
    { 
      $result = $this->gm->get_by_id($pid)->row();    
      if ($result->status == 0){ $val = array('status' => 1); $stts = 'published';  }else{ $val = array('status' => 0); $stts = 'unpublished'; }
       
      $this->gm->update_id($pid, $val);
      $this->session->set_flashdata('message', "1 $this->title ".$stts."..!");
      redirect($this->title); 
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
        $this->form_validation->set_rules('tperiod', 'Period', 'required|numeric');
        $this->form_validation->set_rules('titem', 'Accumulation Account', 'required');
        $this->form_validation->set_rules('titem2', 'Depreciation Account', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $groupasset = array('code' => $this->input->post('tcode'), 'name' => $this->input->post('tname'),
                                'period' => $this->input->post('tperiod'), 'acc_accumulation' => $this->account->get_id_code($this->input->post('titem')),
                                'acc_depreciation' => $this->account->get_id_code($this->input->post('titem2')));
            
            $this->gm->add($groupasset);
            echo 'true';
        }
        else{echo validation_errors(); }
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        if ( $this->asset->cek_relation($uid,'group_id') == TRUE )
        {
            $this->gm->delete($uid);
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "$this->title related to another component..!"); }
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
        
        $asset = $this->gm->get_by_id($uid)->row();
        $data['default']['code']     = $asset->code;
        $data['default']['name']     = $asset->name;
        $data['default']['description'] = $asset->description;
        $data['default']['period'] = $asset->period;
        $data['default']['accumulation']  = $this->account->get_code($asset->acc_accumulation);
        $data['default']['depreciation']  = $this->account->get_code($asset->acc_depreciation);

	$this->session->set_userdata('curid', $asset->id);
        $this->load->view('group_update', $data);
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
        $this->form_validation->set_rules('tperiod', 'Period', 'required|numeric');
        $this->form_validation->set_rules('titem', 'Accumulation Account', 'required');
        $this->form_validation->set_rules('titem2', 'Depreciation Account', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $groupasset = array('code' => $this->input->post('tcode'), 'name' => $this->input->post('tname'),
                                'period' => $this->input->post('tperiod'), 'acc_accumulation' => $this->account->get_id_code($this->input->post('titem')),
                                'acc_depreciation' => $this->account->get_id_code($this->input->post('titem2')));
            
            $this->gm->update_id($this->session->userdata('curid'),$groupasset);
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

    public function valid_code($code)
    {
        $val = $this->gm->valid('code',$code);

        if ($val == FALSE)
        {
            $this->form_validation->set_message('valid_code', "Invalid Code..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_code($code)
    {
        $val = $this->gm->validating('code',$code,$this->session->userdata('curid'));

        if ($val == FALSE)
        {
            $this->form_validation->set_message('validating_code', "Invalid Code..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_name($name)
    {
        $val = $this->gm->validating('name',$name,$this->session->userdata('curid'));

        if ($val == FALSE)
        {
            $this->form_validation->set_message('validating_name', "Invalid Name..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_name($name)
    {
        $val = $this->gm->valid('name',$name);

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