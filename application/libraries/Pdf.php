<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Pdf {
    
    public function __construct()
    {
        $this->ci = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
    
    private $ci;
 
    function load($param=NULL)
    {
        include_once APPPATH.'/third_party/mpdf/mpdf.php';
        if ($params == NULL)
        {
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';         
        }
        return new mPDF($param);
    }
    
    function create($html,$filename='none')
    {
        $this->ci->load->helper('download');
        $pdfFilePath = './downloads/report/'.$filename.'.pdf';
//        $data['page_title'] = $filename; // pass data to the view 
        
        
        if (file_exists($pdfFilePath) == FALSE)
        {
            ini_set('memory_limit','128M');
            $pdf = $this->load();
            $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            $pdf->WriteHTML($html); // write the HTML into the PDF
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can
    //        $pdf->Output();

            $name = $filename.'.pdf';
            $data = file_get_contents(base_url()."downloads/report/".$name); // Read the file's contents
            force_download($name, $data); 
        }
        else {unlink("$pdfFilePath"); $this->create($html, $filename);  }
    }
}