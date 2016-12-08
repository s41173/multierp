<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Log_model', '', TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
    }
    
    private $properti,$modul,$title;
    
    function index()
    { $this->display(); }
    
    function display()
    {
        $this->acl->otentikasi1($this->title);
        
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'log_view';
	$data['form_action'] = site_url($this->title.'/process_log');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
        $logs = $this->Log_model->get_last_log($this->modul['limit'], $offset)->result();
        $num_rows = $this->Log_model->count_all_num_rows();

            // ==================== pagination  ======================
	    $config['base_url'] = site_url($this->title.'/display');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // ==================== pagination  ======================

            // ==================== table  ======================
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');
            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");
	    $this->table->set_heading('No', 'Log-ID', 'Username', 'Date', 'Time', 'Activity', 'Action');
            
            $i = 0 + $offset;
            
            foreach ($logs as $log)
            {
                $no = array('data' => ++$i, 'class' => 'center');
                $this->table->add_row
                (
                    $no, $log->logid, $log->username, tgleng($log->date), $log->time, $log->activity,
                    anchor($this->title.'/delete/'.$log->logid,'<span>delete</span>',array('class'=> 'delete','onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
            $data['table'] = $this->table->generate();
            // ==================== table  ======================
	
	$this->load->view('template', $data);
    }
    
    function process_log()
    {
        $this->form_validation->set_rules('tstart', 'Start Date', 'required');

	if ($this->form_validation->run($this) == TRUE)
        {
            $tgl = $this->input->post('ttgl');
            if ($this->input->post('submit') == 'Search')
            {  redirect($this->title.'/search_log/'.$this->input->post('tstart')); }
            else
            { redirect($this->title.'/delete_log/'.$this->input->post('tstart')); }
        }
        else
        {
            $this->session->set_flashdata('message', 'Validation Error..!!'); // set flash data message dengan session
            redirect($this->title);
        }
    }

    function delete_log($daten)
    {
        $this->acl->otentikasi3($this->title);
        $tgl = date('Y-m-d', strtotime($daten));
        $this->Log_model->delete_log($tgl); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', 'history of  ['.tgleng($daten).'] successfully removed..!!'); // set flash data message dengan session
        redirect($this->title);
    }
    
    function search_log($daten)
    {
        $this->acl->otentikasi3($this->title);
	$data['title'] = $this->properti['name'].' | Administrator  '.ucwords('Search '.$this->modul['title']);
        $data['h2title'] = "Search ".$this->modul['title'];
        $data['main_view'] = 'log_view';
	$data['form_action'] = site_url($this->title.'/process_log');
	$data['link'] = array('link_back' => anchor($this->title,'<span>kembali</span>', array('class' => 'back')));


        $tgl = date('Y-m-d', strtotime($daten));
        $logs = $this->Log_model->search($tgl)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Log-ID', 'Username', 'Date', 'Time', 'Action');

        $i = 0;
        foreach ($logs as $log)
        {
            $this->table->add_row
            (
                ++$i, $log->logid, $log->username, tgleng($log->date), $log->time,
                anchor($this->title.'/delete/'.$log->logid,'<span>delete</span>',array('class'=> 'delete','onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        // table di generate
        $data['table'] = $this->table->generate();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function delete($uid)
    {
//        otentikasi3($this->title);
        $this->Log_model->delete($uid); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', '1 log history successfully removed..!!'); // set flash data message dengan session
        redirect($this->title);
    }
    
    function valid_date($str)
    {
	if(!ereg("^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$", $str))
	{
            $this->form_validation->set_message('valid_date', 'Date format invalid. dd-mm-yyyy');
            return false;
	}
	else { return true; }
    }
    
}

?>