<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gproduct extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Gproduct_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->category = $this->load->library('gcategory');
        $this->unit = $this->load->library('unit_lib');

        $this->currency = $this->load->library('currency_lib');
    }

    private $properti, $modul, $title;
    private $category, $unit, $currency;

    function index()
    { $this->get_last_product(); }

   public function autocomplete()
   {
//      // tangkap variabel keyword dari URL
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('gproduct')->like('name',$keyword)->get();

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

    function get_last_product()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor('main','<span>back</span>', array('class' => 'back')));

        $data['category'] = $this->category->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $products = $this->Gproduct_model->get_last_product($this->modul['limit'], $offset)->result();
        $num_rows = $this->Gproduct_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_product');
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
            $this->table->set_heading('#','No', 'Code', 'Category', 'Cur', 'Name / Model', 'Qty', 'Unit Cost', 'Price', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($products as $product)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $product->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, 'GPRO-0'.$product->id, $this->category->get_name($product->category), $product->currency, $product->name, $product->qty.' '.$product->unit, number_format($product->hpp), number_format($product->price), number_format($product->hpp*$product->qty),
                    anchor($this->title.'/update/'.$product->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$product->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();

            //          fasilitas check all
            $js = "onClick='cekall($i)'";
            $sj = "onClick='uncekall($i)'";
            $data['radio1'] = form_radio('newsletter', 'accept1', FALSE, $js).'Check';
            $data['radio2'] = form_radio('newsletter', 'accept2', FALSE, $sj).'Uncheck';
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'product_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['category'] = $this->category->combo_all();

	// ---------------------------------------- //
        $products = $this->Gproduct_model->search($this->input->post('ccategory'), $this->input->post('tsearch'))->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Code', 'Category', 'Cur', 'Name / Model', 'Qty', 'Price', 'Action');

        $i = 0;
        foreach ($products as $product)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $product->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                form_checkbox($datax), ++$i, 'GPRO-0'.$product->id, $this->category->get_name($product->category), $product->currency, $product->name, $product->qty.' '.$product->unit, number_format($product->price),
                anchor($this->title.'/update/'.$product->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$product->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        // fasilitas check all
        $js = "onClick='cekall($i)'";
        $sj = "onClick='uncekall($i)'";
        $data['radio1'] = form_radio('newsletter', 'accept1', FALSE, $js).'Check';
        $data['radio2'] = form_radio('newsletter', 'accept2', FALSE, $sj).'Uncheck';
            
	$this->load->view('template', $data);
    }

    function get_list($cur='IDR')
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_list';
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['category'] = $this->category->combo_all();

        $category = $this->input->post('ccategory');
        $name = $this->input->post('tsearch');

        $products = $this->Gproduct_model->get_list_product($cur,$category,$name)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Category', 'Name / Model', 'Qty', 'Action');

            $i = 0;
            foreach ($products as $product)
            {
               $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$product->name.'\',\'tproduct\')');

                $this->table->add_row
                (
                    ++$i, 'GPRO-0'.$product->id, $this->category->get_name($product->category), $product->name, $product->qty.' '.$product->unit,
                    form_button($datax)
                );
            }

            $data['table'] = $this->table->generate();

            $this->load->view('product_list', $data);
    }

    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);

        if ( $this->cek_relation($uid) == TRUE )
        {
           $this->Gproduct_model->delete($uid);
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        redirect($this->title);
    }

    function delete_all()
    {
      $this->acl->otentikasi_admin($this->title);
      $cek = $this->input->post('cek');

      if($cek)
      {
        $jumlah = count($cek);
        for ($i=0; $i<$jumlah; $i++)
        {
            if ( $this->cek_relation($cek[$i]) == TRUE )
            {
               $this->Gproduct_model->delete($cek[$i]);
               $this->session->set_flashdata('message', "$jumlah $this->title successfully removed..!!");
            }
            else { $this->session->set_flashdata('message', "$this->title related to another component..!"); }
        }
      }
      else
      { $this->session->set_flashdata('message', "No $this->title Selected..!!"); }
      redirect($this->title);
    }

    private function cek_relation($id)
    {
        $name = $this->Gproduct_model->get_product_by_id($id)->row();
//        if ($in == TRUE && $out == TRUE) { return TRUE; } else { return FALSE; }
        return TRUE;
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['category'] = $this->category->combo();
        $data['unit'] = $this->unit->combo();
        $data['currency'] = $this->currency->combo();

        $this->load->view('product_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('product/','<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_product');
        $this->form_validation->set_rules('ccategory', 'Category Person', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        $this->form_validation->set_rules('cunit', 'Unit', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $product = array('name' => $this->input->post('tname'), 'category' => $this->input->post('ccategory'),
                             'desc' => $this->input->post('tdesc'), 'unit' => $this->input->post('cunit'),
                             'currency' => $this->input->post('ccurrency'));
            
            $this->Gproduct_model->add($product);
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
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['category'] = $this->category->combo();
        $data['unit'] = $this->unit->combo();
        $data['currency'] = $this->currency->combo();

        $product = $this->Gproduct_model->get_product_by_id($uid)->row();

        $data['default']['category'] = $product->category;
        $data['default']['name'] = $product->name;
        $data['default']['desc'] = $product->desc;
        $data['default']['currency'] = $product->currency;
        $data['default']['qty'] = $product->qty;
        $data['default']['price'] = $product->price;
        $data['default']['unit'] = $product->unit;
        $data['default']['hpp'] = $product->hpp;

	$this->session->set_userdata('curid', $product->id);
        $this->load->view('product_update', $data);
    }


    public function valid_product($name)
    {
        if ($this->Gproduct_model->valid_product($name) == FALSE)
        {
            $this->form_validation->set_message('valid_product', "This $this->title is already registered.!");
            return FALSE;
        }
        else { return TRUE; }
    }

    function validation_product($name)
    {
	$id = $this->session->userdata('curid');
	if ($this->Gproduct_model->validating_product($name,$id) == FALSE)
        {
            $this->form_validation->set_message('validation_product', 'This product is already registered!');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('product/','<span>back</span>', array('class' => 'back')));
        $data['currency'] = $this->currency->combo();

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_validation_product');
        $this->form_validation->set_rules('ccategory', 'Category Person', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        $this->form_validation->set_rules('cunit', 'Unit', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required');
        $this->form_validation->set_rules('tprice', 'Price', 'required|numeric');
        $this->form_validation->set_rules('thpp', 'Unit Cost', 'required|numeric');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

                $product = array('name' => $this->input->post('tname'), 'category' => $this->input->post('ccategory'), 
                                 'price' => $this->input->post('tprice'), 'hpp' => $this->input->post('thpp'), 'desc' => $this->input->post('tdesc'),
                                 'unit' => $this->input->post('cunit'), 'qty' => $this->input->post('tqty'), 'currency' => $this->input->post('ccurrency')
                           );
            
	    $this->Gproduct_model->update($this->session->userdata('curid'), $product);
            echo 'true';
        }
        else
        { echo validation_errors(); }
    }

    //    ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['category'] = $this->category->combo_all();

        $this->load->view('product_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $category = $this->input->post('ccategory');

        $data['category'] = $this->category->get_name($category);
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Gproduct_model->report($category,$brand)->result();
        $total = $this->Gproduct_model->total($category,$brand);
        $data['total'] = $total['total'];

        $this->load->view('product_report', $data);

    }

//    ================================ REPORT =====================================

}

?>