<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Product_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->city = new City_lib(); 
        $this->purchase = new Purchase_lib();
        $this->brand = new Brand();
        $this->category = new Category_lib();
        $this->unit = new Unit_lib();
        $this->vendor = new Vendor_lib();
        $this->opname = new Opname();
//
        $this->currency = new Currency_lib();
//
        $this->purchase_item = new Purchase_item();
        $this->stock_out_item = new Stock_out_item();
        $this->warehouse = new Warehouse_lib();
        $this->product = new Products_lib();
    }

    private $properti, $modul, $title, $currency, $warehouse, $product;
    private $purchase, $brand, $category, $unit, $vendor, $purchase_item, $stock_out_item, $opname;

    function index()
    {
        $this->get_last_product();
    }

   public function autocomplete()
   {
//      // tangkap variabel keyword dari URL
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('product')->like('name',$keyword)->get();

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
        $data['link'] = array('link_back' => anchor('warehouse_reference','<span>back</span>', array('class' => 'back')));

        $data['brand']    = $this->brand->combo_all();
        $data['category'] = $this->category->combo_all();
        $data['warehouse'] = $this->warehouse->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $products = $this->Product_model->get_last_product($this->modul['limit'], $offset)->result();
        $num_rows = $this->Product_model->count_all_num_rows();

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
            $this->table->set_heading('#','No', 'Code', 'Brand', 'Category', 'Cur', 'Type', 'Name / Model', 'Qty', 'Unit', 'Price', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($products as $product)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $product->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    form_checkbox($datax), ++$i, 'PRO-0'.$product->id, $this->brand->get_name($product->brand), $this->category->get_name($product->category), $product->currency, ucfirst($product->type), $product->name, $product->qty.' '.$product->unit, number_format($this->get_unit_cost($product->id)), number_format($product->price), number_format($this->get_unit_cost($product->id)*$product->qty),
                    anchor_popup($this->title.'/details/'.$product->id,'<span>print</span>',array('class' => 'details1', 'title' => '')).' '.    
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

        $data['brand']    = $this->brand->combo_all();
        $data['category'] = $this->category->combo_all();

	// ---------------------------------------- //
        $products = $this->Product_model->search($this->input->post('ctype'),$this->input->post('cbrand'), $this->input->post('ccategory'), 
                                                 $this->vendor->get_vendor_id($this->input->post('tvendor')),
                                                 $this->input->post('tsearch'))->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Code', 'Category', 'Cur', 'Type', 'Name / Model', 'Qty', 'Unit Cost', 'Price', 'Total', 'Action');

        $i = 0;
        foreach ($products as $product)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $product->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
               form_checkbox($datax), ++$i, 'PRO-0'.$product->id, $this->brand->get_name($product->brand), $this->category->get_name($product->category), $product->currency, ucfirst($product->type), $product->name, $product->qty.' '.$product->unit, number_format($this->get_unit_cost($product->id)), number_format($product->price), number_format($this->get_unit_cost($product->id)*$product->qty),
               anchor_popup($this->title.'/details/'.$product->id,'<span>print</span>',array('class' => 'details1', 'title' => '')).' '.    
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
            
	$this->load->view('template', $data);
    }

    function get_list($cur=null,$target='tproduct',$type=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_list';
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['brand']    = $this->brand->combo_all();
        $data['category'] = $this->category->combo_all();

        $brand = $this->input->post('cbrand');
        $category = $this->input->post('ccategory');
        $name = $this->input->post('tsearch');

        $products = $this->Product_model->get_list_product($cur,$type,$brand,$category,$name)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Category', 'Brand', 'Type', 'Name / Model', 'Qty', 'Action');

            $i = 0;
            foreach ($products as $product)
            {
               $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$product->name.'\',\''.$target.'\')');

                $this->table->add_row
                (
                    ++$i, 'PRO-0'.$product->id, $this->category->get_name($product->category), $this->brand->get_name($product->brand), ucfirst($product->type), $product->name, $product->qty.' '.$product->unit,
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
           $this->Product_model->delete($uid);
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
               $this->Product_model->delete($cek[$i]);
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
        $name = $this->Product_model->get_product_by_id($id)->row();
        
        $in = $this->purchase_item->cek_relation($id, 'product');
        $out = $this->stock_out_item->cek_relation($id, $this->title);
        if ($in == TRUE && $out == TRUE) { return TRUE; } else { return FALSE; }
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['brand']    = $this->brand->combo();
        $data['category'] = $this->category->combo();
        $data['unit'] = $this->unit->combo();
        $data['currency'] = $this->currency->combo();
        $data['warehouse'] = $this->warehouse->combo();

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
        $data['warehouse'] = $this->warehouse->combo();

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required|callback_valid_product');
        $this->form_validation->set_rules('ctype', 'Product Type', 'required');
        $this->form_validation->set_rules('cbrand', 'Brand', 'required');
        $this->form_validation->set_rules('cwarehouse', 'Warehouse', 'required');
        $this->form_validation->set_rules('ccategory', 'Category Person', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        $this->form_validation->set_rules('cunit', 'Unit', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('tbuying', 'Buying Price', 'required|numeric');
        $this->form_validation->set_rules('tprice', 'Price', 'required|numeric');
        $this->form_validation->set_rules('tucost', 'Unit Cost', 'required|numeric');
        $this->form_validation->set_rules('tvendor', 'Vendor', 'required');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $product = array('type' => $this->input->post('ctype'),'name' => $this->input->post('tname'), 'brand' => $this->input->post('cbrand'),
                             'warehouse_id' => $this->input->post('cwarehouse'), 'category' => $this->input->post('ccategory'),
                             'qty' => $this->input->post('tqty'), 'buying' => $this->input->post('tbuying'), 'price' => $this->input->post('tprice'), 'hpp' => $this->input->post('tucost'),
                             'amount' => intval($this->input->post('tqty')*$this->input->post('tucost')),
                             'desc' => $this->input->post('tdesc'), 'unit' => $this->input->post('cunit'), 'currency' => $this->input->post('ccurrency'),
                             'vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor'))
                           );
            
            $this->Product_model->add($product);
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

    function details($uid)
    {
        $this->acl->otentikasi3($this->title);
        $wt = new Warehouse_transaction();
        $p = new Period();
        $p->get();

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_card';
        
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];

        $product = $this->Product_model->get_product_by_id($uid)->row();

        $data['code'] = 'PRO-0'.$product->id;
        $data['brand'] = $this->brand->get_name($product->brand);
        $data['category'] = $this->category->get_name($product->category);
        $data['name'] = $product->name;
        $data['currency'] = $product->currency;
        $data['qty'] = $product->qty;
        $data['unit'] = $product->unit;
        $data['vendor'] = $this->vendor->get_vendor_shortname($product->vendor);
        
        $data['trans'] = $wt->get_monthly($uid, intval($p->month), intval($p->year))->result();

	$this->session->set_userdata('curid', $product->id);
        $this->load->view('product_card', $data);
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

        $data['brand']    = $this->brand->combo();
        $data['category'] = $this->category->combo();
        $data['unit'] = $this->unit->combo();
        $data['currency'] = $this->currency->combo();
        $data['warehouse'] = $this->warehouse->combo();

        $product = $this->Product_model->get_product_by_id($uid)->row();
        
        $data['default']['brand'] = $product->brand;
        $data['default']['category'] = $product->category;
        $data['default']['name'] = $product->name;
        $data['default']['type'] = $product->type;
        $data['default']['desc'] = $product->desc;
        $data['default']['currency'] = $product->currency;
        $data['default']['qty'] = $product->qty;
        $data['default']['price'] = $product->price;
        $data['default']['ucost'] = $product->hpp;
        $data['default']['buying'] = $product->buying;
        $data['default']['unit'] = $product->unit;
        $data['default']['vendor'] = $this->vendor->get_vendor_shortname($product->vendor);
        $data['default']['warehouse'] = $product->warehouse_id;

	$this->session->set_userdata('curid', $product->id);
        $this->load->view('product_update', $data);
    }

    // IMPORT --------------------------------------------------------
    
     function import()
    {
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'attendance_form';
	$data['form_action'] = site_url($this->title.'/import_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['error']  = '';
        $this->load->view('product_import', $data);
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
            $config['file_name']     = 'product';
            $config['allowed_types'] = 'csv';
            $config['overwrite']     = TRUE;
            $config['max_size']	     = '1000';
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            
            if ( !$this->upload->do_upload("userfile"))
            { 
               $data['error'] = $this->upload->display_errors(); 
               $this->load->view('product_import', $data);
            }
            else
            { 
               // success page 
              $this->import_product($config['file_name'].'.csv');
              $info = $this->upload->data(); 
              $this->session->set_flashdata('message', "One $this->title data successfully imported!");
              redirect($this->title.'/import');
            }                
        }
        else { $this->load->view('product_import', $data); }
        
    }
    
    private function import_product($filename)
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
             if ($this->validation_import($res['BRAND'],$res['CATEGORY'], strtolower($res['TYPE']), $res['NAME'], $res['CURRENCY']) == TRUE)
             {  
                 $product = array('type' => strtolower($res['TYPE']),'name' => $res['NAME'], 
                                  'brand' => $this->brand->get_id($res['BRAND']), 'category' => $this->category->get_id($res['CATEGORY']),
                                  'buying' => intval($res['BUYING']), 'price' => intval($res['PRICE']), 
                                  'hpp' => intval($res['BUYING']), 'unit' => $res['UNIT'], 'currency' => $res['CURRENCY']
                           );
            
                $this->Product_model->add($product); 
             } 
           } 
        }
    }
    
    private function valid_coloumn($res)
    {
        if(isset($res['BRAND']) && isset($res['CATEGORY']) && isset($res['CURRENCY']) && isset($res['TYPE']) && 
           isset($res['NAME']) && isset($res['UNIT']) && isset($res['BUYING']) && isset($res['PRICE']))
        { return TRUE; }else { return FALSE; }
    }
    
    private function validation_import($brand=null,$category=null,$type=null,$name=null,$cur='IDR')
    {
        $res[0] = FALSE;
        $res[1] = FALSE;
        $res[2] = FALSE;
        $res[3] = FALSE;
        $res[4] = FALSE;
        
        if ($this->brand->get_id($brand)){ $res[0] = TRUE;}
        if ($this->category->get_id($category) == TRUE){ $res[1] = TRUE; }
        if ($this->valid_product($name) == TRUE){ $res[2] = TRUE; }
        switch ($type){ case 'tool': $res[3] = TRUE; break; case 'material': $res[3] = TRUE; break; default: $res[3] = FALSE; }
        if ($this->currency->cek($cur) == TRUE){ $res[4] = TRUE; }
        
        if ($res[0] == TRUE && $res[1] == TRUE && $res[2] == TRUE && $res[3] == TRUE && $res[4] == TRUE){ return TRUE; }else { return FALSE; }
    }
    
     // IMPORT --------------------------------------------------------
    
    public function valid_product($name)
    {
        if ($this->Product_model->valid_product($name) == FALSE)
        {
            $this->form_validation->set_message('valid_product', "This $this->title is already registered.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    public function valid_type($type)
    {
        if ($type == 'tool')
        {
            $this->form_validation->set_message('valid_type', "This $this->title type can't saved.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    

    function validation_product($name)
    {
	$id = $this->session->userdata('curid');
	if ($this->Product_model->validating_product($name,$id) == FALSE)
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
        $data['warehouse'] = $this->warehouse->combo();

	// Form validation
        $this->form_validation->set_rules('tname', 'Name', 'required');
        $this->form_validation->set_rules('cbrand', 'Brand', 'required');
        $this->form_validation->set_rules('cwarehouse', 'Warehouse', 'required');
        $this->form_validation->set_rules('ccategory', 'Category Person', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        $this->form_validation->set_rules('cunit', 'Unit', 'required');
        $this->form_validation->set_rules('tvendor', 'Vendor', '');
        $this->form_validation->set_rules('tqty', 'Qty', 'required');
        $this->form_validation->set_rules('tprice', 'Price', 'required|numeric');
        $this->form_validation->set_rules('tucost', 'Unit Cost', 'required|numeric');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $product = array('name' => $this->input->post('tname'), 'brand' => $this->input->post('cbrand'), 
                             'warehouse_id' => $this->input->post('cwarehouse'),
                             'category' => $this->input->post('ccategory'), 'price' => $this->input->post('tprice'),
                             'hpp' => $this->input->post('tucost'), 'desc' => $this->input->post('tdesc'), 
                             'unit' => $this->input->post('cunit'), 'qty' => $this->input->post('tqty'), 
                             'amount' => intval($this->input->post('tqty')*$this->input->post('tucost')),
                             'currency' => $this->input->post('ccurrency'),
                             'vendor' => $this->vendor->get_vendor_id($this->input->post('tvendor'))
                       );

	    $this->Product_model->update($this->session->userdata('curid'), $product);
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

        $data['brand'] = $this->brand->combo_all();
        $data['category'] = $this->category->combo_all();
        $data['currency'] = $this->currency->combo();

        $this->load->view('product_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        // property
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $category = $this->input->post('ccategory');
        $brand    = $this->input->post('cbrand');
        $ptype     = $this->input->post('cptype');
        $type     = $this->input->post('ctype'); 
        $start    = $this->input->post('tstart'); 
        $end      = $this->input->post('tend'); 
        $cur      = $this->input->post('ccur');

        $data['category'] = $this->category->get_name($category);
        $data['brand'] = $this->brand->get_name($brand);
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['start'] = $start;
        $data['end'] = $end;
        $data['currency'] = $cur;
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Product_model->report($ptype,$category,$brand,$cur)->result();
        $total = $this->Product_model->total($ptype,$category,$brand,$cur);
        $data['total'] = $total['total'];

        if ($type == 0){ $this->load->view('product_report', $data); }
        else { $this->load->view('stock_card_report', $data); }
        

    }
    
    private function get_unit_cost($pid)
    { 
        $sum = $this->product->get_sum_stock($pid);
        $qty = intval($this->product->get_qty($pid));
        
        $i=0;
        if ($sum)
        {
          foreach ($sum as $res){  $i = $i + intval($res->amount * $res->qty); }  
        }
        else {$i = 0;}
        
        
        return @($i/$qty);
    }

//    ================================ REPORT =====================================

}

?>