<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reststock_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'rest_stock';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get($limit, $offset)
    {
        $this->db->select('id, currency, dates, warehouse_id, product, qty, userid');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($warehouse=null,$product=null)
    {
        $this->db->select('id, currency, dates, warehouse_id, product, qty, userid');
        $this->db->from($this->table);
        $this->cek_null($warehouse,"warehouse_id");
        $this->cek_null($product,"product");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
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
    
    function get_stock_by_id($uid)
    {
        $this->db->select('id, currency, dates, warehouse_id, product, qty, userid');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }
    
    function valid_product($product)
    {
        $this->db->from($this->table);
        $this->db->where('id', $product);
        $num = $this->db->get()->num_rows();
        if ($num > 0){ return FALSE; }else { return TRUE; }
        
    }
    
    function update($uid, $users)
    {
        $this->db->where('no', $uid);
        $this->db->update($this->table, $users);
    }
    
    function report($warehouse=null,$start,$end)
    {
        $this->db->select('id, currency, dates, warehouse_id, product, qty, userid');
        $this->db->from($this->table);
        $this->cek_null($warehouse,"warehouse_id");
        $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        return $this->db->get();
    }

}

?>