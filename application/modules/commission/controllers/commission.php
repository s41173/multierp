<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Commission extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Commission_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->customer = $this->load->library('customer_lib');
        $this->user = $this->load->library('admin_lib');
        $this->tax = $this->load->library('tax_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->nar = $this->load->library('nar_payment');

    }

    private $properti, $modul, $title;
    private $customer,$user,$tax,$journal,$nar,$currency,$journalgl;

    function index()
    { $this->get_last_commission(); }

    function get_last_commission()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'commission_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $saless = $this->Commission_model->get_last_commission($this->modul['limit'], $offset)->result();
        $num_rows = $this->Commission_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Balance', 'Action');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total),
//                    anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$sales->no,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'commission_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $atts = array('width'=> '400','height'=> '220',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        $saless = $this->Commission_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Balance', 'Action');

        $i = 0;
        foreach ($saless as $sales)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total),
//                anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$sales->no,'<span>print</span>',$atts).' '.
                anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function cek_space($val)
    {  $res = explode("<br />",$val);  if (count($res) == 1) { return $val;  } else { return implode('', $res); } }

    function get_list($currency=null,$customer=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];

        $saless = $this->Commission_model->get_commission_list($currency,$customer)->result();

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
                ++$i, 'NSO-00'.$sales->no, tgleng($sales->dates), $sales->notes, number_format($sales->total), number_format($sales->p2),
                form_button($data)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('commission_list', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'C'; break; case 1: $val = 'S'; break; } return $val; }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class;
    }

    function confirmation($pid)
    {
        $sales = $this->Commission_model->get_commission_by_id($pid)->row();

        if ($sales->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($sales->dates,$sales->currency); // cek apakah journal sudah approved atau belum
            $total = $sales->total;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Commission_model->update_id($pid, $data);

                //  create journal
                $this->create_po_journal($sales->dates, $sales->currency, 'NSO-00'.$sales->no.'-'.$sales->notes, 'NSJ',
                                         $sales->no, 'AR', $sales->total + $sales->costs, $sales->p1,$sales->p2);

               $this->session->set_flashdata('message', "$this->title NSO-00$sales->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
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
        $sales = $this->Commission_model->get_commission_by_no($po)->row();

        if ( $sales->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - NSO-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    private function create_po_journal($date,$currency,$code,$codetrans,$no,$type,$amount,$p1,$p2)
    {
        if ($p1 > 0)
        {
           $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount);
           $this->journal->create_journal($date,$currency,$code.' (Cash) ','NDS',$no,'AR', $p1);
        }
        else { $this->journal->create_journal($date,$currency,$code,$codetrans,$no,$type,$amount); }
    }


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $com = $this->Commission_model->get_commission_by_no($po)->row();
        
        if ($this->valid_period($com->dates) == TRUE)
        {
           $this->journalgl->remove_journal('CD', '000'.$po); // journal gl 
           $this->Commission_model->delete($uid); // memanggil model untuk mendelete data
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else
        {  $this->session->set_flashdata('message', "1 $this->title can't removed, invalid period..!"); }
        
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Commission_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('commission_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'sales_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Commission_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'CO - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required|callback_valid_date|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tbalance', 'Total', 'required|numeric');
        $this->form_validation->set_rules('cacc', 'Acc', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')), 'no' => $this->input->post('tno'), 'total' => $this->input->post('tbalance'),
                           'dates' => $this->input->post('tdate'), 'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                           'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Commission_model->add($sales);
            
            // gl proses
            
            $cm = new Control_model();
        
            $bank      = $cm->get_id(22);
            $kas       = $cm->get_id(13);
            $kaskecil  = $cm->get_id(14);
            $commision = $cm->get_id(33);
            $account  = 0;
            
            switch ($this->input->post('cacc')) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }              
               
            $this->journalgl->new_journal('000'.$this->input->post('tno'), $this->input->post('tdate'),'CD', $this->input->post('ccurrency'), 'Payment - Commision to : '.$this->input->post('tcustomer').' - '.  ucfirst($this->input->post('cacc')), $this->input->post('tbalance'), $this->session->userdata('log'));
            $dpid = $this->journalgl->get_journal_id('CD','000'.$this->input->post('tno'));
            
            $this->journalgl->add_trans($dpid,$commision,$this->input->post('tbalance'),0); // komisi debit
            $this->journalgl->add_trans($dpid,$account,0,$this->input->post('tbalance')); // kas, bank, kas kecil ( kredit )
               
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add/');
//            echo 'true';
        }
        else
        {
              $this->load->view('commission_form', $data);
//            echo validation_errors();
        }

    }

//    ==========================================================================================


    private function get_status($p2=null)
    { if ($p2 == 0){ return 1; } else { return 0; } }

    public function valid_customer($name)
    {
        if ($this->customer->valid_customer($name) == FALSE)
        {
            $this->form_validation->set_message('valid_customer', "Invalid Customer.!");
            return FALSE;
        }
        else { return TRUE; }
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

    public function valid_no($no)
    {
        if ($this->Commission_model->valid_no($no) == FALSE)
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

        $data['pono'] = $po;
        $this->load->view('commission_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];

       $sales = $this->Commission_model->get_commission_by_no($po)->row();
       $salesitem = $this->Commission_item_model->get_last_item($po)->row();

       $data['pono'] = '0'.$po.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / P / '.date("Y",strtotime($sales->dates));
       $data['podate'] = tglincomplete($sales->dates);
       $data['customer'] = strtoupper($sales->prefix.' '.$sales->name);
       $data['desc'] = $sales->desc;
       $data['notes'] = $sales->notes;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);

       if ($sales->currency == "IDR"){ $data['symbol'] = 'Rp.'; $matauang = 'rupiah'; }else { $data['symbol'] = ''; $matauang = ''; }

       $data['cost'] = $sales->costs;
       $data['p2'] = $sales->p2;
       $data['p1'] = $sales->p1;
       $data['discount'] = $sales->discount;
       $data['discountpercent'] = $salesitem->discount;
       $data['tax'] = $sales->tax;
       $data['bruto'] = $salesitem->size * $salesitem->coloumn * $salesitem->price;
       $data['total'] = $sales->total + $sales->costs-$sales->p1;
       $data['netto'] = $salesitem->size * $salesitem->coloumn * $salesitem->price - $sales->discount;
       
       $data['disdesc'] = $sales->discount_desc;
       $data['sup'] = $salesitem->sup;

       // -------------------------------
       $data['size'] = $salesitem->size;
       $data['coloumn'] = $salesitem->coloumn;
       $data['price'] = $salesitem->price;

       if ($salesitem->type == 0) { $data['size'] = ''; $data['coloumn'] = ''; $data['price'] = ''; }
       else { $data['size'] = $salesitem->size; $data['coloumn'] = $salesitem->coloumn; $data['price'] = number_format($salesitem->price,0,",","."); }
       
       $terbilang = $this->load->library('terbilang');
       $data['terbilang'] = ucwords($terbilang->baca($sales->total+$sales->costs-$sales->p1).' '.$matauang);

       if ($type){ $this->load->view('commission_invoice_blank', $data); } else { $this->load->view('commission_invoice', $data); }
       
   }

   function print_nontax_invoice($po=null,$page=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Non Tax Invoice'.$this->modul['title'];

       $sales = $this->Commission_model->get_commission_by_no($po)->row();

       $data['pono'] = '0'.$po.' / '.$this->get_romawi(date("m",strtotime($sales->dates))).' / N / '.date("Y",strtotime($sales->dates));
       $data['podate'] = tgleng($sales->dates);
       $data['customer'] = strtoupper($sales->prefix.' '.$sales->name);
       $data['desc'] = $sales->desc;
       $data['notes'] = $sales->notes;
       $data['user'] = $this->user->get_username($sales->user);
       $data['currency'] = $this->currency->get_code($sales->currency);

       if ($sales->currency == "IDR"){ $data['symbol'] = 'Rp.'; $matauang = 'rupiah'; } else { $data['symbol'] = ''; $matauang = ''; }

       $data['cost'] = "";
       $data['p2'] = '';
       $data['p1'] = '';
       $data['total'] = $sales->total;

       $terbilang = $this->load->library('terbilang');
       $data['terbilang'] = ucwords($terbilang->baca($sales->total).' '.$matauang);

       if ($page){ $this->load->view('commissionnontax_blank', $data); } else { $this->load->view('commissionnontax', $data); }
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

// ===================================== PRINT ===========================================

}

?>