<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->year = date('Y');
    }
    
    var $table = 'sales';
    private $year;
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_sales($limit, $offset)
    {
        $this->db->select('sales.id, sales.no, sales.contract_no, sales.dates, sales.docno, customer.prefix, customer.name, sales.user, sales.status,
                           sales.total, sales.p2, sales.costs, sales.notes, sales.currency, sales.tax_status, sales.approved');
        
        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->db->order_by('sales.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function search($no,$customer,$date)
    {
        $this->db->select('sales.id, sales.no, sales.contract_no, sales.dates, sales.docno, customer.prefix, customer.name, sales.user, sales.status,
                           sales.total, sales.p2, sales.costs, sales.notes, sales.currency, sales.tax_status, sales.approved');

        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->cek_null($no,"sales.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"sales.dates");
        return $this->db->get();
    }

    function get_sales_list($currency=null,$customer=null)
    {
        $this->db->select('sales.id, sales.no, sales.contract_no, sales.dates, sales.docno, customer.prefix, customer.name, sales.user, sales.status,
                           sales.total, sales.p2, sales.notes, sales.currency, sales.tax_status, sales.approved');

        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->db->where('sales.currency', $currency);
        $this->db->where('sales.status', 0);
        $this->cek_null($customer,"sales.customer");
        $this->db->where('sales.approved', 1);
        
        $this->db->order_by('sales.dates', 'asc');
        return $this->db->get();
    }

    function counter()
    {
        $this->db->select_max('no');
        $this->db->where('YEAR(dates)', $this->year);
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function counter_id()
    {
        $this->db->select_max('id');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['id'];
	return $userid;
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_sales_by_id($uid)
    {
        $this->db->select('sales.id, sales.no, sales.contract_no, sales.dates, sales.docno, sales.customer, customer.prefix, customer.name, sales.currency, sales.user, sales.log,
                           sales.status, sales.tax, sales.p1, sales.p2, sales.total, sales.notes, sales.desc, sales.discount_desc,
                           sales.shipping_date,sales.costs, sales.discount, sales.work_cost, sales.work_notes, sales.tax_serial, sales.tax_notes,
                           sales.tax_status, sales.tax_date, sales.tax_desc, sales.approved');

        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->db->where('sales.id', $uid);
        return $this->db->get();
    }

    function get_sales_by_no($uid,$year=null)
    {
        $this->db->select('sales.id, sales.no, sales.contract_no, sales.dates, sales.docno, sales.customer, customer.prefix, customer.name, customer.address, customer.phone1, customer.phone2,
                           customer.city, sales.currency, sales.user, sales.log, sales.desc, sales.shipping_date,
                           sales.status, sales.tax, sales.p1, sales.p2, sales.total, sales.notes, sales.discount_desc,
                           sales.costs, sales.discount, sales.work_cost, sales.work_notes, sales.tax_serial, sales.tax_notes,
                           sales.tax_status, sales.tax_date, sales.tax_desc, sales.approved');

        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->db->where('sales.no', $uid);
//        $this->db->where('YEAR(dates)', $year);
        $this->cek_null($year, 'YEAR(dates)');
        return $this->db->get();
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    function update($uid, $users)
    {
        $this->db->where('no', $uid);
        $this->db->update($this->table, $users);
    }
    
    function update_stts()
    {
        $val = array('status' => 1);
        $this->db->where('p2', 0);
        $this->db->where('approved', 1);
        $this->db->update($this->table, $val);
    }

    function update_id($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function valid_no($no)
    {
        $this->db->where('no', $no);
        $this->db->where('YEAR(dates)', $this->year);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_no($no,$id)
    {
        $this->db->where('no', $no);
        $this->db->where('YEAR(dates)', $this->year);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }


//    =========================================  REPORT  =================================================================

    function report($customer,$cur,$start,$end,$status)
    {
        $this->db->select('sales.id, sales.no, sales.contract_no, sales.dates, sales.docno, customer.prefix, customer.name, customer.address, customer.phone1, customer.phone2,
                           customer.city, sales.currency, sales.user, sales.log, sales.desc,
                           sales.status, sales.tax, sales.p1, sales.p2, sales.discount, sales.total, sales.notes, sales.shipping_date,
                           sales.costs, sales.tax_status, sales.tax_date, sales.tax_desc, sales.approved');

        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->db->where("sales.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"sales.currency");
        $this->cek_null($status,"sales.status");

        $this->db->where('sales.approved', 1);
        $this->db->order_by('sales.no', 'asc');
        return $this->db->get();
    }
    
    function total($customer,$cur,$start,$end,$status)
    {
        $this->db->select_sum('p1');
        $this->db->select_sum('p2');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');
        $this->db->select_sum('discount');

        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->db->where("sales.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"sales.currency");
        $this->cek_null($status,"sales.status");
        $this->db->where('sales.approved', 1);
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

        $this->db->from('sales, customer');
        $this->db->where('sales.customer = customer.id');
        $this->cek_null($cur,"sales.currency");
        $this->db->where('sales.approved', 1);
        $this->cek_null($month,"MONTH(sales.dates)");
        $this->cek_null($year,"YEAR(sales.dates)");
        $query = $this->db->get()->row_array();
        return $query['total'] + $query['costs'];
    }

//    =========================================  REPORT  =================================================================

}

?>