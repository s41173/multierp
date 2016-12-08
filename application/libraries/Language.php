<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name, code');
        $val = $this->ci->db->get('language')->result();
        foreach($val as $row){$data['options'][$row->code] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name, code');
        $val = $this->ci->db->get('language')->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->code] = $row->name;}
        return $data;
    }


}

/* End of file Property.php */