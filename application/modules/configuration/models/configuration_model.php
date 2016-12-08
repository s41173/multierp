<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    var $table = 'property';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_propery()
    {
        $this->db->select('id,name,npwp,cp,address,phone1,phone2,email,billing_email,technical_email, cc_email, zip,account_name,account_no,bank,city,site_name,meta_description,meta_keyword,logo,manager,accounting');
        return $this->db->get($this->table);
    }

    function update($uid, $users)
    {
      $this->db->where('id', $uid);
      $this->db->update($this->table, $users);
    }
}

?>