<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gcategory_spec_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'gcategory_spec';
    
    function get_last_item($po)
    {
        $this->db->select('id, product, qty, unit');
        $this->db->from($this->table);
        $this->db->where('category', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table);
    }

    function delete_category($uid)
    {
        $this->db->where('category', $uid);
        $this->db->delete($this->table);
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>