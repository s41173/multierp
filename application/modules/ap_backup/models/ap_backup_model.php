<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap_backup_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ap_backup';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_ap_backup($limit, $offset)
    {
        $this->db->select('ap_backup.id, ap_backup.no, ap_backup.docno, ap_backup.dates, vendor.prefix, vendor.name, ap_backup.user, ap_backup.acc, ap_backup.status,
                           ap_backup.amount, ap_backup.notes, ap_backup.desc, ap_backup.log, ap_backup.currency');
        
        $this->db->from('ap_backup, vendor');
        $this->db->where('ap_backup.vendor = vendor.id');
        $this->db->order_by('ap_backup.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$vendor,$start,$end)
    {
        $this->db->select('ap_backup.id, ap_backup.no, ap_backup.docno, ap_backup.dates, vendor.prefix, vendor.name, ap_backup.user, ap_backup.acc, ap_backup.status,
                           ap_backup.amount, ap_backup.notes, ap_backup.desc, ap_backup.log, ap_backup.currency');

        $this->db->from('ap_backup, vendor');
        $this->db->where('ap_backup.vendor = vendor.id');
        $this->cek_null($no,"ap_backup.no");
        $this->cek_null($vendor,"vendor.name");
        $this->between($start,$end);
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function report($vendor,$start,$end,$acc,$cur)
    {
        $this->db->select('ap_backup.id, ap_backup.no, ap_backup.docno, ap_backup.dates, vendor.prefix, vendor.name, ap_backup.user, ap_backup.acc, ap_backup.status,
                           ap_backup.amount, ap_backup.notes, ap_backup.desc, ap_backup.log, ap_backup.currency');

        $this->db->from('ap_backup, vendor');
        $this->db->where('ap_backup.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap_backup.acc");
        $this->cek_null($cur,"ap_backup.currency");
        $this->between($start,$end);
        return $this->db->get();
    }

    function total($vendor,$start,$end,$acc,$cur)
    {
        $this->db->select_sum('amount');
        $this->db->from('ap_backup, vendor');
        $this->db->where('ap_backup.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap_backup.acc");
        $this->cek_null($cur,"ap_backup.currency");
        $this->between($start,$end);
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ap_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

    

}

?>