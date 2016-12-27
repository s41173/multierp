<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_out_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'stock_out_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, stock_out, product, qty, price, desc');
        $this->db->from($this->table);
        $this->db->where('stock_out', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function total($po)
    {
        $this->db->select('sum(price*qty) as amount');
        $this->db->where('stock_out', $po);
        $res = $this->db->get($this->table)->row_array();
        return intval($res['amount']);
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('stock_out', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function report($po)
    {
        $this->db->select("$this->table.id, $this->table.stock_out, $this->table.product as pid, product.name as product, product.unit, $this->table.qty, $this->table.price, $this->table.desc");
        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.stock_out", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    

}

?>