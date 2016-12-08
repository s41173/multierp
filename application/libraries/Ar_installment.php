<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ar_installment {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'ar_installment';
    }

    private $ci,$table;

    function add($sales,$ar_payment,$dates,$amount)
    {
       $result = array('sales_no' => $sales, 'ar_payment' => $ar_payment, 'no' => $this->counter($sales), 'dates' => $dates, 'amount' => $amount);
       $this->ci->db->insert($this->table, $result);
    }
   
    function delete($sales,$ar_payment,$dates)
    {
       $this->ci->db->where('sales_no', $sales);
       $this->ci->db->where('ar_payment', $ar_payment);
       $this->ci->db->where('dates', $dates);
       $this->ci->db->delete($this->table);
    }

    private function counter($so)
    {
        $this->ci->db->select_max('no');
        $this->ci->db->where('sales_no', $so);
        $test = $this->ci->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }


}

/* End of file Property.php */