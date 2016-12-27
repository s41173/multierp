<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_return_backup_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'purchase_return_backup';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_purchase_return($limit, $offset)
    {
        $this->db->select('purchase_return_backup.id, purchase_return_backup.no, purchase_return_backup.purchase, purchase_return_backup.dates, purchase_return_backup.docno, vendor.prefix, vendor.name, purchase_return_backup.user, purchase_return_backup.status,
                           purchase_return_backup.total, purchase_return_backup.balance, purchase_return_backup.costs, purchase_return_backup.notes, purchase_return_backup.currency, purchase_return_backup.approved');
        
        $this->db->from('purchase_return_backup, vendor');
        $this->db->where('purchase_return_backup.vendor = vendor.id');
        $this->db->order_by('purchase_return_backup.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$po,$vendor,$start,$end)
    {
        $this->db->select('purchase_return_backup.id, purchase_return_backup.no, purchase_return_backup.purchase, purchase_return_backup.dates, purchase_return_backup.docno, vendor.prefix, vendor.name, purchase_return_backup.user, purchase_return_backup.status,
                           purchase_return_backup.total, purchase_return_backup.balance, purchase_return_backup.costs, purchase_return_backup.notes, purchase_return_backup.currency, purchase_return_backup.approved');

        $this->db->from('purchase_return_backup, vendor');
        $this->db->where('purchase_return_backup.vendor = vendor.id');
        $this->cek_null($no,"purchase_return_backup.no");
        $this->cek_null($po,"purchase_return_backup.purchase");
        $this->cek_null($vendor,"vendor.name");
        $this->db->where("purchase_return_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function report($cur,$vendor,$start,$end)
    {
        $this->db->select('purchase_return_backup.id, purchase_return_backup.no, purchase_return_backup.purchase, purchase_return_backup.dates, purchase_return_backup.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vendor.city, purchase_return_backup.user, purchase_return_backup.log, purchase_return_backup.currency,
                           purchase_return_backup.status, purchase_return_backup.tax, purchase_return_backup.balance, purchase_return_backup.total, purchase_return_backup.notes,
                           purchase_return_backup.costs, purchase_return_backup.approved');

        $this->db->from('purchase_return_backup, vendor');
        $this->db->where('purchase_return_backup.vendor = vendor.id');
        $this->db->where("purchase_return_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"purchase_return_backup.currency");

        $this->db->where('purchase_return_backup.approved', 1);
        $this->db->order_by('purchase_return_backup.no', 'asc');
        return $this->db->get();
    }
    
    function total($cur,$vendor,$start,$end)
    {
        $this->db->select_sum('balance');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');

        $this->db->from('purchase_return_backup, vendor');
        $this->db->where('purchase_return_backup.vendor = vendor.id');
        $this->db->where("purchase_return_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"purchase_return_backup.currency");
        $this->db->where('purchase_return_backup.approved', 1);
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>