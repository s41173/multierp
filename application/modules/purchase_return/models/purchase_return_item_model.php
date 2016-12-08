<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_return_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'purchase_return_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, purchase_return_id, product, qty, price, tax, amount');
        $this->db->from($this->table);
        $this->db->where('purchase_return_id', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function total($po)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->where('purchase_return_id', $po);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('purchase_return_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function valid_item($product,$po)
    {
        $this->db->where('product', $product);
        $this->db->where('purchase_return_id', $po);
        $query = $this->db->get($this->table)->num_rows();
        if ($query > 0){ return FALSE;} else { return TRUE;}
    }
    

}

?>