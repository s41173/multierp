<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_model extends CI_Model
{
    function __construct() { parent::__construct(); }
    
    var $table = '';

    function getmodul()
    {
        $this->db->select('name');
        $this->db->from('modul');
        $this->db->where('aktif', 'Y');
        $this->db->where('publish', 'Y');
        return $this->db->get();
    }

    function getarticle()
    {
        $this->db->select('nama_kategori, news_category_id');
        $this->db->from('news_category');
        return $this->db->get();
    }

    function getcity($ccountry)
    {
        $this->db->select('city.name');
        $this->db->from('city,country');
        $this->db->where('city.country_id = country.id');
        $this->db->where('country.name', $ccountry);
        return $this->db->get()->result();
    }

    function stockout_item_qty($stockout=null,$product=null)
    {
        $this->db->select('qty, price');
        $this->db->from('stock_out_item');
        $this->db->where('stock_out', $stockout);
        $this->db->where('product', $product);
        $res = $this->db->get()->row();
        if ($res){ return $res->qty.'|'.$res->price; } else { return '0|0'; }
    }

    function get_product_qty($product=null)
    {
        $this->db->select('qty');
        $this->db->from('product');
        $this->db->where('id', $product);
        $res = $this->db->get()->row();
        if ($res){ return $res->qty; } else { return 0; }
    }

}

?>