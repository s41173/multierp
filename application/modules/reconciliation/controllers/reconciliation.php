<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reconciliation extends MX_Controller
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
        $this->journal = $this->load->library('journal_lib');
        $this->account  = $this->load->library('account_lib');
        $this->ap       = $this->load->library('ap_lib');
        $this->category = $this->load->library('categories_lib');

        $this->load->model('Ledger_model','lm',TRUE);
    }

    private $properti, $modul, $title, $model, $account,$ap;
    private $user,$journal,$currency,$category;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->display();
    }
    
    function display()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'recon_view';
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));   
        
        $data['form_action'] = site_url($this->title.'/search');
        
        $data['debit'] = 0;
        $data['credit'] = 0;
        $data['diff']   = 0;
        $data['current'] = 0; 
        
        $this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'].'- '.  ucfirst($this->input->post('cacc'));
        $data['main_view'] = 'recon_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $cm = new Control_model();
        
        $bank     = $cm->get_id(22);
        $kas      = $cm->get_id(13);
        $kaskecil = $cm->get_id(14);
        $account  = 0;
        
        switch ($this->input->post('cacc')) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }              

	// ---------------------------------------- //
        $ledgers = $this->lm->get_ledger($account,$this->input->post('tstart'),$this->input->post('tend'))->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Date', 'Ref', 'Note', 'Debit', 'Credit');

        $i = 0;
        foreach ($ledgers as $ledger)
        {
            $this->table->add_row
            (
                ++$i, tgleng($ledger->dates), $ledger->code.'-'.$ledger->no, $ledger->notes, number_format($ledger->debit), number_format($ledger->credit)
            );
        }

        $data['table'] = $this->table->generate();
        
        $total = $this->lm->get_sum_balance($account,$this->input->post('tstart'),$this->input->post('tend'))->row_array();
        $data['debit'] = $total['debit'];
        $data['credit'] = $total['credit'];
        $data['diff']   = intval($this->input->post('tbalance')) - intval($total['debit'] - $total['credit']);
        $data['current'] = intval($this->input->post('tbalance'));

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    
    public function report()
    {
        $this->acl->otentikasi1($this->title);
        $data['log'] = $this->session->userdata('log');
        $data['form_action'] = site_url($this->title.'/report_process');
        $data['company'] = $this->properti['name'];
        $this->load->view('recon_report_panel', $data);
    }
    
    function report_process()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        
        $data['start'] = tglin($this->input->post('tstart'));
        $data['end'] = tglin($this->input->post('tend'));
        $data['acc'] = ucfirst($this->input->post('cacc'));
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        
        $cm = new Control_model();
        
        $bank     = $cm->get_id(22);
        $kas      = $cm->get_id(13);
        $kaskecil = $cm->get_id(14);
        $account  = 0;
        
        switch ($this->input->post('cacc')) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }              

	// ---------------------------------------- //
        $data['ledgers'] = $this->lm->get_ledger($account,$this->input->post('tstart'),$this->input->post('tend'))->result();

        $total = $this->lm->get_sum_balance($account,$this->input->post('tstart'),$this->input->post('tend'))->row_array();
        $data['debit'] = $total['debit'];
        $data['credit'] = $total['credit'];
        $data['diff']   = intval($this->input->post('tbalance')) - intval($total['debit'] - $total['credit']);
        $data['current'] = intval($this->input->post('tbalance'));

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('recon_report', $data);
    }

}

?>