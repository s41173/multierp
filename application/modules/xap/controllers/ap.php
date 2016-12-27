<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ap_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->vendor = $this->load->library('vendor_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->terbilang = $this->load->library('terbilang');

    }

    private $properti, $modul, $title;
    private $vendor,$user,$journal,$terbilang,$currency;

    function index()
    {
        $this->get_last_ap();
//        echo $this->terbilang->baca(194186400).' rupiah';

    }

    function get_last_ap()
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
        $aps = $this->Ap_model->get_last_ap($this->modul['limit'], $offset)->result();
        $num_rows = $this->Ap_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_ap');
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
            $this->table->set_heading('No', 'Code', 'Date', 'Vendor', 'Notes', 'Currency', 'Acc', 'Total', 'Status', 'Action');

            $i = 0 + $offset;
            foreach ($aps as $ap)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'GJ-00'.$ap->no, tgleng($ap->dates), $ap->prefix.' '.$ap->name, $ap->notes, $ap->currency, $this->acc_type($ap->acc), number_format($ap->amount), $this->status($ap->status),
                    anchor($this->title.'/confirmation/'.$ap->id,'<span>update</span>',array('class' => $this->post_status($ap->approved), 'title' => 'edit / update')).' '.
                    anchor($this->title.'/update/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$ap->id.'/'.$ap->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['link'] = array('link_back' => anchor('ap/','<span>back</span>', array('class' => 'back')));

        $aps = $this->Ap_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Vendor', 'Notes', 'Currency', 'Acc', 'Total', 'Status', 'Action');

        $i = 0;
        foreach ($aps as $ap)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $ap->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'GJ-00'.$ap->no, tgleng($ap->dates), $ap->prefix.' '.$ap->name, $ap->notes, $ap->currency, $this->acc_type($ap->acc), number_format($ap->amount), $this->status($ap->status),
                anchor($this->title.'/confirmation/'.$ap->id,'<span>update</span>',array('class' => $this->post_status($ap->approved), 'title' => 'edit / update')).' '.
                anchor($this->title.'/update/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$ap->id.'/'.$ap->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function get_list($currency=null, $acc=null, $vendor=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ap_list';

        $aps = $this->Ap_model->get_ap_list($currency,$acc,$vendor)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Balance', 'Action');

        $i = 0;
        foreach ($aps as $ap)
        {
           $data = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$ap->no.'\',\'titem\')'
                         );

            $this->table->add_row
            (
                ++$i, 'GJ-00'.$ap->no, tgleng($ap->dates), $ap->notes, number_format($ap->amount),
                form_button($data)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('ap_list', $data);
    }

    private function status($val=null)
    {
        switch ($val) { case 0: $val = 'debt'; break;  case 1: $val = 'settled'; break; }
        return $val;
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
//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $ap = $this->Ap_model->get_ap_by_id($pid)->row();

        if ($ap->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($ap->dates,$ap->currency); // cek apakah journal sudah approved atau belum
            $total = $ap->amount;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); // set flash data message dengan session
              redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Ap_model->update_id($pid, $data);

                //  create journal
                $this->journal->create_journal($ap->dates, $ap->currency, 'GJ-00'.$ap->no.'-'.$ap->notes, 'GJ', $ap->no, 'AP', $ap->amount);

               $this->session->set_flashdata('message', "$this->title PO-00$ap->no confirmed..!"); // set flash data message dengan session
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
        $ap = $this->Ap_model->get_ap_by_no($po)->row();

        if ( $ap->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - GJ-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);

        if ( $this->journal->cek_approval('GJ',$po) == TRUE ) // cek journal harian sudah di approve atau belum
        {
            $this->Ap_model->delete($uid); // memanggil model untuk mendelete data

            $this->journal->remove_journal('GJ',$po); // delete journal

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            redirect($this->title);
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!");
           redirect($this->title);
        } 
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['code'] = $this->Ap_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('ap_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'ap_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Ap_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
        $this->form_validation->set_rules('tno', 'GJ - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('tuser', 'User', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $ap = array('vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')), 'no' => $this->input->post('tno'), 'docno' => $this->input->post('tdocno'), 'acc' => $this->input->post('cacc'),
                        'dates' => $this->input->post('tdate'), 'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                        'amount' => $this->input->post('tamount'), 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Ap_model->add($ap);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add/');
//            echo 'true';
        }
        else
        {
              $this->load->view('ap_form', $data);
//            echo validation_errors();
        }

    }

    function update($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        $data['currency'] = $this->currency->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $ap = $this->Ap_model->get_ap_by_no($po)->row();

        $data['default']['vendor'] = $ap->name;
        $data['default']['date'] = $ap->dates;
        $data['default']['currency'] = $ap->currency;
        $data['default']['note'] = $ap->notes;
        $data['default']['desc'] = $ap->desc;
        $data['default']['amount'] = $ap->amount;
        $data['default']['user'] = $this->user->get_username($ap->user);
        $data['default']['acc'] = $ap->acc;
        $data['default']['docno'] = $ap->docno;

        $this->load->view('ap_update', $data);
    }


    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($po,'update');

        $data['currency'] = $this->currency->combo();

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('ap/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required|callback_valid_vendor');
        $this->form_validation->set_rules('tno', 'GJ - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('tuser', 'User', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $aps = $this->Ap_model->get_ap_by_no($po)->row();

            $ap = array('vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor')), 'no' => $this->input->post('tno'), 'docno' => $this->input->post('tdocno'), 'acc' => $this->input->post('cacc'),
                        'dates' => $this->input->post('tdate'), 'currency' => $this->input->post('ccurrency'), 'notes' => $this->input->post('tnote'), 'desc' => $this->input->post('tdesc'),
                        'amount' => $this->input->post('tamount'), 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));

            $this->Ap_model->update($po, $ap);
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$po);
//            echo 'true';
        }
        else
        {
            $this->load->view('ap_update', $data);
//            echo validation_errors();
        }
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
        if ($this->Ap_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
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
        $data['reports'] = $this->Ap_model->report($vendor,$start,$end,$acc,$cur,$status)->result();

        $total = $this->Ap_model->total($vendor,$start,$end,$acc,$cur,$status);
        $data['total'] = $total['amount'];

        $this->load->view('ap_report', $data);

    }

//    ================================ REPORT =====================================

   
}

?>