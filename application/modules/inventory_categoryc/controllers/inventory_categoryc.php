<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_categoryc extends MX_Controller
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
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account  = $this->load->library('account_lib');
        $this->ap       = $this->load->library('ap_lib');

        $this->model = new Icategory();
    }

    private $properti, $modul, $title, $model, $account,$ap;
    private $user,$journalgl,$currency;

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
        $data['main_view'] = 'icategory_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('inventoryc','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $costs = $this->model->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

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
            $this->table->set_heading('No', 'Category', 'Desc', 'Account', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, ucfirst($cost->name), $cost->desc, $this->account->get_code($cost->account_id).' : '.$this->account->get_name($cost->account_id),
                    anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cost_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_category');
        $this->form_validation->set_rules('tdesc', 'Desc', 'required');
        $this->form_validation->set_rules('titem', 'Account Code', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name  = $this->input->post('tname');
            $this->model->desc  = $this->input->post('tdesc');
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
    
    function delete($uid)
    {
        $in = new Inventory_lib();
        if ($in->cek_relation($uid, 'category_id') == TRUE)
        {
            $this->acl->otentikasi_admin($this->title);
            $this->model->where('id', $uid)->get();
            $this->model->delete();
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "Category related to inventory module..!"); }
        
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

        $cost = $this->model->where('id', $uid)->get();
        $data['default']['name']     = $cost->name;
        $data['default']['desc']     = $cost->desc;
        $data['default']['account']  = $this->account->get_code($cost->account_id);

	$this->session->set_userdata('curid', $cost->id);
        $this->load->view('icategory_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validation_room');
        $this->form_validation->set_rules('tdesc', 'Desc', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->name  = $this->input->post('tname');
            $this->model->desc  = $this->input->post('tdesc');
            $this->model->account_id  = $this->account->get_id_code($this->input->post('titem'));
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true';
        }
        else
        {
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->load->view('room_update', $data);
//            echo validation_errors();
        }
    }
    
    public function valid_account($acc)
    {
        $val = $this->model->where('account_id', $this->account->get_id_code($acc))->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_account', "Invalid Account..!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function valid_category($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_category', "Invalid Category..!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function validation_category($name)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_room', "Invalid Room..!");
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