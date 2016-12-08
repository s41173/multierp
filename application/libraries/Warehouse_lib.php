<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'warehouse';
    }

    private $ci,$table;

    function combo()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function combo_all()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function get_name($id=null)
    {
        if ($id == 0){ return 'Primary'; }
        else 
        { 
          $this->ci->db->select('name');
          $this->ci->db->from($this->table);
          $this->ci->db->where('id', $id);
          $res = $this->ci->db->get()->row();
          return $res->name; 
        }
    }


}

/* End of file Property.php */