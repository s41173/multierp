<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Soverpayment {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function delete($so,$arpayment)
    {
        $this->ci->db->where('sales_no', $so);
        $this->ci->db->where('ar_payment', $arpayment);
        $this->ci->db->delete('sales_over_payment');
    }

    function delete_by_no($no)
    {
        $this->ci->db->where('no', $no);
        $this->ci->db->delete('sales_over_payment');
    }

    function add($cust,$sales,$ar_payment,$balance,$over,$currency)
    {
        $res = array('no' => $this->counter(), 'customer' => $cust, 'sales_no' => $sales, 'ar_payment' => $ar_payment, 'balance' => $balance, 'over' => $over, 'currency' => $currency);
        $this->ci->db->insert('sales_over_payment', $res);
    }

    function add_undo($no,$cust,$sales,$ar_payment,$balance,$over,$currency)
    {
        $res = array('no' => $no, 'customer' => $cust, 'sales_no' => $sales, 'ar_payment' => $ar_payment, 'balance' => $balance, 'over' => $over, 'currency' => $currency);
        $this->ci->db->insert('sales_over_payment', $res);
    }

    function get_by_id($id)
    {
        $this->ci->db->select('id, customer, no, sales_no, ar_payment, balance, over, currency');
        $this->ci->db->from('sales_over_payment');
        $this->ci->db->where('id', $id);
        return $this->ci->db->get()->row();
    }

    function get_by_no($id)
    {
        $this->ci->db->select('id, customer, no, sales_no, ar_payment, balance, over, currency');
        $this->ci->db->from('sales_over_payment');
        $this->ci->db->where('no', $id);
        return $this->ci->db->get()->row();
    }

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('sales_over_payment')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    private function counter()
    {
        $this->ci->db->select_max('no');
        $test = $this->ci->db->get("sales_over_payment")->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }

    function combo_all($cust=null,$currency='IDR')
    {
        $this->ci->db->select('id, no, sales_no, ar_payment, over');
        $this->ci->db->where('customer', $cust);
        $this->ci->db->where('currency', $currency);
        $query = $this->ci->db->get('sales_over_payment')->num_rows();

        if ($query>0)
        {
          $val = $this->ci->db->get('sales_over_payment')->result();
          foreach($val as $row){$data['options'][$row->no] = 'SOV-00'.$row->no.' / '.'CR-00'.$row->ar_payment.' - '.number_format($row->over);}
        }
        else { $data['options'][''] = 'No Data'; }
        return $data;
    }

}

/* End of file Property.php */