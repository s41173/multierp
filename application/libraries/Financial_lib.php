<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financial_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'financial_year';
    }

    private $ci,$table;
    
    function combo()
    {
        $this->ci->db->select('id, year, begin, desc, active');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->year] = $row->year;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, year, begin, desc, active');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->year] = $row->year;}
        return $data;
    }
    
    function combo_active()
    {
        $this->ci->db->select('id, year, begin, desc, active');
        $this->ci->db->order_by('active', 'desc');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->year] = $row->year;}
        return $data;
    }
    
    function get()
    {
        $this->ci->db->select('year');
        $this->ci->db->from($this->table);
        $this->ci->db->where('active', 1);
        $res = $this->ci->db->get()->row();
        return $res->year;
    }
    
    function get_begin()
    {
        $this->ci->db->select('begin');
        $this->ci->db->from($this->table);
        $this->ci->db->where('active', 1);
        $res = $this->ci->db->get()->row();
        return $res->begin;
    }
    
}

/* End of file Property.php */