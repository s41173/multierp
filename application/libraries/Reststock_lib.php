<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reststock_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->product = new Products_lib();
    }

    private $ci,$product;
    private $table = 'rest_stock';

//  $date = tanggal, $code = PJ/ SJ, $product = product, $in = 0, $out = 0;


    function add($cur='IDR', $date, $warehouse, $product, $qty=0, $userid=0)
    {
        if ($this->get($cur, $warehouse, $product) == TRUE)
        {
           $trans = array('dates' => $date, 'currency' => $cur, 'warehouse_id' => $warehouse, 'product' => $product, 'qty' => $qty, 'userid' => $userid); 
           $this->ci->db->insert($this->table, $trans);
        }
        else { $this->add_qty($cur, $date, $warehouse, $product, $qty, $userid); }
    }
    
    private function add_qty($currency, $date, $warehouse, $product, $qty=0, $userid=0)
    {
        $this->ci->db->from($this->table);
        $this->ci->db->where('currency', $currency);
        $this->ci->db->where('warehouse_id', $warehouse);
        $this->ci->db->where('product', $product);
        $val = $this->ci->db->get()->row(); 
        $qty = intval($val->qty + $qty);
        
        $trans = array('dates' => $date, 'currency' => $currency, 'warehouse_id' => $warehouse, 'product' => $product, 'qty' => $qty, 'userid' => $userid); 
        
        $this->db->where('id', $val->id);
        $this->db->update($this->table, $trans);
    }
    
    private function get($currency,$warehouse,$product)
    {
        $this->ci->db->from($this->table);
        $this->ci->db->where('currency', $currency);
        $this->ci->db->where('warehouse_id', $warehouse);
        $this->ci->db->where('product', $product);
        $val = $this->ci->db->get()->num_rows();
        if ($val > 0){ return FALSE; }else { return TRUE; }
    }
    
}

/* End of file Property.php */