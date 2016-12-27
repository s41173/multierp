<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Category_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
        $this->product = $this->load->library('products_lib');

    }

    private $properti, $modul, $title;
    private $product;

    function index()
    {
        $this->get_last_category();
    }

    function get_last_category()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'category_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $categorys = $this->Category_model->get_last_category($this->modul['limit'], $offset)->result();
        $num_rows = $this->Category_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_category');
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
            $this->table->set_heading('#','No', 'Name', 'Action');

            $i = 0 + $offset;
            foreach ($categorys as $category)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $category->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $category->name,
                    anchor($this->title.'/update/'.$category->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$category->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        if ( $this->cek_relation($uid) == TRUE )
        {
           $this->Category_model->delete($uid);
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    private function cek_relation($id)
    {
        $product = $this->product->cek_relation($id, $this->title);
        if ($product == TRUE) { return TRUE; } else { return FALSE; }
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'category_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('category/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_category');

        if ($this->form_validation->run($this) == TRUE)
        {
            $category = array('name' => $this->input->post('tname'));
            
            $this->Category_model->add($category);
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
        $data['main_view'] = 'category_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('category/','<span>back</span>', array('class' => 'back')));

        $category = $this->Category_model->get_category_by_id($uid)->row();

        $data['default']['name'] = $category->name;

	$this->session->set_userdata('langid', $category->id);
        $this->load->view('category_update', $data);
    }


    public function valid_category($name)
    {
        if ($this->Category_model->valid_category($name) == FALSE)
        {
            $this->form_validation->set_message('valid_category', "This $this->title is already registered.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    function validation_category($name)
    {
	$id = $this->session->userdata('langid');
	if ($this->Category_model->validating_category($name,$id) == FALSE)
        {
            $this->form_validation->set_message('validation_category', 'This category is already registered!');
            return FALSE;
        }
        else { return TRUE; }
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'category_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('category/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|max_length[100]|callback_validation_category');

        if ($this->form_validation->run($this) == TRUE)
        {
            $category = array('name' => $this->input->post('tname'));

	    $this->Category_model->update($this->session->userdata('langid'), $category);
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('langid'));
            $this->session->unset_userdata('langid');

        }
        else
        {
            $this->load->view('category_update', $data);
        }
    }

}

?>