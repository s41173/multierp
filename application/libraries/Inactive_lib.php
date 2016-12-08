<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inactive_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'inactive_student';
    }

    private $ci,$table,$st;
    
    function add($student,$no,$transcode,$log)
    {   
       $trans = array('student_id' => $student, 'no' => $no, 'transcode' => $transcode, 'log' => log);
       $this->ci->db->insert($this->table, $trans); 
    }

}

/* End of file Property.php */