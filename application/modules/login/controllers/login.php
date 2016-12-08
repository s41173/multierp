<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller {


   public function __construct()
   {
        parent::__construct();

        $this->load->model('Login_model', '', TRUE);

        $this->load->helper('date');
        $this->load->library('log');
        $this->load->library('email');
        $this->login = new Login_lib();

        $this->properti = $this->property->get();
        $this->installation = $this->load->library('installation');

        // Your own constructor code
   }

   private $date,$time;
   private $properti;
   private $installation,$login;

   function index()
   {
        $this->cek_installation();
        $data['pname'] = $this->properti['name'];
        $data['logo'] = $this->properti['logo'];
        $data['form_action'] = site_url('login/login_process');

        $this->load->view('login_view', $data);
    }

    function cek_installation()
    {
        if ($this->installation->get() == FALSE)
        {
            $this->session->set_flashdata('message', 'Installation Page');
            redirect('settings');
        }
    }

    // function untuk memeriksa input user dari form sebagai admin
    function login_process()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            if ($this->Login_model->check_user($username,$password) == TRUE)
            {
                $this->date  = date('Y-m-d');
                $this->time  = waktuindo();
                $userid = $this->Login_model->get_userid($username);
                $role = $this->Login_model->get_role($username);
                $rules = $this->Login_model->get_rules($role);
                $logid = $this->log->max_log();
                $waktu = tgleng(date('Y-m-d')).' - '.waktuindo().' WIB';

                $this->log->insert($userid, $this->date, $this->time, 'login');
                $this->login->add($userid, $logid);

                $data = array('username' => $username, 'role' => $role, 'rules' => $rules, 'log' => $logid, 'login' => TRUE, 'waktu' => $waktu);
                $this->session->set_userdata($data);

                //memanggil controller main utama
                redirect('main');
            }
            else
            {
                $this->session->set_flashdata('message', 'Sorry, wrong username or password');
                redirect('login');
            }
        }
        else // else untuk form valdation
        {
            $this->load->view('login_view');
        }
    }

    // function untuk logout
    function process_logout()
    {
        $userid = $this->Login_model->get_userid($this->session->userdata('username'));
        $this->date  = date('Y-m-d');
        $this->time  = waktuindo();
        
        $this->log->insert($userid, $this->date, $this->time, 'logout');
        $this->session->sess_destroy();
        redirect('login');
    }

    function forgot()
    {
	$data['form_action'] = site_url('login/send_password');
        $data['pname'] = $this->properti['name'];
        $data['logo'] = $this->properti['logo'];
        $this->load->view('forgot_view' ,$data);
    }

    function send_password()
    {
        $this->form_validation->set_rules('username', 'Type Username', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            if ($this->Login_model->check_username($this->input->post('username')) == FALSE)
            {
               $this->session->set_flashdata('message', 'Username not registered ..!!');
               redirect('login/forgot');
            }
            else
            {
              $email = $this->Login_model->get_email($this->input->post('username'));
              $pass = $this->Login_model->get_pass($this->input->post('username'));
              $mess = "
                ".$this->properti['name']." - ".base_url()."
                FORGOT PASSWORD :

                Your Username is: ".$this->input->post('username')."
                Your Password : ".$pass." <hr />
Your password for this account has been recovered . You don�t need to do anything, this message is simply a notification to protect the security of your account.
Please note: your password may take awhile to activate. If it doesn�t work on your first try, please try it again later
DO NOT REPLY TO THIS MESSAGE. For further help or to contact support, please email to ".$this->properti['email']."
****************************************************************************************************************** ";

              $params = array($this->properti['email'], $this->properti['name'], $email, 'Password Recovery', $mess, 'text');
              $se = $this->load->library('send_email',$params);

              if ( $se->send_process() == TRUE )
              { $this->session->set_flashdata('message', 'Password has been sent to your email!'); }
              else { $this->session->set_flashdata('message', 'Failed To Sent Email!'); }
              redirect('login/forgot');

            }
            
        }
        else
        {
            $data['form_action'] = site_url('login/send_password');
            $data['pname'] = $this->properti['name'];
            $this->load->view('forgot_view' ,$data);
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */