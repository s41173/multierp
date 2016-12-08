<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ar_refund {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'ar_refund';
    }

    private $ci,$table;


    //    ======================= relation cek  =====================================

    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $this->ci->db->where('code', 'SO');
       $query = $this->ci->db->get('ar_payment_trans')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get($this->table)->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($po)
    {
       $this->ci->db->where($this->table, $po);
       $this->ci->db->delete('ar_refund_trans');

       $this->ci->db->where('no', $po);
       $this->ci->db->delete($this->table);
    }


}

/* End of file Property.php */