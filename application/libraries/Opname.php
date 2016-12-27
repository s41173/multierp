<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opname {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'opname';


//    ============================  remove transaction journal ==============================

    function cek_begindate()
    {
        $this->ci->db->where('begin_dates', NULL);
        $query = $this->ci->db->get($this->table)->num_rows();
        if ($query > 0){ return TRUE;} else { return FALSE;}
    }

}

/* End of file Property.php */