<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Journal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'journal';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_journal($limit, $offset)
    {
        $this->db->select('id, dates, currency, GJ, DP, PJ, SJ, CSJ, DS, CDS, CD, CG, CR, CCR, TR, SAJ, PRJ, ARJ, RF, AJ, approved');
        $this->db->from($this->table);
        $this->db->order_by('dates', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$date)
    {
        $this->db->select('id, dates, currency, GJ, DP, PJ, SJ, CSJ, DS, CDS, CD, CG, CR, CCR, TR, SAJ, PRJ, ARJ, RF, AJ, approved');
        $this->db->from($this->table);
        $this->cek_null($no,"id");
        $this->cek_null($date,"dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter()
    {
        $this->db->select_max('id');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['id'];
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
    
    function get_journal_by_id($uid)
    {
        $this->db->select('id, dates, currency, GJ, DP, PJ, SJ, CSJ, DS, CDS, CD, CG, CR, CCR, TR, SAJ, PRJ, ARJ, RF, AJ, approved');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_journal($date,$currency)
    {
        $this->db->where('dates', $date);
        $this->db->where('currency', $currency);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_journal($date,$currency,$id)
    {
        $this->db->where('dates', $date);
        $this->db->where('currency', $currency);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }

    function report($cur,$start,$end)
    {
        $this->db->select('id, dates, currency, GJ, DP, PJ, SJ, CSJ, DS, CDS, CD, CG, CR, CCR, TR, SAJ, PRJ, ARJ, RF, AJ, approved');
        $this->db->from($this->table);
        $this->cek_null($cur,"currency");
        $this->between($start,$end);
        $this->db->where('approved', 1);
        $this->db->order_by('dates', 'desc');
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