<?php

class Payroll_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'payroll_trans';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($type=null, $dept=null, $uid=0, $payment=null)
    {
//        $this->db->select('id, dept, employee_id, month, year, date, type, amount, user');
        $this->db->from($this->table);
        $this->cek_null($dept, 'dept');
        $this->cek_null($type, 'type');
        $this->cek_null($payment, 'payment_type');
        $this->db->where('payroll_id', $uid);
        return $this->db->get(); 
    }
    
    function report($division = null, $type=null, $dept=null, $uid=0, $payment=null)
    {
//        $this->db->select('payroll_trans.id, payroll_trans.dept, payroll_trans.employee_id, payroll_trans.type, payroll_trans.basic_salary, payroll_trans.experience, payroll_trans.consumption,
//                           payroll_trans.transportation, payroll_trans.overtime, payroll_trans.bonus, payroll_trans.principal, payroll_trans.principal_helper, payroll_trans.head_department, payroll_trans.home_room, 
//                           payroll_trans.loan, payroll_trans.tax, payroll_trans.insurance, payroll_trans.other_discount, payroll_trans.amount, payroll_trans.user, payroll_trans.log');
        
        $this->db->from('payroll_trans, employee, division');
        $this->db->where('payroll_trans.employee_id = employee.id');
        $this->db->where('employee.division_id = division.id');
        $this->cek_null($dept, 'payroll_trans.dept');
        $this->cek_null($type, 'payroll_trans.type');
        $this->cek_null($division, 'employee.division_id');
        $this->cek_null($payment, 'payment_type');
        $this->db->where('payroll_id', $uid);
        return $this->db->get(); 
    }
    
    function sum_search($type=null, $dept=null, $uid=0, $payment=null)
    {
        $this->db->select_sum('amount');
        $this->db->from($this->table);
        $this->cek_null($dept, 'dept');
        $this->cek_null($type, 'type');
        $this->cek_null($payment, 'payment_type');
        $this->db->where('payroll_id', $uid);
        return $this->db->get(); 
    }
    
    function count_search($type=null, $dept=null, $uid=0, $payment=null)
    {
        $this->db->from($this->table);
        $this->cek_null($dept, 'dept');
        $this->cek_null($type, 'type');
        $this->cek_null($payment, 'payment_type');
        $this->db->where('payroll_id', $uid);
        return $this->db->get()->num_rows(); 
    }
    
    function sum_report($division = null, $type=null, $dept=null, $uid=0, $payment=null)
    {
        $this->db->select_sum('amount');
        
        $this->db->from('payroll_trans, employee, division');
        $this->db->where('payroll_trans.employee_id = employee.id');
        $this->db->where('employee.division_id = division.id');
        $this->cek_null($dept, 'payroll_trans.dept');
        $this->cek_null($type, 'payroll_trans.type');
        $this->cek_null($division, 'employee.division_id');
        $this->cek_null($payment, 'payment_type');
        $this->db->where('payroll_id', $uid);
        return $this->db->get(); 
    }
    
    function count_report($division = null, $type=null, $dept=null, $uid=0, $payment=null)
    {
        $this->db->from('payroll_trans, employee, division');
        $this->db->where('payroll_trans.employee_id = employee.id');
        $this->db->where('employee.division_id = division.id');
        $this->cek_null($dept, 'payroll_trans.dept');
        $this->cek_null($type, 'payroll_trans.type');
        $this->cek_null($division, 'employee.division_id');
        $this->cek_null($payment, 'payment_type');
        $this->db->where('payroll_id', $uid);
        return $this->db->get()->num_rows(); 
    }
            
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
}

?>