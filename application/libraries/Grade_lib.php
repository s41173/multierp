<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'grade';
    }

    private $ci,$table;
    
    
    function combo()
    {
        $this->ci->db->select('grade_id, name');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->grade_id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('grade_id, name');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->grade_id] = $row->name;}
        return $data;
    }
    
    function get_name($id)
    {
        if ($id)
        {
          $this->ci->db->select('name');
          $this->ci->db->from($this->table);
          $this->ci->db->where('grade_id', $id);
          $res = $this->ci->db->get()->row();
          if ($res){ return $res->name; }
        }
    }
    
    function get_id($name)
    {
        if ($name)
        {
          $this->ci->db->select('grade_id');
          $this->ci->db->from($this->table);
          $this->ci->db->where('name', $name);
          $res = $this->ci->db->get()->row();
          return $res->grade_id;   
        }
    }
    
    function get_level($id)
    {
        if ($id)
        {
          $this->ci->db->select('level');
          $this->ci->db->from($this->table);
          $this->ci->db->where('grade_id', $id);
          $res = $this->ci->db->get()->row();
          return $res->level;   
        }
    }
    
    function get_instructor($id)
    {
        if ($id)
        {
          $this->ci->db->select('instructor');
          $this->ci->db->from($this->table);
          $this->ci->db->where('grade_id', $id);
          $res = $this->ci->db->get()->row();
          return $res->instructor;   
        }
    }
    
    function get_fee($id)
    {
        if ($id)
        {
          $this->ci->db->select('fee');
          $this->ci->db->from($this->table);
          $this->ci->db->where('grade_id', $id);
          $res = $this->ci->db->get()->row();
          return $res->fee;
        }
    }
    
    function get_practice_status($id)
    {
        if ($id)
        {
          $this->ci->db->select('practice');
          $this->ci->db->from($this->table);
          $this->ci->db->where('grade_id', $id);
          $res = $this->ci->db->get()->row();
          return $res->practice;   
        }
    }
    
    function get($id=null)
    {
        $this->ci->db->select('grade_id, dept_id, name');
//        $this->ci->db->where('dept_id', $id);
        $this->cek_null($id,'dept_id');
        $this->ci->db->order_by('grade_id', 'asc');
        $this->ci->db->where('active', '1');
        return $this->ci->db->get($this->table)->result();
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }
    
}


/* End of file Property.php */