<?php

class Students_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'students';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($dept=null, $faculty=null, $grade=null, $value=null, $type)
    {
        $this->db->select('students.students_id, students.nisn, students.name, dept.name as dept, grade.name as grade, faculty.code as faculty, students.genre, students.joined, students.resign, students.status'); // select kolom yang mau di tampilkan
        $this->db->from('students,dept,grade,faculty'); // from table dengan join nya
        $this->db->where('students.grade_id = grade.grade_id');
        $this->db->where('students.faculty = faculty.faculty_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($faculty, 'students.faculty');
        $this->cek_null($grade, 'students.grade_id');
        $this->db->where('students.active', 1);
        
        if ($type == 0){ $this->cek_null($value, 'students.nisn'); }
        else { $this->cek_null($value, 'students.name'); }
        
        return $this->db->get(); 
    }
    
    function report($dept=null, $faculty=null, $grade=null)
    {
        $this->db->select('students.students_id, students.nisn, students.name, dept.name as dept, grade.name as grade, faculty.code as faculty, students.genre, students.joined, students.resign, students.status'); // select kolom yang mau di tampilkan
        $this->db->from('students,dept,grade,faculty'); // from table dengan join nya
        $this->db->where('students.grade_id = grade.grade_id');
        $this->db->where('students.faculty = faculty.faculty_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->cek_null($dept, 'students.dept_id');
        $this->cek_null($faculty, 'students.faculty');
        $this->cek_null($grade, 'students.grade_id');
        $this->db->where('students.active', 1);
        
        
        return $this->db->get(); 
    }
    
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

    function get($limit, $offset)
    {
        $this->db->select('students.students_id, students.nisn, students.name, dept.name as dept, grade.name as grade, faculty.code as faculty, students.genre, students.joined, students.resign, students.status'); // select kolom yang mau di tampilkan
        $this->db->from('students,dept,grade,faculty'); // from table dengan join nya
        $this->db->where('students.grade_id = grade.grade_id');
        $this->db->where('students.faculty = faculty.faculty_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->order_by('students.name', 'asc'); // query order
        $this->db->where('students.active', 1);
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function delete($uid)
    {
        $this->db->where('students_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
     
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_students_by_id($uid)
    {
        $this->db->select('students.students_id, students.name, students.born, students.genre, students.address, students.phone, students.religion, students.nisn, students.npsn, students.certificateno, students.skhun, students.joined, students.resign,
                           students.parents_name, students.parents_job, students.parents_address, students.parents_phone, students.trustee_name, students.trustee_job, students.trustee_address, students.trustee_phone,
                           dept.name as dept, dept.dept_id, students.faculty, students.grade_id, students.image, students.notes');
        $this->db->from('students,dept'); // from table dengan join nya
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->where('students.students_id', $uid);
        return $this->db->get();
    }

    function get_recap($dep)
    {
        $this->db->select('students.name, students.born, students.genre, students.address, students.phone, students.religion, students.nisn, students.npsn, students.certificateno, students.skhun, students.joined, students.resign,
                           students.parents_name, students.parents_job, students.parents_address, students.parents_phone, students.trustee_name, students.trustee_job, students.trustee_address, students.trustee_phone,
                           dept.name as dept, faculty.name as faculty');
        $this->db->from('students,dept,faculty'); // from table dengan join nya
        $this->db->where('students.faculty = faculty.faculty_id');
        $this->db->where('students.dept_id = dept.dept_id');
        $this->db->where('students.dept_id', $dep);
        return $this->db->get();
    }

    function get_grade()
    {
        $this->db->select('grade_id, name');
        $this->db->order_by('name', 'asc'); // query order
        return $this->db->get($this->table);
    }
    
    function counter()
    {
        $this->db->select_max('students_id');
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('students_id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_name($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get($this->table)->num_rows();

        if($query > 0)
        {
           return FALSE;
        }
        else
        {
           return TRUE;
        }
    }

    function validating_name($name,$depid,$id)
    {
        $this->db->where('name', $name);
        $this->db->where('dept_id', $depid);
        $this->db->where_not_in('students_id', $id);
        $query = $this->db->get($this->table)->num_rows();

        if($query > 0)
        {
           return FALSE;
        }
        else
        {
           return TRUE;
        }
    }
    
}

?>