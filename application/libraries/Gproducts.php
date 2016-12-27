<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gproducts {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'gproduct';

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function add_qty($product=0,$amount_qty=null)
    {
        $this->ci->db->where('id', $product);
        $val = $this->ci->db->get($this->table)->row();
        $qty = $val->qty+$amount_qty;

        $res = array('qty' => $qty);
        $this->ci->db->where('id', $product);
        $this->ci->db->update($this->table, $res);
    }

    function min_qty($product=null,$amount_qty=null)
    {
        $this->ci->db->where('id', $product);
        $val = $this->ci->db->get($this->table)->row();
        $qty = $val->qty - $amount_qty;

        $res = array('qty' => $qty);
        $this->ci->db->where('id', $product);
        $this->ci->db->update($this->table, $res);
    }

    function edit_price($name=null,$price=0)
    {
        $this->ci->db->where('name', $name);
        $val = $this->ci->db->get($this->table)->row();

        $res = array('price' => $price);
        $this->ci->db->where('name', $name);
        $this->ci->db->update($this->table, $res);
    }

    function valid_qty($pid,$qty)
    {
       $this->ci->db->select('id, name, qty');
       $this->ci->db->where('id', $pid);
       $res = $this->ci->db->get($this->table)->row();
       if ($res->qty - $qty < 0){ return FALSE; } else { return TRUE; }
    }

    function get_details($name=null)
    {
        if ($name)
        {
           $this->ci->db->select('id, name, qty');
           $this->ci->db->where('name', $name);
           return $this->ci->db->get($this->table)->row();
        }
    }

    function get_id($name=null)
    {
        if ($name)
        {
           $this->ci->db->select('id, name, qty');
           $this->ci->db->where('name', $name);
           $res = $this->ci->db->get($this->table)->row();
           return $res->id;
        }
    }

    function get_name($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('id, name, qty');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get($this->table)->row();
           return $res->name;
        }
    }

    function get_unit($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('unit');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get($this->table)->row();
           return $res->unit;
        }
    }

    function get_qty($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('qty');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get($this->table)->row();
           return $res->qty;
        }
    }

    function get_price($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('price');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get($this->table)->row();
           return $res->price;
        }
    }
    
    function get_hpp($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('hpp');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get($this->table)->row();
           return intval($res->hpp);
        }
    }

    function get_all()
    {
      $this->ci->db->select('id, name, qty, unit');
      $this->ci->db->order_by('name', 'asc');
      return $this->ci->db->get($this->table);
    }

}

/* End of file Property.php */