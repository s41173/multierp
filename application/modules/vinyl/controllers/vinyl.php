<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vinyl extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Purchase_model', '', TRUE);
        $this->load->model('Purchase_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = new Currency_lib();
        $this->unit = new Unit_lib();
        $this->vendor = new Vendor_lib();
        $this->user = new Admin_lib();
        $this->tax = new Tax_lib();
//        $this->journal = new Journal_lib();
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->product = new Products_lib();
        $this->stockin = new Stock_in_lib();
        $this->ap = new Ap_payment_lib();
        $this->sales = new Sales_lib();
        $this->trans = new Trans_ledger_lib();

    }

    private $properti, $modul, $title, $trans;
    private $vendor,$user,$tax,$journal,$journalgl,$product,$currency,$unit,$stockin,$ap,$sales;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');
    
    private  $atts_update = array('width'=> '550','height'=> '350',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 550)/2)+\'',
                      'screeny'=> '0','class'=> 'update','title'=> 'update', 'screeny' => '\'+((parseInt(screen.height) - 350)/2)+\'');

    function index()
    {
       $this->get_last_purchase();
    }

    function get_last_purchase()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'purchase_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
        $data['currency'] = $this->currency->combo();

	// ---------------------------------------- //
        $purchases = $this->Purchase_model->get_last_purchase($this->modul['limit'], $offset)->result();
        $num_rows = $this->Purchase_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_purchase');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Acc', 'Vendor', 'Notes', 'Total', 'Balance', '#', 'Action');

            $i = 0 + $offset;
            foreach ($purchases as $purchase)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $purchase->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CP-00'.$purchase->no, $purchase->currency, tglin($purchase->dates), ucfirst($purchase->acc), $purchase->prefix.' '.$purchase->name, $purchase->notes, number_format($purchase->total + $purchase->costs), number_format($purchase->p2), $this->status($purchase->status),
                    anchor($this->title.'/confirmation/'.$purchase->id,'<span>update</span>',array('class' => $this->post_status($purchase->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$purchase->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$purchase->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$purchase->id.'/'.$purchase->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
        
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('tyear'));
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    public function chart($cur='IDR')
    {
        $fusion = $this->load->library('fusioncharts');
        $chart  = base_url().'public/flash/Column3D.swf';
        
        $ps = new Period();
        $ps->get();
        $py = new Payment_status_lib();
        
        if ($this->input->post('ccurrency')){ $cur = $this->input->post('ccurrency'); }else { $cur = 'IDR'; }
        if ($this->input->post('tyear')){ $year = $this->input->post('tyear'); }else { $year = $ps->year; }
        
        $arpData[0][1] = 'January';
        $arpData[0][2] =  $this->Purchase_model->total_chart($cur,1,$year);
//
        $arpData[1][1] = 'February';
        $arpData[1][2] =  $this->Purchase_model->total_chart($cur,2,$year);
//
        $arpData[2][1] = 'March';
        $arpData[2][2] =  $this->Purchase_model->total_chart($cur,3,$year);
//
        $arpData[3][1] = 'April';
        $arpData[3][2] =  $this->Purchase_model->total_chart($cur,4,$year);
//
        $arpData[4][1] = 'May';
        $arpData[4][2] =  $this->Purchase_model->total_chart($cur,5,$year);
//
        $arpData[5][1] = 'June';
        $arpData[5][2] =  $this->Purchase_model->total_chart($cur,6,$year);
//
        $arpData[6][1] = 'July';
        $arpData[6][2] =  $this->Purchase_model->total_chart($cur,7,$year);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->Purchase_model->total_chart($cur,8,$year);
        
        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->Purchase_model->total_chart($cur,9,$year);
//        
        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->Purchase_model->total_chart($cur,10,$year);
//        
        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->Purchase_model->total_chart($cur,11,$year);
//        
        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->Purchase_model->total_chart($cur,12,$year);

        $strXML1 = $fusion->setDataXML($arpData,'','') ;
        $graph   = $fusion->renderChart($chart,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
        
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'purchase_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();

        $purchases = $this->Purchase_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
         $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Acc', 'Vendor', 'Notes', 'Total', 'Balance', '#', 'Action');

         $i = 0;
         foreach ($purchases as $purchase)
         {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $purchase->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CP-00'.$purchase->no, $purchase->currency, tglin($purchase->dates), ucfirst($purchase->acc), $purchase->prefix.' '.$purchase->name, $purchase->notes, number_format($purchase->total + $purchase->costs), number_format($purchase->p2), $this->status($purchase->status),
                anchor($this->title.'/confirmation/'.$purchase->id,'<span>update</span>',array('class' => $this->post_status($purchase->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$purchase->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$purchase->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$purchase->id.'/'.$purchase->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
         }

        $data['table'] = $this->table->generate();
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('tyear'));
        $this->load->view('template', $data);
    }

    function get_list($currency=null,$vendor=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['main_view'] = 'vendor_list';
        $data['currency'] = $this->currency->combo();
        $data['link'] = array('link_back' => anchor($this->title.'/get_list','<span>back</span>', array('class' => 'back')));

        $currency = $this->input->post('ccurrency');
        $vendor = $this->vendor->get_vendor_id($this->input->post('tvendor'));

        $purchases = $this->Purchase_model->get_purchase_list($currency,$vendor)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Acc', 'Cur', 'Notes', 'Total', 'Balance', 'Action');

        $i = 0;
        foreach ($purchases as $purchase)
        {
           $datax = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$purchase->no.'\',\'titem\')'
                         );

            $this->table->add_row
            (
                ++$i, 'CP-00'.$purchase->no, tgleng($purchase->dates), ucfirst($purchase->acc), $purchase->currency, $purchase->notes, number_format($purchase->total), number_format($purchase->p2),
                form_button($datax)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('purchase_list', $data);
    }

    function get_list_all($currency=null,$vendor=null,$st=0)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'vendor_list';
        $data['form_action'] = site_url($this->title.'/get_list_all');
        $data['link'] = array('link_back' => anchor($this->title.'/get_list_all','<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

        $currency = $this->input->post('ccurrency');
        $vendor = $this->vendor->get_vendor_id($this->input->post('tvendor'));
        
        $purchases = $this->Purchase_model->get_purchase_list_all($currency,$vendor,$st)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Code', 'Date', 'Acc', 'Cur', 'Notes', 'Total', 'Balance', 'Action');

        $i = 0;
        foreach ($purchases as $purchase)
        {
           $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$purchase->no.'\',\'titem\')');
           $data_check = array('name'=> 'cek[]','id'=> 'cek'.$i, 'class'=> 'ads_Checkbox', 'value'=> $purchase->no,'checked'=> FALSE, 'style'=> 'margin:0px');
           $this->table->add_row
           (
              form_checkbox($data_check), ++$i, 'CP-00'.$purchase->no, tgleng($purchase->dates), ucfirst($purchase->acc), $purchase->currency, $purchase->notes, number_format($purchase->total), number_format($purchase->p2),
              form_button($datax)
           );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('purchase_list', $data);
    }

    function item_list($po)
    {
        $this->acl->otentikasi($this->title);
        $purchase = $this->Purchase_model->get_purchase_by_no($po)->row();
        $items = $this->Purchase_item_model->get_last_item($po)->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Qty', 'Unit');

        $i = 0;
        foreach ($items as $res)
        {
            $this->table->add_row
            ( ++$i, $this->product->get_name($res->product), $res->qty, $this->product->get_unit($res->product) );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('purchase_item_list', $data); 
    }
    
    private function status($val=0)
    { switch ($val) { case 0: $val = 'D'; break; case 1: $val = 'S'; break; } return $val; }
//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $this->acl->otentikasi3($this->title);
        $purchase = $this->Purchase_model->get_purchase_by_id($pid)->row();

        if ($purchase->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
        }
        elseif ($this->valid_period($purchase->dates) == FALSE)
        {
           $this->session->set_flashdata('message', "$this->title Invalid Period..!"); // set flash data message dengan session 
        }
        else
        {
            $total = $purchase->total;

            if ($total == 0 && $purchase->p2 == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
            }
            else
            {   
                // membuat kartu hutang
                if ($purchase->status == 0){ $this->trans->add($purchase->acc, 'CP', $purchase->no, $purchase->currency, $purchase->dates, 0, $purchase->p2, $purchase->vendor, 'AP'); }
                
                $data = array('approved' => 1);
                $this->Purchase_model->update_id($pid, $data);

//                //  create journal
                $this->create_po_journal($pid, $purchase->dates, $purchase->currency, 'CP-00'.$purchase->no.'-'.$purchase->notes, 'PJ',
                                         $purchase->no, 'AP', $purchase->total + $purchase->costs, $purchase->p1,$purchase->p2);

               $this->session->set_flashdata('message', "$this->title CP-00$purchase->no confirmed..!"); // set flash data message dengan session
            }
        }
        
        redirect($this->title);
    }
    
    private function update_buying($po)
    {
        $items = $this->Purchase_item_model->get_last_item($po)->result();
        foreach ($items as $res) { $this->product->edit_price($res->product,$res->price); }
    }

    
    private function cek_confirmation($po=null,$page=null)
    {
        $purchase = $this->Purchase_model->get_purchase_by_no($po)->row();

        if ( $purchase->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - CP-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); } 
        }
    }

//    ===================== approval ===========================================


    private function create_po_journal($pid,$date,$currency,$code,$codetrans,$no,$type,$amount,$p1,$p2)
    {
        $cm = new Control_model();
        
        $landed   = $cm->get_id(1); // biaya pengiriman barang
        $tax      = $cm->get_id(9); // pajak dibayar dimuka
        $stock    = $cm->get_id(47); // piutang persediaan
        $ap       = $cm->get_id(11); // hutang usaha
        $bank     = $cm->get_id(12); // bank
        $kas      = $cm->get_id(13); // kas
        $kaskecil = $cm->get_id(14); // kas kecil
        $hpp      = $cm->get_id(59); // hpp billboard
        $account = 0;
        
        $purchase = $this->Purchase_model->get_purchase_by_id($pid)->row();
        switch ($purchase->acc) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }
        
        if ($p1 > 0)
        {  
           // create journal- GL
           $this->journalgl->new_journal('0'.$no,$date,'CP',$currency,$code,$amount, $this->session->userdata('log'));
           $this->journalgl->new_journal('0'.$no,$date,'CDP',$currency,'DP Payment : CP-00'.$no,$p1, $this->session->userdata('log'));
           
           $jid = $this->journalgl->get_journal_id('CP','0'.$purchase->no);
           $dpid = $this->journalgl->get_journal_id('CDP','0'.$purchase->no);
           
//           $this->journalgl->add_trans($jid,$stock,$purchase->total-$purchase->tax-$purchase->discount-$purchase->over_amount,0); // tambah persediaan
           
           $this->journalgl->add_trans($jid,$hpp,$purchase->total-$purchase->tax-$purchase->discount-$purchase->over_amount,0); // tambah persediaan
           if ($purchase->tax > 0){ $this->journalgl->add_trans($jid,$tax,$purchase->tax,0); } // pajak pembelian
           if ($purchase->costs > 0){ $this->journalgl->add_trans($jid,$landed,$purchase->costs,0); } // landed costs
           $this->journalgl->add_trans($jid,$ap,0,$purchase->p1+$purchase->p2); // hutang usaha
           
           //DP proses
           $this->journalgl->add_trans($dpid,$ap,$purchase->p1,0); // potongan hutang usaha
           $this->journalgl->add_trans($dpid,$account,0,$purchase->p1); // potongan bank pembelian
           
        }
        else 
        { 
           $this->journalgl->new_journal('0'.$no,$date,'CP',$currency,$code,$amount, $this->session->userdata('log'));
           
           $jid = $this->journalgl->get_journal_id('CP','0'.$purchase->no);
            
//           $this->journalgl->add_trans($jid,$stock,$purchase->total-$purchase->tax-$purchase->discount-$purchase->over_amount,0); // tambah persediaan
           
           $this->journalgl->add_trans($jid,$hpp,$purchase->total-$purchase->tax-$purchase->discount-$purchase->over_amount,0); // tambah persediaan
           if ($purchase->tax > 0){ $this->journalgl->add_trans($jid,$tax,$purchase->tax,0); } // pajak pembelian
           if ($purchase->costs > 0){ $this->journalgl->add_trans($jid,$landed,$purchase->costs,0); } // landed costs
           $this->journalgl->add_trans($jid,$ap,0,$purchase->p1+$purchase->p2); // hutang usaha
        }
    }

    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Purchase_model->get_purchase_by_id($uid)->row();
        
        if ( $this->valid_period($val->dates) == TRUE && $this->ap->cek_relation_trans($po,'no','CP') == TRUE )
        {
           if ($val->approved == 1){ $this->rollback($uid,$po); } else { $this->remove($uid,$po); }
        }
        else{ $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved, related to another component..!");} 
        redirect($this->title);        
    }
    
    private function rollback($uid,$po)
    {
      $this->journalgl->remove_journal('CP', '0'.$po); // journal gl
      $this->journalgl->remove_journal('CDP', '0'.$po);   
      $this->over_status($po, 1);
      
      // rollback kartu piutang
      $val = $this->Purchase_model->get_purchase_by_id($uid)->row();
      $this->trans->remove($val->dates, 'CP', $val->no);
      
      $trans = array('approved' => 0);
      $this->Purchase_model->update_id($uid, $trans);
      $this->session->set_flashdata('message', "1 $this->title successfully rollback..!");
    }
    
    private function remove($uid,$po)
    {
       $this->Purchase_item_model->delete_po($po); // model to delete purchase item
       $this->Purchase_model->delete($uid); // memanggil model untuk mendelete data
       $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Purchase_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('purchase_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'purchase_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Purchase_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
        $this->form_validation->set_rules('tno', 'PO - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tshipping', 'Shipping', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');
        $this->form_validation->set_rules('tsales', 'Sales - ID', 'callback_valid_sales');
        $this->form_validation->set_rules('tremarks', 'Sales - Remarks', 'callback_valid_remarks['.$this->input->post('tsales').']');

        if ($this->form_validation->run($this) == TRUE)
        {
            $purchase = array('vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')), 'no' => $this->input->post('tno'), 'status' => 0, 'docno' => $this->input->post('tdocno'),
                              'dates' => $this->input->post('tdate'), 'acc' => $this->input->post('cacc'), 'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 
                              'desc' => $this->input->post('tdesc'), 'shipping_date' => $this->input->post('tshipping'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                              'sid' => $this->input->post('tsales'), 'remarks' => $this->input->post('tremarks'),
                              'log' => $this->session->userdata('log'));
            
            $this->Purchase_model->add($purchase);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('purchase_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        $data['currency'] = $this->currency->combo();
        $data['unit'] = $this->unit->combo();
        $data['tax'] = $this->tax->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $purchase = $this->Purchase_model->get_purchase_by_no($po)->row();
        
        $data['over'] = $this->ap->combo_over($purchase->vendor,$purchase->currency);

        $data['default']['vendor'] = $purchase->name;
        $data['default']['date'] = $purchase->dates;
        $data['default']['acc'] = $purchase->acc;
        $data['default']['currency'] = $purchase->currency;
        $data['default']['note'] = $purchase->notes;
        $data['default']['desc'] = $purchase->desc;
        $data['default']['shipping'] = $purchase->shipping_date;
        $data['default']['user'] = $this->user->get_username($purchase->user);
        $data['default']['docno'] = $purchase->docno;

        $data['default']['tax'] = $purchase->tax;
        $data['default']['totaltax'] = $purchase->total;
        $data['default']['p1'] = $purchase->p1;
        $data['default']['costs'] = $purchase->costs;
        $data['default']['balance'] = $purchase->p2;
        
        $data['default']['over'] = $purchase->ap_over;
        $data['default']['overamount'] = $purchase->over_amount;
        
        $data['default']['sales'] = $this->sales->get_so_no($purchase->sid);
        $data['default']['remarks'] = $purchase->remarks;

//        ============================ Purchase Item  =========================================
        $items = $this->Purchase_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Item', 'Qty', 'Unit price', 'Tax', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->product, $item->qty, number_format($item->price), number_format($item->tax), number_format($item->amount),
                anchor_popup($this->title.'/edit_item/'.$item->id.'/'.$po,'<span>update</span>',$this->atts_update).' '.
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('purchase_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function edit_item($id,$po)
    {
       $this->acl->otentikasi2($this->title); 
       $val = $this->Purchase_item_model->get_by_id($id)->row();  
       $data['form_action_item'] = site_url($this->title.'/edit_item_process/'.$id.'/'.$po); 
       
       $data['tax'] = $this->tax->combo();
       
       $data['default']['item'] = $val->product;
       $data['default']['qty'] = $val->qty;
       $data['default']['amount'] = $val->price;       
       $data['default']['tax'] = $val->tax;
        
       $this->load->view('purchase_update_item', $data); 
    }
    
    function edit_item_process($id,$po)
    {   
        $this->form_validation->set_rules('titem', 'Item Name', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('tamount', 'Unit Price', 'required');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($po) == TRUE)
        {
           $pitem = array('product' => $this->input->post('titem'), 'purchase_id' => $po, 'qty' => $this->input->post('tqty'),
                          'price' => $this->input->post('tamount'),
                          'amount' => $this->input->post('tqty') * $this->input->post('tamount'),
                          'tax' => $this->tax->calculate($this->input->post('ctax'),$this->input->post('tqty'),$this->input->post('tamount')));
            $this->Purchase_item_model->update($id,$pitem);
            $this->update_trans($po);
        }
        
        redirect($this->title.'/edit_item/'.$id.'/'.$po);
    }
    
    function add_item($po=null)
    {
//        $this->cek_confirmation($po,'add_trans',true);
        
        $this->form_validation->set_rules('titem', 'Item Name', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('tamount', 'Unit Price', 'required');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($po) == TRUE)
        {   
            $pitem = array('product' => $this->input->post('titem'), 'purchase_id' => $po, 'qty' => $this->input->post('tqty'),
                           'price' => $this->input->post('tamount'),
                           'amount' => $this->input->post('tqty') * $this->input->post('tamount'),
                           'tax' => $this->tax->calculate($this->input->post('ctax'),$this->input->post('tqty'),$this->input->post('tamount')));
            $this->Purchase_item_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $totals = $this->Purchase_item_model->total($po);
        $purchase = array('tax' => $totals['tax'], 'total' => $totals['amount'] + $totals['tax']);
	$this->Purchase_model->update($po, $purchase);
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Purchase_item_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);
//        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
        $this->form_validation->set_rules('tno', 'PO - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tshipping', 'Shipping', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tcosts', 'Landed Cost', 'required|numeric');
        $this->form_validation->set_rules('tp1', 'Down Payment', 'required|numeric');
        $this->form_validation->set_rules('cover', 'Credit / Debit Memo', 'required|numeric|callback_validation_over['.$this->input->post('tno').']');
        $this->form_validation->set_rules('tsales', 'Sales - ID', '');
        $this->form_validation->set_rules('tremarks', 'Sales - Remarks', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $purchases = $this->Purchase_model->get_purchase_by_no($po)->row();

            $purchase = array('vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')), 'log' => $this->session->userdata('log'), 'docno' => $this->input->post('tdocno'),
                              'dates' => $this->input->post('tdate'), 'acc' => $this->input->post('cacc'), 'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                              'shipping_date' => $this->input->post('tshipping'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                              'costs' => $this->input->post('tcosts'), 'p1' => $this->input->post('tp1'), 'over_amount' => $this->input->post('toveramount'), 'ap_over' => $this->input->post('cover'),
                              'p2' => $this->calculate_balance($this->input->post('tcosts'),$purchases->total,$this->input->post('tp1'),$this->input->post('toveramount')),
                              'sid' => $this->input->post('tsales'), 'remarks' => $this->input->post('tremarks'),
                              'status' => $this->get_status($this->calculate_balance($this->input->post('tcosts'),$purchases->total,$this->input->post('tp1'),$this->input->post('toveramount')))
                             );

            $this->Purchase_model->update($po, $purchase);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('purchase_transform', $data);
            echo validation_errors();
        }
    }
    
    private function over_status($po,$type=0)
    {
       $purchases = $this->Purchase_model->get_purchase_by_no($po)->row(); 
       
       if ($purchases->ap_over != 0){ $data = array('credit_over' => 1); }
       else { $data = array('credit_over' => 0); }
       
       if ($type != 0){ $data = array('credit_over' => 0); }
       $this->ap->set_over_stts($purchases->ap_over, $data);
    }

    private function calculate_balance($cost,$total,$p1,$over)
    {
        $res=0;
        $res = $cost + $total;
        $res = $res - $p1 - $over;
        return $res;
    }

    private function get_status($p2=null)
    { if ($p2 == 0){ return 1; } else { return 0; } }

    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            if (cek_previous_period($month, $year) == TRUE){ return TRUE; }
            else { $this->form_validation->set_message('valid_period', "Invalid Period.!"); return FALSE; }
        }
        else {  return TRUE; }
    }
    
    public function valid_vendor($name)
    {
        if ($this->vendor->valid_vendor($name) == FALSE)
        {
            $this->form_validation->set_message('valid_vendor', "Invalid Vendor.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function valid_no($no)
    {
        if ($this->Purchase_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_sales($sid)
    {
        if ($this->Purchase_model->valid_sales($sid) == FALSE)
        {
            $this->form_validation->set_message('valid_sales', "Sales No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_remarks($remarks,$sid)
    {
        if ($sid){ return TRUE; }
        else
        {
          if ($remarks){ return TRUE; }
          else { $this->form_validation->set_message('valid_remarks', "Remarks - Remarks required.!"); return FALSE; }
        }
    }
    
    function validation_over($no,$po)
    {
	if ($no != 0)
        {
            $val = $this->Purchase_model->get_purchase_by_no($po)->row();
            
            if ($this->Purchase_model->validating_over($no,$val->id) == FALSE)
            {
               $this->form_validation->set_message('validation_over', 'This Credit / Debit Memo is already registered!');
               return FALSE;  
            }
            else { return TRUE; }
        }
        else{ return TRUE; }
    }

    public function valid_confirmation($no)
    {
        $purchase = $this->Purchase_model->get_purchase_by_no($no)->row();

        if ($purchase->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Can't change value - Order approved..!.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_rate($rate)
    {
        if ($rate == 0)
        {
            $this->form_validation->set_message('valid_rate', "Rate can't 0..!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================
    
   function invoice($po=null)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'tombolprint','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
//        $this->table->set_heading('Name', 'Action');
        $this->table->add_row('<h3>Faktur Pembelian</h3>', anchor_popup($this->title.'/print_invoice/'.$po,'Preview',$atts));
        $this->table->add_row('<h3>Expediter Status</h3>', anchor_popup($this->title.'/print_expediter/'.$po,'Preview',$atts));
//        $data['table'] = $this->table->generate();

        $data['pono'] = $po;
        $this->load->view('purchase_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);
       $purchase = $this->Purchase_model->get_purchase_by_no($po)->row();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = $po;
       $data['logo'] = $this->properti['logo'];
       $data['podate'] = tgleng($purchase->dates);
       $data['vendor'] = $purchase->prefix.' '.$purchase->name;
       $data['address'] = $purchase->address;
       $data['city'] = $purchase->city;
       $data['phone'] = $purchase->phone1;
       $data['phone2'] = $purchase->phone2;
       $data['desc'] = $purchase->desc;
       $data['user'] = $this->user->get_username($purchase->user);
       $data['currency'] = $purchase->currency;
       $data['docno'] = $purchase->docno;
       $data['log'] = $this->session->userdata('log');

       $data['cost'] = $purchase->costs;
       $data['p2'] = $purchase->p2;
       $data['p1'] = $purchase->p1;
       $data['over'] = $purchase->over_amount;
       
       if ($purchase->sid != 0){ $data['sales'] = 'SO-00'.$this->sales->get_so_no($purchase->sid); }
       else { $data['sales'] = '-'; } 
       
       if ($purchase->ap_over > 0){ $data['ap_over'] = 'CD-00'.$purchase->ap_over.' / '.tglin($this->ap->get_dates($purchase->ap_over)); }
       else { $data['ap_over'] = ""; }
       
       // terbilang
       $tt = new Terbilang();
       if ($purchase->currency == 'IDR'){  $data['terbilang'] = $tt->baca($purchase->p2).' rupiah'; }
       else { $data['terbilang'] = $tt->baca($purchase->p2); } 
       
       $data['items'] = $this->Purchase_item_model->get_last_item($po)->result();

       // property display
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];
       $data['accounting'] = $this->properti['accounting'];
       $data['manager'] = $this->properti['manager'];

//       if ($purchase->approved != 1){ $this->load->view('rejected', $data); }
//       else{ if ($type) { $this->load->view('purchase_invoice_blank', $data); } else { $this->load->view('purchase_invoice', $data); } }
       $this->load->view('purchase_invoice', $data);

   }

   function print_expediter($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Expediter'.$this->modul['title'];

       $purchase = $this->Purchase_model->get_purchase_by_no($po)->row();

       $data['pono'] = $po;
       $data['podate'] = tgleng($purchase->dates);
       $data['vendor'] = $purchase->prefix.' '.$purchase->name;
       $data['address'] = $purchase->address;
       $data['shipdate'] = tgleng($purchase->shipping_date);
       $data['city'] = $purchase->city;
       $data['phone'] = $purchase->phone1;
       $data['phone2'] = $purchase->phone2;
       $data['desc'] = $purchase->desc;
       $data['user'] = $this->user->get_username($purchase->user);
       $data['currency'] = $this->currency->get_code($purchase->currency);
       $data['docno'] = $purchase->docno;

       $data['cost'] = $purchase->costs;
       $data['p2'] = $purchase->p2;
       $data['p1'] = $purchase->p1;

       $data['items'] = $this->Purchase_item_model->get_last_item($po)->result();

       // property display
       $data['p_name'] = $this->properti['name'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('purchase_expediter', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('purchase_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
        $status = $this->input->post('cstatus');
        $acc = $this->input->post('cacc');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['acc'] = $acc;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['purchases'] = $this->Purchase_model->report($vendor,$cur,$start,$end,$status,$acc)->result();
        $data['item'] = $this->Purchase_model->report_product($vendor,$cur,$start,$end,$status)->result();
        $total = $this->Purchase_model->total($vendor,$cur,$start,$end,$status,$acc);
        
        $data['total'] = $total['total'] - $total['tax'];
        $data['tax'] = $total['tax'];
        $data['p1'] = $total['p1'];
        $data['p2'] = $total['p2'];
        $data['costs'] = $total['costs'];
        $data['ptotal'] = $total['total'] + $total['costs'];
        
        if ($type == '1'){ $page = "purchase_report_details"; }elseif ($type == '0'){ $page = "purchase_report"; }elseif ($type == '2'){ $page = "purchase_pivot"; }
        elseif ($type == '3'){ $page = "purchase_product_report"; }
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
    }

    function report_product()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_product_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('purchase_report_product_panel', $data);
    }
    
    
    function report_product_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $product = $this->product->get_id($this->input->post('titem'));
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['purchases'] = $this->Purchase_model->report_product($product,$cur,$start,$end)->result();
        
        if ($type == '0'){ $page = "purchase_product_report"; }elseif ($type == '1'){ $page = "purchase_product_pivot"; }
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
    }
    
// ====================================== REPORT =========================================
    
// ====================================== AJAX =========================================    
   
    function get_over()
    {
       $no = $this->input->post('tno'); 
       if (!$no){ echo '0'; }else { echo $this->ap->get_over_payment($no); }
    }

}

?>