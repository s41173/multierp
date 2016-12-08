<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller
{
    function Ajax()
    {
        parent::__construct();
        $this->load->model('Ajax_model', '', TRUE);
//        $this->load->model('Product_model', '', TRUE);
//        $this->load->model('City_model', '', TRUE);
    }

     var $title = 'ajax';
     var $limit = null;
    
    function index()
    {       
        redirect('login');
    }
    
    function cek_session()
    { 
        if ($this->session->userdata('login') == TRUE){ echo 'TRUE'; }else { echo 'FALSE'; } 
    }
    
    
    function get_loan()
    {
        $employee = new Employee_lib;
        $loan = new Loan_lib();
        $employee = $employee->get_id_by_nip($this->input->post('nip'));
        $loan = $loan->get($employee);
        if ($loan){ echo $loan; }else{ echo 0; }
    }
    
    // registration
    
    function get_regcost()
    {   
      $val = $this->db->from('registration_cost')->where('dept_id', $this->input->post("dept"))->where('level', $this->input->post('level'))->get()->row();
      if ($val){ echo $val->registration.'|'.$val->development.'|'.$val->school.'|'.$val->osis.'|'.$val->practice.'|'.$val->others; }
      else { echo "0|0|0|0|0|0"; }
      
    }
    
    function get_unicost()
    { 
      $val = $this->db->from('unicost')->where('dept_id', $this->input->post("dept"))->get()->row();
      
      if ($val)
      {
         if ($this->input->post('gender') == 'm'){ $gender = $val->m; }else{ $gender = $val->f; } 
         echo $gender.'|'.$val->practice.'|'.$val->scout.'|'.$val->additional.'|'.$val->pair;
      }
      else { echo "0|0|0|0|0"; }
      
    }
    
    function get_reg_id()
    {   
      $this->db->select_max('id');  
      $val = $this->db->from('registration')->get()->row_array();
      echo intval($val['id'] + 1);
    }
    
    // registration
    
    // payroll
    function get_salary()
    {
        $division = new Division_lib();
        $employee = new Employee_lib;
        $experience = new Experience_lib();
        $attendance = new Attendance_lib();
        
        $month = $this->input->post('month');
        $year  = $this->input->post('year');
        
        // attendance
        $att = $attendance->get($employee->get_id_by_nip($this->input->post('nip')), $month, $year);
        
        if ($att){ $res[0] = $att->presence; $res[1] = $att->overtime; }
        else{ $res[0] = 0; $res[1] = 0; }
//        // attendance
//        
        $salary = $division->get_salary_details($employee->get_division_by_nip($this->input->post('nip')));
        $exbonus = $experience->get_amount($employee->get_id_by_nip($this->input->post('nip')));
//        
        if($salary){ echo $salary->basic_salary.'|'.intval($salary->consumption*$res[0]).'|'.intval($salary->transportation*$res[0]).'|'.intval($salary->overtime*$res[1]).'|'.$exbonus; }
        else { echo "0|0|0|0|0"; }
    }
    
    function get_honor()
    {
        $attendance = new Honor_attendance_lib();
        $honor = new Honor_fee_lib(); 
        $employee = new Employee_lib;
        
        $dept  = $this->input->post('dept');
        
        $attendance = $attendance->get($employee->get_id_by_nip($this->input->post('nip')), $dept);
        if ($attendance)
        {
            $honor = $honor->get($dept,$attendance->work_time);
            echo intval($attendance->hours*$honor).'|0|0|0|0|0';
        }
        else { echo '0|0|0|0|0|0'; }
    }
    // payroll

    function getcity()
    { 
      $values = $this->Ajax_model->getcity($this->input->post('ccountry'));
      echo "<select name=\"ccity\" id=\"ccity\">";
      if ($values)
      {
          foreach ($values as $val)
          {
             echo "<option value=\"$val->name\"> $val->name </option>";
          }
      }
      else{ echo "<option value=\"\"> -- No List -- </option>"; }
      echo "</select>";
    }

    function get_stock_out_qty()
    {
        $stockout = $this->input->post("stockout");
        $product = $this->input->post("product");

        echo $this->Ajax_model->stockout_item_qty($stockout,$product);
    }
    
    // fungsi tidak digunakan
    function get_fee()
    {
        
        $data = $this->db->from('reg_cost')->where('id', $this->input->post("cfee"))->get()->row();
        if (!$data){ echo "0|0|0|0|0"; }
        else { echo $data->school.'|'.$data->osis.'|'.$data->computer.'|'.$data->practice.'|'.$data->aid; }
    }
    
    function get_fee_type($val=null)
    { 
      if ($val)  
      {
          $grade_lib = new Grade_lib();
          $level = $grade_lib->get_level($this->input->post("cgrade"));
          $values = $this->db->from('reg_cost')->where('dept_id', $this->input->post("cdept"))->where('grade', $level)->get()->result();
      }
      else{ $values = $this->db->from('reg_cost')->where('dept_id', $this->input->post("cdept"))->where('grade', $this->input->post("cgrade"))->get()->result(); }
      
      echo "<select name=\"cfee\" id=\"cfee\">";
      if ($values)
      {
          echo "<option value=\"\"> -- Select -- </option>";
          foreach ($values as $val)
          {
             echo "<option value=\"$val->id\"> $val->name </option>";
          }
      }
      else{ echo "<option value=\"\"> -- No List -- </option>"; }
      echo "</select>";
    }
    
    // fungsi tidak digunakan
    
    function get_payment_status()
    {
       $sid = $this->input->post('sid');
       $year = $this->input->post('year');
       
       $ps = new Payment_status_lib();
//       $year = new Financial_lib();
       
       echo $ps->get_month_status($sid,$year).'|'.$ps->months_name($ps->get_month_status($sid,$year));
       
    }
    
    function get_payment_front()
    {
       $sid = $this->input->post('sid');
      
       $ps = new Payment_status_lib();
       $year = new Financial_lib();
//       
       $m = $ps->get_front_status($sid,$year->get());
       $name = $ps->months_name($m); 
       echo $m.'|'.$name;
    }
    
    // get faculty
    function get_faculty()
    { 
//      $values = $this->Ajax_model->getcity($this->input->post('ccountry'));
      $values = $this->db->from('faculty')->where('dept_id', $this->input->post("cdept"))->get()->result();
      echo "<select name=\"cfaculty\" id=\"cfaculty\">";
      if ($values)
      {
          foreach ($values as $val)
          {
             echo "<option value=\"$val->code\"> $val->code </option>";
          }
      }
      else{ echo "<option value=\"\"> -- No List -- </option>"; }
      echo "</select>";
    }
    
    function get_faculty_id($val=null,$comboid='cfacultys')
    {   
      $values = $this->db->from('faculty')->where('dept_id', $this->input->post("cdept"))->get()->result();
      echo "<select name=\"cfaculty\" id=\"".$comboid."\">";
      if ($val){ echo "<option value=\"\"> -- Select -- </option>";  }
      if ($values)
      {
          foreach ($values as $val)
          {
             echo "<option value=\"$val->faculty_id\"> $val->code </option>";
          }
      }
      else{ echo "<option value=\"\"> -- No List -- </option>"; }
      echo "</select>";
    }
    // get faculty
    
    
    // get grade
    function get_grade($fac=null)
    { 
      if ($fac == null) 
      {
         $dept = $this->input->post('dept');   
         $values = $this->db->from('grade')->where('dept_id', $dept)->get()->result();  
      }
      else
      {
         $dept = $this->input->post('dept');  
         $faculty = $this->input->post('faculty');  
         $values = $this->db->from('grade')->where('dept_id', $dept)->where('faculty_id', $faculty)->get()->result();
      }
        
     
      echo "<select name=\"cgrade\" id=\"cgrade\">";
      if ($values)
      {
          foreach ($values as $val)
          {
             echo "<option value=\"$val->grade_id\"> $val->name </option>";
          }
      }
      else{ echo "<option value=\"\"> -- No List -- </option>"; }
      echo "</select>";
    }
    // get grade
    
    function getreceipt()
    { 
//      $values = $this->Ajax_model->getcity($this->input->post('ccountry'));
      $values = $this->db->from('tuition_receipt_type')->where('dept_id', $this->input->post("cdept"))->get()->result();
      echo "<select name=\"creceipt\" id=\"creceipt\">";
      if ($values)
      {
          foreach ($values as $val)
          {
             echo "<option value=\"$val->id\"> $val->name </option>";
          }
      }
      else{ echo "<option value=\"\"> -- No List -- </option>"; }
      echo "</select>";
    }
    

    function get_product_qty()
    {
        $product = $this->input->post("product");
        echo $this->Ajax_model->get_product_qty($product);
    }

    function get_salesno()
    {
        $lib = $this->load->library('sales');

        if ($this->input->post('salesno') > 0)
        {
          $sales = $lib->get_so($this->input->post('salesno'));
          echo $sales->total+$sales->costs.'|'.$sales->p1;
        }
        else { echo '0|0'; }
    }

    function get_nsalesno()
    {
        $lib = $this->load->library('nsales');

        if ($this->input->post('salesno') > 0)
        {
          $sales = $lib->get_so($this->input->post('salesno'));
          echo $sales->total+$sales->costs.'|'.$sales->p1;
        }
        else { echo '0|0'; }
    }


    function get_classification_no()
    {
        $cl = new Classification();
        $cl->get_by_id($this->input->post('cclassification'));
        echo $cl->no;
    }
    
    function get_counter_journal()
    {
        $gl = new Gl();
        $gl->where('code', $this->input->post('ctype'));
        
        if ( $gl->count() > 0 )
        {
           $gl->select_max('no');
           $gl->where('code', $this->input->post('ctype'))->get();
           $res = $gl->no + 1;
        }
        else{ $res = 1; }
        echo $res;
    }

//    -------------          batas ajax untuk menu -------------------------------------------

    function modultypefront()
    {
        $type = $this->input->post('ctype');

        if ($type == 'modul')
        {
           $values = $this->Ajax_model->getmodul()->result();
           echo "<select name=\"cmodul\" id=\"cmodul\" size=\"10\" onchange=\"geturl(this.value)\">";
           foreach ($values as $val)
           {
             echo "<option value=\"$val->name\"> $val->name </option>";
           }
           echo "</select>";
        }
        elseif ($type == "articlelist")
        {
           $values = $this->Ajax_model->getarticle()->result();
           echo "<select name=\"ccat\" id=\"ccat\" size=\"10\" onchange=\"setnilai(this.value)\">";
           foreach ($values as $val)
           {
             echo "<option value=\"$val->nama_kategori\"> $val->nama_kategori </option>";
           }
           echo "</select>";
        }
    }

}

?>