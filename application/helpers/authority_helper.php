<?php

/**
*@category Internal Helpers
*@author Riyan S.I (riyansaputrai007@gmail.com)
*/


if(!function_exists('checkMenuAuthority'))
{
	function checkMenuAuthority()
	{
		$CI = get_instance();
		$CI->load->model('Menu_model');
		$role_id=$CI->session->userdata('user_segment');
		$is_menu_authorized=false;
		$menu_check_authority=$CI->menu_model->get_menu_by_criteria($role_id);
		$current_url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		//Prevent for core menus being unauthorized by admin 
		//when deleted their role core menus unexpectly
		$is_core_menus=false;
		//Core menus main controllers link
		$core_menus=array('menu','role_menu');

		
		for($i=0;$i<count($core_menus);$i++)
		{

			$menu_link=str_replace('http://','',base_url().$core_menus[$i]);
			$menu_link=str_replace('https://','',$menu_link);

			if(strpos($current_url,$menu_link)!==false && $role_id==1)
			{
				// echo '<script>alert("Acccess to this module is granted because this is part of sys core menus set for admin, preventing while accidentally unauthorized!");</script>';
				$is_menu_authorized=true;
				$is_core_menus=true;
				break;	
			}
		}

		if(!$is_core_menus)
		{
			foreach($menu_check_authority as $menu)
			{
				$menu_link=str_replace('http://','',base_url().$menu['link']);
				$menu_link=str_replace('https://','',$menu_link);
				if($menu['id_role_user']==$role_id && strpos($current_url,$menu_link)!==false)
				{
					$is_menu_authorized=true;
					break;
				}
				else
				{
					$is_menu_authorized=false;
				}
			}		
		}
		

		if(!$is_menu_authorized)
		{
			echo '<script>
			alert("Anda tidak memiliki hak untuk melihat menu ini!");
			document.location.href="'.base_url().'home";
			</script>';
		}

		return $is_menu_authorized;
	}
}

if(!function_exists('load_menu_by_criteria'))
{
	function load_menu_by_criteria($id_role_user="",$level="",$parent="")
	{
		$CI = get_instance();
		$CI->load->model('menu_model');
		return $CI->menu_model->get_menu_by_criteria($id_role_user,$level,$parent);
	}	
}

if(!function_exists('checkUserAuthorize'))
{
	function checkUserAuthorize()
	{
		$CI= get_instance();
		if($CI->session->userdata('user_token')!=null)
		{
			return true;
		}
		else
		{

			echo '<script>
			alert("Silahkan login terlebih dahulu!");
			document.location.href="'.base_url().'home/checkUserAuthorization";
			</script>';	
			return false;
		}
	}	
}
