<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'purchase';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_purchase($limit, $offset)
    {
        $this->db->select('purchase.id, purchase.no, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, purchase.user, purchase.status,
                           purchase.total, purchase.p2, purchase.costs, purchase.notes, purchase.currency, purchase.approved');
        
        $this->db->from('purchase, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->db->order_by('purchase.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$vendor,$date)
    {
        $this->db->select('purchase.id, purchase.no, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, purchase.user, purchase.status,
                           purchase.total, purchase.p2, purchase.costs, purchase.notes, purchase.currency, purchase.approved');

        $this->db->from('purchase, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->cek_null($no,"purchase.no");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($date,"purchase.dates");
        return $this->db->get();
    }

    function get_purchase_list($currency=null,$vendor=null,$st=0)
    {
        $this->db->select('purchase.id, purchase.no, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, purchase.user, purchase.status,
                           purchase.total, purchase.p2, purchase.notes, purchase.currency, purchase.approved');

        $this->db->from('purchase, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->cek_null($currency,"purchase.currency");
        $this->db->where('purchase.stock_in_stts', $st);
        $this->cek_null($vendor,"purchase.vendor");
        $this->db->where('purchase.approved', 1);
        
        $this->db->order_by('purchase.dates', 'asc');
        return $this->db->get();
    }

    function get_purchase_list_all($currency=null,$vendor=null,$st=0)
    {
        $this->db->select('purchase.id, purchase.no, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, purchase.user, purchase.status,
                           purchase.total, purchase.p2, purchase.notes, purchase.currency, purchase.approved');

        $this->db->from('purchase, vendor');
//        $this->db->from('purchase, vendor, stock_in');
        $this->db->where('purchase.vendor = vendor.id');
//        $this->db->where('purchase.no = stock_in.purchase');
        $this->cek_null($currency,"purchase.currency");
        $this->cek_null($vendor,"purchase.vendor");
        $this->db->where('purchase.approved', 1);
        $this->db->where('purchase.status', $st);
//        $this->db->where('stock_in.approved', 1);
//        $this->db->where('purchase.stock_in_stts', 1);

        $this->db->order_by('purchase.dates', 'asc');
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
        $this->db->select('purchase.id, purchase.no, purchase.demand, purchase.vendor, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, purchase.currency, purchase.user, purchase.log,
                           purchase.status, purchase.tax, purchase.discount, purchase.p1, purchase.p2, purchase.total, purchase.notes, purchase.desc,
                           purchase.shipping_date,purchase.costs, purchase.approved, purchase.ap_over, purchase.over_amount, purchase.stock_in_stts');

        $this->db->from('purchase, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->db->where('purchase.id', $uid);
        return $this->db->get();
    }

    function get_purchase_by_no($uid)
    {
        $this->db->select('purchase.id, purchase.no, purchase.demand, purchase.vendor, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vendor.city, purchase.currency, purchase.user, purchase.log, purchase.desc, purchase.shipping_date,
                           purchase.status, purchase.tax, purchase.p1, purchase.p2, purchase.total, purchase.notes, purchase.shipping_date,
                           purchase.costs, purchase.approved, purchase.ap_over, purchase.over_amount, purchase.stock_in_stts');

        $this->db->from('purchase, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->db->where('purchase.no', $uid);
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
        $this->db->select('purchase.id, purchase.no, purchase.demand, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           vendor.city, purchase.currency, purchase.user, purchase.log, purchase.desc,
                           purchase.status, purchase.tax, purchase.p1, purchase.p2, purchase.total, purchase.notes, purchase.shipping_date,
                           purchase.costs, purchase.approved, purchase.ap_over, purchase.over_amount, purchase.stock_in_stts');

        $this->db->from('purchase, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->db->where("purchase.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"purchase.currency");
        $this->cek_null($status,"purchase.status");
        $this->cek_null($acc,"purchase.acc");

        $this->db->where('purchase.approved', 1);
        $this->db->order_by('purchase.no', 'asc');
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

        $this->db->from('purchase, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->db->where("purchase.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($cur,"purchase.currency");
        $this->cek_null($status,"purchase.status");
        $this->cek_null($acc,"purchase.acc");
        $this->db->where('purchase.approved', 1);
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

        $this->db->from('purchase');
        $this->cek_null($cur,"purchase.currency");
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        $this->db->where('purchase.approved', 1);
        $res = $this->db->get()->row_array();
        
        return intval($res['total']+$res['costs']);
    }
    
    function report_product($product,$cur,$start,$end)
    {
        $this->db->select('purchase.id, purchase.no, purchase.demand, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           purchase.currency, purchase.user, purchase.log,
                           purchase.notes, purchase.shipping_date, purchase.status,
                           purchase_item.product, purchase_item.qty, purchase_item.price, purchase_item.tax, purchase_item.amount');

        $this->db->from('purchase, purchase_item, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->db->where('purchase_item.purchase_id = purchase.no');
        $this->db->where("purchase.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($product,"purchase_item.product");
        $this->cek_null($cur,"purchase.currency");

        $this->db->order_by('purchase.dates', 'desc');
        return $this->db->get();
    }
    
    function report_product_search($product,$cur)
    {
        $this->db->select('purchase.id, purchase.no, purchase.demand, purchase.dates, purchase.acc, purchase.docno, vendor.prefix, vendor.name, vendor.address, vendor.phone1, vendor.phone2,
                           purchase.currency, purchase.user, purchase.log,
                           purchase.notes, purchase.shipping_date, purchase.status,
                           purchase_item.product, purchase_item.qty, purchase_item.price, purchase_item.tax, purchase_item.amount');

        $this->db->from('purchase, purchase_item, vendor');
        $this->db->where('purchase.vendor = vendor.id');
        $this->db->where('purchase_item.purchase_id = purchase.no');
        $this->cek_null($product,"purchase_item.product");
        $this->cek_null($cur,"purchase.currency");

        $this->db->order_by('purchase.dates', 'desc');
        return $this->db->get();
    }

//    =========================================  REPORT  =================================================================

}

?>