<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tuition_over_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'tuition_over_trans';
    
    function report($start=null, $end=null, $dept=null, $type=null)
    {
        $this->db->select('tuition_over_trans.id, 
                           tuition_over_trans.tuition_over_id, 
                           tuition_over_trans.dates, 
                           tuition_over_trans.student, 
                           tuition_over_trans.desc,
                           tuition_over_trans.fee,
                           tuition_over_trans.status,
                           students.dept_id,
                           students.grade_id,
                           students.name');
        
        $this->db->from('tuition_over_trans, students, tuition_over');
        $this->db->where('tuition_over_trans.student = students.students_id');
        $this->db->where('tuition_over_trans.tuition_over_id = tuition_over.id');
        
        $this->cek_null($type, 'tuition_over_trans.tuition_over_id');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_between($start, $end);
        $this->db->order_by('dates','desc');
        return $this->db->get(); 
    }
    
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("tuition_over_trans.dates BETWEEN '".$start."' AND '".$end."'"); }
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