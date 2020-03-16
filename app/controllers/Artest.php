<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Artest extends MY_Controller
{
 
	public function index(Type $var = null)
	{
		$this->load->helper('ignited');
		echo ignited_callback('text','1','yo',11312);
	}
	function text($x,$s1,$s3)
		{
			
			return $s1.$x.$s3;
		}

}
