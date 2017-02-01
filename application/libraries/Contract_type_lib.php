<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract_type_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'contract_type';
    }

    private $ci,$table;

    function combo()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function get_name($id)
    {
       $this->ci->db->select('name'); 
       $this->ci->db->where('id', $id);
       $val = $this->ci->db->get($this->table)->row();
       if ($val){ return $val->name; }
    }
    
    function get_account($id)
    {
       $this->ci->db->select('account_id'); 
       $this->ci->db->where('id', $id);
       $val = $this->ci->db->get($this->table)->row();
       if ($val){ return $val->account_id; }
    }

}

/* End of file Property.php */