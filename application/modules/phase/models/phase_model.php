<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Phase_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'phase';
    
    function get_last_phase()
    {
        $this->db->select('phase.id, phase.contract, phase.no, phase.dates, phase.amount, phase.status, contract.customer');
        $this->db->from('contract, phase');
        $this->db->where('contract.no = phase.contract');
        $this->db->where('contract.approved', 1);
        $this->db->where('phase.status', 0);
        $this->db->where('MONTH(phase.dates) <=', date('m'));
        $this->db->where('YEAR(phase.dates) <=', date('Y'));
        $this->db->order_by('phase.id', 'desc');
        return $this->db->get(); 
    }

    function search($month=null,$year=null)
    {
        $this->db->select('phase.id, phase.contract, phase.no, phase.dates, phase.amount, phase.status, contract.customer');
        $this->db->from('contract, phase');
        $this->db->where('contract.no = phase.contract');
        $this->cek_null($month,"MONTH(phase.dates)");
        $this->db->where('contract.approved', 1);
        $this->db->where('phase.status', 0);
        $this->cek_null($year,"YEAR(phase.dates)");
        $this->db->order_by('phase.id', 'asc');
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function get_free_phase($po)
    {
        $this->db->from($this->table);
        $this->db->where('contract', $po);
        $this->db->where('status', 0);
        return $this->db->get()->num_rows();
    }


    function get_phase_by_id($uid)
    {
        $this->db->select('id, contract, no, dates, amount, status');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('contract', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    

}

?>