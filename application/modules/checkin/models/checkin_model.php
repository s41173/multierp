<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Checkin_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    private function table($val)
    {
        if ($val == 'sales') { $val = 'ar_payment'; } elseif ($val == 'nsales') { $val = 'nar_payment'; } return $val;
    }
    
    function search($no=null,$start=null,$end=null,$type=null)
    {
        $this->db->select('check_no, no, bank, currency, dates, due, amount');
        $this->db->from($this->table($type));
        $this->cek_null($no,"check_no");
        $this->between($start,$end);
//        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('approved', 1);
        $this->db->where('check_no IS NOT NULL', null, false);

        return $this->db->get();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function report($start,$end,$type)
    {
        $this->db->select('check_no, no, bank, currency, dates, due, amount, customer');
        $this->db->from($this->table($type));
        $this->between($start,$end);
//        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('approved', 1);
        $this->db->where('check_no IS NOT NULL', null, false);

        return $this->db->get();
    }

}

?>