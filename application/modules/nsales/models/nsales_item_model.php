<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nsales_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'nsales_item';
    
    function get_last_item($po,$year=0)
    {
        $this->db->select('id, nsales_id, type, size, coloumn, price, discount, discount_amount, tax, amount, sup');
        $this->db->from('nsales_item');
        $this->db->where('nsales_id', $po);
        $this->db->order_by('id', 'asc'); 
        $num = $this->db->get()->num_rows();
        
        if ($num == 0)
        {
            $this->db->select('id, nsales_id, type, size, coloumn, price, discount, discount_amount, tax, amount, sup');
            $this->db->from('nsales_item');
            $this->db->where('nsales_id', $po);
            $this->db->order_by('id', 'asc'); 
            return $this->db->get(); 
        }
        else
        {
            $this->db->select('id, nsales_id, type, size, coloumn, price, discount, discount_amount, tax, amount, sup');
            $this->db->from('nsales_item');
            $this->db->where('nsales_id', $po);
            $this->db->where('year', $year);
            $this->db->order_by('id', 'asc'); 
            return $this->db->get();  
        }
    }

    function total($po,$year)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('discount_amount');
        $this->db->select_sum('amount');
        $this->db->where('nsales_id', $po);
        $this->db->where('year', $year);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid,$year)
    {
        $this->db->where('nsales_id', $uid);
        $this->db->where('year', $year);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>