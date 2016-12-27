<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Project_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->customer = $this->load->library('customer_lib');
        $this->user = $this->load->library('admin_lib');
    }

    private $properti, $modul, $title;
    private $customer,$user,$currency;

    function index()
    { $this->get_last_project();}

    function get_last_project()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'project_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $projects = $this->Project_model->get_last_project($this->modul['limit'], $offset)->result();
        $num_rows = $this->Project_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_project');
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
            $this->table->set_heading('No', 'Code', 'Note', 'Customer', 'Date', 'Staff', '#', 'Action');

            $i = 0 + $offset;
            foreach ($projects as $project)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $project->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'PRJ-00'.$project->id, $project->name, $project->prefix.' '.$project->name, tglin($project->dates), $project->staff,
                    anchor($this->title.'/confirmation/'.$project->id,'<span>update</span>',array('class' => $this->post_status($project->status), 'title' => 'edit / update')).' - '.$this->status($project->status),
                    anchor($this->title.'/add_trans/'.$project->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$project->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'project_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('project/','<span>back</span>', array('class' => 'back')));

        $projects = $this->Project_model->search($this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Note', 'Customer', 'Date',  'Staff', '#', 'Action');

        $i = 0;
        foreach ($projects as $project)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $project->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'PRJ-00'.$project->id, $project->name, $project->prefix.' '.$project->name, tglin($project->dates), $project->staff,
                anchor('#','<span>update</span>',array('class' => $this->post_status($project->status), 'title' => 'edit / update')).' - '.$this->status($project->status),
                anchor($this->title.'/add_trans/'.$project->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$project->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'project_list';
        $data['link'] = array('link_back' => anchor($this->title.'/get_list','<span>back</span>', array('class' => 'back')));

        $projects = $this->Project_model->get_project_list()->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Note', 'Customer', 'Date',  'Staff', 'Action');

        $i = 0;
        foreach ($projects as $project)
        {
           $datax = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$project->id.'\',\'titem\')'
                         );

            $this->table->add_row
            (
                ++$i, 'PRJ-00'.$project->id, $project->name, $project->prefix.' '.$project->name, tglin($project->dates), $project->staff,    
                form_button($datax)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('project_list', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val > 0) {$class = "notapprove"; }
       else{$class = "approve"; }
       return $class;
    }

    private function status($val)
    {
        if ($val == 0){ $val = 'On Progress'; }
        elseif ( $val == 1 ) { $val = 'Finished'; }
        return $val;
    }

    function confirmation($pid)
    {
        $this->acl->otentikasi4($this->title);
        $project = array('status' => 1);
        $this->Project_model->update($pid, $project);
        $this->session->set_flashdata('message', "One $this->title has successfully confirmed..!");
        redirect($this->title);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->cek_status($uid);
        
        $this->Project_model->delete($uid);
        $this->session->set_flashdata('message', "1 $this->title removed, project approved..!");
        redirect($this->title);
    }


    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Project_model->counter();
        
        $this->load->view('project_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'project_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Project_model->counter();

	// Form validation
        $this->form_validation->set_rules('tcust', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'PRJ - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tlocation', 'Location', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $project = array('id' => $this->input->post('tno'), 'customer' => $this->customer->get_customer_id($this->input->post('tcust')),
                             'dates' => $this->input->post('tdate'), 'name' => $this->input->post('tnote'), 'status' => 0,
                             'location' => $this->input->post('tlocation'), 'desc' => $this->input->post('tdesc'),
                             'staff' => $this->input->post('tstaff'), 'log' => $this->session->userdata('log'));

            $this->Project_model->add($project);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add/');

//            echo 'true';
        }
        else
        {
              $this->load->view('project_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_status($po);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['code'] = $po;

        $project = $this->Project_model->get_project_by_id($po)->row();

        $data['default']['customer'] = $this->customer->get_customer_shortname($project->customer);
        $data['default']['date'] = $project->dates;
        $data['default']['note'] = $project->name;
        $data['default']['location'] = $project->location;
        $data['default']['desc'] = $project->desc;
        $data['default']['staff'] = $project->staff;
        
        $this->load->view('project_form', $data);
    }


    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('project/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcust', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tlocation', 'Location', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $project = array('customer' => $this->customer->get_customer_id($this->input->post('tcust')),
                             'dates' => $this->input->post('tdate'), 'name' => $this->input->post('tnote'),
                             'location' => $this->input->post('tlocation'), 'desc' => $this->input->post('tdesc'),
                             'staff' => $this->input->post('tstaff'), 'log' => $this->session->userdata('log'));

            $this->Project_model->update($po, $project);
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/add_trans/'.$po);
//            echo 'true';
        }
        else
        {
            $this->load->view('project_form', $data);
//            echo validation_errors();
        }
    }

    // ========== validation ========================
    
    private function cek_status($id)
    {
        
        $val = $this->Project_model->get_project_by_id($id)->row();
        $val = $val->status;
        if ($val == 1)
        {
          $this->session->set_flashdata('message', "$this->title approved can't removed..!");
          redirect($this->title.'/rejected');
        }
    }

    function rejected()
    {
       $data= null;
       $this->load->view('rejected', $data);
    }

    public function valid_customer($name)
    {
        if ($this->customer->valid_customer($name) == FALSE)
        {
            $this->form_validation->set_message('valid_customer', "Invalid Customer.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function valid_project($val)
    {
        $val = $this->Project_model->get_project_by_id($val)->row();
        if ($val->status == 1)
        {
            $this->form_validation->set_message('valid_project', "Project Approved.!");
            return FALSE;
        }
        else { return TRUE; }
    }


}

?>