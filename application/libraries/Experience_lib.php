<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Experience_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'experience_bonus';
    }

    private $ci,$table;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function get_amount($employee)
    {
        $this->ci->db->select('amount');
        $this->ci->db->from($this->table);
        $this->ci->db->where('employee_id', $employee);
        $res = $this->ci->db->get()->row();
        if ($res){return $res->amount;}else{return 0;}
    }    
    
}


/* End of file Property.php */