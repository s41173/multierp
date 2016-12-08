<?php

class Inactive_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'students';
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

//    -------------------------------------------------------------------------------------

    function cek_dep($val)
    {
        if ($val != "all"){$val = $this->db->where('students.dept_id', $val);}
        else{ $val = null;}
        return $val;
    }

    function cek_grade($val)
    {
        if ($val != "all"){$val = $this->db->where('students.grade_id', $val);}
        else{ $val = null;}
        return $val;
    }

    function cek_faculty($val)
    {
        if ($val != "all"){$val = $this->db->where('students.faculty', $val);}
        else{ $val = null;}
        return $val;
    }

    function search($dept=null, $faculty=null, $grade=null)
    {
        $this->db->select('students.students_id, students.nisn, students.name, dept.name as dept, grade.name as grade, faculty.code as faculty, students.genre, students.status, students.resign, students.active');
        $this->db->from('students,dept,grade,faculty'); // from table dengan join nya
        $this->db->where('students.grade_id = grade.grade_id');
        $this->db->where('students.faculty = faculty.faculty_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->order_by('students.name', 'asc'); // query order
        $this->db->where('students.active', 0);
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($faculty, 'students.faculty');
        $this->cek_null($grade, 'students.grade_id');
        return $this->db->get(); 
    }
    
    function count()
    {
        $this->db->from('students');
        $this->db->where('students.active', 0);
        return $this->db->get()->num_rows();
    }

    
}

?>