<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model
{
    function __construct()
    {
       parent::__construct();
    }
    
    var $table = 'log';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function counter()
    {
        $this->db->select_max('logid');
        return $this->db->get($this->table);
    }
    
    function get_last_log($limit, $offset)
    {
        $this->db->select('log.logid, user.username, log.date, log.time, log.activity'); // select kolom yang mau di tampilkan
        $this->db->from('log,user'); // from table dengan join nya
        $this->db->where('log.userid = user.userid');
        $this->db->order_by('log.logid', 'desc'); // query order
        $this->db->limit($limit, $offset);
        return $this->db->get(); // mengembalikan isi dari db
    }
    
    function search($date=null)
    {
        $this->db->select('log.logid, user.username, log.date, log.time'); // select kolom yang mau di tampilkan
        $this->db->from('log,user'); // from table dengan join nya
        $this->db->where('log.userid = user.userid');
        $this->db->where('date', $date);
        $this->db->order_by('log.logid', 'desc'); // query order
        return $this->db->get(); // mengembalikan isi dari db
    }
    
    function delete($uid)
    {
        $this->db->where('logid', $uid); 
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_log($daten)
    {
        $this->db->where('date', $daten);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($logs)
    {
        $this->db->insert($this->table, $logs);
    }
    
}

?>