<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autocomplete extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

        public function index()
        {
            $data = null;
            $this->load->view('autocomplete-jquery', $data);
        }
        
	public function search()
	{
		// tangkap variabel keyword dari URL
//		$keyword = $this->uri->segment(3);
//
//		// cari di database
//		$data = $this->db->from('product')->like('name',$keyword)->get();
//
//		// format keluaran di dalam array
//		foreach($data->result() as $row)
//		{
//			$arr['query'] = $keyword;
//			$arr['suggestions'][] = array(
//				'value'	=>$row->name,
//				'data'	=>$row->id
//			);
//		}

            $arr['suggestions'][] = array(
            'value'  => 'dodol',
            'data'   => 'garut');

		// minimal PHP 5.2
		echo json_encode($arr);
	}
}