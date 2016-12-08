<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Balance_account_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->bl = new Balance();
    }

    private $ci;
    private $table = 'balances';
    private $bl,$num;

    
    function create($acc,$month=0,$year=0,$begin=0,$end=0)
    {
       $this->ci->db->where('account_id',$acc);
       $this->ci->db->where('month',$month);
       $this->ci->db->where('year',$year);
       $query = $this->ci->db->get($this->table)->num_rows();
//       echo $acc.' : '.$month.'-'.$year.' -- '.$query.'<br>';
       
       if ($query == 0)
       {
//           
//            echo $query.' insert <br>';
           $this->fill($acc, $month, $year, $begin, $end);
       }
       else
       {
          $this->edit($acc, $month, $year, $begin, $end);
////           echo 'edit <br>';
       }
    }
    
    private function edit($acc,$month=0,$year=0,$begin=0,$end=0)
    {
       $trans = array('beginning' => $begin, 'end' => $end);
       $this->ci->db->where('account_id', $acc);
       $this->ci->db->where('month', $month);
       $this->ci->db->where('year', $year);
       $this->ci->db->update($this->table, $trans); 
    }
    
    function fill($acc,$month,$year,$begin=0,$end=0)
    {
       $this->ci->db->where('account_id',$acc);
       $this->ci->db->where('month',$month);
       $this->ci->db->where('year',$year);
       $num = $this->ci->db->get($this->table)->num_rows();
       
       if ($num == 0)
       {
          $trans = array('account_id' => $acc, 'month' => $month, 'year' => $year, 'beginning' => $begin, 'end' => $end);
          $this->ci->db->insert($this->table, $trans); 
       }
    }



}

/* End of file Property.php */