<?php 

if ( ! function_exists('element'))
{
	/**
	 * Igniter Datatable Callback
	 * 
	 * Its a better Igniter Datatable Callback.
	 * Function needs to be on the same controller that called this helper.
	 * 
	 * Author : Arun rk 
	 * 
	 * Link : https://github.com/ar0krishna/arignited-callback
	 *
	 */
	function ignited_callback() {
		$arg = func_get_args();
		$ci =& get_instance();
		$function = $arg[0];
		unset($arg[0]);
		$res= $ci->$function(...$arg);
		return $res;
	}
}
/**
 * Example controller
 * 
 *     function get_stores() {
 *
 *        $this->load->library('datatables');
 *        $this->load->helper('ignited');
 *        $this->datatables
 *        ->select("id, name, code, phone, email, address1, city")
 *        ->from("stores")
 *        ->add_column("Actions", "<div class='text-center'><a href='" . site_url('settings/edit_store/$1') . "' class='tip' title='".$this->lang->line("edit_store")."'><i class='fa fa-edit'></i></a></div>", "id")
 *				->unset_column('id')
 *				->edit_column('name','$1','ignited_callback(decrypt,name)')
 *				->edit_column('code','$1','ignited_callback(decrypt,code)')
 *				->edit_column('phone','$1','ignited_callback(decrypt,phone)')
 *				->edit_column('email','$1','ignited_callback(decrypt,email)')
 *				->edit_column('address1','$1','ignited_callback(decrypt,address1)')
 *				->edit_column('city','$1','ignited_callback(decrypt,city)');
 *				// ->edit_column('name','$1','ignited_callback(array($this , "testx"), name)');
 *				
 *        // <a href='" . site_url('settings/delete_store/$1') . "' onClick=\"return confirm('". $this->lang->line('alert_x_store') ."')\" class='tip btn btn-danger btn-xs' title='".$this->lang->line("delete_store")."'><i class='fa fa-trash-o'></i></a>
 *        echo $this->datatables->generate();
 *
 *		}
 *		function decrypt($var)
 *		{
 *			return cryptography($var);
 *		}
 * 
 * .//second
 *  	public function index(Type $var = null)
 *	{
 *		$this->load->helper('ignited');
 *		echo ignited_callback('text','1','yo',11312);
 * ist function name rest function parameters
 *	}
 *	function text($x,$s1,$s3)
 *		{
 *			
 *			return $s1.$x.$s3;
 *		}
 *  
 */

?>