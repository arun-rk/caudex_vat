<?php
if(!function_exists('remove_comma')) {
  function remove_comma($value) {
    return str_replace(',','',$value);
  }
}
////////////////GETA  FEILD ////////////////////////////////////////////
if(!function_exists('getAfield')) {
  function getAfield($field,$table,$cond)
  {
   $ci =& get_instance();
   $class = $ci->db->query("SELECT $field FROM $table  $cond");
   $rows=$class->num_rows();
   if($rows>0)
   {
   $class = $class->result_array();
   return $class[0][$field];
   }
   else {
     return "";
   }
  }
}
function getMfield($field,$table,$cond='')
{
 $ci =& get_instance();
 $class = $ci->db->query("SELECT $field FROM $table  $cond");
 $x = $ci->db->error();
 $rows=$class->num_rows();
 if($rows>0)
 {
 $class = $class->result_array();
 return $class;
 }
 else {
	 return "";
 }
}
function create_select($table,$field_id,$field,$cond='')
{
 $ci =& get_instance();
 $class = $ci->db->query("SELECT $field_id,$field FROM $table  $cond");
 $x = $ci->db->error();
 $rows=$class->num_rows();
 if($rows>0)
 {
	$class = $class->result_array();
	$cat= getMfield($field_id.','.$field,$table);
	$items= array(''=>'Choose Unit of measure');
	foreach ($cat as  $value) {
		$items[$value[$field_id]] = $value[$field];
	}
 form_dropdown('uom_id', $items, set_value('uom_id'), 'class="form-control select2 tip" id="uom_id"  required="required" style="width:100%;"'); 
 }
 else {
	 return "";
 }
}

function insertInDb($table,$data)
{
  $ci =& get_instance();
  if($ci->db->insert($table, $data))
  {
    $insert_id = $ci->db->insert_id();
    return $insert_id;
  }
}
function simpleUpdate($table,$data,$coloumn="id",$val)
{
    $ci =& get_instance();
    $ci->db->where($coloumn, $val);
    if($ci->db->update($table, $data))
    {
      return 1;
    }
    else {
      return 2;
    }
}



function checkexist($feild,$table,$cond)
{
  $ci =& get_instance();
  $class = $ci->db->query("SELECT $feild FROM $table $cond");
  $rows=$class->num_rows();
  if($rows>0)
  {
  $class = $class->result_array();
  return 1;
  }
  else {
    return 0;
  }
}
function echomsg($mes)
{
	if($mes) print("<script type=\"text/JavaScript\">alert(\"$mes\"); </script>");
}
function getDateIn_YMD($dt)
{
	$year=substr($dt,6,4);
	$month=substr($dt,3,2);
	$day=substr($dt,0,2);
	$date=$year."-".$month."-".$day;
	return($date);

}
function LoadCombo($TName,$FiledCodeName,$FieldDescName,$SelectCode,$Cond,$ordby)
{
 $ci =& get_instance();
 $sql="select ". $FiledCodeName. "," .$FieldDescName ." from ". $TName . " ". $Cond." ".$ordby;
 $query = $ci->db->query($sql);

   if ($query->num_rows() > 0)
  {
     foreach ($query->result() as $row)
     {
        $id= $row->$FiledCodeName;
        $value= $row->$FieldDescName;

        if($id==$SelectCode)
        {
          	print("<option value=$id selected=selected>$value</option>");
        }
        else {
          	print("<option value=$id >$value</option>");
        }
     }
  }

}
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
function cryptography($string,$key="CImsdYkmPHnsfruypojkeER")
{

$Cipherkey="CImsdYkmPHnsfruypojkeER";
if($key==$Cipherkey)
{
    if($string!="")
    {
    	$output = false;
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'netroxe secret key';
	    $secret_iv = 'secret netroxe iv';
	    $key = hash('sha256', $secret_key);
      $iv = substr(hash('sha256', $secret_iv), 0, 16);
	    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    return $output;
    }
    else
    {
          $output="Invalid paramertes";
          return $output;
    }
}
else
{   
    $output="Invalid key detected";
    return $output;
}

}



/////////////////////////////////////////
 ?>
