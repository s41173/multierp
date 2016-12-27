<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'transaction';
    
    function get_last_transaction($po)
    {
        $this->db->select('id, journal, code, no, name, type, amount');
        $this->db->from($this->table);
        $this->db->where('journal', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }


    function get_transaction_type($po,$code)
    {
        $this->db->select('id, journal, code, no, name, type, amount');
        $this->db->from($this->table);
        $this->db->where('journal', $po);
        $this->db->where('code', $code);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function total($po,$type)
    {
        $this->db->select_sum('amount');
        $this->db->where('journal', $po);
        $this->db->where('type', $type);
        return $this->db->get($this->table)->row_array();
    }

    function get_trans_by_id($uid)
    {
        $this->db->select('id, journal, code, no, name, type, amount');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get(); 
    }

    function counter_no()
    {
        $this->db->select_max('no');
        $this->db->where('code', 'GJ');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_journal($uid)
    {
        $this->db->where('journal', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>