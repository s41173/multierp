<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Appaymentcash_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ap_payment_cash';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('ap_payment_cash.id, ap_payment_cash.no, ap_payment_cash.tax, ap_payment_cash.check_no, ap_payment_cash.dates, vendor.prefix, vendor.name, ap_payment_cash.user,
                           ap_payment_cash.amount, ap_payment_cash.acc, ap_payment_cash.currency, ap_payment_cash.approved');
        
        $this->db->from('ap_payment_cash, vendor');
        $this->db->where('ap_payment_cash.vendor = vendor.id');
        $this->db->order_by('ap_payment_cash.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$vendor,$acc,$date)
    {
        $this->db->select('ap_payment_cash.id, ap_payment_cash.no, ap_payment_cash.tax, ap_payment_cash.docno, ap_payment_cash.check_no, ap_payment_cash.dates, vendor.prefix, vendor.name, ap_payment_cash.user,
                           ap_payment_cash.amount, ap_payment_cash.acc, ap_payment_cash.currency, ap_payment_cash.approved');

        $this->db->from('ap_payment_cash, vendor');
        $this->db->where('ap_payment_cash.vendor = vendor.id');
        $this->cek_null($no,"ap_payment_cash.no");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap_payment_cash.acc");
        $this->cek_null($date,"ap_payment_cash.dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter_docno()
    {
        $this->db->select_max('docno');
        $this->db->where('tax', 0);
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['docno'];
	$userid = $userid+1;
	return $userid;
    }

     function counter_no()
    {
        $this->db->select_max('no');
        $this->db->where('tax', 0);
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
    
    function get_ap_payment_cash_by_id($uid)
    {
        $this->db->select('ap_payment_cash.id, ap_payment_cash.no, ap_payment_cash.tax, ap_payment_cash.docno, ap_payment_cash.check_no, ap_payment_cash.bank, ap_payment_cash.due,
                           ap_payment_cash.dates, vendor.prefix, vendor.name, ap_payment_cash.user,
                           ap_payment_cash.amount, ap_payment_cash.acc, ap_payment_cash.currency, ap_payment_cash.approved');

        $this->db->from('ap_payment_cash, vendor');
        $this->db->where('ap_payment_cash.vendor = vendor.id');
        $this->db->where('ap_payment_cash.id', $uid);
        return $this->db->get();
    }

    function get_ap_payment_cash_by_no($uid)
    {
        $this->db->select('ap_payment_cash.id, ap_payment_cash.no, ap_payment_cash.tax, ap_payment_cash.docno, ap_payment_cash.check_no, ap_payment_cash.bank, ap_payment_cash.due, ap_payment_cash.vendor,
                           ap_payment_cash.dates, vendor.prefix, vendor.name, ap_payment_cash.user,
                           ap_payment_cash.amount, ap_payment_cash.acc, ap_payment_cash.currency, ap_payment_cash.approved');

        $this->db->from('ap_payment_cash, vendor');
        $this->db->where('ap_payment_cash.vendor = vendor.id');
        $this->db->where('ap_payment_cash.no', $uid);
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

    function cek_no($no, $pid)
    {
        $this->db->where('check_no', $no);
        $this->db->where_not_in('id', $pid);
        $num = $this->db->get($this->table)->num_rows();

        if ($num > 0) { return FALSE; } else { return TRUE; }
    }
    
    function report($vendor,$start,$end,$acc,$cur)
    {
        $this->db->select('ap_payment_cash.id, ap_payment_cash.no, ap_payment_cash.tax, ap_payment_cash.docno, ap_payment_cash.check_no, ap_payment_cash.dates, vendor.prefix, vendor.name, ap_payment_cash.user,
                           ap_payment_cash.amount, ap_payment_cash.acc, ap_payment_cash.currency, ap_payment_cash.approved, ap_payment_cash.log');

        $this->db->from('ap_payment_cash, vendor');
        $this->db->where('ap_payment_cash.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap_payment_cash.acc");
        $this->cek_null($cur,"ap_payment_cash.currency");
        $this->db->where('ap_payment_cash.approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }

    function total($vendor,$start,$end,$acc,$cur)
    {
        $this->db->select_sum('amount');
        $this->db->from('ap_payment_cash, vendor');
        $this->db->where('ap_payment_cash.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap_payment_cash.acc");
        $this->cek_null($cur,"ap_payment_cash.currency");
        $this->db->where('ap_payment_cash.approved', 1);
        $this->between($start,$end);
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ap_payment_cash.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }



}

?>