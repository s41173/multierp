<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_backup_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'sales_backup';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_sales($limit, $offset)
    {
        $this->db->select('sales_backup.id, sales_backup.no, sales_backup.dates, sales_backup.docno, customer.prefix, customer.name, sales_backup.user, sales_backup.status,
                           sales_backup.total, sales_backup.p2, sales_backup.costs, sales_backup.notes, sales_backup.currency, sales_backup.approved');
        
        $this->db->from('sales_backup, customer');
        $this->db->where('sales_backup.customer = customer.id');
        $this->db->order_by('sales_backup.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$start,$end)
    {
        $this->db->select('sales_backup.id, sales_backup.no, sales_backup.dates, sales_backup.docno, customer.prefix, customer.name, sales_backup.user, sales_backup.status,
                           sales_backup.total, sales_backup.p2, sales_backup.costs, sales_backup.notes, sales_backup.currency, sales_backup.approved');

        $this->db->from('sales_backup, customer');
        $this->db->where('sales_backup.customer = customer.id');
        $this->cek_null($no,"sales_backup.no");
        $this->cek_null($customer,"customer.name");
        $this->db->where("sales_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }


//    =========================================  REPORT  =================================================================

    function report($customer,$cur,$start,$end)
    {
        $this->db->select('sales_backup.id, sales_backup.no, sales_backup.dates, sales_backup.docno, customer.prefix, customer.name, customer.address, customer.phone1, customer.phone2,
                           customer.city, sales_backup.currency, sales_backup.user, sales_backup.log, sales_backup.desc,
                           sales_backup.status, sales_backup.tax, sales_backup.p1, sales_backup.p2, sales_backup.discount, sales_backup.total, sales_backup.notes, sales_backup.shipping_date,
                           sales_backup.costs, sales_backup.approved');

        $this->db->from('sales_backup, customer');
        $this->db->where('sales_backup.customer = customer.id');
        $this->db->where("sales_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"sales_backup.currency");

        $this->db->where('sales_backup.approved', 1);
        $this->db->order_by('sales_backup.no', 'asc');
        return $this->db->get();
    }
    
    function total($customer,$cur,$start,$end)
    {
        $this->db->select_sum('p1');
        $this->db->select_sum('p2');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');
        $this->db->select_sum('discount');

        $this->db->from('sales_backup, customer');
        $this->db->where('sales_backup.customer = customer.id');
        $this->db->where("sales_backup.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"sales_backup.currency");
        $this->db->where('sales_backup.approved', 1);
        return $this->db->get()->row_array();
    }

    function total_chart($month,$year,$cur='IDR')
    {
        $this->db->select_sum('p1');
        $this->db->select_sum('p2');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');
        $this->db->select_sum('discount');

        $this->db->from('sales_backup, customer');
        $this->db->where('sales_backup.customer = customer.id');
        $this->cek_null($cur,"sales_backup.currency");
        $this->db->where('sales_backup.approved', 1);
        $this->cek_null($month,"MONTH(sales_backup.dates)");
        $this->cek_null($year,"YEAR(sales_backup.dates)");
        $query = $this->db->get()->row_array();
        return $query['total'] + $query['costs'];
    }

//    =========================================  REPORT  =================================================================

}

?>