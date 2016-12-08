<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash_demand_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'cash_demand_trans';
    }

    private $ci,$table;

    function get_by_no($no=null)
    {
        $this->ci->db->from($this->table);
        $this->ci->db->where('cash_demand_id', $no);
        return $this->ci->db->get()->result();
    }

}

/* End of file Property.php */