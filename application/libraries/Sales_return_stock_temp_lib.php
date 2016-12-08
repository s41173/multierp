<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_return_stock_temp_lib {

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
        
        $this->clean_stock();
    }
    
    private function clean_stock()
    {       
       $this->ci->db->where('qty', 0);
       $this->ci->db->delete('stock');
    }
    
    function valid_stock($pid,$date,$aqty)
    {
      $this->ci->db->where('product_id', $pid);
      $this->ci->db->where('dates', $date);
      $this->ci->db->where('qty >=', $aqty);
      $num = $this->ci->db->get('stock')->num_rows();
      if ($num > 0){ return TRUE; }else{ return FALSE; }
    }
           
    
    function get_first_stock($pid)
    {
        $this->ci->db->where('product_id', $pid);
        $num = $this->ci->db->get('stock_temp')->num_rows();
        if ($num > 0)
        {
            $this->ci->db->where('product_id', $pid);
            $this->ci->db->limit(1);
            $this->ci->db->order_by('dates', 'asc');
            $val = $this->ci->db->get('stock_temp')->row(); 
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
    
    
    // stock
}

/* End of file Property.php */