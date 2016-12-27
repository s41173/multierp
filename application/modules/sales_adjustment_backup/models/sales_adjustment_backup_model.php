<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_adjustment_backup_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'sales_adjustment_backup';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_sales_adjustment($limit, $offset)
    {
        $this->db->select('id, no, dates, sales_no, docno, currency, user, notes, desc, total, dp, approved, log');
        $this->db->from($this->table);
        $this->db->order_by('no', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no=null,$start=null, $end=null)
    {
        $this->db->select('id, no, dates, sales_no, docno, currency, user, notes, desc, total, dp, approved, log');
        $this->db->from($this->table);
        $this->cek_null($no,"no");
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function report($start,$end,$cur)
    {
        $this->db->select('id, no, dates, sales_no, docno, currency, user, notes, desc, total, dp, approved, log');
        $this->db->from($this->table);
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('approved', 1);
        $this->db->where('currency', $cur);
        $this->db->order_by('no', 'asc');
        return $this->db->get();
    }

    function total($start,$end,$cur)
    {
        $this->db->select_sum('total');
        $this->db->from($this->table);
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('approved', 1);
        $this->db->where('currency', $cur);
        $this->db->order_by('no', 'asc');
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>