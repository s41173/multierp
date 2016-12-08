<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
	private $table = 'vendor';

    function valid_vendor($name=null)
    {
        $this->ci->db->select('name');
        $this->ci->db->where('name', $name);
        $val = $this->ci->db->get($this->table)->num_rows();
        if ($val > 0){return TRUE;} else{ return FALSE; }
    }

    function get_vendor_id($name=null)
    {
        if ($name != null)
        {
            $this->ci->db->select('id,name');
            $this->ci->db->where('name', $name);
            $val = $this->ci->db->get($this->table)->row();
            return $val->id;
        }
        else { return null; }
    }

    function get_vendor_shortname($id=null)
    {
        if ($id)
        {
             $this->ci->db->select('id,name,prefix');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get($this->table)->row();
            return $val->name;
        }
        else { return null; }
    }

    function get_vendor_name($id=null)
    {
        if ($id)
        {
             $this->ci->db->select('id,name,prefix');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get($this->table)->row();
            return $val->prefix.' '.$val->name;
        }
        else { return null; }
    }

    function combo()
    {
        $this->ci->db->select('id, name, code');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->code] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name, code');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->code] = $row->name;}
        return $data;
    }

    function get_vendor_bank($vid)
    {
        $this->ci->db->select('acc_name, acc_no, bank');
        $this->ci->db->where('id', $vid);
        $val = $this->ci->db->get($this->table)->row();
        return $val->acc_name.' / '.$val->acc_no.' - '.$val->bank;
    }


}

/* End of file Property.php */