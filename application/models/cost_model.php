<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cost_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'costs';
    
    function get_last()
    {
        $this->db->select('costs.id, costs.name, costs.account_id, categories.name as category');
        $this->db->from('costs, categories');
        $this->db->where('costs.category = categories.id');
        return $this->db->get(); 
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
 

}

?>