<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Checkin extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Checkin_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->bank = $this->load->library('bank');
        $this->customer = new Customer_lib();

    }

    private $properti, $modul, $title;
    private $bank,$customer;

    function index()
    { $this->get_last(); }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'check_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    private function code($val)
    {
        if ($val == 'sales') { $val = 'CR-00'; } elseif ($val == 'nsales') { $val = 'NCR-00'; } return $val;
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'check_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));


        $aps = $this->Checkin_model->search($this->input->post('tno'), $this->input->post('tstart'), $this->input->post('tend'),
                                             $this->input->post('ctype'))->result();

        $code = $this->code($this->input->post('ctype'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Check - No', 'Payment', 'Bank', 'Dates', 'Due', 'Amount', 'Action');

        $i = 0;
        foreach ($aps as $ap)
        {

            $this->table->add_row
            (
                  ++$i, $ap->check_no, $code.$ap->no, $this->bank->get_bank_name($ap->bank), tgleng($ap->dates), tgleng($ap->due), number_format($ap->amount),
                anchor($this->title,'<span>update</span>',array('class' => $this->alert_date($ap->due), 'title' => 'edit / update'))
//                anchor($this->title.'/details/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => ''))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function alert_date($due)
    {
        $due = strtotime($due);
        $now = strtotime(date('Y-m-d'));
        $res = null;
        if ($now > $due) { $res = "approve"; } else { $res = "notapprove"; } return $res;
    }

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $this->load->view('checkin_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $data['type'] = $type;

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['reports'] = $this->Checkin_model->report($start,$end,$type)->result();

        $data['total'] = 0;
        $data['tax'] = 0;
        $data['p1'] = 0;
        $data['p2'] = 0;
        $data['costs'] = 0;
        $data['ptotal'] = 0;

        $this->load->view('checkin_report', $data);

    }
   
}

?>