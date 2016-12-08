<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Property {

    public function __construct($params=null)
    {
        // Do something with $params
        $this->ci = & get_instance();
    }

    private $table = 'property';
    private $ci;

//    private $id, $name, $address, $phone1, $phone2, $fax, $email, $billing_email, $technical_email, $cc_email,
//            $zip, $city, $account_name, $account_no, $bank, $site_name, $logo, $meta_description, $meta_keyword;


    public function get()
    {
//        $this->db->select('id,name,address,phone1,phone2,email,billing_email,technical_email, cc_email, zip,account_name,account_no,bank,city,site_name,meta_description,meta_keyword');
        $val = null;
        $num = $this->ci->db->get($this->table)->num_rows();

        if ($num > 0)
        {
            $res = $this->ci->db->get($this->table)->row();
            $val = array('name' => $res->name, 'address' => $res->address, 'npwp' => $res->npwp, 'cp' => $res->cp, 'phone1' => $res->phone1, 'phone2' => $res->phone2, 'fax' => $res->fax,
                     'email' => $res->email, 'billing_email' => $res->billing_email, 'technical_email' => $res->technical_email, 'cc_email' => $res->cc_email,
                     'zip' => $res->zip, 'city' => $res->city, 'account' => $res->account_name, 'acc_no' => $res->account_no, 'bank' => $res->bank, 'manager' => $res->manager, 'accounting' => $res->accounting,
                     'sitename' => $res->site_name, 'logo' => base_url("images/property/").'/'.$res->logo, 'meta_desc' => $res->meta_description, 'meta_key' => $res->meta_keyword
                    );
        }

        
        return $val;
    }
}

/* End of file Property.php */