<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'students';
    }

    private $ci,$table;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function cek_active($id)
    {
       $this->ci->db->where('students_id',$id);
       $query = $this->ci->db->get($this->table)->row();
       if ($query->active == 1) { return TRUE; } else { return FALSE; }
    }
     
    function get_name($id)
    {
        $this->ci->db->select('name');
        $this->ci->db->from($this->table);
        $this->ci->db->where('students_id', $id);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->name; }
    }
    
    function get_nisn($id)
    {
        $this->ci->db->select('nisn');
        $this->ci->db->from($this->table);
        $this->ci->db->where('students_id', $id);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->nisn; }
    }
    
    function get_dept($id)
    {
        $this->ci->db->select('dept_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('students_id', $id);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->dept_id; }
    }
    
    function get_grade($id)
    {
        $this->ci->db->select('grade_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('students_id', $id);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->grade_id; }
        
    }
    
    function get_faculty($id)
    {
        $this->ci->db->select('faculty');
        $this->ci->db->from($this->table);
        $this->ci->db->where('students_id', $id);
        $res = $this->ci->db->get()->row();
        if ($res){return $res->faculty;}
    }
    
    function get_address($id)
    {
        $this->ci->db->select('address');
        $this->ci->db->from($this->table);
        $this->ci->db->where('students_id', $id);
        $res = $this->ci->db->get()->row();
        return $res->address;
    }
    
    function get_id_by_name($name,$dept=0)
    {
        $this->ci->db->select('students_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('name', $name);
        $this->ci->db->where('dept_id', $dept);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->students_id; }
    }
    
    function get_id_by_no($no=0)
    {
        $this->ci->db->select('students_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('nisn', $no);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->students_id; }
    }
    
    // -- add student ------
    
    function add($dept=0, $faculty=0, $name, $bornplace, $borndate, $gender, $address, $zip, $phone=0, $mobile, $email,
                 $religion, $citizen, $condition, $nis, $nisn, $npsn, $certificate, $skhun, 
                 $fname, $fjob, $faddress, $fphone=0, $fmobile=0, $fincome=0,
                 $mname, $mjob, $maddress, $mphone=0, $mmobile=0, $mincome=0,
                 $gname, $gjob, $gaddress, $gphone=0, $join, $resign, $active)
    {
        $trans = array('dept_id' => $dept, 'faculty' => $faculty, 'name' => $name, 'born_place' => $bornplace, 'born_date' => $borndate, 'genre' => $gender,
                       'address' => $address, 'zipcode' => $zip, 'phone' => $phone, 'mobile' => $mobile, 'email' => $email, 'religion' => $religion, 'citizen' => $citizen, 'condition' => $condition, 
                       'nisn' => $nis, 'nisn_national' => $nisn, 'npsn' => $npsn, 'certificateno' => $certificate, 'skhun' => $skhun, 
            
                       'fathers_name' => $fname, 'fathers_job' => $fjob, 'fathers_address' => $faddress, 'fathers_phone' => $fphone, 'fathers_mobile' => $fmobile, 'fathers_income' => $fincome, 
                       'mothers_name' => $mname, 'mothers_job' => $mjob, 'mothers_address' => $maddress, 'mothers_phone' => $mphone, 'mothers_mobile' => $mmobile, 'mothers_income' => $mincome,
                       'trustee_name' => $gname, 'trustee_job' => $gjob, 'trustee_address' => $gaddress, 'trustee_phone' => $gphone,
                       'joined' => $join, 'resign' => $resign, 'active' => $active
                      );
        
        $this->ci->db->insert($this->table, $trans); 
    }
    
    function get_max_id()
    {
       $this->ci->db->select_max('students_id');  
       $val = $this->ci->db->from($this->table)->get()->row_array();
       return intval($val['students_id']); 
    }
    
    function remove($id)
    {   
        $this->ci->db->where('students_id', $id);
        $this->ci->db->delete($this->table);
    }
    
    function inactive($id)
    {
        $st = new Student();
        $val = array('active' => 0);
        $st->where('students_id ', $id)->update($val, TRUE);
    }
    
    function get_by_grade($grade)
    {
       $st = new Student();
       $result = $st->where('grade_id', $grade)->where('active', 1)->get();
       return $result;
    }
    
    function graduation($id,$val=0,$status='graduation',$resign)
    {
        $st = new Student();
        $val = array('active' => $val, 'status' => $status, 'resign' => $resign);
        $st->where('students_id ', $id)->update($val, TRUE);
    }
    
    function active($id)
    {
        $st = new Student();
        $val = array('active' => 1);
        $st->where('students_id ', $id)->update($val, TRUE);
    }
    
    function total_student_active($grade)
    {
        $st = new Student();
        return $st->where('grade_id', $grade)->where('active', 1)->count();
    }
    
    function count_student($id)
    {
        $st = new Student();
        $st->where('students_id', $id)->count();
        if ($st->where('students_id', $id)->count() > 0){ return TRUE; }else{ return FALSE; }
    }
    
    
    function update($uid, $dept=0, $faculty=0, $name, $bornplace, $borndate, $gender, $address, $zip, $phone=0, $mobile, $email,
                 $religion, $citizen, $condition, $nis, $nisn, $npsn, $certificate, $skhun, 
                 $fname, $fjob, $faddress, $fphone=0, $fmobile=0, $fincome=0,
                 $mname, $mjob, $maddress, $mphone=0, $mmobile=0, $mincome=0,
                 $gname, $gjob, $gaddress, $gphone=0, $join, $resign, $active)
    {
        $trans = array('dept_id' => $dept, 'faculty' => $faculty, 'name' => $name, 'born_place' => $bornplace, 'born_date' => $borndate, 'genre' => $gender,
                       'address' => $address, 'zipcode' => $zip, 'phone' => $phone, 'mobile' => $mobile, 'email' => $email, 'religion' => $religion, 'citizen' => $citizen, 'condition' => $condition, 
                       'nisn' => $nis, 'nisn_national' => $nisn, 'npsn' => $npsn, 'certificateno' => $certificate, 'skhun' => $skhun, 
            
                       'fathers_name' => $fname, 'fathers_job' => $fjob, 'fathers_address' => $faddress, 'fathers_phone' => $fphone, 'fathers_mobile' => $fmobile, 'fathers_income' => $fincome, 
                       'mothers_name' => $mname, 'mothers_job' => $mjob, 'mothers_address' => $maddress, 'mothers_phone' => $mphone, 'mothers_mobile' => $mmobile, 'mothers_income' => $mincome,
                       'trustee_name' => $gname, 'trustee_job' => $gjob, 'trustee_address' => $gaddress, 'trustee_phone' => $gphone,
                       'joined' => $join, 'resign' => $resign, 'active' => $active
                      );
        
        $this->ci->db->where('students_id', $uid);
        $this->ci->db->update($this->table, $trans);
    }
    
}


/* End of file Property.php */