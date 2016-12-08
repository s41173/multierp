<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nsales_adjustment {

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
        $query = $this->ci->db->get('nsales_adjustment')->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($po)
    {
       $this->ci->db->where('no', $po);
       $this->ci->db->delete('nsales_adjustment');
    }

}

/* End of file Property.php */