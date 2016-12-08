<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assembly {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'assembly';
    }

    private $ci,$table;


    //    ======================= relation cek  =====================================

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get($this->table)->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }


}

/* End of file Property.php */