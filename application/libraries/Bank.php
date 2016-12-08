<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'bank';
    }

    private $ci,$table;

    function combo()
    {
        $this->ci->db->select('id, acc_name, acc_no, acc_bank, currency');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->acc_no.' - '.$row->currency.' - '.$row->acc_bank;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, acc_name, acc_no, acc_bank, currency');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- No Selected --';
        foreach($val as $row){$data['options'][$row->id] = $row->acc_no.' - '.$row->currency.' - '.$row->acc_bank;}
        return $data;
    }

    function get_bank_name($id)
    {
        if ($id != 0)
        {
            $this->ci->db->select('id, acc_name, acc_no, acc_bank, currency');
            $this->ci->db->where('id', $id);
            $val = $this->ci->db->get($this->table)->row();
            return $val->acc_no.' - '.$val->currency.' - '.$val->acc_bank;
        }
        else { return ''; }
        
    }


}

/* End of file Property.php */