<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Return_stock {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('return_stock')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }


}