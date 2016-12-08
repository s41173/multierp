<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

    function combo()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get('city')->result();
        foreach($val as $row){$data['options'][$row->name] = $row->name;}
        return $data;
    }
    
    function combo_zip()
    {
        $this->ci->db->select('zip');
        $val = $this->ci->db->get('city')->result();
        foreach($val as $row){$data['options'][$row->zip] = $row->zip;}
        return $data;
    }
    
    function combo_district()
    {
        $this->ci->db->select('district');
        $val = $this->ci->db->get('city')->result();
        foreach($val as $row){$data['options'][$row->district] = $row->district;}
        return $data;
    }
    
    function combo_village()
    {
        $this->ci->db->select('village');
        $val = $this->ci->db->get('city')->result();
        foreach($val as $row){$data['options'][$row->zip] = $row->village;}
        return $data;
    }
    
    function get_from_zip($zip,$type)
    {
       $this->ci->db->select($type); 
       $this->ci->db->where('zip', $zip);
       $val = $this->ci->db->get('city')->row();
       if ($val){ return $val; }
    }


}

/* End of file Property.php */