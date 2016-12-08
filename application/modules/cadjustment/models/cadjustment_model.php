<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cadjustment_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->year = date('Y');
    }
    
    var $table = 'contract_adjustment';
    private $year;
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_contract($limit, $offset)
    {
        $this->db->select('contract_adjustment.id, contract_adjustment.no, contract_adjustment.contract_no, contract_adjustment.dates, contract_adjustment.docno, contract_adjustment.user,
                           contract_adjustment.total, contract_adjustment.notes, contract_adjustment.approved');
        
        $this->db->from('contract_adjustment');
        $this->db->order_by('contract_adjustment.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$date)
    {
        $this->db->select('contract_adjustment.id, contract_adjustment.no, contract_adjustment.contract_no, contract_adjustment.dates, contract_adjustment.docno, contract_adjustment.user,
                           contract_adjustment.total, contract_adjustment.notes, contract_adjustment.approved');

        $this->db->from('contract_adjustment');
        $this->cek_null($no,"contract_adjustment.no");
        $this->cek_null($date,"contract_adjustment.dates");
        return $this->db->get();
    }


    function counter()
    {
        $this->db->select_max('no');
        $this->db->where('YEAR(dates)', $this->year);
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function counter_id()
    {
        $this->db->select_max('id');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['id'];
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
    
    function get_contract_adjustment_by_id($uid)
    {
        $this->db->select('contract_adjustment.id, contract_adjustment.no, contract_adjustment.contract_no, contract_adjustment.dates, contract_adjustment.docno, 
                           contract_adjustment.user, contract_adjustment.desc, contract_adjustment.log,
                           contract_adjustment.total, contract_adjustment.notes, contract_adjustment.approved');

        $this->db->from('contract_adjustment');
        $this->db->where('contract_adjustment.id', $uid);
        return $this->db->get();
    }

    function get_contract_adjustment_by_no($uid,$year=null)
    {
        $this->db->select('contract_adjustment.id, contract_adjustment.no, contract_adjustment.contract_no, contract_adjustment.dates, contract_adjustment.docno, 
                           contract_adjustment.user, contract_adjustment.desc, contract_adjustment.log,
                           contract_adjustment.total, contract_adjustment.notes, contract_adjustment.approved');

        $this->db->from('contract_adjustment');
        $this->db->where('contract_adjustment.no', $uid);
//        $this->db->where('YEAR(dates)', $year);
        $this->cek_null($year, 'YEAR(dates)');
        return $this->db->get();
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
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
        $this->db->where('YEAR(dates)', $this->year);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_no($no,$id)
    {
        $this->db->where('no', $no);
        $this->db->where('YEAR(dates)', $this->year);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }


//    =========================================  REPORT  =================================================================

    function report($start,$end)
    {
        $this->db->select('contract_adjustment.id, contract_adjustment.no, contract_adjustment.contract_no,
                           contract_adjustment.dates, contract_adjustment.docno, contract_adjustment.user, 
                           contract_adjustment.log, contract_adjustment.desc,
                           contract_adjustment.total, contract_adjustment.notes, contract_adjustment.approved');

        $this->db->from('contract_adjustment');
        $this->db->where("contract_adjustment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");

        $this->db->where('contract_adjustment.approved', 1);
        $this->db->order_by('contract_adjustment.no', 'asc');
        return $this->db->get();
    }
    
    function total($start,$end)
    {
        $this->db->select_sum('total');

        $this->db->from('contract_adjustment');
        $this->db->where("contract_adjustment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where('contract_adjustment.approved', 1);
        return $this->db->get()->row_array();
    }

    function total_chart($month,$year,$cur='IDR')
    {
        $this->db->select_sum('total');

        $this->db->from('contract_adjustment');
        $this->db->where('contract_adjustment.approved', 1);
        $this->cek_null($month,"MONTH(contract_adjustment.dates)");
        $this->cek_null($year,"YEAR(contract_adjustment.dates)");
        $query = $this->db->get()->row_array();
        return $query['total'];
    }

//    =========================================  REPORT  =================================================================

}

?>