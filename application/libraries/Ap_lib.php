<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'ap';
        $this->table1 = 'ap_trans';
    }

    private $ci,$table;


//    fungsi di panggil ketika ada po yg masih blm approved ketika hendak closing harian
    function cek_approval_po($date,$currency)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $this->ci->db->where('approved', 0);

        $query = $this->ci->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function get_ap($no)
    {
        $this->ci->db->select('amount, notes, docno');
        $this->ci->db->where('no', $no);
        $query = $this->ci->db->get($this->table)->row();
        return $query;
    }

    function cek_settled($no=null)
    {
        $this->ci->db->select('status');
        $this->ci->db->where('no', $no);
        $query = $this->ci->db->get($this->table)->row();
        if($query->status != 0) { return FALSE; } else { return TRUE; }
    }

    function settled_ap($uid, $users)
    {
        $this->ci->db->where('no', $uid);
        $this->ci->db->update($this->table, $users);
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