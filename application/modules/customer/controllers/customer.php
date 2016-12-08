<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->city = $this->load->library('city_lib');
        $this->sales = $this->load->library('sales_lib');
        $this->ar_payment = $this->load->library('ar_payment');
        $this->nar_payment = $this->load->library('nar_payment');
        $this->model = new Customers();
    }

    private $properti, $modul, $title, $model;
    private $sales, $ar_payment, $nar_payment;

    function index()
    {
        $this->get_last_customer();        
    }

   public function autocomplete()
   {
//      // tangkap variabel keyword dari URL
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('customer')->like('name',$keyword)->get();

      // format keluaran di dalam array
      foreach($data->result() as $row)
      {
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>$row->name,
            'data'   =>$row->id
         );
      }

      // minimal PHP 5.2
      echo json_encode($arr);
   }
    
    function get_last_customer()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'customer_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_search'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['city'] = $this->city->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //

        $customers = $this->model->get($this->modul['limit'],$offset);
        $num_rows  = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_customer');
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
            $this->table->set_heading('#','No', 'Code', 'Name', 'Type', 'Contact', 'Phone', 'Action');

            $i = 0 + $offset;
            foreach ($customers as $customer)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $customer->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, 'CUST-0'.$customer->id, $customer->prefix.' '.$customer->name, $customer->type, $customer->cp1, $customer->phone1,
                    anchor($this->title.'/update/'.$customer->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$customer->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'customer_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_search'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['city'] = $this->city->combo();
	// ---------------------------------------- //

        $customers = $this->model->where('name', $this->input->post('tsearch'))->get();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Code', 'Name', 'Type', 'Contact', 'Phone', 'Action');

        $i = 0;
        foreach ($customers as $customer)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $customer->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                form_checkbox($datax), ++$i, 'CUST-0'.$customer->id, $customer->prefix.' '.$customer->name, $customer->type, $customer->cp1, $customer->phone1,
                anchor($this->title.'/update/'.$customer->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$customer->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();


        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function get_list()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'customer_list';

        $customers = $this->model->get();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Name', 'Type', 'Contact', 'Phone', 'Action');

            $i = 0;
            foreach ($customers as $customer)
            {
               $data = array(
                                'name' => 'button',
                                'type' => 'button',
                                'content' => 'Select',
                                'onclick' => 'setvalue(\''.$customer->name.'\',\'tcust\')'
			     );

                $this->table->add_row
                (
                    ++$i, 'CUST-0'.$customer->id, $customer->prefix.' '.$customer->name, $customer->type, $customer->cp1, $customer->phone1,
                    form_button($data)
                );
            }

            $data['table'] = $this->table->generate();
            $this->load->view('customer_list', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        if ( $this->cek_relation($uid) == TRUE )
        {
          // Delete customer
          $this->model->where('id', $uid)->get();
          $this->model->delete();
          $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    private function cek_relation($id)
    {
        $sales = $this->sales->cek_relation($id, $this->title);
        $ar_payment = $this->ar_payment->cek_relation($id, $this->title);
        if ($sales == TRUE && $ar_payment == TRUE) { return TRUE; } else { return FALSE; }
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'customer_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('customer/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_customer');
        $this->form_validation->set_rules('ctype', 'Type', 'required');
        $this->form_validation->set_rules('tcontact', 'Contact Person', 'required');
        $this->form_validation->set_rules('tphone', 'Phone', 'required');
        $this->form_validation->set_rules('tshipaddress', 'Shipping Address', 'required');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
        $this->form_validation->set_rules('ccity', 'City', 'required');
        $this->form_validation->set_rules('tzip', 'Zip Code', 'required');
        $this->form_validation->set_rules('tmail', 'Email', 'valid_email');

        if ($this->form_validation->run($this) == TRUE)
        {

            $this->model->prefix           = $this->input->post('tpre');
            $this->model->name             = $this->input->post('tname');
            $this->model->type             = $this->input->post('ctype');
            $this->model->cp1              = $this->input->post('tcontact');
            $this->model->npwp             = $this->input->post('tnpwp');
            $this->model->address          = $this->input->post('taddress');
            $this->model->shipping_address = $this->input->post('tshipaddress');
            $this->model->phone1           = $this->input->post('tphone');
            $this->model->phone2           = $this->input->post('phone2');
            $this->model->fax              = $this->input->post('tfax');
            $this->model->hp               = $this->input->post('tmobile');
            $this->model->email            = $this->input->post('tmail');
            $this->model->acc_name         = $this->input->post('taccname');
            $this->model->acc_no           = $this->input->post('taccno');
            $this->model->bank             = $this->input->post('tbank');
            $this->model->website          = $this->input->post('turl');
            $this->model->city             = $this->input->post('ccity');
            $this->model->zip              = $this->input->post('tzip');
            $this->model->notes            = $this->input->post('tnotes');

            // Save new user
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
        $data['main_view'] = 'customer_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('customer/','<span>back</span>', array('class' => 'back')));

        $data['city'] = $this->city->combo();

        $customer = $this->model->where('id', $uid)->get();

        $data['default']['prefix'] = $customer->prefix;
        $data['default']['name'] = $customer->name;
        $data['default']['type'] = $customer->type;
        $data['default']['contact'] = $customer->cp1;
        $data['default']['npwp'] = $customer->npwp;
        $data['default']['address'] = $customer->address;
        $data['default']['shipaddress'] = $customer->shipping_address;
        $data['default']['phone'] = $customer->phone1;
        $data['default']['phone2'] = $customer->phone2;
        $data['default']['fax'] = $customer->fax;
        $data['default']['mobile'] = $customer->hp;
        $data['default']['mail'] = $customer->email;
        $data['default']['url'] = $customer->website;
        $data['default']['city'] = $customer->city;
        $data['default']['zip'] = $customer->zip;
        $data['default']['notes'] = $customer->notes;

        $data['default']['accname'] = $customer->acc_name;
        $data['default']['accno'] = $customer->acc_no;
        $data['default']['bank'] = $customer->bank;

	$this->session->set_userdata('curid', $customer->id);
        $this->load->view('customer_update', $data);
    }


    public function valid_customer($name)
    {
        $val = $this->model->where('name', $name)->count();
        
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_customer', "This $this->title is already registered.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    function validation_customer($name)
    {
	$id = $this->session->userdata('curid');
        $this->model->where_not_in('id', $id);
        $val = $this->model->where('name', $name)->count();
        
	if ($val > 0)
        {
            $this->form_validation->set_message('validation_customer', 'This customer is already registered!');
            return FALSE;
        }
        else{  return TRUE; }
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'customer_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('customer/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validation_customer');
        $this->form_validation->set_rules('ctype', 'Type', 'required');
        $this->form_validation->set_rules('tcontact', 'Contact Person', 'required');
        $this->form_validation->set_rules('tphone', 'Phone', 'required');
        $this->form_validation->set_rules('tshipaddress', 'Shipping Address', 'required');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
        $this->form_validation->set_rules('ccity', 'City', 'required');
        $this->form_validation->set_rules('tzip', 'Zip Code', 'required');
        $this->form_validation->set_rules('tmail', 'Email', 'valid_email');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->prefix           = $this->input->post('tpre');
            $this->model->name             = $this->input->post('tname');
            $this->model->type             = $this->input->post('ctype');
            $this->model->cp1              = $this->input->post('tcontact');
            $this->model->npwp             = $this->input->post('tnpwp');
            $this->model->address          = $this->input->post('taddress');
            $this->model->shipping_address = $this->input->post('tshipaddress');
            $this->model->phone1           = $this->input->post('tphone');
            $this->model->phone2           = $this->input->post('phone2');
            $this->model->fax              = $this->input->post('tfax');
            $this->model->hp               = $this->input->post('tmobile');
            $this->model->email            = $this->input->post('tmail');
            $this->model->acc_name         = $this->input->post('taccname');
            $this->model->acc_no           = $this->input->post('taccno');
            $this->model->bank             = $this->input->post('tbank');
            $this->model->website          = $this->input->post('turl');
            $this->model->city             = $this->input->post('ccity');
            $this->model->zip              = $this->input->post('tzip');
            $this->model->notes            = $this->input->post('tnotes');

            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
        }
        else
        {
            $this->load->view('customer_update', $data);
        }
    }

}

?>