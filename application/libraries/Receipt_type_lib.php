<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receipt_type_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->journal = new Journalgl_lib();
    }

    private $ci,$journal;
    private $table = 'tuition_receipt_type';

    function get($id)
    {
        $this->ci->db->where('id', $id);
        $val = $this->ci->db->get($this->table)->row();
        return $val;
    }
    
    function get_by_dept($id)
    {
        $this->ci->db->where('dept_id', $id);
        $val = $this->ci->db->get($this->table)->row();
        return $val->id;
    }


}

/* End of file Property.php */