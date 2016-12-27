<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'payment_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, ap_payment, code, no, discount, amount');
        $this->db->from($this->table);
        $this->db->where('ap_payment', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function get_last_trans($po,$code)
    {
        $this->db->select('id, ap_payment, code, no, discount, amount');
        $this->db->from($this->table);
        $this->db->where('ap_payment', $po);
        $this->db->where('code', $code);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function get_po_details($po)
    {
        $this->db->select('payment_trans.id, payment_trans.ap_payment, payment_trans.code, payment_trans.no, payment_trans.discount, payment_trans.amount,
                          purchase.docno, purchase.notes, purchase.dates');

        $this->db->from("payment_trans, purchase");
        $this->db->where('payment_trans.no = purchase.no');
        $this->db->where('payment_trans.ap_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function get_printing_details($po)
    {
        $this->db->select('payment_trans.id, payment_trans.ap_payment, payment_trans.code, payment_trans.no, payment_trans.discount, payment_trans.amount,
                          vinyl_purchase.docno, vinyl_purchase.notes, vinyl_purchase.dates');

        $this->db->from("payment_trans, vinyl_purchase");
        $this->db->where('payment_trans.no = vinyl_purchase.no');
        $this->db->where('payment_trans.ap_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function get_item_based_po($no,$code)
    {
        $this->db->select('id, ap_payment, code, no, discount, amount');
        $this->db->from($this->table);
        $this->db->where('no', $no);
        $this->db->where('code', $code);
//        $this->db->where('ap_payment', $ap);
        $query = $this->db->get()->num_rows();
        if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function total($po,$code)
    {
        $this->db->select_sum('amount');
        $this->db->select_sum('discount');
        
        $this->db->where('code', $code);
        $this->db->where('ap_payment', $po);
        return $this->db->get($this->table)->row_array();
    }

    function total_pr($po)
    {
        $this->db->select_sum('amount');
        
        $this->db->where('code', 'PR');
        $this->db->where('ap_payment', $po);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_payment($uid)
    {
        $this->db->where('ap_payment', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>