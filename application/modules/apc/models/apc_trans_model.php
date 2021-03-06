<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Apc_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'apc_trans';
    
    function get_last_item($po,$type=null)
    {
        $this->db->select('id, apc_id, type, trans_no, cost, notes, staff, amount');
        $this->db->from($this->table);
        $this->db->where('apc_id', $po);
        $this->cek_null($type,"type");
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }
    
    private function cek_null($val,$field)
    { if (isset($val)){ return $this->db->where($field, $val); } }
    
    function get_by_id($id)
    {
        $this->db->select('id, apc_id, type, trans_no, cost, notes, staff, amount');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        return $this->db->get()->row(); 
    }

    function total($pid)
    {
        $this->db->select_sum('amount');
        $this->db->where('apc_id', $pid);
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
        $this->db->where('apc_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>