<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_adjustment_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'stock_adjustment_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, stock_adjustment, type, product, qty, price, account');
        $this->db->from($this->table);
        $this->db->where('stock_adjustment', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }
    
    function get_item_by_id($id)
    {
        $this->db->select('id, stock_adjustment, type, product, qty, price, account');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get()->row(); 
    }

    function total($po)
    {
        $this->db->select_sum('price');
        $this->db->where('stock_adjustment', $po);
        return $this->db->get($this->table)->row_array();
    }
    
    function total_criteria($po,$type)
    {
//        $this->db->select_sum('price');
        $amount = 0;
        $this->db->where('type', $type);
        $this->db->where('stock_adjustment', $po);
        $res = $this->db->get($this->table)->result();
        foreach ($res as $val)
        {
           $res1 = intval($val->qty*$val->price);
           $amount = $amount + $res1;
        }
        return intval($amount);
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('stock_adjustment', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function report($po)
    {
        $this->db->select("$this->table.id, $this->table.stock_adjustment, $this->table.type, product.name as product, product.unit, $this->table.qty, $this->table.price, $this->table.account");
        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.stock_adjustment", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    
    

}

?>