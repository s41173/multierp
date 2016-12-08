<?php

class Adminmenu_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'admin_menu';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_adminmenu($limit, $offset)
    {
        $this->db->select('id, parent_id, name, modul, url, menu_order, class_style, id_style, icon, target'); // select kolom yang mau di tampilkan
        $this->db->from($this->table); // from table dengan join nya
        $this->db->order_by('menu_order', 'asc'); // query order
        $this->db->limit($limit, $offset);
        return $this->db->get(); // mengembalikan isi dari db
    }

    // tarik data untuk combobox
    function get_adminmenu_name()
    {
        $this->db->order_by('name');
        $val = $this->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function get_adminmenu_condition($uid)
    {
        $this->db->where_not_in('id', $uid);
        $this->db->order_by('name');
        return $this->db->get($this->table);
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
        $this->delete_submenu($uid);
    }

    function delete_submenu($uid)
    {
       $this->db->where('parent_id', $uid);
       $this->db->delete($this->table);
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_adminmenu_by_id($uid)
    {
        $this->db->select('id, parent_id, name, modul, url, menu_order, class_style, id_style, icon, target'); // select kolom yang mau di tampilkan
        $this->db->where('id', $uid);
        return $this->db->get($this->table);
    }
   
    function update($uid, $value)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $value);
    }

    function valid_name($name)
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

    function validating_name($name,$id)
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