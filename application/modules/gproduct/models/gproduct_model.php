<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gproduct_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'gproduct';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_product($limit, $offset)
    {
        $this->db->select('id, category, currency, name, desc, qty, unit, hpp, price');
        $this->db->from($this->table); 
        $this->db->order_by('id', 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($cat=null, $name=null)
    {
        $this->db->select('id, category, currency, name, desc, qty, unit, hpp, price');
        $this->db->from($this->table);
        $this->cek_null($cat,"category");
        $this->cek_null($name,"name");
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function get_list_product($cur='IDR', $cat=null, $name=null)
    {
        $this->db->select('id, category, currency, name, desc, qty, unit, hpp, price');
        $this->db->from($this->table);
        $this->cek_null($cat,"category");
        $this->cek_null($name,"name");
        $this->db->where('currency', $cur);
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
        $this->db->select('id, category, currency, name, desc, qty, unit, hpp, price');
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

    function report($category=null)
    {
        $this->db->select('gproduct.id, category.name as category, gproduct.currency, gproduct.name, gproduct.desc, gproduct.qty, gproduct.hpp, gproduct.price, gproduct.unit');
        $this->db->from('category, gproduct, brand');
        $this->db->where('gproduct.category = category.id');
   
        $this->cek_null($brand,"gproduct.brand");
        $this->cek_null($category,"gproduct.category");
        $this->db->order_by('gproduct.id', 'asc');
        return $this->db->get();
    }

    function total($category=null)
    {
        $this->db->select('sum(gproduct.price*gproduct.qty) as total');
        $this->db->from('category, gproduct');
        $this->db->where('gproduct.category = category.id');
        $this->cek_null($category,"gproduct.category");
        $this->db->order_by('gproduct.id', 'asc');
        return $this->db->get()->row_array();
    }

}

?>