<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Phase_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'phase';
    
    function get_last($po)
    {
        $this->db->select('id, contract, no, dates, amount, status');
        $this->db->from($this->table);
        $this->db->where('contract', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function get_free_phase($po)
    {
        $this->db->from($this->table);
        $this->db->where('contract', $po);
        $this->db->where('status', 0);
        return $this->db->get()->num_rows();
    }

    function total($po)
    {
        $this->db->select_sum('amount');
        $this->db->from('phase');
        $this->db->where('contract', $po);
        $res = $this->db->get()->row_array();
        return intval($res['amount']);
    }
    
    function valid_part($part,$po)
    {
        $this->db->from($this->table);
        $this->db->where('contract', $po);
        $this->db->where('no', $part);
        return $this->db->get()->num_rows();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('contract', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>