<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dept_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'dept';
    }

    private $ci,$table;
    
    function get()
    {
        $this->ci->db->select('dept_id, name');
        return $this->ci->db->get($this->table)->result();
    }
    
    function combo()
    {
        $this->ci->db->select('dept_id, name');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->dept_id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('dept_id, name');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->dept_id] = $row->name;}
        return $data;
    }
    
    function get_name($id)
    {
        if ($id)
        {
           $this->ci->db->select('name');
           $this->ci->db->from($this->table);
           $this->ci->db->where('dept_id', $id);
           $res = $this->ci->db->get()->row();
           if ($res){ return $res->name; }
        }
    }
    
    function get_id($name)
    {
        if ($name)
        {
           $this->ci->db->select('dept_id');
           $this->ci->db->from($this->table);
           $this->ci->db->where('name', $name);
           $res = $this->ci->db->get()->row();
           if($res){ return $res->dept_id; }
        }
    }
    
}


/* End of file Property.php */