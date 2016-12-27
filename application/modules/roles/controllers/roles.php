<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Role_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
    }
    
    private $properti, $modul, $title;
    
    function index(){ $this->get_last_roles(); }
    
    function get_last_roles()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'roles_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
        $roless = $this->Role_model->get_last_role($this->modul['limit'], $offset)->result(); // ambil data dari db
        $num_rows = $this->Role_model->count_all_num_rows(); // hitung jumlah baris
        
        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_roles');
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
	    $this->table->set_heading('No', 'Name', 'Rules', 'Action');
            
            $i = 0;
            foreach ($roless as $roles)
            {
                $this->table->add_row
                (
                    ++$i, $roles->name, $this->get_rules($roles->rules),
		    anchor($this->title.'/update/'.$roles->id,'<span>update</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$roles->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
            
            // table di generate 
            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "Not found any $this->title of data!";
        }
	
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    private function get_rules($val)
    {
        $re = null;
        switch ($val)
        {
            case 1:
              $re = "read";
              break;
            case 2:
              $re = "read / write";
              break;
            case 3:
              $re = "full control";
              break;
        }
        return $re;
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->Role_model->delete($uid); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!!"); // set flash data message dengan session
        redirect($this->title);
    }
    
    function add_process()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'admin_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
         
        $this->form_validation->set_rules('tname', 'Role Name', 'required||maxlength[100]|callback_valid_roles');
        $this->form_validation->set_rules('tdesc', 'Role Description', 'required');
        $this->form_validation->set_rules('crules', 'Rules', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $roles = array('name' => $this->input->post('tname'), 'desc' => $this->input->post('tdesc'), 'rules' => $this->input->post('crules'));
            $this->Role_model->add($roles);
            $this->session->set_flashdata('message', "One data $this->title successfully saved!");
//            redirect($this->title);
            echo "true";
        }
        else
        {
//            $this->load->view('template', $data);
            echo validation_errors();
        }
    }

    function split_array($val)
    {
      return implode(",",$val);
    }

    function valid_roles($val)
    {
        if ($this->Role_model->valid_role($val) == FALSE)
        {
            $this->form_validation->set_message('valid_roles', $this->title.' registered');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function validating_roles($val)
    {
	$id = $this->session->userdata('rolid');
	if ($this->Role_model->validating_role($val,$id) == FALSE)
        {
            $this->form_validation->set_message('validating_roles', "This $this->title name is already registered!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    // Fungsi update untuk menset texfield dengan nilai dari database
    function update($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'roles_update';
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $roles = $this->Role_model->get_role_by_id($uid)->row();
       
	$data['default']['name'] = $roles->name;
        $data['default']['desc'] = $roles->desc;
        $data['default']['rules'] = $roles->rules;
	
	$this->session->set_userdata('rolid', $roles->id);
    
       $this->load->view('roles_update', $data);
    }
    
    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'roles_update';
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $this->form_validation->set_rules('tname', 'Role Name', 'required||maxlength[100]|callback_validating_roles');
        $this->form_validation->set_rules('tdesc', 'Role Description', 'required');
	
        if ($this->form_validation->run($this) == TRUE)
        {
            $roles = array('name' => $this->input->post('tname'), 'desc' => $this->input->post('tdesc'), 'rules' => $this->input->post('crules'));
            $this->Role_model->update($this->session->userdata('rolid'),$roles);
            $this->session->set_flashdata('message', "One data $this->title successfully saved!");
            redirect($this->title.'/update/'.$this->session->userdata('rolid'));
        }
        else
        {
           $this->load->view('template', $data);
        }
    }
    
}

?>