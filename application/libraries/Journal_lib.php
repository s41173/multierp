<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journal_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;

//  $date = tanggal, $currency = matauang, $code = PJ001 - Pembelian Notes, $codetrans = PJ/ SJ, $no = no, $type = AP / AR, $amount = nominal;

    function create_journal($date,$currency,$code,$codetrans,$no,$type,$amount)
    {
        if ( $this->cek_journal($date,$currency) == FALSE )
        {
            $this->edit_journal($date,$currency,$code,$codetrans,$no,$type,$amount);
        }
        else { $this->new_journal($date,$currency,$code,$codetrans,$no,$type,$amount); }
    }

    private function cek_journal($date,$currency)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $query = $this->ci->db->get('journal')->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    private function edit_journal($date,$currency,$code,$codetrans,$no,$type,$amount)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $jid = $this->ci->db->get('journal')->row();

        $jid = $this->get_journal_id($date,$currency);

        $trans = array('name' => $code, 'journal' => $jid, 'code' => $codetrans, 'no' => $no, 'type' => $type, 'amount' => $amount);
        $this->ci->db->insert('transaction', $trans);

        $this->update_trans($jid,$codetrans);

    }


    private function new_journal($date,$currency,$code,$codetrans,$no,$type,$amount)
    {
        $journal = array('dates' => $date, 'currency' => $currency);
        $this->ci->db->insert('journal', $journal);

        $jid = $this->get_journal_id($date,$currency);
        
        $trans = array('name' => $code, 'journal' => $jid, 'code' => $codetrans, 'no' => $no, 'type' => $type, 'amount' => $amount);
        $this->ci->db->insert('transaction', $trans);

        $this->update_trans($jid,$codetrans);

    }

    private function get_journal_id($date,$currency)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $jid = $this->ci->db->get('journal')->row();
        $jid = $jid->id;
        return $jid;
    }

    private function update_trans($jid,$codetrans=null)
    {
        $total = $this->total($jid,$codetrans);

        $journal = array($codetrans => $total['amount']);
        $this->ci->db->where('id', $jid);
        $this->ci->db->update('journal', $journal);
    }

    private function total($po,$codetrans)
    {
        $this->ci->db->select_sum('amount');
        $this->ci->db->where('journal', $po);
        $this->ci->db->where('code', $codetrans);
        return $this->ci->db->get('transaction')->row_array();
    }


//    ============================  remove transaction journal ==============================

    function remove_journal($codetrans,$no)
    {
        // ============ update transaction ===================
        $this->ci->db->where('no', $no);
        $this->ci->db->where('code', $codetrans);
        $jid = $this->ci->db->get('transaction')->row();
        // ====================================================

        $this->ci->db->where('code', $codetrans);
        $this->ci->db->where('no', $no);
        $this->ci->db->delete('transaction');

        $this->update_trans($jid->journal,$codetrans);
    }

//  =======================  cek approval  =======================================

    function cek_approval($codetrans,$no)
    {
        $this->ci->db->where('no', $no);
        $this->ci->db->where('code', $codetrans);
        $jid = $this->ci->db->get('transaction');

        if ($jid->num_rows() > 0)
        {
            $jid = $jid->row();
            $this->ci->db->where('id', $jid->journal);
            $val = $this->ci->db->get('journal')->row();
            $val = $val->approved;

            if ($val == 1) { return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

    function valid_journal($date,$currency)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $val = $this->ci->db->get('journal');

        $num = $val->num_rows();
        if ($num == 0){ return TRUE; }
        else
        {
          $res = $val->row();
          if ($res->approved == 1) { return FALSE; } else { return TRUE; }
        }
    }

//  =======================  cek approval  =======================================

}

/* End of file Property.php */