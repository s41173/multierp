<?php

class Experience_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'experience_bonus';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($employee_id=null)
    {
        $this->db->select('id, employee_id, time_work, amount');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($employee_id, 'employee_id');
        return $this->db->get(); 
    }
    
    function report()
    {
        $this->db->select('experience_bonus.id, experience_bonus.employee_id, experience_bonus.time_work, experience_bonus.amount');
        $this->db->from('experience_bonus, employee');
        $this->db->where('employee.id = experience_bonus.employee_id');
        return $this->db->get(); 
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("experience.date BETWEEN '".$start."' AND '".$end."'"); }
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
}

?>