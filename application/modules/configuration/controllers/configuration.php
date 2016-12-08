<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Configuration_model', '', TRUE);
        $this->load->library('property');
        $this->properti = $this->property->get();
        $this->acl->otentikasi();
        $this->ps = new Period();
        $this->ps = $this->ps->get();
    }

    var $title = 'configuration';
    private $properti,$ps;

    function index()
    {
      $this->display();
    }

    function display()
    {
        $this->acl->otentikasi1($this->title);
        
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords('Global Configuration');
        $data['h2title'] = 'Global Configuration';
        $data['main_view'] = 'configuration_view';
        $data['form_action_add'] = site_url(''.$this->title.'/update_process');
        $data['form_action_period'] = site_url(''.$this->title.'/update_period');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['monthcombo'] = combo_month();

        $property = $this->Configuration_model->get_last_propery()->row();

        $phone1 = explode("-", $property->phone1);
        $phone2 = explode("-", $property->phone2);

        $data['default']['name'] = $property->name;
        $data['default']['address'] = $property->address;
        $data['default']['npwp'] = $property->npwp;
        $data['default']['cp'] = $property->cp;
        $data['default']['area1'] = $phone1[0];
        $data['default']['phone1'] = $phone1[1];
        $data['default']['area2'] = $phone2[0];
        $data['default']['phone2'] = $phone2[1];

        $data['default']['mail'] = $property->email;
        $data['default']['billingmail'] = $property->billing_email;
        $data['default']['techmail'] = $property->technical_email;
        $data['default']['ccmail'] = $property->cc_email;

	$data['default']['zip'] = $property->zip;
	$data['default']['account_name'] = $property->account_name;
        $data['default']['account_no'] = $property->account_no;
        $data['default']['bank'] = $property->bank;
        $data['default']['city'] = $property->city;

        $data['default']['sitename'] = $property->site_name;
        $data['default']['metadesc'] = $property->meta_description;
        $data['default']['metakey'] = $property->meta_keyword;
        $data['default']['image'] = base_url().'images/property/'.$property->logo;
        
        // accounting period
        
        $data['default']['monthperiod'] = $this->ps->month;
        $data['default']['yearperiod']  = $this->ps->year;
        $data['default']['yearend']     = $this->ps->closing_month;
        $data['default']['beginmonth']  = $this->ps->start_month;
        $data['default']['beginyear']   = $this->ps->start_year;
        
        // management staff
        $data['default']['accounting'] = $property->accounting;
        $data['default']['manager']    = $property->manager;

        $this->session->set_userdata('prid', $property->id);
        $this->load->view('template', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);
        
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords('Global Configuration');
        $data['h2title'] = 'Global Configuration';
        $data['main_view'] = 'configuration_view';
        $data['form_action_add'] = site_url(''.$this->title.'/update_process');
        $data['link'] = array('link_back' => anchor(''.$this->title.'/','<span>back</span>', array('class' => 'back')));
        $data['monthcombo'] = combo_month();

        $this->form_validation->set_rules('tname', 'Property', 'required|max_length[100]');
        $this->form_validation->set_rules('taddress', 'Address', 'required');
	$this->form_validation->set_rules('tphone1', 'Phone1', 'required|max_length[15]');
        $this->form_validation->set_rules('tphone2', 'Phone2', 'required|max_length[15]');
        $this->form_validation->set_rules('tmail', 'Property Mail', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('tbillmail', 'Billing Email', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('ttechmail', 'Technical Email', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('tccmail', 'CC Email', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('tarea1', 'Area Code', 'required|max_length[15]');
        $this->form_validation->set_rules('tarea2', 'Area Code', 'required|max_length[15]');
	$this->form_validation->set_rules('tcity', 'City', 'required|max_length[25]');
        $this->form_validation->set_rules('tzip', 'Zip Code', 'required|numeric|max_length[25]');

        $this->form_validation->set_rules('taccount_name', 'Account Name', 'required|max_length[100]');
        $this->form_validation->set_rules('taccount_no', 'Account No', 'required|max_length[100]');
        $this->form_validation->set_rules('tbank', 'Bank Name', 'required');

        $this->form_validation->set_rules('tsitename', 'Site Name', 'required');
        $this->form_validation->set_rules('tmetadesc', 'Global Meta Description', '');
        $this->form_validation->set_rules('tmetakey', 'Global Meta Keyword', '');
        
        $this->form_validation->set_rules('tmanager', 'Manager', '');
        $this->form_validation->set_rules('taccounting', 'Accounting', '');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $phone1 = $this->input->post('tarea1')."-".$this->input->post('tphone1');
            $phone2 = $this->input->post('tarea2')."-".$this->input->post('tphone2');
            
            $config['upload_path']   = './images/property/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite']     = TRUE;
            $config['max_size']	 = '150';
            $config['max_width']     = '1000';
            $config['max_height']    = '1000';
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload', $config);
            
            if ($_FILES['userfile'])
            {
                if ( !$this->upload->do_upload("userfile"))
                {
                    $data['error'] = $this->upload->display_errors();
                    $property = array('name' => $this->input->post('tname'), 'address' => $this->input->post('taddress'),
                                  'phone1' => $phone1, 'phone2' => $phone2, 'npwp' => $this->input->post('tnpwp'), 'cp' => $this->input->post('tcp'),
                                  'cc_email' => $this->input->post('tccmail'), 'email' => $this->input->post('tmail'),'billing_email' => $this->input->post('tbillmail'), 'technical_email' => $this->input->post('ttechmail'),
                                  'zip' => $this->input->post('tzip'),'city' => strtoupper($this->input->post('tcity')), 'bank' => $this->input->post('tbank'),
                                  'account_name' => $this->input->post('taccount_name'), 'account_no' => $this->input->post('taccount_no'),
                                  'site_name' => $this->input->post('tsitename'), 'meta_description' => $this->input->post('tmetadesc'), 'meta_keyword' => $this->input->post('tmetakey'),
                                  'manager' => $this->input->post('tmanager'), 'accounting' => $this->input->post('taccounting')
                                  );

                    $this->Configuration_model->update($this->session->userdata('prid'), $property);
                    $this->session->set_flashdata('message', "One $this->title has successfully updated!");
                    redirect($this->title);

                }
                else
                {
                    $info = $this->upload->data();
                    
                    $property = array('name' => $this->input->post('tname'), 'address' => $this->input->post('taddress'),
                                  'phone1' => $phone1, 'phone2' => $phone2, 'npwp' => $this->input->post('tnpwp'), 'cp' => $this->input->post('tcp'),
                                  'cc_email' => $this->input->post('tccmail'), 'email' => $this->input->post('tmail'),'billing_email' => $this->input->post('tbillmail'), 'technical_email' => $this->input->post('ttechmail'),
                                  'zip' => $this->input->post('tzip'),'city' => strtoupper($this->input->post('tcity')), 'bank' => $this->input->post('tbank'),
                                  'account_name' => $this->input->post('taccount_name'), 'account_no' => $this->input->post('taccount_no'),
                                  'site_name' => $this->input->post('tsitename'), 'meta_description' => $this->input->post('tmetadesc'), 'meta_keyword' => $this->input->post('tmetakey'), 'logo' => $info['file_name']);

                    $this->Configuration_model->update($this->session->userdata('prid'), $property);
                    $this->session->set_flashdata('message', "One $this->title has successfully updated!");
                    redirect($this->title);
                }

            } 
        }
        else
        {
            $this->load->view('template', $data);
        }
    }
    
    function update_period()
    {
        $this->form_validation->set_rules('cbeginmonth', 'Begin Month', 'required|numeric|callback_valid_starting_period');
        $this->form_validation->set_rules('tbeginyear', 'Begin Year', 'required|numeric|max_length[4]|callback_valid_starting_period');
        
        $this->form_validation->set_rules('cmonthperiod', 'Month Period', 'required');
//        $this->form_validation->set_rules('tyearperiod', 'Year Period', 'required|numeric|max_length[4]|callback_valid_closing_period');
        $this->form_validation->set_rules('tyearperiod', 'Year Period', 'required|numeric|max_length[4]');
        $this->form_validation->set_rules('cyearend', 'Year-End', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            if ($this->ps->status == 1)
            {
                $monthperiod = $this->input->post('cmonthperiod');
                $yearperiod = $this->input->post('tyearperiod');
                $yearend = $this->ps->closing_month;
                $startmonth = $this->ps->start_month;
                $startyear = $this->ps->start_year;
            }
            elseif($this->ps->status == 0) 
            {
               $monthperiod = $this->input->post('cmonthperiod');
               $yearperiod = $this->input->post('tyearperiod');
               $yearend = $this->input->post('cyearend');
               $startmonth = $this->input->post('cbeginmonth');
               $startyear = $this->input->post('tbeginyear');
            }

            $ps = new Period();
            $ps->where('id', 1)->get();
            $ps->month = $monthperiod;
            $ps->year = $yearperiod;
            $ps->closing_month = $yearend;
            $ps->start_month = $startmonth;
            $ps->start_year = $startyear;
            $ps->save();
            
            echo 'true';
        }
        else { echo validation_errors(); }
    }
    
    public function valid_closing_period($val=null)
    {
        $end = $this->input->post('cyearend');
        $year = $this->input->post('tyearperiod'); 
        
        if ($this->ps->status == 1)
        {
            if ($end != $this->ps->closing_month || $year != $this->ps->year)
            {
                $this->form_validation->set_message('valid_closing_period', "Year Period & Year-End Cannot Changed..!");
                return FALSE;
            }
            else { return TRUE; }
        }
        else { return TRUE; }
    }
    
    public function valid_starting_period($val=null)
    {
        $smonth = $this->input->post('cbeginmonth');
        $emonth = $this->input->post('cmonthperiod');
        $syear = $this->input->post('tbeginyear');
        $eyear = $this->input->post('tyearperiod');
        
        if ($syear > $eyear)
        {
           $this->form_validation->set_message('valid_starting_period', "Invalid Begin Year..!!");
           return FALSE;
        }
        else
        {
            if ($syear == $eyear) { if ($smonth > $emonth){ $this->form_validation->set_message('valid_starting_period', "Invalid Begin Month..!!"); return FALSE; }else { return TRUE; }}
            else { return TRUE; }
        }
    }

}

?>