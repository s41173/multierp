<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Admin_model', '', TRUE);
        $this->role = $this->load->library('role');

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->city = $this->load->library('city_lib');

        $this->purchase         = $this->load->library('purchase_lib');
        $this->sales            = $this->load->library('sales_lib');
        $this->ap               = $this->load->library('ap_lib');
        $this->ap_payment       = $this->load->library('ap_payment_lib');
        $this->ap_payment_cash  = $this->load->library('ap_payment_cash');
        $this->ar_payment       = $this->load->library('ar_payment');
    }
    

    private $properti, $role, $modul, $title;
    private $city;
    private $purchase, $sales, $ap, $ap_payment, $ap_payment_cash, $ar_payment;


    function index()
    { $this->get_last_user(); }
   
    
    function get_last_user()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'admin_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['roles'] = $this->role->combo();
        $data['city'] = $this->city->combo();

	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
 
        $users = $this->Admin_model->get_last_user($this->modul['limit'], $offset)->result(); // ambil data dari db
        $num_rows = $this->Admin_model->count_all_num_rows(); // hitung jumlah baris
        
        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_user');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination

            $atts = array(
              'class'      => 'update',
              'title'      => 'edit / update',
              'width'      => '600',
              'height'     => '600',
              'scrollbars' => 'no',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
              'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
            );

            // library HTML table untuk membuat template table class zebra
            $tmpl = array( 'table_open' => '<table class="tablemaster">', 'row_alt_start' => '<tr class="zebra">', 'row_alt_end' => '</tr>');
            
            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");
            
            //Set heading untuk table
            //$this->table->set_heading('No', 'User ID', 'Username', 'Password', 'Role', 'School ID','Action');
	    $this->table->set_heading('No', 'Username', 'E-mail', 'Role', 'Status', 'Action');
            
            $i = 0;
            foreach ($users as $user)
            {
                $this->table->add_row
                (
                    ++$i, $user->username, mailto($user->email), $user->role, $this->status($user->status),
//                    anchor_popup($this->title.'/update/'.$user->userid, '<span>update</span>', $atts).' '.
                    anchor($this->title.'/update/'.$user->userid,'<span>update</span>',array('class' => 'update')).' '.
                    anchor($this->title.'/delete/'.$user->userid,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
            
            // table di generate 
            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = 'Not found any '.$this->modul['title'].' of data!';
        }
	
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function status($val)
    {if ($val == 1){return "TRUE";} else{return "FALSE";}}


    private function cek_admin($id)
    {
        $user = $this->Admin_model->get_user_by_id($id)->row();
        $user = $user->username;

        if ($user == 'admin')
        {
           $this->session->set_flashdata('message', 'admin can not edit / remove..!!');
           redirect($this->title);
        }
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->cek_admin($uid);

        if ( $this->cek_relation($uid) == TRUE )
        {
           $this->Admin_model->delete($uid);
           $this->session->set_flashdata('message', '1 users successfully removed..!!');
        }
        else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    private function cek_relation($id)
    {
        $purchase        = $this->purchase->cek_relation($id, 'user');
        $sales           = $this->sales->cek_relation($id, 'user');
        $ar_payment      = $this->ar_payment->cek_relation($id, 'user');
        $ap_payment      = $this->ap_payment->cek_relation($id, 'user');
        $ap              = $this->ap->cek_relation($id, 'user');
        $ap_payment_cash = $this->ap_payment_cash->cek_relation($id, 'user');

        if ($purchase == TRUE && $sales == TRUE && $ar_payment == TRUE && $ap_payment == TRUE && $ap == TRUE && $ap_payment_cash == TRUE )
        { return TRUE; } else { return FALSE; }
    }
    
    function add_process()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'admin_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['roles'] = $this->role->combo();
        $data['city'] = $this->city->combo();
         
        $this->form_validation->set_rules('tusername', 'UserName', 'required|callback_valid_username');
	$this->form_validation->set_rules('tpassword', 'Password', 'required');
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
        $this->form_validation->set_rules('tphone', 'Phone', 'required');
        $this->form_validation->set_rules('ccity', 'City', 'required');
        $this->form_validation->set_rules('tmail', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('crole', 'Role', 'required');
        $this->form_validation->set_rules('tid', 'Yahoo Id', '');
        $this->form_validation->set_rules('rstatus', 'Status', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $users = array('username' => $this->input->post('tusername'),'password' => $this->input->post('tpassword'),'name' => $this->input->post('tname'),
                           'address' => $this->input->post('taddress'), 'phone1' => $this->input->post('tphone'), 'city' => $this->input->post('ccity'),
                           'email' => $this->input->post('tmail'), 'yahooid' => setnull($this->input->post('tid')), 'role' => $this->input->post('crole'), 'status' => $this->input->post('rstatus'));

            $this->Admin_model->add($users);
            $this->session->set_flashdata('message', "One data ".$this->title." successfully saved!");
//            redirect($this->title);  ========= fungsi untuk ajax ============
            echo 'true';
        }
        else
        {
//            $this->load->view('template', $data); ========= fungsi untuk ajax ============
            echo validation_errors();
        }
    }
    
    function valid_username()
    {
        $uname = $this->input->post('tusername');
        
        if ($this->Admin_model->valid_name($uname) == FALSE)
        {
            $this->form_validation->set_message('valid_username', 'User ini sudah ada.!');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function validation_username($name)
    {
	$id = $this->session->userdata('admin_id');
	if ($this->Admin_model->validation_username($name,$id) == FALSE)
        {
            $this->form_validation->set_message('validation_username', 'This user is already registered!');
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
        $data['h2title'] = 'Updating - '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['roles'] = $this->role->combo();
        $data['city'] = $this->city->combo();

        $user = $this->Admin_model->get_user_by_id($uid)->row();

        $data['default']['name'] = $user->name;
        $data['default']['address'] = $user->address;
        $data['default']['phone'] = $user->phone1;
        $data['default']['city'] = $user->city;
        $data['default']['user_name'] = $user->username;
	$data['default']['password'] = $user->password;
        $data['default']['mail'] = $user->email;
        $data['default']['id'] = $user->yahooid;
        $data['default']['role'] = $user->role;
        $data['default']['status'] = $user->status;
	
	$this->session->set_userdata('admin_id', $user->userid);
    
       $this->load->view('admin_update', $data);
    }
    
    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = 'Updating - '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['roles'] = $this->role->combo();
        $data['city'] = $this->city->combo();
        
        $this->form_validation->set_rules('tusername', 'UserName', 'required|callback_validation_username');
	$this->form_validation->set_rules('tpassword', 'Password', 'required');
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
        $this->form_validation->set_rules('tphone', 'Phone', 'required');
        $this->form_validation->set_rules('ccity', 'City', 'required');
        $this->form_validation->set_rules('tmail', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('crole', 'Role', 'required');
        $this->form_validation->set_rules('tid', 'Yahoo Id', '');
        $this->form_validation->set_rules('rstatus', 'Status', 'required');
	
        if ($this->form_validation->run($this) == TRUE)
        {   
            $users = array('username' => $this->input->post('tusername'),'password' => $this->input->post('tpassword'),'name' => $this->input->post('tname'),
                           'address' => $this->input->post('taddress'), 'phone1' => $this->input->post('tphone'), 'city' => $this->input->post('ccity'),
                           'email' => $this->input->post('tmail'), 'yahooid' => setnull($this->input->post('tid')), 'role' => $this->input->post('crole'), 'status' => $this->input->post('rstatus'));
            $this->Admin_model->update($this->session->userdata('admin_id'), $users);

           
            $this->session->set_flashdata('message', 'One '.$this->title.' of data successfully updated!');
            redirect($this->title.'/update/'.$this->session->userdata('admin_id'));
            $this->session->unset_userdata('admin_id');
        }
        else
        {
           $this->load->view('admin_update', $data);
        }
    }
    
}

?>