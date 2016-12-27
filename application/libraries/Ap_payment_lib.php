<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_payment_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'ap_payment';
        $this->table2 = 'payment_trans';
    }

    private $ci,$table;


    //    ======================= relation cek  =====================================

    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function cek_relation_trans($id,$type,$code='PO')
    {
       $this->ci->db->where($type, $id);
       $this->ci->db->where('code', $code);
       $query = $this->ci->db->get($this->table2)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function combo_over($vendor,$cur='IDR')
    {
        $data['options']['0'] = '-- Select --';
        $this->ci->db->select('id, no, dates, over');
        $this->ci->db->where('vendor', $vendor);
        $this->ci->db->where('currency', $cur);
        $this->ci->db->where('over_stts', 1);
        $this->ci->db->where('credit_over', 0);
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->no] = 'CD-0'.$row->no.' : '.tglin($row->dates).' = '.number_format($row->over);}
        return $data;
    }
    
    function get_over_payment($no)
    {
       $this->ci->db->select('id, no, dates, over');
       $this->ci->db->where('no', $no);
       $this->ci->db->where('over_stts', 1);
       $this->ci->db->where('credit_over', 0);
       $res = $this->ci->db->get($this->table)->row();
       return intval($res->over);
    }
    
    function get_dates($no)
    {
       $this->ci->db->select('id, no, dates, over');
       $this->ci->db->where('no', $no);
       $res = $this->ci->db->get($this->table)->row();
       return intval($res->dates);
    }
    
    function set_over_stts($no, $users)
    {
        $this->ci->db->where('no', $no);
        $this->ci->db->update($this->table, $users);
    }
    
    function set_post_stts($no, $users)
    {
        $this->ci->db->where('no', $no);
        $this->ci->db->update($this->table, $users);
    }

}

/* End of file Property.php */