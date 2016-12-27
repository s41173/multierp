<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tuition_over_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'tuition_over';
        $this->table2 = 'tuition_over_trans';
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
    
    function get_name($id)
    {
        if ($id)
        {
          $this->ci->db->select('name');
          $this->ci->db->from($this->table);
          $this->ci->db->where('id', $id);
          $res = $this->ci->db->get()->row();
          return $res->name;   
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
    
// ----------------------------  tuition over trans ----------------------------
    
    function cek_active($val)
    {
       $this->ci->db->select('tuition_over_id'); 
       $this->ci->db->from($this->table2);
       $this->ci->db->where('tuition_over_id', $val);
       $this->ci->db->where('status', 1);
       $res = $this->ci->db->get()->num_rows();
       if ($res > 0){ return FALSE; }else{ return TRUE; }
    }
    
    function cek_student_active($val)
    {
       $this->ci->db->from($this->table2);
       $this->ci->db->where('student', $val);
       $this->ci->db->where('status', 1);
       $res = $this->ci->db->get()->num_rows();
       if ($res > 0){ return FALSE; }else{ return TRUE; }
    }
    
    function get_fee($val)
    {
        if ($val)
        {
          $this->ci->db->select('fee');
          $this->ci->db->from($this->table2);
          $this->ci->db->where('student', $val);
          $res = $this->ci->db->get()->row();
          return $res->fee;   
        }
    }
    
}


/* End of file Property.php */