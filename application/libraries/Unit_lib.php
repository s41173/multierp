<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unit_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name, code');
        $val = $this->ci->db->get('units')->result();
        foreach($val as $row){$data['options'][$row->code] = $row->code;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name, code');
        $val = $this->ci->db->get('units')->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->code] = $row->name;}
        return $data;
    }

    function get_code($name=null)
    {
        $this->ci->db->select('code');
        $this->ci->db->from('units');
        $this->ci->db->where('name', $name);
        $res = $this->ci->db->get()->row();
        return $res->code;
    }


}

/* End of file Property.php */