<?php

class Employee_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'employee';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($dept=0, $division=0, $role=null, $name=null)
    {
//        $this->db->select('id, nip, name, division_id, dept_id, born_place, born_date, phone, active');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($name, 'name');
        $this->cek_null($role, 'role');
        $this->cek_null($division, 'division_id');
        return $this->db->get(); 
    }
    
    function getlist($nip=null, $type=null)
    {
//        $this->db->select('id, nip, name, division_id, dept_id, born_place, born_date, phone, active');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($nip, 'nip');
        $this->cek_null($type, 'type');
        return $this->db->get(); 
    }
    
    function report($dept=null,$division=null, $role=null, $status=null)
    {
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($division, 'division_id');
        $this->cek_null($role, 'role');
        $this->cek_null($status, 'active');
        return $this->db->get(); 
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    private function cek_nol($val,$field)
    {
        if ($val == ''){return $this->db->where($field, 0);}
    }

//    -------------------------------------------------------------------------------------

    function cek_dep($val)
    {
        if ($val != "all"){$val = $this->db->where('students.dept_id', $val);}
        else{ $val = null;}
        return $val;
    }
    
}

?>