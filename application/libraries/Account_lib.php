<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    private $table = 'accounts';


    function get_id($name=null)
    {
        if ($name)
        {
            $this->ci->db->select('id,name');
            $this->ci->db->where('name', $name);
            $val = $this->ci->db->get($this->table)->row();
            return $val->id;
        }
    }

    function get_id_code($code=null)
    {
        if ($code)
        {
            $this->ci->db->select('id,name,code');
            $this->ci->db->where('code', $code);
            $val = $this->ci->db->get($this->table)->row();
            if ($val){ return $val->id; }
        }
    }
	
    function get_code($id=null)
    {
        if ($id)
        {
            $this->ci->db->select('id,name,code');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get($this->table)->row();
            if ($val){ return $val->code; }
        }
    }

    function get_name($id=null)
    {
        $this->ci->db->select('id,name');
        $this->ci->db->where('id', $id);
        $val = $this->ci->db->get($this->table)->row();
        if ($val){ return $val->name; }
    }
	
    function get_cur($id=null)
    {
        $this->ci->db->select('id,name,currency');
        $this->ci->db->where('id', $id);
        $val = $this->ci->db->get($this->table)->row();
        return $val->currency;
    }

    function get_classi($id=null)
    {
        $this->ci->db->select('classification_id');
        $this->ci->db->where('id', $id);
        $val = $this->ci->db->get($this->table)->row();
        return intval($val->classification_id);
    }

    function combo()
    {
        $this->ci->db->select('id, name, code');
        $this->ci->db->where('status', 1);
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->code] = $row->name;}
        return $data;
    }
    
    function combo_based_classi($cla)
    {
        $this->ci->db->select('id, name, code');
        $this->ci->db->where('classification_id', $cla);
        $this->ci->db->where('status', 1);
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->code.' : '.$row->name;}
        return $data;
    }
    
    function combo_asset()
    {
        $val = array('7', '8');
        $this->ci->db->select('id, name, code');
//        $this->ci->db->where_in('classification_id', $val);
        $this->ci->db->where('status', 1);
        $this->ci->db->where('bank_stts', 1);
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->code.' : '.$row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name, code');
        $this->ci->db->where('status', 1);
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->code] = $row->name;}
        return $data;
    }


}

/* End of file Property.php */