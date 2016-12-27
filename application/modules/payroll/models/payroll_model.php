<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'payroll';
    
    function count()
    {
        return $this->db->count_all($this->table);
    }
    
    function get($limit, $offset, $year=null)
    {
//        $this->db->select('id, month, year, dates, currency, acc, log, total_honor, total_salary, total_consumption, total_transportation, total_overtime, total_late, total_loan, total_insurance, total_tax, total_other, balance, notes, approved');
        $this->db->from($this->table);
        $this->cek_null($year,"YEAR(dates)");
        $this->db->order_by('dates', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function search($month, $year)
    {
//        $this->db->select('id, month, year, dates, currency, acc, log, total_honor, total_salary, total_overtime, total_late, total_loan, total_insurance, total_tax, total_other, balance, notes, approved');
        $this->db->from($this->table);
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        $this->db->order_by('dates', 'desc');
        return $this->db->get(); 
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function report($cur='IDR', $year=null)
    {
//        $this->db->select('id, month, year, dates, currency, acc, log, total_honor, total_salary, total_overtime, total_late, total_loan, total_insurance, total_tax, total_other, balance, notes, approved');
        $this->db->from($this->table);
        $this->db->where('currency', $cur);
        $this->cek_null($year,"YEAR(dates)");
        $this->db->order_by('dates', 'asc');
        $this->db->where('approved', 1);
        return $this->db->get(); 
    }

    function total_chart($month,$year,$cur='IDR')
    {
        $this->db->select_sum('balance');

        $this->db->from($this->table);
        $this->cek_null($cur,"currency");
        $this->db->where('approved', 1);
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        $query = $this->db->get()->row_array();
        return $query['balance'];
    }

//    =========================================  REPORT  =================================================================

}

?>