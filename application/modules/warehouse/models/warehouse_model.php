<?php

class Warehouse_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'warehouse';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($name=null)
    {
        $this->db->select('id, name, desc');
        $this->db->from($this->table);
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