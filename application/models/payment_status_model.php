<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_status_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'fee_payment_status';
    
    function count($year)
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        $this->db->where('financial_year', $year);
        return $this->db->get($this->table)->num_rows();
    }
    
    function get($limit, $offset,$year)
    {
        $this->db->select('id, student_id, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, financial_year');
        $this->db->from('fee_payment_status, students');
        $this->db->where('fee_payment_status.student_id = students.students_id');
        $this->db->order_by('students.name', 'asc');
        $this->db->where('financial_year', $year);
        $this->db->where('students.active', 1);
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function get_all()
    {
        $this->db->select('id, student_id, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, financial_year');
        $this->db->from('fee_payment_status');
        return $this->db->get(); 
    }
    
    function search($dept=null, $faculty=null, $grade=null, $value=null, $type, $year=null,$stts=1)
    {
        $this->db->select('id, student_id, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, financial_year');
        $this->db->from('fee_payment_status, students');
        $this->db->where('fee_payment_status.student_id = students.students_id');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($faculty, 'students.faculty');
        $this->cek_null($grade, 'students.grade_id');
        $this->cek_null($year, 'fee_payment_status.financial_year');
        $this->db->where('students.active', $stts);
        
        if ($type == 0){ $this->cek_null($value, 'students.nisn'); }
        else { $this->cek_null($value, 'students.name'); }
        
        return $this->db->get(); 
    }
    
    function get_id($id)
    {
        $this->db->select('id, student_id, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, financial_year');
        $this->db->from('fee_payment_status');
        $this->db->where('id', $id);
        return $this->db->get()->row(); 
    }
    
    function report($dept=null, $faculty=null, $grade=null, $fyear=null, $bulan=0, $tahun=0)
    {
        $this->db->select('fee_payment_status.id, 
                           fee_payment_status.student_id, 
                           fee_payment_status.p1,
                           fee_payment_status.p2,
                           fee_payment_status.p3,
                           fee_payment_status.p4,
                           fee_payment_status.p5,
                           fee_payment_status.p6,
                           fee_payment_status.p7,
                           fee_payment_status.p8,
                           fee_payment_status.p9,
                           fee_payment_status.p10,
                           fee_payment_status.p11,
                           fee_payment_status.p12,
                           fee_payment_status.financial_year,
                           students.name,
                           students.nisn,
                           students.grade_id,
                           dept.name as dept,
                           dept.dept_id,
                           grade.name as grade,
                           grade.level,
                           faculty.name as faculty');
        
        $this->db->from('fee_payment_status, students, dept, grade, faculty');
        $this->db->where('fee_payment_status.student_id = students.students_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->where('students.grade_id = grade.grade_id');
        $this->db->where('students.faculty = faculty.faculty_id');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($faculty, 'students.faculty');
        $this->cek_null($grade, 'students.grade_id');
        $this->cek_null($fyear, 'fee_payment_status.financial_year');
//        $this->db->where('students.active', 1);
//        $this->db->where('MONTH(students.resign) >', $bulan);
//        $this->db->where('YEAR(students.resign) >=', $tahun);
//        ============ JOIN ================================
        $this->db->where('students.resign >', $tahun.'-'.$bulan.'-'.get_total_days($bulan));
        $this->db->where('students.joined <=', $tahun.'-'.$bulan.'-'.get_total_days($bulan));
        $this->db->order_by('students.name', 'asc');
        return $this->db->get(); 
    }
    
    function unpaid_sum($dept=null, $faculty=null, $grade=null, $year=null, $month=null)
    {
        $this->db->from('fee_payment_status, students');
        $this->db->where('fee_payment_status.student_id = students.students_id');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($faculty, 'students.faculty');
        $this->cek_null($grade, 'students.grade_id');
        $this->cek_null($year, 'fee_payment_status.financial_year');
        $this->db->where('students.active', 1);
        $this->db->where('fee_payment_status.'.$month, null);
        return $this->db->get()->num_rows(); 
    }
    
    function paid_onfront_sum($dept=null, $faculty=null, $grade=null, $yearacademic=null, $monthcol=null,$month=0,$year=0)
    {
        $this->db->from('fee_payment_status, students');
        $this->db->where('fee_payment_status.student_id = students.students_id');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($faculty, 'students.faculty');
        $this->cek_null($grade, 'students.grade_id');
        $this->cek_null($yearacademic, 'fee_payment_status.financial_year');
        $this->db->where("$monthcol IS NOT NULL");
        $this->db->where("MONTH($monthcol) !=", $month);
        $this->db->where("YEAR($monthcol) !=", $year);
        $this->db->where('students.active', 1);
        return $this->db->get()->num_rows(); 
    }
    
    public function delete($id)
    {
       $this->db->where('id', $id);
       $this->db->delete($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("gls.dates BETWEEN '".$start."' AND '".$end."'"); }
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

}

?>