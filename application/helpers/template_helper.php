<?php

/**
*@category Internal Helpers
*@author Riyan S.I (riyansaputrai007@gmail.com)
*/
if(!function_exists('view_dashboard'))
{
	function view_dashboard($page,$data=null)
	{
		$CI = get_instance();
		$data['_content']=$CI->load->view($page,$data,true);
		return $CI->load->view('index',$data);
	}
}