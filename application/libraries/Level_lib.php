<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Level_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'level';
    }

    private $ci,$table;
    
    
    function combo()
    {
        $this->ci->db->select('id, no, name');
        $this->ci->db->order_by('no','asc');
        $val = $this->ci->db->get($this->table)->result();
        if ($val){ foreach($val as $row){$data['options'][$row->no] = $row->name;}} 
        else{ $data['options'][''] = '--'; }
        return $data; 
    }

    function combo_all()
    {
        $this->ci->db->select('id, no, name');
        $this->ci->db->order_by('no','asc');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '--';
        if ($val){ foreach($val as $row){$data['options'][$row->no] = $row->name;}} 
        return $data; 
    }
     
    function get_name($id)
    {
        if ($id)
        {
          $this->ci->db->select('name');
          $this->ci->db->from($this->table);
          $this->ci->db->where('id', $id);
          $res = $this->ci->db->get()->row();
          if ($res){ return $res->name; }
        }
    }
    
    function get_id($name)
    {
        if ($name)
        {
          $this->ci->db->select('id');
          $this->ci->db->from($this->table);
          $this->ci->db->where('name', $name);
          $res = $this->ci->db->get()->row();
          return $res->id;   
        }
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }
    
}


/* End of file Property.php */