<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'language';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_language($limit, $offset)
    {
        $this->db->select('id, name, code, primary');
        $this->db->from($this->table); 
        $this->db->order_by('name', 'asc'); 
        $this->db->limit($limit, $offset);
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
    
    function get_language_by_id($uid)
    {
        $this->db->select('id, name, code, primary');
        $this->db->where('id', $uid);
        return $this->db->get($this->table);
    }

    function get_language_name()
    {
        $this->db->order_by('primary', 'desc'); // query order
        return $this->db->get($this->table);
    }

    function get_primary()
    {
        $this->db->where('primary', 1);
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_language($code)
    {
        $this->db->where('code', $code);
        $query = $this->db->get($this->table)->num_rows();

        if($query > 0)
        {
           return FALSE;
        }
        else
        {
           return TRUE;
        }
    }

    function validating_language($code,$id)
    {
        $this->db->where('code', $code);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();

        if($query > 0)
        {
           return FALSE;
        }
        else
        {
           return TRUE;
        }
    }

}

?>