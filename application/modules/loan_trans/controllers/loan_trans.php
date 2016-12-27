<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loan_trans extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Loan_trans_model', 'lm', TRUE);

        $this->properti = $this->property->get();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->dept = $this->load->library('dept_lib');
        $this->employee = $this->load->library('employee_lib');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->loan = $this->load->library('loan_lib');
        $this->model = new Loan_transs();
        
        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';
    }

    private $properti, $modul, $title,$dept,$employee;
    private $user,$currency,$model,$loan,$journalgl;

    private  $atts = array('width'=> '800','height'=> '400',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 400)/2)+\'');

    function index()
    {
       $this->blank_loan(); 
       $this->get_last();
    }
    
    function autocomplete()
    {
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('students')->like('name',$keyword,'after')->get();

      // format keluaran di dalam array
      foreach($data->result() as $row)
      {
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>$row->name,
            'data'   =>$row->students_id
         );
      }

      // minimal PHP 5.2
      echo json_encode($arr);
    }

    function get_last()
    {
        $this->blank_loan();
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'loan_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('payroll_reference/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['currency'] = $this->currency->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
	// ---------------------------------------- //
        $result = $this->model->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination
//            
//
            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Date', 'Name', 'Type', 'Amount', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, 'LT-00'.$res->id, tglin($res->date), $this->employee->get_name($res->employee_id).' - '.$this->employee->get_nip($res->employee_id), ucfirst($res->type), number_format($res->amount),
                    anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
//
            $data['table'] = $this->table->generate();
            // ===== chart  =======
            $data['graph'] = $this->chart($this->input->post('ccurrency'));
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
        
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function chart($cur='IDR')
    {
        $ps = new Period();
        $ps->get();
        $year = $ps->year;

        $arpData[0][1] = 'January';
        $arpData[0][2] = $this->lm->total_chart('01',$year,$cur);

        $arpData[1][1] = 'February';
        $arpData[1][2] = $this->lm->total_chart('02',$year,$cur);

        $arpData[2][1] = 'March';
        $arpData[2][2] = $this->lm->total_chart('03',$year,$cur);

        $arpData[3][1] = 'April';
        $arpData[3][2] = $this->lm->total_chart('04',$year,$cur);

        $arpData[4][1] = 'May';
        $arpData[4][2] = $this->lm->total_chart('05',$year,$cur);

        $arpData[5][1] = 'June';
        $arpData[5][2] = $this->lm->total_chart('06',$year,$cur);

        $arpData[6][1] = 'July';
        $arpData[6][2] = $this->lm->total_chart('07',$year,$cur);

        $arpData[7][1] = 'August';
        $arpData[7][2] = $this->lm->total_chart('08',$year,$cur);

        $arpData[8][1] = 'September';
        $arpData[8][2] = $this->lm->total_chart('09',$year,$cur);

        $arpData[9][1] = 'October';
        $arpData[9][2] = $this->lm->total_chart('10',$year,$cur);

        $arpData[10][1] = 'November';
        $arpData[10][2] = $this->lm->total_chart('11',$year,$cur);

        $arpData[11][1] = 'December';
        $arpData[11][2] = $this->lm->total_chart('12',$year,$cur);

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Payroll", "98%", 400, false, false) ;
        return $graph;
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'loan_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['currency'] = $this->currency->combo();
        
	// ---------------------------------------- //
        $result = $this->lm->search($this->employee->get_id_by_nip($this->input->post('tnip')), $this->input->post('tdate'), $this->input->post('ctype'))->result();
  
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Name', 'Type', 'Amount', '#');
//
        $i = 0;
        foreach ($result as $res)
        {
            $this->table->add_row
            (
                ++$i, 'LT-00'.$res->id, tglin($res->date), $this->employee->get_name($res->employee_id).' - '.$this->employee->get_nip($res->employee_id), ucfirst($res->type), number_format($res->amount),
                anchor_popup($this->title.'/invoice/'.$res->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
//
        $data['table'] = $this->table->generate();
	$this->load->view('template', $data);
    }
    
    function invoice($id)
    {
       $this->acl->otentikasi2($this->title);
       $this->load->library('terbilang');
       
       $res = $this->model->where('id', $id)->get();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $data['p_name'] = $this->properti['name'];
       $data['pono'] = $id;
       $data['podate'] = tglin($res->date);
       $data['employee'] = $this->employee->get_name($res->employee_id);
       $data['user'] = $this->session->userdata('username');
       $data['currency'] = $res->currency;
       $data['notes'] =  $res->notes;
       $data['acc'] = $res->acc;
       $data['log'] = $this->session->userdata('log');
       $data['amount'] = $res->amount;
       
       if ($res->currency == 'IDR'){ $data['terbilang'] = $this->terbilang->baca($res->amount).' Rupiah'; }
       else { $data['terbilang'] = $this->terbilang->baca($res->amount); }
       
       if($res->type == 'borrow'){ $this->load->view('loan_invoice', $data); }
       else { $this->load->view('loan_payment_invoice', $data); }
       
    }
    
    function add()
    {
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'loan_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();
        $this->load->view('loan_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'loan_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
         
        $data['dept'] = $this->dept->combo_all(); 
        $data['currency'] = $this->currency->combo();
        
	// Form validation
        $this->form_validation->set_rules('tdate', 'Name', 'required|callback_valid_period');
        $this->form_validation->set_rules('tnip', 'Employee Nip', 'required|numeric');
        $this->form_validation->set_rules('ctype', 'Trans Type', 'required');
        $this->form_validation->set_rules('tnotes', 'Notes', 'required');
        $this->form_validation->set_rules('tamount', 'Loans Amount', 'required|numeric|callback_valid_nol');
        
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->date         = $this->input->post('tdate');
            $this->model->employee_id  = $this->employee->get_id_by_nip($this->input->post('tnip'));
            $this->model->currency     = $this->input->post('ccur');
            $this->model->amount       = $this->input->post('tamount');
            $this->model->type         = $this->input->post('ctype');
            $this->model->acc          = $this->input->post('cacc');
            $this->model->notes        = $this->input->post('tnotes');
            $this->model->log          = $this->session->userdata('log');
            $this->model->save();
            
            $this->loan->change_loan($this->employee->get_id_by_nip($this->input->post('tnip')),$this->input->post('ccur'),$this->input->post('tamount'),$this->input->post('ctype'));
            $this->create_journal($this->input->post('tdate'),$this->input->post('ccur'),$this->input->post('cacc'),$this->input->post('tamount'),$this->input->post('ctype'));
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add');
            echo 'true';
        }
        else
        { 
//            $this->load->view('loan_form', $data); 
            echo validation_errors();
        }
    }
    
    
    private function create_journal($date,$cur='IDR',$acc,$amount,$type)
    {
        $this->model->select_max('id');
        $id = $this->model->get();
        $id = intval($id->id);
        
        if($type == 'borrow'){ $type = 'CD'; }elseif ( $type == 'paid' ){ $type = 'CR'; }
        
        $cm = new Control_model();
        
        $bank     = $cm->get_id(22);
        $kas      = $cm->get_id(13);
        $kaskecil = $cm->get_id(14);
        $loan     = $cm->get_id(38);
        $account  = 0;

        $this->journalgl->new_journal('0000'.$id, $date, $type, $cur, 'Loan : '.tglmonth($date).' - '.  ucfirst($acc), $amount, $this->session->userdata('log'));
        $dpid = $this->journalgl->get_journal_id($type,'0000'.$id);

        switch ($acc) { case 'bank': $account = $bank; break; case 'cash': $account = $kas; break; case 'pettycash': $account = $kaskecil; break; }              
        if ($type == 'CD')
        {
           $this->journalgl->add_trans($dpid,$loan,$amount,0); // loan (debit) 
           $this->journalgl->add_trans($dpid,$account,0,$amount); // kas, bank, kas kecil ( kredit )
        }
        elseif($type == 'CR')
        {
           $this->journalgl->add_trans($dpid,$account,$amount,0); // kas, bank, kas kecil ( debit ) 
           $this->journalgl->add_trans($dpid,$loan,0,$amount); // loan (kredit)
        }
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->model->where('id', $uid)->get();
        
        if ($this->valid_period($this->model->date) == TRUE)
        {
          if ($this->model->type == 'borrow')
          { 
            $this->loan->change_loan($this->model->employee_id, $this->model->currency, $this->model->amount, "paid");
            $this->journalgl->remove_journal('CD', '0000'.$uid);
          }
          else
          { $this->loan->change_loan($this->model->employee_id, $this->model->currency, $this->model->amount, "borrow");
            $this->journalgl->remove_journal('CR', '0000'.$uid);
          }

          $this->model->delete(); 
          $this->session->set_flashdata('message', "1 $this->title successfully removed..!");       
        }
        else { $this->session->set_flashdata('message', "Invalid period..!"); }
        redirect($this->title);  
    }
    
    private function blank_loan(){$this->lm->delete_amount(); }
        
    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_nol($amount)
    {
        if ($amount == 0)
        {
            $this->form_validation->set_message('valid_nol', "Amount has no value..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_loan($payment)
    {
        $type = $this->input->post('ctype');
        if ($type == 'paid')
        {
           $employee = $this->employee->get_id_by_nip($this->input->post('tnip'));
           $loan = $this->loan->get($employee);

           if ($payment > $loan)
           {
               $this->form_validation->set_message('valid_loan', "Invalid Payment..!");
               return FALSE;
           }
           else{ return TRUE; } 
        }
        elseif ($type == 'borrow') { return TRUE; }
    }
    
    public function validating_name($name,$nip)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_name', "Division [$name] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    
    public function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $this->load->view('loan_report_panel', $data);
    }

    public function report_process()
    {
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['type'] = $this->input->post('ctype');
        $data['transtype'] = $this->input->post('ctranstype');
                
        $data['results'] = $this->lm->report($this->input->post('tstart'),$this->input->post('tend'),$this->input->post('ctype'),$this->input->post('ctranstype'))->result();
        
        $this->load->view('loan_report', $data);
    }

}

?>