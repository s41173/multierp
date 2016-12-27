<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uniform_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'uniform';
        $this->loan = new Loan_lib();
    }

    private $ci,$table,$loan;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
     
    function add($reg,$suni,$spractice,$sscout,$unitot,$practicetot,$scouttot,$additional,$balance,$p1)
    {
        $trans = array('reg_id' => $reg, 'stel_uniform' => $suni, 'stel_practice' => $spractice,
                       'stel_scout' => $sscout, 'total_uniform' => $unitot, 'total_practice' => $practicetot, 
                       'total_scout' => $scouttot, 'additional' => $additional, 'balance' => $balance, 'p1' => $p1);
        
        $this->ci->db->insert($this->table, $trans);
    }
    
    function total_by_regid($uid)
    {
       $this->ci->db->from($this->table);
       $this->ci->db->where('reg_id', $uid);
       $res = $this->ci->db->get()->row();
       if ($res){ return intval($res->jumlah);  }
    }
    
    function remove($reg)
    {   
        $this->ci->db->where('reg_id', $reg);
        $this->ci->db->delete($this->table);
    }
    
    function update($uid,$suni,$spractice,$sscout,$unitot,$practicetot,$scouttot,$additional,$balance,$p1)
    {
        $trans = array('stel_uniform' => $suni, 'stel_practice' => $spractice,
                       'stel_scout' => $sscout, 'total_uniform' => $unitot, 'total_practice' => $practicetot, 
                       'total_scout' => $scouttot, 'additional' => $additional, 'balance' => $balance, 'p1' => $p1);
        
        $this->ci->db->where('id', $uid);
        $this->ci->db->update($this->table, $trans);
    }
    
}


/* End of file Property.php */