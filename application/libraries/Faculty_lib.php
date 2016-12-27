<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faculty_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'faculty';
    }

    private $ci,$table;
    
    
    function combo()
    {
        $this->ci->db->select('faculty_id, name, code');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->faculty_id] = $row->code;}
        return $data;
    }
    
    function combo_code()
    {
        $this->ci->db->select('faculty_id, name, code');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->code] = $row->code;}
        return $data;
    }
    
    function combo_criteria($dept)
    {
        $this->ci->db->select('faculty_id, name, code');
        $this->ci->db->where('dept_id', $dept);
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->code] = $row->code;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('faculty_id, name, code');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->faculty_id] = $row->code;}
        return $data;
    }
    
    function get_name($id)
    {
        if ($id)
        {
           $this->ci->db->select('name');
           $this->ci->db->from($this->table);
           $this->ci->db->where('faculty_id', $id);
           $res = $this->ci->db->get()->row();
           return $res->name;
        }
    }
    
    function get_code($id)
    {
        if ($id)
        {
           $this->ci->db->select('code');
           $this->ci->db->from($this->table);
           $this->ci->db->where('faculty_id', $id);
           $res = $this->ci->db->get()->row();
           return $res->code;  
        }
    }
    
    function get_id_by_code($code)
    {
        if ($code)
        {
           $this->ci->db->select('faculty_id');
           $this->ci->db->from($this->table);
           $this->ci->db->where('code', $code);
           $res = $this->ci->db->get()->row();
           return $res->faculty_id;  
        }
    }
    
}


/* End of file Property.php */