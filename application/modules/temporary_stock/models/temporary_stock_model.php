<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Temporary_stock_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'temporary_stock';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_temporary_stock($limit, $offset)
    {
        $this->db->select('id, product, qty, unit');
        $this->db->from($this->table);
        $this->db->order_by('id', 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($product=null)
    {
        $this->db->select('id, product, qty, unit');
        $this->db->from($this->table);
        $this->cek_null($product,"product");
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function get_list_temporary_stock()
    {
        $this->db->select('id, product, qty, unit');
        $this->db->from($this->table);
        $this->db->where('qty >', 0);
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
    
    function get_temporary_stock_by_id($uid)
    {
        $this->db->select('id, product, qty, unit');
        $this->db->where('id', $uid);
        return $this->db->get($this->table)->row();
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function report($product=null)
    {
        $this->db->select('temporary_stock.id, temporary_stock.product, temporary_stock.qty, temporary_stock.unit, product.name');
        $this->db->from('temporary_stock, product');
        $this->db->where('temporary_stock.product = product.id');
        $this->cek_null($product,"temporary_stock.product");
        $this->db->order_by('temporary_stock.id', 'asc');
        return $this->db->get();
    }

}

?>