<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_trans_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'asset_trans';
    }

    private $ci,$table;
    private $field = "id,asset_id,closing_dates,amount,period";

    function get($no)
    {
        $this->ci->db->select($this->field);
        $this->ci->db->where('no', $no);
        $query = $this->ci->db->get($this->table)->row();
        return $query;
    }
    
    function create($asset,$dates=null,$amount){
        
        $stts = $this->cek_trans($asset, $dates);
        if ($stts == TRUE){
            $this->add($asset, $dates, $amount);  
        }else{ $this->edit($asset, $dates, $amount);  }    
    }
    
    private function cek_trans($asset,$date)
    {
        $this->ci->db->where('asset_id', $asset);
        $this->ci->db->where('closing_dates', $date);
        $num = $this->ci->db->get($this->table)->num_rows();
        if ($num > 0){ return FALSE; }else { return TRUE; }
    }
    
    private function add($asset,$dates,$amount=0)
    {
        $trans = array('asset_id' => $asset, 'closing_dates' => $dates, 'amount' => $amount, 'period' => $this->counter_period($asset));
        $this->ci->db->insert($this->table, $trans);
    }
    
    private function edit($asset,$dates,$amount=0)
    {
        $this->ci->db->where('asset_id', $asset);
        $this->ci->db->where('closing_dates', $dates);
        $res = $this->ci->db->get($this->table)->row();  
        
        $trans = array('closing_dates' => $dates, 'amount' => $amount, 'period' => $res->period);
        $this->ci->db->where('asset_id', $asset);
        $this->ci->db->where('closing_dates', $dates);
        $this->ci->db->where('period', $res->period);
        $this->ci->db->update($this->table, $trans);
    }
    
    private function counter_period($asset){
        
        $this->ci->db->select_max('period');
        $this->ci->db->where('asset_id', $asset);
        $test = $this->ci->db->get($this->table)->row_array();
        $userid=@intval($test['period']);
	$userid = $userid+1;
	return $userid; 
    }


    //    ======================= relation cek  =====================================
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('status', 1);
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get('ap')->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }

    private function delete($uid)
    {
       $this->ci->db->where('id', $uid);
       $this->ci->db->delete($this->table);
    }
    
    function delete_asset($asset)
    {
       $this->ci->db->where('asset_id', $asset);
       $this->ci->db->delete($this->table);
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