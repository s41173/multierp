<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Narpayment_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'nar_payment';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('nar_payment.id, nar_payment.no, nar_payment.check_no, nar_payment.dates, customer.prefix, customer.name, nar_payment.user,
                           nar_payment.amount, nar_payment.acc, nar_payment.account, nar_payment.currency, nar_payment.approved');
        
        $this->db->from('nar_payment, customer');
        $this->db->where('nar_payment.customer = customer.id');
        $this->db->order_by('nar_payment.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$date)
    {
        $this->db->select('nar_payment.id, nar_payment.no, nar_payment.docno, nar_payment.check_no, nar_payment.dates, customer.prefix, customer.name, nar_payment.user,
                           nar_payment.amount, nar_payment.acc, nar_payment.account, nar_payment.currency, nar_payment.approved');

        $this->db->from('nar_payment, customer');
        $this->db->where('nar_payment.customer = customer.id');
        $this->cek_null($no,"nar_payment.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"nar_payment.dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter_no()
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
    
    function get_nar_payment_by_id($uid)
    {
        $this->db->select('nar_payment.id, nar_payment.no, nar_payment.docno, nar_payment.check_no, nar_payment.bank, nar_payment.due,
                           nar_payment.dates, nar_payment.customer, customer.prefix, customer.name, nar_payment.user,
                           nar_payment.amount, nar_payment.acc, nar_payment.account, nar_payment.currency, nar_payment.approved');

        $this->db->from('nar_payment, customer');
        $this->db->where('nar_payment.customer = customer.id');
        $this->db->where('nar_payment.id', $uid);
        return $this->db->get();
    }

    function get_nar_payment_by_no($uid)
    {
        $this->db->select('nar_payment.id, nar_payment.no, nar_payment.docno, nar_payment.check_no, nar_payment.bank, nar_payment.due, nar_payment.customer,
                           nar_payment.dates, customer.prefix, customer.name, nar_payment.user,
                           nar_payment.amount, nar_payment.acc, nar_payment.account, nar_payment.currency, nar_payment.approved');

        $this->db->from('nar_payment, customer');
        $this->db->where('nar_payment.customer = customer.id');
        $this->db->where('nar_payment.no', $uid);
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

    function report($customer,$start,$end,$acc,$cur)
    {
        $this->db->select('nar_payment.id, nar_payment.no, nar_payment.docno, nar_payment.check_no, nar_payment.bank, nar_payment.due, nar_payment.dates, customer.prefix, customer.name, nar_payment.user,
                           nar_payment.amount, nar_payment.acc, nar_payment.account, nar_payment.currency, nar_payment.approved, nar_payment.log');

        $this->db->from('nar_payment, customer');
        $this->db->where('nar_payment.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"nar_payment.acc");
        $this->cek_null($cur,"nar_payment.currency");
        $this->db->where('nar_payment.approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }

    function total($customer,$start,$end,$acc,$cur)
    {
        $this->db->select_sum('amount');
        $this->db->from('nar_payment, customer');
        $this->db->where('nar_payment.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"nar_payment.acc");
        $this->cek_null($cur,"nar_payment.currency");
        $this->db->where('nar_payment.approved', 1);
        $this->between($start,$end);
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("nar_payment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

}

?>