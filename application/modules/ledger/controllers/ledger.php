<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ledger extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ledger_model', 'lm', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency   = $this->load->library('currency_lib');
        $this->user       = $this->load->library('admin_lib');
        $this->account    = $this->load->library('account_lib');

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency, $account;
    private $user;

    function index()
    {
        $this->start();
    }
    
    function start()
    {
       $this->acl->otentikasi1($this->title);
       $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
       $data['h2title'] = $this->modul['title'];
       $data['main_view'] = 'ledger_view';
       $data['form_action'] = site_url($this->title.'/search');
       $data['form_action_graph'] = site_url($this->title.'/get_last_sales');
       $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
       
       $data['currency'] = $this->currency->combo();
       
       $data['begin']    = 0;
       $data['end']      = 0;
       $data['mutation'] = 0;
       $data['debit']    = 0;
       $data['credit']   = 0;
       
       $this->load->view('template', $data);
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);
        
        $acc   = $this->input->post('titem');
        $start = $this->input->post('tstart');
        $end   = $this->input->post('tend');

        $accname = null; if($acc){ $accname =  $this->account->get_name($this->account->get_id_code($acc)); }
        
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'].' '.$accname;
        $data['main_view'] = 'ledger_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last_sales');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        if($acc){ $ledgers = $this->lm->get_ledger($this->account->get_id_code($acc),$start,$end)->result(); }
        else { $ledgers = null; }

        $atts = array('width'=> '800','height'=> '500',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 500)/2)+\'');

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Debit', 'Credit', 'Action');

        $i = 0;
        if ($ledgers)
        {
            foreach ($ledgers as $ledger)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ledger->id,'checked'=> FALSE, 'style'=> 'margin:0px');

                $this->table->add_row
                (
                    ++$i, $ledger->code.'-00'.$ledger->no, $ledger->currency, tglin($ledger->dates), $this->cek_space($ledger->notes), number_format($ledger->debit), number_format($ledger->credit),
                    anchor('journalgl/add_trans/'.$ledger->no.'/'.$ledger->code,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor_popup($this->title.'/voucher/'.$ledger->no,'<span>print</span>',$atts)
                );
            }

        $data['table'] = $this->table->generate();
        }
        // ===== chart  =======
        $data['graph'] = $this->chart($this->input->post('ccurrency'),$this->account->get_id_code($acc));
        
        // balance
        $bl = $this->get_balance($this->account->get_id_code($acc));
        $data['begin'] = $bl[0];
        $data['end'] = $bl[1];
        $data['mutation'] = $bl[2];
        $data['debit'] = $bl[3];
        $data['credit'] = $bl[4];
        
        
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function get($acc=null)
    {
        $this->acl->otentikasi1($this->title);

        $accname = null; if($acc){ $accname =  $this->account->get_name($this->account->get_id_code($acc)); }
        
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'].' '.$accname;
        $data['main_view'] = 'ledger_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last_sales');
        $data['link'] = array('link_back' => anchor('accountc/','<span>back</span>', array('class' => 'back')));
        
        $ps = new Period();
        $ps->get();

        $data['currency'] = $this->currency->combo();
        if($acc){ $ledgers = $this->lm->get_monthly($this->account->get_id_code($acc),$ps->month,$ps->year)->result(); }
        else { $ledgers = null; }

        $atts = array('width'=> '800','height'=> '500',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 500)/2)+\'');

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Debit', 'Credit', 'Action');

        $i = 0;
        if ($ledgers)
        {
            foreach ($ledgers as $ledger)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ledger->id,'checked'=> FALSE, 'style'=> 'margin:0px');

                $this->table->add_row
                (
                    ++$i, 'JT-00'.$ledger->no, $ledger->currency, tglin($ledger->dates), $this->cek_space($ledger->notes), number_format($ledger->debit), number_format($ledger->credit),
                    anchor('journalgl/add_trans/'.$ledger->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor_popup($this->title.'/voucher/'.$ledger->no,'<span>print</span>',$atts)
                );
            }

        $data['table'] = $this->table->generate();
        }
        // ===== chart  =======
        $data['graph'] = $this->chart($this->input->post('ccurrency'),$this->account->get_id_code($acc));
        
        // balance
        $bl = $this->get_balance($this->account->get_id_code($acc));
        $data['begin'] = $bl[0];
        $data['end'] = $bl[1];
        $data['mutation'] = $bl[2];
        $data['debit'] = $bl[3];
        $data['credit'] = $bl[4];
        
        
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    private function get_balance($acc=null)
    {
        $ps = new Period();
        $gl = new Gl();
        $bl = new Balance();
        $ps->get();
        
        $gl->where('approved', 1);
        $gl->where('MONTH(dates)', $ps->month);
        $gl->where('YEAR(dates)', $ps->year)->get();
        
        $bl->where('month', $ps->month);
        $bl->where('year', $ps->year);
        $bl->where('account_id', $acc)->get();
                
        $this->load->model('Account_model','am',TRUE);
        $val = $this->am->get_balance($acc,$ps->month,$ps->year)->row_array();
        
        $res[0] = $bl->beginning; //begin
        $res[1] = $bl->beginning + $val['vamount']; //end
        $res[2] = $val['vamount']; // mutation
        $res[3] = $val['debit']; // debit
        $res[4] = $val['credit']; // credit
        
        return $res;
        
    }
    
    public function chart($cur='IDR',$acc=null)
    {
        $ps = new Period();
        $gl = new Gl();
        $bl = new Balance();
        $ps->get();
        
//        $gl->where('approved', 1);
//        $gl->where('MONTH(dates)', $ps->month);
//        $gl->where('YEAR(dates)', $ps->year);
//        $gl->order_by("dates", "asc")->get();
        
        $gl = $this->lm->get_monthly($acc,$ps->month,$ps->year)->result();
        
        $bl->where('month', $ps->month);
        $bl->where('account_id', $acc);
        $bl->where('year', $ps->year)->get();
        
        $i=0; $j=1; $k=2;
        $arpData = null;
        $result = $bl->beginning; 
        
        foreach ($gl as $value)
        {
            $res = $this->lm->get_balance($acc,$value->no)->row_array();
            $res[$i] = $result;

            $arpData[$i][$j] = tglshort($value->dates);
            $arpData[$i][$k] = $result + intval($res['vamount']);
            $result = $res[$i];
            $i++;
        }

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Csales", "98%", 400, false, false) ;
        return $graph;
    }

    private function cek_space($val)
    {  $res = explode("<br />",$val);  if (count($res) == 1) { return $val;  } else { return implode('', $res); } }

//    ===================== approval ===========================================

    public function valid_date($date)
    {
        $cur = $this->input->post('ccurrency');
        if ($this->journal->valid_journal($date,$cur) == FALSE)
        {
            $this->form_validation->set_message('valid_date', "Journal [ ".tgleng($date)." ] - ".$cur." already approved.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================

   function voucher($no=0)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Voucher'.$this->modul['title'];
       
       $gl = new Gl();
       $gl->where('no', $no)->get();
       
       $data['code']    = $gl->no;
       $data['dates']  = $gl->dates;
       $data['currency']   = $gl->currency;
       $data['notes'] = $gl->notes;
       $data['log']   = $gl->log;
       $data['codetrans']   = $gl->code;
       $data['docno']   = $gl->docno;
       $data['balance']   = $gl->balance;
       $data['user'] = $this->session->userdata("username");
       
       $data['items'] = $gl->order_by('id', 'desc')->transaction->get();
       $data['account'] = $this->account;

       // property display
       $data['p_name'] = $this->properti['name'];
       $data['logo'] = $this->properti['logo'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('ledger_voucher', $data);
       
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('sales/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('ledger_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $accstart = $this->input->post('taccstart');
        $accend   = $this->input->post('taccend');

        $data['cur'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

    //        Property Details
        $data['company'] = $this->properti['name'];
        $data['accounts'] = $this->lm->report($accstart,$accend)->result();

        $this->load->view('journal_invoice', $data);
        
    }


// ====================================== REPORT =========================================

    public function valid_part($part,$po)
    {
        if ($this->sinvoice->valid_part($part,$po) == FALSE)
        {
            $this->form_validation->set_message('valid_part', "Payment term already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }


}

?>