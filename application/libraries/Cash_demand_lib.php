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
        $uid = $this->get_id_by_no($no);
        $this->ci->db->from($this->table);
        $this->ci->db->where('cash_demand_id', $uid);
        return $this->ci->db->get()->result();
    }
    
    private function get_id_by_no($no){
        
        $this->ci->db->select('id');
        $this->ci->db->from('cash_demand');
        $this->ci->db->where('no', $no);
        $res = $this->ci->db->get()->row();
        return $res->id;
    }

}

/* End of file Property.php */