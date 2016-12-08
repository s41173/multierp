<?php

class Honor_absence_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'honor_absence';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($employee_id=null,$dept=null,$work=null)
    {
        $this->db->select('id, employee_id, dept, hours, work_time');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($employee_id, 'employee_id');
        $this->cek_null($dept, 'dept');
        $this->cek_null($work, 'work_time');
        return $this->db->get(); 
    }
            
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
}

?>