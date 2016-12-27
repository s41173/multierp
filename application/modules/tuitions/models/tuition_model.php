<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tuition_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'tuition';
    
    
    private function cek_null($val,$field)
    {
        if (isset($val)){return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function report_tuition($cur='IDR',$start,$end)
    {
      
        $this->db->from($this->table);
        
        $this->db->where('tuition.currency', $cur);
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->order_by('tuition.no', 'asc');
        return $this->db->get();
    }
    
    function report($cur,$dept,$start,$end)
    {
        $this->db->select('tuition.no, tuition.dates, tuition.currency, tuition.notes, tuition.total,
                           tuition_trans.school_fee, tuition_trans.practical, tuition_trans.computer, tuition_trans.osis, 
                           tuition_trans.aid_foundation, tuition_trans.aid_goverment, tuition_trans.cost, tuition_trans.amount,
                           tuition_trans.type, tuition_trans.log, tuition.approved,
                           students.name, students.nisn, dept.dept_id, dept.name, students.faculty, students.grade_id,
                           dept.name as dept');

        $this->db->from('tuition, tuition_trans, students, dept');
        $this->db->where('tuition.no = tuition_trans.tuition');
        $this->db->where('tuition_trans.student = students.students_id');
        $this->db->where('students.dept_id = dept.dept_id');
        
        $this->db->where('tuition.currency', $cur);
        $this->cek_null($dept,"students.dept_id");
//        $this->cek_null($status,"tuition_trans.type");
        $this->db->where("tuition.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('tuition.approved', 1);
        $this->db->order_by('tuition.no', 'asc');
        $this->db->group_by('dept.dept_id');
        return $this->db->get();
    }
    
    function total_report($cur,$dept,$start,$end)
    {
        $this->db->select_sum('school_fee');
        $this->db->select_sum('practical');
        $this->db->select_sum('osis');
        $this->db->select_sum('computer');
        $this->db->select_sum('cost');
        $this->db->select_sum('aid_foundation');
        $this->db->select_sum('aid_goverment');
        $this->db->select_sum('amount');

        $this->db->from('tuition, tuition_trans, students, dept');
        $this->db->where('tuition_trans.student = students.students_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->where('tuition.no = tuition_trans.tuition');
        
        $this->db->where('tuition.currency', $cur);
        $this->cek_null($dept,"students.dept_id");
        $this->db->where("tuition.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
//        $this->db->where('tuition_trans.type', $status);
        $this->db->where('tuition.approved', 1);
        
        return $this->db->get()->row_array();
    }
    
    function total($no,$dept,$status)
    {
        $this->db->select_sum('school_fee');
        $this->db->select_sum('practical');
        $this->db->select_sum('osis');
        $this->db->select_sum('computer');
        $this->db->select_sum('cost');
        $this->db->select_sum('aid_foundation');
        $this->db->select_sum('aid_goverment');

        $this->db->from('tuition_trans, students, dept');
        $this->db->where('tuition_trans.student = students.students_id');
        $this->db->where('students.dept_id = dept.dept_id');
        
        $this->db->where('tuition_trans.tuition', $no);
        $this->cek_null($dept,"students.dept_id");
        $this->db->where('tuition_trans.type', $status);
        
        return $this->db->get()->row_array();
    }

    function total_chart($month,$year,$cur='IDR')
    {
        $this->db->select_sum('total');

        $this->db->from($this->table);
        $this->cek_null($cur,"currency");
        $this->db->where('approved', 1);
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        $query = $this->db->get()->row_array();
        return $query['total'];
    }

//    =========================================  REPORT  =================================================================

}

?>