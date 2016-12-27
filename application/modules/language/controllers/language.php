<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Language_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
    }

    private $properti, $modul, $title;

    function index()
    {
        $this->get_last_language();
    }

    function get_last_language()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'language_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $languages = $this->Language_model->get_last_language($this->modul['limit'], $offset)->result();
        $num_rows = $this->Language_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_language');
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
            $this->table->set_heading('#','No', 'Name', 'Code', 'Action');

            $i = 0 + $offset;
            foreach ($languages as $language)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $language->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $language->name, $language->code,
                    anchor($this->title.'/primary/'.$language->id,'<span>update</span>',array('class' => $this->alert_order($language->primary), 'title' => 'primary')).' '.
                    anchor($this->title.'/update/'.$language->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$language->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    private function alert_order($val)
    {
       if ($val == 0) {$class = "credit"; }
       elseif ($val == 1){$class = "settled"; }
       return $class;
    }

    function primary($uid = null)
    {
       $val = $this->Language_model->get_primary()->row();
       $warehouse = array('primary' => 0);
       $this->Language_model->update($val->id,$warehouse);

       $warehouse = array('primary' => 1);
       $this->Language_model->update($uid,$warehouse);
       $this->session->set_flashdata('message', "Primary Changed..!");
       redirect($this->title);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->cek_primary($uid);
        $this->Language_model->delete($uid); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); // set flash data message dengan session
        redirect($this->title);
    }

    function cek_primary($uid = null)
    {
      $val = $this->Language_model->get_language_by_id($uid)->row();
      if ($val->primary == 1)
      {
         $this->session->set_flashdata('message', "primary $this->title can't removed..!");
         redirect($this->title);
      }
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'language_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('language/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|max_length[100]');
        $this->form_validation->set_rules('tcode', 'Code', 'required|max_length[15|callback_valid_code');

        if ($this->form_validation->run($this) == TRUE)
        {
            $language = array('name' => $this->input->post('tname'),'code' => $this->input->post('tcode'));
            
            $this->Language_model->add($language);
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

    function valid_code($code)
    {
        if ($this->Language_model->valid_language($code) == FALSE)
        {
            $this->form_validation->set_message('valid_code', "This $this->title is already registered.!");
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
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'language_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('language/','<span>back</span>', array('class' => 'back')));

        $language = $this->Language_model->get_language_by_id($uid)->row();

        $data['default']['code'] = $language->code;
        $data['default']['name'] = $language->name;

	$this->session->set_userdata('langid', $language->id);
        $this->load->view('language_update', $data);
    }

    function validation_code($code)
    {
	$id = $this->session->userdata('langid');
	if ($this->Language_model->validating_language($code,$id) == FALSE)
        {
            $this->form_validation->set_message('validation_code', 'This language is already registered!');
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
        $data['main_view'] = 'language_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('language/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|max_length[100]');
        $this->form_validation->set_rules('tcode', 'Code', 'required|max_length[15|callback_validation_code');

        if ($this->form_validation->run($this) == TRUE)
        {
            $language = array('name' => $this->input->post('tname'),'code' => $this->input->post('tcode'));

	    $this->Language_model->update($this->session->userdata('langid'), $language);
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('langid'));
        }
        else
        {
            $this->load->view('template', $data);
        }
    }

}

?>