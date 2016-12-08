<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nar_payment {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'nar_payment';
    }

    private $ci,$table;


    //    ======================= relation cek  =====================================

    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('nar_payment')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get('nar_payment')->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($po)
    {
       $this->ci->db->where('nar_payment', $po);
       $this->ci->db->delete('nar_payment_trans');

       $this->ci->db->where('no', $po);
       $this->ci->db->delete('nar_payment');
    }


}

/* End of file Property.php */