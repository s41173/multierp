<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Opname_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'opname_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, opname, product, end, physical, difference');
        $this->db->from($this->table);
        $this->db->where('opname', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function total($po)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->where('opname', $po);
        return $this->db->get($this->table)->row_array();
    }

    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('opname', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function get_begin($product,$po)
    {
        $this->db->select('id, opname, product, end, physical, difference');
        $this->db->from($this->table);
        $this->db->where('opname', $po);
        $this->db->where('product', $product);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function report($po,$product=null)
    {
        $this->db->select("$this->table.id, $this->table.opname, $this->table.product as pid, product.name as product, product.unit,
                           $this->table.end, $this->table.physical, $this->table.difference");
        
        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.opname", $po);
        $this->cek_null($product,"$this->table.product");
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

}

?>