<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_backup extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Sales_backup_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->load->library('currency');
        $this->customer = $this->load->library('customer');
        $this->user = $this->load->library('admin');
        $this->tax = $this->load->library('tax');
        $this->journal = $this->load->library('journal');

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title;
    private $customer,$user,$tax,$journal;

    function index()
    { $this->get_last_sales(); }

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
        $saless = $this->Sales_backup_model->get_last_sales($this->modul['limit'], $offset)->result();
        $num_rows = $this->Sales_backup_model->count_all_num_rows();

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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Total', 'Balance');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'SO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total + $sales->costs), number_format($sales->p2)
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
        $data['main_view'] = 'sales_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $atts = array('width'=> '400','height'=> '220',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        $saless = $this->Sales_backup_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tstart'), $this->input->post('tend'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
         $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Customer', 'Notes', 'Total', 'Balance');

         $i = 0;
         foreach ($saless as $sales)
         {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'SO-00'.$sales->no, $sales->currency, tglin($sales->dates), $sales->prefix.' '.$sales->name, $this->cek_space($sales->notes), number_format($sales->total + $sales->costs), number_format($sales->p2)
            );
         }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }
   
    private function cek_space($val)
    {  $res = explode("<br />",$val);  if (count($res) == 1) { return $val;  } else { return implode('', $res); } }
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

        $data['saless'] = $this->Sales_backup_model->report($customer,$cur,$start,$end,$status)->result();
        $total = $this->Sales_backup_model->total($customer,$cur,$start,$end,$status);
        
        $data['total'] = $total['total'] - $total['tax'] + $total['discount'];
        $data['tax'] = $total['tax'];
        $data['discount'] = $total['discount'];
        $data['p1'] = $total['p1'];
        $data['p2'] = $total['p2'];
        $data['costs'] = $total['costs'];
        $data['ptotal'] = $total['total'] + $total['costs'];

        $this->load->view('sales_report', $data); 
    }

// ====================================== REPORT =========================================

}

?>