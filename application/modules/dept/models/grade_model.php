<?php

class Grade_model extends Model
{
    function Grade_model()
    {
        parent::Model();
    }
    
    var $table = 'grade';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_grade($limit, $offset)
    {
        $this->db->select('grade_id, name'); // select kolom yang mau di tampilkan
        $this->db->from($this->table); // from table dengan join nya
        $this->db->order_by('name', 'asc'); // query order
        $this->db->limit($limit, $offset);
        return $this->db->get(); // mengembalikan isi dari db
    }
    
    function delete($uid)
    {
        $this->db->where('grade_id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_grade_by_id($uid)
    {
        $this->db->select('grade_id, name');
        $this->db->where('grade_id', $uid);
        return $this->db->get($this->table);
    }

    function get_grade()
    {
        $this->db->select('grade_id, name');
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
        $this->db->where('grade_id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_grade($name)
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

    function validating_grade($name,$id)
    {
        $this->db->where('name', $name);
        $this->db->where_not_in('grade_id', $id);
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