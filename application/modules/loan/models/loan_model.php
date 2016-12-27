<?php

class Loan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'loan';
    
    function count()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }

    function search($employee_id=null)
    {
        $this->db->select('id, employee_id, employee.type, amount');
        $this->db->from($this->table); // from table dengan join nya
        $this->cek_null($employee_id, 'employee_id');
        return $this->db->get(); 
    }
    
    function report($e_type=null)
    {
        $this->db->select('loan.id, loan.employee_id, employee.type, loan.amount');
        $this->db->from('loan, employee');
        $this->db->where('employee.id = loan.employee_id');
        $this->cek_null($e_type, 'employee.type');
        return $this->db->get(); 
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("loan.date BETWEEN '".$start."' AND '".$end."'"); }
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