<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gcategory {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get('gcategory')->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function get_name($id=null)
    {
        if ($id)
        {
            $this->ci->db->select('id,name');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get('gcategory')->row();
            return $val->name;
        }
        else { return ''; }
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get('gcategory')->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }


}

/* End of file Property.php */