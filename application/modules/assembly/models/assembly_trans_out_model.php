<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assembly_trans_out_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'assembly_trans_out';
    
    function get_last_item($uid)
    {
        $this->db->select('id, assembly, product, qty, unit');
        $this->db->from($this->table);
        $this->db->where('assembly', $uid);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_assembly($uid)
    {
        $this->db->where('assembly', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    

}

?>