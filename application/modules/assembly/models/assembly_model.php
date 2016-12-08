<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assembly_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'assembly';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_assembly($limit, $offset)
    {
        $this->db->select('id, no, dates, docno, currency, project, product, qty, user, log, costs, total, notes, desc, approved');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$date)
    {
        $this->db->select('id, no, dates, docno, currency, project, product, qty, user, log, costs, total, notes, desc, approved');
        $this->db->from($this->table);
        $this->cek_null($no,"no");
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
    
    function get_assembly_by_id($uid)
    {
        $this->db->select('id, no, dates, docno, currency, project, product, qty, user, log, costs, total, notes, desc, approved');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }

    function get_assembly_by_no($uid)
    {
        $this->db->select('id, no, dates, docno, currency, project, product, qty, user, log, costs, total, notes, desc, approved');
        $this->db->from($this->table);
        $this->db->where('no', $uid);
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


//    =========================================  REPORT  =================================================================

    function report($cur,$start,$end)
    {
        $this->db->select("$this->table.id, $this->table.no, $this->table.dates, $this->table.docno, $this->table.currency, $this->table.project,
                           gproduct.name as product, $this->table.qty, gproduct.unit, $this->table.user, $this->table.log, $this->table.costs, $this->table.total, $this->table.notes,
                           $this->table.desc, $this->table.approved");

        $this->db->from("$this->table,gproduct");
        $this->db->where("$this->table.product = gproduct.id");

        $this->db->where("$this->table.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($cur,"$this->table.currency");

        $this->db->where("assembly.approved", 1);
        $this->db->order_by("assembly.no", 'asc');
        return $this->db->get();
    }
    
    function total($cur,$start,$end)
    {
        $this->db->select_sum('total');
        $this->db->select_sum('costs');
        $this->db->from($this->table);
        $this->db->where("assembly.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($cur,"currency");
        $this->db->where('assembly.approved', 1);
        return $this->db->get()->row_array();
    }

//    =========================================  REPORT  =================================================================

}

?>