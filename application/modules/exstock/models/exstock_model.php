<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Exstock_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'exstock';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_exstock($limit, $offset)
    {
        $this->db->select('id, no, dates, currency, vendor, desc, type, ref, user, approved, log');
        $this->db->from($this->table);
        $this->db->order_by('no', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no=null,$date=null,$type=null)
    {
        $this->db->select('id, no, dates, currency, vendor, desc, type, ref, user, approved, log');
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
    
    function get_exstock_by_id($uid)
    {
        $this->db->select('id, no, dates, currency, vendor, desc, type, ref, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }

    function get_exstock_by_no($uid)
    {
        $this->db->select('id, no, dates, currency, vendor, desc, type, ref, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('no', $uid);
        return $this->db->get();
    }

    function get_exstock_by_ref($ref)
    {
        $this->db->select('id, no, dates, currency, vendor, desc, type, ref, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('ref', $ref);
        $this->db->where('type', 'IN');
        $this->db->where('approved', 1);
        $query = $this->db->get()->num_rows();
        if ($query > 0){ return FALSE; } else { return TRUE; }
    }

    function get_list()
    {
        $this->db->select('id, no, dates, currency, vendor, desc, type, ref, user, approved, log');
        $this->db->from($this->table);
        $this->db->where('type', 'OUT');
        $this->db->where('approved', 1);
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

    function validating_no($no,$id)
    {
        $this->db->where('no', $no);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }


//    =========================================  REPORT  ===============================

    function report($start,$end,$type)
    {
        $this->db->select("$this->table.id, $this->table.no, $this->table.dates, $this->table.currency, vendor.prefix, vendor.name, $this->table.desc, $this->table.type,
                           $this->table.ref, $this->table.user, $this->table.approved, $this->table.log");
        $this->db->from("$this->table,vendor");
        $this->db->where("$this->table.vendor = vendor.id");
        $this->db->where("$this->table.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where("$this->table.approved", 1);
        $this->cek_null($type,"$this->table.type");
        $this->db->order_by("$this->table.no", "asc");
        return $this->db->get();
    }

//    =========================================  REPORT  ===============================

}

?>