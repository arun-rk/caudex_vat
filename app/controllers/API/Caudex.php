<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Caudex extends REST_Controller
{
    function __construct()
    {
      parent::__construct();
     
      //$this->load->model('caudex_model');
    }

    public function Login_get()
		{
			$this->benchmark->mark('code_start');
			try
			{

			}
			catch (Exception $ex)
			{
				$this->response(array('status' => 103,'result'=>'Internel Server Error') ,REST_Controller::HTTP_NOT_FOUND);
			}
		}



}
