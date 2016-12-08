<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_adjustment {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;


    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get('sales_adjustment')->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($po)
    {
       $this->ci->db->where('no', $po);
       $this->ci->db->delete('sales_adjustment');
    }

}

/* End of file Property.php */