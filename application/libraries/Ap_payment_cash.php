<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_payment_cash {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'ap_payment_cash';
    }

    private $ci,$table;


    //    ======================= relation cek  =====================================

    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }


}

/* End of file Property.php */