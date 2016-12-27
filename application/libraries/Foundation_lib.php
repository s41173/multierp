<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Foundation_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function get_name($id=null)
    {
        if ($id)
        {
          $this->ci->db->select('id,name,role');
          $this->ci->db->where('role', $id);
          $val = $this->ci->db->get('foundation')->row();
          return $val->name;
        }
    }
    
}

/* End of file Property.php */