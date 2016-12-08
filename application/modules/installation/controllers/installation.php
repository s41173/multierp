<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Installation extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Component_model', '', TRUE);
        $this->load->model('Closing_model', '', TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();
        $this->role = $this->load->library('role');

//        $this->modul = $this->installations->get('installation');
////        $this->title = $this->installation['name'];
        $this->title = strtolower(get_class($this));
    }
    
    private $properti, $role;
    var $title;
    var $limit = 25;
    
    function index()
    {
         $this->get_last_installation();
    }
    
    function get_last_installation()
    {
        $this->acl->otentikasi_admin($this->title);
        
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords('Component Manager');
        $data['h2title'] = 'Installation';
        $data['main_view'] = 'installation_view';
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $aps = $this->Component_model->get_truncate()->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Component', 'Table');

        $i = 0;
        foreach ($aps as $ap)
        {

            $this->table->add_row
            (
                  ++$i, $ap->title, $ap->table
//                anchor($this->title.'/details/'.$ap->no,'<span>details</span>',array('class' => 'update', 'title' => ''))
            );
        }

        $data['table'] = $this->table->generate();


        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function status($val){ if ($val == 0){ return 'N'; } else { return 'Y'; } }

    function remove($val=null)
    {
       if ($val == '12011989')
       {
           $aps = $this->Component_model->get_truncate()->result();
           foreach ($aps as $ap)
           {  if ($ap->table == 'user'){ $this->Component_model->remove_admin(); } else { $this->Component_model->remove($ap->table); } }

           $this->Component_model->status(0);
           $this->session->sess_destroy();
           redirect('settings');
       }
       else { $this->session->set_flashdata('message', "Wrong Pin..!!"); redirect($this->title); }
    }

    function backup($val=null)
    {
        $this->load->library('purchase');
        $this->load->library('sales');
        $this->load->library('csales');
        $this->load->library('ap');
        $this->load->library('sales_adjustment');
        $this->load->library('ar_adjustment');
        $this->load->library('purchase_return');
        $this->load->library('ar_payment');
        $this->load->library('car_payment');
        $this->load->library('admin');

        $this->load->library('ar_refund');
        $this->load->library('assembly');

        $this->load->library('temporary_stock_transaction');
        
        if ($val == '12011989')
        {
            // purchase
            $this->Closing_model->insert_into('purchase','purchase_backup');
            $this->purchase->closing();

            // purchase_return
            $this->Closing_model->insert_into('purchase_return','purchase_return_backup');
            $this->purchase_return->closing();

             // sales
            $this->Closing_model->insert_into('sales','sales_backup');
            $this->sales->closing();

            // csales
            $this->Closing_model->insert_into('csales','csales_backup');
            $this->csales->closing();

             // ap
            $this->Closing_model->insert_into('ap','ap_backup');
            $this->ap->closing();

            // sales_adjustment
            $this->Closing_model->insert_into_single('sales_adjustment','sales_adjustment_backup');
            $this->sales_adjustment->closing();

            // ar_adjustment
            $this->ar_adjustment->closing();


             // ar_payment
            $this->Closing_model->insert_into_single('ar_payment','ar_payment_backup');
            $this->ar_payment->closing();

             // car_payment
            $this->Closing_model->insert_into_single('car_payment','car_payment_backup');
            $this->car_payment->closing();

             // ar_refund
            $this->Closing_model->insert_into_single('ar_refund','ar_refund_backup');
            $this->ar_refund->closing();

            // temporary_stock_transaction
            $this->temporary_stock_transaction->closing();

            // assemblying
            $this->Closing_model->insert_into_single('assembly','assembly_backup');
            $this->assembly->closing();


            // add to closing table
            $clos = array('notes' => 'Closing : '.tgleng(date('Y-m-d')).' - '.waktuindo(),
                        'dates' => date('Y-m-d'), 'times' => waktuindo(),
                        'user' => $this->admin->get_userid($this->session->userdata('username')),
                        'log' => $this->session->userdata('log'));

            $this->Closing_model->add($clos);

            $this->session->set_flashdata('message', "Backup Successfull..!!"); redirect($this->title);
        }
        else { $this->session->set_flashdata('message', "Wrong Pin..!!"); redirect($this->title); }
    }

}

?>