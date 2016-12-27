<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Installation {

    public function __construct($params=null)
    {
        // Do something with $params
        $this->ci = & get_instance();
    }

    private $table = 'settings';
    private $ci;


    public function get()
    {
        $res = $this->ci->db->get($this->table)->row();
        if ($res->status == 0) { return FALSE;} else { return TRUE; }
    }
}

/* End of file Property.php */