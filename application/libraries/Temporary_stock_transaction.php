<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Temporary_stock_transaction {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'temporary_stock_transaction';
    }

    private $ci,$table;


    //    ======================= relation cek  =====================================

    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $this->ci->db->where('code', 'SO');
       $query = $this->ci->db->get('ar_payment_trans')->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======

    function closing()
    {
        $this->ci->db->select('id');
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get($this->table)->result();

        foreach ($query as $value)
        { $this->delete($value->id); }
    }

    private function delete($po)
    {
       $this->ci->db->where('id', $po);
       $this->ci->db->delete($this->table);
    }
	
	function add($date, $product, $qty, $unit, $type, $user, $staff, $approved, $log=null)
    {
       $trans = array('dates' => $date, 'product' => $product, 'qty' => $qty, 'unit' => $unit, 'type' => $type, 'user' => $user, 'staff' => $staff,
	                  'approved' => $approved, 'log' => $log);
					  
        $this->ci->db->insert($this->table, $trans);
    }


}

/* End of file Property.php */