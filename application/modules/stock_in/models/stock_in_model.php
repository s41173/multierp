<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_in_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'stock_in';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('stock_in.id, stock_in.no, stock_in.dates, stock_in.purchase, stock_in.desc, stock_in.staff, stock_in.user,
                           stock_in.approved, stock_in.log');
        
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no=null,$date=null)
    {
        $this->db->select('stock_in.id, stock_in.no, stock_in.dates, stock_in.purchase, stock_in.desc, stock_in.staff, stock_in.user,
                           stock_in.approved, stock_in.log');

        $this->db->from($this->table);
        $this->cek_null($no,"purchase");
        $this->cek_null($date,"dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    { if ($val == ""){return null;} else {return $this->db->where($field, $val);} }

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
    
    function get_stock_in_by_id($uid)
    {
        $this->db->select('id, no, dates, purchase, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }

    function get_stock_in_by_no($uid)
    {
        $this->db->select('id, no, dates, purchase, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('no', $uid);
        return $this->db->get();
    }

    function get_stock_in_by_po($uid)
    {
        $this->db->select('id, no, dates, purchase, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('purchase', $uid);
        return $this->db->get();
    }


    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function update_no($uid, $users)
    {
        $this->db->where('no', $uid);
        $this->db->update($this->table, $users);
    }

    function valid_no($no)
    {
        $this->db->where('no', $no);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function valid_po($no)
    {
        $this->db->where('purchase', $no);
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

    function report($start,$end)
    {
        $this->db->select('id, no, dates, purchase, desc, staff, user, approved, log');
        $this->db->from('stock_in');
        $this->db->where('approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }


    function get_purchase_list($po=null)
    {
        $this->db->select('id, purchase_id, product, qty, price, tax, amount');
        $this->db->from('purchase_item');
        $this->db->where('purchase_id', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function report_transaction($start,$end)
    {
        $this->db->select('stock_in.id, stock_in.no, stock_in.dates, stock_in.purchase,
                           stock_in_item.product, stock_in_item.qty');
        
        $this->db->from('stock_in, stock_in_item');
        $this->db->where('stock_in.no = stock_in_item.stock_in');
        $this->db->where("stock_in.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('stock_in.approved', 1);
        $this->db->order_by('stock_in.no', 'asc');
        return $this->db->get();
    }

}

?>