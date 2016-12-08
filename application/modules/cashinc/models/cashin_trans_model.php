<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cashin_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'cashin_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, cash_id, account_id, balance');
        $this->db->from($this->table);
        $this->db->where('cash_id', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function total($pid)
    {
        $this->db->select_sum('balance');
        $this->db->where('cash_id', $pid);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('cash_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>