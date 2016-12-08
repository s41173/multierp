<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get('category')->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function get_name($id=null)
    {
        if ($id)
        {
            $this->ci->db->select('id,name');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get('category')->row();
            return $val->name;
        }
        else { return ''; }
    }
    
    function get_id($name=null)
    {
        if ($name)
        {
            $this->ci->db->select('id,name');
            $this->ci->db->where('name', $name);
            $num = $this->ci->db->get('category')->num_rows();
            if ($num > 0){ $val = $this->ci->db->get('category')->row(); return $val->id; } 
        }
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get('category')->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }


}

/* End of file Property.php */