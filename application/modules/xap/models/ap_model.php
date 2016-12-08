<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ap';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_ap($limit, $offset)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.dates, vendor.prefix, vendor.name, ap.user, ap.acc, ap.status,
                           ap.amount, ap.notes, ap.desc, ap.log, ap.currency, ap.approved');
        
        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->db->order_by('ap.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$vendor,$date)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.dates, vendor.prefix, vendor.name, ap.user, ap.acc, ap.status,
                           ap.amount, ap.notes, ap.desc, ap.log, ap.currency, ap.approved');

        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->cek_null($no,"ap.no");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($date,"ap.dates");
        return $this->db->get();
    }

    function get_ap_list($currency,$acc,$vendor)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.dates, ap.vendor, vendor.prefix, vendor.name, ap.user, ap.acc, ap.status,
                           ap.amount, ap.notes, ap.desc, ap.log, ap.currency, ap.approved');

        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');

        $this->db->where('ap.currency', $currency);
        $this->db->where('ap.acc', $acc);
        $this->db->where('ap.vendor', $vendor);
        $this->db->where('status', 0);
        $this->db->where('approved', 1);

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
    
    function get_ap_by_id($uid)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.dates, vendor.prefix, vendor.name, ap.user, ap.acc, ap.status,
                           ap.amount, ap.notes, ap.desc, ap.log, ap.currency, ap.approved');

        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->db->where('ap.id', $uid);
        return $this->db->get();
    }

    function get_ap_by_no($uid)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.dates, vendor.prefix, vendor.name, ap.user, ap.acc, ap.status,
                           ap.amount, ap.notes, ap.desc, ap.log, ap.currency, ap.approved');

        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->db->where('ap.no', $uid);
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

    function report($vendor,$start,$end,$acc,$cur,$status)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.dates, vendor.prefix, vendor.name, ap.user, ap.acc, ap.status,
                           ap.amount, ap.notes, ap.desc, ap.log, ap.currency, ap.approved');

        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap.acc");
        $this->cek_null($cur,"ap.currency");
        $this->cek_null($status,"ap.status");
        $this->between($start,$end);
        $this->db->where('ap.approved', 1);
        return $this->db->get();
    }

    function total($vendor,$start,$end,$acc,$cur,$status)
    {
        $this->db->select_sum('amount');
        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($acc,"ap.acc");
        $this->cek_null($cur,"ap.currency");
        $this->cek_null($status,"ap.status");
        $this->between($start,$end);
        $this->db->where('ap.approved', 1);
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ap.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

    

}

?>