<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontmenu {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get('menu')->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    public function getmenuname($val)
    {
        if ($val)
        {
           $this->ci->db->select('id, parent_id, position, name, type, url, menu_order, limit, default, class_style, id_style, icon');
           $this->ci->db->where('id', $val);
           $res = $this->ci->db->get('menu')->row();

           if ($res) {  return $res->name; } else{ return null; }
        }
    }


}

/* End of file Property.php */