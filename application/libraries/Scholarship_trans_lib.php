<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scholarship_trans_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'scholarship_trans';
        $this->table2 = 'scholarship';
    }

    private $ci,$table;
    
    function get()
    {
        $this->ci->db->select('id, name');
        return $this->ci->db->get($this->table)->result();
    }
    
    // cek student in scholarship active or not
    function valid_trans($student,$year)
    {
        $this->ci->db->where('student', $student);
        $this->ci->db->where('financial_year', $year);
        $this->ci->db->where('status', 1);
        $res = $this->ci->db->get($this->table)->num_rows();
        if ($res>0){ return FALSE; }else { return TRUE; }
    }
    
    // kurangi period by 1
    function min_period($student,$year)
    {
       $this->ci->db->where('student', $student);
       $this->ci->db->where('financial_year', $year);
       $val = $this->ci->db->get($this->table)->row();
       
       $qty = $val->period - 1;
       $res = array('period' => $qty);
       $this->ci->db->where('id', $val->id);
       $this->ci->db->update($this->table, $res);  
       $this->cek_status($student, $year);
    }
    
    // tambah period by 1
    function add_period($student,$year)
    {
       $this->ci->db->where('student', $student);
       $this->ci->db->where('financial_year', $year);
       $val = $this->ci->db->get($this->table)->row();
       
       $qty = $val->period + 1;
       $res = array('period' => $qty);
       $this->ci->db->where('id', $val->id);
       $this->ci->db->update($this->table, $res);  
       $this->cek_status($student, $year);
    }
    
    // update status
    private function cek_status($student,$year)
    {
       $this->ci->db->where('student', $student);
       $this->ci->db->where('financial_year', $year);
       $val = $this->ci->db->get($this->table)->row();
       
       if ($val->period > 0){ $stts = 1; }else { $stts = 0; }
       $res = array('status' => $stts);
       $this->ci->db->where('id', $val->id);
       $this->ci->db->update($this->table, $res);  
    }
    
    function get_by_criteria($dept,$level)
    {
//        $this->ci->db->select('id, name, p1');
        $this->ci->db->where('dept_id', $dept);
        $this->ci->db->where('grade', $level);
        $res = $this->ci->db->get($this->table)->result();
        if($res){ return $res; }
    }
    
    function combo()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table2)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table2)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function get_name($id)
    {
        if ($id)
        {
           $this->ci->db->select('name');
           $this->ci->db->from($this->table2);
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
           if($res){ return $res->id; }
        }
    }
    
    function get_amount($id)
    {
        if ($id)
        {
           $this->ci->db->select('p1');
           $this->ci->db->from($this->table);
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get()->row();
           if($res){ return $res->p1; }
        }
    }
    
    function get_period($id)
    {
        if ($id)
        {
           $this->ci->db->select('period');
           $this->ci->db->from($this->table2);
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get()->row();
           if($res){ return $res->period; }
        }
    }
    
    function get_scholarship_id($student,$year)
    {
        $this->ci->db->select('scholarship_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('student', $student);
        $this->ci->db->where('financial_year', $year);
        $res = $this->ci->db->get()->row();
        if($res){ return $res->scholarship_id; }
    }
    
    function get_fee_type($id)
    {
        $fee = new Regcost_lib();
        
        if ($id)
        {
           $this->ci->db->select('fee_type');
           $this->ci->db->from($this->table2);
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get()->row();
           if($res){ return $fee->get_aid($res->fee_type); }
        }
    }
    
    function get_fee($id)
    {
        if ($id)
        {
           $this->ci->db->select('fee_type');
           $this->ci->db->from($this->table2);
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get()->row();
           if($res){ return $res->fee_type; }
        }
    }
    
}


/* End of file Property.php */