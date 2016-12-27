<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nsoverpayment {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function delete($so,$arpayment)
    {
        $this->ci->db->where('nsales_no', $so);
        $this->ci->db->where('nar_payment', $arpayment);
        $this->ci->db->delete('nsales_over_payment');
    }

    function delete_by_no($no)
    {
        $this->ci->db->where('no', $no);
        $this->ci->db->delete('nsales_over_payment');
    }

    function add($cust,$sales,$ar_payment,$balance,$over,$currency)
    {
        $res = array('no' => $this->counter(), 'customer' => $cust, 'nsales_no' => $sales, 'nar_payment' => $ar_payment, 'balance' => $balance, 'over' => $over, 'currency' => $currency);
        $this->ci->db->insert('nsales_over_payment', $res);
    }

    function add_undo($no,$cust,$sales,$ar_payment,$balance,$over,$currency)
    {
        $res = array('no' => $no, 'customer' => $cust, 'nsales_no' => $sales, 'nar_payment' => $ar_payment, 'balance' => $balance, 'over' => $over, 'currency' => $currency);
        $this->ci->db->insert('nsales_over_payment', $res);
    }

    function get_by_id($id)
    {
        $this->ci->db->select('id, customer, no, nsales_no, nar_payment, balance, over, currency');
        $this->ci->db->from('nsales_over_payment');
        $this->ci->db->where('id', $id);
        return $this->ci->db->get()->row();
    }

    function get_by_no($id)
    {
        $this->ci->db->select('id, customer, no, nsales_no, nar_payment, balance, over, currency');
        $this->ci->db->from('nsales_over_payment');
        $this->ci->db->where('no', $id);
        return $this->ci->db->get()->row();
    }

    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get('nsales_over_payment')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    private function counter()
    {
        $this->ci->db->select_max('no');
        $test = $this->ci->db->get("nsales_over_payment")->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }

    function combo_all($cust=null,$currency='IDR')
    {
        $this->ci->db->select('id, no, nsales_no, nar_payment, over');
        $this->ci->db->where('customer', $cust);
        $this->ci->db->where('currency', $currency);
        $query = $this->ci->db->get('nsales_over_payment')->num_rows();

        if ($query>0)
        {
          $val = $this->ci->db->get('nsales_over_payment')->result();
          foreach($val as $row){$data['options'][$row->no] = 'NSOV-00'.$row->no.' / '.'NCR-00'.$row->nar_payment.' - '.number_format($row->over);}
        }
        else { $data['options'][''] = 'No Data'; }
        return $data;
    }

}

/* End of file Property.php */