<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Apc extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Apc_trans_model', '', TRUE);
        $this->load->model('Apc_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->vendor = new Vendor_lib();
        $this->user = new Admin_lib();
        $this->tax = $this->load->library('tax_lib');
        $this->product = $this->load->library('products_lib');
        $this->cost = new Cost_lib();
        $this->category = $this->load->library('categories_lib');
        $this->ps = new Period_lib();
        $this->model = new Apcmodel();
        $this->ledger = new Cash_ledger_lib();
        $this->journalgl = new Journalgl_lib();
        $this->account = new Account_lib();
        $this->purchase = new Purchase_lib();
        $this->vinyl = new Printing_lib();
        $this->trans = new Trans_ledger_lib();
        $this->demand = new Cash_demand_lib();
    }

    private $properti, $modul, $title, $cost, $financial,$ps, $model, $ledger, $account, $purchase, $vinyl;
    private $vendor,$user,$tax,$journal,$journalgl,$product,$currency,$unit,$category,$trans,$demand;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');
    
    private  $attsupdate = array('width'=> '600','height'=> '300',
                                 'scrollbars' => 'yes','status'=> 'yes',
                                 'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 600)/2)+\'',
                                 'screeny'=> '0','class'=> 'edit','title'=> '', 'screeny' => '\'+((parseInt(screen.height) - 300)/2)+\'');

    function index()
    {
       $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();
        $data['year'] = null;
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $aps = $this->Apc_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Apc_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Type', 'Notes', 'Acc', 'Balance', '#', 'Action');

            $i = 0 + $offset;
            foreach ($aps as $ap)
            {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                if ($ap->type == 0){$type = 'General'; }elseif ($ap->type == 1){$type = 'Purchase'; }elseif ($ap->type == 2){$type = 'Printing'; }
                
                $this->table->add_row
                (
                    ++$i, 'DJC-00'.$ap->no, $ap->currency, tglin($ap->dates), $type, $ap->notes, ucfirst($ap->acc), number_format($ap->amount), $this->status($ap->status),
                    anchor($this->title.'/confirmation/'.$ap->id,'<span>update</span>',array('class' => $this->post_status($ap->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$ap->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$ap->id.'/'.$ap->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
        
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('cyear'));

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
        $arpData[0][2] = $this->Apc_model->total_chart(1,$year,$cur);
//
        $arpData[1][1] = 'February';
        $arpData[1][2] = $this->Apc_model->total_chart(2,$year,$cur);
//
        $arpData[2][1] = 'March';
        $arpData[2][2] = $this->Apc_model->total_chart(3,$year,$cur);
//
        $arpData[3][1] = 'April';
        $arpData[3][2] = $this->Apc_model->total_chart(4,$year,$cur);
//
        $arpData[4][1] = 'May';
        $arpData[4][2] = $this->Apc_model->total_chart(5,$year,$cur);
//
        $arpData[5][1] = 'June';
        $arpData[5][2] = $this->Apc_model->total_chart(6,$year,$cur);
//
        $arpData[6][1] = 'July';
        $arpData[6][2] = $this->Apc_model->total_chart(7,$year,$cur);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->Apc_model->total_chart(8,$year,$cur);
        
        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->Apc_model->total_chart(9,$year,$cur);
//        
        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->Apc_model->total_chart(10,$year,$cur);
//        
        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->Apc_model->total_chart(11,$year,$cur);
//        
        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->Apc_model->total_chart(12,$year,$cur);

        $strXML1 = $fusion->setDataXML($arpData,'','') ;
        $graph   = $fusion->renderChart($chart,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
        
    }
    
    private function get_search($no,$date)
    {
        if ($no){ $this->model->where('no', $no); }
        elseif($date){ $this->model->where('dates', $date); }
        return $this->model->get();
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'ap_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

        $aps = $this->get_search($this->input->post('tno'), $this->input->post('tdate'));
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
       $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Acc', 'Balance', '#', 'Action');

        $i = 0;
        foreach ($aps as $ap)
        {
//                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'DJC-00'.$ap->no, $ap->currency, tglin($ap->dates), $ap->notes, ucfirst($ap->acc), number_format($ap->amount), $this->status($ap->status),
                anchor($this->title.'/confirmation/'.$ap->id,'<span>update</span>',array('class' => $this->post_status($ap->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$ap->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$ap->id.'/'.$ap->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('cyear'));
        $this->load->view('template', $data);
    }

    private function status($val=null)
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
        $ap = $this->model->where('id',$pid)->get();

        if ($ap->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
//            $this->cek_journal($ap->dates,$ap->currency); // cek apakah journal sudah approved atau belum
            $total = $ap->amount;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $this->settled_po($pid,$ap->type); // fungsi untuk mensettled kan semua po
                
                $this->model->approved = 1;
                $this->model->status = 1;
                $this->model->save();
                $this->model->clear();
                
                $ap1 = $this->model->where('id',$pid)->get();

                //  create journal gl
                
                $cm = new Control_model();

                $account  = $ap1->account; 
                $hutang   = $cm->get_id(11);
                
                // create journal- GL
                $this->journalgl->new_journal('0'.$ap1->no,$ap1->dates,'DJC',$ap1->currency,$ap1->notes,$ap1->amount, $this->session->userdata('log'));

                $transs = $this->Apc_trans_model->get_last_item($pid)->result(); 
                $dpid = $this->journalgl->get_journal_id('DJC','0'.$ap1->no);
                
                if ($ap->type == '0'){
                    
                    foreach ($transs as $trans) 
                    {
    //                    $this->cost->get_acc($trans->cost);
                        $this->journalgl->add_trans($dpid,$this->cost->get_acc($trans->cost),$trans->amount,0); // biaya
                    }
                    $this->journalgl->add_trans($dpid,$account,0,$ap1->amount); // kas, bank, kas kecil
                    
                }else{
                    $this->journalgl->add_trans($dpid,$hutang,intval($ap1->amount),0); // hutang usaha
                    $this->journalgl->add_trans($dpid,$account,0,$ap1->amount); // kas, bank, kas kecil
                }
                
               $this->session->set_flashdata('message', "$this->title DJC-00$ap1->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
            }
        }

    }
    
    private function settled_po($pid,$type)
    {
        $vals = $this->Apc_trans_model->get_last_item($pid,$type)->result();
        $ap = $this->model->where('id',$pid)->get();
        
        if ($type == '1')
        {
            foreach ($vals as $val)
            {
                $purchase = $this->purchase->get_po($val->trans_no);
                $p2 = $purchase->p2;

                if ($val->amount - $p2 >= 0)
                {
                   $data = array('status' => 1,'p2' => $p2-$val->amount);
                   $this->purchase->settled_po($val->trans_no,$data);
                }
                else
                {
                    $datax = array('p2' => $p2-$val->amount);
                    $this->purchase->settled_po($val->trans_no,$datax);
                }
                
                // membuat kartu hutang
                $this->trans->add($ap->acc, 'APC', $ap->no, $ap->currency, $ap->dates, intval($ap->amount), 0, $purchase->vendor, 'AP'); 
                
            }
        }
        elseif ($type == '2')
        {
            foreach ($vals as $val)
            {
                $purchase = $this->vinyl->get_po($val->trans_no);
                $p2 = $purchase->p2;

                if ($val->amount - $p2 >= 0)
                {
                   $data = array('status' => 1,'p2' => $p2-$val->amount);
                   $this->vinyl->settled_po($val->trans_no,$data);
                }
                else
                {
                    $datax = array('p2' => $p2-$val->amount);
                    $this->vinyl->settled_po($val->trans_no,$datax);
                }
                
                // membuat kartu hutang
                $this->trans->add($ap->acc, 'APC', $ap->no, $ap->currency, $ap->dates, intval($ap->amount), 0, $purchase->vendor, 'AP'); 
            }
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
        $ap = $this->model->where('id', $po)->get();

        if ( $ap->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - DJ-00$ap->no approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }

//    ===================== approval ===========================================


    private function create_po_journal($pid,$date,$currency,$code,$codetrans,$no,$type,$amount,$p1,$p2)
    {
        $cm = new Control_model();
        
        $landed   = $cm->get_id(1);
        $discount = $cm->get_id(3);
        $tax      = $cm->get_id(9);
        $stock    = $cm->get_id(10);
        $ap       = $cm->get_id(11);
        $bank     = $cm->get_id(12);
        
        $ap = $this->Apc_model->get_purchase_by_id($pid)->row();
        
        
        if ($p1 > 0)
        {
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount);
           $this->journal->create_journal($date,$currency,$code.' (Cash) ','DP',$no,'AP', $p1);
           
           // create journal- GL
           $this->journalgl->new_journal($no,$date,'PJ',$currency,$code,$amount, $this->session->userdata('log'));
           $this->journalgl->new_journal($no,$date,'CD',$currency,'DP Payment : PJ-00'.$no,$p1, $this->session->userdata('log'));
           
           $jid = $this->journalgl->get_journal_id('PJ',$ap->no);
           $dpid = $this->journalgl->get_journal_id('CD',$ap->no);
           
           $this->journalgl->add_trans($jid,$stock,$ap->total-$ap->tax-$ap->discount,0); // tambah persediaan
           $this->journalgl->add_trans($jid,$ap,0,$ap->p1+$ap->p2); // hutang usaha
           if ($ap->tax > 0){ $this->journalgl->add_trans($jid,$tax,$ap->tax,0); } // pajak pembelian
           if ($ap->costs > 0){ $this->journalgl->add_trans($jid,$landed,$ap->costs,0); } // landed costs
           if ($ap->discount > 0){ $this->journalgl->add_trans($jid,$discount,$ap->discount,0); } // discount
           
           //DP proses
           $this->journalgl->add_trans($dpid,$ap,$ap->p1,0); // potongan hutang usaha
           $this->journalgl->add_trans($dpid,$bank,0,$ap->p1); // potongan bank pembelian
           
        }
        else 
        { 
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount); 
           $this->journalgl->new_journal($no,$date,'PJ',$currency,$code,$amount, $this->session->userdata('log'));
           
           $jid = $this->journalgl->get_journal_id('PJ',$ap->no);
           $dpid = $this->journalgl->get_journal_id('CD',$ap->no);
            
           $this->journalgl->add_trans($jid,$stock,$ap->total-$ap->tax-$ap->discount,0); // tambah persediaan
           $this->journalgl->add_trans($jid,$ap,0,$ap->p1+$ap->p2); // hutang usaha
           if ($ap->tax > 0){ $this->journalgl->add_trans($jid,$tax,$ap->tax,0); } // pajak pembelian
           if ($ap->costs > 0){ $this->journalgl->add_trans($jid,$landed,$ap->costs,0); } // landed costs
           if ($ap->discount > 0){ $this->journalgl->add_trans($jid,$discount,$ap->discount,0); } // discount
        }
    }


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->model->where('id',$uid)->get();

        if ($val->approved == 1){ $this->void($uid,$po); }
        elseif ( $this->valid_period($val->dates) == TRUE ) // cek journal harian sudah di approve atau belum
        {
            $this->Apc_trans_model->delete_po($uid); // model to delete item
            $this->model->delete(); // memanggil model untuk mendelete data
            
            // remove cash ledger
            $this->ledger->remove($val->dates, "DJC-00".$val->no);
            
//            $this->journal->remove_journal('GJ',$po); // delete journal
//            $this->journalgl->remove_journal('DJ', $po); // journal gl

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            redirect($this->title);
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!");
           redirect($this->title);
        } 
    }
    
    private function unsettled_po($pid,$type)
    {
        $vals = $this->Apc_trans_model->get_last_item($pid,$type)->result();
        
        if ($type == '1')
        {
            foreach ($vals as $val)
            {
                $p2 = $this->purchase->get_po($val->trans_no);
                $p2 = $p2->p2;
                $data = array('status' => 0, 'p2'=> $val->amount+$p2);
                $this->purchase->settled_po($val->trans_no,$data);
            }
        }
        elseif ($type == '2')
        {
            foreach ($vals as $val)
            {
                $p2 = $this->vinyl->get_po($val->trans_no);
                $p2 = $p2->p2;
                $data = array('status' => 0, 'p2'=> $val->amount+$p2);
                $this->vinyl->settled_po($val->trans_no,$data);
            }
        } 
    }
    
    private function void($uid,$po)
    {
       $val = $this->model->where('id',$uid)->get();
       if ($this->valid_period($val->dates) == TRUE)
       {
           $this->journalgl->remove_journal('DJC', '0'.$po); // journal gl
           $this->unsettled_po($uid,$val->type); 
           $this->trans->remove($val->dates, 'APC', $val->no);
           
           $val->approved = 0;
           $val->status = 0;
           $val->save();
           $this->session->set_flashdata('message', "1 $this->title successfull voided..!");  
       }
       else { $this->session->set_flashdata('message', "Invalid Period..!");   }
       redirect($this->title);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Apc_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('ap_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'purchase_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Apc_model->counter();

	// Form validation
        $this->form_validation->set_rules('tno', 'DJ - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $trans = array('no' => $this->input->post('tno'), 'status' => 0, 'type' => $this->input->post('ctype'),
                           'dates' => $this->input->post('tdate'), 'acc' => $this->input->post('cacc'), 'demand' => $this->input->post('tdemand'),
                           'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 
                           'desc' => $this->input->post('tdesc'), 'user' => $this->user->get_userid($this->input->post('tuser')),
                           'log' => $this->session->userdata('log'));
            
            $this->Apc_model->add($trans);
            
            // add demand trans
            if ($this->input->post('tno') != ""){
                $this->add_demand_trans($this->input->post('tno'));
            }
            
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('ap_form', $data);
//            echo validation_errors();
        }

    }
    
    function add_demand_trans($no)
    {
        if ($no)
        {
          $ap = $this->model->where('no',$no)->get();
          if ($ap->approved == 0){
              
            $result = $this->demand->get_by_no($ap->demand);
            foreach ($result as $res)
            {
              $pitem = array('apc_id' => $ap->id, 'cost' => $res->cost, 'type' => '0',
                         'notes' => $res->notes,
                         'staff' => '',
                         'amount' => $res->amount);

              $this->Apc_trans_model->add($pitem);
              $this->update_trans($ap->id);
            }  
          }
        }
    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);
        
        $ap = $this->model->where('no', $po)->get();

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$ap->id);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$ap->id.'/'.$ap->no);
        $data['currency'] = $this->currency->combo();
        $data['code'] = $ap->no;
        $data['user'] = $this->session->userdata("username");
        
        if ($ap->acc == 'bank'){ $data['account'] = $this->account->combo_asset(); }else { $data['account'] = $this->account->combo_based_classi(7); }
        
        $data['default']['vendor'] = $this->vendor->get_vendor_shortname($ap->vendor);
        $data['venid'] = $ap->vendor;
        $data['default']['date'] = $ap->dates;
        $data['default']['currency'] = $ap->currency;
        $data['default']['acc'] = $ap->acc;
        $data['default']['note'] = $ap->notes;
        $data['default']['desc'] = $ap->desc;
        $data['default']['user'] = $this->user->get_username($ap->user);
        $data['default']['docno'] = $ap->docno;
        $data['default']['account'] = $ap->account;
        $data['default']['type'] = $ap->type;
        $data['default']['demand'] = $ap->demand;
        
        $data['default']['balance'] = $ap->amount;
        if ($ap->type == 0){ $type = 'GENERAL'; $view = 'apc_transform'; }elseif($ap->type == 1){ $type = 'PURCHASE'; $view = 'apc_transform_purchase'; }
        elseif($ap->type == 2){ $type = 'PRINTING'; $view = 'apc_transform_purchase'; }
        
        $data['default']['transtype'] = $type;

//        ============================ Apc Item  =========================================
        $items = $this->Apc_trans_model->get_last_item($ap->id)->result();
        
        $data['cost'] = $this->cost->combo();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Cost Type', 'Code', 'Trans No', 'Notes', 'Staff', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            if ($item->type == 0){ $type = ''; }elseif ($item->type == 1){ $type = 'PO-00'; }elseif ($item->type == 2){ $type = 'CP-00'; }
            $this->table->add_row
            (
                ++$i, $this->cost->get_name($item->cost), $type, $item->trans_no, $item->notes, $item->staff, number_format($item->amount),
                anchor_popup($this->title.'/print_item/'.$item->id,'<span>print</span>',$this->atts).' '.
                anchor_popup($this->title.'/edit_item/'.$item->id,'<span>print</span>',$this->attsupdate).' '.
                anchor($this->title.'/delete_item/'.$item->id.'/'.$ap->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        
        $this->load->view($view, $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($pid=null,$po)
    {
//        $this->cek_confirmation($pid,'add_trans');
        
        $ap = $this->model->where('no', $po)->get();
        
        if ($ap->type == '0'){
            $this->form_validation->set_rules('ccost', 'Cost Type', 'required');
            $this->form_validation->set_rules('tstaff', 'Staff', 'required');
            $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');
        }else{
            $this->form_validation->set_rules('titem', 'Transaction No', 'required');
        }
        

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($po) == TRUE)
        {
            if ($ap->type == '0'){
                $pitem = array('apc_id' => $pid, 'cost' => $this->input->post('ccost'),
                           'notes' => $this->input->post('tnotes'),
                           'staff' => $this->input->post('tstaff'),
                           'amount' => $this->input->post('tamount'));
            }elseif ($ap->type == '1'){
                $purchase = $this->purchase->get_po($this->input->post('titem'));
                $pitem = array('apc_id' => $pid, 'cost' => 0, 'trans_no' => $this->input->post('titem'),
                           'notes' => $purchase->notes.' : '.tglin($purchase->dates),'staff' => '', 'type' => $ap->type, 'amount' =>  $purchase->p2);
            }elseif ($ap->type == '2'){
                $purchase = $this->vinyl->get_po($this->input->post('titem'));
                $pitem = array('apc_id' => $pid, 'cost' => 0, 'trans_no' => $this->input->post('titem'),
                           'notes' => $purchase->notes.' : '.tglin($purchase->dates),'staff' => '', 'type' => $ap->type, 'amount' =>  $purchase->p2);
            }
            
            $this->Apc_trans_model->add($pitem);
            $this->update_trans($pid);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }
    
    function edit_item($id)
    {
       $this->acl->otentikasi2($this->title); 
       $val = $this->Apc_trans_model->get_by_id($id);  
       $data['form_action_item'] = site_url($this->title.'/edit_item_process/'.$id.'/'.$val->apc_id); 
       
       $data['cost'] = $this->cost->combo();
       
       $data['default']['notes'] = $val->notes;
       $data['default']['staff'] = $val->staff;
       $data['default']['amount'] = $val->amount;       
       $data['default']['cost'] = $val->cost;
        
       $this->load->view('apc_update_item', $data); 
    }
    
    function edit_item_process($id,$apc)
    {
        $ap = $this->model->where('id', $apc)->get();
        
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($ap->no) == TRUE)
        {
            $pitem = array('notes' => $this->input->post('tnotes'), 
                           'cost' => $this->input->post('ccost'),
                           'staff' => $this->input->post('tstaff'),
                           'amount' => $this->input->post('tamount'));
            
            $this->Apc_trans_model->update($id,$pitem);
            $this->update_trans($apc);
        }
        
        redirect($this->title.'/edit_item/'.$id);
    }

    private function update_trans($pid)
    {
        $totals = $this->Apc_trans_model->total($pid);
        
        $this->model->where('id', $pid)->get();
        $this->model->amount = intval($totals['amount']);
        $this->model->save();
    }

    function delete_item($id,$pid)
    {
        $this->cek_confirmation($pid,'add_trans');
        $this->acl->otentikasi2($this->title);
        $no = $this->model->where('id', $pid)->get();
        
        $this->Apc_trans_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($pid);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$no->no);
    }
    
    function print_item($id)
    {
//        $this->cek_confirmation($pid,'add_trans');
        $this->acl->otentikasi1($this->title);
        $terbilang = $this->load->library('terbilang');
        
        $value = $this->Apc_trans_model->get_by_id($id);
        $ap = $this->model->where('id', $value->apc_id)->get();
        
        $data['pono'] = $ap->no;
        $data['staff'] = $value->staff;
        $data['currency'] = $ap->currency;
        $data['notes'] = $value->notes;
        $data['cost'] = $value->cost;
        $data['amount'] = $value->amount;
        $data['user'] = $this->user->get_username($ap->user);
        
        if ($ap->currency == 'IDR')
        { $data['terbilang'] = ucwords($terbilang->baca($value->amount)).' Rupiah'; }
        else { $data['terbilang'] = ucwords($terbilang->baca($value->amount)); }
        
        if ($ap->acc == 'pettycash'){ $this->load->view('apc_receipt', $data); }
        else
        {
           if ($ap->approved == 1){ $this->load->view('apc_receipt', $data); }
           else { $this->load->view('rejected', $data); } 
        } 
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($pid=null)
    {
        $this->acl->otentikasi2($this->title);
//        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

	// Form validation
        
        $this->form_validation->set_rules('tno', 'DJ - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('ctype', 'Transaction Type', 'required|callback_valid_type['.$pid.']');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        { 
            // cash ledger
            $val = $this->model->where('id',$pid)->get();
            $this->ledger->remove($val->dates, "DJC-00".$val->no);
            
            $this->model->where('id',$pid)->get();
            
            $this->model->no       = $this->input->post('tno');
            $this->model->status   = 0;
            $this->model->docno    = $this->input->post('tdocno');
            $this->model->dates    = $this->input->post('tdate');
            $this->model->acc      = $this->input->post('cacc');
            $this->model->account  = $this->input->post('caccount');
            $this->model->notes    = $this->input->post('tnote');
            $this->model->desc     = $this->input->post('tdesc');
            $this->model->user     = $this->user->get_userid($this->input->post('tuser'));
            $this->model->type     = $this->input->post('ctype');
            $this->model->log      = $this->session->userdata('log');
            
            
            $this->ledger->add($this->model->acc, "DJC-00".$this->model->no, $this->model->currency, $this->model->dates, 0, $this->model->amount);
            $this->model->save();

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
        else{ return TRUE; }
    }

   public function valid_no($no)
   {
        if ($this->Apc_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
   }
   
   public function valid_type($type,$pid)
    {
        $val = $this->model->where('id',$pid)->get();
        $numrows = $this->Apc_trans_model->get_last_item($pid)->num_rows();

        if ($val->type != $type)
        {
            if ($numrows > 0){ 
               $this->form_validation->set_message('valid_type', "Remove Transaction list first to update journal..!.!");
               return FALSE;
            }else{ return TRUE; }
        }
        else {  return TRUE; }
    }

    public function valid_confirmation($no)
    {
        $ap = $this->model->where('no', $no)->get();

        if ($ap->approved == 1)
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
       $ap = $this->model->where('no', $po)->get();
       $viewx = null;
       $data['h2title'] = 'Print Invoice'.$this->modul['title'];
       
       if ($ap->type == '0'){ $data['type'] = 'GENERAL'; $viewx = 'apc_invoice'; }
       elseif ($ap->type == '1'){ $data['type'] = 'PURCHASE';  $viewx = 'apc_invoice_purchase'; }
       elseif ($ap->type == '2'){ $data['type'] = 'PRINTING'; $viewx = 'apc_invoice_purchase'; }

       $data['pono'] = $po;
       $data['podate'] = tgleng($ap->dates);
       $data['vendor'] = "";
       $data['venbank'] = "";
       $data['notes'] = $ap->notes;
       $data['demand'] = $ap->demand;
       $data['acc'] = ucfirst($ap->acc);
       $data['user'] = $this->user->get_username($ap->user);
       $data['currency'] = $ap->currency;
       $data['docno'] = $ap->docno;
       $data['log'] = $this->session->userdata('log');
       $data['account'] = $this->account->get_code($ap->account).' : '. $this->account->get_name($ap->account);

       $data['amount'] = $ap->amount;
       $terbilang = $this->load->library('terbilang');
       if ($ap->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($ap->amount)); }
       
       
       if($ap->approved == 1){ $stts = 'A'; }else{ $stts = 'NA'; }
       $data['stts'] = $stts;

       $data['items'] = $this->Apc_trans_model->get_last_item($ap->id)->result();
       
       $data['accounting'] = $this->properti['accounting'];
       $data['manager'] = $this->properti['manager'];
       
//       if ($ap->approved != 1){ $this->load->view('rejected', $data); }
//       else { $this->load->view('apc_invoice', $data); }
       $this->load->view($viewx, $data);

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
        $data['category'] = $this->category->combo_all();
        
        $this->load->view('apc_report_panel', $data);
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
        $category = null;
        $acc      = $this->input->post('cacc');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['account'] = ucfirst($acc);
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        if ($type == 0){ $data['aps'] = $this->Apc_model->report($cur,$start,$end,$category,$acc)->result(); $page = 'apc_report'; }
        elseif ($type == 1){ $data['aps'] = $this->Apc_model->report($cur,$start,$end,$category,$acc)->result(); $page = 'apc_report_details'; }
        elseif ($type == 2) { $data['aps'] = $this->Apc_model->report_category($vendor,$cur,$start,$end,$category,$acc)->result(); $page = 'apc_report_category'; }
        elseif ($type == 3) { $data['aps'] = $this->Apc_model->report_category($vendor,$cur,$start,$end,$category,$acc)->result(); $page = 'apc_pivot'; }
        
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
        
    }


// ====================================== REPORT =========================================

// ====================================== CASH LEDGER ====================================
   
    function cash_ledger()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/cash_ledger_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('cash_ledger_report_panel', $data);
    }
    
    function cash_ledger_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur   = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end   = $this->input->post('tend');
        $acc   = $this->input->post('cacc');

        $data['currency'] = $cur;
        $data['start'] = tglin($start);
        $data['end'] = tglin($end);
        $data['account'] = ucfirst($acc);
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['opening'] = $this->ledger->get_sum_transaction_open_balance($acc, $cur, $start);
        $data['trans'] = $this->ledger->get_transaction($acc, $cur, $start, $end)->result();
        $data['endbalance'] = $this->ledger->get_sum_transaction_balance($acc, $cur, $start, $end);
        
        $this->load->view('cash_ledger_invoice', $data);
    }
    
}

?>