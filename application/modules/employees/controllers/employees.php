<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Employee_model', 'em', TRUE);

        $this->properti = $this->property->get();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->dept = $this->load->library('dept_lib');
        $this->student = $this->load->library('student_lib');
        $this->finance = $this->load->library('financial_lib');
        $this->division = $this->load->library('division_lib');
        $this->model = new Employee();
        $this->loan = new Loan_lib(); 
        $this->payrolltrans = new Payroll_trans_lib();
    }

    private $properti, $modul, $title,$dept,$loan,$payrolltrans;
    private $user,$currency,$student,$finance,$model,$division;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
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
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'employee_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('payroll_reference/','<span>back</span>', array('class' => 'back')));
        
        $data['dept']     = $this->dept->combo_all();
        $data['division'] = $this->division->combo_all();
        
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
            $this->table->set_heading('No', 'Nip', 'Att-Code', 'Name', 'Type', 'Time Work', 'Role', 'Division', 'Dept', 'Phone', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, $res->nip, $res->attcode, $res->first_title.' '.strtoupper($res->name).'.'.$res->end_title, ucfirst($res->type), $res->work_time, ucfirst($res->role), $this->division->get_name($res->division_id), $this->dept_status($this->dept->get_name($res->dept_id)), $res->phone,
                    anchor($this->title.'/active/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->active), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/details/'.$res->id,'<span>print</span>',array('class' => 'print', 'title' => ''), $this->atts).' '.    
                    anchor($this->title.'/update/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
//
            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
//
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
     
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'employee_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        $data['division'] = $this->division->combo_all();
        
	// ---------------------------------------- //
        $result = $this->em->search($this->input->post('cdept'), $this->input->post('cdivision'), $this->input->post('crole'), $this->input->post('tvalue'))->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Nip', 'Att-Code', 'Name', 'Type', 'Time Work', 'Role', 'Division', 'Dept', 'Phone', '#');
//
        $i = 0;
        foreach ($result as $res)
        {
            $this->table->add_row
            (
                ++$i, $res->nip, $res->attcode, $res->first_title.' '.strtoupper($res->name).'.'.$res->end_title, ucfirst($res->type), $res->work_time, ucfirst($res->role), $this->division->get_name($res->division_id), $this->dept_status($this->dept->get_name($res->dept_id)), $res->phone,
                anchor($this->title.'/active/'.$res->id,'<span>update</span>',array('class' => $this->post_status($res->active), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/details/'.$res->id,'<span>print</span>',array('class' => 'print', 'title' => ''), $this->atts).' '.    
                anchor($this->title.'/update/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
//
        $data['table'] = $this->table->generate();
        
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function get_list($type=null,$field=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'employee_list';

        if ($this->input->post('tnip'))
        { $result = $this->model->where('nip', $this->input->post('tnip'))->where('active', 1)->get(); }
        else{ if($type){ $result = $this->model->where('type', $type)->where('active', 1)->get(); } 
        else{$result = $this->model->where('active', 1)->get();} }
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Nip', 'Name', 'Type', 'Division', 'Dept', '#');

        $i = 0;
        foreach ($result as $res)
        {
           if ($field){ $val = $res->name; }else { $val = $res->nip; }
           $data = array('name' => 'button', 'type' => 'button', 'content' => 'Select',
                         'onclick' => 'setvalue(\''.$val.'\',\'tsearch\')'
                         );

            $this->table->add_row
            (
                ++$i, $res->nip, $res->first_title.' '.strtoupper($res->name).'.'.$res->end_title, ucfirst($res->type), $this->division->get_name($res->division_id), $this->dept_status($this->dept->get_name($res->dept_id)),
                form_button($data)
            );
        }

            $data['table'] = $this->table->generate();
            $data['form_action'] = site_url('employees/get_list');
            $this->load->view('employee_list', $data);
    }
    
    private function post_status($val=null)
    { $class = 'notapprove'; if ($val) {$class = "approve"; }elseif ($val == 1){$class = "notapprove"; } return $class; }
    
    private function dept_status($val){ if (!$val){ return 'Non Department'; }else { return $val;} }
    
    function details($id)
    {
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['paddress'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $this->model->where('id', $id)->get();
       
        if ($this->model->type == 1){ $section = 'Academic'; }else { $section = 'Non Academic'; }
        if ($this->model->dept_id == 0){ $dept = 'General'; }else { $dept = $this->dept->get_name($this->model->dept_id); }
        if ($this->model->genre == 'm'){ $genre = 'Male'; }else { $genre = 'Female'; }
        if ($this->model->status == 'yes'){ $status = 'Married'; }elseif ($this->model->status == 'no') { $status = 'Not Married'; }else { $status = 'No Status'; }
        
        $data['section']   = $section; 
        $data['dept']      = $dept;
        $data['nip']       = $this->model->nip;
        $data['name']      = ucfirst($this->model->first_title.' '.$this->model->name.' '.$this->model->end_title);
        $data['first']     = $this->model->first_title;
        $data['end']       = $this->model->end_title;
        $data['nickname']  = $this->model->nickname;
        $data['genre']     = $genre;
        $data['bornplace'] = $this->model->born_place;
        $data['borndate']  = tglincomplete($this->model->born_date);
        $data['religion']  = $this->model->religion;
        $data['ethnic']    = $this->model->ethnic;
        $data['marital']   = $status;
        $data['idno']      = $this->model->id_no;
        $data['address']   = $this->model->address;
        $data['phone']     = $this->model->phone;
        $data['mobile']    = $this->model->mobile;
        $data['email']     = $this->model->email;
        $data['image']     = base_url().'images/employee/'.$this->model->image;
        $data['desc']      = $this->model->desc;
        
        $this->load->view('employee_detail', $data);
    }
    
    function add()
    {
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'employee_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept']    = $this->dept->combo_all();  
        $data['division'] = $this->division->combo(); 
        
        $this->load->view('employee_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'employee_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
         
        $data['dept']     = $this->dept->combo_all();  
        $data['division'] = $this->division->combo(); 
        
	// Form validation
        $this->form_validation->set_rules('csection', 'Section Type', 'required|callback_valid_section['.$this->input->post('cdept').']');
        $this->form_validation->set_rules('cdivision', 'Division', 'required');
        $this->form_validation->set_rules('crole', 'Role', 'required');
        $this->form_validation->set_rules('ctime', 'Time Work', 'required');
        $this->form_validation->set_rules('tnip', 'NIP', 'required|numeric|callback_valid_nip');
        $this->form_validation->set_rules('tatt', 'Att Code', 'required|numeric|callback_valid_att');
        $this->form_validation->set_rules('cdept', 'Department', '');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_name['.$this->input->post('tnip').']');
        $this->form_validation->set_rules('tnickname', 'Nick Name', '');
        $this->form_validation->set_rules('cgenre', 'Genre', 'required');
        $this->form_validation->set_rules('tbornplace', 'Born Place', '');
        $this->form_validation->set_rules('tborndate', 'Born Date', '');
        $this->form_validation->set_rules('creligion', 'Religion', 'required');
        $this->form_validation->set_rules('tethnic', 'Ethnic', '');
        $this->form_validation->set_rules('rmarried', 'Marital Status', '');
        $this->form_validation->set_rules('tidno', 'ID-No', '');
        $this->form_validation->set_rules('taddress', 'Address', '');
        $this->form_validation->set_rules('tphone', 'Phone', '');
        $this->form_validation->set_rules('tmobile', 'Mobile', '');
        $this->form_validation->set_rules('temail', 'Email', 'valid_email');
        $this->form_validation->set_rules('tdesc', 'Description', '');
        
        // account
        $this->form_validation->set_rules('taccname', 'Account Name', '');
        $this->form_validation->set_rules('taccno', 'Account No', 'numeric');
        $this->form_validation->set_rules('tbank', 'Bank', '');
        
        if ($this->form_validation->run($this) == TRUE)
        {
//            if($this->input->post('csection') == 'academic'){ $division = 0; }else { $division = $this->input->post('cdivision'); }
            
            $this->model->type        = $this->input->post('csection');
            $this->model->division_id = $this->input->post('cdivision');
            $this->model->role        = $this->input->post('crole');
            $this->model->work_time   = $this->input->post('ctime');
            $this->model->dept_id     = $this->input->post('cdept');;
            $this->model->nip         = $this->input->post('tnip');
            $this->model->attcode     = $this->input->post('tatt');
            $this->model->name        = $this->input->post('tname');
            $this->model->first_title = $this->input->post('tfirst');
            $this->model->end_title   = $this->input->post('tend');
            $this->model->nickname    = $this->input->post('tnickname');
            $this->model->genre       = $this->input->post('cgenre');
            $this->model->born_place  = $this->input->post('tbornplace');
            $this->model->born_date   = $this->input->post('tborndate');
            $this->model->religion    = $this->input->post('creligion');
            $this->model->ethnic      = $this->input->post('tethnic');
            $this->model->status      = $this->input->post('rmarried');
            $this->model->id_no       = $this->input->post('tidno');
            $this->model->address     = $this->input->post('taddress');
            $this->model->phone       = $this->input->post('tphone');
            $this->model->mobile      = $this->input->post('tmobile');
            $this->model->email       = $this->input->post('temail');
            $this->model->desc        = $this->input->post('tdesc');
            $this->model->bank_name   = $this->input->post('tbank');
            $this->model->acc_name    = $this->input->post('taccname');
            $this->model->acc_no      = $this->input->post('taccno');
            $this->model->active      = 1;
            
            // ==================== upload ========================
            
            $config['upload_path']   = './images/employee/';
            $config['file_name']     = $this->input->post('tnip');
            $config['allowed_types'] = 'jpg|jpeg';
            $config['overwrite']     = TRUE;
            $config['max_size']	     = '150';
            $config['max_width']     = '1000';
            $config['max_height']    = '1000';
            $config['remove_spaces'] = TRUE;
            
            $this->load->library('upload', $config);
            
            if ( !$this->upload->do_upload("userfile")){ $data['error'] = $this->upload->display_errors();  $this->model->image = 'default.png';}
            else{ $info = $this->upload->data();  $this->model->image = $info['file_name']; }
            
            // ==================== upload ========================
            
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add/');
        }
        else{ $this->load->view('employee_form', $data); }

    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        if ($this->loan->cek_loan($uid) == TRUE && $this->payrolltrans->cek_payroll($uid) == TRUE)
        {
            $img = $this->model->where('id', $uid)->get()->image;
            if ($img != 'default.png'){ $img = "./images/employee/".$img; unlink("$img"); }

            $this->model->where('id', $uid)->get();
            $this->model->delete();
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
        }
        else { $this->session->set_flashdata('message', "1 $this->title still have loan & transaction..!");  }
        redirect($this->title);
    }
    
    function update($uid)
    {
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'employee_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept']     = $this->dept->combo_all(); 
        $data['division'] = $this->division->combo_all(); 
        
        $this->model->where('id', $uid)->get();
        
        $data['default']['section']   = $this->model->type;
        $data['default']['division']  = $this->model->division_id;
        $data['default']['role']      = $this->model->role;
        $data['default']['time']      = $this->model->work_time;
        $data['default']['dept']      = $this->model->dept_id;
        $data['default']['nip']       = $this->model->nip;
        $data['default']['att']       = $this->model->attcode;
        $data['default']['name']      = $this->model->name;
        $data['default']['first']     = $this->model->first_title;
        $data['default']['end']       = $this->model->end_title;
        $data['default']['nickname']  = $this->model->nickname;
        $data['default']['genre']     = $this->model->genre;
        $data['default']['bornplace'] = $this->model->born_place;
        $data['default']['borndate']  = $this->model->born_date;
        $data['default']['religion']  = $this->model->religion;
        $data['default']['ethnic']    = $this->model->ethnic;
        $data['default']['married']   = $this->model->status;
        $data['default']['idno']      = $this->model->id_no;
        $data['default']['address']   = $this->model->address;
        $data['default']['phone']     = $this->model->phone;
        $data['default']['mobile']    = $this->model->mobile;
        $data['default']['email']     = $this->model->email;
        $data['default']['image']     = base_url().'images/employee/'.$this->model->image;
        $data['default']['desc']      = $this->model->desc;
        
        $data['default']['bank']      = $this->model->bank_name;
        $data['default']['accno']     = $this->model->acc_no;
        $data['default']['accname']   = $this->model->acc_name;
        
	$this->session->set_userdata('curid', $this->model->id);
        $this->load->view('employee_update', $data);
    }
    
    function active($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $active = $this->model->where('id',$uid)->get()->active;
        if ( $active == 0 ){ $val = array('active' => 1); $st = 'activate'; } elseif ( $active == 1 ){ $val = array('active' => 0); $st = 'inactivate';  }
        $this->model->where('id ', $uid)->update($val, TRUE);
        $this->session->set_flashdata('message', "1 $this->title successfully $st..!");
        redirect($this->title);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_form';
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['dept'] = $this->dept->combo_all(); 
        $data['division'] = $this->division->combo_all(); 
        $data['default']['image'] = null;
        $data['default']['nip'] = $this->input->post('tnip');
        
	// Form validation
        $this->form_validation->set_rules('csection', 'Section Type', 'required|callback_valid_section['.$this->input->post('cdept').']');
        $this->form_validation->set_rules('cdivision', 'Division', 'callback_valid_division['.$this->input->post('csection').']');
        $this->form_validation->set_rules('ctime', 'Time Work', 'required');
        $this->form_validation->set_rules('crole', 'Role', 'required');
        $this->form_validation->set_rules('tnip', 'NIP', 'required|numeric|callback_validating_nip');
        $this->form_validation->set_rules('tatt', 'Att Code', 'required|numeric|callback_validating_att');
        $this->form_validation->set_rules('cdept', 'Department', '');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validating_name['.$this->input->post('tnip').']');
        $this->form_validation->set_rules('tnickname', 'Nick Name', '');
        $this->form_validation->set_rules('cgenre', 'Genre', '');
        $this->form_validation->set_rules('tbornplace', 'Born Place', '');
        $this->form_validation->set_rules('tborndate', 'Born Date', '');
        $this->form_validation->set_rules('creligion', 'Religion', '');
        $this->form_validation->set_rules('tethnic', 'Ethnic', '');
        $this->form_validation->set_rules('rmarried', 'Marital Status', '');
        $this->form_validation->set_rules('tidno', 'ID-No', '');
        $this->form_validation->set_rules('taddress', 'Address', '');
        $this->form_validation->set_rules('tphone', 'Phone', '');
        $this->form_validation->set_rules('tmobile', 'Mobile', '');
        $this->form_validation->set_rules('temail', 'Email', 'valid_email');
        $this->form_validation->set_rules('tdesc', 'Description', '');
         // account
        $this->form_validation->set_rules('taccname', 'Account Name', '');
        $this->form_validation->set_rules('taccno', 'Account No', 'numeric');
        $this->form_validation->set_rules('tbank', 'Bank', '');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();
            
            if($this->input->post('csection') == 'academic'){ $division = 0; }else { $division = $this->input->post('cdivision'); }
            
            $this->model->type        = $this->input->post('csection');
            $this->model->division_id = $this->input->post('cdivision');
            $this->model->role        = $this->input->post('crole');
            $this->model->work_time   = $this->input->post('ctime');
            $this->model->dept_id     = $this->input->post('cdept');
            $this->model->nip         = $this->input->post('tnip');
            $this->model->attcode     = $this->input->post('tatt');
            $this->model->name        = $this->input->post('tname');
            $this->model->first_title = $this->input->post('tfirst');
            $this->model->end_title   = $this->input->post('tend');
            $this->model->nickname    = $this->input->post('tnickname');
            $this->model->genre       = $this->input->post('cgenre');
            $this->model->born_place  = $this->input->post('tbornplace');
            $this->model->born_date   = $this->input->post('tborndate');
            $this->model->religion    = $this->input->post('creligion');
            $this->model->ethnic      = $this->input->post('tethnic');
            $this->model->status      = $this->input->post('rmarried');
            $this->model->id_no       = $this->input->post('tidno');
            $this->model->address     = $this->input->post('taddress');
            $this->model->phone       = $this->input->post('tphone');
            $this->model->mobile      = $this->input->post('tmobile');
            $this->model->email       = $this->input->post('temail');
            $this->model->desc        = $this->input->post('tdesc');
            $this->model->bank_name   = $this->input->post('tbank');
            $this->model->acc_name    = $this->input->post('taccname');
            $this->model->acc_no      = $this->input->post('taccno');
            $this->model->active      = 1;
                  
            // ==================== upload ========================
            
            $config['upload_path']   = './images/employee/';
            $config['file_name']     = $this->input->post('tnip');
            $config['allowed_types'] = 'jpg|jpeg';
            $config['overwrite']     = TRUE;
            $config['max_size']	     = '150';
            $config['max_width']     = '1000';
            $config['max_height']    = '1000';
            $config['remove_spaces'] = TRUE;
            
            $this->load->library('upload', $config);
            
            if ( !$this->upload->do_upload("userfile")){ $data['error'] = $this->upload->display_errors(); }
            else{ $info = $this->upload->data();  $this->model->image = $info['file_name']; }
            
            // ==================== upload ========================
            
            $this->model->save();
            
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            
//            echo 'true'; 
        }
        else
        {
            $this->load->view('employee_update', $data);
//           echo validation_errors();
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
        }
        
    }
    
    function import()
    {
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'attendance_form';
	$data['form_action'] = site_url($this->title.'/import_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        $data['error']  = '';
        $this->load->view('employee_import', $data);
    }
    
    function import_process()
    {
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'employee_import';
	$data['form_action'] = site_url($this->title.'/import_process');
        $data['error'] = null;
	
        $this->form_validation->set_rules('userfile', 'Import File', '');
        
        if ($this->form_validation->run($this) == TRUE)
        {
             // ==================== upload ========================
            
            $config['upload_path']   = './uploads/';
            $config['file_name']     = 'employee';
            $config['allowed_types'] = 'csv';
            $config['overwrite']     = TRUE;
            $config['max_size']	     = '1000';
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            
            if ( !$this->upload->do_upload("userfile"))
            { 
               $data['error'] = $this->upload->display_errors(); 
               $this->load->view('attendance_import', $data);
            }
            else
            { 
               // success page 
              $this->import_attendance($config['file_name'].'.csv');
              $info = $this->upload->data(); 
              $this->session->set_flashdata('message', "One $this->title data successfully imported!");
              redirect($this->title.'/import');
            }                
        }
        else { $this->load->view('employee_import', $data); }
        
    }
    
    private function import_attendance($filename)
    {
        $this->load->helper('file');
        $emp = new Employee_lib();
        $csvreader = new CSVReader();
        $filename = './uploads/'.$filename;
        
        $result = $csvreader->parse_file($filename);
        
        foreach($result as $res)
        {
           if($this->valid_coloumn($res) == TRUE)
           {  
             if ($this->validation_import($res['DIVISION'],$res['ATTCODE'],$res['NIP'],  strtolower($res['TYPE']),$res['ACTIVE']) == TRUE)
             {  
                $emp->save($this->division->get_id($res['DIVISION']), $this->dept->get_id($res['DEPARTMENT']), $res['ATTCODE'], $res['NIP'], $res['NAME'], strtolower($res['TYPE']), $res['ACTIVE']).'<br>'; 
             } 
           } 
        }
    }
    
    private function valid_coloumn($res)
    {
        if(isset($res['DIVISION']) && isset($res['ATTCODE']) && isset($res['NIP']) && isset($res['TYPE']) && 
           isset($res['DEPARTMENT']) && isset($res['NAME']) && isset($res['ACTIVE']))
        { return TRUE; }else { return FALSE; }
    }
    
    private function validation_import($division=null,$attcode=null,$nip=null,$type=null,$active=null)
    {
        $res[0] = FALSE;
        $res[1] = FALSE;
        $res[2] = FALSE;
        $res[3] = FALSE;
        $res[4] = FALSE;
        
        if ($this->division->get_id($division)){ $res[0] = TRUE;}
        if ($this->valid_att($attcode) == TRUE){ $res[1] = TRUE; }
        if ($this->valid_nip($nip) == TRUE){ $res[2] = TRUE; }
        switch ($type){ case 'non': $res[3] = TRUE; break; case 'academic': $res[3] = TRUE; break; default: $res[3] = FALSE; }
        if ($active == 0){ $res[4] = TRUE; }elseif ($active == 1){ $res[4] = TRUE; }else { $res[4] = FALSE; }
        
        if ($res[0] == TRUE && $res[1] == TRUE && $res[2] == TRUE && $res[3] == TRUE && $res[4] == TRUE){ return TRUE; }else { return FALSE; }
    }
    
    public function valid_division($division,$section)
    {
        if ($section == 'non')
        { if (!$division){ $this->form_validation->set_message('valid_division', "Division required..!"); return FALSE; } else{ return TRUE;} }
    }
    
    public function valid_section($section,$dept)
    {
        if ($section == 'academic')
        {
            if (!$dept){ $this->form_validation->set_message('valid_section', "Department required..!"); return FALSE; }
            else { return TRUE; }
        }
        else { return TRUE; }
    }

    public function valid_nip($nip)
    {
        $val = $this->model->where('nip', $nip)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_nip', "Employee [$nip] already registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_att($code)
    {
        $val = $this->model->where('attcode', $code)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_att', "Employee [$code] already registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_nip($nip)
    {
        $val = $this->model->where_not_in('id', $this->session->userdata('curid'))->where('nip', $nip)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_nip', "NIP [$nip] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function valid_name($name,$nip)
    {
        $this->model->where('name', $name);
        $val = $this->model->where('nip', $nip)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_name', "Student [$nip - $name] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_name($name,$nip)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $this->model->where('name', $name);
        $val = $this->model->where('nip', $nip)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_name', "Employee [$nip - $name] Already Registered..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validating_att($att)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('attcode', $att)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validating_att', "Employee Att-Code [$att] Already Registered..!");
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
        
        $data['dept']    = $this->dept->combo_all();
        $data['division'] = $this->division->combo_all();
        
        $this->load->view('employee_report_panel', $data);
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
        
        $data['department'] = $this->dept->get_name($this->input->post('cdept'));
        $data['division']   = $this->division->get_name($this->input->post('cdivision'));
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
                
        $data['results'] = $this->em->report($this->input->post('cdept'),$this->input->post('cdivision'), $this->input->post('crole') ,$this->input->post('cstatus'))->result();
        
        $this->load->view('employee_report', $data);
    }

}

?>