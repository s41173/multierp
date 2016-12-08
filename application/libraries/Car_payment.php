<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Car_payment {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'car_payment';
        $this->table2 = 'car_payment_trans';
    }

    private $ci,$table,$table2;


    //    ======================= relation cek  =====================================

    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('car_payment')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function cek_relation_trans($id,$type,$code='CSO')
    {
       $this->ci->db->where($type, $id);
       $this->ci->db->where('code', $code);
       $query = $this->ci->db->get($this->table2)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get('car_payment')->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($po)
    {
       $this->ci->db->where('ar_payment', $po);
       $this->ci->db->delete('car_payment_trans');

       $this->ci->db->where('no', $po);
       $this->ci->db->delete('car_payment');
    }


}

/* End of file Property.php */