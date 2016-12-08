<?php

class Admin_model extends CI_Model
{
    function __construct()
    {
       parent::__construct();
    }
    
    var $table = 'user';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_user($limit, $offset)
    {
        $this->db->select('userid, username, password, name, address, phone1, phone2, city, email, yahooid, role, status'); // select kolom yang mau di tampilkan
        $this->db->from($this->table); // from table dengan join nya
        $this->db->order_by('username', 'asc'); // query order
        $this->db->limit($limit, $offset);
        return $this->db->get(); // mengembalikan isi dari db
    }
    
    function delete($uid)
    {
        $this->db->where('userid', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function get_user()
    {
        $this->db->order_by('name', 'asc'); // query order
        return $this->db->get($this->table);
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_user_by_id($uid)
    {
        $this->db->select('userid, username, password, name, address, phone1, phone2, city, email, yahooid, role, status');
        $this->db->where('userid', $uid);
        return $this->db->get($this->table);
    }

    function get_userid($name)
    {
        $this->db->select('userid, username, password, name, address, phone1, phone2, city, email, yahooid, role, status');
        $this->db->where('username', $name);
        return $this->db->get($this->table);
    }
    
    function counter()
    {
        $this->db->select_max('userid');
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
            $this->db->where('userid', $uid);
            $this->db->update($this->table, $users);
    }
    
    function valid_name($uid)
    {
        $this->db->where('username', $uid);
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

    function validation_username($name ,$id)
    {
        $this->db->where('username', $name);
        $this->db->where_not_in('userid', $id);
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