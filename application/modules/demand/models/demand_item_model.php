<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Demand_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'demand_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, demand, product, qty, desc, demand_date, vendor');
        $this->db->from($this->table);
        $this->db->where('demand', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function total($po)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->where('demand', $po);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('demand', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function report($po)
    {
        $this->db->select("$this->table.id, $this->table.demand, product.name as product, product.unit, $this->table.qty, 
                           $this->table.desc, $this->table.demand_date, $this->table.vendor, vendor.prefix, vendor.name, vendor.phone1");
        $this->db->from("$this->table,product,vendor");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.vendor = vendor.id");
        $this->db->where("$this->table.demand", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    

}

?>