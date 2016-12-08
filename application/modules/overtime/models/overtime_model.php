<?php

class Overtime_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'overtime';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($division=null)
    {
        $this->db->select('id, division_id, amount');
        $this->db->from($this->table);
        $this->cek_null($division, 'division_id');
        return $this->db->get();
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
}

?>