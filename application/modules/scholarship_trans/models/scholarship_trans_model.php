<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scholarship_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'scholarship_trans';
    
    function search($year=null, $dept=null, $level=null, $sc=null, $st=null, $period=null, $until=null, $start=null, $end=null, $grade=null)
    {
        $this->db->select('scholarship_trans.id, 
                           scholarship_trans.scholarship_id, 
                           scholarship_trans.dates, 
                           scholarship_trans.student, 
                           scholarship_trans.financial_year, 
                           scholarship_trans.start,
                           scholarship_trans.request,
                           scholarship_trans.status,
                           scholarship_trans.period,
                           scholarship_trans.until,
                           scholarship_trans.desc,
                           scholarship_trans.approved,
                           students.dept_id,
                           students.grade_id,
                           scholarship.fee_type,
                           scholarship.currency');
        
        $this->db->from('scholarship_trans, students, scholarship');
        $this->db->where('scholarship_trans.student = students.students_id');
        $this->db->where('scholarship_trans.scholarship_id = scholarship.id');
        
        $this->cek_null($year, 'scholarship_trans.financial_year');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($level, 'scholarship.level');
        $this->cek_null($sc, 'scholarship_trans.scholarship_id');
        $this->cek_null($st, 'scholarship_trans.status');
        $this->cek_null($period, 'scholarship_trans.request');
        $this->cek_null($until, 'scholarship_trans.until');
        
        $this->cek_null($grade, 'students.grade_id');
        $this->cek_between($start, $end);
        $this->db->where('approved', 1);
        $this->db->order_by('dates','asc');
        return $this->db->get(); 
    }
    
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("scholarship_trans.dates BETWEEN '".$start."' AND '".$end."'"); }
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