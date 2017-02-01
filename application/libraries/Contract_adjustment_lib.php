<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract_adjustment_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'contract_adjustment';
    }

    private $ci,$table;


//    fungsi di panggil ketika ada contract yg masih blm approved ketika hendak closing harian
    function cek_contract($contract)
    {
        $this->ci->db->where('contract_no', $contract);
        $this->ci->db->where('approved', 1);

        $query = $this->ci->db->get($this->table)->num_rows();
        if($query > 0) { 
            $res = $this->ci->db->get($this->table)->row();
            return 'COA-00'.$res->no;
        } else { return '-'; }
    }
    
    function cek_contract_amount($contract,$sales_amt)
    {
        $this->ci->db->where('no', $contract);
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get($this->table)->row();
        if($query->balance < $sales_amt) { return FALSE; }else { return TRUE; }
    }

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('sales')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    } 
    
}

/* End of file Property.php */