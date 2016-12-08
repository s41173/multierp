<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_ledger_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ps = new Period_lib();
        $this->product = new Products_lib();
        $this->wt = new Warehouse_transaction();
    }

    private $ci;
    private $table = 'stock_ledger';
    private $bl,$num,$ps,$product,$wt;

    
    function create($pid,$month=0,$year=0,$open_qty=0,$end_qty=0,$open_balance=0,$end_balance=0)
    {
       $this->ci->db->where('product_id',$pid);
       $this->ci->db->where('month',$month);
       $this->ci->db->where('year',$year);
       $query = $this->ci->db->get($this->table)->num_rows();
//       echo $acc.' : '.$month.'-'.$year.' -- '.$query.'<br>';
       
       if ($query == 0)
       {
//           
//            echo $query.' insert <br>';
           $this->fill($pid, $month, $year, $open_qty, $end_qty, $open_balance, $end_balance);
       }
       else
       {
          $this->edit($pid, $month, $year, $open_qty, $end_qty, $open_balance, $end_balance);
////           echo 'edit <br>';
       }
    }
    
    private function edit($pid,$month=0,$year=0,$begin=0,$end=0,$open_balance=0, $end_balance=0)
    {
       $trans = array('open_qty' => $begin, 'end_qty' => $end, 'open_balance' => $open_balance, 'end_balance' => $end_balance);
       $this->ci->db->where('product_id', $pid);
       $this->ci->db->where('month', $month);
       $this->ci->db->where('year', $year);
       $this->ci->db->update($this->table, $trans); 
    }
    
    function fill($pid,$month,$year,$begin=0,$end=0,$open_balance=0, $end_balance=0)
    {
       $this->ci->db->where('product_id', $pid);
       $this->ci->db->where('month', $month);
       $this->ci->db->where('year', $year);
       $num = $this->ci->db->get($this->table)->num_rows();
       
       if ($num == 0)
       {
          $trans = array('product_id' => $pid, 'month' => $month, 'year' => $year, 'open_qty' => $begin, 'end_qty' => $end, 'open_balance' => $open_balance, 'end_balance' => $end_balance);
          $this->ci->db->insert($this->table, $trans); 
       }
    }

    function get_trans($pid,$month,$year,$type=null)
    {
       $this->ci->db->where('product_id', $pid);
       $this->ci->db->where('month', $month);
       $this->ci->db->where('year', $year);
       $res = $this->ci->db->get($this->table)->row();
       
       if ($res)
       {
         if ($type == 'openqty'){ return ($res->open_qty); }
         elseif ($type == 'endqty'){ return ($res->end_qty); } 
         elseif ($type == 'open_balance'){ return ($res->open_balance); } 
         elseif ($type == 'end_balance'){ return ($res->end_balance); } 
       }
       else { return 0; }
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }
    
    function get_trans_by_category($cat,$month,$year,$type=null)
    {
       $this->ci->db->select_sum("open_qty"); 
       $this->ci->db->select_sum("open_balance");
       $this->ci->db->from("$this->table, product");
       $this->ci->db->where($this->table.'.product_id = product.id');
//       $this->ci->db->where('product.category', $cat);
       $this->cek_null($cat, 'product.category');
       $this->cek_null($month, 'month');
//       $this->ci->db->where('month', $month);
       $this->ci->db->where('year', $year);
       $res = $this->ci->db->get();
       $res = $res->row_array();
       
       if ($type == 'openqty'){return intval($res['open_qty']); }
       elseif ($type == 'open_balance'){ return intval($res['open_balance']); }
    }
    
    function get_sum_trans($month,$year,$type=null)
    {
       $this->ci->db->where('month', $month);
       $this->ci->db->where('year', $year);
       $res = $this->ci->db->get($this->table)->row();
       
       if ($res)
       {
         if ($type == 'openqty'){ return ($res->open_qty); }
         elseif ($type == 'endqty'){ return ($res->end_qty); } 
         elseif ($type == 'open_balance'){ return ($res->open_balance); } 
         elseif ($type == 'end_balance'){ return ($res->end_balance); } 
       }
       else { return 0; }
    }
    
    function cek_month($month){ if ($month == 12) { return 1; }else { return $month+1; } }
    
    function cek_year($month,$year){ if ($month == 12){ return $year+1; }else{ return $year; }  }
    
    private function next_period()
    {
        $ps = new Period();
        $ps = $ps->get();
        
        $month = $ps->month;
        $year = $ps->year;
        
        if ($month == 12){$nmonth = 1;}else { $nmonth = $month +1; }
        if ($month == 12){ $nyear = $year+1; }else{ $nyear = $year; }
        $res[0] = $nmonth; $res[1] = $nyear;
        return $res;
    }
    
    function closing()
    {
        $next = $this->next_period();
        $month = $this->ps->get('month');
        $year = $this->ps->get('year');
        $nextmonth = $next[0];
        $nextyear = $next[1];
        
        $products = $this->product->get_all()->result();
        
        foreach ($products as $res)
        {
            $qty = $this->wt->get_sum_transaction_qty($res->id,$month,$year);
            $trans = intval($this->wt->get_sum_transaction_balance($res->id,$month,$year,'in')-$this->wt->get_sum_transaction_balance($res->id,$month,$year,'out'));
            
            $openqty = $this->get_trans($res->id, $month, $year, 'openqty');
            $openbalance = $this->get_trans($res->id, $month, $year, 'open_balance');
            
            // edit end saldo bulan ini
            $this->create($res->id, $month, $year, $openqty, $openqty+$qty, $openbalance, $openbalance+$trans);
            
            // create saldo next month
            $this->create($res->id, $nextmonth, $nextyear, $openqty+$qty, 0, $openbalance+$trans, 0);
        }
        
    }
    
    function edit_begin_saldo($product,$month,$year,$openqty,$openbalance)
    {
        
       $this->ci->db->where('product_id',$product);
       $this->ci->db->where('month',$month);
       $this->ci->db->where('year',$year);
       $query = $this->ci->db->get($this->table)->num_rows(); 
       
       if ($query > 0)
       {
          $trans = array('open_qty' => $openqty, 'open_balance' => $openbalance);
          $this->ci->db->where('product_id', $product);
          $this->ci->db->where('month', $month);
          $this->ci->db->where('year', $year);
          $this->ci->db->update($this->table, $trans);   
       }
       else 
       { 
          $this->create($product, $month, $year); 
          
          $trans = array('open_qty' => $openqty, 'open_balance' => $openbalance);
          $this->ci->db->where('product_id', $product);
          $this->ci->db->where('month', $month);
          $this->ci->db->where('year', $year);
          $this->ci->db->update($this->table, $trans);   
       }
    }
}

/* End of file Property.php */