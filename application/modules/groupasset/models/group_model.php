<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'asset_group';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('asset_group.id, asset_group.code, asset_group.name, asset_group.description, 
                           asset_group.period, asset_group.acc_accumulation,
                           asset_group.acc_depreciation, asset_group.status');
        
        $this->db->from('asset_group');
        $this->db->order_by('asset_group.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function search($no,$vendor,$date)
    {
        $this->db->select('asset_group.id, asset_group.code, asset_group.name, asset_group.description, 
                           asset_group.period, asset_group.acc_accumulation,
                           asset_group.acc_depreciation, asset_group.status');

        $this->db->from('asset_group,vendor');
        $this->db->where('asset_group.vendor = vendor.id');
        $this->cek_null($no,"asset_group.no");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($date,"asset_group.dates");
        return $this->db->get();
    }
    
    function get_by_id($uid)
    {
       $this->db->select('asset_group.id, asset_group.code, asset_group.name, asset_group.description, 
                           asset_group.period, asset_group.acc_accumulation,
                           asset_group.acc_depreciation, asset_group.status');
       
       $this->db->where('asset_group.id', $uid);
       return $this->db->get($this->table);
    }
    
    function update_id($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function counter()
    {
        $this->db->select_max('no');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function counter_voucher($type)
    {
        $this->db->select_max('voucher_no');
        $this->db->where('type', $type);
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['voucher_no'];
	$userid = $userid+1;
	return $userid;
    }

    private function cek_null_report($val,$field)
    { if ($val != ""){ return $this->db->where($field, $val); } }

    private function cek_null($val,$field)
    { if (isset($val)){ return $this->db->where($field, $val); } }
    
    function valid($type,$param)
    {
        $this->db->where($type, $param);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function validating($type,$param,$id)
    {
        $this->db->where($type, $param);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

}

?>