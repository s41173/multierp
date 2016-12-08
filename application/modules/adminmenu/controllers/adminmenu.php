<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Adminmenu extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Adminmenu_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->compo = $this->components->combo();
    }
    
    private $properti, $modul, $title;
    private $compo;
    
    function index()
    { $this->get_last_adminmenu(); }
    
    function get_last_adminmenu()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'adminmenu_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['query_menu'] = $this->Adminmenu_model->get_adminmenu_name();
        $data['query_modul'] = $this->compo;

	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
        $menus = $this->Adminmenu_model->get_last_adminmenu($this->modul['limit'], $offset)->result(); // ambil data dari db
        $num_rows = $this->Adminmenu_model->count_all_num_rows(); // hitung jumlah baris
        
        
        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_adminmenu');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination
	    
            // library HTML table untuk membuat template table class zebra
            $tmpl = array( 'table_open' => '<table class="tablemaster">', 'row_alt_start' => '<tr class="zebra">', 'row_alt_end' => '</tr>');
            
            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");
            
            //Set heading untuk table
	    $this->table->set_heading('#','No', 'Parent', 'Name', 'Modul', 'Url', 'Order', 'Class', 'ID', 'Target', 'Action');
            $i = 0 + $offset;
            
            foreach ($menus as $menu)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $menu->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, $this->parentname($menu->parent_id), $menu->name, $menu->modul, $menu->url, $menu->menu_order, $menu->class_style, $menu->id_style, $menu->target,
		    anchor($this->title.'/update/'.$menu->id,'<span>update</span>',array('class' => 'update')).' '.
                    anchor($this->title.'/delete/'.$menu->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
            
            // table di generate 
            $data['table'] = $this->table->generate();
            //          fasilitas check all
            $js = "onClick='cekall($i)'";
            $sj = "onClick='uncekall($i)'";
            $data['radio1'] = form_radio('newsletter', 'accept1', FALSE, $js).'Check';
            $data['radio2'] = form_radio('newsletter', 'accept2', FALSE, $sj).'Uncheck';
        }
        else
        {
            $data['message'] = "Not found any $this->title of data!";
        }
	
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    private function parentname($catid)
    {
        if ($catid != 0)
        {
           $menu = $this->Adminmenu_model->get_adminmenu_by_id($catid)->row();
           $val = $menu->name;
        }
        else
        {$val = "top";}
        return $val;
    }
        
    public function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        $img = $this->Adminmenu_model->get_adminmenu_by_id($uid)->row();
        $img = $img->icon;
        if ($img){ $img = "./images/adminmenu/".$img; unlink("$img"); }

        $this->Adminmenu_model->delete($uid); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!!"); // set flash data message dengan session
        redirect($this->title);
    }

    function delete_all()
    {
      $this->acl->otentikasi_admin($this->title);
      $cek = $this->input->post('cek');

      if($cek)
      {
        $jumlah = count($cek);
        for ($i=0; $i<$jumlah; $i++)
        {
            $img = $this->Adminmenu_model->get_adminmenu_by_id($cek[$i])->row();
            $img = $img->icon;
            if ($img){ $img = "./images/adminmenu/".$img; unlink("$img"); }
            $this->Adminmenu_model->delete($cek[$i]);
        }
        $this->session->set_flashdata('message', "$jumlah $this->title successfully removed..!!"); // set flash data message dengan session
        redirect($this->title);
      }
      else
      {
           $this->session->set_flashdata('message', "No $this->title Selected..!!"); // set flash data message dengan session
           redirect($this->title);
      }
    }
    
    function add_process()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'adminmenu_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['query_menu'] = $this->Adminmenu_model->get_adminmenu_name();
        $data['query_modul'] = $this->compo;
        $data['error'] = '';
         
	$this->form_validation->set_rules('tname', 'Password', 'required|callback_valid_name');
        $this->form_validation->set_rules('cparent', 'Parent Adminmenu', '');
        $this->form_validation->set_rules('cmodul', 'Modul', 'required');
        $this->form_validation->set_rules('turl', 'URL', 'required');
        $this->form_validation->set_rules('tmenuorder', 'Menu Order', 'required');
        $this->form_validation->set_rules('tclass', 'Class', '');
        $this->form_validation->set_rules('tid', 'ID', '');
        $this->form_validation->set_rules('ctarget', 'Target', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $config['upload_path']   = './images/adminmenu/';
            $config['file_name']     = $this->input->post('tname');
            $config['allowed_types'] = 'png|jpg';
            $config['overwrite']     = TRUE;
            $config['max_size']	     = '150';
            $config['max_width']     = '200';
            $config['max_height']    = '200';
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload', $config);

            if ( !$this->upload->do_upload("userfile")) // if upload failure
            {
                $data['error'] = $this->upload->display_errors();
                $menu = array('parent_id' => $this->get_top_menu($this->input->post('cparent')),'name' => $this->input->post('tname'),
                              'modul' => $this->input->post('cmodul'), 'url' => $this->input->post('turl'),
                              'menu_order' => $this->input->post('tmenuorder'), 'class_style' => $this->input->post('tclass'),
                              'id_style' => $this->input->post('tid'),'icon' => null, 'target' => $this->input->post('ctarget'));

            }
            else
            {
                $info = $this->upload->data();
                $menu = array('parent_id' => $this->get_top_menu($this->input->post('cparent')),'name' => $this->input->post('tname'),
                              'modul' => $this->input->post('cmodul'), 'url' => $this->input->post('turl'),
                              'menu_order' => $this->input->post('tmenuorder'), 'class_style' => $this->input->post('tclass'),
                              'id_style' => $this->input->post('tid'),'icon' => $info['file_name'], 'target' => $this->input->post('ctarget'));
            }

            $this->Adminmenu_model->add($menu);
            $this->session->set_flashdata('message', "One data $this->title successfully saved!");
            redirect($this->title);
        }
        else { $this->load->view('template', $data); }
    }

    private function get_top_menu($val=null)
    {
        if (!$val) { $val = 0; }
        return $val;
    }

    function valid_name($name)
    {
        if ($this->Adminmenu_model->valid_name($name) == FALSE)
        {
            $this->form_validation->set_message('valid_name', $this->title.' name registered');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function validating_name($name)
    {
	$id = $this->session->userdata('adminmenu');
	if ($this->Adminmenu_model->validating_name($name,$id) == FALSE)
        {
            $this->form_validation->set_message('validating_name', "This $this->title name is already registered!");
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
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'adminmenu_update';
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['query_menu'] = $this->Adminmenu_model->get_adminmenu_name();
        $data['query_modul'] = $this->compo;

        $menu = $this->Adminmenu_model->get_adminmenu_by_id($uid)->row();
        
        $data['default']['parent'] = $menu->parent_id;
	$data['default']['name'] = $menu->name;
        $data['default']['modul'] = $menu->modul;
        $data['default']['url'] = $menu->url;
        $data['default']['menuorder'] = $menu->menu_order;
        $data['default']['class'] = $menu->class_style;
        $data['default']['id'] = $menu->id_style;
        $data['default']['target'] = $menu->target;

        $icon = isset($menu->icon) ? $menu->icon : 'default.png';
        $data['default']['image'] = base_url().'images/adminmenu/'.$icon;
	
	$this->session->set_userdata('adminmenu', $menu->id);
    
       $this->load->view('adminmenu_update', $data);
    }
    
    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi_admin($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'adminmenu_update';
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['query_menu'] = $this->Adminmenu_model->get_adminmenu_name();
        $data['query_modul'] = $this->compo;
        
	$this->form_validation->set_rules('tname', 'Password', 'required|callback_validating_name');
        $this->form_validation->set_rules('cparent', 'Parent Adminmenu', '');
        $this->form_validation->set_rules('cmodul', 'Modul', 'required');
        $this->form_validation->set_rules('turl', 'URL', 'required');
        $this->form_validation->set_rules('tmenuorder', 'Menu Order', 'required');
        $this->form_validation->set_rules('tclass', 'Class', '');
        $this->form_validation->set_rules('tid', 'ID', '');
        $this->form_validation->set_rules('ctarget', 'Target', 'required');
	
        if ($this->form_validation->run($this) == TRUE)
        {   
            $config['upload_path']   = './images/adminmenu/';
            $config['file_name']     = $this->input->post('tname');
            $config['allowed_types'] = 'png|jpg';
            $config['overwrite']     = TRUE;
            $config['max_size']	     = '150';
            $config['max_width']     = '200';
            $config['max_height']    = '200';
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload', $config);

            if ( !$this->upload->do_upload("userfile")) // if upload failure
            {
                $data['error'] = $this->upload->display_errors();
                $menu = array('parent_id' => $this->get_top_menu($this->input->post('cparent')),'name' => $this->input->post('tname'),
                              'modul' => $this->input->post('cmodul'), 'url' => $this->input->post('turl'),
                              'menu_order' => $this->input->post('tmenuorder'), 'class_style' => $this->input->post('tclass'),
                              'id_style' => $this->input->post('tid'), 'target' => $this->input->post('ctarget'));

            }
            else
            {
                $info = $this->upload->data();
                $menu = array('parent_id' => $this->get_top_menu($this->input->post('cparent')),'name' => $this->input->post('tname'),
                              'modul' => $this->input->post('cmodul'), 'url' => $this->input->post('turl'),
                              'menu_order' => $this->input->post('tmenuorder'), 'class_style' => $this->input->post('tclass'),
                              'id_style' => $this->input->post('tid'),'icon' => $info['file_name'], 'target' => $this->input->post('ctarget'));
            }

            $this->Adminmenu_model->update($this->session->userdata('adminmenu'), $menu);
            $this->session->set_flashdata('message', "One $this->title of data successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('adminmenu'));
            $this->session->unset_userdata('adminmenu');
        }
        else
        {
           $this->load->view('adminmenu_update', $data);
        }
    }
    
}

?>