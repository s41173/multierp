<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_model extends CI_Model
{
    function __construct()
    {
       parent::__construct();
    }
    
    var $table = 'modul';

    
    function get_truncate()
    {
        $this->db->select('id, name, title, table, truncate');
        $this->db->from($this->table);
        $this->db->order_by('title', 'asc');
        $this->db->where('truncate', 1);
        return $this->db->get(); 
    }

    function remove($table=null)
    {
      $this->db->truncate($table);
    }

    function remove_admin()
    {
      $names = array('admin');
      $this->db->or_where_in('username', $names);
      $this->db->delete('user');
    }

    function status($val=null)
    {
        $component = array('status' => $val);
        $this->db->update('settings', $component);
    }

}

?>