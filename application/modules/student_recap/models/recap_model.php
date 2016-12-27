<?php

class Recap_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'student_recap';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($dept=null,$month=null,$year=null)
    {
        $this->db->select('id, month, year, qty, dept_id, grade_id');
        $this->db->from($this->table);
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($month, 'month');
        $this->cek_null($year, 'year');
        return $this->db->get(); 
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
}

?>