<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tuitions extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Tuition_model','tm',TRUE);
        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency   = $this->load->library('currency_lib');
        $this->user       = $this->load->library('admin_lib');
        $this->journal    = $this->load->library('journal_lib');
        $this->journalgl  = $this->load->library('journalgl_lib');
        $this->dept       = $this->load->library('dept_lib');
        $this->financial = new Financial_lib();
        
        $this->model = new Tuition();

        $this->load->library('fusioncharts');
        $this->swfCharts  = base_url().'public/flash/Column3D.swf';

    }

    private $properti, $modul, $title, $currency,$model;
    private $user,$journal,$dept, $financial;

    function index()
    {
        $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'tuition_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['form_action_graph'] = site_url($this->title.'/get_last');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['year'] = $this->financial->combo_active();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $saless = $this->model->order_by('dates','desc')->get($this->modul['limit'], $offset);
        $num_rows = $this->model->count();

        $atts = array('width'=> '450','height'=> '300',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
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
            $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Balance', 'Action');

            $i = 0 + $offset;
            foreach ($saless as $sales)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'TJ-00'.$sales->no, $sales->currency, tglin($sales->dates), ucfirst($sales->notes), number_format($sales->total),
                    anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/invoice/'.$sales->no,'<span>print</span>',$atts).' '.
                    anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // ===== chart  =======
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('cyear'));
        

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }


    private function chart($cur='IDR')
    {
        $ps = new Period();
        $ps->get();
        $py = new Payment_status_lib();
        
        if ($this->input->post('ccurrency')){ $cur = $this->input->post('ccurrency'); }else { $cur = 'IDR'; }
        if ($this->input->post('cyear')){ $year = $this->input->post('cyear'); }else { $year = $this->financial->get(); }

        $arpData[0][1] = 'July';
        $arpData[0][2] = $this->tm->total_chart(7, $py->year_name(1, $year), $cur);

        $arpData[1][1] = 'August';
        $arpData[1][2] = $this->tm->total_chart(8, $py->year_name(2, $year), $cur);

        $arpData[2][1] = 'September';
        $arpData[2][2] = $this->tm->total_chart(9, $py->year_name(3, $year), $cur);

        $arpData[3][1] = 'October';
        $arpData[3][2] = $this->tm->total_chart(10, $py->year_name(4, $year), $cur);

        $arpData[4][1] = 'November';
        $arpData[4][2] = $this->tm->total_chart(11, $py->year_name(5, $year), $cur);

        $arpData[5][1] = 'December';
        $arpData[5][2] = $this->tm->total_chart(12, $py->year_name(6, $year), $cur);

        $arpData[6][1] = 'January';
        $arpData[6][2] = $this->tm->total_chart(1, $py->year_name(7, $year), $cur);

        $arpData[7][1] = 'February';
        $arpData[7][2] = $this->tm->total_chart(2, $py->year_name(8, $year), $cur);

        $arpData[8][1] = 'March';
        $arpData[8][2] = $this->tm->total_chart(3, $py->year_name(9, $year), $cur);

        $arpData[9][1] = 'April';
        $arpData[9][2] = $this->tm->total_chart(4, $py->year_name(10, $year), $cur);

        $arpData[10][1] = 'May';
        $arpData[10][2] = $this->tm->total_chart(5, $py->year_name(11, $year), $cur);

        $arpData[11][1] = 'June';
        $arpData[11][2] = $this->tm->total_chart(6, $py->year_name(12, $year), $cur);

        $strXML1        = $this->fusioncharts->setDataXML($arpData,'','') ;
        $graph = $this->fusioncharts->renderChart($this->swfCharts,'',$strXML1,"Tuition", "98%", 400, false, false) ;
        return $graph;
    }
           
    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'tuition_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['year'] = $this->financial->combo_active();

        $atts = array('width'=> '400','height'=> '220',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

        if ($this->input->post('tdate')){ $saless = $this->model->where('dates', $this->input->post('tdate'))->get(); }
        else { $saless = $this->model->get(); }
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Cur', 'Date', 'Notes', 'Balance', 'Action');

        $i = 0;
        foreach ($saless as $sales)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $sales->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'TJ-00'.$sales->no, $sales->currency, tglin($sales->dates), ucfirst($sales->notes), number_format($sales->total),
                anchor($this->title.'/confirmation/'.$sales->id,'<span>update</span>',array('class' => $this->post_status($sales->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/invoice/'.$sales->no,'<span>print</span>',$atts).' '.
                anchor($this->title.'/delete/'.$sales->id.'/'.$sales->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $data['graph'] = $this->chart($this->input->post('ccurrency'),  $this->input->post('cyear'));
        $this->load->view('template', $data);
    }

    private function cek_space($val)
    {  $res = explode("<br />",$val);  if (count($res) == 1) { return $val;  } else { return implode('', $res); } }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($id)
    {
        $this->acl->otentikasi3($this->title);
        $sales = $this->model->where('id', $id)->get();

        if ($sales->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); 
           redirect($this->title);
        }
        else
        {
            $total = $sales->total;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!"); 
              redirect($this->title);
            }
            else
            {
                //  create journal
                $this->calculate_sum($sales->no, 0);
                $this->calculate_sum($sales->no, 1);
                $this->calculate_sum($sales->no, 2);
                
                $this->model->approved = 1;
                $this->model->save();


               $this->session->set_flashdata('message', "$this->title TJ-00$sales->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
                
            }
        }

    }
    
//    ===================== approval ===========================================

    private function calculate_sum($no=1, $type=0)
    {
        $deptlib = new Dept_lib();
        $rt = new Receipt_type_lib();

        foreach ($deptlib->get() as $res)
        {
//           echo $res->dept_id;
           $total = $this->tm->total($no,$res->dept_id,$type);
//           $this->create_journal($no, $rt->get_by_dept($res->dept_id), 
//                  intval($total['school_fee']), intval($total['practical']), intval($total['osis']),
//                  intval($total['computer']), intval($total['cost']), intval($total['aid_foundation']),$type);
        } 
    }
    
    
    private function create_journal($no,$receipt,$school=0,$practical=0,$osiscost=0,$comcost=0,$cost=0,$found=0,$type)
    {
        $total = intval($school+$practical+$comcost+$osiscost+$cost);
        
        $cm = new Control_model();
        $rt = new Receipt_type_lib();
        
        $val = $rt->get($receipt);
        
        $kas             = $cm->get_id(13);
        $spp_dimuka      = $val->p1;
        $spp_berjalan    = $val->p2;
        $spp_piutang     = $val->p3;
        $osis            = $val->p4;
        $osis_piutang    = $val->p5;
        $com             = $val->p6;
        $com_piutang     = $val->p7;
        $praktek         = $val->p8;
        $praktek_piutang = $val->p9;
        $bantuan         = $val->discount;
        
        
        $sppacc =0;  $osisacc=0; $praktekacc=0; $comacc=0;
        if ($type==0){ $sppacc = $spp_piutang; $osisacc = $osis_piutang; $praktekacc = $praktek_piutang; $comacc = $com_piutang; }
        elseif ($type==1) { $sppacc = $spp_berjalan; $osisacc = $osis; $praktekacc = $praktek; $comacc = $com; }
        elseif ($type==2) { $sppacc = $spp_dimuka; $osisacc = $osis; $praktekacc = $praktek; $comacc = $com; }

//        journal
        $jid = $this->journalgl->get_journal_id('TJ',$no);
//
        if ($total-$found > 0){$this->journalgl->add_trans($jid,$kas, $total-$found, 0);} 
        if ($school+$cost > 0){ $this->journalgl->add_trans($jid,$sppacc, 0, $school+$cost); }
        if ($practical > 0){ $this->journalgl->add_trans($jid,$praktekacc,0, $practical); }
        if ($osiscost > 0){ $this->journalgl->add_trans($jid,$osisacc,0, $osiscost); }
        if ($comcost > 0){ $this->journalgl->add_trans($jid,$comacc,0, $comcost); }
        if ($found > 0){ $this->journalgl->add_trans($jid,$bantuan,$found, 0); }
       
    }


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $sales = $this->model->where('id', $uid)->get();

        if ($sales->approved == 1){ $this->void($uid,$po); }
        elseif ( $this->valid_period($sales->dates) == TRUE )
        {
            $this->journalgl->remove_journal('TJ', $po); // journal gl
            $this->update_payment_status($po);
            $this->update_scholarship_status($po);
            
            $tt = new Tuitiontrans();
            $tt->where('tuition', $po)->get();
            $tt->delete_all();
            $this->model->delete();

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "1 $this->title can't removed, invalid period..!"); }

        redirect($this->title);
    }
    
    private function void($uid,$po)
    {
       $val = $this->model->where('id',$uid)->get();
       if ($this->valid_period($val->dates) == TRUE)
       {
           $this->journalgl->remove_journal('TJ', $po); // journal gl
           
           $val->approved = 0;
           $val->save();
           $this->session->set_flashdata('message', "1 $this->title successfull voided..!");  
       }
       else { $this->session->set_flashdata('message', "Invalid Period..!");   }
       redirect($this->title);
    }
    
    private function update_payment_status($po)
    {
        $tt = new Tuitiontrans();
        $transs = $tt->where('tuition', $po)->get();
                
        $year = new Financial_lib();
        $ps = new Payment_status_lib();
        
        foreach ($transs as $res){ $ps->remove($res->student,$year->get(),$res->month);  }
    }
    
    private function update_scholarship_status($po)
    {
        $tt = new Tuitiontrans();
        $transs = $tt->where('tuition', $po)->get();
                
        $sc = new Scholarship_trans_lib();
        
        foreach ($transs as $res)
        {  if ($res->scholarship == 1){ $sc->add_period($res->student, $res->financial_year); } }
    }

// ===================================== PRINT ===========================================
    
   function invoice($po=null)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'tombolprint','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        $data['pono'] = $po;
        $this->load->view('tuition_invoice_form', $data);
   }

   function recap($po=0,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];

       // SMP
       $smp0 = $this->tm->total($po,6,0);
       $data['smp_spp_piutang'] = intval($smp0['school_fee']+$smp0['cost']);
       $data['smp_osis_piutang'] = intval($smp0['osis']);
       $data['smp_com_piutang'] = intval($smp0['computer']);
       $data['smp_praktek_piutang'] = intval($smp0['practical']);
       $data['smp_bantuan_piutang'] = intval($smp0['aid_foundation']);
       $data['smp_total_piutang'] = $smp0['school_fee']+$smp0['cost']+$smp0['osis']+$smp0['computer']+$smp0['practical'];

       $smp1 = $this->tm->total($po,6,1);
       $data['smp_spp_berjalan'] = intval($smp1['school_fee']+$smp1['cost']);
       $data['smp_osis_berjalan'] = intval($smp1['osis']);
       $data['smp_com_berjalan'] = intval($smp1['computer']);
       $data['smp_praktek_berjalan'] = intval($smp1['practical']);
       $data['smp_bantuan_berjalan'] = intval($smp1['aid_foundation']);
       $data['smp_total_berjalan'] = $smp1['school_fee']+$smp1['cost']+$smp1['osis']+$smp1['computer']+$smp1['practical'];
       
       $smp2 = $this->tm->total($po,6,2);
       $data['smp_spp_depan'] = intval($smp2['school_fee']+$smp2['cost']);
       $data['smp_osis_depan'] = intval($smp2['osis']);
       $data['smp_com_depan'] = intval($smp2['computer']);
       $data['smp_praktek_depan'] = intval($smp2['practical']);
       $data['smp_bantuan_depan'] = intval($smp2['aid_foundation']);
       $data['smp_total_depan'] = $smp2['school_fee']+$smp2['cost']+$smp2['osis']+$smp2['computer']+$smp2['practical'];
       // SMP
       
       // SMA
       $sma0 = $this->tm->total($po,3,0);
       $data['sma_spp_piutang'] = intval($sma0['school_fee']+$sma0['cost']);
       $data['sma_osis_piutang'] = intval($sma0['osis']);
       $data['sma_com_piutang'] = intval($sma0['computer']);
       $data['sma_praktek_piutang'] = intval($sma0['practical']);
       $data['sma_bantuan_piutang'] = intval($sma0['aid_foundation']);
       $data['sma_total_piutang'] = $sma0['school_fee']+$sma0['cost']+$sma0['osis']+$sma0['computer']+$sma0['practical'];
       
       $sma1 = $this->tm->total($po,3,1);
       $data['sma_spp_berjalan'] = intval($sma1['school_fee']+$sma1['cost']);
       $data['sma_osis_berjalan'] = intval($sma1['osis']);
       $data['sma_com_berjalan'] = intval($sma1['computer']);
       $data['sma_praktek_berjalan'] = intval($sma1['practical']);
       $data['sma_bantuan_berjalan'] = intval($sma1['aid_foundation']);
       $data['sma_total_berjalan'] = $sma1['school_fee']+$sma1['cost']+$sma1['osis']+$sma1['computer']+$sma1['practical'];
       
       $sma2 = $this->tm->total($po,3,2);
       $data['sma_spp_depan'] = intval($sma2['school_fee']+$sma2['cost']);
       $data['sma_osis_depan'] = intval($sma2['osis']);
       $data['sma_com_depan'] = intval($sma2['computer']);
       $data['sma_praktek_depan'] = intval($sma2['practical']);
       $data['sma_bantuan_depan'] = intval($sma2['aid_foundation']);
       $data['sma_total_depan'] = $sma2['school_fee']+$sma2['cost']+$sma2['osis']+$sma2['computer']+$sma2['practical'];
       // SMA
       
       
       // STM
       $stm0 = $this->tm->total($po,4,0);
       $data['stm_spp_piutang'] = intval($stm0['school_fee']+$stm0['cost']);
       $data['stm_osis_piutang'] = intval($stm0['osis']);
       $data['stm_com_piutang'] = intval($stm0['computer']);
       $data['stm_praktek_piutang'] = intval($stm0['practical']);
       $data['stm_bantuan_piutang'] = intval($stm0['aid_foundation']);
       $data['stm_total_piutang'] = $stm0['school_fee']+$stm0['cost']+$stm0['osis']+$stm0['computer']+$stm0['practical'];
       
       $stm1 = $this->tm->total($po,4,1);
       $data['stm_spp_berjalan'] = intval($stm1['school_fee']+$stm1['cost']);
       $data['stm_osis_berjalan'] = intval($stm1['osis']);
       $data['stm_com_berjalan'] = intval($stm1['computer']);
       $data['stm_praktek_berjalan'] = intval($stm1['practical']);
       $data['stm_bantuan_berjalan'] = intval($stm1['aid_foundation']);
       $data['stm_total_berjalan'] = $stm1['school_fee']+$stm1['cost']+$stm1['osis']+$stm1['computer']+$stm1['practical'];
       
       $stm2 = $this->tm->total($po,4,2);
       $data['stm_spp_depan'] = intval($stm2['school_fee']+$stm2['cost']);
       $data['stm_osis_depan'] = intval($stm2['osis']);
       $data['stm_com_depan'] = intval($stm2['computer']);
       $data['stm_praktek_depan'] = intval($stm2['practical']);
       $data['stm_bantuan_depan'] = intval($stm2['aid_foundation']);
       $data['stm_total_depan'] = $stm2['school_fee']+$stm2['cost']+$stm2['osis']+$stm2['computer']+$stm2['practical'];
       // STM
       
       // SMEA
       $smea0 = $this->tm->total($po,5,0);
       $data['smea_spp_piutang'] = intval($smea0['school_fee']+$smea0['cost']);
       $data['smea_osis_piutang'] = intval($smea0['osis']);
       $data['smea_com_piutang'] = intval($smea0['computer']);
       $data['smea_praktek_piutang'] = intval($smea0['practical']);
       $data['smea_bantuan_piutang'] = intval($smea0['aid_foundation']);
       $data['smea_total_piutang'] = $smea0['school_fee']+$smea0['cost']+$smea0['osis']+$smea0['computer']+$smea0['practical'];
       
       $smea1 = $this->tm->total($po,5,1);
       $data['smea_spp_berjalan'] = intval($smea1['school_fee']+$smea1['cost']);
       $data['smea_osis_berjalan'] = intval($smea1['osis']);
       $data['smea_com_berjalan'] = intval($smea1['computer']);
       $data['smea_praktek_berjalan'] = intval($smea1['practical']);
       $data['smea_bantuan_berjalan'] = intval($smea1['aid_foundation']);
       $data['smea_total_berjalan'] = $smea1['school_fee']+$smea1['cost']+$smea1['osis']+$smea1['computer']+$smea1['practical'];
       
       $smea2 = $this->tm->total($po,5,2);
       $data['smea_spp_depan'] = intval($smea2['school_fee']+$smea2['cost']);
       $data['smea_osis_depan'] = intval($smea2['osis']);
       $data['smea_com_depan'] = intval($smea2['computer']);
       $data['smea_praktek_depan'] = intval($smea2['practical']);
       $data['smea_bantuan_depan'] = intval($smea2['aid_foundation']);
       $data['smea_total_depan'] = $smea2['school_fee']+$smea2['cost']+$smea2['osis']+$smea2['computer']+$smea2['practical'];
       // SMEA
       
//     -----------------------------------------------------------------------------------------
       $sales = $this->model->where('no', $po)->get();
       $data['total'] = intval($sales->total);
       
       //keterangan
       $data['pono'] = $po;
       $data['dates'] = tglincomplete($sales->dates);
       $data['user'] = '';
       $data['cur'] = $this->currency->get_code($sales->currency);
       $data['log'] = $this->session->userdata('log');

       // property display
       $data['logo'] = $this->properti['logo'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];
       $data['pname'] = $this->properti['name'];

//       if ($type){ $this->load->view('sales_invoice_blank', $data); } else { $this->load->view('sales_order_invoice', $data); }
       
       $this->load->view('invoice', $data);
       
   }

   private function get_romawi($val)
   {
       switch ($val)
       {
           case 01: $val = 'I'; break;
           case 02: $val = 'II'; break;
           case 03: $val = 'III'; break;
           case 04: $val = 'IV'; break;
           case 05: $val = 'V'; break;
           case 06: $val = 'VI'; break;
           case 07: $val = 'VII'; break;
           case 08: $val = 'VIII'; break;
           case 09: $val = 'IX'; break;
           case 10: $val = 'X'; break;
           case 11: $val = 'XI'; break;
           case 12: $val = 'XII'; break;
       }
       return $val;
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('sales/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        $data['dept']     = $this->dept->combo_all();
        
        $this->load->view('tuition_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');
//        $status = $this->input->post('cstatus');
        $dept = null;

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
//        $data['status'] = $status;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $data['dept'] = $this->dept->get_name($dept);

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['tuitions'] = $this->tm->report_tuition($cur,$start,$end)->result();
//        $total = $this->tm->total_report($cur,$dept,$start,$end);
//        
//        $data['school_total']    = $total['school_fee'];
//        $data['practical_total'] = $total['practical'];
//        $data['osis_total']      = $total['osis'];
//        $data['computer_total']  = $total['computer'];
//        $data['cost_total']      = $total['cost'];
//        $data['aid_total']       = $total['aid_foundation'];
//        $data['bos_total']       = $total['aid_goverment'];
//        $data['balance_total']   = $total['amount'];

//        if ($type==0){ $this->load->view('tuition_report', $data); }
//        elseif ($type==1){ $this->load->view('tuition_report_details', $data); }
//        elseif ($type==2) {  $this->load->view('tuition_recap', $data); }
        
        $this->load->view('tuition_report', $data);
        
    }


    function void_report()
    {
        $this->acl->otentikasi_admin($this->title.'/void_report');

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/void_report_process');
        
        $this->load->view('tuition_void_panel', $data);
    }
    
    function void_report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        
        $void = new Void_lib();

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['result'] = $void->get($start, $end);

        if ($type==0){ $this->load->view('void_report', $data); }
        elseif ($type==1){ $this->load->view('void_pivot', $data); }
        
    }
    
// ====================================== REPORT =========================================

// ======================================= COST ==========================================
    
    
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


}

?>