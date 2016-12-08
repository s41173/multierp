<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_transaction extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Warehouse_transaction_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->product = $this->load->library('products_lib');
        $this->currency = $this->load->library('currency_lib');

    }

    private $properti, $modul, $title, $product, $currency;

    public $atts = array('width'=> '800','height'=> '600', 'scrollbars' => 'yes','status'=> 'yes', 'toolbar'=> 'yes',
                         'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                         'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
        $this->get_last_warehouse_transaction();
    }

    function get_last_warehouse_transaction()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'warehouse_transaction_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_reference/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $warehouse_transactions = $this->Warehouse_transaction_model->get_last_warehouse_transaction($this->modul['limit'], $offset)->result();
        $num_rows = $this->Warehouse_transaction_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_warehouse_transaction');
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

            $this->table->set_heading('No', 'Code', 'Date', 'Cur', 'Transaction Code', 'Product', 'IN', 'OUT', 'Nominal', 'Amount');

            $i = 0 + $offset;
            foreach ($warehouse_transactions as $warehouse_transaction)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $warehouse_transaction->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'WT-0'.$warehouse_transaction->id, tglin($warehouse_transaction->dates), $warehouse_transaction->currency, $warehouse_transaction->code, $this->product->get_name($warehouse_transaction->product), $warehouse_transaction->in.' '.$this->product->get_unit($warehouse_transaction->product), $warehouse_transaction->out.' '.$this->product->get_unit($warehouse_transaction->product),
                    number_format($warehouse_transaction->price), number_format($warehouse_transaction->amount)
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
        $data['main_view'] = 'warehouse_transaction_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('warehouse_transaction/','<span>back</span>', array('class' => 'back')));

        $warehouse_transactions = $this->Warehouse_transaction_model->search($this->product->get_id($this->input->post('tproduct')), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
       $this->table->set_heading('No', 'Code', 'Date', 'Cur', 'Transaction Code', 'Product', 'IN', 'OUT', 'Nominal', 'Amount');

        $i = 0;
        foreach ($warehouse_transactions as $warehouse_transaction)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $warehouse_transaction->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'WT-0'.$warehouse_transaction->id, tglin($warehouse_transaction->dates), $warehouse_transaction->currency, $warehouse_transaction->code, $this->product->get_name($warehouse_transaction->product), $warehouse_transaction->in.' '.$this->product->get_unit($warehouse_transaction->product), $warehouse_transaction->out.' '.$this->product->get_unit($warehouse_transaction->product),
                number_format($warehouse_transaction->price), number_format($warehouse_transaction->amount)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->Transaction_model->delete_warehouse_transaction($uid);

//        panggil lib purchase , sales, untuk delete transaksi

        $this->Warehouse_transaction_model->delete($uid); // memanggil model untuk mendelete data
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
        $data['code'] = $this->Warehouse_transaction_model->counter();
        redirect($this->title);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'warehouse_transaction_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Warehouse_transaction_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'Warehouse_transaction Transaction No', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_warehouse_transaction');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $warehouse_transaction = array( 'dates' => $this->input->post('tdate'),  'currency' => $this->input->post('ccurrency'),
                              'debit' => 0, 'credit' => 0);
            
            $this->Warehouse_transaction_model->add($warehouse_transaction);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('warehouse_transaction_form', $data);
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

        $warehouse_transaction = $this->Warehouse_transaction_model->get_warehouse_transaction_by_id($po)->row();

        $data['default']['date'] = $warehouse_transaction->dates;
        $data['default']['currency'] = $warehouse_transaction->currency;

        $data['default']['gj'] = $warehouse_transaction->GJ;
        $data['default']['dp'] = $warehouse_transaction->DP;
        $data['default']['pj'] = $warehouse_transaction->PJ;
        $data['default']['sj'] = $warehouse_transaction->SJ;
        $data['default']['ds'] = $warehouse_transaction->DS;
        $data['default']['nsj'] = $warehouse_transaction->NSJ;
        $data['default']['nds'] = $warehouse_transaction->NDS;
        $data['default']['cd'] = $warehouse_transaction->CD;
        $data['default']['cg'] = $warehouse_transaction->CG;
        $data['default']['cr'] = $warehouse_transaction->CR;
        $data['default']['ncr'] = $warehouse_transaction->NCR;
        $data['default']['tr'] = $warehouse_transaction->TR;
        $data['default']['saj'] = $warehouse_transaction->SAJ;
        $data['default']['prj'] = $warehouse_transaction->PRJ;

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
//                anchor($this->title.'/delete_transaction/'.$transaction->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
//
        $data['table'] = $this->table->generate();
        
        $this->load->view('warehouse_transaction_transform', $data);
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
            $pitem = array('name' => $this->input->post('tname'), 'code' => 'GJ', 'warehouse_transaction' => $po, 'no' => $this->Transaction_model->counter_no(),
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

        $warehouse_transaction = array('debit' => $total_ar['amount'], 'credit' => $total_ap['amount']);
	$this->Warehouse_transaction_model->update($po, $warehouse_transaction);
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
	$data['link'] = array('link_back' => anchor('warehouse_transaction/','<span>back</span>', array('class' => 'back')));

        $this->session->set_userdata('jid', $po);

	// Form validation
        $this->form_validation->set_rules('tno', 'Warehouse_transaction Transaction No', 'required');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_validating_warehouse_transaction');
        $this->form_validation->set_rules('tcur', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $warehouse_transaction = array('dates' => $this->input->post('tdate'),  'currency' => $this->input->post('tcur'));

            $this->Warehouse_transaction_model->update($po, $warehouse_transaction);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('warehouse_transaction_transform', $data);
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
        $warehouse_transaction = $this->Warehouse_transaction_model->get_warehouse_transaction_by_id($pid)->row();

        if ($warehouse_transaction->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); 
           redirect($this->title);
        }
        else
        {
            if ($this->purchase->cek_approval_po($warehouse_transaction->dates,$warehouse_transaction->currency) == TRUE)
            {
                 $data = array('approved' => 1);
                 $this->Warehouse_transaction_model->update($pid, $data);

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
        $warehouse_transaction = $this->Warehouse_transaction_model->get_warehouse_transaction_by_id($jid)->row();

        if ( $warehouse_transaction->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - Warehouse_transaction approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$jid); } else { redirect($this->title); }
        }
    }

// ===========================  approval  ==============================================


    public function valid_warehouse_transaction($dates)
    {
        $currency = $this->input->post('ccurrency');
        if ($this->Warehouse_transaction_model->valid_warehouse_transaction($dates,$currency) == FALSE)
        {
            $this->form_validation->set_message('valid_warehouse_transaction', "Warehouse_transaction already created.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function validating_warehouse_transaction($dates)
    {
        $currency = $this->input->post('ccurrency');
        $id = $this->session->userdata('jid');
        if ($this->Warehouse_transaction_model->validating_warehouse_transaction($dates,$currency,$id) == FALSE)
        {
            $this->form_validation->set_message('validating_warehouse_transaction', "Warehouse_transaction already created.!");
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

       $warehouse_transaction = $this->Warehouse_transaction_model->get_warehouse_transaction_by_id($po)->row();

       $data['total_PJ'] = $warehouse_transaction->PJ;
       $data['total_GJ'] = $warehouse_transaction->GJ;
       $data['total_DP'] = $warehouse_transaction->DP;
       $data['total_CD'] = $warehouse_transaction->CD;
       $data['total_CG'] = $warehouse_transaction->CG;
       $data['total_TR'] = $warehouse_transaction->TR;
       
       $data['total_SJ'] = $warehouse_transaction->SJ;
       $data['total_NSJ'] = $warehouse_transaction->NSJ;
       $data['total_DS'] = $warehouse_transaction->DS;
       $data['total_NDS'] = $warehouse_transaction->NDS;
       $data['total_CR'] = $warehouse_transaction->CR;
       $data['total_NCR'] = $warehouse_transaction->NCR;

       $data['total_SAJ'] = $warehouse_transaction->SAJ;
       $data['total_PRJ'] = $warehouse_transaction->PRJ;
//
       $data['pono'] = $po;
       $data['dates'] = tgleng($warehouse_transaction->dates);
       $data['cur'] = $warehouse_transaction->currency;
       if ($warehouse_transaction->approved == 1){ $data['status'] = 'Approved';  } else { $data['status'] = 'Not Approved'; }

       $data['company'] = $this->properti['name'];

//       Transaction
       $data['PJ'] = $this->Transaction_model->get_transaction_type($po,'PJ')->result();
       $data['GJ'] = $this->Transaction_model->get_transaction_type($po,'GJ')->result();
       $data['DP'] = $this->Transaction_model->get_transaction_type($po,'DP')->result();
       $data['CD'] = $this->Transaction_model->get_transaction_type($po,'CD')->result();
       $data['CG'] = $this->Transaction_model->get_transaction_type($po,'CG')->result();
       $data['TR'] = $this->Transaction_model->get_transaction_type($po,'TR')->result();

       $data['SJ'] = $this->Transaction_model->get_transaction_type($po,'SJ')->result();
       $data['NSJ'] = $this->Transaction_model->get_transaction_type($po,'NSJ')->result();
       $data['DS'] = $this->Transaction_model->get_transaction_type($po,'DS')->result();
       $data['NDS'] = $this->Transaction_model->get_transaction_type($po,'NDS')->result();
       $data['CR'] = $this->Transaction_model->get_transaction_type($po,'CR')->result();
       $data['NCR'] = $this->Transaction_model->get_transaction_type($po,'NCR')->result();

       $data['SAJ'] = $this->Transaction_model->get_transaction_type($po,'SAJ')->result();
       $data['PRJ'] = $this->Transaction_model->get_transaction_type($po,'PRJ')->result();

       $this->load->view('warehouse_transaction_invoice', $data);
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

        $this->load->view('warehouse_transaction_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $product = $this->product->get_id($this->input->post('tproduct'));

        $data['product'] = $this->input->post('tproduct');
        $data['currency'] = $this->input->post('ccurrency');
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Warehouse_transaction_model->report($product,$start,$end,$this->input->post('ccurrency'))->result();
        $total = $this->Warehouse_transaction_model->total($product,$start,$end,$this->input->post('ccurrency'));

        $data['total_amount'] = $total['amount'];

        $this->load->view('warehouse_transaction_report', $data);

    }

//    ================================ REPORT =====================================

}

?>