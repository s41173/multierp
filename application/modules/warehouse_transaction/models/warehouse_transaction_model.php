<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_transaction_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'warehouse_transaction';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_warehouse_transaction($limit, $offset)
    {
        $this->db->select('id, dates, code, currency, product, in, out, balance, price, amount, log');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($product=null,$date=null)
    {
        $this->db->select('id, dates, code, currency, product, in, out, balance, price, amount, log');
        $this->db->from($this->table);
        $this->cek_null($product,"product");
        $this->cek_null($date,"dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter()
    {
        $this->db->select_max('id');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['id'];
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
    
    function get_warehouse_transaction_by_id($uid)
    {
        $this->db->select('id, dates, code, currency, product, in, out, balance, price, amount, log');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function report($product=null,$start=null,$end=null,$currency=null,$type=null)
    {
        $this->db->select("$this->table.id, $this->table.dates, $this->table.code, $this->table.currency, $this->table.product as pid, product.name as product, product.unit, $this->table.in, $this->table.out, $this->table.balance, $this->table.price, $this->table.amount, $this->table.log");
        $this->db->from("$this->table, product");
        $this->db->where($this->table.'.product = product.id');
        $this->cek_null($product,"$this->table.product");
        $this->cek_null($currency,"$this->table.currency");
        $this->between($start,$end);
        $this->db->order_by('dates', 'desc');
        return $this->db->get();
    }

    function total($product=null,$start=null,$end=null,$currency=null)
    {
        $this->db->select_sum("$this->table.price");
        $this->db->select_sum("$this->table.amount");
        $this->db->from("$this->table, product");
        $this->db->where($this->table.'.product = product.id');
        $this->cek_null($product,"$this->table.product");
        $this->cek_null($currency,"$this->table.currency");
        $this->between($start,$end);
        $this->db->order_by('dates', 'desc');
        return $this->db->get()->row_array();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null){ return $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");}
        else { return null; }
    }

}

?>