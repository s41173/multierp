<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ar_tuition_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->journal = new Journalgl_lib();
    }

    private $ci,$journal;
    private $table = 'ar_tuition';

    function total_student($dept=null,$faculty=null, $grade=null, $month, $year, $ayear)
    {
        $this->ci->db->from('ar_tuition, students');
        $this->ci->db->where('ar_tuition.student_id = students.students_id');
        
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($faculty,"students.faculty");
        $this->cek_null($grade,"students.grade_id");
        
        $this->ci->db->where('MONTH(ar_tuition.dates)', $month);
        $this->ci->db->where('YEAR(ar_tuition.dates)', $year);
        $this->ci->db->where('ar_tuition.financial_year', $ayear);
        $this->ci->db->where('ar_tuition.approved', 1);
        
        return $this->ci->db->get()->num_rows();
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }

}

/* End of file Property.php */