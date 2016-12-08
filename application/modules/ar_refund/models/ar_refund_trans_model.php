<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ar_refund_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ar_refund_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, ar_refund, no, sales_no, ar_payment, balance, over');
        $this->db->from($this->table);
        $this->db->where('ar_refund', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function valid_trans($so,$arrefund)
    {
       $this->db->where('no', $so);
       $this->db->where('ar_refund', $arrefund);
       $query = $this->db->get($this->table)->num_rows();
       if($query > 0) { return FALSE; } else { return TRUE; }
    }


    function total($po)
    {
        $this->db->select_sum('over');
        $this->db->where('ar_refund', $po);
        return $this->db->get($this->table)->row_array();
    }

    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_payment($uid)
    {
        $this->db->where('ar_refund', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>