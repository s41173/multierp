<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('product')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    // cek warehouse
    function cek_warehouse($ware)
    {
       $this->ci->db->where('warehouse_id', $ware);
       $query = $this->ci->db->get('product')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    // stock
    
    function add_stock($pid,$date,$qty,$price)
    {   
        $this->ci->db->where('product_id', $pid); 
        $this->ci->db->where('dates', $date); 
        $num = $this->ci->db->get('stock')->num_rows();
        
        if ($num > 0)
        {
            $this->ci->db->where('product_id', $pid); 
            $this->ci->db->where('dates', $date); 
            $val = $this->ci->db->get('stock')->row();
            $qty = intval($qty + $val->qty);

            $res = array('qty' => $qty);
            $this->ci->db->where('id', $val->id);
            $this->ci->db->update('stock', $res);
        }
        else
        {
            $trans = array('product_id' => $pid, 'dates' => $date, 'qty' => $qty, 'amount' => $price);
            $this->ci->db->insert('stock', $trans); 
        }
        
        $this->cleaning();
    }
    
    function cleaning()
    {
       $this->ci->db->where('qty', 0); 
       $this->ci->db->delete('stock'); 
    }
    
    function min_stock($pid,$date,$transdate=null,$aqty,$so=0,$type='S')
    {
        
        $this->ci->db->where('product_id', $pid); 
        $this->ci->db->where('dates', $date); 
        $num = $this->ci->db->get('stock')->num_rows();
        
        if ($num > 0)
        {
           $this->ci->db->where('product_id', $pid);
           $this->ci->db->where('dates', $date);
           $val = $this->ci->db->get('stock')->row();
           $qty = intval($val->qty - $aqty);

           $res = array('qty' => $qty);
           $this->ci->db->where('id', $val->id);
           $this->ci->db->update('stock', $res);

           if ($so != 0){ $this->clean_stock($val->id,$pid,$date,$transdate,$aqty,$val->amount,$so,$type); }
           else { $this->ci->db->where('qty', 0); $this->ci->db->delete('stock'); }
        }
    }
    
    function valid_stock($pid,$date,$aqty)
    {
      $this->ci->db->where('product_id', $pid);
      $this->ci->db->where('dates', $date);
      $this->ci->db->where('qty >=', $aqty);
      $num = $this->ci->db->get('stock')->num_rows();
      if ($num > 0){ return TRUE; }else{ return FALSE; }
    }
    
    function add_stock_temp($pid,$aqty,$so)
    {
        $this->ci->db->where('product_id', $pid);
        $this->ci->db->where('sales_no', $so);
        $res = $this->ci->db->get('stock_temp')->row();
        
        $this->ci->db->where('id', $res->id);
        $val = $this->ci->db->get('stock')->row();
        $qty = intval($val->qty - $aqty);
        
        $ress = array('qty' => $qty);
        $this->ci->db->where('id', $val->id);
        $this->ci->db->update('stock', $ress);
        
        $this->clean_stock($val->id,$pid,$val->dates,$aqty,$val->amount,$so);
    }
    
    function min_stock_temp($pid,$aqty,$so)
    {
        $this->ci->db->where('product_id', $pid);
        $this->ci->db->where('sales_no', $so);
        $val = $this->ci->db->get('stock_temp')->row();
        $qty = intval($val->qty - $aqty);

        $res = array('qty' => $qty);
        $this->ci->db->where('id', $val->id);
        $this->ci->db->update('stock_temp', $res);
        
        $this->do_rollback($val->id, $aqty);
        $this->clean_stock_temp();
    }
    
    function rollback_stock($no)
    {
        $this->ci->db->where('sales_no', $no);
        $result = $this->ci->db->get('stock_temp')->result();
        
        foreach ($result as $res)
        {
            $this->do_rollback($res->id, $res->qty, $res->sales_no);
        }
        
        $this->ci->db->where('sales_no', $no);
        $this->ci->db->delete('stock_temp');
    }
    
    private function do_rollback($id,$qty,$so)
    {
       $this->ci->db->where('id', $id); 
       $num = $this->ci->db->get('stock')->num_rows();
       if ($num > 0)
       {
           $this->ci->db->where('id', $id);
           $val = $this->ci->db->get('stock')->row();
           $qty = $val->qty + $qty;
           $res = array('qty' => $qty);
           $this->ci->db->where('id', $id);
           $this->ci->db->update('stock', $res);
       }
       else
       {
           $this->ci->db->where('id', $id); 
           $this->ci->db->where('sales_no', $so);
           $val = $this->ci->db->get('stock_temp')->row();
           $trans = array('id' => $id,'product_id' => $val->product_id, 'dates' => $val->dates, 'qty' => $val->qty, 'amount' => $val->amount);
           $this->ci->db->insert('stock', $trans);
       }
    }
            
    function get_amount_stock($no)
    {
      $this->ci->db->select('sum(amount*qty) as amount');
      $this->ci->db->where('sales_no', $no);
      $val = $this->ci->db->get('stock_temp')->row_array();
      return intval($val['amount']);
    }
    
    function get_first_stock($pid)
    {
        $this->ci->db->where('product_id', $pid);
//        $this->ci->db->limit(1);
        $num = $this->ci->db->get('stock')->num_rows();
        if ($num > 0)
        {
            $this->ci->db->where('product_id', $pid);
            $this->ci->db->order_by('dates', 'asc');
            $this->ci->db->limit(1);
            $val = $this->ci->db->get('stock')->row(); 
            return $val;
        }
        else{ return null; }
    }
    
    function get_stock($pid,$date)
    {
        $this->ci->db->where('product_id', $pid);
        $this->ci->db->where('dates', $date);
        $this->ci->db->limit(1);
        $num = $this->ci->db->get('stock')->num_rows();
        if ($num > 0)
        {
            $this->ci->db->order_by('dates', 'asc');
            $val = $this->ci->db->get('stock')->row(); 
            return $val;
        }
        else{ return null; }
    }
    
    function get_out_stock($pid,$so=0)
    {
        $this->ci->db->where('product_id', $pid);
        $this->ci->db->where('sales_no', $so);
        $res = $this->ci->db->get('stock_temp')->row();
        return $res->amount;
    }
    
    private function clean_stock($id,$pid,$date,$transdate,$qty,$amount,$so,$type)
    {       
       $trans = array('id' => $id, 'product_id' => $pid, 'dates' => $date, 'transdates' => $transdate, 
                      'qty' => $qty, 'amount' => $amount, 'sales_no' => $so);
       
       if ($type == 'S'){ $this->ci->db->insert('stock_temp', $trans); }
       elseif ($type == 'A'){ $this->ci->db->insert('assembly_stock_temp', $trans); }
       
       $this->ci->db->where('qty', 0);
       $this->ci->db->delete('stock');
    }
    
    private function clean_stock_temp()
    {        
        $this->ci->db->where('qty', 0);
        $this->ci->db->delete('stock');
    }
    
    function clean_assembly_stock_temp($no=0)
    {        
        $this->ci->db->where('sales_no', $no);
        $this->ci->db->delete('assembly_stock_temp');
    }
    
    function get_sum_stock($pid)
    {
       $this->ci->db->where('product_id', $pid);
       return $this->ci->db->get('stock')->result();  
    }
    
    function get_opening_stock($pid,$dates)
    {
       $this->ci->db->select_sum('amount');
       $this->ci->db->select_sum('qty');
       $this->ci->db->where('product_id', $pid);
       $this->ci->db->where('dates <', $dates);
       $res = $this->ci->db->get('stock')->row_array();  
       return $res;
    }
    
    function get_end_date_stock($pid,$start)
    {
       $this->ci->db->select('dates');
       $this->ci->db->where('product_id', $pid);
       $this->ci->db->where('dates <', $start);
       $this->ci->db->order_by('dates','desc');
       $this->ci->db->limit(1);
       $res = $this->ci->db->get('stock')->row();
       if ($res){ return $res->dates; }else { return null; }
       
    }
    
    // stock
    
    // stock temp lib
    
    function get_beginning_balance($product,$start,$end)
    {
       $this->ci->db->select_sum('qty');
       $this->ci->db->select_sum('amount');
       $this->ci->db->where('product_id', $product);
       if ($start != null){ $this->ci->db->where('transdates >=', $start); }
       $this->ci->db->where('transdates <', $end);
       return $this->ci->db->get('stock_temp')->row_array();
    }
    
    // stock temp lib
    
    function add_qty($product=null,$amount_qty=null,$amount=0)
    {
        $this->ci->db->where('id', $product);
        $val = $this->ci->db->get('product')->row();
        $qty = $val->qty + $amount_qty;
        $amt = $val->amount + $amount;

        $res = array('qty' => $qty, 'amount' => $amt);
        $this->ci->db->where('id', $product);
        $this->ci->db->update('product', $res);
    }

    function min_qty($product=null,$amount_qty=null,$amount=0)
    {
        $this->ci->db->where('id', $product);
        $val = $this->ci->db->get('product')->row();
        $qty = $val->qty - $amount_qty;
        $amt = $val->amount - $amount;

        $res = array('qty' => $qty, 'amount' => $amt);
        $this->ci->db->where('id', $product);
        $this->ci->db->update('product', $res);
    }

    function edit_price($product=null,$price=0)
    {
        $this->ci->db->where('id', $product);
        $val = $this->ci->db->get('product')->row();

        $res = array('buying' => $price, 'hpp' => $price);
        $this->ci->db->where('id', $product);
        $this->ci->db->update('product', $res);
    }

    function valid_qty($pid,$qty)
    {
       $this->ci->db->select('id, name, qty');
       $this->ci->db->where('id', $pid);
       $res = $this->ci->db->get('product')->row();
       if ($res->qty - $qty < 0){ return FALSE; } else { return TRUE; }
    }

    function get_details($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('id, name, qty');
           $this->ci->db->where('id', $id);
           return $this->ci->db->get('product')->row();
        }
    }

    function get_id($name=null)
    {
        if ($name)
        {
           $this->ci->db->select('id, name, qty');
           $this->ci->db->where('name', $name);
           $res = $this->ci->db->get('product')->row();
           return $res->id;
        }
    }
    
    function get_currency($name=null)
    {
        if ($name)
        {
           $this->ci->db->select('id, name, qty, currency');
           $this->ci->db->where('name', $name);
           $res = $this->ci->db->get('product')->row();
           return $res->currency;
        }
    }

    function get_name($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('id, name, qty');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get('product')->row();
           return $res->name;
        }
    }

    function get_unit($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('unit');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get('product')->row();
           return $res->unit;
        }
    }

    function get_qty($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('qty');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get('product')->row();
           return $res->qty;
        }
    }
    
    function get_category($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('category');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get('product')->row();
           return $res->category;
        }
    }

    function get_price($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('price');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get('product')->row();
           return $res->price;
        }
    }
    
    function get_hpp($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('hpp');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get('product')->row();
           return $res->hpp;
        }
    }
    
    function get_buying($id=null)
    {
        if ($id)
        {
           $this->ci->db->select('buying');
           $this->ci->db->where('id', $id);
           $res = $this->ci->db->get('product')->row();
           return $res->buying;
        }
    }

    function get_all()
    {
      $this->ci->db->select('id, name, qty, unit');
      $this->ci->db->order_by('name', 'asc');
      return $this->ci->db->get('product');
    }
    
    function get_unit_cost($pid,$dates)
    { 
        $this->ci->db->where('product_id', $pid);
        $this->ci->db->where('dates', $dates);
        $res = $this->ci->db->get('stock')->row();
        if ($res)
        { return $res->amount; }
        else
        {
            // get hpp manual
            $sum = $this->get_sum_stock($pid);
            $qty = intval($this->get_qty($pid));

            $i=0;
            if ($sum){ foreach ($sum as $res){  $i = $i + intval($res->amount * $res->qty); } }
            else {$i = 0;}

            return @($i/$qty);
        }
    }

}

/* End of file Property.php */