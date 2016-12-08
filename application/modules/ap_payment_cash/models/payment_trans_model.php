<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'payment_trans_cash';
    
    function get_last_item($po)
    {
        $this->db->select('id, ap_payment, code, no, amount');
        $this->db->from($this->table);
        $this->db->where('ap_payment', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function get_po_details($po)
    {
        $this->db->select('payment_trans_cash.id, payment_trans_cash.ap_payment, payment_trans_cash.code, payment_trans_cash.no, payment_trans_cash.amount,
                          ap.docno, ap.notes, ap.dates');

        $this->db->from("payment_trans_cash, ap");
        $this->db->where('payment_trans_cash.no = ap.no');
        $this->db->where('payment_trans_cash.ap_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function get_item_based_po($no,$code)
    {
        $this->db->select('id, ap_payment, code, no, amount');
        $this->db->from($this->table);
        $this->db->where('no', $no);
        $this->db->where('code', $code);
        $query = $this->db->get()->num_rows();
        if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function total($po)
    {
        $this->db->select_sum('amount');
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