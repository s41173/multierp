<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Iroom_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'inventaris_room';
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
        $this->ci->db->select('id, name, desc');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->name] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name, desc');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->name] = $row->name;}
        return $data;
    }
   

}

/* End of file Property.php */