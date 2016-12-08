<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'card';
    }

    private $ci,$table;
    

    function get($dept)
    {
        $this->ci->db->select('name');
        $this->ci->db->from($this->table);
        $this->ci->db->where('dept_id', $dept);
        $res = $this->ci->db->get()->row();
        return $res->name;
    }

    
}


/* End of file Property.php */