<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mutation_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'mutation';
    
    function report($cur=null,$start=null, $end=null, $type=null, $dept=null, $grade=null,$acc=null, $st=null)
    {
        $this->db->from($this->table);
        $this->cek_null($cur, 'currency');
        $this->cek_null($type, 'type');
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($grade, 'grade_id');
        $this->cek_null($acc, 'acc');
        $this->cek_null($st, 'settled');
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
    
    private function cek_cat($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

}

?>