<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Components {

    public function __construct($params=null)
    {
        // Do something with $params
        $this->ci = & get_instance();
    }

    private $table = 'modul';
    private $ci;

//    private $id, $name, $address, $phone1, $phone2, $fax, $email, $billing_email, $technical_email, $cc_email,
//            $zip, $city, $account_name, $account_no, $bank, $site_name, $logo, $meta_description, $meta_keyword;


    public function get($name = null)
    {
        $this->ci->db->where('name', $name);
        $res = $this->ci->db->get($this->table)->row();
        $val = array('id' => $res->id, 'name' => $res->name, 'title' => $res->title, 'limit' => $res->limit, 'publish' => $res->publish,
                     'status' => $res->status,'aktif' => $res->aktif, 'role' => $res->role, 'icon' => $res->icon, 'order' => $res->order
                    );
        return $val;
    }
    
    public function get_name($id = null)
    {
        $this->ci->db->where('id', $id);
        $res = $this->ci->db->get($this->table)->row();
        if ($res){ return $res->name; }
    }

    function combo()
    {
        $this->ci->db->select('name');
        $this->ci->db->where('aktif', 'Y');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->name] = $row->name;}
        return $data;
    }
    
    function combo_id()
    {
        $this->ci->db->select('id,name');
        $this->ci->db->where('aktif', 'Y');
        $this->ci->db->order_by('name','asc');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function get_closing_aktif()
    {
       $this->ci->db->select('name,table');
       $this->ci->db->where('aktif', 'Y'); 
       $this->ci->db->where('closing', '1'); 
       return $this->ci->db->get($this->table)->result();
    }
}

/* End of file Property.php */