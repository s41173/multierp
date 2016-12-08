<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Opname_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'opname';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_opname($limit, $offset)
    {
        $this->db->select('id, no, dates, desc, user, supervisor, approved, log');
        $this->db->from($this->table);
        $this->db->order_by('no', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no=null,$date=null,$type=null)
    {
        $this->db->select('id, no, dates, desc, user, supervisor, approved, log');
        $this->db->from($this->table);
        $this->cek_null($no,"no");
        $this->cek_null($date,"dates");
        $this->cek_null($type,"type");
        return $this->db->get();
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
    
    function get_opname_by_id($uid)
    {
        $this->db->select('id, no, dates, desc, user, supervisor, approved, log');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }

    function get_opname_by_no($uid)
    {
        $this->db->select('id, no, dates, desc, user, supervisor, approved, log');
        $this->db->from($this->table);
        $this->db->where('no', $uid);
        return $this->db->get();
    }

    function get_opname_by_begindate($date)
    {
        $this->db->select('id, no, dates, desc, user, supervisor, approved, log');
        $this->db->from($this->table);
        $this->db->where('dates', $date);
        return $this->db->get()->row();
    }

    function get_list($date)
    {
        $this->db->select('id, no, dates, desc, user, supervisor, approved, log');
        $this->db->from($this->table);
        $this->db->where('approved', 1);
        $this->db->where('dates <', $date);
        $this->db->order_by('dates', 'desc');
        $this->db->limit(1);
        return $this->db->get();
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

    function valid_date($date)
    {
        $this->db->where('dates', $date);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function valid_begindate($date)
    {
        $this->db->where('begin_dates', $date);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }



//    =========================================  REPORT  ===============================

    function report($start,$end,$type)
    {
        $this->db->select("$this->table.id, $this->table.no, $this->table.dates, $this->table.desc, $this->table.supervisor, $this->table.user, $this->table.approved, $this->table.log");
        $this->db->from("$this->table");
        $this->db->where("$this->table.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where("$this->table.approved", 1);
        $this->cek_null($type,"$this->table.type");
        $this->db->order_by("$this->table.no", "asc");
        return $this->db->get();
    }

//    =========================================  REPORT  ===============================

}

?>