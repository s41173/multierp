<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_return_temp_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

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
    }
    
    function min_stock($pid,$date,$transdate=null,$aqty,$so=0,$type='S')
    {
        $this->ci->db->where('product_id', $pid);
        $this->ci->db->where('dates', $date);
        $val = $this->ci->db->get('stock')->row();
        $qty = intval($val->qty - $aqty);

        $res = array('qty' => $qty);
        $this->ci->db->where('id', $val->id);
        $this->ci->db->update('stock', $res);
        
        if ($so != 0){ $this->clean_stock($val->id,$pid,$date,$transdate,$aqty,$val->amount,$so); }
        else { $this->ci->db->where('qty', 0); $this->ci->db->delete('stock'); }
    }
    
    function add_stock_temp($pid,$aqty,$so)
    {
        $this->ci->db->where('product_id', $pid);
        $this->ci->db->where('purchase_return', $so);
        $res = $this->ci->db->get('purchase_return_stock')->row();
        
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
        $this->ci->db->where('purchase_return', $so);
        $val = $this->ci->db->get('purchase_return_stock')->row();
        $qty = intval($val->qty - $aqty);

        $res = array('qty' => $qty);
        $this->ci->db->where('id', $val->id);
        $this->ci->db->update('purchase_return_stock', $res);
        
        $this->do_rollback($val->id, $aqty);
        $this->clean_stock_temp();
    }
    
    function rollback_stock($no)
    {
        $this->ci->db->where('purchase_return', $no);
        $result = $this->ci->db->get('purchase_return_stock')->result();
        
        foreach ($result as $res)
        {
            $this->do_rollback($res->id, $res->qty, $res->purchase_return);
        }
        
        $this->ci->db->where('purchase_return', $no);
        $this->ci->db->delete('purchase_return_stock');
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
           $this->ci->db->where('purchase_return', $so);
           $val = $this->ci->db->get('purchase_return_stock')->row();
           $trans = array('id' => $id,'product_id' => $val->product_id, 'dates' => $val->dates, 'qty' => $val->qty, 'amount' => $val->amount);
           $this->ci->db->insert('stock', $trans);
       }
    }
            
    function get_amount_stock($no)
    {
      $this->ci->db->select('sum(amount*qty) as amount');
      $this->ci->db->where('purchase_return', $no);
      $val = $this->ci->db->get('purchase_return_stock')->row_array();
      return intval($val['amount']);
    }
    
    function get_first_stock($pid)
    {
        $this->ci->db->where('product_id', $pid);
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
        $this->ci->db->where('purchase_return', $so);
        $res = $this->ci->db->get('purchase_return_stock')->row();
        return $res->amount;
    }
    
    private function clean_stock($id,$pid,$date,$transdate,$qty,$amount,$so)
    {       
       $trans = array('id' => $id, 'product_id' => $pid, 'dates' => $date, 'transdates' => $transdate, 
                      'qty' => $qty, 'amount' => $amount, 'purchase_return' => $so);
       
       $this->ci->db->insert('purchase_return_stock', $trans);
       
       $this->ci->db->where('qty', 0);
       $this->ci->db->delete('stock');
    }
    
    private function clean_stock_temp()
    {        
        $this->ci->db->where('qty', 0);
        $this->ci->db->delete('stock');
    }
    
    function get_sum_stock($pid)
    {
       $this->ci->db->select_sum('amount');
       $this->ci->db->where('product_id', $pid);
       $res = $this->ci->db->get('stock')->row_array();  
       return intval($res['amount']);
    }
    
    function get_beginning_balance($product,$start,$end)
    {
       $this->ci->db->select_sum('qty');
       $this->ci->db->select_sum('amount');
       $this->ci->db->where('product_id', $product);
       if ($start != null){ $this->ci->db->where('transdates >=', $start); }
       $this->ci->db->where('transdates <', $end);
       return $this->ci->db->get('purchase_return_stock')->row_array();
    }
    
    // stock
    
    // temp stock
    
    function get_temp_stock($po)
    {
        $this->ci->db->select('id, product_id, dates, transdates, qty, amount, purchase_return');
        $this->ci->db->where('purchase_return', $po);
        return $this->ci->db->get('purchase_return_stock')->result();
    }
}

/* End of file Property.php */