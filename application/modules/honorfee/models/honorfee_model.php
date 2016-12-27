<?php

class Honorfee_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'honor_fee';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($type=null,$dept=null)
    {
        $this->db->select('id, work_time, dept, amount');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($type, 'work_time');
        $this->cek_null($dept, 'dept');
        $this->db->order_by('dept','desc');
        return $this->db->get(); 
    }
            
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
}

?>