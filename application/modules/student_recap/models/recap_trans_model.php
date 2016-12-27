<?php

class Recap_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'student_recap_trans';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get($limit,$offset)
    {
        $this->db->select('id, student_id, dept_id, grade_id, dates, type, qty, month, year, transcode, description');
        $this->db->from($this->table);
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($dept=null,$start=null,$end=null,$type=null)
    {
        $this->db->select('id, student_id, dept_id, grade_id, dates, type, qty, month, year, transcode, description');
        $this->db->from($this->table);
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($type, 'type');
        $this->cek_between($start, $end);
        return $this->db->get(); 
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("dates BETWEEN '".$start."' AND '".$end."'"); }
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
}

?>