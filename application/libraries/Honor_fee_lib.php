<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Honor_fee_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'honor_fee';
    }

    private $ci,$table;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function get($dept,$worktime)
    {
        $this->ci->db->select('amount');
        $this->ci->db->from($this->table);
        $this->ci->db->where('dept', $dept);
        $this->ci->db->where('work_time', $worktime);
        $res = $this->ci->db->get()->row();
        if ($res){return $res->amount;}else {return 0;}
    }    
    
}


/* End of file Property.php */