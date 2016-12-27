<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_refund_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ar_refund';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('ar_refund.id, ar_refund.no, ar_refund.notes, ar_refund.check_no, ar_refund.dates, customer.prefix, customer.name, ar_refund.user,
                           ar_refund.amount, ar_refund.acc, ar_refund.currency, ar_refund.approved');
        
        $this->db->from('ar_refund, customer');
        $this->db->where('ar_refund.customer = customer.id');
        $this->db->order_by('ar_refund.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$date)
    {
        $this->db->select('ar_refund.id, ar_refund.no, ar_refund.notes, ar_refund.check_no, ar_refund.dates, customer.prefix, customer.name, ar_refund.user,
                           ar_refund.amount, ar_refund.acc, ar_refund.currency, ar_refund.approved');

        $this->db->from('ar_refund, customer');
        $this->db->where('ar_refund.customer = customer.id');
        $this->cek_null($no,"ar_refund.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"ar_refund.dates");
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
    
    function get_ar_refund_by_id($uid)
    {
        $this->db->select('ar_refund.id, ar_refund.no, ar_refund.notes, ar_refund.check_no, ar_refund.bank, ar_refund.due,
                           ar_refund.dates, ar_refund.customer, customer.prefix, customer.name, ar_refund.user,
                           ar_refund.amount, ar_refund.acc, ar_refund.currency, ar_refund.approved');

        $this->db->from('ar_refund, customer');
        $this->db->where('ar_refund.customer = customer.id');
        $this->db->where('ar_refund.id', $uid);
        return $this->db->get();
    }

    function get_ar_refund_by_no($uid)
    {
        $this->db->select('ar_refund.id, ar_refund.no, ar_refund.notes, ar_refund.check_no, ar_refund.bank, ar_refund.due, ar_refund.customer,
                           ar_refund.dates, customer.prefix, customer.name, ar_refund.user,
                           ar_refund.amount, ar_refund.acc, ar_refund.currency, ar_refund.approved');

        $this->db->from('ar_refund, customer');
        $this->db->where('ar_refund.customer = customer.id');
        $this->db->where('ar_refund.no', $uid);
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
        $this->db->select('ar_refund.id, ar_refund.no, ar_refund.notes, ar_refund.check_no, ar_refund.dates, customer.prefix, customer.name, ar_refund.user,
                           ar_refund.amount, ar_refund.acc, ar_refund.currency, ar_refund.approved, ar_refund.log');

        $this->db->from('ar_refund, customer');
        $this->db->where('ar_refund.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"ar_refund.acc");
        $this->cek_null($cur,"ar_refund.currency");
        $this->db->where('ar_refund.approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }

    function total($customer,$start,$end,$acc,$cur)
    {
        $this->db->select_sum('amount');
        $this->db->from('ar_refund, customer');
        $this->db->where('ar_refund.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"ar_refund.acc");
        $this->cek_null($cur,"ar_refund.currency");
        $this->db->where('ar_refund.approved', 1);
        $this->between($start,$end);
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ar_refund.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

}

?>