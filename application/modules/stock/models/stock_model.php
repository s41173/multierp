<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'product';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_product($limit, $offset)
    {
        $this->db->select('id, brand, category, type, warehouse_id, currency, name, desc, qty, unit, buying, hpp, price, amount, vendor');
        $this->db->from($this->table); 
        $this->db->order_by('id', 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($type=null, $brand=null, $cat=null, $vendor=null, $name=null)
    {
        $this->db->select('id, brand, category, type, warehouse_id, currency, name, desc, qty, unit, buying, hpp, price, amount, vendor');
        $this->db->from($this->table);
        $this->cek_null($type,"type");
        $this->cek_null($brand,"brand");
        $this->cek_null($cat,"category");
        $this->cek_null($vendor,"vendor");
        $this->cek_null($name,"name");
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function get_list_product($cur=null, $type=null, $brand=null, $cat=null, $name=null)
    {
        $this->db->select('id, brand, category, currency, name, type, desc, qty, unit, buying, hpp, price, amount, vendor');
        $this->db->from($this->table);
        $this->cek_null($cur,"currency");
        $this->cek_null($type,"type");
        $this->cek_null($brand,"brand");
        $this->cek_null($cat,"category");
        $this->cek_null($name,"name");
//        $this->db->where('currency', $cur);
        $this->db->order_by('name', 'asc');
        return $this->db->get();
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_product_by_id($uid)
    {
        $this->db->select('id, brand, category, type, warehouse_id, currency, name, desc, qty, unit, buying, hpp, price, amount, vendor');
        $this->db->where('id', $uid);
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_product($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_product($name,$id)
    {
        $this->db->where('name', $name);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }

    function report($type=null, $category=null,$brand=null,$cur='IDR')
    {
        $this->db->select('product.id, brand.name as brand, product.type, product.warehouse_id, category.name as category, product.currency, product.name, product.desc, product.qty, product.buying, product.hpp, product.price, product.amount, product.unit');
        $this->db->from('category, product, brand');
        $this->db->where('product.category = category.id');
        $this->db->where('product.brand = brand.id');
        $this->db->where('product.currency', $cur);
        $this->cek_null($type,"product.type");
        $this->cek_null($brand,"product.brand");
        $this->cek_null($category,"product.category");
        $this->db->order_by('product.id', 'asc');
        return $this->db->get();
    }

    function total($type=null, $category=null,$brand=null,$cur='IDR')
    {
        $this->db->select('sum(product.price*product.qty) as total');
        $this->db->select('sum(product.buying*product.qty) as totalbuying');
        $this->db->select('sum(product.hpp*product.qty) as totalhpp');
        $this->db->select('sum(product.amount) as totalamount');
        $this->db->from('category, product, brand');
        $this->db->where('product.category = category.id');
        $this->db->where('product.brand = brand.id');
        $this->db->where('product.currency', $cur);
        $this->cek_null($type,"product.type");
        $this->cek_null($brand,"product.brand");
        $this->cek_null($category,"product.category");
        $this->db->order_by('product.id', 'asc');
        return $this->db->get()->row_array();
    }

}

?>