<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendor extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->city = $this->load->library('city_lib');
        $this->purchase = new Purchase_lib();
        $this->ap = new Ap_lib();
        $this->ap_payment = new Ap_payment_lib();
        $this->ap_payment_cash = new Ap_payment_cash();
        $this->product = $this->load->library('products_lib');
        $this->model = new Vendors();
    }

    private $properti, $modul, $title;
    private $purchase, $ap, $ap_payment, $ap_payment_cash, $product, $model;

    function index()
    {
        $this->get_last_vendor();
    }

    function get_last_vendor()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'vendor_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_search'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['city'] = $this->city->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $vendors   = $this->model->get($this->modul['limit'],$offset);
        $num_rows  = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_vendor');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination

            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">', 'row_start' => '<tr class="rowOdd">', 'row_alt_start' => '<tr class="rowEven">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('#','No', 'Code', 'Name', 'Type', 'Contact', 'Phone', 'Action');

            $i = 0 + $offset;
            foreach ($vendors as $vendor)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $vendor->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, 'VEN-0'.$vendor->id, $vendor->prefix.' '.$vendor->name, $vendor->type, $vendor->cp1, $vendor->phone1,
                    anchor($this->title.'/update/'.$vendor->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$vendor->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
    
   public function autocomplete()
   {
//      // tangkap variabel keyword dari URL
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('vendor')->like('name',$keyword)->get();

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
    
     function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'vendor_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_search'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['city'] = $this->city->combo();

	// ---------------------------------------- //
        $vendors   = $this->model->where('name', $this->input->post('tsearch'))->get();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">', 'row_start' => '<tr class="rowOdd">', 'row_alt_start' => '<tr class="rowEven">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Code', 'Name', 'Type', 'Contact', 'Phone', 'Action');

        $i = 0;
        foreach ($vendors as $vendor)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $vendor->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                form_checkbox($datax), ++$i, 'VEN-0'.$vendor->id, $vendor->prefix.' '.$vendor->name, $vendor->type, $vendor->cp1, $vendor->phone1,
                anchor($this->title.'/update/'.$vendor->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$vendor->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'vendor_list';

        $vendors = $this->model->get();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Name', 'Type', 'Contact', 'Phone', 'Action');

            $i = 0;
            foreach ($vendors as $vendor)
            {
               $data = array(
                                'name' => 'button',
                                'type' => 'button',
                                'content' => 'Select',
                                'onclick' => 'setvalue(\''.$vendor->name.'\',\'tcust\')'
			     );

                $this->table->add_row
                (
                    ++$i, 'VEN-0'.$vendor->id, $vendor->prefix.' '.$vendor->name, $vendor->type, $vendor->cp1, $vendor->phone1,
                    form_button($data)
                );
            }

            $data['table'] = $this->table->generate();
            $this->load->view('vendor_list', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        if ( $this->cek_relation($uid) == TRUE )
        {
           // Delete vendor
           $this->model->where('id', $uid)->get();
           $this->model->delete();
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    private function cek_relation($id)
    {
        $purchase = $this->purchase->cek_relation($id, $this->title);
        $ap = $this->ap->cek_relation($id, $this->title);
        $ap_payment = $this->ap_payment->cek_relation($id, $this->title);
        $ap_payment_cash = $this->ap_payment_cash->cek_relation($id, $this->title);
        $product = $this->product->cek_relation($id, $this->title);
        if ($purchase == TRUE && $ap == TRUE && $ap_payment == TRUE && $ap_payment_cash == TRUE && $product == TRUE) { return TRUE; } else { return FALSE; }
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'vendor_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('vendor/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_vendor');
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
            $this->model->swiftcode        = $this->input->post('tswiftcode');
            $this->model->website          = $this->input->post('turl');
            $this->model->city             = $this->input->post('ccity');
            $this->model->zip              = $this->input->post('tzip');
            $this->model->notes            = $this->input->post('tnotes');

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
        $data['main_view'] = 'vendor_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('vendor/','<span>back</span>', array('class' => 'back')));

        $data['city'] = $this->city->combo();
        
        $vendor = $this->model->where('id', $uid)->get();

        $data['default']['prefix'] = $vendor->prefix;
        $data['default']['name'] = $vendor->name;
        $data['default']['type'] = $vendor->type;
        $data['default']['contact'] = $vendor->cp1;
        $data['default']['npwp'] = $vendor->npwp;
        $data['default']['address'] = $vendor->address;
        $data['default']['shipaddress'] = $vendor->shipping_address;
        $data['default']['phone'] = $vendor->phone1;
        $data['default']['phone2'] = $vendor->phone2;
        $data['default']['fax'] = $vendor->fax;
        $data['default']['mobile'] = $vendor->hp;
        $data['default']['mail'] = $vendor->email;
        $data['default']['url'] = $vendor->website;
        $data['default']['city'] = $vendor->city;
        $data['default']['zip'] = $vendor->zip;
        $data['default']['notes'] = $vendor->notes;

        $data['default']['accname'] = $vendor->acc_name;
        $data['default']['accno'] = $vendor->acc_no;
        $data['default']['bank'] = $vendor->bank;
        $data['default']['swiftcode'] = $vendor->swiftcode;

	$this->session->set_userdata('curid', $vendor->id);
        $this->load->view('vendor_update', $data);
    }


    public function valid_vendor($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_vendor', "This $this->title is already registered.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    function validation_vendor($name)
    {
	$id = $this->session->userdata('curid');
        $this->model->where_not_in('id', $id);
        $val = $this->model->where('name', $name)->count();
        
	if ($val > 0)
        {
            $this->form_validation->set_message('validation_vendor', 'This vendor is already registered!');
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
        $data['main_view'] = 'vendor_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('vendor/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validation_vendor');
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
            $this->model->swiftcode        = $this->input->post('tswiftcode');
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
            $this->load->view('vendor_update', $data);
        }
    }
    
    function report()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['results'] = $this->model->get(); 
        $page = 'vendor_report'; 
        
        $this->load->view($page, $data);
    }

}

?>