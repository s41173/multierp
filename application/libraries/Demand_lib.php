<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Demand_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;


//    fungsi di panggil ketika ada po yg masih blm approved ketika hendak closing harian
    function cek_approval_po($date,$currency)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $this->ci->db->where('approved', 0);

        $query = $this->ci->db->get('purchase')->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function get_request($no)
    {
        $this->ci->db->select('id, demand, product, qty, desc, demand_date, vendor');
        $this->ci->db->from('demand_item');
        $this->ci->db->where('demand', $no);
        $this->ci->db->order_by('id', 'asc'); 
        return $this->ci->db->get(); 
    }
    
    function get_buying_price($pname,$no)
    {
        $this->ci->db->select('price');
        $this->ci->db->where('purchase_id', $no);
        $this->ci->db->where('product', $pname);
        $query = $this->ci->db->get('purchase_item')->row();
        if ($query){ return intval($query->price); }
    }


//    ======================= relation cek  =====================================

    // vendor
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('purchase')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

}

/* End of file Property.php */