<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Phase extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Phase_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->customer = $this->load->library('customer_lib');
        $this->user = $this->load->library('admin_lib');
    }

    private $properti, $modul, $title;
    private $customer,$user;

    function index()
    {
        $this->get_last_phase();
    }

    function get_last_phase()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'phase_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

	// ---------------------------------------- //
        $phases = $this->Phase_model->get_last_phase()->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Customer', 'Period', 'Date', 'Amount', 'Action');

        $i = 0;
        foreach ($phases as $phase)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $phase->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CO-00'.$phase->contract, $this->customer->get_customer_name($phase->customer), $phase->no, tglin($phase->dates), number_format($phase->amount),
                anchor($this->title.'/confirmation/'.$phase->id,'<span>update</span>',array('class' => $this->post_status($phase->id), 'title' => 'edit / update'))
            );
        }

        $data['table'] = $this->table->generate();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'phase_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('phase/','<span>back</span>', array('class' => 'back')));

        $phases = $this->Phase_model->search($this->input->post('cmonth'), $this->input->post('tyear'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Customer', 'Period', 'Date', 'Amount', 'Action');

        $i = 0;
        foreach ($phases as $phase)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $phase->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CO-00'.$phase->contract, $this->customer->get_customer_name($phase->customer), $phase->no, tglin($phase->dates), number_format($phase->amount),
                anchor($this->title.'/confirmation/'.$phase->id,'<span>update</span>',array('class' => $this->post_status($phase->id), 'title' => 'edit / update'))
            );
        }
        
        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       $val = $this->Phase_model->get_phase_by_id($val)->row();
       if ($val->status == 0) {$class = "notapprove"; }
       else{$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $phase = array('status' => 1);
        $this->Phase_model->update($pid, $phase);
        $this->session->set_flashdata('message', "1 phase successfully confirmed..!");
        redirect($this->title);
    }

}

?>