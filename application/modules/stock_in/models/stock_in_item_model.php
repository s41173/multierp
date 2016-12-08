<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_in_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'stock_in_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, stock_in, product, qty');
        $this->db->from($this->table);
        $this->db->where('stock_in', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function valid_item($po,$product)
    {
       $this->db->where('stock_in', $po);
       $this->db->where('product', $product);
       $query = $this->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('stock_in', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function report($po)
    {
        $this->db->select("$this->table.id, $this->table.stock_in, product.name as product, product.unit, $this->table.qty");
        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.stock_in", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    
}

?>