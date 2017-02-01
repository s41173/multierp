<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ctype extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
//        $this->load->model('Ctype_model', 'model', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
        $this->model = new Ctypes();
        $this->contract = new Contract_lib();
        $this->account = new Account_lib();
    }

    private $properti, $modul, $title, $model, $contract, $account;

    function index(){ $this->get_last(); }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ctype_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ------------------------------------------- //
        $citys     = $this->model->get($this->modul['limit'],$offset);
        $num_rows  = $this->model->count();

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
            $this->table->set_heading('#','No', 'Name', 'Account', 'Action');

            $i = 0 + $offset;
            foreach ($citys as $city)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $city->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, strtoupper($city->name), $this->account->get_code($city->account_id).' : '.$this->account->get_name($city->account_id),
                    anchor($this->title.'/update/'.$city->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$city->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{  $data['message'] = "No $this->title data was found!"; }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    
    function get_list()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];

        $result = null;
        if ($this->input->post('tvalue')){ $result = $this->model->where($this->input->post('ctype'), $this->input->post('tvalue'))->get(); }
        else { $result = $this->model->get(); } 

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Province', 'Ctype', 'District', 'Village', 'Zip', 'Action');

        $i = 0;
        foreach ($result as $res)
        {
           $data = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$res->zip.'\',\'tzip\')'
                         );

            $this->table->add_row
            (
                ++$i, $res->province, $res->name, $res->district, $res->village, $res->zip,
                form_button($data)
            );
        }

        $data['form_action'] = site_url($this->title.'/get_list');
        $data['table'] = $this->table->generate();
        $this->load->view('city_list', $data);
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        if ($this->contract->cek_relation_contract_type($uid, 'contract_type') == TRUE)
        {
          $this->model->where('id', $uid)->get();
          $this->model->delete();
          $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); // set flash data message dengan session
        }
        else{
          $this->session->set_flashdata('message', "Contract Types is related to another component..!"); // set flash data message dengan session  
        }
        redirect($this->title);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        
        $this->load->view('city_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ctype_view';
	$data['form_action'] = site_url($this->title.'/add_process');

	// Form validation
        $this->form_validation->set_rules('tname', 'Contract Type', 'required|callback_valid_name');
        $this->form_validation->set_rules('titem', 'Account', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name = strtoupper($this->input->post('tname'));
            $this->model->account_id  = $this->account->get_id_code($this->input->post('titem'));
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title);
            echo 'true';
        }
        else
        {
//               $this->load->view('template', $data);
            echo validation_errors();
        }

    }

    // Fungsi update untuk menset texfield dengan nilai dari database
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'city_form';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('city/','<span>back</span>', array('class' => 'back')));

        $city = $this->model->where('id', $uid)->get();
        
        $data['default']['name'] = $city->name;
        $data['default']['account'] = $this->account->get_code($city->account_id);

	$this->session->set_userdata('langid', $city->id);
        $this->load->view('ctype_form', $data);
    }


    public function valid_name($name)
    {
       $val = $this->model->where('name', $name)->count();

       if ($val > 0)
       {
           $this->form_validation->set_message('valid_name', "This Contract Type is already registered.!");
           return FALSE;
       }
       else{ return TRUE; }
    }
    
    public function validation_name($val)
    {
       $id = $this->session->userdata('langid');
       $this->model->where_not_in('id', $id); 
       $vals = $this->model->where('name', $val)->count();

       if ($vals > 0)
       {
           $this->form_validation->set_message('validation_name', "This $this->title is already registered.!");
           return FALSE;
       }
       else{ return TRUE; }
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'city_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('city/','<span>back</span>', array('class' => 'back')));

	// Form validation
        
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validation_name');


        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('langid'))->get();
            
            $this->model->name = strtoupper($this->input->post('tname'));
            $this->model->account_id  = $this->account->get_id_code($this->input->post('titem'));
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('langid'));
            $this->session->unset_userdata('langid');
            
            echo 'true';
        }
        else
        {  //$this->load->view('city_update', $data); 
           echo validation_errors(); 
        }
    }

}

?>