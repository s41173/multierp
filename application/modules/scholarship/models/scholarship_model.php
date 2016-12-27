<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scholarship_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'scholarship';
    
    function report($cur=null,$start=null, $end=null, $type=null, $dept=null, $period=null)
    {
        $this->db->from($this->table);
        $this->cek_null($cur, 'currency');
        $this->cek_null($type, 'fee_type');
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($period, 'period');
        $this->cek_between($start, $end);
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