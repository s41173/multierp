<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Temporary_stock {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'temporary_stock';

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function valid_product($product,$unit)
    {
       $this->ci->db->where('product', $product);
       $this->ci->db->where('unit', $unit);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return TRUE; } else { return FALSE; }
    }

    function valid_qty($product,$unit)
    {
       $this->ci->db->select('qty');
       $this->ci->db->where('product', $product);
       $this->ci->db->where('unit', $unit);
       $res = $this->ci->db->get($this->table)->row();
       if ($res->qty == 0){ return FALSE; } else { return TRUE; }
    }

    function get_qty($product=null,$unit=null)
    {
        if ($product)
        {
           $this->ci->db->select('qty');
           $this->ci->db->where('product', $product);
           $this->ci->db->where('unit', $unit);
           $res = $this->ci->db->get($this->table)->row();
           return $res->qty;
        }
    }

    function get_unit($product=null)
    {
        if ($product)
        {
           $this->ci->db->select('unit');
           $this->ci->db->where('product', $product);
           $res = $this->ci->db->get($this->table)->row();
           return $res->unit;
        }
    }

    function get_price($product=null)
    {
        if ($product)
        {
           $this->ci->db->select('price');
           $this->ci->db->where('product', $product);
           $res = $this->ci->db->get($this->table)->row();
           return $res->price;
        }
    }


    function add_qty($product=null,$amount_qty=null, $unit=null)
    {
        $this->ci->db->where('product', $product);
        
        $num = $this->ci->db->get($this->table)->num_rows();
        if ($num > 0)
        {
            $qty = $this->ci->db->get($this->table)->row();
            $qty = $qty->qty;
            $qty = $qty + $amount_qty;
            $res = array('qty' => $qty);
            $this->ci->db->where('product', $product);
            $this->ci->db->update($this->table, $res);
        }
        else
        {
            $value = array('product' => $product, 'qty' => $amount_qty, 'unit' => $unit);
            $this->ci->db->insert($this->table, $value);
        }

    }

    function min_qty($product=null,$amount_qty=null, $unit=null)
    {
        $this->ci->db->where('product', $product);
        $qty = $this->ci->db->get($this->table)->row();
        $qty = $qty->qty;
        $qty = $qty - $amount_qty;

        $res = array('qty' => $qty);
        $this->ci->db->where('product', $product);
        $this->ci->db->update($this->table, $res);
    }

    function get_all()
    {
      $this->ci->db->select('id, product, qty, unit');
      $this->ci->db->where('qty > 0');
      $this->ci->db->order_by('id', 'asc');
      return $this->ci->db->get($this->table);
    }

}

/* End of file Property.php */