<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ledger_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    
    public function calculate_profit_loss($cur='IDR',$month=0, $year=0, $emonth=0, $eyear=0)
    {
        $ac = $this->ci->load->model('Account_model','', TRUE);
        
        $incometot = intval($ac->get_balance_by_classification($cur,16,$month,$year,$emonth,$eyear));
        $hpptot = intval($ac->get_balance_by_classification($cur,15,$month,$year,$emonth,$eyear));
        $operationalcosttot = intval($ac->get_balance_by_classification($cur,19,$month,$year,$emonth,$eyear));
        $nonoperationalcosttot = intval($ac->get_balance_by_classification($cur,24,$month,$year,$emonth,$eyear));
        $othercosttot = intval($ac->get_balance_by_classification($cur,17,$month,$year,$emonth,$eyear));
        
        $outincometot = intval($ac->get_balance_by_classification($cur,21,$month,$year,$emonth,$eyear));
        $outcosttot = intval($ac->get_balance_by_classification($cur,25,$month,$year,$emonth,$eyear));
        
        $result = $incometot-$hpptot+$othercosttot-$operationalcosttot+$nonoperationalcosttot+$outincometot-$outcosttot;
        return $result;
    }
    
    public function set_profit_loss($cur='IDR')
    {
        $ac = $this->ci->load->model('Account_model','', TRUE);
        $ps = new Period();
        $bl = new Balance();
        $ps->get();
        
        // pendapatan
        $incometot = intval($ac->get_balance_by_classification($cur,16,$ps->month,$ps->year,$ps->month,$ps->year));
        $outincometot = intval($ac->get_balance_by_classification($cur,21,$ps->month,$ps->year,$ps->month,$ps->year));
        $otherincometot = intval($ac->get_balance_by_classification($cur,37,$ps->month,$ps->year,$ps->month,$ps->year));
        
        //biaya
        $hpptot = intval($ac->get_balance_by_classification($cur,15,$ps->month,$ps->year,$ps->month,$ps->year));
        $operationalcosttot = intval($ac->get_balance_by_classification($cur,19,$ps->month,$ps->year,$ps->month,$ps->year));
        $nonoperationalcosttot = intval($ac->get_balance_by_classification($cur,24,$ps->month,$ps->year,$ps->month,$ps->year));
        $othercosttot = intval($ac->get_balance_by_classification($cur,17,$ps->month,$ps->year,$ps->month,$ps->year));
        $outcosttot = intval($ac->get_balance_by_classification($cur,25,$ps->month,$ps->year,$ps->month,$ps->year));
        
        // laba tahun berjalan
        $laba = intval($ac->get_balance_by_classification($cur,18,$ps->month,$ps->year,$ps->month,$ps->year));
        
        $pendapatan = intval($incometot + $outincometot + $otherincometot);
        $biaya = intval($hpptot+$operationalcosttot+$nonoperationalcosttot+$othercosttot+$outcosttot);
        $result = $pendapatan-$biaya;
        
        $bl->where('account_id', 21);
        $bl->where('month', $ps->month);
        $bl->where('year', $ps->year)->get();
        
        $bl->vamount = intval($bl->beginning + $result);
//        $bl->vamount = intval($result);
        $bl->save();
        
        
    }

}