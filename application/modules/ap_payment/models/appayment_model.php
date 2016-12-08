<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Appayment_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ap_payment';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('ap_payment.id, ap_payment.no, ap_payment.tax, ap_payment.type, ap_payment.check_no, ap_payment.due, ap_payment.dates, vendor.prefix, vendor.name, ap_payment.user,
                           ap_payment.amount, ap_payment.over, ap_payment.acc, ap_payment.currency, ap_payment.approved');
        
        $this->db->from('ap_payment, vendor');
        $this->db->where('ap_payment.vendor = vendor.id');
        $this->db->order_by('ap_payment.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$vendor,$date)
    {
        $this->db->select('ap_payment.id, ap_payment.no, ap_payment.tax, ap_payment.type, ap_payment.docno, ap_payment.due, ap_payment.check_no, ap_payment.dates, vendor.prefix, vendor.name, ap_payment.user,
                           ap_payment.amount, ap_payment.over, ap_payment.acc, ap_payment.currency, ap_payment.approved');

        $this->db->from('ap_payment, vendor');
        $this->db->where('ap_payment.vendor = vendor.id');
        $this->cek_null($no,"ap_payment.no");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($date,"ap_payment.dates");
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
//        $this->db->where('tax', 0);
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function counter_voucher_no($type)
    {
        $this->db->select_max('voucher_no');
        $this->db->where('tax', $type);
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['voucher_no'];
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
    
    function get_ap_payment_by_id($uid)
    {
        $this->db->select('ap_payment.id, ap_payment.no, ap_payment.type, ap_payment.voucher_no, ap_payment.tax, ap_payment.docno, ap_payment.check_no, ap_payment.check_type, ap_payment.check_acc, ap_payment.check_acc_no, ap_payment.post_dated, ap_payment.account, ap_payment.due,
                           ap_payment.dates, ap_payment.vendor, vendor.prefix, vendor.name, ap_payment.user, ap_payment.discount, ap_payment.late, ap_payment.credit_over, ap_payment.over, ap_payment.over_stts,
                           ap_payment.amount, ap_payment.acc, ap_payment.rate, ap_payment.currency, ap_payment.approved');

        $this->db->from('ap_payment, vendor');
        $this->db->where('ap_payment.vendor = vendor.id');
        $this->db->where('ap_payment.id', $uid);
        return $this->db->get();
    }

    function get_ap_payment_by_no($uid)
    {
        $this->db->select('ap_payment.id, ap_payment.no, ap_payment.type, ap_payment.voucher_no, ap_payment.tax, ap_payment.docno, ap_payment.check_no, ap_payment.check_type, ap_payment.check_acc, ap_payment.check_acc_no, ap_payment.post_dated, ap_payment.account, ap_payment.due, ap_payment.vendor,
                           ap_payment.dates, vendor.prefix, vendor.name, ap_payment.user, ap_payment.discount, ap_payment.late, ap_payment.credit_over, ap_payment.over, ap_payment.over_stts,
                           ap_payment.amount, ap_payment.acc, ap_payment.rate, ap_payment.currency, ap_payment.approved');

        $this->db->from('ap_payment, vendor');
        $this->db->where('ap_payment.vendor = vendor.id');
        $this->db->where('ap_payment.no', $uid);
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
    
    function valid_voucher($no,$type)
    {
        $this->db->where('voucher_no', $no);
        $this->db->where('tax', $type);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function validating_voucher($no,$type,$po)
    {
        $this->db->where('voucher_no', $no);
        $this->db->where('tax', $type);
        $this->db->where_not_in('no', $po);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
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
        $this->db->select('ap_payment.id, ap_payment.no, ap_payment.type, ap_payment.voucher_no, ap_payment.tax, ap_payment.docno, ap_payment.check_no, ap_payment.check_acc, ap_payment.check_acc_no, ap_payment.post_dated, ap_payment.dates, vendor.prefix, vendor.name, ap_payment.user,
                           ap_payment.amount, ap_payment.discount, ap_payment.late, ap_payment.over, ap_payment.over_stts, ap_payment.credit_over, ap_payment.acc, ap_payment.rate, ap_payment.currency, ap_payment.approved, ap_payment.log');

        $this->db->from('ap_payment, vendor');
        $this->db->where('ap_payment.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap_payment.acc");
        $this->cek_null($cur,"ap_payment.currency");
        $this->db->where('ap_payment.approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }

    function total($vendor,$start,$end,$acc,$cur)
    {
        $this->db->select_sum('amount');
        $this->db->select_sum('late');
        $this->db->select_sum('discount');
        
        $this->db->from('ap_payment, vendor');
        $this->db->where('ap_payment.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap_payment.acc");
        $this->cek_null($cur,"ap_payment.currency");
        $this->db->where('ap_payment.approved', 1);
        $this->between($start,$end);
        return $this->db->get()->row_array();
    }
    
    function total_chart($cur,$month,$year)
    {
        $this->db->select_sum('amount');
        $this->db->select_sum('late');
        $this->db->select_sum('discount');
        $this->db->select_sum('over');
        
        $this->db->from('ap_payment');
        $this->cek_null($cur,"ap_payment.currency");
        $this->db->where('ap_payment.approved', 1); 
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        
        $res = $this->db->get()->row_array();
        return intval($res['amount']+$res['over']);
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ap_payment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

}

?>