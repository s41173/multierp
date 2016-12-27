<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nsales {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;


//    fungsi di panggil ketika ada po yg masih blm approved ketika hendak closing harian
    function cek_approval_so($date,$currency)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $this->ci->db->where('approved', 0);

        $query = $this->ci->db->get('nsales')->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function get_so($no)
    {
        $this->ci->db->select('no, p1, p2, costs, status, total, tax, notes, docno, currency, customer');
        $this->ci->db->where('id', $no);
        $query = $this->ci->db->get('nsales')->row();
        return $query;
    }

    function delete_so_item($so)
    {
        $this->ci->db->where('nsales_id', $so);
        $this->ci->db->delete('nsales_item');
    }

    function update($uid, $users)
    {
        $this->ci->db->where('no', $uid);
        $this->ci->db->update('nsales', $users);
    }
    
    function update_id($uid, $users)
    {
        $this->ci->db->where('id', $uid);
        $this->ci->db->update('nsales', $users);
    }

    function cek_settled($no=null)
    {
        $this->ci->db->select('status');
        $this->ci->db->where('id', $no);
        $query = $this->ci->db->get('nsales')->row();
        if($query->status != 0) { return FALSE; } else { return TRUE; }
    }

    function settled_so($uid, $users)
    {
        $this->ci->db->where('id', $uid);
        $this->ci->db->update('nsales', $users);
    }

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('nsales')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('status', 1);
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get('nsales')->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($po)
    {
       $this->ci->db->where('nsales_id', $po);
       $this->ci->db->delete('nsales_item');

       $this->ci->db->where('nsales_no', $po);
       $this->ci->db->delete('nar_installment');

       $this->ci->db->where('no', $po);
       $this->ci->db->delete('nsales');
    }
    
    function get_sum_sales_contract($contract)
    {
       $this->ci->db->select_sum('total'); 
       $this->ci->db->where('contract', 1);
       $this->ci->db->where('contract_no', $contract);
       $query = $this->ci->db->get('nsales')->row_array();
       return $query['total'];
    }

}

/* End of file Property.php */