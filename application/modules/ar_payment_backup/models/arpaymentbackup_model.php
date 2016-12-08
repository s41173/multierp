<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Arpaymentbackup_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ar_payment_backup';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('ar_payment_backup.id, ar_payment_backup.no, ar_payment_backup.check_no, ar_payment_backup.dates, customer.prefix, customer.name, ar_payment_backup.user,
                           ar_payment_backup.amount, ar_payment_backup.acc, ar_payment_backup.currency, ar_payment_backup.approved');
        
        $this->db->from('ar_payment_backup, customer');
        $this->db->where('ar_payment_backup.customer = customer.id');
        $this->db->order_by('ar_payment_backup.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$start,$end)
    {
        $this->db->select('ar_payment_backup.id, ar_payment_backup.no, ar_payment_backup.docno, ar_payment_backup.check_no, ar_payment_backup.dates, customer.prefix, customer.name, ar_payment_backup.user,
                           ar_payment_backup.amount, ar_payment_backup.acc, ar_payment_backup.currency, ar_payment_backup.approved');

        $this->db->from('ar_payment_backup, customer');
        $this->db->where('ar_payment_backup.customer = customer.id');
        $this->cek_null($no,"ar_payment_backup.no");
        $this->cek_null($customer,"customer.name");
        $this->between($start,$end);
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

   
    function report($customer,$start,$end,$acc,$cur)
    {
        $this->db->select('ar_payment_backup.id, ar_payment_backup.no, ar_payment_backup.docno, ar_payment_backup.check_no, ar_payment_backup.dates, customer.prefix, customer.name, ar_payment_backup.user,
                           ar_payment_backup.amount, ar_payment_backup.acc, ar_payment_backup.currency, ar_payment_backup.approved, ar_payment_backup.log');

        $this->db->from('ar_payment_backup, customer');
        $this->db->where('ar_payment_backup.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"ar_payment_backup.acc");
        $this->cek_null($cur,"ar_payment_backup.currency");
        $this->db->where('ar_payment_backup.approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }

    function total($customer,$start,$end,$acc,$cur)
    {
        $this->db->select_sum('amount');
        $this->db->from('ar_payment_backup, customer');
        $this->db->where('ar_payment_backup.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"ar_payment_backup.acc");
        $this->cek_null($cur,"ar_payment_backup.currency");
        $this->db->where('ar_payment_backup.approved', 1);
        $this->between($start,$end);
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ar_payment_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

}

?>