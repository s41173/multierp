<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_asset_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'asset_group';
    }

    private $ci,$table;
    
    private $field = "id, code, name, description, period, acc_accumulation, acc_depreciation, status";
    
    function get(){
        $this->ci->db->select($this->field);
        $this->ci->db->where('status', 1);
        return $this->ci->db->get($this->table)->result();        
    }
    
    function combo_all()
    {
        $this->ci->db->select($this->field);
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = strtoupper($row->name);}
        return $data;
    }
    
    function get_name($id)
    {
        $this->ci->db->select($this->field);
        $this->ci->db->where('id', $id);
        $query = $this->ci->db->get($this->table)->row();
        return $query->name;
    }
    
    function get_details($id)
    {
        $this->ci->db->select($this->field);
        $this->ci->db->where('id', $id);
        $query = $this->ci->db->get($this->table)->row();
        return $query;
    }

    //    ======================= relation cek  =====================================
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======
    private function delete($po)
    {
       $this->ci->db->where('no', $po);
       $this->ci->db->delete('ap');
    }
    
    //======================================  REPORT =========================
    
    function report_trans($ap=0,$cat=null,$start,$end,$acc=null)
    {
        $this->ci->db->select('ap_trans.ap_id, ap_trans.cost, ap_trans.notes, ap_trans.staff, ap_trans.amount, ap.dates, ap.no');
        
        $this->ci->db->from('ap, ap_trans, costs, categories');
        $this->ci->db->where('ap.id = ap_trans.ap_id');
        $this->ci->db->where('ap_trans.cost = costs.id');
        $this->ci->db->where('costs.category = categories.id');
        $this->cek_cat($cat, 'costs.category');
        $this->cek_null($acc, 'ap.acc');
        $this->cek_between($start, $end);
        return $this->ci->db->get(); 
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->ci->db->where("ap.dates BETWEEN '".$start."' AND '".$end."'"); }
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }
    
    private function cek_cat($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }


}

/* End of file Property.php */