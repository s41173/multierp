<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Csales_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'csales';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_sales($limit, $offset)
    {
        $this->db->select('csales.id, csales.no, csales.dates, csales.docno, customer.prefix, customer.name, csales.user, csales.status,
                           csales.total, csales.p1, csales.p2, csales.discount, csales.costs, csales.notes, csales.currency, csales.approved');
        
        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->db->order_by('csales.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$date)
    {
        $this->db->select('csales.id, csales.no, csales.dates, csales.docno, customer.prefix, customer.name, csales.user, csales.status,
                           csales.total, csales.p1, csales.p2, csales.discount, csales.costs, csales.notes, csales.currency, csales.approved');

        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->cek_null($no,"csales.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"csales.dates");
        return $this->db->get();
    }

    function get_sales_list($currency=null,$customer=0,$st=0)
    {
        $this->db->select('csales.id, csales.no, csales.dates, csales.docno, customer.prefix, customer.name, csales.user, csales.status,
                           csales.total, csales.p1, csales.p2, csales.discount, csales.costs, csales.notes, csales.currency, csales.approved');

        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->db->where('csales.currency', $currency);
        $this->db->where('csales.status', $st);
        $this->cek_nol($customer,"csales.customer");
        $this->db->where('csales.approved', 1);
        
        $this->db->order_by('csales.dates', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    private function cek_nol($val,$field)
    {
        if ($val == 0){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter()
    {
        $this->db->select_max('no');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
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
        $this->db->select('csales.id, csales.no, csales.dates, csales.docno, csales.customer, customer.prefix, customer.name, csales.currency, csales.user, csales.log,
                           csales.status, csales.tax, csales.costs, csales.p1, csales.p2, csales.discount, csales.total, csales.notes, csales.desc,
                           csales.shipping_date, csales.approved');

        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->db->where('csales.id', $uid);
        return $this->db->get();
    }

    function get_sales_by_no($uid)
    {
        $this->db->select('csales.id, csales.no, csales.dates, csales.docno, csales.customer, customer.prefix, customer.name, csales.currency, csales.user, csales.log,
                           csales.status, csales.tax, csales.costs, csales.p1, csales.p2, csales.discount, csales.total, csales.notes, csales.desc,
                           csales.shipping_date, csales.approved');

        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->db->where('csales.no', $uid);
        return $this->db->get();
    }
    
    function update($uid, $users)
    {
        $this->db->where('no', $uid);
        $this->db->update($this->table, $users);
    }

    function update_id($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function valid_no($no)
    {
        $this->db->where('no', $no);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_no($no,$id)
    {
        $this->db->where('no', $no);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }


//    =========================================  REPORT  =================================================================

    function report($customer,$cur,$start,$end,$status)
    {
        $this->db->select('csales.id, csales.no, csales.dates, csales.docno, csales.customer, customer.prefix, customer.name, csales.currency, csales.user, csales.log,
                           csales.status, csales.tax, csales.costs, csales.p1, csales.p2, csales.discount, csales.total, csales.notes, csales.desc,
                           csales.shipping_date, csales.approved');

        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->db->where("csales.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"csales.currency");
        $this->cek_null($status,"csales.status");

        $this->db->where('csales.approved', 1);
        $this->db->order_by('csales.no', 'asc');
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

        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->db->where("csales.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"csales.currency");
        $this->cek_null($status,"csales.status");
        $this->db->where('csales.approved', 1);
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

        $this->db->from('csales, customer');
        $this->db->where('csales.customer = customer.id');
        $this->cek_null($cur,"csales.currency");
        $this->db->where('csales.approved', 1);
        $this->cek_null($month,"MONTH(csales.dates)");
        $this->cek_null($year,"YEAR(csales.dates)");
        $query = $this->db->get()->row_array();
        return $query['total'] + $query['costs'];
    }

//    =========================================  REPORT  =================================================================

}

?>