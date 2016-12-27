<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brand {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get('brand')->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get('brand')->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function get_name($id=null)
    {
        if ($id)
        {
            $this->ci->db->select('id,name');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get('brand')->row();
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
            $num = $this->ci->db->get('brand')->num_rows();
            if ($num > 0){ $val = $this->ci->db->get('brand')->row(); return $val->id; } 
        }
    }


}

/* End of file Property.php */