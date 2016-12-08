<?php

class Attendance_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'attendance';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($employee_id=null,$month=null,$year=null)
    {
        $this->db->select('id, employee_id, month, year, presence, late, overtime, log');
        $this->db->from($this->table);
        $this->cek_null($employee_id, 'employee_id');
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