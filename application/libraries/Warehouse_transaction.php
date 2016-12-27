<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_transaction {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->product = new Products_lib();
    }

    private $ci,$product;
    private $table = 'warehouse_transaction';

//  $date = tanggal, $code = PJ/ SJ, $product = product, $in = 0, $out = 0;


    function get_amount($no)
    {
//        $this->ci->db->select('dates,code,currency,amount');
        $this->ci->db->select_sum('amount');
        $this->ci->db->where('code', $no);
        $res = $this->ci->db->get($this->table)->row_array();
        return intval($res['amount']);
    }
    
    function add($date, $code, $cur='IDR', $product, $in=0, $out=0, $price=0, $amount=0, $log=null)
    {
        $balance = $this->product->get_qty($product);
        $opening = 0;
        if ($in>0){ $opening = $balance - $in; }elseif ($out > 0){ $opening = $balance + $out; }
        
        $trans = array('dates' => $date, 'code' => $code, 'currency' => $cur, 'product' => $product, 'in' => $in, 'out' => $out, 'price' => $price, 
                       'amount' => $amount, 'open' => $opening, 'balance' => $balance, 'log' => $log);
        $this->ci->db->insert('warehouse_transaction', $trans);
    }

//    ============================  remove transaction journal ==============================

    function remove($dates,$codetrans,$product)
    {
        // ============ update transaction ===================
        $this->ci->db->where('dates', $dates);
        $this->ci->db->where('code', $codetrans);
        $this->ci->db->where('product', $product);
        $this->ci->db->delete('warehouse_transaction');
        // ====================================================
    }
    
    function get_monthly($product,$month=0,$year=0)
    {
        $this->ci->db->select('code, dates, in, out, balance, log');
        $this->ci->db->where('product', $product);
        $this->ci->db->where('MONTH(dates)', $month);
        $this->ci->db->where('YEAR(dates)', $year);
        $this->ci->db->order_by('id','asc');
        return $this->ci->db->get($this->table);
    }
    
    function get_opening($product,$start,$end)
    {
        $this->ci->db->select('id, dates, in, out, open, balance, log');
        $this->ci->db->where('product', $product);
        $this->ci->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->ci->db->order_by('id','asc');
        $this->ci->db->limit(1);
        return $this->ci->db->get($this->table)->row();
    }
    
    function get_beginning_balance($product,$start,$end)
    {
       $this->ci->db->select_sum('in', 'ins');
       $this->ci->db->select_sum('amount');
       $this->ci->db->where('product', $product);
       if ($start != null){ $this->ci->db->where('dates >=', $start); }
       $this->ci->db->where('dates <', $end);
       return $this->ci->db->get($this->table)->row_array();
    }
    
    function get_transaction($product,$start,$end)
    {
        $this->ci->db->select('id, code, currency, product, dates, in, out, open, price, balance, amount, log');
        $this->ci->db->where('product', $product);
        $this->ci->db->where("dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
//        $this->ci->db->order_by('id','asc');
        $this->ci->db->order_by('dates','asc');
        return $this->ci->db->get($this->table);
    }
    
    function get_sum_transaction_open_balance($product,$start,$type=null)
    {
        $this->ci->db->select_sum('in', 'ins');
        $this->ci->db->select_sum('out', 'outs');
        $this->ci->db->where('product', $product);
        $this->ci->db->where('dates <', $start);
        $this->ci->db->where('MONTH(dates)', split_date($start,'n'));
        $this->ci->db->where('YEAR(dates)', split_date($start,'Y'));
        $res = $this->ci->db->get($this->table)->row_array();
        if ($type == 'in'){ return $res['ins']; }
        elseif ($type == 'out'){ return $res['outs']; }
        else{ return @intval($res['ins']-$res['outs']); }
    }
    
    function get_sum_transaction_open_amount($product,$start,$type=null)
    {
        $this->ci->db->select_sum('amount');
        $this->ci->db->where('product', $product);
        $this->ci->db->where('dates <', $start);
        $this->ci->db->where('MONTH(dates)', split_date($start,'n'));
        $this->ci->db->where('YEAR(dates)', split_date($start,'Y'));
        if ($type == 'in'){ $this->ci->db->where('in >', 0); }
        if ($type == 'out'){ $this->ci->db->where('out >', 0); }
        $res = $this->ci->db->get($this->table)->row_array();
        return intval($res['amount']);
    }
    
     // closing function
    function get_sum_transaction_balance($product,$month,$year,$type=null)
    {
        $this->ci->db->select_sum('amount');
        $this->ci->db->where('product', $product);
        $this->ci->db->where('MONTH(dates)', $month);
        $this->ci->db->where('YEAR(dates)', $year);
        if ($type == 'in'){ $this->ci->db->where('in >', 0); }
        if ($type == 'out'){ $this->ci->db->where('out >', 0); }
        $res = $this->ci->db->get($this->table)->row_array();
        return intval($res['amount']);
    }
    
    function get_sum_transaction_qty($product,$month,$year)
    {
        $this->ci->db->select_sum('in', 'ins');
        $this->ci->db->select_sum('out', 'outs');
        $this->ci->db->where('product', $product);
        $this->ci->db->where('MONTH(dates)', $month);
        $this->ci->db->where('YEAR(dates)', $year);
        $res = $this->ci->db->get($this->table)->row_array();
        return intval($res['ins']-$res['outs']);
    }
    
    // closing function
    
    // category based
    function get_transaction_based_category($cat,$month,$year,$type)
    {
        $this->ci->db->select_sum('warehouse_transaction.in', 'ins');
        $this->ci->db->select_sum('warehouse_transaction.out', 'outs');
        
        $this->ci->db->from("warehouse_transaction, product");
        $this->ci->db->where("warehouse_transaction.product = product.id");
//        $this->ci->db->where('product.category', $cat);
        $this->cek_null($cat, 'product.category');
        if ($type == 'in'){ $this->ci->db->where('warehouse_transaction.in >', 0); }
        elseif ($type == 'out'){ $this->ci->db->where('warehouse_transaction.out >', 0); }
//        $this->ci->db->where('MONTH(warehouse_transaction.dates)', $month);
        $this->cek_null($month, 'MONTH(warehouse_transaction.dates)');
        $this->ci->db->where('YEAR(warehouse_transaction.dates)', $year);
        
        $res = $this->ci->db->get()->row_array();
        if ($type == 'in'){ return intval($res['ins']); }
        elseif ($type == 'out'){ return intval($res['outs']); }
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }
}

/* End of file Property.php */