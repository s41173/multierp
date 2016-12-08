<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generation_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'generation';
    }

    private $ci,$table;
    
    
    function combo()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name','desc');
        $val = $this->ci->db->get($this->table)->result();
        if ($val){ foreach($val as $row){$data['options'][$row->name] = $row->name;}} 
        else{ $data['options'][''] = '--'; }
        return $data; 
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name','desc');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        if ($val){ foreach($val as $row){$data['options'][$row->name] = $row->name;}} 
        return $data; 
    }
    
    function get_active()
    {
      $this->ci->db->select('name');
      $this->ci->db->from($this->table);
      $this->ci->db->where('active', 1);
      $res = $this->ci->db->get()->row();
      if ($res){ return $res->name; }
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