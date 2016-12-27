<?php

class Role_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'role';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_role($limit, $offset)
    {
        $this->db->select('id, name, rules, desc'); // select kolom yang mau di tampilkan
        $this->db->from($this->table); // from table dengan join nya
        $this->db->order_by('name', 'asc'); // query order
        $this->db->limit($limit, $offset);
        return $this->db->get(); // mengembalikan isi dari db
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
    
    function get_role_by_id($uid)
    {
        $this->db->select('id, name, rules, desc'); // select kolom yang mau di tampilkan
        $this->db->where('id', $uid);
        return $this->db->get($this->table);
    }

    function get_role_by_name($name)
    {
        $this->db->select('id, name, rules, desc'); // select kolom yang mau di tampilkan
        $this->db->where('name', $name);
        return $this->db->get($this->table);
    }

    function get_role_name()
    {
        $this->db->order_by('name', 'asc'); // query order
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_role($name)
    {
        $this->db->where('name', $name);
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

    function validating_role($name,$id)
    {
        $this->db->where('name', $name);
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