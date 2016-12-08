<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mutation_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'mutation_type';
    }

    private $ci,$table;
    
    function get()
    {
        $this->ci->db->select('id, code, name');
        return $this->ci->db->get($this->table)->result();
    }
    
    function combo()
    {
        $this->ci->db->select('id, code, name');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->code.' | '.$row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, code, name');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->code.' | '.$row->name;}
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
    
    function get_code($id)
    {
        if ($id)
        {
           $this->ci->db->select('code');
           $this->ci->db->from($this->table);
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get()->row();
           if ($res){ return $res->code; }
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
           if($res){ return $res->id; }
        }
    }
    
    // ========================================================================
    
    function get_acc($dept,$type)
    {
       $this->ci->db->select('account');
       $this->ci->db->from('mutation_config');
       $this->ci->db->where('dept_id', $dept);
       $this->ci->db->where('type', $type);
       $res = $this->ci->db->get()->row();
       if($res){ return $res->account; } 
    }
    
    function cek_acc($dept,$type)
    {
       $this->ci->db->select('account');
       $this->ci->db->from('mutation_config');
       $this->ci->db->where('dept_id', $dept);
       $this->ci->db->where('type', $type);
       $res = $this->ci->db->get()->num_rows();
       if($res > 0){ return TRUE; }else { return FALSE; }
    }
    
}


/* End of file Property.php */