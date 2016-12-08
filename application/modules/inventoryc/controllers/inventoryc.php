<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventoryc extends MX_Controller
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
        $this->category = $this->load->library('icategories_lib');
        $this->room = $this->load->library('iroom_lib');

        $this->model = new Inventory();
        $this->load->model('Inventory_model','im',TRUE);
    }

    private $properti, $modul, $title, $model, $account,$ap;
    private $user,$journalgl,$currency,$category,$room;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last();
    }
    
    function autocomplete()
    {
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('inventaris')->like('name',$keyword)->get();

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

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'inventory_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['category'] = $this->category->combo_all();
        $data['room'] = $this->room->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $costs = $this->im->get_last($this->modul['limit'], $offset)->result();
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Category', 'Name', 'Room', 'Price', 'Purchase Date', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, 'IN-0'.$cost->no, $cost->currency, ucfirst($cost->category), ucfirst($cost->name), ucfirst($cost->room), number_format($cost->price), tglin($cost->buying), 
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

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'inventory_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['category'] = $this->category->combo_all();
        $data['room'] = $this->room->combo_all();
        

        $cat = $this->input->post('ccategory');
        $room = $this->input->post('croom');
        $name = $this->input->post('tname');
        $date = $this->input->post('tdate');
        
	// ---------------------------------------- //
        $costs = $this->im->search($cat,$room,$name,$date)->result();

	    
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
       $this->table->set_heading('No', 'Code', 'Cur', 'Category', 'Name', 'Room', 'Price', 'Purchase Date', 'Action');

        $i = 0;
        foreach ($costs as $cost)
        {
            $this->table->add_row
            (
                ++$i, 'IN-0'.$cost->no, $cost->currency, ucfirst($cost->category), ucfirst($cost->name), ucfirst($cost->room), number_format($cost->price), tglin($cost->buying), 
                anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();

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
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'inventory_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();  
        $data['category'] = $this->category->combo();  
        $data['room'] = $this->room->combo();
        $data['no'] = $this->counter();
        
        $this->load->view('inventory_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cost_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();  
        $data['category'] = $this->category->combo();  
        $data['room'] = $this->room->combo();
        $data['no'] = $this->counter();

	// Form validation
        $this->form_validation->set_rules('tno', 'No', 'required|numeric');
        $this->form_validation->set_rules('ccur', 'Currency', 'required');
        $this->form_validation->set_rules('ccategory', 'Category', 'required');
        $this->form_validation->set_rules('croom', 'Room', 'required');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_inventory');
        $this->form_validation->set_rules('tdesc', 'Desc', 'required');
        $this->form_validation->set_rules('tdate', 'Purchase Date', 'required');
        $this->form_validation->set_rules('tprice', 'Price', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->no          = $this->input->post('tno');
            $this->model->currency    = $this->input->post('ccur');
            $this->model->category_id = $this->input->post('ccategory');
            $this->model->room        = $this->input->post('croom');
            $this->model->name        = $this->input->post('tname');
            $this->model->desc        = $this->input->post('tdesc');
            $this->model->buying      = $this->input->post('tdate');
            $this->model->price       = $this->input->post('tprice');
            $this->model->log         = $this->session->userdata('log');

            $this->model->save();
            
            // create journal
             $cm = new Control_model();
        
             $inventaris = $this->category->get_account($this->input->post('ccategory'));
             $modal   = $cm->get_id(35);
             
             $this->journalgl->new_journal($this->input->post('tno'), $this->input->post('tdate'),'IJ',$this->input->post('ccur'),"Inventory Journal - ".$this->input->post('tdate'), $this->input->post('tprice'), $this->session->userdata('log'));
             
             $jid = $this->journalgl->get_journal_id('IJ',$this->input->post('tno'));
           
             $this->journalgl->add_trans($jid,$inventaris, $this->input->post('tprice'), 0); // tambah inventaris
             $this->journalgl->add_trans($jid,$modal, 0, $this->input->post('tprice')); // tambah modal
            
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
        $this->acl->otentikasi_admin($this->title);

        $this->model->where('id', $uid)->get();
        
        $this->journalgl->remove_journal('IJ', $this->model->no); // journal gl
        
        $this->model->delete();
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        
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
        
        $data['category'] = $this->category->combo();

        $cost = $this->model->where('id', $uid)->get();
        $data['default']['name']     = $cost->name;
        $data['default']['category'] = $cost->category;
        $data['default']['account']  = $this->account->get_code($cost->account_id);

	$this->session->set_userdata('curid', $cost->id);
        $this->load->view('cost_update', $data);
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
        
        $data['category'] = $this->category->combo();

	// Form validation
        $this->form_validation->set_rules('titem', 'Account', 'required|callback_validation_cost');
        $this->form_validation->set_rules('ccategory', 'Category', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->account_id  = $this->account->get_id_code($this->input->post('titem'));
            $this->model->category    = $this->input->post('ccategory');
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

    public function valid_inventory($name)
    {
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_inventory', "Invalid Inventory..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validation_cost($acc)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('account_id', $this->account->get_id_code($acc))->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_cost', "Invalid Account..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['category'] = $this->category->combo_all();
        $data['room'] = $this->room->combo_all();
        
        $this->load->view('inventory_report_panel', $data);
    }
    
    public function report_process()
    {
        $cat = $this->input->post('ccategory');
        $room = $this->input->post('croom');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        
        $data['start'] = $start;
        $data['end'] = $end;
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['category'] = $this->category->get_name($this->input->post('ccategory'));
        
	// ---------------------------------------- //
        $data['inventory'] = $this->im->report($cat,$room,$start,$end)->result();
        $this->load->view('inventory_report', $data);
    }

}

?>