<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_adjustment_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'stock_adjustment';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_stock_adjustment($limit, $offset)
    {
        $this->db->select('id, no, dates, currency, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->db->order_by('no', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no=null,$date=null)
    {
        $this->db->select('id, no, dates, currency, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->cek_null($no,"no");
        $this->cek_null($date,"dates");
        return $this->db->get();
    }

    function get_list($no=null)
    {
        $this->db->select('id, no, dates, currency, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->cek_null($no,"no");
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
    
    function get_stock_adjustment_by_id($uid)
    {
        $this->db->select('id, no, dates, currency, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }

    function get_stock_adjustment_by_no($uid)
    {
        $this->db->select('id, no, dates, currency, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('no', $uid);
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

    function report($start,$end)
    {
        $this->db->select('id, no, dates, currency, desc, staff, user, approved, log');
        $this->db->from($this->table);
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('approved', 1);
        $this->db->order_by('no', 'asc');
        return $this->db->get();
    }
    
    function report_category($start,$end)
    {
        $this->db->select('stock_adjustment.id, stock_adjustment.no, stock_adjustment.dates, stock_adjustment.currency, stock_adjustment.desc, stock_adjustment.staff, stock_adjustment.user, stock_adjustment.approved, stock_adjustment.log,
                           stock_adjustment_item.type, stock_adjustment_item.product, stock_adjustment_item.qty, stock_adjustment_item.price');
        
        $this->db->from("stock_adjustment,stock_adjustment_item");
        $this->db->where("stock_adjustment.no = stock_adjustment_item.stock_adjustment");
        $this->db->where("stock_adjustment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('stock_adjustment.approved', 1);
        $this->db->order_by('stock_adjustment.no', 'asc');
        return $this->db->get();
    }

//    =========================================  REPORT  =================================================================

}

?>