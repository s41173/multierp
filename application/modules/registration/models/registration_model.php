<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registration_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'registration';
    
    function report($dept=null,$start=null,$end=null, $year=null,$stts=1)
    {
        $this->db->from($this->table);
        $this->cek_null($dept, 'dept_id');
        $this->cek_between($start, $end);
        $this->db->where('financial_year', $year);
        $this->db->where('approval', $stts);
        $this->db->order_by('no','asc');
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