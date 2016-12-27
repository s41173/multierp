<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    function __construct()
    {
       parent::__construct();
    }
    

    function add($data)
    {
       $this->db->insert('property', $data);
    }

    function add_user($data)
    {
       $this->db->insert('user', $data);
    }

    function status()
    {
        $data = array('status' => 1);
        $this->db->update('settings', $data);
    }


}

?>