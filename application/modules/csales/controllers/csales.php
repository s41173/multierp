<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Csales extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Csales_model', '', TRUE);
        $this->load->model('Csales_item_model', 'sitem', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency   = $this->load->library('currency_lib');
        $this->customer   = $this->load->library('customer_lib');
        $this->user       = $this->load->library('admin_lib');
        $this->tax        = $this->load->library('tax_lib');
        $this->journal    = $this->load->library('journal_lib');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->ar         = $this->load->library('car_payment');
        $this->product    = $this->load->library('products_lib');
        $this->wt         = $this->load->library('warehouse_transaction');
        $this->sr         = $this->load->library('sales_return');

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency, $stockvalue=0;
    private $customer,$user,$tax,$journal,$ar,$product,$sr;

    function index()
    {
        $this->get_last_sales();
    }

    function get_last_sales()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last_sales');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $saless = $this->Csales_model->get_last_sales($this->modul['limit'], $offset)->result();
        $num_rows = $this->Csales_model->count_all_num_rows();

        $atts = array('width'=> '450','height'=> '300',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_sales');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Total', 'Balance', '#', 'Action');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CSO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total + $sales->costs), number_format($sales->p2), $this->status($sales->status),
                    anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$sales->no,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/add_trans/'.$sales->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // ===== chart  =======
        $data['graph'] = $this->chart($this->input->post('ccurrency'));
        

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }


    private function chart($cur='IDR')
    {
        $year = date('Y');

        $arpData[0][1] = 'January';
        $arpData[0][2] = $this->Csales_model->total_chart('01',$year,$cur);

        $arpData[1][1] = 'February';
        $arpData[1][2] = $this->Csales_model->total_chart('02',$year,$cur);

        $arpData[2][1] = 'March';
        $arpData[2][2] = $this->Csales_model->total_chart('03',$year,$cur);

        $arpData[3][1] = 'April';
        $arpData[3][2] = $this->Csales_model->total_chart('04',$year,$cur);

        $arpData[4][1] = 'May';
        $arpData[4][2] = $this->Csales_model->total_chart('05',$year,$cur);

        $arpData[5][1] = 'June';
        $arpData[5][2] = $this->Csales_model->total_chart('06',$year,$cur);

        $arpData[6][1] = 'July';
        $arpData[6][2] = $this->Csales_model->total_chart('07',$year,$cur);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->Csales_model->total_chart('08',$year,$cur);

        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->Csales_model->total_chart('09',$year,$cur);

        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->Csales_model->total_chart('10',$year,$cur);

        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->Csales_model->total_chart('11',$year,$cur);

        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->Csales_model->total_chart('12',$year,$cur);

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Csales", "98%", 400, false, false) ;
        return $graph;
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'sales_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $atts = array('width'=> '400','height'=> '220',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        $saless = $this->Csales_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
         $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Total', 'Balance', '#', 'Action');

         $i = 0;
         foreach ($saless as $sales)
         {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CSO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total + $sales->costs), number_format($sales->p2), $this->status($sales->status),
                anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$sales->no,'<span>print</span>',$atts).' '.
                anchor($this->title.'/add_trans/'.$sales->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
         }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function cek_space($val)
    {  $res = explode("<br />",$val);  if (count($res) == 1) { return $val;  } else { return implode('', $res); } }

    function get_list($currency='IDR',$customer=null,$st=0)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'customer_list';

        $saless = $this->Csales_model->get_sales_list($currency,$customer,$st)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Total', 'Balance', 'Action');

        $i = 0;
        foreach ($saless as $sales)
        {
           $data = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$sales->no.'\',\'titem\')'
                         );

            $this->table->add_row
            (
                ++$i, 'CSO-00'.$sales->no, tgleng($sales->dates), $sales->notes, number_format($sales->total), number_format($sales->p2),
                form_button($data)
            );
        }

            $data['table'] = $this->table->generate();
            $this->load->view('sales_list', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'C'; break; case 1: $val = 'S'; break; } return $val; }
//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $sales = $this->Csales_model->get_sales_by_id($pid)->row();

        if ($sales->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); 
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($sales->dates,$sales->currency); 
            $total = $sales->total;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); 
              redirect($this->title);
            }
            elseif ($this->sitem->valid_item($sales->no) == FALSE)
            {
               $this->session->set_flashdata('message', "$this->title not have transaction..!");
               redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Csales_model->update_id($pid, $data);

                $this->calculate_stock($sales->no); 
                $this->update_product($sales->no);

                //  create journal
               $this->create_po_journal($sales->id,$sales->dates, $sales->currency, 'CSO-00'.$sales->no.'-'.$sales->notes, 'CSJ',
                                         $sales->no, 'AR', $sales->total + $sales->costs, $sales->p1,$sales->p2);

               $this->session->set_flashdata('message', "$this->title CSO-00$sales->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
                
            }
        }

    }
    
    private function calculate_stock($so)
    {
        $val = $this->sitem->get_last_item($so)->result();
        $this->stockvalue = 0;
        $this->stockid = null;
        
        foreach ($val as $res)
        {  $this->get_stock($res->product, $res->qty, $so);  }
    }
    
    function get_stock($pid,$qty=0,$so) //FIFO / LIFO
    {
        if ($qty > 0){ $this->stock($pid,$qty,$so); }
    }
    
    private function stock($pid,$req,$so)
    {
          $res = $this->product->get_first_stock($pid);  
          
          if ($res != null)
          {
             if($req > $res->qty)
             { 
                 $this->stockvalue = $this->stockvalue + intval($res->qty*$res->amount);
                 $this->product->min_stock($pid,$res->dates,$res->qty,$so);
                 $this->get_stock($pid, intval($req - $res->qty),$so); 
             }
             else 
             { 
                 $this->stockvalue = $this->stockvalue + intval($req*$res->amount);
                 $this->product->min_stock($pid,$res->dates,$req,$so);
                 $this->get_stock($pid, 0,$so); 
             } 
          }
          else{ $this->get_stock($pid, 0,$so); }  
    }

    private function update_product($so)
    {
        $val = $this->sitem->get_last_item($so)->result();
        $sales = $this->Csales_model->get_sales_by_no($so)->row();

        foreach ($val as $res)
        {
            $this->product->min_qty($this->product->get_name($res->product),$res->qty, $this->product->get_amount_stock($so));

            $this->wt->add($sales->dates, 'CSO-00'.$sales->no,
                           $sales->currency, $res->product,
                           0, $res->qty, $res->price, $res->amount,
                           $this->session->userdata('log'));
        }
    }
     
    private function unupdate_product($so)
    {
        $sales = $this->Csales_model->get_sales_by_no($so)->row();
        $val = $this->sitem->get_last_item($so)->result();

        foreach ($val as $res)
        {
            $this->product->add_qty($this->product->get_name($res->product),$res->qty,$this->product->get_amount_stock($so));
            $this->product->rollback_stock($so);
            $this->wt->remove($sales->dates, 'CSO-00'.$sales->no, $res->product);
        }
    }

    private function cek_journal($date,$currency)
    {
        if ($this->journal->valid_journal($date,$currency) == FALSE)
        {
           $this->session->set_flashdata('message', "Journal for [".tgleng($date)."] - ".$currency." approved..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $sales = $this->Csales_model->get_sales_by_no($po)->row();

        if ( $sales->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - SO-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    private function create_po_journal($sid,$date,$currency,$code,$codetrans,$no,$type,$amount,$p1,$p2)
    {
        $cm = new Control_model();
        
        $landed   = $cm->get_id(2);
        $discount = $cm->get_id(4);
        $tax      = $cm->get_id(18);
        $stock    = $cm->get_id(10);
        $ar       = $cm->get_id(17);
        $bank     = $cm->get_id(21);
        $salesacc = $cm->get_id(19);
        $cost     = $cm->get_id(20);
        
        $sales = $this->Csales_model->get_sales_by_id($sid)->row();   
        
        if ($p1 > 0)
        {
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount);
           $this->journal->create_journal($date,$currency,$code.' (Cash) ','CDS',$no,'AR', $p1);
           
           // create journal- GL
           $this->journalgl->new_journal($no,$date,'SJ',$currency,$code,$amount, $this->session->userdata('log'));
           $this->journalgl->new_journal($no,$date,'CR',$currency,'Customer DP Payment : SJ-00'.$no,$p1, $this->session->userdata('log'));
           
           $jid = $this->journalgl->get_journal_id('SJ',$no);
           $dpid = $this->journalgl->get_journal_id('CR',$no);
           
           $this->journalgl->add_trans($jid,$cost, $this->stockvalue, 0); // tambah biaya 1 (hpp)
           $this->journalgl->add_trans($jid,$stock,0,$this->stockvalue); // kurang persediaan
           $this->journalgl->add_trans($jid,$ar,$sales->p1+$sales->p2,0); // piutang usaha bertambah
           $this->journalgl->add_trans($jid,$salesacc,0,$sales->total-$sales->tax); // tambah penjualan
           
           if ($sales->tax > 0){ $this->journalgl->add_trans($jid,$tax,0,$sales->tax); } // pajak penjualan
           if ($sales->costs > 0){ $this->journalgl->add_trans($jid,$landed,0,$sales->costs); } // landed costs
           
           //DP proses
           $this->journalgl->add_trans($dpid,$bank,$sales->p1,0); //bank penjualan
           $this->journalgl->add_trans($dpid,$ar,0,$sales->p1); // piutang usaha kurang dp
           
        }
        else
        { 
            $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount); 
            
            $this->journalgl->new_journal($no,$date,'SJ',$currency,$code,$amount, $this->session->userdata('log'));
            $jid = $this->journalgl->get_journal_id('SJ',$no);
            
            $this->journalgl->add_trans($jid,$cost, $this->stockvalue, 0); // tambah biaya 1 (hpp)
            $this->journalgl->add_trans($jid,$stock, 0, $this->stockvalue); // kurang persediaan
            $this->journalgl->add_trans($jid,$ar,$sales->p1+$sales->p2,0); // piutang usaha bertambah
            $this->journalgl->add_trans($jid,$salesacc,0,$sales->total-$sales->tax); // tambah penjualan
           
            if ($sales->tax > 0){ $this->journalgl->add_trans($jid,$tax,0,$sales->tax); } // pajak penjualan
            if ($sales->costs > 0){ $this->journalgl->add_trans($jid,$landed,0,$sales->costs); } // landed costs
            
        }
    }

    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $sales = $this->Csales_model->get_sales_by_no($po)->row();

        if ( $this->ar->cek_relation($po,'no') == TRUE )
        {
            if ( $this->journal->cek_approval('CSJ',$po) == TRUE && $this->valid_period($sales->dates) == TRUE && $this->sr->cek_relation($po, 'sales') == TRUE ) // cek journal harian sudah di approve atau belum
            {
                $this->unupdate_product($po);
                $this->sitem->delete_po($po); // model to delete sales item
                $this->Csales_model->delete($uid); // memanggil model untuk mendelete data

                $this->journal->remove_journal('CSJ',$po); // delete journal
                $this->journal->remove_journal('CDS',$po); // delete down payment journal
                
                $this->journalgl->remove_journal('SJ', $po); // journal gl
                $this->journalgl->remove_journal('CR', $po);

                $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            }
            else{ $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!"); }
        }
        else { $this->session->set_flashdata('message', "This $this->title related to another component..!");  }

        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Csales_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('sales_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Csales_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'CSO - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_date|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tshipping', 'Shipping', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')), 'no' => $this->input->post('tno'), 
                           'status' => 0, 'docno' => $this->input->post('tdocno'), 'dates' => $this->input->post('tdate'),
                           'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'),
                           'desc' => $this->input->post('tdesc'), 'log' => $this->session->userdata('log'),
                           'shipping_date' => $this->input->post('tshipping'), 'user' => $this->user->get_userid($this->input->post('tuser')));
            
            $this->Csales_model->add($sales);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('sales_form', $data);
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
        $data['tax'] = $this->tax->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $sales = $this->Csales_model->get_sales_by_no($po)->row();

        $data['default']['customer'] = $sales->name;
        $data['default']['date'] = $sales->dates;
        $data['default']['currency'] = $sales->currency;
        $data['default']['note'] = $sales->notes;
        $data['default']['desc'] = $sales->desc;
        $data['default']['shipping'] = $sales->shipping_date;
        $data['default']['user'] = $this->user->get_username($sales->user);
        $data['default']['docno'] = $sales->docno;

        $data['default']['tax'] = $sales->tax;
        $data['default']['discount'] = $sales->discount;
        $data['default']['totaltax'] = $sales->total;
        $data['default']['p1'] = $sales->p1;
        $data['default']['costs'] = $sales->costs;
        $data['default']['balance'] = $sales->p2;

//        ============================ Csales Item  =========================================
        $items = $this->sitem->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Qty', 'Unit price', 'Discount', 'Tax', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $this->product->get_name($item->product), $item->qty.' '.$this->product->get_unit($item->product), number_format($item->price), number_format($item->discount), number_format($item->tax), number_format($item->amount),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('sales_transform', $data);
    }
    
//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        
        $this->form_validation->set_rules('titem', 'Product', 'required|callback_valid_confirmation['.$po.']');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('ctax', 'Tax', 'required');
        $this->form_validation->set_rules('tdiscount', 'Discount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $res = $this->total($this->product->get_price($this->product->get_id($this->input->post('titem')))*$this->input->post('tqty'),
                                $this->input->post('tdiscount'), $this->input->post('ctax'),$this->input->post('tqty'));

            $pitem = array('sales' => $po, 'product' => $this->product->get_id($this->input->post('titem')),
                           'price' => $this->product->get_price($this->product->get_id($this->input->post('titem'))),
                           'discount' => $res['discount'], 'qty' => $this->input->post('tqty'),
                           'tax' => $res['tax'], 'amount' => $res['amount']);
            
            $this->sitem->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    public function valid_confirmation($product,$po)
    {
        $val = $this->Csales_model->get_sales_by_no($po)->row();

        if ($val->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Order Approved...!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    private function total($price,$discount,$tax,$qty)
    {
//        $discount = $this->calculate_discount($price,$discount);
        $discount = $discount * $qty;
        $netto = $price - $discount;
        $tax = round($this->tax->calculate_tax($netto,$tax));
        $amount = $netto + $tax;

        $val = array('bruto' => $price, 'discount' => $discount, 'netto' => $netto, 'tax' => $tax, 'amount' => $amount);

        return $val;
    }

    private function calculate_discount($amount,$discount)
    {
        $discount = $discount / 100;
        $discount = $amount * $discount;
        return $discount;
    }

    private function update_trans($po)
    {
        $totals = $this->sitem->total($po);
        $sales = array('tax' => $totals['tax'], 'total' => $totals['amount'], 'discount' => $totals['discount'], 'p2' => $totals['amount']);
	$this->Csales_model->update($po, $sales);
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->sitem->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
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
	$data['link'] = array('link_back' => anchor('sales/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'SO - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tshipping', 'Shipping', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tcosts', 'Landed Cost', 'required|numeric');
        $this->form_validation->set_rules('tp1', 'Down Payment', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $saless = $this->Csales_model->get_sales_by_no($po)->row();

            $sales = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')), 'log' => $this->session->userdata('log'), 'docno' => $this->input->post('tdocno'),
                           'dates' => $this->input->post('tdate'),  'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                           'shipping_date' => $this->input->post('tshipping'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                           'costs' => $this->input->post('tcosts'), 'p1' => $this->input->post('tp1'),
                           'p2' => $this->calculate_balance($this->input->post('tcosts'),$saless->total,$this->input->post('tp1')),
                           'status' => $this->get_status($this->calculate_balance($this->input->post('tcosts'),$saless->total,$this->input->post('tp1')))
                             );

            $this->Csales_model->update($po, $sales);
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/add_trans/'.$po);
//            echo 'true';
        }
        else
        {
            $this->load->view('sales_transform', $data);
//            echo validation_errors();
        }
    }

    private function calculate_balance($cost,$total,$p1)
    {
        $res = $cost + $total;
        $res = $res - $p1;
        return $res;
    }

    private function get_status($p2=null)
    { if ($p2 == 0){ return 1; } else { return 0; } }

    public function valid_customer($name)
    {
        if ($this->customer->valid_customer($name) == FALSE)
        {
            $this->form_validation->set_message('valid_customer', "Invalid Customer.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function valid_no($no)
    {
        if ($this->Csales_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

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
        $this->table->add_row('<h3>Faktur Penjualan</h3>', anchor_popup($this->title.'/print_invoice/'.$po,'Preview',$atts));
        $this->table->add_row('<h3>Faktur Pajak</h3>', anchor_popup($this->title.'/print_invoice/'.$po,'Preview',$atts));
        $this->table->add_row('<h3>Tanda Terima</h3>', anchor_popup($this->title.'/print_expediter/'.$po,'Preview',$atts));
//        $data['table'] = $this->table->generate();

        $data['pono'] = $po;
        $this->load->view('sales_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];

       $sales = $this->Csales_model->get_sales_by_no($po)->row();
       
       // customer
       $customer = $this->customer->get_customer_details($sales->customer);
       $data['customer'] = $sales->prefix.' '.$sales->name;
       $data['address'] = $customer->address;
       $data['city'] = $customer->city;
       $data['phone'] = $customer->phone1;
       $data['phone2'] = $customer->phone2;

       //sales
       $data['pono'] = 'CSO-00'.$po.'/'.$this->get_romawi(date("m",strtotime($sales->dates))).'/'.date("Y",strtotime($sales->dates));
       $data['podate'] = tglincomplete($sales->dates);
       $data['desc'] = $sales->desc;
       $data['notes'] = $sales->notes;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);
       $data['log'] = $this->session->userdata('log');
       $data['cost'] = $sales->costs;
       $data['p1'] = $sales->p1;
       $data['p2'] = $sales->p2;

       // sales item
       $data['items'] = $this->sitem->get_last_item($po)->result();

       // property display
       $data['logo'] = $this->properti['logo'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       if ($sales->currency == "IDR"){ $data['symbol'] = 'Rp.'; $matauang = 'rupiah'; }
       else { $data['symbol'] = ''; $matauang = ''; }


       $data['status'] = $this->status($sales->status);
       $app = null;
       if ($sales->approved == 1){ $app = 'A'; } else{ $app = 'NA'; }
       $data['approve'] = $app;

       
//       number_format($salesitem->price,0,",",".")
       
       $terbilang = $this->load->library('terbilang');
       $data['terbilang'] = ucwords($terbilang->baca($sales->total+$sales->costs-$sales->p1).' '.$matauang);

       if ($type){ $this->load->view('sales_invoice_blank', $data); } else { $this->load->view('sales_order_invoice', $data); }
       
   }

   private function get_romawi($val)
   {
       switch ($val)
       {
           case 01: $val = 'I'; break;
           case 02: $val = 'II'; break;
           case 03: $val = 'III'; break;
           case 04: $val = 'IV'; break;
           case 05: $val = 'V'; break;
           case 06: $val = 'VI'; break;
           case 07: $val = 'VII'; break;
           case 08: $val = 'VIII'; break;
           case 09: $val = 'IX'; break;
           case 10: $val = 'X'; break;
           case 11: $val = 'XI'; break;
           case 12: $val = 'XII'; break;
       }
       return $val;
   }

   function print_expediter($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Expediter'.$this->modul['title'];

       $sales = $this->Csales_model->get_sales_by_no($po)->row();

       $data['pono'] = '000'.$po.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / P / '.date("Y",strtotime($sales->dates));

       $data['podate'] = tgleng($sales->dates);
       $data['customer'] = $sales->prefix.' '.$sales->name;
       $data['shipdate'] = tgleng($sales->shipping_date);
       $data['desc'] = $sales->desc;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);
       $data['docno'] = $sales->docno;
       $data['notes'] = $sales->notes;

       // customer
       $cust = $this->customer->get_customer_details($sales->customer);
       $data['address'] = $cust->address;
       $data['city']    = $cust->city;
       $data['phone']   = $cust->phone1;
       $data['phone2']  = $cust->phone2;

       $data['cost'] = $sales->costs;
       $data['p2'] = $sales->p2;
       $data['p1'] = $sales->p1;

       $data['items'] = $this->sitem->get_last_item($po)->result();

       // property display
       $data['p_name'] = $this->properti['name'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

//       customer
       $customer = $this->customer->get_customer_details($sales->customer);
       $data['c_name']    = strtoupper($customer->prefix.' '.$customer->name);
       $data['c_address'] = strtoupper($customer->address);
       $data['c_city']    = strtoupper($customer->city);
       $data['c_zip']     = strtoupper($customer->zip);
       $data['c_npwp']    = strtoupper($customer->npwp);

       $this->load->view('sales_expediter', $data);
   }

   function tax_invoice($po=null,$page=1)
   {
      $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax'.$this->modul['title'];

       $sales = $this->Csales_model->get_sales_by_no($po)->row();
       $salesitem = $this->sitem->get_last_item($po)->row();

       $data['pono'] = '0'.$po.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / P / '.date("Y",strtotime($sales->dates));
       $data['podate'] = tgleng($sales->dates);
       $data['customer'] = strtoupper($sales->prefix.' '.$sales->name);
       $data['desc'] = $sales->desc;
       $data['notes'] = $sales->notes;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);

       $data['cost'] = $sales->costs;
       $data['p2'] = $sales->p2;
       $data['p1'] = 0;
       $data['discount'] = $sales->discount;
       $data['discountpercent'] = $salesitem->discount;
       $data['bruto'] = $sales->total - $sales->tax + $sales->discount;
       $data['total'] = $sales->total + $sales->costs;
       $data['netto'] = $data['bruto']-$sales->discount;
       $data['tax'] = $sales->tax;

//       properti
       $data['name'] = strtoupper($this->properti['name']);
       $data['address'] = strtoupper($this->properti['address']);
       $data['city'] = strtoupper($this->properti['city']);
       $data['zip'] = strtoupper($this->properti['zip']);
       $data['npwp'] = strtoupper($this->properti['npwp']);
       $data['cp'] = strtoupper($this->properti['cp']);
       
//       customer
       $customer = $this->customer->get_customer_details($sales->customer);
       $data['c_name']    = strtoupper($customer->prefix.' '.$customer->name);
       $data['c_address'] = strtoupper($customer->address);
       $data['c_city']    = strtoupper($customer->city);
       $data['c_zip']     = strtoupper($customer->zip);
       $data['c_npwp']    = strtoupper($customer->npwp);

//       product details
       $data['p_name'] = $sales->notes;

       switch ($page) { case 1: $page = 'fakturpajak'; break; case 2: $page = 'fakturpajak2'; break; case 3: $page = 'fakturpajak3'; break; case 'format': $page = 'fakturpajakformat'; break; }
       $this->load->view($page, $data);
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
        
        $this->load->view('sales_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $customer = $this->input->post('tcust');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
        $status = $this->input->post('cstatus');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['saless'] = $this->Csales_model->report($customer,$cur,$start,$end,$status)->result();
        $total = $this->Csales_model->total($customer,$cur,$start,$end,$status);
        
        $data['total'] = $total['total'] - $total['tax'] + $total['discount'];
        $data['tax'] = $total['tax'];
        $data['discount'] = $total['discount'];
        $data['p1'] = $total['p1'];
        $data['p2'] = $total['p2'];
        $data['costs'] = $total['costs'];
        $data['ptotal'] = $total['total'] + $total['costs'];


        if ($type == 'detail')
        { $this->load->view('sales_report_details', $data); }
        else {  $this->load->view('sales_report', $data); }
        
    }


// ====================================== REPORT =========================================

// ======================================= COST ==========================================

    function cost($po)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create Csales Invoice - SO-00'.$po;
	$data['form_action'] = site_url($this->title.'/cost_item/'.$po);
        $data['main_view'] = 'sales_cost';
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        $items = $this->sinvoice->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Date', 'Part', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, tgleng($item->dates), $item->part, number_format($item->amount),
                anchor_popup($this->title.'/cost_invoice/'.$item->id.'/'.$po,'<span>print</span>',$atts).' '.
                anchor($this->title.'/delete_cost_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('sales_cost', $data);
        
    }

    function cost_item($po)
    {
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_approved['.$po.']');
        $this->form_validation->set_rules('cpart', 'Part', 'required|callback_valid_part['.$po.']');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric|callback_valid_amount['.$po.']');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('sales' => $po, 'dates' => $this->input->post('tdate'),
                           'part' => $this->input->post('cpart'), 'amount' => $this->input->post('tamount'));

            $this->sinvoice->add($pitem);
            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    function delete_cost_item($id,$po)
    {
        $this->acl->otentikasi3($this->title);
        $this->cek_status($po,'cost');

        $this->sinvoice->delete($id); 
        $this->session->set_flashdata('message', "1 item successfully removed..!"); 
        redirect($this->title.'/cost/'.$po);
    }

    function cost_invoice($id,$po)
    {
       $this->acl->otentikasi2($this->title);

       $sales = $this->Csales_model->get_sales_by_no($po)->row();
       $invoice = $this->sinvoice->get_salesinvoice_by_id($id)->row();
       
       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['pono'] = '1/0159/V/P/2013';
       $data['logo'] = $this->properti['logo'];
       
       $customer = $this->customer->get_customer_details($sales->customer);
       $data['customer'] = $sales->prefix.' '.$sales->name;
       $data['address'] = $customer->address;
       $data['city'] = $customer->city;
       $data['phone'] = $customer->phone1;
       $data['phone2'] = $customer->phone2;
       $data['desc'] = $sales->desc;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);
       $data['docno'] = $sales->docno;
       $data['log'] = $this->session->userdata('log');

       $data['status'] = $this->status($sales->status);
       $app = null;
       if ($sales->approved == 1){ $app = 'A'; } else{ $app = 'NA'; }
       $data['approve'] = $app;

       // invoice details
       $data['podate'] = tgleng($invoice->dates);
       $data['amount'] = $invoice->amount;
       $data['cost'] = $invoice->cost;
       $data['notes'] = $invoice->notes;
       $data['part'] = $invoice->part;

      // property display
      $data['logo'] = $this->properti['logo'];
      $data['paddress'] = $this->properti['address'];
      $data['p_phone1'] = $this->properti['phone1'];
      $data['p_phone2'] = $this->properti['phone2'];
      $data['p_city'] = ucfirst($this->properti['city']);
      $data['p_zip'] = $this->properti['zip'];
      $data['p_npwp'] = $this->properti['npwp'];

      $this->load->view('cost_invoice', $data);
    }
    
    
    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_part($part,$po)
    {
        if ($this->sinvoice->valid_part($part,$po) == FALSE)
        {
            $this->form_validation->set_message('valid_part', "Payment term already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_amount($amount,$po)
    {
        $totalamount = $this->sinvoice->total($po);
        $totalamount = $totalamount['amount'] + $amount;

        $totalpo = $this->Csales_model->get_sales_by_no($po)->row();
        $totalpo = $totalpo->p2;

        if ($totalamount > $totalpo)
        {
            $this->form_validation->set_message('valid_amount', "Over Bills...!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_approved($date,$po)
    {
        $val = $this->Csales_model->get_sales_by_no($po)->row();

        if ($val->approved == 0)
        {
            $this->form_validation->set_message('valid_approved', "Order Haven't Approved...!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    private function cek_status($po=null,$page=null)
    {
        $sales = $this->Csales_model->get_sales_by_no($po)->row();

        if ( $sales->status == 1 )
        {
           $this->session->set_flashdata('message', "Can't delete / change value - SO-00$po..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }


}

?>