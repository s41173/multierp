<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'car_payment_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, ar_payment, code, no, amount');
        $this->db->from($this->table);
        $this->db->where('ar_payment', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function get_po_details($po)
    {
        $this->db->select('car_payment_trans.id, car_payment_trans.ar_payment, car_payment_trans.code, car_payment_trans.no, car_payment_trans.amount,
                          csales.docno, csales.notes, csales.dates');

        $this->db->from("car_payment_trans, csales");
        $this->db->where('car_payment_trans.no = csales.no');
        $this->db->where('car_payment_trans.ar_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function get_last_trans($po,$code)
    {
        $this->db->select('id, ar_payment, code, no, amount');
        $this->db->from($this->table);
        $this->db->where('ar_payment', $po);
        $this->db->where('code', $code);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function get_item_based_po($arpayment,$no,$code)
    {
        $this->db->select('id, ar_payment, code, no, amount');
        $this->db->from($this->table);
        $this->db->where('no', $no);
        $this->db->where('code', $code);
        $this->db->where('ar_payment', $arpayment);
        $query = $this->db->get()->num_rows();
        if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function total_so($po)
    {
        $this->db->select_sum('amount');
        $this->db->where('ar_payment', $po);
        $this->db->where('code', 'CSO');
        return $this->db->get($this->table)->row_array();
    }
    
    function total_sr($po)
    {
        $this->db->select_sum('amount');
        $this->db->where('ar_payment', $po);
        $this->db->where('code', 'SR');
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_payment($uid)
    {
        $this->db->where('ar_payment', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>