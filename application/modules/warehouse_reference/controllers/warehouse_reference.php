<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_reference extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Main_model', '', TRUE);
        
        $this->load->library('property');
        $this->customer = $this->load->library('customer_lib');
        $this->vendor = $this->load->library('vendor_lib');
        $this->load->library('user_agent');
        $this->properti = $this->property->get();

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

        $this->acl->otentikasi();
    }

    var $title = 'main';
    var $limit = null;
    private $properti,$vendor,$customer;

    function index()
    {       
       $this->main_panel();
    }

    function main_panel()
    {
       $data['name'] = $this->properti['name'];
       $data['title'] = $this->properti['name'].' | Administrator  '.ucwords('Main Panel');
       $data['h2title'] = "Warehouse Reference";

       $data['waktu'] = tgleng(date('Y-m-d')).' - '.waktuindo().' WIB';
       $data['user_agent'] = $this->user_agent();
       $data['main_view'] = 'warehouse_reference/warehouse_reference_view';

       $this->load->view('template', $data);

    }
    
    private function user_agent()
    {
        $agent=null;
        if ($this->agent->is_browser()){  $agent = $this->agent->browser().' '.$this->agent->version();}
        elseif ($this->agent->is_robot()){ $agent = $this->agent->robot(); }
        elseif ($this->agent->is_mobile()){ $agent = $this->agent->mobile(); }
        else{ $agent = 'Unidentified User Agent'; }
        return $agent." - ".$this->agent->platform();
    }

    function registration()
    {
       $this->acl->otentikasi(); 
       $data['name'] = $this->properti['name'];
       $data['title'] = $this->properti['name'].' | Administrator  '.ucwords('Main Panel');
       $data['h2title'] = "Student Registration";

       $data['waktu'] = tgleng(date('Y-m-d')).' - '.waktuindo().' WIB';
       $data['user_agent'] = $this->user_agent();
       $data['main_view'] = 'academic/academic_registration';

       $this->load->view('template', $data);
    }
    
}

?>