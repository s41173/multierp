<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Apc_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'apc';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('apc.id, apc.no, apc.currency, apc.dates, apc.acc, apc.account, apc.user, apc.status,
                           apc.amount, apc.notes, apc.approved');
        
        $this->db->from('apc');
        $this->db->order_by('apc.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function search($no,$date)
    {
        $this->db->select('apc.id, apc.no, apc.docno, apc.currency, apc.dates, apc.acc, apc.account, apc.user, apc.status,
                           apc.amount, apc.notes, apc.approved');

        $this->db->from('apc');
        $this->cek_null($no,"apc.no");
        $this->cek_null($date,"apc.dates");
        return $this->db->get();
    }
    
    function counter()
    {
        $this->db->select_max('no');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function report($cur=null,$start=null,$end,$cat=null,$acc=null)
    {
        $this->db->select('apc.id, apc.no, apc.dates, apc.currency, apc.notes, apc.acc, apc.amount, apc.approved');
        $this->db->from('apc');
        $this->cek_cat($acc, 'apc.acc');
        $this->db->where('apc.currency', $cur);
        $this->cek_between($start, $end);
//        $this->db->where('apc.approved', 1);
        $this->db->order_by('apc.dates','asc');
        return $this->db->get(); 
    }
    
    function report_category($vendor=null,$cur=null,$start=null,$end,$cat=null,$acc)
    {
       $this->db->select('apc.id, apc.no, apc.dates, apc.currency, apc.acc, apc.approved,
                          costs.name as cost, costs.account_id as account, apc_trans.notes, apc_trans.staff, apc_trans.amount,
                          categories.name as category, categories.id as catid');
        
        $this->db->from('apc, apc_trans, costs, categories');
        $this->db->where('apc.id = apc_trans.apc_id');
        $this->db->where('apc_trans.cost = costs.id');
        $this->db->where('costs.category = categories.id');
        $this->cek_cat($acc, 'apc.acc');
        $this->cek_cat($cat, 'costs.category');
        $this->db->where('apc.currency', $cur);
        $this->cek_between($start, $end);
//        $this->db->group_by('categories.id');
//        $this->db->where('apc.approved', 1);
       
        return $this->db->get();  
    }
    
    private function cek_null_report($val,$field)
    { if ($val != ""){ return $this->db->where($field, $val); } }
    
    function total_chart($month,$year,$cur='IDR')
    {
        $this->db->select_sum('amount');

        $this->db->from('apc');
        $this->cek_null($cur,"currency");
        $this->db->where('approved', 1);
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        $query = $this->db->get()->row_array();
        return $query['amount'];
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("apc.dates BETWEEN '".$start."' AND '".$end."'"); }
    }

    private function cek_null($val,$field)
    { if (isset($val)){ return $this->db->where($field, $val); } }
    
    private function cek_cat($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    function valid_no($no)
    {
        $this->db->where('no', $no);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

}

?>