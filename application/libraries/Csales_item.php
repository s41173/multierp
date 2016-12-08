<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csales_item {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->pro = $this->ci->load->library('products_lib');
    }

    private $ci;

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('csales_item')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }


    function get_last_item($po)
    {
        $this->ci->db->select('id, sales, product, qty, price, tax, amount');
        $this->ci->db->from('csales_item');
        $this->ci->db->where('sales', $po);
        $this->ci->db->order_by('id', 'asc');
        return $this->ci->db->get();
    }

    function valid_item($po,$pro)
    {
      $this->ci->db->where('sales', $po);
      $this->ci->db->where('product', $pro);
      $query = $this->ci->db->get('csales_item')->num_rows();
      if ($query > 0) { return TRUE; } else { return FALSE; }
    }

    function combo($no)
    {
        $this->ci->db->select('product');
        $this->ci->db->where('sales', $no);
        $val = $this->ci->db->get('csales_item')->result();
        $data['options'][''] = '-- Select --';
        foreach($val as $row){$data['options'][$row->product] = $this->pro->get_name($row->product);}
        return $data;
    }

}