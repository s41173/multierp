<?php

class Faculty_model extends Model
{
    function Faculty_model()
    {
        parent::Model();
    }
    
    var $table = 'faculty';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_faculty($limit, $offset)
    {
        $this->db->select('faculty_id, name, code'); // select kolom yang mau di tampilkan
        $this->db->from($this->table); // from table dengan join nya
        $this->db->order_by('name', 'asc'); // query order
        $this->db->limit($limit, $offset);
        return $this->db->get(); // mengembalikan isi dari db
    }
    
    function delete($uid)
    {
        $this->db->where('faculty_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_faculty_by_id($uid)
    {
        $this->db->select('faculty_id, name, code');
        $this->db->where('faculty_id', $uid);
        return $this->db->get($this->table);
    }

    function get_faculty_name()
    {
        $this->db->select('faculty_id,name,code');
        $this->db->order_by('name', 'asc'); // query order
        return $this->db->get($this->table);
    }
    
    function counter()
    {
        $this->db->select_max('userid');
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('faculty_id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_faculty($name)
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

    function valid_code($code)
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

    function validating_faculty($name,$id)
    {
        $this->db->where('name', $name);
        $this->db->where_not_in('faculty_id', $id);
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

    function validating_code($code,$id)
    {
        $this->db->where('code', $code);
        $this->db->where_not_in('faculty_id', $id);
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