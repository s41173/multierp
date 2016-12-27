<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_last_ar_between($val1,$val2)
    {
        $this->db->select('SUM(p2) AS total');
        $this->db->from('sales');
        $this->db->where("dates BETWEEN (NOW() - INTERVAL ".$val1." DAY) AND (NOW() - INTERVAL ".$val2." DAY) ");
        $this->db->where('approved', 1);
        $this->db->where('currency', 'IDR');
        $this->db->where('status', 0);
        return $this->db->get(); 
    }

    function get_last_ar($val1)
    {
        $this->db->select('SUM(p2) AS total');
        $this->db->from('sales');
        $this->db->where("dates <= (NOW() - INTERVAL ".$val1." DAY)");
        $this->db->where('approved', 1);
        $this->db->where('currency', 'IDR');
        $this->db->where('status', 0);
        return $this->db->get();
    }

    function get_ar_list()
    {
        $this->db->select('no, dates, customer, p2');
        $this->db->from('sales');
        $this->db->where('approved', 1);
        $this->db->where('currency', 'IDR');
        $this->db->where('status', 0);
        return $this->db->get();
    }

    // ============== purchase ===================================

    function get_last_ap_between($val1,$val2)
    {
        $this->db->select('SUM(p2) AS total');
        $this->db->from('purchase');
        $this->db->where("dates BETWEEN (NOW() - INTERVAL ".$val1." DAY) AND (NOW() - INTERVAL ".$val2." DAY) ");
        $this->db->where('approved', 1);
        $this->db->where('currency', 'IDR');
        $this->db->where('status', 0);
        return $this->db->get();
    }

    function get_last_ap($val1)
    {
        $this->db->select('SUM(p2) AS total');
        $this->db->from('purchase');
        $this->db->where("dates <= (NOW() - INTERVAL ".$val1." DAY)");
        $this->db->where('approved', 1);
        $this->db->where('currency', 'IDR');
        $this->db->where('status', 0);
        return $this->db->get();
    }

    function get_ap_list()
    {
        $this->db->select('no, dates, vendor, p2');
        $this->db->from('purchase');
        $this->db->where('approved', 1);
        $this->db->where('currency', 'IDR');
        $this->db->where('status', 0);
        return $this->db->get();
    }

    // ===================== check in ========================================

    function checkin()
    {
        $this->db->select('check_no, no, bank, currency, dates, due, amount');
        $this->db->from('ar_payment');
//        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('approved', 1);
        $this->db->where('check_no IS NOT NULL', null, false);

        return $this->db->get();
    }


    function checkout($table=null)
    {
        $this->db->select('check_no, no, bank, currency, dates, due, amount');
        $this->db->from($table);
//        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('approved', 1);
        $this->db->where('check_no IS NOT NULL', null, false);

        return $this->db->get();
    }

    function get_min_product()
    {
        $this->db->select('id, brand, category, currency, name, desc, qty, unit, price, vendor');
        $this->db->from('product');
        $this->db->where('qty <=', 0);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

}

?>