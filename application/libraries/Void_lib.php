<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Void_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'void';
    }

    private $ci,$table;
    
    function get($start=null, $end=null, $modul=null, $user=null)
    { 
      $this->cek_null($modul, 'modul');  
      $this->cek_between($start, $end);
      $this->cek_null($user, 'user');
      return $this->ci->db->get($this->table)->result();
    }
    
    function save($modul,$transcode,$date,$reason,$user,$log)
    {
        $value = array('modul' => $modul, 'transcode' => $transcode, 'dates' => $date, 'reason' => $reason, 'user' => $user, 'log' => $log);
        $this->ci->db->insert($this->table, $value); 
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->ci->db->where("dates BETWEEN '".$start."' AND '".$end."'"); }
    }
    
}


/* End of file Property.php */