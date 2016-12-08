<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_return_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'sales_return';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_sales_return($limit, $offset)
    {
        $this->db->select('sales_return.id, sales_return.no, sales_return.sales, sales_return.dates, sales_return.docno, sales_return.user, sales_return.status,
                           sales_return.total, sales_return.balance, sales_return.costs, sales_return.notes, sales_return.approved');
        
        $this->db->from('sales_return');
        $this->db->order_by('sales_return.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$po,$date)
    {
        $this->db->select('sales_return.id, sales_return.no, sales_return.sales, sales_return.dates, sales_return.docno, sales_return.user, sales_return.status,
                           sales_return.total, sales_return.balance, sales_return.costs, sales_return.notes, sales_return.approved');

        $this->db->from('sales_return');
        $this->cek_null($no,"sales_return.no");
        $this->cek_null($po,"sales_return.sales");
        $this->cek_null($date,"sales_return.dates");
        return $this->db->get();
    }

    function get_sales_return_list($currency=null,$customer=null)
    {
        $this->db->select('sales_return.id, sales_return.no, sales_return.sales, sales_return.dates, sales_return.docno, sales_return.user, sales_return.status,
                           sales_return.total, sales_return.balance, sales_return.costs, sales_return.notes, sales_return.approved');

        $this->db->from('sales_return,csales');
        $this->db->where('sales_return.sales = csales.no');
        $this->db->where('sales_return.status', 0);
        $this->db->where('sales_return.approved', 1);
        $this->cek_null($currency,"csales.currency");
        $this->cek_null($customer,"csales.customer");
        
        $this->db->order_by('sales_return.dates', 'asc');
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
    
    function get_sales_return_by_id($uid)
    {
        $this->db->select('sales_return.id, sales_return.no, sales_return.sales, sales_return.dates, sales_return.docno, sales_return.user, sales_return.log,
                           sales_return.status, sales_return.tax, sales_return.balance, sales_return.total, sales_return.notes,
                           sales_return.costs, sales_return.cash, sales_return.approved');

        $this->db->from('sales_return');
        $this->db->where('sales_return.id', $uid);
        return $this->db->get();
    }

    function get_sales_return_by_no($uid)
    {
        $this->db->select('sales_return.id, sales_return.no, sales_return.sales, sales_return.dates, sales_return.docno,
                           sales_return.user, sales_return.log,
                           sales_return.status, sales_return.tax, sales_return.balance, sales_return.total, sales_return.notes,
                           sales_return.costs, sales_return.cash, sales_return.approved');

        $this->db->from('sales_return');
        $this->db->where('sales_return.no', $uid);
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

    function report($cur,$start,$end,$status)
    {
        $this->db->select('sales_return.id, sales_return.no, sales_return.sales, sales_return.dates, sales_return.docno,
                           sales_return.user, sales_return.log,
                           sales_return.status, sales_return.tax, sales_return.balance, sales_return.total, sales_return.notes,
                           sales_return.costs, sales_return.approved');

        $this->db->from('sales_return,csales');
        $this->db->where('sales_return.sales = csales.no');
        $this->db->where("sales_return.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($status,"sales_return.status");
        $this->cek_null($cur,"csales.currency");

        $this->db->where('sales_return.approved', 1);
        $this->db->order_by('sales_return.no', 'asc');
        return $this->db->get();
    }
    
    function total($cur,$start,$end,$status)
    {
        $this->db->select_sum('sales_return.balance');
        $this->db->select_sum('sales_return.tax');
        $this->db->select_sum('sales_return.costs');
        $this->db->select_sum('sales_return.total');

        $this->db->from('sales_return,csales');
        $this->db->where('sales_return.sales = csales.no');
        $this->db->where("sales_return.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($status,"sales_return.status");
        $this->cek_null($cur,"csales.currency");
        $this->db->where('sales_return.approved', 1);
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>