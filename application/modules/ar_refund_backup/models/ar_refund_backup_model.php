<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_refund_backup_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ar_refund_backup';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('ar_refund_backup.id, ar_refund_backup.no, ar_refund_backup.notes, ar_refund_backup.check_no, ar_refund_backup.dates, customer.prefix, customer.name, ar_refund_backup.user,
                           ar_refund_backup.amount, ar_refund_backup.acc, ar_refund_backup.currency, ar_refund_backup.approved');
        
        $this->db->from('ar_refund_backup, customer');
        $this->db->where('ar_refund_backup.customer = customer.id');
        $this->db->order_by('ar_refund_backup.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$date)
    {
        $this->db->select('ar_refund_backup.id, ar_refund_backup.no, ar_refund_backup.notes, ar_refund_backup.check_no, ar_refund_backup.dates, customer.prefix, customer.name, ar_refund_backup.user,
                           ar_refund_backup.amount, ar_refund_backup.acc, ar_refund_backup.currency, ar_refund_backup.approved');

        $this->db->from('ar_refund_backup, customer');
        $this->db->where('ar_refund_backup.customer = customer.id');
        $this->cek_null($no,"ar_refund_backup.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"ar_refund_backup.dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }


    function report($customer,$start,$end,$acc,$cur)
    {
        $this->db->select('ar_refund_backup.id, ar_refund_backup.no, ar_refund_backup.notes, ar_refund_backup.check_no, ar_refund_backup.dates, customer.prefix, customer.name, ar_refund_backup.user,
                           ar_refund_backup.amount, ar_refund_backup.acc, ar_refund_backup.currency, ar_refund_backup.approved, ar_refund_backup.log');

        $this->db->from('ar_refund_backup, customer');
        $this->db->where('ar_refund_backup.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"ar_refund_backup.acc");
        $this->cek_null($cur,"ar_refund_backup.currency");
        $this->between($start,$end);
        return $this->db->get();
    }

    function total($customer,$start,$end,$acc,$cur)
    {
        $this->db->select_sum('amount');
        $this->db->from('ar_refund_backup, customer');
        $this->db->where('ar_refund_backup.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"ar_refund_backup.acc");
        $this->cek_null($cur,"ar_refund_backup.currency");
        $this->between($start,$end);
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ar_refund_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

}

?>