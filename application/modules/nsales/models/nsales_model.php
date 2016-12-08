<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nsales_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->year = date('Y');
    }
    
    var $table = 'nsales';
    private $year;
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_nsales($limit, $offset)
    {
        $this->db->select('nsales.id, nsales.no, nsales.contract, nsales.contract_no, nsales.dates, nsales.docno, customer.prefix, customer.name, nsales.user, nsales.status,
                           nsales.total, nsales.p2, nsales.costs, nsales.notes, nsales.currency, nsales.approved');
        
        $this->db->from('nsales, customer');
        $this->db->where('nsales.customer = customer.id');
        $this->db->order_by('nsales.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$date)
    {
        $this->db->select('nsales.id, nsales.no, nsales.contract, nsales.contract_no, nsales.dates, nsales.docno, customer.prefix, customer.name, nsales.user, nsales.status,
                           nsales.total, nsales.p2, nsales.costs, nsales.notes, nsales.currency, nsales.approved');

        $this->db->from('nsales, customer');
        $this->db->where('nsales.customer = customer.id');
        $this->cek_null($no,"nsales.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"nsales.dates");
        return $this->db->get();
    }

    function get_nsales_list($currency=null,$customer=null)
    {
        $this->db->select('nsales.id, nsales.no, nsales.contract, nsales.contract_no, nsales.dates, nsales.docno, customer.prefix, customer.name, nsales.user, nsales.status,
                           nsales.total, nsales.p2, nsales.notes, nsales.currency, nsales.approved');

        $this->db->from('nsales, customer');
        $this->db->where('nsales.customer = customer.id');
        $this->db->where('nsales.currency', $currency);
        $this->db->where('nsales.status', 0);
        $this->cek_null($customer,"nsales.customer");
        $this->db->where('nsales.approved', 1);
        
        $this->db->order_by('nsales.dates', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
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
    
    function get_nsales_by_id($uid)
    {
        $this->db->select('nsales.id, nsales.no, nsales.contract, nsales.contract_no, nsales.dates, nsales.docno, nsales.customer, customer.prefix, customer.name, nsales.currency, nsales.user, nsales.log,
                           nsales.status, nsales.tax, nsales.p1, nsales.p2, nsales.total, nsales.notes, nsales.desc, nsales.discount_desc,
                           nsales.shipping_date,nsales.costs, nsales.discount, nsales.approved');

        $this->db->from('nsales, customer');
        $this->db->where('nsales.customer = customer.id');
        $this->db->where('nsales.id', $uid);
        return $this->db->get();
    }

    function get_nsales_by_no($uid,$year=null)
    {
        $this->db->select('nsales.id, nsales.no, nsales.contract, nsales.contract_no, nsales.dates, nsales.docno, nsales.customer, customer.prefix, customer.name, customer.address, customer.phone1, customer.phone2,
                           customer.city, nsales.currency, nsales.user, nsales.log, nsales.desc, nsales.shipping_date,
                           nsales.status, nsales.tax, nsales.p1, nsales.p2, nsales.total, nsales.notes, nsales.discount_desc,
                           nsales.costs, nsales.discount, nsales.approved');

        $this->db->from('nsales, customer');
        $this->db->where('nsales.customer = customer.id');
        $this->db->where('nsales.no', $uid);
        if ($year){ $this->db->where('YEAR(dates)', $year); }else { $this->db->where('YEAR(dates)', $this->year); }
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

    function valid_no($no,$year)
    {
        $this->db->where('no', $no);
        $this->db->where('YEAR(dates)', $year);
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
        $this->db->select('nsales.id, nsales.no, nsales.contract, nsales.contract_no, nsales.dates, nsales.docno, customer.prefix, customer.name, customer.address, customer.phone1, customer.phone2,
                           customer.city, nsales.currency, nsales.user, nsales.log, nsales.desc,
                           nsales.status, nsales.tax, nsales.p1, nsales.p2, nsales.discount, nsales.total, nsales.notes, nsales.shipping_date,
                           nsales.costs, nsales.approved');

        $this->db->from('nsales, customer');
        $this->db->where('nsales.customer = customer.id');
        $this->db->where("nsales.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"nsales.currency");
        $this->cek_null($status,"nsales.status");

        $this->db->where('nsales.approved', 1);
        $this->db->order_by('nsales.no', 'asc');
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

        $this->db->from('nsales, customer');
        $this->db->where('nsales.customer = customer.id');
        $this->db->where("nsales.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"nsales.currency");
        $this->cek_null($status,"nsales.status");
        $this->db->where('nsales.approved', 1);
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>