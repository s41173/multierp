<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tuition_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'tuition_transaction';
    
    
   private function cek_null($val,$field)
   { if ($val == ""){return null;}else {return $this->db->where($field, $val);} }
    
    private function cek_nol($val,$field)
    {
        if ($val == 0){return null;}
        else {return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function search($date,$dept,$ptype,$fee)
    {
        $this->db->select('tuition_trans.id, tuition_trans.tuition, tuition_trans.dates, tuition_trans.student, tuition_trans.amount, tuition_trans.practical, tuition_trans.month, tuition_trans.scholarship, tuition_trans.type, tuition_trans.user, tuition_trans.fee_type, tuition_trans.financial_year');
        $this->db->from('tuition_trans, students');
        $this->db->where('tuition_trans.student = students.students_id');
        
        $this->cek_null($date,"tuition_trans.dates");
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($ptype,"tuition_trans.type");
        $this->cek_null($fee,"tuition_trans.fee_type");
        $this->db->order_by('tuition_trans.id', 'desc');
        return $this->db->get();
    }
    
    function report($start,$end,$dept,$ptype,$fee,$period,$user)
    {
        $this->db->select('tuition_trans.id, tuition_trans.tuition, tuition_trans.dates, tuition_trans.student, 
                           tuition_trans.school_fee, tuition_trans.practical, tuition_trans.computer, tuition_trans.osis,
                           tuition_trans.aid_foundation, tuition_trans.aid_goverment, tuition_trans.cost, tuition_trans.amount,
                           tuition_trans.scholarship, 
                           tuition_trans.month, tuition_trans.type, tuition_trans.user, tuition_trans.fee_type, tuition_trans.financial_year');
        $this->db->from('tuition_trans, students');
        $this->db->where('tuition_trans.student = students.students_id');
        
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($ptype,"tuition_trans.type");
        $this->cek_null($fee,"tuition_trans.fee_type");
        $this->cek_null($period,"tuition_trans.financial_year");
        $this->cek_null($user,"tuition_trans.user");
        $this->cek_between($start, $end);
        $this->db->order_by('tuition_trans.id', 'desc');
        return $this->db->get();
    }
    
    function monthly_report($dept,$grade,$month,$year,$fee=null)
    {
        $this->db->select('tuition_trans.id as id, 
                           tuition_trans.tuition, 
                           tuition_trans.dates, 
                           tuition_trans.student, 
                           tuition_trans.school_fee, 
                           tuition_trans.practical, 
                           tuition_trans.computer,
                           tuition_trans.osis,
                           tuition_trans.aid_foundation, 
                           tuition_trans.aid_goverment,
                           tuition_trans.cost,
                           tuition_trans.amount,
                           tuition_trans.scholarship, 
                           tuition_trans.month, 
                           tuition_trans.type as type, 
                           reg_cost.name as fee, 
                           tuition_trans.financial_year,
                           students.name as name,
                           students.nisn as nisn');
        
        
        $this->db->from('tuition_trans, students, reg_cost');
        $this->db->where('tuition_trans.student = students.students_id');
        $this->db->where('tuition_trans.fee_type = reg_cost.id');
        
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($grade,"students.grade_id");
        $this->cek_null($fee,"tuition_trans.fee_type");
        $this->db->where('MONTH(tuition_trans.dates)', $month);
        $this->db->where('YEAR(tuition_trans.dates)', $year);
        return $this->db->get();
    }
    
    function monthly_report_based_financial($dept,$grade,$month,$year,$financial,$fee=null)
    {
        $this->db->select('tuition_trans.id as id, 
                           tuition_trans.tuition, 
                           tuition_trans.dates, 
                           tuition_trans.student, 
                           tuition_trans.school_fee, 
                           tuition_trans.practical, 
                           tuition_trans.computer,
                           tuition_trans.osis,
                           tuition_trans.aid_foundation, 
                           tuition_trans.aid_goverment,
                           tuition_trans.cost,
                           tuition_trans.amount,
                           tuition_trans.scholarship, 
                           tuition_trans.month, 
                           tuition_trans.type as type, 
                           reg_cost.name as fee, 
                           tuition_trans.financial_year,
                           students.name as name,
                           students.nisn as nisn');
        
        
        $this->db->from('tuition_trans, students, reg_cost');
        $this->db->where('tuition_trans.student = students.students_id');
        $this->db->where('tuition_trans.fee_type = reg_cost.id');
        
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($grade,"students.grade_id");
        $this->cek_null($fee,"tuition_trans.fee_type");
        $this->db->where('MONTH(tuition_trans.dates)', $month);
        $this->db->where('YEAR(tuition_trans.dates)', $year);
        $this->db->where('tuition_trans.financial_year', $financial);
        return $this->db->get();
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("tuition_trans.dates BETWEEN '".$start."' AND '".$end."'"); }
    }
    
//    =========================================  REPORT  =================================================================

}

?>