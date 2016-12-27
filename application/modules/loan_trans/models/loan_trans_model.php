<?php

class Loan_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'loan_trans';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($employee_id=null,$date=null, $type=null)
    {
        $this->db->select('id, employee_id, type, date, amount, log');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($employee_id, 'employee_id');
        $this->cek_null($date, 'date');
        $this->cek_null($type, 'type');
        return $this->db->get(); 
    }
    
    function report($start=null, $end=null, $e_type=null, $t_type=null)
    {
        $this->db->select('loan_trans.id, employee.type, loan_trans.type as trans_type, loan_trans.notes,  loan_trans.employee_id, loan_trans.date, loan_trans.amount, loan_trans.log');
        $this->db->from('loan_trans, employee');
        $this->db->where('employee.id = loan_trans.employee_id');
        $this->cek_between($start, $end);
        $this->cek_null($e_type, 'employee.type');
        $this->cek_null($t_type, 'loan_trans.type');
        return $this->db->get(); 
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("loan_trans.date BETWEEN '".$start."' AND '".$end."'"); }
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    function total_chart($month,$year,$cur='IDR')
    {
        $this->db->select_sum('amount');

        $this->db->from($this->table);
        $this->cek_null($cur,"currency");
        $this->cek_null($month,"MONTH(date)");
        $this->cek_null($year,"YEAR(date)");
        $this->db->where('type', 'borrow');
        $query = $this->db->get()->row_array();
        return $query['amount'];
    }
    
    function delete_amount()
    {
        $this->db->where('amount', 0);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
}

?>