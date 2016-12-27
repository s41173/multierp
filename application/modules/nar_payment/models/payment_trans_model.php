<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'nar_payment_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, nar_payment, cash, code, no, sid, notes, cost, tax, amount');
        $this->db->from($this->table);
        $this->db->where('nar_payment', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }
    
    function report($customer,$start,$end,$acc,$cur)
    {
        $this->db->select('nar_payment.id, nar_payment.no, nar_payment.docno, nar_payment.check_no, nar_payment.dates, customer.prefix, customer.name, nar_payment.user,
                           nar_payment.acc, nar_payment.currency, nar_payment.approved, nar_payment.log,
                           nar_payment_trans.code, nar_payment_trans.sid, nar_payment_trans.notes as transnotes,  nar_payment_trans.no as transno, nar_payment_trans.cost, nar_payment_trans.tax, nar_payment_trans.amount');

        $this->db->from('nar_payment, customer, nar_payment_trans');
        $this->db->where('nar_payment.customer = customer.id');
        $this->db->where('nar_payment.no = nar_payment_trans.nar_payment');
        
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"nar_payment.acc");
        $this->cek_null($cur,"nar_payment.currency");
        $this->db->where('nar_payment.approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }
    
    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("nar_payment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function get_po_details($po)
    {
        $this->db->select('nar_payment_trans.id, nar_payment_trans.nar_payment, nar_payment_trans.code, nar_payment_trans.cash, nar_payment_trans.no, 
                           nar_payment_trans.sid, nar_payment_trans.notes as transnotes, nar_payment_trans.cost, nar_payment_trans.tax, nar_payment_trans.amount,
                          nsales.docno, nsales.notes, nsales.dates');

        $this->db->from("nar_payment_trans, nsales");
        $this->db->where('nar_payment_trans.no = nsales.no');
        $this->db->where('nar_payment_trans.nar_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function get_details_based_id($no)
    {
        $this->db->select('nar_payment_trans.id, nar_payment_trans.nar_payment, nar_payment_trans.code, nar_payment_trans.cash, nar_payment_trans.no, 
                           nar_payment_trans.sid, nar_payment_trans.notes as transnotes, nar_payment_trans.cost, nar_payment_trans.tax, nar_payment_trans.amount,
                          nsales.docno, nsales.notes, nsales.dates');

        $this->db->from("nar_payment_trans, nsales");
        $this->db->where('nar_payment_trans.sid = nsales.id');
        $this->db->where('nar_payment_trans.nar_payment', $no);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function get_item_based_po($narpayment,$no,$code)
    {
        $this->db->select('id, nar_payment, cash, code, no, sid, notes, cost, tax, amount');
        $this->db->from($this->table);
        $this->db->where('no', $no);
        $this->db->where('code', $code);
        $this->db->where('nar_payment', $narpayment);
        $query = $this->db->get()->num_rows();
        if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function total($po)
    {
        $this->db->select_sum('amount');
        $this->db->select_sum('cost');
        $this->db->select_sum('tax');
        $this->db->where('nar_payment', $po);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_payment($uid)
    {
        $this->db->where('nar_payment', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>