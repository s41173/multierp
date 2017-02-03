<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journaltype_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
	private $table = 'gls';

    function combo()
    {
        $this->ci->db->select('code');
        $this->ci->db->order_by('code', 'asc'); 
        $this->ci->db->distinct();
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->code] = $row->code;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('code');
        $this->ci->db->order_by('code', 'asc'); 
        $this->ci->db->distinct();
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->code] = $row->code;}
        return $data;
    }

    function get_code($name=null)
    {
        $this->ci->db->select('code');
        $this->ci->db->from($this->table);
        $this->ci->db->where('name', $name);
        $res = $this->ci->db->get()->row();
        return $res->code;
    }


}

/* End of file Property.php */