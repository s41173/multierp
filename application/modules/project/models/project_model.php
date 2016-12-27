<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'project';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_project($limit, $offset)
    {
        $this->db->select('project.id, project.name, customer.prefix, customer.name, project.dates, project.location,
                           project.desc, project.status, project.staff, project.log');
        
        $this->db->from('project, customer');
        $this->db->where('project.customer = customer.id');
        $this->db->order_by('project.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($customer=null,$date=null)
    {
        $this->db->select('project.id, project.name, customer.prefix, customer.name, project.dates, project.location,
                           project.desc, project.status, project.staff, project.log');

        $this->db->from('project, customer');
        $this->db->where('project.customer = customer.id');
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"project.dates");
        return $this->db->get();
    }

    function get_project_list()
    {
        $this->db->select('project.id, project.name, customer.prefix, customer.name, project.dates, project.location,
                           project.desc, project.status, project.staff, project.log');

        $this->db->from('project, customer');
        $this->db->where('project.customer = customer.id');
        $this->db->where('project.status', 0);
        $this->db->order_by('project.dates', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter()
    {
        $this->db->select_max('id');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['id'];
	$userid = $userid+1;
	return $userid;
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_project_by_id($uid)
    {
       $this->db->select('id, name, customer, dates, location, desc, status, staff, log');
       $this->db->where('id', $uid);
       return $this->db->get($this->table);
    }

    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }


//    =========================================  REPORT  =================================================================

    function report($customer,$start,$end,$status)
    {
        $this->db->select('project.id, project.name, customer.prefix, customer.name, project.dates, project.location,
                           project.desc, project.status, project.staff, project.log');

        $this->db->from('project, customer');
        $this->db->where('project.customer = customer.id');
        $this->db->where("project.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($status,"project.status");

        $this->db->order_by('project.id', 'asc');
        return $this->db->get();
    }

//    =========================================  REPORT  =================================================================

}

?>