<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'user';
    }

    private $ci,$table;

    function combo()
    {
        $this->ci->db->select('userid, name, username');
        $val = $this->ci->db->get('user')->result();
        foreach($val as $row){$data['options'][$row->userid] = $row->name;}
        return $data;
    }
    
    function combo_criteria($role)
    {
        $data = null;
        $this->ci->db->select('userid, name, username');
        $this->ci->db->where('role', $role);
        $val = $this->ci->db->get($this->table)->result();
        if ($val){ $data['options'][''] = '-- All --'; foreach($val as $row){$data['options'][$row->userid] = $row->username;} }
        else { $data['options'][''] = '-- All --';  }
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('userid, name, username');
        $val = $this->ci->db->get('user')->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->userid] = $row->name;}
        return $data;
    }

    function get_userid($username)
    {
        $this->ci->db->select('userid, username');
        $this->ci->db->where('username', $username);
        $val = $this->ci->db->get('user')->row();
        return $val->userid;
    }

    function get_username($userid)
    {
        if ($userid)
        {
            $this->ci->db->select('userid, username');
            $this->ci->db->where('userid', $userid);
            $val = $this->ci->db->get('user')->row();
            if ($val){ return $val->username; }
            else { return null; }
            
        }
    }


}

/* End of file Property.php */