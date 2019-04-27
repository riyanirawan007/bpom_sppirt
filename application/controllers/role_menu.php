<?php

class Role_menu extends APP_Controller{

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
		return view_dashboard('role_menu/main');
	}


	public function add()
	{
		if(!isset($_POST['id_role_user']))
		{
			$data['roles_user']=$this->menu_model->get_roles();
			$data['id_role_for_edit']=0;
			return view_dashboard('role_menu/form',$data);
		}
		else
		{
			if($this->menu_model->insert_role_menu())
			{
				echo '<script>
				alert("Data Berhasil Diinput!");
				document.location.href="'.base_url().'role_menu";
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
		if(!isset($_POST['id_role_user']))
		{
			$id=isset($_POST['id_role_menu'])?$_POST['id_role_menu']:'';
			if($id!='')
			{
				$data['roles_user']=$this->menu_model->get_roles();
				$data['id_role_for_edit']=$id;
				return view_dashboard('role_menu/form',$data);
			}
			else
			{
				echo '<script>
				alert("Data yang ingin dirubah belum ditentukan!");
				document.location.href="'.base_url().'role_menu";
				</script>';	
			}
		}
		else
		{
			if($this->menu_model->edit_role_menu())
			{
				echo '<script>
				alert("Data Berhasil Diedit!");
				document.location.href="'.base_url().'role_menu";
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
		$id_role_menu=isset($_POST['id_role_menu']) ? $_POST['id_role_menu'] : '';
		echo json_encode($this->menu_model->delete_role_menu($id_role_menu));	
	}


	public function getRoleMenu()
	{
		$id=isset($_GET['id_role_menu'])? $_GET['id_role_menu']:'';
		$data=$this->menu_model->get_role_menu($id);
		echo json_encode($data);
	}

	public function getRole()
	{
		$data=$this->menu_model->get_roles();
		echo json_encode($data);
	}

	public function getMenuSiblings()
	{
		$parent=isset($_GET['parent']) ? $_GET['parent_id'] : '';
		$level=isset($_GET['level']) ? $_GET['level'] : '';
		$data=$this->menu_model->get_menu_by_context($parent,$level);
		echo json_encode($data);
	}

	public function getUnlistedMenuByRole()
	{
		$id=isset($_GET['role_id'])?$_GET['role_id']:'';
		$data=$this->menu_model->get_unlisted_menu_on_role($id);
		echo json_encode($data);
	}

	public function changeActiveStat()
	{
		$id=isset($_POST['id'])?$_POST['id']:'';
		$current_stat=isset($_POST['stat'])?$_POST['stat']:'';
		echo json_encode($this->menu_model->chage_active($id,$current_stat));
	}
}