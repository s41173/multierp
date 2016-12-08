<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_in_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'stock_in';


    function cek_approval($po)
    {
        $this->ci->db->where('purchase', $po);
        $query = $this->ci->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    // belum di pake

    function get_stockin_based_purchase($no)
    {
        $this->ci->db->select('dates, no');
        $this->ci->db->where('purchase', $no);
        $query = $this->ci->db->get($this->table)->row();
        return $query;
    }

    function cek_settled($no=null)
    {
        $this->ci->db->select('status');
        $this->ci->db->where('no', $no);
        $query = $this->ci->db->get($this->table)->row();
        if($query->status != 0) { return FALSE; } else { return TRUE; }
    }

    function settled_pr($uid, $users)
    {
        $this->ci->db->where('no', $uid);
        $this->ci->db->update($this->table, $users);
    }
    

//    ======================= relation cek  =====================================

    // vendor
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('status', 1);
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get('purchase_return')->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($po)
    {
       $this->ci->db->where('purchase_return_id', $po);
       $this->ci->db->delete('purchase_return_item');

       $this->ci->db->where('no', $po);
       $this->ci->db->delete('purchase_return');
    }

}

/* End of file Property.php */