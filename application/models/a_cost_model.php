<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class A_cost_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'assembly_cost';
    
    function get_last($no)
    {
        $this->db->select('id, no, notes, amount');
        $this->db->from($this->table);
        $this->db->where('no', $no);
        return $this->db->get(); 
    }
    
    function delete($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table);
    }
    
    function delete_assembly($no)
    {
        $this->db->where('no', $no);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function total($no)
    {
        $this->db->select_sum('amount');
        $this->db->where('no', $no);
        $val = $this->db->get($this->table)->row_array();
        return intval($val['amount']);
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
 

}

?>