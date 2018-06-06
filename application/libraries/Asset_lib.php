<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'asset';
        $this->category = new Group_asset_lib();
        $this->trans = new Asset_trans_lib();
    }

    private $ci,$table,$category,$trans;
    private $field = "asset.id, asset.code, asset.name, asset.group_id, asset.description, asset.purchase_date, asset.end_date,
                      asset.amount, asset.residual, asset.monthly_cost, asset.account, asset.total_month, asset.status";
    
    function closing(){
        
        try {     
            
            $p = new Period();
            $p->get();
        
            $date=date_create($p->year."-".$p->month."-".get_total_days($p->month));
            $end_date = date_format($date,"Y-m-d");
            
            //  create journal gl
            $journal = new Journalgl_lib();

            $no = $journal->counter('FA');
             // create journal- GL
            if ($this->get_total_based_category() > 0){
                
                $journal->cek_journal_fa($end_date);
                $journal->new_journal('0'.$no, $end_date, 'FA', 'IDR', 'Depreciation : '.tglin($end_date), $this->get_total_based_category(), $this->ci->session->userdata('log'));
                $dpid = $journal->get_journal_id('FA','0'.$no);

                $category = $this->category->get();
                foreach ($category as $res) {

                    $amt = $this->get_total_based_category($res->id);
                    $akumulasi  = $res->acc_accumulation; 
                    $depresiasi = $res->acc_depreciation;

                    $journal->add_trans($dpid,$depresiasi,$amt,0); // tambah depresiasi
                    $journal->add_trans($dpid,$akumulasi,0,$amt); // kurang akumulasi

                    // add transaksi
                    $this->get_asset_based_category($res->id);
                }
            }
            return TRUE;
            
        } catch (Exception $e) {
            //alert the user.
            var_dump($e->getMessage());
            return FALSE;
         }   
    }
    
    private function get_asset_based_category($group=null){
        
        try{
            $p = new Period();
            $p->get();
            $month = $p->month;  $year  = $p->year;
            $days  = get_total_days($p->month);
            $now = date_create($year.'-'.$month.'-'.$days); $now = (date_format($now,"Y-m-d"));

            $this->ci->db->select($this->field);
            $this->ci->db->where('status', 1);
            $this->cek_null_value($group, 'group_id');
            $this->ci->db->where('MONTH(purchase_date) <=', $month);
            $this->ci->db->where('YEAR(purchase_date) <=', $year);

            $this->ci->db->where('end_date >=', $now);
            $result = $this->ci->db->get($this->table)->result(); 
            
            foreach ($result as $res) {
                $this->trans->create($res->id, $now, $res->monthly_cost);
            }
        } catch (Exception $e){
            var_dump($e->getMessage());
            return false;
        }
    }
    
    private function get_total_based_category($group=null){
                
        $p = new Period();
        $p->get();
        $month = $p->month;
        $year  = $p->year;
        $days  = get_total_days($p->month);
        $now = date_create($year.'-'.$month.'-'.$days);
        $now = (date_format($now,"Y-m-d"));
        
        $this->ci->db->select_sum('monthly_cost');
        $this->ci->db->where('status', 1);
        $this->cek_null_value($group, 'group_id');
        $this->ci->db->where('MONTH(purchase_date) <=', $month);
        $this->ci->db->where('YEAR(purchase_date) <=', $year);
        
        $this->ci->db->where('end_date >=', $now);
        $res = $this->ci->db->get($this->table)->row_array();
        return intval($res['monthly_cost']);
    }

    function get_ap($no)
    {
        $this->ci->db->select('amount, notes, docno');
        $this->ci->db->where('no', $no);
        $query = $this->ci->db->get($this->table)->row();
        return $query;
    }
    
    function get_name($uid)
    {
        $this->ci->db->select($this->field);
        $this->ci->db->where('id', $uid);
        $query = $this->ci->db->get($this->table)->row();
        return $query->name;
    }


    //    ======================= relation cek  =====================================
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
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
    
    private function cek_null_value($val,$field)
    {
        if ($val == null){return null;}
        else {return $this->ci->db->where($field, $val);}
    }
    
    private function cek_cat($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->ci->db->where($field, $val);}
    }


}

/* End of file Property.php */