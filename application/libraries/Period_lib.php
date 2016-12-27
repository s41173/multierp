<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Period_lib {

    public function __construct()
    {
        // Do something with $params
        $this->ci = & get_instance();
    }

    private $table = 'periods';
    private $ci;

//    private $id, $name, $address, $phone1, $phone2, $fax, $email, $billing_email, $technical_email, $cc_email,
//            $zip, $city, $account_name, $account_no, $bank, $site_name, $logo, $meta_description, $meta_keyword;


    public function get($type=null)
    {
       $this->ci->db->select('id, month, year, closing_month, start_month, start_year, status');
       $val = $this->ci->db->get($this->table)->row();
       if ($type == 'month'){ return $val->month; }
       elseif ($type == 'year') { return $val->year; }
       else { return $val; }
    }
}

/* End of file Property.php */