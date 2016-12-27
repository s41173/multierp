<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_backup extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Purchase_backup_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->load->library('currency');
        $this->load->library('unit');
        $this->vendor = $this->load->library('vendor');
        $this->user = $this->load->library('admin');
        $this->tax = $this->load->library('tax');
        $this->journal = $this->load->library('journal');
        $this->product = $this->load->library('products');

    }

    private $properti, $modul, $title;
    private $vendor,$user,$tax,$journal,$product;

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

	// ---------------------------------------- //
        $purchases = $this->Purchase_backup_model->get_last_purchase($this->modul['limit'], $offset)->result();
        $num_rows = $this->Purchase_backup_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Vendor', 'Notes', 'Total', 'Balance');

            $i = 0 + $offset;
            foreach ($purchases as $purchase)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $purchase->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'PO-00'.$purchase->no, $purchase->currency, tgleng($purchase->dates), $purchase->prefix.' '.$purchase->name, $purchase->notes, number_format($purchase->total + $purchase->costs), number_format($purchase->p2)
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
        $data['main_view'] = 'purchase_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('purchase_backup/','<span>back</span>', array('class' => 'back')));

        $purchases = $this->Purchase_backup_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tstart'), $this->input->post('tend'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
         $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Vendor', 'Notes', 'Total', 'Balance');

         $i = 0;
         foreach ($purchases as $purchase)
         {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $purchase->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'PO-00'.$purchase->no, $purchase->currency, tgleng($purchase->dates), $purchase->prefix.' '.$purchase->name, $purchase->notes, number_format($purchase->total + $purchase->costs), number_format($purchase->p2)
            );
         }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }


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

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['purchases'] = $this->Purchase_backup_model->report($vendor,$cur,$start,$end)->result();
        $total             = $this->Purchase_backup_model->total($vendor,$cur,$start,$end);
        
        $data['total'] = $total['total'] - $total['tax'];
        $data['tax'] = $total['tax'];
        $data['p1'] = $total['p1'];
        $data['p2'] = $total['p2'];
        $data['costs'] = $total['costs'];
        $data['ptotal'] = $total['total'] + $total['costs'];

        $this->load->view('purchase_report', $data);
        
    }


// ====================================== REPORT =========================================

}

?>