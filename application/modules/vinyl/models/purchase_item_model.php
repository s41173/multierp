<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'vinyl_purchase_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, purchase_id, product, qty, price, tax, amount');
        $this->db->from('vinyl_purchase_item');
        $this->db->where('purchase_id', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }
    
    function get_by_id($id)
    {
        $this->db->select('id, purchase_id, product, qty, price, tax, amount');
        $this->db->from('vinyl_purchase_item');
        $this->db->where('id', $id);
        return $this->db->get(); 
    }

    function total($po)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->where('purchase_id', $po);
        return $this->db->get($this->table)->row_array();
    }

    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('purchase_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    

}

?>