<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_return_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'purchase_return';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_purchase_return($limit, $offset)
    {
        $this->db->select('purchase_return.id, purchase_return.no, purchase_return.purchase, purchase_return.dates, purchase_return.acc, purchase_return.docno, vendor.prefix, vendor.name, purchase_return.user, purchase_return.status,
                           purchase_return.total, purchase_return.balance, purchase_return.costs, purchase_return.notes, purchase_return.currency, purchase_return.approved');
        
        $this->db->from('purchase_return, vendor');
        $this->db->where('purchase_return.vendor = vendor.id');
        $this->db->order_by('purchase_return.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$po,$vendor,$date)
    {
        $this->db->select('purchase_return.id, purchase_return.no, purchase_return.purchase, purchase_return.dates, purchase_return.acc, purchase_return.docno, vendor.prefix, vendor.name, purchase_return.user, purchase_return.status,
                           purchase_return.total, purchase_return.balance, purchase_return.costs, purchase_return.notes, purchase_return.currency, purchase_return.approved');

        $this->db->from('purchase_return, vendor');
        $this->db->where('purchase_return.vendor = vendor.id');
        $this->cek_null($no,"purchase_return.no");
        $this->cek_null($po,"purchase_return.purchase");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($date,"purchase_return.dates");
        return $this->db->get();
    }

    function get_purchase_return_list($currency=null,$vendor=null)
    {
        $this->db->select('purchase_return.id, purchase_return.no, purchase_return.purchase, purchase_return.dates, purchase_return.acc, purchase_return.docno, vendor.prefix, vendor.name, purchase_return.user, purchase_return.status,
                           purchase_return.total, purchase_return.balance, purchase_return.costs, purchase_return.notes, purchase_return.currency, purchase_return.approved');

        $this->db->from('purchase_return, vendor');
        $this->db->where('purchase_return.vendor = vendor.id');
        $this->db->where('purchase_return.status', 0);
        $this->cek_null($vendor,"purchase_return.vendor");
        $this->cek_null($currency,"purchase_return.currency");
        $this->db->where('purchase_return.approved', 1);
        
        $this->db->order_by('purchase_return.dates', 'asc');
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
    
    function get_purchase_return_by_id($uid)
    {
        $this->db->select('purchase_return.id, purchase_return.no, purchase_return.currency, purchase_return.purchase, purchase_return.dates, purchase_return.acc, purchase_return.docno, vendor.prefix, vendor.name, purchase_return.user, purchase_return.log,
                           purchase_return.status, purchase_return.tax, purchase_return.balance, purchase_return.total, purchase_return.notes, purchase_return.currency,
                           purchase_return.costs, purchase_return.cash, purchase_return.approved');

        $this->db->from('purchase_return, vendor');
        $this->db->where('purchase_return.vendor = vendor.id');
        $this->db->where('purchase_return.id', $uid);
        return $this->db->get();
    }

    function get_purchase_return_by_no($uid)
    {
        $this->db->select('purchase_return.id, purchase_return.no, purchase_return.currency, purchase_return.purchase, purchase_return.dates, purchase_return.acc, purchase_return.docno,
                           vendor.prefix, vendor.name, vendor.address, vendor.city, vendor.phone1, vendor.phone2,
                           purchase_return.user, purchase_return.log,
                           purchase_return.status, purchase_return.tax, purchase_return.balance, purchase_return.total, purchase_return.notes, purchase_return.currency,
                           purchase_return.costs, purchase_return.cash, purchase_return.approved');

        $this->db->from('purchase_return, vendor');
        $this->db->where('purchase_return.vendor = vendor.id');
        $this->db->where('purchase_return.no', $uid);
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

    function report($cur,$vendor,$start,$end,$status,$acc)
    {
        $this->db->select('purchase_return.id, purchase_return.no, purchase_return.purchase, purchase_return.dates, purchase_return.acc, purchase_return.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vendor.city, purchase_return.user, purchase_return.log, purchase_return.currency,
                           purchase_return.status, purchase_return.tax, purchase_return.balance, purchase_return.total, purchase_return.notes,
                           purchase_return.costs, purchase_return.approved');

        $this->db->from('purchase_return, vendor');
        $this->db->where('purchase_return.vendor = vendor.id');
        $this->db->where("purchase_return.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"purchase_return.currency");
        $this->cek_null($status,"purchase_return.status");
        $this->cek_null($acc,"purchase_return.acc");

        $this->db->where('purchase_return.approved', 1);
        $this->db->order_by('purchase_return.no', 'asc');
        return $this->db->get();
    }
    
    function total($cur,$vendor,$start,$end,$status,$acc)
    {
        $this->db->select_sum('balance');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');

        $this->db->from('purchase_return, vendor');
        $this->db->where('purchase_return.vendor = vendor.id');
        $this->db->where("purchase_return.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($status,"purchase_return.status");
        $this->cek_null($cur,"purchase_return.currency");
        $this->cek_null($acc,"purchase_return.acc");
        
        $this->db->where('purchase_return.approved', 1);
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>