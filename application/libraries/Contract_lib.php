<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'contract';
    }

    private $ci,$table;


//    fungsi di panggil ketika ada contract yg masih blm approved ketika hendak closing harian
    function cek_approval_contract($contract)
    {
        $this->ci->db->where('no', $contract);
        $this->ci->db->where('approved', 1);

        $query = $this->ci->db->get($this->table)->num_rows();
        if($query > 0) { return TRUE; } else { return FALSE; }
    }
    
    function cek_contract_amount($contract,$sales_amt)
    {
        $this->ci->db->where('no', $contract);
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get($this->table)->row();
        if($query->balance < $sales_amt) { return FALSE; }else { return TRUE; }
    }

    function get_contract_details($contract)
    {
        $this->ci->db->select('id, no, type, customer, notes, dates, due, user, currency, amount, tax, balance, status, approved, log');
        $this->ci->db->where('no', $contract);
        $query = $this->ci->db->get($this->table)->row();
        return $query;
    }
    
    function get_contract_customer($contract)
    {
        $this->ci->db->select('id, no, type, customer, notes, dates, due, user, currency, amount, tax, balance, status, approved, log');
        $this->ci->db->where('no', $contract);
        $query = $this->ci->db->get($this->table)->row();
        return $query->customer;
    }

    function update_balance($no, $amount,$type=0)
    {
        if ($this->cek_approval_contract($no) == TRUE)
        {
           $balance = $this->get_contract_details($no);
           $balance = $balance->balance;
        
           if ($type == 0){ $balance = intval($balance-$amount); }else { $balance = intval($balance+$amount); }
        
           $value = array('balance' => $balance);
           $this->ci->db->where('no', $no);
           $this->ci->db->update($this->table, $value); 
           $balance = null;
           
           // update status
           $balance = $this->get_contract_details($no);
           $balance = $balance->balance;
           if ($balance <= 0){ $stts = 'S'; }else { $stts = 'C'; }
           
           $value1 = array('status' => $stts);
           $this->ci->db->where('no', $no);
           $this->ci->db->update($this->table, $value1); 
        }  
    }

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('sales')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    } 
    
}

/* End of file Property.php */