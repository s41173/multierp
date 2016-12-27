<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_recap_trans_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'student_recap_trans';
        $this->st = new Student_recap_lib();
    }

    private $ci,$table,$st;
    
    function add_trans($student,$dept,$grade,$date,$type,$qty,$month,$year,$transcode,$desc)
    {   
       $trans = array('student_id' => $student, 'dept_id' => $dept, 'grade_id' => $grade, 'dates' => $date, 'type' => $type, 'qty' => $qty, 
                      'month' => $month, 'year' => $year, 'transcode' => $transcode, 'description' => $desc);
       $this->ci->db->insert($this->table, $trans); 
       
       // add qty to student recap
//       $this->st->add_qty($dept, $grade, $qty, $month, $year);
       
    }
    
    function min_trans($student,$dept,$grade,$date,$type,$qty,$month,$year,$transcode,$desc)
    {
       $trans = array('student_id' => $student, 'dept_id' => $dept, 'grade_id' => $grade, 'dates' => $date, 'type' => $type, 'qty' => $qty, 
                      'month' => $month, 'year' => $year, 'transcode' => $transcode, 'description' => $desc);
       $this->ci->db->insert($this->table, $trans); 
       
       // min qty to student recap
//       $this->st->min_qty($dept, $grade, $qty, $month, $year);
    }
    
    function remove($sid,$dept,$grade,$dates,$type,$month,$year)
    {
        if ($type == 'out'){ $this->st->add_qty($dept, $grade, 1, $month, $year); }
        elseif ($type == 'in') { $this->st->min_qty($dept, $grade, 1, $month, $year); }
        
        $this->ci->db->where('student_id', $sid);
        $this->ci->db->where('type', $type);
        $this->ci->db->where('dates', $dates);
        $this->ci->db->delete($this->table); // perintah untuk delete data dari db
    }
    
   
    // stock

   

    function valid_qty($pid,$qty)
    {
       $this->ci->db->select('id, name, qty');
       $this->ci->db->where('id', $pid);
       $res = $this->ci->db->get('product')->row();
       if ($res->qty - $qty < 0){ return FALSE; } else { return TRUE; }
    }

    function get_id($name=null)
    {
        if ($name)
        {
           $this->ci->db->select('id, name, qty');
           $this->ci->db->where('name', $name);
           $res = $this->ci->db->get('product')->row();
           return $res->id;
        }
    } 

    function get_previous_trans($type,$dept,$grade,$month,$year)
    {
      $this->ci->db->select_sum('qty');
      $this->ci->db->where('dept_id', $dept);
      $this->ci->db->where('grade_id', $grade);
      $this->ci->db->where('type', $type);
      $this->ci->db->where('month', $month);
      $this->ci->db->where('year', $year);
      $val = $this->ci->db->from($this->table)->get()->row_array();
      return intval($val['qty']); 
    }
    
    private function previous($month)
    {
       $res = 0;
       switch ($month) 
       {
        case 1: $res=12; break;
        case 2: $res=1;  break;
        case 3: $res=2;  break;
        case 4: $res=3;  break;
        case 5: $res=4;  break;
        case 6: $res=5;  break;
        case 7: $res=6;  break;
        case 8: $res=7;  break;
        case 9: $res=8;  break;
        case 10: $res=9;  break;
        case 11: $res=10;  break;
        case 12: $res=11;  break;
       } 
       return $res;
    }

}

/* End of file Property.php */