<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contract_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'contract';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_contract($limit, $offset)
    {
        $this->db->select('contract.id, contract.no, contract.docno, contract.type, customer.prefix, customer.name, contract.notes, contract.deal_dates, contract.dates, contract.due,
                           contract.user, contract.staff, contract.currency, contract.amount, contract.tax, contract.balance, contract.status, contract.approved, contract.log');
        
        $this->db->from('contract, customer');
        $this->db->where('contract.customer = customer.id');
        $this->db->order_by('contract.id', 'desc');
        $this->db->order_by('contract.approved', 'asc');
        
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$date,$due)
    {
        $this->db->select('contract.id, contract.no, contract.docno, contract.type, customer.prefix, customer.name, contract.notes, contract.deal_dates,  contract.dates, contract.due,
                           contract.user, contract.staff, contract.currency, contract.amount, contract.tax, contract.balance, contract.status, contract.approved, contract.log');

        $this->db->from('contract, customer');
        $this->db->where('contract.customer = customer.id');
        $this->cek_null($no,"contract.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"contract.deal_dates");
        $this->cek_null($due,"contract.due");
        return $this->db->get();
    }

    function get_contract_list($type)
    {
        $this->db->select('contract.id, contract.no, contract.docno, contract.type, customer.prefix, customer.name, contract.notes, contract.deal_dates, contract.dates, contract.due,
                           contract.user, contract.staff, contract.currency, contract.tax, contract.amount, contract.balance, contract.status, contract.approved, contract.log');

        $this->db->from('contract, customer');
        $this->db->where('contract.customer = customer.id');
        if ($type != 'null'){ $this->cek_null($type,"contract.type"); }
        
        $this->db->where('contract.status', 0);
        $this->db->where('contract.approved', 1);
        $this->db->order_by('contract.dates', 'asc');
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
    
    function get_contract_by_id($uid)
    {
       $this->db->select('id, no, docno, type, contract_type, customer, notes, deal_dates, dates, due, user, staff, currency, amount, tax, balance, status, void, void_date, void_desc, approved, log');
       $this->db->where('id', $uid);
       return $this->db->get($this->table);
    }

    function get_contract_by_no($uid)
    {
       $this->db->select('id, no, docno, type, contract_type, customer, notes, deal_dates, dates, due, user, staff, currency, amount, tax, balance, status, void, void_date, void_desc, approved, log');
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
    
    function valid_docno($no)
    {
        $this->db->where('docno', $no);
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
    
    function validating_docno($no,$id)
    {
        $this->db->where('docno', $no);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }


//    =========================================  REPORT  =================================================================

    function report($customer,$cur,$start,$end,$duestart,$dueend)
    {
        $this->db->select('contract.id, contract.no, contract.docno, contract.type, contract.contract_type, contract.dates, contract.deal_dates, contract.due, customer.prefix, customer.name, customer.address, customer.phone1, customer.phone2,
                           customer.city, contract.currency, contract.user, contract.staff, contract.log, contract.contract_type,
                           contract.status, contract.tax, contract.amount, contract.balance, contract.amount, contract.notes,
                           contract.void_date, contract.void_desc, contract.approved');

        $this->db->from('contract, customer');
        $this->db->where('contract.customer = customer.id');
        $this->cek_between_date($start, $end);
        $this->cek_between_due($duestart, $dueend);
        $this->cek_null($customer,"customer.name");
        $this->cek_null($cur,"contract.currency");

//        $this->db->where('contract.approved', 1);
        $this->db->order_by('contract.no', 'asc');
        return $this->db->get();
    }
    
    private function cek_between_date($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("contract.deal_dates BETWEEN '".$start."' AND '".$end."'"); }
    }
    
    private function cek_between_due($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("contract.due BETWEEN '".$start."' AND '".$end."'"); }
    }
    
    function total($month,$year,$type=0)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->select_sum('balance');

        $this->db->from('contract, customer');
        $this->db->where('contract.customer = customer.id');
        $this->cek_null($month,"MONTH(deal_dates)");
        $this->cek_null($year,"YEAR(deal_dates)");
        $this->db->where('contract.approved', 1);
        $value = $this->db->get()->row_array();
        if ($type == 0){ return intval($value['tax']+$value['amount']); }
        else { return intval($value['balance']); }
    }

//    =========================================  REPORT  =================================================================

}

?>