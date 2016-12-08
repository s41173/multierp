<?php

class Journal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'gls';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($cur=null,$type=null,$start,$end)
    {
        $this->db->select('id, no, dates, code, currency, docno, notes, balance, log, approved');
        $this->db->from($this->table);
        $this->db->where('currency', $cur);
        $this->cek_null($type, 'code');
        $this->cek_between($start, $end);
        $this->db->where('approved', 1);
        $this->db->order_by('dates','asc');
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