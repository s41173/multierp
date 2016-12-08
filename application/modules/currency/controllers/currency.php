<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Currency extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
        $this->model = new Currencies();

    }

    private $properti, $modul, $title, $model;

    function index()
    {
        $this->get_last_currency();
    }

    function get_last_currency()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'currency_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $currencys = $this->model->get($this->modul['limit'],$offset);
        $num_rows  = $this->model->count();


        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_currency');
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
            $this->table->set_heading('#','No', 'Code', 'Name', 'Action');

            $i = 0 + $offset;
            foreach ($currencys as $currency)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $currency->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $currency->code, $currency->name,
                    anchor($this->title.'/update/'.$currency->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$currency->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->model->where('id', $uid)->get();
        $this->model->delete();
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); // set flash data message dengan session
        redirect($this->title);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'currency_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('currency/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_currency');
        $this->form_validation->set_rules('tcode', 'Code', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name = $this->input->post('tname');
            $this->model->code = $this->input->post('tcode');
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
        $data['main_view'] = 'currency_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('currency/','<span>back</span>', array('class' => 'back')));

        $currency = $this->model->where('id', $uid)->get();

        $data['default']['name'] = $currency->name;
        $data['default']['code'] = $currency->code;

	$this->session->set_userdata('curid', $currency->id);
        $this->load->view('currency_update', $data);
    }


    public function valid_currency($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_currency', "This $this->title is already registered.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function validation_currency($name)
    {
	$id = $this->session->userdata('curid');
        $this->model->where_not_in('id', $id);
        $val = $this->model->where('name', $name)->count();
        
	if ($val > 0)
        {
            $this->form_validation->set_message('validation_currency', 'This currency is already registered!');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'currency_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('currency/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|max_length[100]|callback_validation_currency');
        $this->form_validation->set_rules('tcode', 'Code', 'required|max_length[100]');

        if ($this->form_validation->run($this) == TRUE)
        {
            $currency = $this->model->where('id', $this->session->userdata('curid'))->get();
            
            $this->model->name = $this->input->post('tname');
            $this->model->code = $this->input->post('tcode');
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
        }
        else
        {
            $this->load->view('currency_update', $data);
        }
    }

}

?>