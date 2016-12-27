<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Csales_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'csales_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, sales, product, qty, price, discount, tax, amount');
        $this->db->from($this->table);
        $this->db->where('sales', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function valid_item($po)
    {
       $this->db->from($this->table);
       $this->db->where('sales', $po);
       $num = $this->db->get()->num_rows();
       if ($num > 0){ return TRUE; } else{ return FALSE; }
    }

    function total($po)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->select_sum('discount');
        $this->db->where('sales', $po);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('sales', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>