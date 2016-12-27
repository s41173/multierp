<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap_backup extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ap_backup_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->load->library('currency');
        $this->vendor = $this->load->library('vendor');
        $this->user = $this->load->library('admin');
        $this->journal = $this->load->library('journal');
        $this->terbilang = $this->load->library('terbilang');

    }

    private $properti, $modul, $title;
    private $vendor,$user,$journal,$terbilang;

    function index()
    {
        $this->get_last_ap_backup();
    }

    function get_last_ap_backup()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $ap_backups = $this->Ap_backup_model->get_last_ap_backup($this->modul['limit'], $offset)->result();
        $num_rows = $this->Ap_backup_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_ap_backup');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Vendor', 'Notes', 'Currency', 'Acc', 'Total');

            $i = 0 + $offset;
            foreach ($ap_backups as $ap_backup)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap_backup->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'GJ-00'.$ap_backup->no, tgleng($ap_backup->dates), $ap_backup->prefix.' '.$ap_backup->name, $ap_backup->notes, $ap_backup->currency, $this->acc_type($ap_backup->acc), number_format($ap_backup->amount)
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

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'ap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('ap_backup/','<span>back</span>', array('class' => 'back')));

        $ap_backups = $this->Ap_backup_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tstart'),$this->input->post('tend'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Vendor', 'Notes', 'Currency', 'Acc', 'Total');

        $i = 0;
        foreach ($ap_backups as $ap_backup)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap_backup->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'GJ-00'.$ap_backup->no, tgleng($ap_backup->dates), $ap_backup->prefix.' '.$ap_backup->name, $ap_backup->notes, $ap_backup->currency, $this->acc_type($ap_backup->acc), number_format($ap_backup->amount)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function acc_type($val=null)
    {
        switch ($val)
        {
            case 'pettycash': $val = 'Petty cash'; break;
            case 'cash': $val = 'Cash'; break;
            case 'bank': $val = 'Bank'; break;
        }
        return $val;
    }

    //    ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $this->load->view('ap_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $acc = $this->input->post('cacc');
        $status = $this->input->post('cstatus');
        $cur = $this->input->post('ccurrency');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['acc'] = $acc;
        $data['status'] = $status;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Ap_backup_model->report($vendor,$start,$end,$acc,$cur)->result();

        $total = $this->Ap_backup_model->total($vendor,$start,$end,$acc,$cur);
        $data['total'] = $total['amount'];

        $this->load->view('ap_report', $data);

    }

//    ================================ REPORT =====================================

   
}

?>