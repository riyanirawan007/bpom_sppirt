<?php

class Menu extends APP_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('menu_model');	
		$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		return view_dashboard('menu/main');
	}


	public function add()
	{
		if(!isset($_POST['title']))
		{
			$menu_places=array('Administrator Page','Landing Page');
			$menu_levels=array('Level 1','Level 2','Level 3');
			$menu_link_types=array('External','Internal');

			$data['places']=$menu_places;
			$data['levels']=$menu_levels;
			$data['link_types']=$menu_link_types;	
			$data['id_menu_for_edit']=0;
			return view_dashboard('menu/form',$data);
		}
		else
		{
			if($this->menu_model->insert())
			{
				echo '<script>
				alert("Data Berhasil Diinput!");
				document.location.href="'.base_url().'menu";
				</script>';			
			}
			else
			{
				echo '<script>alert("Data Gagal Diinput!");</script>';
			}

		}	
	}

	public function edit()
	{
		if(!isset($_POST['title']))
		{
			$id=isset($_POST['id_menu'])?$_POST['id_menu']:'';
			if($id!='')
			{
				$menu_places=array('Administrator Page','Landing Page');
				$menu_levels=array('Level 1','Level 2','Level 3');
				$menu_link_types=array('External','Internal');

				$data['places']=$menu_places;
				$data['levels']=$menu_levels;
				$data['link_types']=$menu_link_types;
				$data['id_menu_for_edit']=$id;
				return view_dashboard('menu/form',$data);
			}
			else
			{
				echo '<script>
				alert("Data yang ingin dirubah belum ditentukan!");
				document.location.href="'.base_url().'menu";
				</script>';	
			}
		}
		else
		{
			if($this->menu_model->edit())
			{
				echo '<script>
				alert("Data Berhasil Diedit!");
				document.location.href="'.base_url().'menu";
				</script>';			
			}
			else
			{
				echo '<script>alert("Data Gagal Diedit!");</script>';
			}

		}
	}

	public function delete()
	{
		$id_menu=isset($_POST['id_menu']) ? $_POST['id_menu'] : '';
		echo json_encode($this->menu_model->delete($id_menu));	
	}

	public function getParentByLevel()
	{
		$level=isset($_GET['level']) ? urldecode($_GET['level']) : '';
		echo json_encode($this->menu_model->get_menu('',$level));
	}

	public function getMenuData()
	{
		$id_menu=isset($_GET['id_menu']) ? $_GET['id_menu'] : '';
		$level=isset($_GET['level']) ? $_GET['level'] : '';
		echo json_encode($this->menu_model->get_menu($id_menu,$level));
	}

	public function setActiveStat()
	{
		$id_role_menu=isset($_POST['id_role_menu']) ? $_POST['id_role_menu'] : '';
		$current_stat=isset($_POST['current']) ? $_POST['current'] : '';
	}

	public function getSortIndex()
	{
		$parent_id=isset($_GET['parent_id']) ? $_GET['parent_id'] : 0;
		echo json_encode($this->menu_model->get_sort_index($parent_id)); 
	}
}