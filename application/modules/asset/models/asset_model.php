<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Asset_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'asset';
    private $field = "asset.id, asset.code, asset.name, asset.group_id, asset.description, asset.purchase_date, asset.end_date,
                      asset.amount, asset.residual, asset.monthly_cost, asset.account, asset.total_month, asset.status";
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select($this->field);
        $this->db->from('asset');
        $this->db->order_by('asset.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function search($group=null,$status=1)
    {
        $this->db->select($this->field);
        $this->db->from($this->table);
        $this->cek_null_report($group,"group_id");
        $this->cek_null_report($status,"status");
        return $this->db->get();
    }
    
    function get_by_id($uid)
    {
       $this->db->select($this->field);
       $this->db->where('asset.id', $uid);
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