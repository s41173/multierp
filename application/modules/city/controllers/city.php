<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class City extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
//        $this->load->model('City_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
        $this->model = new Cities();

    }

    private $properti, $modul, $title, $model;

    function index()
    {
        $this->get_last_city();
        
//        $data['source'] = site_url()."/$this->title/get_json";
//        // ===== chart  =======
//        $data['graph'] = $this->chart($this->input->post('ccurrency'));
//        
//        $this->load->view('city_chart', $data);
    }
    
    function get_json()
    {        
        $data = $this->db->select('OrderDate, ProductName, Quantity')->from('coba')->get()->result();
        echo json_encode($data); 
    }
    
    private function chart($cur='IDR')
    {
        $fusion = new Fusioncharts();
        $chart  = base_url().'public/flash/Column3D.swf';
        
        $ps = new Period();
        $ps->get();
        $year = $ps->year;

        $arpData[0][1] = 'January';
        $arpData[0][2] = 10000;

        $arpData[1][1] = 'February';
        $arpData[1][2] = 2000;

        $arpData[2][1] = 'March';
        $arpData[2][2] = 4567;

        $arpData[3][1] = 'April';
        $arpData[3][2] = 3000;

        $arpData[4][1] = 'May';
        $arpData[4][2] = 4000;

        $arpData[5][1] = 'June';
        $arpData[5][2] = 5000;

        $arpData[6][1] = 'July';
        $arpData[6][2] = 6000;

        $arpData[7][1] = 'August';
        $arpData[7][2] = 7008;

        $strXML1 = $fusion->setDataXML($arpData,'','') ;
        $graph   = $fusion->renderChart($chart,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
    }

    function get_last_city()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'city_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        $data['source'] = site_url()."/$this->title/get_json";
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //

        $citys     = $this->model->get($this->modul['limit'],$offset);
        $num_rows  = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_city');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination

            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('#','No', 'Province', 'City', 'District', 'Village', 'Zip', 'Action');

            $i = 0 + $offset;
            foreach ($citys as $city)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $city->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, strtoupper($city->province), strtoupper($city->name), strtoupper($city->district), strtoupper($city->village), $city->zip,
                    anchor($this->title.'/update/'.$city->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$city->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else{  $data['message'] = "No $this->title data was found!"; }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'city_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// ---------------------------------------- //
        $citys = $this->model->where('name', $this->input->post('tname'))->get();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Province', 'City', 'District', 'Village', 'Zip', 'Action');

        $i = 0;
        foreach ($citys as $city)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $city->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                form_checkbox($datax), ++$i, strtoupper($city->province), strtoupper($city->name), strtoupper($city->district), strtoupper($city->village), $city->zip,
                anchor($this->title.'/update/'.$city->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$city->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    
    function get_list()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];

        $result = null;
        if ($this->input->post('tvalue')){ $result = $this->model->where($this->input->post('ctype'), $this->input->post('tvalue'))->get(); }
        else { $result = $this->model->get(); } 

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Province', 'City', 'District', 'Village', 'Zip', 'Action');

        $i = 0;
        foreach ($result as $res)
        {
           $data = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$res->zip.'\',\'tzip\')'
                         );

            $this->table->add_row
            (
                ++$i, $res->province, $res->name, $res->district, $res->village, $res->zip,
                form_button($data)
            );
        }

        $data['form_action'] = site_url($this->title.'/get_list');
        $data['table'] = $this->table->generate();
        $this->load->view('city_list', $data);
    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->model->where('id', $uid)->get();
        $this->model->delete();
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); // set flash data message dengan session
        redirect($this->title);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        
        $this->load->view('city_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'city_view';
	$data['form_action'] = site_url($this->title.'/add_process');

	// Form validation
        $this->form_validation->set_rules('tprovince', 'Province', 'required');
        $this->form_validation->set_rules('tcity', 'Name', 'required');
        $this->form_validation->set_rules('tdistrict', 'District');
        $this->form_validation->set_rules('tvillage', 'Village');
        $this->form_validation->set_rules('tzip', 'Zip', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->province = strtoupper($this->input->post('tprovince'));
            $this->model->name = strtoupper($this->input->post('tcity'));
            $this->model->district = strtoupper($this->input->post('tdistrict'));
            $this->model->village = strtoupper($this->input->post('tvillage'));
            $this->model->zip = $this->input->post('tzip');
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

    // Fungsi update untuk menset texfield dengan nilai dari database
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'city_form';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('city/','<span>back</span>', array('class' => 'back')));

        $city = $this->model->where('id', $uid)->get();
        
        $data['default']['province'] = $city->province;
        $data['default']['city'] = $city->name;
        $data['default']['district'] = $city->district;
        $data['default']['village'] = $city->village;
        $data['default']['zip'] = $city->zip;

	$this->session->set_userdata('langid', $city->id);
        $this->load->view('city_form', $data);
    }


    public function valid_village($name)
    {
       $val = $this->model->where('village', $name)->count();

       if ($val > 0)
       {
           $this->form_validation->set_message('valid_district', "This village is already registered.!");
           return FALSE;
       }
       else{ return TRUE; }
    }
    
    public function valid_zip($val)
    {
       $val = $this->model->where('zip', $val)->count();

       if ($val > 0)
       {
           $this->form_validation->set_message('valid_zip', "This $this->title is already registered.!");
           return FALSE;
       }
       else{ return TRUE; }
    }
    
    public function validation_zip($val)
    {
       $id = $this->session->userdata('langid');
       $this->model->where_not_in('id', $id); 
       $val = $this->model->where('zip', $val)->count();

       if ($val > 0)
       {
           $this->form_validation->set_message('validation_zip', "This $this->title is already registered.!");
           return FALSE;
       }
       else{ return TRUE; }
    }

    function validation_village($name)
    {
	$id = $this->session->userdata('langid');
        $this->model->where_not_in('id', $id);
        $val = $this->model->where('village', $name)->count();
        
	if ($val > 0)
        {
            $this->form_validation->set_message('validation_village', 'This village is already registered!');
            return FALSE;
        }
        else{ return TRUE; }
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'city_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('city/','<span>back</span>', array('class' => 'back')));

	// Form validation
        
        $this->form_validation->set_rules('tprovince', 'Province', 'required');
        $this->form_validation->set_rules('tcity', 'Name', 'required');
        $this->form_validation->set_rules('tdistrict', 'Name', 'required');
        $this->form_validation->set_rules('tvillage', 'Name', 'required|callback_validation_village');
        $this->form_validation->set_rules('tzip', 'Name', 'required|callback_validation_zip');


        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('langid'))->get();
            
            $this->model->province = strtoupper($this->input->post('tprovince'));
            $this->model->name = strtoupper($this->input->post('tcity'));
            $this->model->district = strtoupper($this->input->post('tdistrict'));
            $this->model->village = strtoupper($this->input->post('tvillage'));
            $this->model->zip = $this->input->post('tzip');
            $this->model->save();

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('langid'));
            $this->session->unset_userdata('langid');
            
            echo 'true';
        }
        else
        {  //$this->load->view('city_update', $data); 
           echo validation_errors(); 
        }
    }

}

?>