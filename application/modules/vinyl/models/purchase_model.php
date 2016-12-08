<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'vinyl_purchase';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_purchase($limit, $offset)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vendor.prefix, vendor.name, vinyl_purchase.user, vinyl_purchase.status,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.total, vinyl_purchase.p2, vinyl_purchase.costs, vinyl_purchase.notes, vinyl_purchase.currency, vinyl_purchase.approved');
        
        $this->db->from('vinyl_purchase, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->db->order_by('vinyl_purchase.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$vendor,$date)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vendor.prefix, vendor.name, vinyl_purchase.user, vinyl_purchase.status,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.total, vinyl_purchase.p2, vinyl_purchase.costs, vinyl_purchase.notes, vinyl_purchase.currency, vinyl_purchase.approved');

        $this->db->from('vinyl_purchase, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->cek_null($no,"vinyl_purchase.no");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($date,"vinyl_purchase.dates");
        return $this->db->get();
    }

    function get_purchase_list($currency=null,$vendor=null,$st=0)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vendor.prefix, vendor.name, vinyl_purchase.user, vinyl_purchase.status,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.total, vinyl_purchase.p2, vinyl_purchase.notes, vinyl_purchase.currency, vinyl_purchase.approved');

        $this->db->from('vinyl_purchase, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->cek_null($currency,"vinyl_purchase.currency");
        $this->db->where('vinyl_purchase.stock_in_stts', $st);
        $this->cek_null($vendor,"vinyl_purchase.vendor");
        $this->db->where('vinyl_purchase.approved', 1);
        
        $this->db->order_by('vinyl_purchase.dates', 'asc');
        return $this->db->get();
    }

    function get_purchase_list_all($currency=null,$vendor=null,$st=0)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vendor.prefix, vendor.name, vinyl_purchase.user, vinyl_purchase.status,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.total, vinyl_purchase.p2, vinyl_purchase.notes, vinyl_purchase.currency, vinyl_purchase.approved');

        $this->db->from('vinyl_purchase, vendor');
//        $this->db->from('vinyl_purchase, vendor, stock_in');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
//        $this->db->where('vinyl_purchase.no = stock_in.vinyl_purchase');
        $this->cek_null($currency,"vinyl_purchase.currency");
        $this->cek_null($vendor,"vinyl_purchase.vendor");
        $this->db->where('vinyl_purchase.approved', 1);
        $this->db->where('vinyl_purchase.status', $st);
//        $this->db->where('stock_in.approved', 1);
//        $this->db->where('vinyl_purchase.stock_in_stts', 1);

        $this->db->order_by('vinyl_purchase.dates', 'asc');
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
    
    function get_purchase_by_id($uid)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.vendor, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vendor.prefix, vendor.name, vinyl_purchase.currency, vinyl_purchase.user, vinyl_purchase.log,
                           vinyl_purchase.status, vinyl_purchase.tax, vinyl_purchase.discount, vinyl_purchase.p1, vinyl_purchase.p2, vinyl_purchase.total, vinyl_purchase.notes, vinyl_purchase.desc,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.shipping_date,vinyl_purchase.costs, vinyl_purchase.approved, vinyl_purchase.ap_over, vinyl_purchase.over_amount, vinyl_purchase.stock_in_stts');

        $this->db->from('vinyl_purchase, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->db->where('vinyl_purchase.id', $uid);
        return $this->db->get();
    }

    function get_purchase_by_no($uid)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.vendor, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vendor.city, vinyl_purchase.currency, vinyl_purchase.user, vinyl_purchase.log, vinyl_purchase.desc, vinyl_purchase.shipping_date,
                           vinyl_purchase.status, vinyl_purchase.tax, vinyl_purchase.p1, vinyl_purchase.p2, vinyl_purchase.total, vinyl_purchase.notes, vinyl_purchase.shipping_date,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.costs, vinyl_purchase.approved, vinyl_purchase.ap_over, vinyl_purchase.over_amount, vinyl_purchase.stock_in_stts');

        $this->db->from('vinyl_purchase, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->db->where('vinyl_purchase.no', $uid);
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
    
    function valid_sales($sid)
    {
        $this->db->where('sid', $sid);
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
    
    function validating_over($no,$id)
    {
        $this->db->where('ap_over', $no);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }


//    =========================================  REPORT  =================================================================

    function report($vendor,$cur,$start,$end,$status,$acc)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vendor.city, vinyl_purchase.currency, vinyl_purchase.user, vinyl_purchase.log, vinyl_purchase.desc,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.status, vinyl_purchase.tax, vinyl_purchase.p1, vinyl_purchase.p2, vinyl_purchase.total, vinyl_purchase.notes, vinyl_purchase.shipping_date,
                           vinyl_purchase.costs, vinyl_purchase.approved, vinyl_purchase.ap_over, vinyl_purchase.over_amount, vinyl_purchase.stock_in_stts');

        $this->db->from('vinyl_purchase, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->db->where("vinyl_purchase.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"vinyl_purchase.currency");
        $this->cek_null($status,"vinyl_purchase.status");
        $this->cek_null($acc,"vinyl_purchase.acc");

        $this->db->where('vinyl_purchase.approved', 1);
        $this->db->order_by('vinyl_purchase.no', 'asc');
        return $this->db->get();
    }
    
    function total($vendor,$cur,$start,$end,$status,$acc)
    {
        $this->db->select_sum('p1');
        $this->db->select_sum('p2');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');
        $this->db->select_sum('over_amount');

        $this->db->from('vinyl_purchase, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->db->where("vinyl_purchase.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"vinyl_purchase.currency");
        $this->cek_null($status,"vinyl_purchase.status");
        $this->cek_null($acc,"vinyl_purchase.acc");
        $this->db->where('vinyl_purchase.approved', 1);
        return $this->db->get()->row_array();
    }
    
    function total_chart($cur,$month,$year)
    {
        $this->db->select_sum('p1');
        $this->db->select_sum('p2');
        $this->db->select_sum('tax');
        $this->db->select_sum('costs');
        $this->db->select_sum('total');
        $this->db->select_sum('over_amount');

        $this->db->from('vinyl_purchase');
        $this->cek_null($cur,"vinyl_purchase.currency");
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        $this->db->where('vinyl_purchase.approved', 1);
        $res = $this->db->get()->row_array();
        
        return intval($res['total']+$res['costs']);
    }
    
    function report_product($vendor=null,$cur,$start,$end,$status)
    {
        $this->db->select('vinyl_purchase.id, vinyl_purchase.no, vinyl_purchase.dates, vinyl_purchase.acc, vinyl_purchase.docno, vinyl_purchase.vendor, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vinyl_purchase.currency, vinyl_purchase.user, vinyl_purchase.log,
                           vinyl_purchase.sid, vinyl_purchase.remarks,
                           vinyl_purchase.notes, vinyl_purchase.shipping_date, vinyl_purchase.status,
                           
                           vinyl_purchase_item.product, vinyl_purchase_item.qty, vinyl_purchase_item.unit,
                           vinyl_purchase_item.price, vinyl_purchase_item.tax, vinyl_purchase_item.amount');

        $this->db->from('vinyl_purchase, vinyl_purchase_item, vendor');
        $this->db->where('vinyl_purchase.vendor = vendor.id');
        $this->db->where('vinyl_purchase_item.purchase_id = vinyl_purchase.no');
        $this->db->where("vinyl_purchase.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vinyl_purchase.vendor");
        $this->cek_null($cur,"vinyl_purchase.currency");
        $this->cek_null($status,"vinyl_purchase.status");

        $this->db->order_by('vinyl_purchase.dates', 'desc');
        return $this->db->get();
    }

//    =========================================  REPORT  =================================================================

}

?>