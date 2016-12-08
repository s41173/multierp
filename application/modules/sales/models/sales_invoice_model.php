<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_invoice_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'sales_invoice';
    
    function get_last_item($po)
    {
        $this->db->select('id, sales, part, dates, notes, amount, cost');
        $this->db->from($this->table);
        $this->db->where('sales', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function get_salesinvoice_by_id($po)
    {
        $this->db->select('id, sales, part, dates, notes, amount, cost');
        $this->db->from($this->table);
        $this->db->where('id', $po);
        return $this->db->get();
    }

    function total($po)
    {
        $this->db->select_sum('amount');
        $this->db->where('sales', $po);
        return $this->db->get($this->table)->row_array();
    }

    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table);
    }

    function delete_po($uid)
    {
        $this->db->where('sales', $uid);
        $this->db->delete($this->table);
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function valid_part($part,$sales)
    {
        $this->db->where('part', $part);
        $this->db->where('sales', $sales);
        $query = $this->db->get($this->table)->num_rows();
        if ( $query > 0 ){ return FALSE; } else { return TRUE; }
    }
    
}

?>