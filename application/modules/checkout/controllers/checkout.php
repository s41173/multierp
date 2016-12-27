<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Checkout extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Checkout_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->bank = $this->load->library('bank');
        $this->vendor = $this->load->library('vendor_lib');
        $this->account = new Account_lib();
        $this->ap_payment = new Ap_payment_lib();
        $this->ap_payment_cash = new Ap_lib();
        $this->journal = new Journalgl_lib();
    }

    private $properti, $modul, $title, $journal;
    private $bank,$vendor,$account,$ap_payment,$ap_payment_cash;

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
        if ($val == 'purchase') { $val = 'CD-00'; } elseif ($val == 'ap') { $val = 'DJ-00'; }
        elseif ($val == 'ar_refund') { $val = 'RF-00'; } elseif ($val == 'nar_refund') { $val = 'NRF-00'; }
        return $val;
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'check_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));


        $aps = $this->Checkout_model->search($this->input->post('tno'), $this->input->post('tstart'), $this->input->post('tend'),
                                             $this->input->post('ctype'))->result();

        $code = $this->code($this->input->post('ctype'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Cur', 'Check - No', 'Payment', 'Bank', 'Dates', 'Due', 'Amount', 'Action');

        $i = 0;
        foreach ($aps as $ap)
        {

            $this->table->add_row
            (
                ++$i, $ap->currency, $ap->check_no, $code.$ap->no, $this->account->get_code($ap->account).'-'.$this->account->get_name($ap->account), tglin($ap->dates), tglin($ap->due), number_format($ap->amount),
                anchor($this->title.'/process/'.$ap->no.'/'.$this->input->post('ctype').'/'.$ap->amount.'/'.$ap->account.'/'.$ap->due.'/'.$ap->currency,'<span>update</span>',array('class' => $this->alert_date($ap->due), 'title' => 'edit / update'))
//                anchor($this->title.'/details/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => ''))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }
    
    function process($no,$type,$amount=0,$acc,$due,$cur)
    {
       $data = array('post_dated_stts' => 1);
       
       if ($type == 'purchase'){ $this->ap_payment->set_post_stts($no, $data); $notes = 'AP-Payment : CD-00'.$no; }
       elseif ($type == 'ap'){ $this->ap_payment_cash->set_post_stts($no, $data); $notes = 'AP : DJ-00'.$no; }
       
       
       
       $cm = new Control_model();

       $ap       = $cm->get_id(48); // hutang giro
       $account  = $acc;                
        // create journal- GL

       $this->journal->new_journal('00'.$no,$due,'GJ',$cur,'Cheque Process '.$notes,$amount, $this->session->userdata('log'));
       $dpid = $this->journal->get_journal_id('GJ','00'.$no);
       
       $this->journal->add_trans($dpid,$account,0,$amount); // kas
       $this->journal->add_trans($dpid,$ap,$amount,0); // hutang usaha
       
       
       $this->session->set_flashdata('message', "$this->title already processed..!");
       redirect($this->title);
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

        $this->load->view('checkout_report_panel', $data);
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

        $data['reports'] = $this->Checkout_model->report($start,$end,$type)->result();

        $data['total'] = 0;
        $data['tax'] = 0;
        $data['p1'] = 0;
        $data['p2'] = 0;
        $data['costs'] = 0;
        $data['ptotal'] = 0;

        $this->load->view('checkout_report', $data);

    }
   
}

?>