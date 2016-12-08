<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Journal extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Journal_model', '', TRUE);
        $this->load->model('Transaction_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->purchase = $this->load->library('purchase');

    }

    private $properti, $modul, $title,$purchase,$currency;

    public $atts = array('width'=> '800','height'=> '600', 'scrollbars' => 'yes','status'=> 'yes', 'toolbar'=> 'yes',
                         'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                         'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
        $this->get_last_journal();
    }

    function get_last_journal()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'journal_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $journals = $this->Journal_model->get_last_journal($this->modul['limit'], $offset)->result();
        $num_rows = $this->Journal_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_journal');
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

            $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Action');

            $i = 0 + $offset;
            foreach ($journals as $journal)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $journal->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'DT-0'.$journal->id, tgleng($journal->dates), $journal->currency,
                    anchor($this->title.'/confirmation/'.$journal->id,'<span>update</span>',array('class' => $this->post_status($journal->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$journal->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$journal->id,'<span>details</span>',array('class' => 'update', 'title' => ''))
//                    anchor($this->title.'/delete/'.$journal->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'journal_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('journal/','<span>back</span>', array('class' => 'back')));

        $journals = $this->Journal_model->search($this->input->post('tno'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Currency', 'Action');

        $i = 0;
        foreach ($journals as $journal)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $journal->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'DT-0'.$journal->id, tglin($journal->dates), $journal->currency,
                anchor($this->title.'/confirmation/'.$journal->id,'<span>update</span>',array('class' => $this->post_status($journal->approved), 'title' => 'edit / update')).' '.
                anchor($this->title.'/add_trans/'.$journal->id,'<span>details</span>',array('class' => 'update', 'title' => ''))
//                    anchor($this->title.'/delete/'.$journal->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->Transaction_model->delete_journal($uid);

//        panggil lib purchase , sales, untuk delete transaksi

        $this->Journal_model->delete($uid); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); // set flash data message dengan session
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Journal_model->counter();
        
        $this->load->view('journal_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'journal_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Journal_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'Journal Transaction No', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_journal');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $journal = array( 'dates' => $this->input->post('tdate'),  'currency' => $this->input->post('ccurrency'), 
                              'debit' => 0, 'credit' => 0);
            
            $this->Journal_model->add($journal);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('journal_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_transaction/'.$po);
        $data['currency'] = $this->currency->combo();
        $data['code'] = $po;

        $journal = $this->Journal_model->get_journal_by_id($po)->row();

        $data['default']['date'] = $journal->dates;
        $data['default']['currency'] = $journal->currency;

        $data['default']['gj']   = $journal->GJ;
        $data['default']['dp']   = $journal->DP;
        $data['default']['pj']   = $journal->PJ;
        $data['default']['sj']   = $journal->SJ;
        $data['default']['csj']  = $journal->CSJ;
        $data['default']['ds']   = $journal->DS;
        $data['default']['cds']  = $journal->CDS;
        $data['default']['cd']   = $journal->CD;
        $data['default']['cg']   = $journal->CG;
        $data['default']['cr']   = $journal->CR;
        $data['default']['ccr']  = $journal->CCR;
        $data['default']['tr']   = $journal->TR;
        $data['default']['saj']  = $journal->SAJ;
        $data['default']['prj']  = $journal->PRJ;
        $data['default']['arj']  = $journal->ARJ;
        $data['default']['rf']   = $journal->RF;
        $data['default']['aj']   = $journal->AJ;

//        ============================ Transaction Item  =========================================
        $transactions = $this->Transaction_model->get_last_transaction($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Name', 'Type', 'Amount');

        $i = 0;
        foreach ($transactions as $transaction)
        {
            $this->table->add_row
            (
                ++$i, $transaction->code.'00'.$transaction->no, $transaction->name, $transaction->type, number_format($transaction->amount)
            );
        }
//
        $data['table'] = $this->table->generate();
        
        $this->load->view('journal_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_transaction($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('ctype', 'Type', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('name' => $this->input->post('tname'), 'code' => 'GJ', 'journal' => $po, 'no' => $this->Transaction_model->counter_no(),
                           'type' => $this->input->post('ctype'), 'amount' => $this->input->post('tamount'));
            $this->Transaction_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $total_ar = $this->Transaction_model->total($po,'AR');
        $total_ap = $this->Transaction_model->total($po,'AP');

        $journal = array('debit' => $total_ar['amount'], 'credit' => $total_ap['amount']);
	$this->Journal_model->update($po, $journal);
    }

    function delete_transaction($id,$po)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($po,'add_trans');
        $this->cek_code_trans($id,$po);
        
        $this->Transaction_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 transaction successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }

    private function cek_code_trans($id,$po)
    {
        $code = $this->Transaction_model->get_trans_by_id($id)->row();

        if ($code->code != 'GJ')
        {
            $this->session->set_flashdata('message', "transaction can't removed..!"); // set flash data message dengan session
            redirect($this->title.'/add_trans/'.$po);
        }
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('journal/','<span>back</span>', array('class' => 'back')));

        $this->session->set_userdata('jid', $po);

	// Form validation
        $this->form_validation->set_rules('tno', 'Journal Transaction No', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_validating_journal');
        $this->form_validation->set_rules('tcur', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $journal = array('dates' => $this->input->post('tdate'),  'currency' => $this->input->post('tcur'));

            $this->Journal_model->update($po, $journal);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('journal_transform', $data);
            echo validation_errors();
        }
        $this->session->unset_userdata('jid');
    }


// ==========================  approval  ===============================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $journal = $this->Journal_model->get_journal_by_id($pid)->row();

        if ($journal->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); 
           redirect($this->title);
        }
        else
        {
            if ($this->purchase->cek_approval_po($journal->dates,$journal->currency) == TRUE)
            {
                 $data = array('approved' => 1);
                 $this->Journal_model->update($pid, $data);

                 $this->session->set_flashdata('message', "$this->title DT-00$pid confirmed..!"); 
                 redirect($this->title);
            }
            else
            {
                $this->session->set_flashdata('message', "$this->title DT-00$pid can't approved..!");
                redirect($this->title);
            }
        }

    }

    private function cek_confirmation($jid=null,$page=null)
    {
        $journal = $this->Journal_model->get_journal_by_id($jid)->row();

        if ( $journal->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - Journal approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$jid); } else { redirect($this->title); }
        }
    }

// ===========================  approval  ==============================================


    public function valid_journal($dates)
    {
        $currency = $this->input->post('ccurrency');
        if ($this->Journal_model->valid_journal($dates,$currency) == FALSE)
        {
            $this->form_validation->set_message('valid_journal', "Journal already created.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function validating_journal($dates)
    {
        $currency = $this->input->post('ccurrency');
        $id = $this->session->userdata('jid');
        if ($this->Journal_model->validating_journal($dates,$currency,$id) == FALSE)
        {
            $this->form_validation->set_message('validating_journal', "Journal already created.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }


//    =========================== PRINT ================================================


   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $journal = $this->Journal_model->get_journal_by_id($po)->row();

       $data['total_PJ'] = $journal->PJ;
       $data['total_GJ'] = $journal->GJ;
       $data['total_DP'] = $journal->DP;
       $data['total_CD'] = $journal->CD;
       $data['total_CG'] = $journal->CG;
       $data['total_TR'] = $journal->TR;
       
       $data['total_SJ'] = $journal->SJ;
       $data['total_CSJ'] = $journal->CSJ;
       $data['total_DS'] = $journal->DS;
       $data['total_CDS'] = $journal->CDS;
       $data['total_CR'] = $journal->CR;
       $data['total_CCR'] = $journal->CCR;

       $data['total_SAJ'] = $journal->SAJ;
       $data['total_PRJ'] = $journal->PRJ;
       $data['total_ARJ'] = $journal->ARJ;
       $data['total_RF'] = $journal->RF;

       $data['total_AJ'] = $journal->AJ;

//
       $data['pono'] = $po;
       $data['dates'] = tgleng($journal->dates);
       $data['cur'] = $journal->currency;
       if ($journal->approved == 1){ $data['status'] = 'Approved';  } else { $data['status'] = 'Not Approved'; }

       $data['company'] = $this->properti['name'];

//       Transaction
       $data['PJ'] = $this->Transaction_model->get_transaction_type($po,'PJ')->result();
       $data['GJ'] = $this->Transaction_model->get_transaction_type($po,'GJ')->result();
       $data['DP'] = $this->Transaction_model->get_transaction_type($po,'DP')->result();
       $data['CD'] = $this->Transaction_model->get_transaction_type($po,'CD')->result();
       $data['CG'] = $this->Transaction_model->get_transaction_type($po,'CG')->result();
       $data['TR'] = $this->Transaction_model->get_transaction_type($po,'TR')->result();

       $data['SJ']  = $this->Transaction_model->get_transaction_type($po,'SJ')->result();
       $data['CSJ'] = $this->Transaction_model->get_transaction_type($po,'CSJ')->result();
       $data['DS']  = $this->Transaction_model->get_transaction_type($po,'DS')->result();
       $data['CDS'] = $this->Transaction_model->get_transaction_type($po,'CDS')->result();
       $data['CR']  = $this->Transaction_model->get_transaction_type($po,'CR')->result();
       $data['CCR'] = $this->Transaction_model->get_transaction_type($po,'CCR')->result();

       $data['SAJ'] = $this->Transaction_model->get_transaction_type($po,'SAJ')->result();
       $data['PRJ'] = $this->Transaction_model->get_transaction_type($po,'PRJ')->result();
       $data['ARJ'] = $this->Transaction_model->get_transaction_type($po,'ARJ')->result();
       $data['RF'] = $this->Transaction_model->get_transaction_type($po,'RF')->result();
       $data['AJ'] = $this->Transaction_model->get_transaction_type($po,'AJ')->result();

       $this->load->view('journal_invoice', $data);
   }

//    =========================== PRINT ================================================

//    ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $this->load->view('journal_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $cur = $this->input->post('ccurrency');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Journal_model->report($cur,$start,$end)->result();

        $this->load->view('journal_report', $data);

    }

//    ================================ REPORT =====================================

}

?>