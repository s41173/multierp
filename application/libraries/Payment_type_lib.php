<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_type_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'payment_type';
    }

    private $ci,$table;
    
    function get()
    {
        $this->ci->db->select('dept_id, name');
        return $this->ci->db->get($this->table)->result();
    }
    
    function combo()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name','asc');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->name] = $row->name;}
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