<?php

class Menu_model extends CI_Model{


	// Table Menu
	public function get_menu($id="",$level="")
	{
		$this->db->select('a.*, ( IF( (SELECT tabel_menu.title FROM tabel_menu WHERE tabel_menu.parent_menu_id=a.parent_menu_id
		AND tabel_menu.sort_index < a.sort_index ORDER BY tabel_menu.sort_index DESC LIMIT 1  ) is NULL
     ,"First from its Parent/Root"
     ,CONCAT ("After ", (SELECT tabel_menu.title FROM tabel_menu WHERE tabel_menu.parent_menu_id=a.parent_menu_id
		AND tabel_menu.sort_index < a.sort_index ORDER BY tabel_menu.sort_index DESC LIMIT 1  ) ) ) ) as sort_after,
		
		( IF( (SELECT tabel_menu.title FROM tabel_menu WHERE tabel_menu.parent_menu_id=a.parent_menu_id
		AND tabel_menu.sort_index < a.sort_index ORDER BY tabel_menu.sort_index DESC LIMIT 1  ) is NULL
     ,"-",(SELECT tabel_menu.title FROM tabel_menu WHERE tabel_menu.parent_menu_id=a.parent_menu_id
		AND tabel_menu.sort_index < a.sort_index ORDER BY tabel_menu.sort_index DESC LIMIT 1  ) ) ) as parent_title'
     
		,false
		)
		->from('tabel_menu a');
		if($id!="")
		{
			$this->db->where('a.id_menu',$id);
		}
		if($level!="")
		{
			$this->db->where('a.level',$level);
		}

		return $this->db->get()->result_array();
	}

	public function get_menu_by_criteria($id_role_user="",$level="",$parent="")
	{
		$this->db->select('a.*,b.*,c.*')
		->from('tabel_menu a')
		->join('tabel_role_menu b', 'b.id_menu=a.id_menu')
		->join('tabel_login_hak_akses c','b.id_role_user=c.id_hak_akses' );
		if($id_role_user!="")
		{
			$this->db->where('b.id_role_user',$id_role_user);
		}
		if($level!="")
		{
			$this->db->where('a.level',$level);		
		}
		if($parent!="")
		{
			$this->db->where('a.parent_menu_id',$parent);		
		}
		$this->db->where('b.active_stat',1);
		$this->db->order_by('a.sort_index','ASC');
		return $this->db->get()->result_array();
	}

	public function insert()
	{
		$title=$_POST['title'];
		// $place=$_POST['place'];
		$place='Administrator Page';
		$level=$_POST['level'];
		$parent=isset($_POST['parent_menu_id'])?$_POST['parent_menu_id']:0;
		$link_type=$_POST['link_type'];
		$link=$_POST['link'];
		$icon=$_POST['fa_icon'];
		// $sort_after=$_POST['sort_index'];

		$last_sort_index=$this->db->select('sort_index')
		->from('tabel_menu')
		->where('parent_menu_id',$parent)
		->get()->result_array();

		$sort_index=count($last_sort_index)+1;

		$param=array('title'=>$title
			,'place'=>$place
			,'level'=>$level
			,'parent_menu_id'=>$parent
			,'link_type'=>$link_type
			,'link'=>$link
			,'fa_icon'=>$icon
			,'sort_index'=>$sort_index);

		$this->db->trans_start();
		$this->db->insert('tabel_menu',$param);
		$this->reorder_menu_sort_index($parent);
		$this->db->trans_complete();
		if($this->db->trans_status()===FALSE)
		{
			$this->db->trans_rollback();
			return false;

		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function edit()
	{
		$id=$_POST['id_menu'];
		$title=$_POST['title'];
		// $place=$_POST['place'];
		$place='Administrator Page';
		$level=$_POST['level'];
		$parent=isset($_POST['parent_menu_id'])?$_POST['parent_menu_id']:0;
		$link_type=$_POST['link_type'];
		$link=$_POST['link'];
		$icon=$_POST['fa_icon'];

		$this->db->trans_start();

		//check if level changed
		$data=$this->db->select('*')
		->from('tabel_menu')
		->where('id_menu',$id)
		->get()->row_array();

		$param=array('title'=>$title
			,'place'=>$place
			,'level'=>$level
			,'parent_menu_id'=>$parent
			,'link_type'=>$link_type
			,'link'=>$link
			,'fa_icon'=>$icon);
		$this->db->where('id_menu',$id);
		$this->db->update('tabel_menu',$param);

		if($data['level']!=$level)
		{
			$this->reorder_menu_sort_index($parent,$id,'lastest');
			$this->reorder_menu_sort_index($data['level']);
		}

		$this->db->trans_complete();
		if($this->db->trans_status()===FALSE)
		{
			$this->db->trans_rollback();
			return false;

		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function delete($id_menu)
	{
		if($id_menu!="")
		{
			$this->db->trans_start();
			
			$data=$this->db->select('*')
			->from('tabel_menu')
			->where('id_menu',$id_menu)
			->get()
			->row_array();
			$parent=$data['parent_menu_id'];

			$this->db->where('id_menu',$id_menu)
			->delete('tabel_role_menu');
			$this->db->where('id_menu',$id_menu)
			->delete('tabel_menu');

			$this->reorder_menu_sort_index($parent);
			$this->db->trans_complete();

			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				return(array('result'=>false));
			}
			else
			{
				$this->db->trans_commit();
				return(array('result'=>true));
			}
		}
		else
		{
			return(array('result'=>false));
		}
	}

	function reorder_menu_sort_index($parent_id='',$current_id='',$sort_after='')
	{
		//getting siblings
		$menu_sibling=$this->db->select('a.*')
		->from('tabel_menu a')
		->where('a.parent_menu_id',$parent_id)
		->order_by('a.sort_index ASC')
		->get()->result_array();

		//reordering
		if($current_id!='' && $sort_after!='')
		{
			if($sort_after=="first")
			{
				$sort=1;
				$check_exist=$this->db->select('*')
				->from('tabel_menu')
				->where('id_menu',$current_id)
				->get()
				->num_rows();
				if($check_exist>0)
				{
					$param=array('sort_index'=>$sort);
					$this->db->where('id_menu',$current_id);
					$this->db->update('tabel_menu',$param);
					$sort+=1;
				}

				foreach($menu_sibling as $sibling)
				{
					if($sibling['id_menu']!=$current_id)
					{
						$check_exist=$this->db->select('*')
						->from('tabel_menu')
						->where('id_menu',$sibling['id_menu'])
						->get()
						->num_rows();
						if($check_exist>0)
						{
							$param=array('sort_index'=>$sort);
							$this->db->where('id_menu',$sibling['id_menu']);
							$this->db->update('tabel_menu',$param);
							$sort+=1;	
						}
							
					}				
				}

			}
			else if($sort_after=="lastest")
			{
				$sort=1;
				foreach($menu_sibling as $sibling)
				{
					if($sibling['id_menu']!=$current_id)
					{
						$check_exist=$this->db->select('*')
						->from('tabel_menu')
						->where('id_menu',$sibling['id_menu'])
						->get()
						->num_rows();
						if($check_exist>0)
						{
							$param=array('sort_index'=>$sort);
							$this->db->where('id_menu',$sibling['id_menu']);
							$this->db->update('tabel_menu',$param);
							$sort+=1;	
						}
							
					}				
				}

				$check_exist=$this->db->select('*')
				->from('tabel_menu')
				->where('id_menu',$current_id)
				->get()
				->num_rows();
				if($check_exist>0)
				{
					$param=array('sort_index'=>$sort);
					$this->db->where('id_menu',$current_id);
					$this->db->update('tabel_menu',$param);
				}
			}
			else
			{
				$sort_after_index=0;
				foreach($menu_sibling as $sibling)
				{
					if($sibling['id_menu']==$sort_after)
					{
						$sort=(int) $sibling['sort_index'];
						$sort+=1;
						$check_exist=$this->db->select('*')
						->from('tabel_menu')
						->where('id_menu',$current_id)
						->get()
						->num_rows();
						if($check_exist>0)
						{
							$param=array('sort_index'=>$sort);
							$this->db->where('id_menu',$current_id);
							$this->db->update('tabel_menu',$param);	
						}
					}
					$sort_after_index+=1;				
				}
				
				$i=0;
				foreach($menu_sibling as $sibling)
				{
					if($i>$sort_after_index)
					{
						$sort+=1;
						$check_exist=$this->db->select('*')
						->from('tabel_menu')
						->where('id_menu',$sibling['id_menu'])
						->get()
						->num_rows();
						if($check_exist>0)
						{
							$param=array('sort_index'=>$sort);
							$this->db->where('id_menu',$sibling['id_menu']);
							$this->db->update('tabel_menu',$param);
						}
					}
					$i++;				
				}
			}	
		}
		else
		{
			$sort=1;
			foreach($menu_sibling as $sibling)
			{
				$check_exist=$this->db->select('*')
				->from('tabel_menu')
				->where('id_menu',$sibling['id_menu'])
				->get()
				->num_rows();

				if($check_exist>0)
				{
					$param=array('sort_index'=>$sort);
					$this->db->where('id_menu',$sibling['id_menu']);
					$this->db->update('tabel_menu',$param);
					$sort+=1;				
				}
			}
		}

	}

	function get_sort_index($parent_id)
	{
		return $this->db->select('id_menu,sort_index,title')
		->from('tabel_menu')
		->where('parent_menu_id',$parent_id)
		->order_by('sort_index ASC')
		->get()->result_array();
	}



	// Table Role Menu
	
	public function get_role_menu($id='')
	{
		$this->db->select('a.*,b.*,c.*')
		->from('tabel_role_menu a')
		->join('tabel_menu b', 'b.id_menu=a.id_menu')
		->join('tabel_login_hak_akses c', 'a.id_role_user=c.id_hak_akses');

		if($id!="")
		{
			$this->db->where('a.id_role_menu',$id);
		}

		return $this->db->get()->result_array();
	}

	public function insert_role_menu()
	{
		$id_role_user=$_POST['id_role_user'];
		$id_menu=$_POST['id_menu'];
		$active_stat=$_POST['active_stat'];
		$param=array(
			'id_role_user'=>$id_role_user
			,'id_menu'=>$id_menu
			,'active_stat'=>$active_stat);
		$this->db->trans_start();
		$this->db->insert('tabel_role_menu',$param);
		$this->db->trans_complete();
		if($this->db->trans_status()===FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function edit_role_menu()
	{
		$id_role_menu=$_POST['id_role_menu'];
		$id_role_user=$_POST['id_role_user'];
		$id_menu=$_POST['id_menu'];
		$active_stat=$_POST['active_stat'];
		$param=array(
			'id_role_user'=>$id_role_user
			,'id_menu'=>$id_menu
			,'active_stat'=>$active_stat);
		$this->db->trans_start();
		$this->db->where('id_role_menu',$id_role_menu);
		$this->db->update('tabel_role_menu',$param);
		$this->db->trans_complete();
		if($this->db->trans_status()===FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function delete_role_menu($id)
	{
		$this->db->trans_start();
		$this->db->where('id_role_menu',$id);
		$this->db->delete('tabel_role_menu');
		$this->db->trans_complete();
		if($this->db->trans_status()===TRUE)
		{
			$this->db->trans_commit();
			return array('success'=>true);
		}
		else
		{
			$this->db->trans_rollback();
			return array('success'=>false);
		}
	}

	public function get_roles($id='')
	{
		$this->db->select('*')
		->from('tabel_login_hak_akses a');
		if($id!='')
		{
			$this->where('a.id_hak_akses',$id);
		}
		return $this->db->get()->result_array();
	}

	public function get_unlisted_menu_on_role($role_id)
	{
		return $this->db->select('a.*, ( SELECT COUNT(b.id_menu) 
			FROM tabel_role_menu b 
			WHERE b.id_role_user='.$role_id.' AND b.id_menu=a.id_menu) as is_has_listed' )
		->from('tabel_menu a')
		->get()->result_array();
	}

	public function get_menu_by_context($parent,$level)
	{
		return $this->db->select('a.*')
		->from('tabel_menu a')
		->where('a.parent_menu_id',$parent)
		->where('a.level',$level)
		->get()->result_array();
	}

	public function chage_active($id,$current_stat)
	{
		if($current_stat==1)
		{
			$stat=0;
		}
		else
		{
			$stat=1;
		}

		$this->db->trans_start();
		$param=array('active_stat'=>$stat);
		$this->db->where('id_role_menu',$id);
		$this->db->update('tabel_role_menu',$param);
		$this->db->trans_complete();
		if($this->db->trans_status()===TRUE)
		{
			$this->db->trans_commit();
			return array('success'=>true);
		}	
		else
		{
			$this->db->trans_rollback();
			return array('success'=>false);
		}
	}

}