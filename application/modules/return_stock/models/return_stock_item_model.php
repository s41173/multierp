<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Return_stock_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'return_stock_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, return_stock, product, qty, price, desc');
        $this->db->from($this->table);
        $this->db->where('return_stock', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function valid_product($product,$po)
    {
        $this->db->from($this->table);
        $this->db->where('return_stock', $po);
        $this->db->where('product', $product);
        $query = $this->db->get()->num_rows();
        if ($query > 0){ return FALSE;} else { return TRUE;}
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('return_stock', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function report($po)
    {
        $this->db->select("$this->table.id, $this->table.return_stock, product.name as product, product.unit, $this->table.qty, $this->table.price, $this->table.desc");
        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.return_stock", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    
    function total($po)
    {
//        $this->db->select_sum("$this->table.qty");
//        $this->db->select_sum("$this->table.price");
        $this->db->from("$this->table");
        $this->db->where("$this->table.return_stock", $po);
        $res = $this->db->get()->result();
        
        $amount = 0;
        foreach ($res as $value) 
        {
           $res1 = intval($value->qty*$value->price); 
           $amount = intval($amount + $res1);
        }
        return $amount;
    }
    

}

?>