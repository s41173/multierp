<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xmlrpc_client extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	function index()
	{
		$this->load->helper('url');
		$server_url = site_url('xmlrpc_server');
                echo $server_url . "<br />";
               
		$this->load->library('xmlrpc');

		$this->xmlrpc->server($server_url, 80);

		$this->xmlrpc->method('Greetings');

		$request = array('How is it going?');
		$this->xmlrpc->request($request);

		if ( ! $this->xmlrpc->send_request())
		{
		    echo $this->xmlrpc->display_error();
                    echo $this->xmlrpc->set_debug(TRUE);
		}
		else
		{
                    echo '<pre>';
                    print_r($this->xmlrpc->display_response());
                    echo '</pre>';
		}
	}
}
?>