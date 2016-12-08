<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ar_payment_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, ar_payment, code, cash, no, sid, notes, cost, tax, tax2, amount');
        $this->db->from($this->table);
        $this->db->where('ar_payment', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function get_po_details($po)
    {
        $this->db->select('ar_payment_trans.id, ar_payment_trans.ar_payment, ar_payment_trans.code, ar_payment_trans.cash, ar_payment_trans.no,
                          ar_payment_trans.sid, ar_payment_trans.notes as transnotes,  ar_payment_trans.cost, ar_payment_trans.tax2, ar_payment_trans.tax, ar_payment_trans.amount,
                          sales.docno, sales.notes, sales.dates');

        $this->db->from("ar_payment_trans, sales");
        $this->db->where('ar_payment_trans.no = sales.no');
        $this->db->where('ar_payment_trans.ar_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function get_details_based_id($po)
    {
        $this->db->select('ar_payment_trans.id, ar_payment_trans.ar_payment, ar_payment_trans.code, ar_payment_trans.cash, ar_payment_trans.no,
                          ar_payment_trans.sid, ar_payment_trans.notes as transnotes, ar_payment_trans.cost, ar_payment_trans.tax2, ar_payment_trans.tax, ar_payment_trans.amount,
                          sales.docno, sales.notes, sales.dates');

        $this->db->from("ar_payment_trans, sales");
        $this->db->where('ar_payment_trans.sid = sales.id');
        $this->db->where('ar_payment_trans.ar_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function report($customer,$start,$end,$acc,$cur)
    {
        $this->db->select('ar_payment.id, ar_payment.no, ar_payment.docno, ar_payment.check_no, ar_payment.dates, customer.prefix, customer.name, ar_payment.user,
                           ar_payment.acc, ar_payment.currency, ar_payment.approved, ar_payment.log,
                           ar_payment_trans.code, ar_payment_trans.sid, ar_payment_trans.notes as transnotes, ar_payment_trans.no as transno, ar_payment_trans.cost, ar_payment_trans.tax2, ar_payment_trans.tax, ar_payment_trans.amount');

        $this->db->from('ar_payment, customer, ar_payment_trans');
        $this->db->where('ar_payment.customer = customer.id');
        $this->db->where('ar_payment.no = ar_payment_trans.ar_payment');
        
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"ar_payment.acc");
        $this->cek_null($cur,"ar_payment.currency");
        $this->db->where('ar_payment.approved', 1);
        $this->between($start,$end);
        return $this->db->get();
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("ar_payment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

    function get_item_based_po($arpayment,$no,$code)
    {
        $this->db->select('id, ar_payment, code, cash, no, sid, cost, tax, tax2, amount');
        $this->db->from($this->table);
        $this->db->where('no', $no);
        $this->db->where('code', $code);
        $this->db->where('ar_payment', $arpayment);
        $query = $this->db->get()->num_rows();
        if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function total($po)
    {
        $this->db->select_sum('cost');
        $this->db->select_sum('tax');
        $this->db->select_sum('tax2');
        $this->db->select_sum('amount');
        $this->db->where('ar_payment', $po);
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