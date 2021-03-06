<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cash_demand_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'cash_demand_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, cash_demand_id, cost, notes, amount');
        $this->db->from($this->table);
        $this->db->where('cash_demand_id', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }
    
    function get_by_id($id)
    {
        $this->db->select('id, cash_demand_id, cost, notes, amount');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        return $this->db->get()->row(); 
    }

    function total($pid)
    {
        $this->db->select_sum('amount');
        $this->db->where('cash_demand_id', $pid);
        return $this->db->get($this->table)->row_array();
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('cash_demand_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users){ $this->db->insert($this->table, $users); }
    
}

?>