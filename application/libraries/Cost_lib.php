<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cost_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'costs';

    function combo()
    {
        $this->ci->db->select('id, name, account_id');
        $this->ci->db->order_by('name', 'asc');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name, account_id');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function get_name($id=null)
    {
        $this->ci->db->select('name');
        $this->ci->db->from($this->table);
        $this->ci->db->where('id', $id);
        $res = $this->ci->db->get()->row();
        return $res->name;
    }
    
    function get_acc($id=null)
    {
        $this->ci->db->select('account_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('id', $id);
        $res = $this->ci->db->get()->row();
        return $res->account_id;
    }
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }


}

/* End of file Property.php */