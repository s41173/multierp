<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_tuition_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ar_tuition';
    
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    private function cek_nol($val,$field)
    {
        if ($val == 0){return null;}
        else {return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function report($cur,$dept,$start,$end,$year)
    {
        $this->db->select('id, no, dates, currency, student_id, month, financial_year, acc, school_fee, practical, osis, computer, amount, ar_tuition.notes, approved,
                          students.name');

        $this->db->from('ar_tuition, students, dept');
        $this->db->where('ar_tuition.student_id = students.students_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($cur,"currency");

        $this->db->where('financial_year', $year);
        $this->db->where('approved', 1);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function total($cur,$dept,$start,$end,$year)
    {
        $this->db->select_sum('school_fee');
        $this->db->select_sum('practical');
        $this->db->select_sum('osis');
        $this->db->select_sum('computer');
        $this->db->select_sum('amount');

        $this->db->from('ar_tuition, students, dept');
        $this->db->where('ar_tuition.student_id = students.students_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($cur,"currency");

        $this->db->where('financial_year', $year);
        $this->db->where('approved', 1);
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>