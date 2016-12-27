<?php

class Grade_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'grade';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get($limit, $offset)
    {
        $this->db->select('grade_id, dept_id, faculty_id, name, practice, level, fee, instructor, capacity, active, desc');
        $this->db->from($this->table);
        $this->db->order_by('name', 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get();
    }
    
    function search($dept=null, $level=null, $faculty=null, $fee=null)
    {
        $this->db->select('grade_id, dept_id, faculty_id, name, practice, level, fee, instructor, capacity, active, desc');
        $this->db->from($this->table);
        $this->cek_null($dept,"dept_id");
        $this->cek_null($level,"level");
        $this->cek_null($faculty,"faculty_id");
        $this->cek_null($fee,"fee");
        return $this->db->get();
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    function delete($uid)
    {
        $this->db->where('grade_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_grade_by_id($uid)
    {
        $this->db->select('grade_id, dept_id, faculty_id, name, practice, level, fee, instructor, capacity, active, desc');
        $this->db->where('grade_id', $uid);
        return $this->db->get($this->table);
    }

    function get_grade()
    {
        $this->db->select('grade_id, dept_id, faculty_id, name, practice, level, fee, instructor, capacity, active, desc');
        $this->db->order_by('name', 'asc'); // query order
        return $this->db->get($this->table);
    }
    
    function counter()
    {
        $this->db->select_max('userid');
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('grade_id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_grade($uid)
    {
        $this->db->where('name', $uid);
        $query = $this->db->get($this->table)->num_rows();  
        if($query > 0){ return FALSE;}else{return TRUE;}
    }

    function validating_grade($name ,$id)
    {
        $this->db->where('name', $name);
        $this->db->where_not_in('grade_id', $id);
        $query = $this->db->get($this->table)->num_rows();

        if($query > 0){ return FALSE; }else{ return TRUE; }
    }
}

?>