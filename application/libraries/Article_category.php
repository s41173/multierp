<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_category {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get('news_category')->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get('news_category')->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row)
        {  $data['options'][$row->id] = $row->name;}
        return $data;
    }


}

/* End of file Property.php */