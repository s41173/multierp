<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controlc extends MX_Controller
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
        $this->journal = $this->load->library('journal_lib');
        $this->classification = $this->load->library('classification_lib');
        $this->account = $this->load->library('account_lib');
        $this->component = new Components();
        
        $this->model = new Control();
    }

    private $properti, $modul, $title, $model, $account, $component;
    private $user,$journal,$currency,$classification;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last_control();
    }

    function get_last_control()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'control_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['classification'] = $this->classification->combo_all();
        $data['component'] = $this->component->combo_id();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $controls = $this->model->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();
//        $cl = $accounts->classification->get_iterated();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_control');
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
            $this->table->set_heading('No', 'Desc', 'Account', 'Modul', 'Action');

            $i = 0 + $offset;
            foreach ($controls as $control)
            {
                $control->account->get();

                $this->table->add_row
                (
                    $control->no, $control->desc, $control->account->code.' : '.$control->account->name, ucfirst($this->component->get_name($control->modul)),
                    anchor($this->title.'/update/'.$control->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$control->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
    
    private function counter()
    {
        if ($this->model->count() == 0){  $res = 1; }
        else
        {
           $this->model->select_max('no');
           $res = $this->model->get();
           $res = $res->no+1;
        }
        return $res;
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
        $this->form_validation->set_rules('tdesc', 'Name', 'required|callback_valid_control');
        $this->form_validation->set_rules('titem', 'Account Code', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->no = $this->counter();
            $this->model->desc  = $this->input->post('tdesc');
            $this->model->account_id  = $this->account->get_id_code($this->input->post('titem'));
            $this->model->status  = 0;

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

    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));

        $data['component'] = $this->component->combo_id();
        
        $control = $this->model->where('id', $uid)->get();
        $data['default']['desc']    = $control->desc;
        $data['default']['account'] = $this->account->get_code($control->account_id);
        $data['default']['modul']    = $control->modul;

	$this->session->set_userdata('curid', $control->id);
        $this->load->view('control_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'account_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('account/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('titem', 'Account', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->account_id  = $this->account->get_id_code($this->input->post('titem'));
            $this->model->modul       = $this->input->post('cmodul');
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true';
        }
        else
        {
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
//            $this->load->view('account_update', $data);
//            echo validation_errors();
        }
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        if ($this->cek_status($uid) == TRUE)
        {
           $this->model->where('id', $uid)->get();
           $this->model->delete();
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
        }
        else { $this->session->set_flashdata('message', "Default control account can not removed..!");  }
        
        redirect($this->title);
    }
    
    private function cek_status($id)
    {
        $this->model->where('id', $id)->get();
        if ($this->model->status == 0){ return TRUE; } else { return FALSE; }
    }


    public function valid_control($name)
    {
        $val = $this->model->where('desc', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_control', "Invalid Control..!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function validation_control($acc)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('account_id', $this->account->get_id_code($acc))->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_control', "Invalid Account..!");
            return FALSE;
        }
        else{ return TRUE; }
    }

}

?>