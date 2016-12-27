<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tuition_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->journal = new Journalgl_lib();
    }

    private $ci,$journal;
    private $table = 'tuition';

    function create_journal($date,$currency,$log)
    {
        if ( $this->cek_journal($date,$currency) == TRUE )
        {
            $this->new_journal($date,$currency,$log);            
        }
        return $this->get_journal_no($date,$currency);
    }

    private function cek_journal($date,$currency)
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $query = $this->ci->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    private function new_journal($date,$currency,$log)
    {
        $journal = array('dates' => $date, 'currency' => $currency, 'no' => $this->counter(),
                         'notes' => 'TJ-00'.$this->counter().' | '.tglin($date), 'log' => $log);
        
        $this->journal->new_journal($this->counter(), $date, 'TJ', $currency, 'TJ-00'.$this->counter().' | '.tglin($date), 0, $log);
        $this->ci->db->insert($this->table, $journal);
    }
    
    private function counter()
    {
        $this->ci->db->select_max('no');
        $test = $this->ci->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }

    function get_journal_no($date=null,$currency='IDR')
    {
        $this->ci->db->where('dates', $date);
        $this->ci->db->where('currency', $currency);
        $jid = $this->ci->db->get($this->table)->row();
        if ($jid){ return $jid->no; }
    }
    
    function get_journal_dates($no)
    {
        if ($no)
        {
           $this->ci->db->where('no', $no);
           $jid = $this->ci->db->get($this->table)->row();
           return $jid->dates;
        }
    }
    
    function get_journal_currency($no)
    {
        if ($no)
        {
           $this->ci->db->where('no', $no);
           $jid = $this->ci->db->get($this->table)->row();
           return $jid->currency;
        }
    }

    public function update_balance($no,$school,$practice,$com,$osis,$cost,$found,$type=0)
    {
        $val = $this->ci->db->where('no', $no)->get($this->table)->row();
        
        $school = $school+$cost;
        $total = intval($school+$practice+$com+$osis) - intval($found);
        
        if ($type == 0){ $total = intval($val->total + $total); }
        else { $total = intval($val->total - $total); }
        
        $trans = array('total' => $total);
        $this->ci->db->where('no', $no);
        $this->ci->db->update($this->table, $trans);
    }

    private function total($po,$codetrans)
    {
        $this->ci->db->select_sum('amount');
        $this->ci->db->where('journal', $po);
        $this->ci->db->where('code', $codetrans);
        return $this->ci->db->get('transaction')->row_array();
    }


//    ============================  remove transaction journal ==============================

    function remove_journal($no)
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

    function cek_approval($no)
    {
        $this->ci->db->where('no', $no);
        $val = $this->ci->db->get($this->table)->row();
        if ($val->approved == 1) { return FALSE; } else { return TRUE; }    
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
    
    function report($no,$dept,$type)
    {
        $this->ci->db->select('tuition_trans.school_fee, tuition_trans.practical, tuition_trans.computer, tuition_trans.osis, tuition_trans.aid_foundation, tuition_trans.aid_goverment, tuition_trans.cost, tuition_trans.amount, tuition_trans.type, tuition_trans.log,
                           students.name, students.nisn, students.faculty, students.grade_id,
                           dept.name as dept');

        $this->ci->db->from('tuition_trans, students, dept');
        $this->ci->db->where('tuition_trans.student = students.students_id');
        $this->ci->db->where('students.dept_id = dept.dept_id');
        
        $this->ci->db->where('tuition_trans.tuition', $no);
        $this->ci->db->where('students.dept_id', $dept);
        $this->cek_null($type,"tuition_trans.type");
        $this->ci->db->order_by('name', 'asc');
        
        return $this->ci->db->get();
    }
    
    function get_by_student($sid,$date,$month,$financial)
    {
       $this->ci->db->select('amount,fee_type,user,log'); 
       $this->ci->db->where('student', $sid);
       $this->ci->db->where('dates', $date);
       $this->ci->db->where('month', $month);
       $this->ci->db->where('financial_year', $financial);
       return $this->ci->db->get('tuition_trans')->row();
    }
    
    function total_report($cur,$dept,$start,$end,$status)
    {
        $this->ci->db->select_sum('school_fee');
        $this->ci->db->select_sum('practical');
        $this->ci->db->select_sum('osis');
        $this->ci->db->select_sum('computer');
        $this->ci->db->select_sum('cost');
        $this->ci->db->select_sum('aid_foundation');
        $this->ci->db->select_sum('aid_goverment');
        $this->ci->db->select_sum('amount');

        $this->ci->db->from('tuition, tuition_trans, students, dept');
        $this->ci->db->where('tuition_trans.student = students.students_id');
        $this->ci->db->where('students.dept_id = dept.dept_id');
        $this->ci->db->where('tuition.no = tuition_trans.tuition');
        
        $this->ci->db->where('tuition.currency', $cur);
        $this->cek_null($dept,"students.dept_id");
        $this->ci->db->where("tuition.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->ci->db->where('tuition_trans.type', $status);
        $this->ci->db->where('tuition.approved', 1);
        
        return $this->ci->db->get()->row_array();
    }
    
    function total_amount($no,$dept,$status)
    {
        $this->ci->db->select_sum('school_fee');
        $this->ci->db->select_sum('practical');
        $this->ci->db->select_sum('osis');
        $this->ci->db->select_sum('computer');
        $this->ci->db->select_sum('cost');
        $this->ci->db->select_sum('aid_foundation');
        $this->ci->db->select_sum('aid_goverment');
        $this->ci->db->select_sum('amount');

        $this->ci->db->from('tuition_trans, students, dept');
        $this->ci->db->where('tuition_trans.student = students.students_id');
        $this->ci->db->where('students.dept_id = dept.dept_id');
        
        $this->ci->db->where('tuition_trans.tuition', $no);
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($status,"tuition_trans.type");
        
        return $this->ci->db->get()->row_array();
    }
    
    function total_student($cur,$dept,$start,$end,$status,$type=null)
    {
        $this->ci->db->from('tuition, tuition_trans, students, dept');
        $this->ci->db->where('tuition.no = tuition_trans.tuition');
        $this->ci->db->where('tuition_trans.student = students.students_id');
        $this->ci->db->where('students.dept_id = dept.dept_id');
        
        $this->ci->db->where('tuition.currency', $cur);
        $this->ci->db->where('students.dept_id', $dept);
        $this->ci->db->where('tuition_trans.type', $status);
        $this->ci->db->where("tuition.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->ci->db->where('tuition.approved', 1);
        $this->ci->db->order_by('tuition_trans.type');
        $this->ci->db->where('tuition_trans.'.$type.' >', 0); 
        
        return $this->ci->db->get()->num_rows();
    }
    
    private function cek_null($val,$field)
    { if (isset($val)){ return $this->ci->db->where($field, $val); } }
    
    
    function total_paid($dept=null, $grade=null, $monthperiod, $financialyear, $type, $scholar=null,$fee=null)
    {
       $py = new Payment_status_lib();
       $year = $py->year_name($monthperiod, $financialyear);
       $month = $py->months_from_period($monthperiod);
       
       $this->ci->db->from('tuition_trans, students'); 
       $this->ci->db->where('tuition_trans.student = students.students_id');
       $this->cek_null($grade, 'students.grade_id');
       $this->cek_null($dept, 'students.dept_id');
       $this->ci->db->where('MONTH(tuition_trans.dates)', $month);
       $this->ci->db->where('YEAR(tuition_trans.dates)', $year);
       $this->cek_null($financialyear, 'tuition_trans.financial_year');
       $this->cek_null($type, 'tuition_trans.type');
       $this->cek_null($scholar, 'tuition_trans.scholarship');
       $this->cek_null($fee, 'tuition_trans.fee_type');
       return $this->ci->db->get()->num_rows();
    }
    
    // search based fee type
    
    function total_paid_criteria($dept=null,$grade=null,$month,$year,$financialyear,$fee=0)
    {
       $this->ci->db->from('tuition_trans, students'); 
       $this->ci->db->where('tuition_trans.student = students.students_id');
       $this->cek_null($grade, 'students.grade_id');
       $this->cek_null($dept, 'students.dept_id');
       $this->ci->db->where('students.dept_id', $dept); 
       $this->ci->db->where('MONTH(tuition_trans.dates)', $month);
       $this->ci->db->where('YEAR(tuition_trans.dates)', $year);
       $this->cek_null($financialyear, 'tuition_trans.financial_year');
       $this->ci->db->where('tuition_trans.fee_type', $fee);
//       $this->ci->db->where('tuition_trans.full_aid', 0);
//       $this->ci->db->where('tuition_trans.half_aid', 0);
//       $this->ci->db->where('tuition_trans.scholarship', 0);
       return $this->ci->db->get()->num_rows();
    }

    function total_realisasi($dept,$grade,$month,$financialyear,$type,$scholar)
    {
        $py = new Payment_status_lib();
        $year = $py->year_name($py->months_periode($month), $financialyear);
        
        $this->ci->db->select_sum('school_fee');
        $this->ci->db->select_sum('practical');
        $this->ci->db->select_sum('osis');
        $this->ci->db->select_sum('computer');
        $this->ci->db->select_sum('cost');
        $this->ci->db->select_sum('aid_foundation');
        $this->ci->db->select_sum('aid_goverment');
        $this->ci->db->select_sum('amount');

        $this->ci->db->from('tuition_trans, students, dept');
        $this->ci->db->where('tuition_trans.student = students.students_id');
        $this->ci->db->where('students.dept_id = dept.dept_id');
        
        $this->ci->db->where('MONTH(tuition_trans.dates)', $month);
        $this->ci->db->where('YEAR(tuition_trans.dates)', $year);
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($grade,"students.grade_id");
        $this->cek_null($type,"tuition_trans.type");
        $this->cek_null($scholar,"tuition_trans.scholarship");
        
        return $this->ci->db->get()->row_array();
    }
    
    function total_realisasi_based_financial($dept,$grade,$month,$financialyear,$type,$scholar)
    {
        $py = new Payment_status_lib();
        $year = $py->year_name($py->months_periode($month), $financialyear);
        
        $this->ci->db->select_sum('school_fee');
        $this->ci->db->select_sum('practical');
        $this->ci->db->select_sum('osis');
        $this->ci->db->select_sum('computer');
        $this->ci->db->select_sum('cost');
        $this->ci->db->select_sum('aid_foundation');
        $this->ci->db->select_sum('aid_goverment');
        $this->ci->db->select_sum('amount');

        $this->ci->db->from('tuition_trans, students, dept');
        $this->ci->db->where('tuition_trans.student = students.students_id');
        $this->ci->db->where('students.dept_id = dept.dept_id');
        
        $this->ci->db->where('MONTH(tuition_trans.dates)', $month);
        $this->ci->db->where('YEAR(tuition_trans.dates)', $year);
        $this->ci->db->where('tuition_trans.financial_year', $financialyear);
        $this->cek_null($dept,"students.dept_id");
        $this->cek_null($grade,"students.grade_id");
        $this->cek_null($type,"tuition_trans.type");
        $this->cek_null($scholar,"tuition_trans.scholarship");
        
        return $this->ci->db->get()->row_array();
    }
    
//  =======================  cek approval  =======================================

}

/* End of file Property.php */