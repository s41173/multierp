<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classification_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'classifications';

    function combo()
    {
        $this->ci->db->select('id, no, name');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, no, name');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function get_no($name=null)
    {
        $this->ci->db->select('no');
        $this->ci->db->from($this->table);
        $this->ci->db->where('name', $name);
        $res = $this->ci->db->get()->row();
        return $res->code;
    }
	
    function get_type($id=null)
    {
        if ($id)
        {
        $this->ci->db->select('type');
        $this->ci->db->from($this->table);
        $this->ci->db->where('id', $id);
        $res = $this->ci->db->get()->row();
        return $res->type;
        }
    }


}

/* End of file Property.php */