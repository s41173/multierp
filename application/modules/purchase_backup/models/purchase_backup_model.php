<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_backup_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'purchase_backup';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_purchase($limit, $offset)
    {
        $this->db->select('purchase_backup.id, purchase_backup.no, purchase_backup.dates, purchase_backup.docno, vendor.prefix, vendor.name, purchase_backup.user, purchase_backup.status,
                           purchase_backup.total, purchase_backup.p2, purchase_backup.costs, purchase_backup.notes, purchase_backup.currency, purchase_backup.approved');
        
        $this->db->from('purchase_backup, vendor');
        $this->db->where('purchase_backup.vendor = vendor.id');
        $this->db->order_by('purchase_backup.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$vendor,$start,$end)
    {
        $this->db->select('purchase_backup.id, purchase_backup.no, purchase_backup.dates, purchase_backup.docno, vendor.prefix, vendor.name, purchase_backup.user, purchase_backup.status,
                           purchase_backup.total, purchase_backup.p2, purchase_backup.costs, purchase_backup.notes, purchase_backup.currency, purchase_backup.approved');

        $this->db->from('purchase_backup, vendor');
        $this->db->where('purchase_backup.vendor = vendor.id');
        $this->cek_null($no,"purchase_backup.no");
        $this->cek_null($vendor,"vendor.name");
        $this->db->where("purchase_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function report($vendor,$cur,$start,$end)
    {
        $this->db->select('purchase_backup.id, purchase_backup.no, purchase_backup.dates, purchase_backup.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vendor.city, purchase_backup.currency, purchase_backup.user, purchase_backup.log, purchase_backup.desc,
                           purchase_backup.status, purchase_backup.tax, purchase_backup.p1, purchase_backup.p2, purchase_backup.total, purchase_backup.notes, purchase_backup.shipping_date,
                           purchase_backup.costs, purchase_backup.approved');

        $this->db->from('purchase_backup, vendor');
        $this->db->where('purchase_backup.vendor = vendor.id');
        $this->db->where("purchase_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"purchase_backup.currency");

        $this->db->where('purchase_backup.approved', 1);
        $this->db->order_by('purchase_backup.no', 'asc');
        return $this->db->get();
    }
    
    function total($vendor,$cur,$start,$end)
    {
        $this->db->select_sum('p1');
        $this->db->select_sum('p2');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');

        $this->db->from('purchase_backup, vendor');
        $this->db->where('purchase_backup.vendor = vendor.id');
        $this->db->where("purchase_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"purchase_backup.currency");
        $this->db->where('purchase_backup.approved', 1);
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>