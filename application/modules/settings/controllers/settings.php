<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Settings_model', '', TRUE);

        $this->title = strtolower(get_class($this));
    }
    
    
    function index()
    { $this->license();  }
    
    function license()
    {
        
        $data['title'] = "D'swip Web Installer";
        $data['h2title'] = 'Installations';
        $data['main_view'] = 'license_view';
	$this->load->view('intemplate', $data);
    }

    function property()
    {
        $data['title'] = "D'swip Web Installer";
        $data['h2title'] = 'Property / Configuration';
        $data['main_view'] = 'property_view';
	$this->load->view('intemplate', $data);
    }

    function process()
    {
        $this->form_validation->set_rules('tname', 'Property', 'required|max_length[100]');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
	$this->form_validation->set_rules('tphone1', 'Phone1', 'required|max_length[15]');
        $this->form_validation->set_rules('tphone2', 'Phone2', 'required|max_length[15]');
        $this->form_validation->set_rules('tmail', 'Property Mail', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('tbillmail', 'Billing Email', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('ttechmail', 'Technical Email', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('tccmail', 'CC Email', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('tarea1', 'Area Code', 'required|max_length[15]');
        $this->form_validation->set_rules('tarea2', 'Area Code', 'required|max_length[15]');
	$this->form_validation->set_rules('tcity', 'City', 'required|max_length[25]');
        $this->form_validation->set_rules('tzip', 'Zip Code', 'required|numeric|max_length[25]');

        $this->form_validation->set_rules('tpass', 'Password', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $phone1 = $this->input->post('tarea1')."-".$this->input->post('tphone1');
            $phone2 = $this->input->post('tarea2')."-".$this->input->post('tphone2');

            $property = array('name' => $this->input->post('tname'), 'address' => $this->input->post('taddress'), 'logo' => 'default.jpg',
                              'phone1' => $phone1, 'phone2' => $phone2, 'npwp' => $this->input->post('tnpwp'), 'cp' => $this->input->post('tcp'),
                              'cc_email' => $this->input->post('tccmail'), 'email' => $this->input->post('tmail'),'billing_email' => $this->input->post('tbillmail'), 'technical_email' => $this->input->post('ttechmail'),
                              'zip' => $this->input->post('tzip'),'city' => strtoupper($this->input->post('tcity')));

            $user = array('password' => $this->input->post('tpass'), 'name' => 'Administrator', 'status' => 1, 'username' => 'admin', 'role' => 'admin');

            $this->Settings_model->add($property);
            $this->Settings_model->add_user($user);
            $this->Settings_model->status();
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect('login');
        }
        else
        {
            $this->load->view('intemplate', $data);
        }

    }

}

?>