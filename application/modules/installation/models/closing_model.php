<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Closing_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'closing';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_closing($limit, $offset)
    {
        $this->db->select('id, dates, currency, user, notes, log');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($date=null)
    {
        $this->db->select('id, dates, currency, user, notes, log');
        $this->db->from($this->table);
        $this->cek_null($date,"dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function insert_into($src=null,$dest=null)
    {
        $this->db->query("INSERT INTO $dest SELECT * FROM $src WHERE status=1 AND approved=1");
    }

    function insert_into_single($src=null,$dest=null)
    {
        $this->db->query("INSERT INTO $dest SELECT * FROM $src WHERE approved=1");
    }

}

?>