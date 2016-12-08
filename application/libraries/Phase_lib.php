<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phase_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'phase';


//    fungsi di panggil ketika ada po yg masih blm approved ketika hendak closing harian
    function get_last($po)
    {
        $this->ci->db->select('id, contract, no, dates, amount, status');
        $this->ci->db->from($this->table);
        $this->ci->db->where('contract', $po);
        $this->ci->db->order_by('id', 'asc'); 
        return $this->ci->db->get(); 
    }

}

/* End of file Property.php */