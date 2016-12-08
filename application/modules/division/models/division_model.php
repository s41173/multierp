<?php

class Division_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'division';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($name=null)
    {
        $this->db->select('id, name, role, basic_salary, consumption, transportation, overtime');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($name, 'name');
        return $this->db->get(); 
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
}

?>