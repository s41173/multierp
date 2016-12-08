<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Classificationc extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->ar_payment      = $this->load->library('ar_payment');
        $this->ap_payment      = $this->load->library('ap_payment_lib');
        $this->ap_payment_cash = $this->load->library('ap_payment_cash');

        $this->model = new Classification();
    }

    private $properti, $modul, $title, $model;
    private $ap_payment, $ap_payment_cash, $ar_payment;

    function index()
    {
        $this->get_last_classification();
    }

    function get_last_classification()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'classification_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $classifications    = $this->model->get($this->modul['limit'], $offset);
        $num_rows           = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_classification');
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
            $this->table->set_heading('#','No', 'Code', 'Name', 'Type', 'Action');

            $i = 0 + $offset;
            foreach ($classifications as $classification)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $classification->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $classification->no, $classification->name, $classification->type,
                    anchor($this->title.'/update/'.$classification->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$classification->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        if ( $this->cek_relation($uid) == TRUE && $this->cek_status($uid) == TRUE)
        {
            $this->model->where('id', $uid)->get();
            $this->model->delete();
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }
    
    private function cek_status($id)
    {
        $this->model->where('id', $id)->get();
        if ($this->model->status == 1){ return FALSE; } else { return TRUE; }
    }

    private function cek_relation($id)
    {
        $m = new Account();
        $res = $m->where('classification_id', $id)->count();
        if ($res > 0){ return FALSE; }else { return TRUE; }
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'classification_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('classification/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tno', 'No', 'required|callback_valid_classification');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_name');
        $this->form_validation->set_rules('ctype', 'Acc Type', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->name  = $this->input->post('tname');
            $this->model->no    = $this->input->post('tno');
            $this->model->type  = $this->input->post('ctype');
            
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
        $data['main_view'] = 'classification_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('classification/','<span>back</span>', array('class' => 'back')));
        
        $classification = $this->model->where('id', $uid)->get();

        $data['default']['name']     = $classification->name;
        $data['default']['no']       = $classification->no;
        $data['default']['type']     = $classification->type;

	$this->session->set_userdata('curid', $classification->id);
        $this->load->view('classification_update', $data);
    }


    public function valid_classification($no)
    {
        $val = $this->model->where('no', $no)->count();
        
        if ($val > 0)
        {
            $this->form_validation->set_message('valid_classification', "This $this->title no is already registered.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function valid_name($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_name', "This $this->title name is already registered.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function validation_classification($no)
    {
	$id = $this->session->userdata('curid');
        
        $this->model->where_not_in('id', $id);
        $val = $this->model->where('no', $no)->count();

	if ($val > 0)
        {
            $this->form_validation->set_message('validation_classification', 'This classification is already registered!');
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    function valid_code($value)
    {
	$id = $this->session->userdata('curid');
        $val = $this->model->where('id',$id)->get();
        
        if ($val->no != $value)
        {
            if ($this->cek_relation($id) == FALSE)
            {
                $this->form_validation->set_message('valid_code', 'This classification is related to another component!');
                return FALSE;
            }
            else{ return TRUE; } 
        }
        else {return TRUE;}
    }

    function validation_name($name)
    {
	$id = $this->session->userdata('curid');

        $this->model->where_not_in('id', $id);
        $val = $this->model->where('name', $name)->count();

	if ($val > 0)
        {
            $this->form_validation->set_message('validation_name', 'This classification is already registered!');
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
        $data['main_view'] = 'classification_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('classification/','<span>back</span>', array('class' => 'back')));

	// Form validation

        $this->form_validation->set_rules('tno', 'No', 'required|callback_validation_classification|callback_valid_code');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validation_name');
        $this->form_validation->set_rules('ctype', 'Acc Type', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();
            
            $this->model->name  = $this->input->post('tname');
            $this->model->no    = $this->input->post('tno');
            $this->model->type  = $this->input->post('ctype');

            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
        }
        else
        {
            $this->load->view('classification_update', $data);
        }
    }

}

?>