<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_recap_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'student_recap';
    }

    private $ci,$table;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    public function get_total($dept=null,$grade=null,$month=null,$year=null)
    {
        $this->ci->db->select_sum('qty');
        $this->ci->db->from($this->table);
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($grade, 'grade_id');
        $this->cek_null($month, 'month');
        $this->cek_null($year, 'year');
        $query = $this->ci->db->get()->row_array();
        return intval($query['qty']);
    }
    
    function get_total_previous($dept=null,$grade=null,$month=null,$year=null)
    {
       $payment = new Payment_status_lib(); 
       $monthperiod = $payment->months_periode($month)-1;
       $year = $payment->year_name($monthperiod, $year);
       $res = $payment->months_from_period($monthperiod);
       
       $this->ci->db->select_sum('qty');
       $this->ci->db->from($this->table);
       $this->cek_null($dept, 'dept_id');
       $this->cek_null($grade, 'grade_id');
       $this->cek_null($res, 'month');
       $this->cek_null($year, 'year');
       $query = $this->ci->db->get()->row_array();
       return intval($query['qty']);
    }
    
   private function cek_null($val,$field)
   { if (isset($val)){ return $this->ci->db->where($field, $val); } }
    

    // -- add student ------
    
    function add($dept=0,$faculty=0,$name,$bornplace,$borndate,$gender,$address,$phone=0,$religion,$nisn,$npsn,$certificate,$skhun,$pname,$pjob,$paddress,$pphone=0,$gname,$gjob,$gaddress,$gphone=0)
    {
        $trans = array('dept_id' => $dept, 'faculty' => $faculty, 'name' => $name, 'born_place' => $bornplace, 'born_date' => $borndate, 'genre' => $gender,
                       'address' => $address, 'phone' => $phone, 'religion' => $religion, 'nisn' => $nisn,
                       'npsn' => $npsn, 'certificateno' => $certificate, 'skhun' => $skhun, 'fathers_name' => $pname, 'fathers_job' => $pjob,
                       'fathers_address' => $paddress, 'fathers_phone' => $pphone, 'trustee_name' => $gname, 'trustee_job' => $gjob,
                       'trustee_address' => $gaddress, 'trustee_phone' => $gphone
                      );
        
        $this->ci->db->insert($this->table, $trans); 
    }
    
    protected function get_max_id()
    {
       $this->ci->db->select_max('students_id');  
       $val = $this->ci->db->from($this->table)->get()->row_array();
       return intval($val['students_id']); 
    }
    
    protected function remove($id)
    {   
        $this->ci->db->where('id', $id);
        $this->ci->db->delete($this->table);
    }
 
    // -- update qty student -------
    function add_qty($dept,$grade,$amountqty,$month,$year)
    {
        if ($this->cek_qty($dept, $grade, $month, $year) == TRUE)
        {
            $this->ci->db->where('dept_id', $dept);
            $this->ci->db->where('grade_id', $grade);
            $this->ci->db->where('month', $month);
            $this->ci->db->where('year', $year);
            $val = $this->ci->db->get($this->table)->row();
            $qty = $val->qty + $amountqty;

            $res = array('qty' => $qty);
            $this->ci->db->where('id', $val->id);
            $this->ci->db->update($this->table, $res); 
        }
    }

    function min_qty($dept,$grade,$amountqty,$month,$year)
    {
        if ($this->cek_qty($dept, $grade, $month, $year) == TRUE)
        {
            $this->ci->db->where('dept_id', $dept);
            $this->ci->db->where('grade_id', $grade);
            $this->ci->db->where('month', $month);
            $this->ci->db->where('year', $year);
            $val = $this->ci->db->get($this->table)->row();
            $qty = $val->qty - $amountqty;

            $res = array('qty' => $qty);
            $this->ci->db->where('id', $val->id);
            $this->ci->db->update($this->table, $res); 
        }
    }
    
    public function cek_qty($dept,$grade,$month,$year)
    {
        $this->ci->db->where('dept_id', $dept);
        $this->ci->db->where('grade_id', $grade);
        $this->ci->db->where('month', $month);
        $this->ci->db->where('year', $year);
        $num = $this->ci->db->from($this->table)->get()->num_rows();
        if ($num > 0){ return TRUE; }else{ return FALSE; }
    }
    
    
    // closing 
    function closing()
    {
        $grade = new Grade_lib();
        $st = new Student_lib();
        $ps = new Period();
        $ps = $ps->get();
        
        foreach ($grade->get() as $res)
        {   
            if ($this->cek_total($res->dept_id,$res->grade_id,$ps->month,$ps->year) == 0)
            {
                $trans = array('dept_id' =>$res->dept_id, 'grade_id' => $res->grade_id, 
                           'qty' => $st->total_student_active($res->grade_id), 
                           'month' => $ps->month, 'year' => $ps->year);
                $this->ci->db->insert($this->table, $trans); 
            }
        }
        
    }
    
    private function cek_total($dept=null,$grade=null,$month=null,$year=null)
    {
        $this->ci->db->from($this->table);
        $this->cek_null($dept, 'dept_id');
        $this->cek_null($grade, 'grade_id');
        $this->cek_null($month, 'month');
        $this->cek_null($year, 'year');
        $query = $this->ci->db->get()->num_rows();
        return intval($query);
    }
    
}


/* End of file Property.php */