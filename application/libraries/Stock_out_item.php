<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_out_item {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->pro = $this->ci->load->library('products_lib');
    }

    private $ci,$pro;

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('stock_out_item')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function combo($no)
    {
        $this->ci->db->select('product');
        $this->ci->db->where('stock_out', $no);
        $val = $this->ci->db->get('stock_out_item')->result();
        $data['options'][''] = '-- Select --';
        foreach($val as $row){$data['options'][$row->product] = $this->pro->get_name($row->product);}
        return $data;
    }

    function get_currency($no)
    {
        $this->ci->db->select('currency');
        $this->ci->db->where('no', $no);
        $val = $this->ci->db->get('stock_out')->row();
        return $val->currency;
    }
    
    function get_price($product,$no)
    {
        $this->ci->db->select('price');
        $this->ci->db->where('product', $product);
        $this->ci->db->where('stock_out', $no);
        $val = $this->ci->db->get('stock_out_item')->row();
        return $val->price;
    }
    
    function get_qty($product,$no)
    {
        $this->ci->db->select('qty');
        $this->ci->db->where('product', $product);
        $this->ci->db->where('stock_out', $no);
        $val = $this->ci->db->get('stock_out_item')->row();
        return $val->qty;
    }


}