<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gcategory extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Gcategory_model', '', TRUE);
        $this->load->model('Gcategory_spec_model', 'gsm', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
        $this->product = $this->load->library('gproducts');
        $this->unit = $this->load->library('unit_lib');

    }

    private $properti, $modul, $title;
    private $product,$unit;

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
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $categorys = $this->Gcategory_model->get_last_category($this->modul['limit'], $offset)->result();
        $num_rows = $this->Gcategory_model->count_all_num_rows();

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
                    anchor_popup($this->title.'/prints/'.$category->id,'<span>print</span>',array('class' => 'print', 'title' => '')).' '.
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
           $this->gsm->delete_category($uid);
           $this->Gcategory_model->delete($uid);
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    private function cek_relation($id)
    {
        $product = $this->product->cek_relation($id, 'category');
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
            
            $this->Gcategory_model->add($category);
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
        $data['form_action_item'] = site_url($this->title.'/add_item');
	$data['link'] = array('link_back' => anchor('category/','<span>back</span>', array('class' => 'back')));

        $data['unit'] = $this->unit->combo();

        $category = $this->Gcategory_model->get_category_by_id($uid)->row();
        $data['default']['name'] = $category->name;

	$this->session->set_userdata('langid', $category->id);

        // table

        $val = $this->gsm->get_last_item($uid)->result();

    // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Qty', 'Action');

        $i = 0;
        foreach ($val as $value)
        {
            $this->table->add_row
            (
                ++$i, $value->product, $value->qty.' '.$value->unit,
                anchor($this->title.'/delete_item/'.$value->id.'/'.$uid,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();

        $this->load->view('category_update', $data);
    }


    function prints($uid)
    {
        $category = $this->Gcategory_model->get_category_by_id($uid)->row();
        $data['category'] = $category->name;
        $data['items'] = $this->gsm->get_last_item($uid)->result();
        $this->load->view('category_invoice', $data);
    }

    function add_item($po=null)
    {

        $this->form_validation->set_rules('titem', 'Item Name', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('product' => $this->input->post('titem'), 'category' => $this->session->userdata('langid'),
                           'qty' => $this->input->post('tqty'), 'unit' => $this->input->post('cunit'));
            $this->gsm->add($pitem);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_item($id,$po)
    {
        $this->acl->otentikasi2($this->title);

        $this->gsm->delete($id); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/update/'.$po);
    }


    public function valid_category($name)
    {
        if ($this->Gcategory_model->valid_category($name) == FALSE)
        {
            $this->form_validation->set_message('valid_category', "This $this->title is already registered.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    function validation_category($name)
    {
	$id = $this->session->userdata('langid');
	if ($this->Gcategory_model->validating_category($name,$id) == FALSE)
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

	    $this->Gcategory_model->update($this->session->userdata('langid'), $category);
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