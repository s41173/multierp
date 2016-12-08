<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Regcost_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'reg_cost';
    }

    private $ci,$table;
    
    function get()
    {
        $this->ci->db->select('id, name');
        return $this->ci->db->get($this->table)->result();
    }
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function get_by_criteria($dept,$level=null)
    {
//        $this->ci->db->select('id, name, p1');
        $this->ci->db->where('dept_id', $dept);
        $this->cek_null($level, 'grade');
        $this->ci->db->order_by('grade','asc');
        $res = $this->ci->db->get($this->table)->result();
        if($res){ return $res; }
    }
    
    function combo()
    {
        $data = null;
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        if ($data){ return $data; }
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function combo_criteria($dept,$level=null)
    {
        $this->ci->db->select('id, name');
        $this->ci->db->where('dept_id', $dept);
        $this->cek_null($level, 'grade');
        $val = $this->ci->db->get($this->table)->result();
        $data = null; 
        if ($val){ foreach($val as $row){$data['options'][$row->id] = $row->name;} }
        else {$data['options'][''] = '-- No Data --'; }
        return $data;
    }
    
    private function cek_null($val,$field)
    { if (isset($val)){ return $this->ci->db->where($field, $val); } }
    
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
    
    function get_aid($id)
    {
        if ($id)
        {
           $this->ci->db->select('aid');
           $this->ci->db->from($this->table);
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get()->row();
           if($res){ return $res->aid; }
        }
    }
    
    function get_default($dept,$level)
    {
//        $this->ci->db->select('id, name, p1');
        $this->ci->db->where('dept_id', $dept);
        $this->ci->db->where('grade', $level);
        $this->ci->db->where('default', 1);
        $res = $this->ci->db->get($this->table)->row();
        if($res){ return $res; }
    }
    
    function get_by_id($id)
    {
//        $this->ci->db->select('id, name, p1');
        $this->ci->db->where('id', $id);
        $res = $this->ci->db->get($this->table)->row();
        if($res){ return $res; }
    }
    
    function get_by_student($sid)
    {
        $year = new Financial_lib();
        $year = $year->get();
        $sc = new Scholarship_trans_lib();
        $over = new Tuition_over_lib();
        $dept = new Dept_lib();
        $grade = new Grade_lib();
        $st = new Student_lib();
        $res = null;

       if ($sc->valid_trans($sid,$year) == FALSE){ $res = $sc->get_fee($sc->get_scholarship_id($sid,$year));}
       elseif ($over->cek_student_active($sid) == FALSE){ $res = $over->get_fee($sid);}
       else{ $res = $grade->get_fee($st->get_grade($sid)); }
       return $res; 
    }
    
}


/* End of file Property.php */