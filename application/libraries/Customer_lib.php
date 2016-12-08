<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
	private $table = 'customer';

    function valid_customer($name=null)
    {
        $this->ci->db->select('name');
        $this->ci->db->where('name', $name);
        $val = $this->ci->db->get($this->table)->num_rows();
        if ($val > 0){return TRUE;} else{ return FALSE; }
    }

    function get_customer_id($name=null)
    {
        if ($name)
        {
            $this->ci->db->select('id,name');
            $this->ci->db->where('name', $name);
            $val = $this->ci->db->get($this->table)->row();
            return $val->id;
        }
    }

    function get_customer_name($id=null)
    {
        if ($id)
        {
            $this->ci->db->select('id,name,prefix,address,zip,city,npwp');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get($this->table)->row();
            return $val->prefix.' '.$val->name;  
        } 
    }

    function get_customer_shortname($id=null)
    {
        $this->ci->db->select('id,name,prefix,address,zip,city,npwp');
        $this->ci->db->where('id', $id);
        $val = $this->ci->db->get($this->table)->row();
        return $val->name;
    }

    function get_customer_details($id=null)
    {
        $this->ci->db->select('id,name,prefix,address,zip,city,npwp,phone1,phone2');
        $this->ci->db->where('id', $id);
        $val = $this->ci->db->get($this->table)->row();
        return $val;
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

    function get_customer_bank($vid)
    {
        $this->ci->db->select('acc_name, acc_no, bank');
        $this->ci->db->where('id', $vid);
        $val = $this->ci->db->get($this->table)->row();
        return $val->acc_name.' <br/> '.$val->acc_no.' - '.$val->bank;
    }


}

/* End of file Property.php */