<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journalgl_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->le = new Ledger_lib();
    }

    private $ci;
    private $le,$currency;

    // no, dates, code, currency, notes, balance, log
    public function new_journal($no, $dates, $code, $currency, $notes, $amount=0, $log)
    {
        $journal = array('no' => $no, 'dates' => $dates, 'code' => $code, 'currency' => $currency,
                         'notes' => $notes, 'balance' => $amount, 'log' => $log, 'approved' => 1);
        
        if ($this->cek_journal($no,$code, $dates, $currency) == TRUE)
        { $this->ci->db->insert('gls', $journal); $this->currency = $currency; }
    }
    
    private function cek_journal($no,$code,$date,$currency)
    {
        $this->ci->db->where('no', $no);
        $this->ci->db->where('code', $code);
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $num = $this->ci->db->get('gls')->num_rows();
        if ($num > 0){ return FALSE; }else { return TRUE; }
    }
    
    public function add_trans($gl,$acc,$debit=0,$credit=0)
    {
        $trans = array('gl_id' => $gl, 'account_id' => $acc, 'debit' => $debit, 
                       'credit' => $credit, 'vamount' => $this->calculate_vamount($acc, $debit, $credit));
        
        $this->ci->db->insert('transactions', $trans);
        $this->update_trans($gl);
        $this->le->set_profit_loss($this->currency); 
    }
    
    private function update_trans($gl)
    {
        $this->ci->db->select_sum('debit');
        $this->ci->db->where('gl_id', $gl);
        $res = $this->ci->db->get('transactions')->row_array();
        $res = intval($res['debit']);
        
        $trans = array('balance' => $res);
        $this->ci->db->where('id', $gl);
        $this->ci->db->update('gls', $trans);
    }
    
    public function get_journal_id($code,$no)
    {
        $this->ci->db->where('code', $code);
        $this->ci->db->where('no', $no);
        $jid = $this->ci->db->get('gls')->row();
        $jid = $jid->id;
        return $jid;
    }

//    ============================  remove transaction journal ==============================

    function remove_journal($codetrans,$no)
    {
        // ============ update transaction ===================
        $this->ci->db->where('no', $no);
        $this->ci->db->where('code', $codetrans);
        $jid = $this->ci->db->get('gls')->row();
        // ====================================================
        
        if ($jid)
        {
          $this->ci->db->where('gl_id', $jid->id);
          $this->ci->db->delete('transactions');

          $this->ci->db->where('id', $jid->id);
          $this->ci->db->delete('gls');
          $this->le->set_profit_loss($this->currency);   
        }
    }
    
    public function calculate_account_amount($acc,$debit=0,$credit=0)
    {
        $classi = $this->ci->load->library('classification_lib');
        $account = $this->ci->load->library('account_lib');
        
        $type = $classi->get_type($account->get_classi($acc));
        $res = 0;

        if ($type == 'harta'){ $res = 0 + $debit - $credit; }
        elseif ($type == 'kewajiban'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'modal'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'pendapatan'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'biaya'){ $res = 0 + $debit - $credit; }
        return $res;
    }
    
    private function calculate_vamount($acc,$debit=0,$credit=0)
    {
        $classi = $this->ci->load->library('classification_lib');
        $account = $this->ci->load->library('account_lib');
        
        $type = $classi->get_type($account->get_classi($acc));
        $res = 0;

        if ($type == 'harta'){ $res = 0 + $debit - $credit; }
        elseif ($type == 'kewajiban'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'modal'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'pendapatan'){ $res = 0 - $debit + $credit; }
        elseif ($type == 'biaya'){ $res = 0 + $debit - $credit; }
        return $res;
    }
    
}