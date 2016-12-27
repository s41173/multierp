<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'categories';
    }

    private $ci,$table;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table1)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function combo()
    {
        $data = null;
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        if ($data){ return $data; }
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function get_name($id=null)
    {
        $this->ci->db->select('name');
        $this->ci->db->from($this->table);
        $this->ci->db->where('id', $id);
        $res = $this->ci->db->get()->row();
        return $res->name;
    }


}

/* End of file Property.php */