<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Receipt_type extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account  = $this->load->library('account_lib');
        $this->dept = $this->load->library('dept_lib');
        $this->model = new Receipt();
        
    }

    private $properti, $modul, $title, $account,$dept;
    private $user,$journalgl,$currency, $model;

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
      $data = $this->db->from('inventaris')->like('name',$keyword)->get();

      // format keluaran di dalam array
      foreach($data->result() as $row)
      {
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>$row->name,
            'data'   =>$row->id
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
        $data['main_view'] = 'receipt_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
	// ---------------------------------------- //
        $costs = $this->model->get($this->modul['limit'], $offset);
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
            $this->table->set_heading('No', 'Department', 'Cur', 'Name', 'Account', 'Action');
//
            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, $this->dept->get_name($cost->dept_id), ucfirst($cost->currency), ucfirst($cost->name), $this->acc_list($cost->id),
                    anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                    anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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

    private function acc_list($id)
    {
        $res = $this->model->get_by_id($id);
        $val = '<table>'.
               '<tr> <td> <b> SPP Di Muka </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p1).' : '.$this->account->get_name($res->p1).'</td> </tr>'.
               '<tr> <td> <b> SPP Bulan Berjalan </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p2).' : '.$this->account->get_name($res->p2).'</td> </tr>'.
               '<tr> <td> <b> SPP Tunggakan </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p3).' : '.$this->account->get_name($res->p3).'</td> </tr>'.
               '<tr> <td> <b> OSIS </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p4).' : '.$this->account->get_name($res->p4).'</td> </tr>'.
               '<tr> <td> <b> Tunggakan OSIS </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p5).' : '.$this->account->get_name($res->p5).'</td> </tr>'.
               '<tr> <td> <b> Komputer </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p6).' : '.$this->account->get_name($res->p6).'</td> </tr>'.
               '<tr> <td> <b> Tunggakan Komputer </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p7).' : '.$this->account->get_name($res->p7).'</td> </tr>'.
               '<tr> <td> <b> Praktek </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p8).' : '.$this->account->get_name($res->p8).'</td> </tr>'.
               '<tr> <td> <b> Tunggakan Praktek </b> <td> <td> : </td> <td>'.$this->account->get_code($res->p9).' : '.$this->account->get_name($res->p9).'</td> </tr>'.
               '<tr> <td> <b> Bantuan SPP </b> <td> <td> : </td> <td>'.$this->account->get_code($res->discount).' : '.$this->account->get_name($res->discount).'</td> </tr>'.
               '</table>'
            ;
        return $val;
    }
    
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'receipt_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
//
        $data['dept'] = $this->dept->combo();
        
	// ---------------------------------------- //
        $this->model->where('dept_id', $this->input->post('cdept'));
        $costs = $this->model->get();
        
        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
       $this->table->set_heading('No', 'Department', 'Cur', 'Name', 'Account', 'Action');
//
        $i = 0;
        foreach ($costs as $cost)
        {
            $this->table->add_row
            (
                ++$i, $this->dept->get_name($cost->dept_id), ucfirst($cost->currency), ucfirst($cost->name), $this->acc_list($cost->id),
                anchor($this->title.'/update/'.$cost->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                anchor($this->title.'/delete/'.$cost->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function counter()
    {
        if ($this->model->count() == 0){  $res = 1; }
        else
        {
           $this->model->select_max('no');
           $res = $this->model->get();
           $res = $res->no+1;
        }
        return $res;
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'receipt_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();  
        $data['dept'] = $this->dept->combo();  
        
        $this->load->view('receipt_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cost_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();  
        $data['dept'] = $this->dept->combo(); 

	// Form validation
        $this->form_validation->set_rules('ccur', 'Currency', 'required');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_receipt['.$this->input->post('cdept').']');
        $this->form_validation->set_rules('tdesc', 'Desc', 'required');
        $this->form_validation->set_rules('tspp1', 'SPP Bayar Dimuka', 'required');
        $this->form_validation->set_rules('tspp2', 'SPP Bulan Berjalan', 'required');
        $this->form_validation->set_rules('tspp3', 'SPP Tunggakan', 'required');
        $this->form_validation->set_rules('tosis1', 'OSIS', 'required');
        $this->form_validation->set_rules('tosis2', 'OSIS Tunggakan', 'required');
        $this->form_validation->set_rules('tkom1', 'Komputer', 'required');
        $this->form_validation->set_rules('tkom2', 'Komputer Tunggakan', 'required');
        $this->form_validation->set_rules('tpraktek1', 'Praktek', 'required');
        $this->form_validation->set_rules('tpraktek2', 'Praktek Tunggakan', 'required');
        $this->form_validation->set_rules('tdiscount', 'Bantuan', 'required');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->currency = $this->input->post('ccur');
            $this->model->dept_id  = $this->input->post('cdept');
            $this->model->name     = $this->input->post('tname');
            $this->model->desc     = $this->input->post('tdesc');
            
            $this->model->p1       = $this->account->get_id_code($this->input->post('tspp1'));
            $this->model->p2       = $this->account->get_id_code($this->input->post('tspp2'));
            $this->model->p3       = $this->account->get_id_code($this->input->post('tspp3'));
            $this->model->p4       = $this->account->get_id_code($this->input->post('tosis1'));
            $this->model->p5       = $this->account->get_id_code($this->input->post('tosis2'));
            $this->model->p6       = $this->account->get_id_code($this->input->post('tkom1'));
            $this->model->p7       = $this->account->get_id_code($this->input->post('tkom2'));
            $this->model->p8       = $this->account->get_id_code($this->input->post('tpraktek1'));
            $this->model->p9       = $this->account->get_id_code($this->input->post('tpraktek2'));
            $this->model->discount = $this->account->get_id_code($this->input->post('tdiscount'));

            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title);
             echo 'true';
        }
        else
        {
//               $this->load->view('template', $data);
            echo validation_errors();
        }

    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        $this->model->where('id', $uid)->get();
        
        $this->journalgl->remove_journal('IJ', $this->model->no); // journal gl
        
        $this->model->delete();
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        
        redirect($this->title);
    }
    
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'receipt_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();  
        $data['dept'] = $this->dept->combo(); 

        $cost = $this->model->where('id', $uid)->get();
        
        $data['default']['cur']  = $cost->currency;
        $data['default']['dept'] = $cost->dept_id;
        $data['default']['name'] = $cost->name;
        $data['default']['desc'] = $cost->desc;
        
        $data['default']['spp1']      = $this->account->get_code($cost->p1);
        $data['default']['spp2']      = $this->account->get_code($cost->p2);
        $data['default']['spp3']      = $this->account->get_code($cost->p3);
        $data['default']['osis1']     = $this->account->get_code($cost->p4);
        $data['default']['osis2']     = $this->account->get_code($cost->p5);
        $data['default']['kom1']      = $this->account->get_code($cost->p6);
        $data['default']['kom2']      = $this->account->get_code($cost->p7);
        $data['default']['praktek1']  = $this->account->get_code($cost->p8);
        $data['default']['praktek2']  = $this->account->get_code($cost->p9);
        $data['default']['discount']  = $this->account->get_code($cost->discount);

	$this->session->set_userdata('curid', $cost->id);
        $this->load->view('receipt_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'receipt_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('account/','<span>back</span>', array('class' => 'back')));
        
        $data['currency'] = $this->currency->combo();  
        $data['dept'] = $this->dept->combo(); 

	// Form validation
        $this->form_validation->set_rules('ccur', 'Currency', 'required');
        $this->form_validation->set_rules('cdept', 'Department', 'required');
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('tdesc', 'Desc', 'required');
        $this->form_validation->set_rules('tspp1', 'SPP Bayar Dimuka', 'required');
        $this->form_validation->set_rules('tspp2', 'SPP Bulan Berjalan', 'required');
        $this->form_validation->set_rules('tspp3', 'SPP Tunggakan', 'required');
        $this->form_validation->set_rules('tosis1', 'OSIS', 'required');
        $this->form_validation->set_rules('tosis2', 'OSIS Tunggakan', 'required');
        $this->form_validation->set_rules('tkom1', 'Komputer', 'required');
        $this->form_validation->set_rules('tkom2', 'Komputer Tunggakan', 'required');
        $this->form_validation->set_rules('tpraktek1', 'Praktek', 'required');
        $this->form_validation->set_rules('tpraktek2', 'Praktek Tunggakan', 'required');
        $this->form_validation->set_rules('tdiscount', 'Bantuan', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();

            $this->model->currency = $this->input->post('ccur');
            $this->model->dept_id  = $this->input->post('cdept');
            $this->model->desc     = $this->input->post('tdesc');
            
            $this->model->p1       = $this->account->get_id_code($this->input->post('tspp1'));
            $this->model->p2       = $this->account->get_id_code($this->input->post('tspp2'));
            $this->model->p3       = $this->account->get_id_code($this->input->post('tspp3'));
            $this->model->p4       = $this->account->get_id_code($this->input->post('tosis1'));
            $this->model->p5       = $this->account->get_id_code($this->input->post('tosis2'));
            $this->model->p6       = $this->account->get_id_code($this->input->post('tkom1'));
            $this->model->p7       = $this->account->get_id_code($this->input->post('tkom2'));
            $this->model->p8       = $this->account->get_id_code($this->input->post('tpraktek1'));
            $this->model->p9       = $this->account->get_id_code($this->input->post('tpraktek2'));
            $this->model->discount = $this->account->get_id_code($this->input->post('tdiscount'));
            
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true';
        }
        else
        {
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->load->view('receipt_update', $data);
//            echo validation_errors();
        }
    }

    public function valid_receipt($name,$dept)
    {
        $this->model->where('dept_id', $dept);
        $val = $this->model->where('name', $name)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_receipt', "Invalid Receipt..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validation_cost($acc)
    {
        $this->model->where_not_in('id', $this->session->userdata('curid'));
        $val = $this->model->where('account_id', $this->account->get_id_code($acc))->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('validation_cost', "Invalid Account..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function report()
    {
        $data['costs'] = $this->model->get();
        $data['log'] = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $this->load->view('cost_report', $data);
    }

}

?>