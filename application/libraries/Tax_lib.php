<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tax_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name, code, value');
        $val = $this->ci->db->get('tax')->result();
        foreach($val as $row){$data['options'][$row->value] = $row->code;}
        return $data;
    }

    function calculate($tax,$qty,$amount)
    {
        $tot = $qty*$amount;
        return floor($tax * $tot);
    }

    function calculate_tax($amount,$tax)
    {
       return $amount * $tax;
    }



}

/* End of file Property.php */