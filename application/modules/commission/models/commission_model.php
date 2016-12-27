<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Commission_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'commission';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_commission($limit, $offset)
    {
        $this->db->select('commission.id, commission.no, commission.dates, customer.prefix, customer.name, commission.user,
                           commission.total, commission.notes, commission.currency, commission.approved');
        
        $this->db->from('commission, customer');
        $this->db->where('commission.customer = customer.id');
        $this->db->order_by('commission.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no,$customer,$date)
    {
        $this->db->select('commission.id, commission.no, commission.dates, customer.prefix, customer.name, commission.user,
                           commission.total, commission.notes, commission.currency, commission.approved');

        $this->db->from('commission, customer');
        $this->db->where('commission.customer = customer.id');
        $this->cek_null($no,"commission.no");
        $this->cek_null($customer,"customer.name");
        $this->cek_null($date,"commission.dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter()
    {
        $this->db->select_max('no');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
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
    
    function get_commission_by_id($uid)
    {
        $this->db->select('commission.id, commission.no, commission.dates, commission.customer, customer.prefix, customer.name, commission.currency, commission.user, commission.log,
                           commission.total, commission.notes, commission.desc, commission.approved');

        $this->db->from('commission, customer');
        $this->db->where('commission.customer = customer.id');
        $this->db->where('commission.id', $uid);
        return $this->db->get();
    }

    function get_commission_by_no($uid)
    {
        $this->db->select('commission.id, commission.no, commission.dates, commission.customer, customer.prefix, customer.name, commission.currency, commission.user, commission.log,
                           commission.total, commission.notes, commission.desc, commission.approved');

        $this->db->from('commission, customer');
        $this->db->where('commission.customer = customer.id');
        $this->db->where('commission.no', $uid);
        return $this->db->get();
    }
    
    function update($uid, $users)
    {
        $this->db->where('no', $uid);
        $this->db->update($this->table, $users);
    }

    function update_id($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function valid_no($no)
    {
        $this->db->where('no', $no);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_no($no,$id)
    {
        $this->db->where('no', $no);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }

}

?>