<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name, desc, rules');
        $val = $this->ci->db->get('role')->result();
        foreach($val as $row){$data['options'][$row->name] = $row->name;}
        return $data;
    }


}

/* End of file Property.php */