<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Temporary_stock_transaction_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'temporary_stock_transaction';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_temporary_stock_transaction($limit, $offset)
    {
        $this->db->select('id, dates, product, qty, unit, type, user, staff, approved, log');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($product=null,$date=null,$type=null)
    {
        $this->db->select('id, dates, product, qty, unit, type, user, staff, approved, log');
        $this->db->from($this->table);
        $this->cek_null($product,"product");
        $this->cek_null($date,"dates");
        $this->cek_null($type,"type");
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
    
    function get_temporary_stock_transaction_by_id($uid)
    {
        $this->db->select('id, dates, product, qty, unit, type, user, staff, approved, log');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }

    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }


//    =========================================  REPORT  ===============================

    function report($start,$end,$type)
    {
        $this->db->select("$this->table.id, $this->table.dates, $this->table.product, product.name as pname, $this->table.type,
                           $this->table.unit, $this->table.qty, $this->table.user, $this->table.staff, $this->table.approved, $this->table.log");

        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where("$this->table.approved", 1);
        $this->cek_null($type,"$this->table.type");
        return $this->db->get();
    }

//    =========================================  REPORT  ===============================

}

?>