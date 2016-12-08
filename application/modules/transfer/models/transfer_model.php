<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'transfer';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_transfer($limit, $offset)
    {
        $this->db->select('id, no, notes, dates, currency, from, to, approved, amount');
        $this->db->order_by('no', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table);
    }

    function search($no=null,$date=null)
    {
        $this->db->select('id, no, notes, dates, currency, from, to, approved, amount');
        $this->cek_null($no,"no");
        $this->cek_null($date,"dates");
        return $this->db->get($this->table);
    }


    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter()
    {
        $this->db->select_max('no');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
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
    
    function get_transfer_by_id($uid)
    {
        $this->db->select('id, no, notes, dates, currency, from, to, approved, amount, log');
        $this->db->where('id', $uid);
        return $this->db->get($this->table);
    }

    function get_transfer_by_no($uid)
    {
        $this->db->select('id, no, notes, dates, currency, from, to, approved, amount, log');
        $this->db->where('no', $uid);
        return $this->db->get($this->table);
    }
    
    function update($uid, $users)
    {
        $this->db->where('no', $uid);
        $this->db->update($this->table, $users);
    }

    function update_id($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function valid_no($no)
    {
        $this->db->where('no', $no);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_no($no,$id)
    {
        $this->db->where('no', $no);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }

    function report($start,$end)
    {
        $this->db->select('id, no, notes, dates, currency, from, to, approved, amount, log');
        $this->db->from($this->table);
        $this->between($start,$end);
        $this->db->where('approved', 1);
        return $this->db->get();
    }

    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }

}

?>