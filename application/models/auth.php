<?php
/** 
*	Author		: Hifshan Riesvicky
* 	From		: Gunadarma University
*	Filename	: auth.php
*/
class Auth extends CI_Model{
	/**
	* Function checkLogin (Model)
	* This function checks user login state 
	* and match it into database
	* and also return it with array
	* @return array
	*/
	
	public function getProvSess($val)
	{
		if($val!='')
		{
			$sql="select tabel_propinsi.no_kode_propinsi 
			from tabel_propinsi
			where no_kode_propinsi=$val
			limit 1";
			$data=$this->db->query($sql)->row_array();
			if( $data['no_kode_propinsi']!=''){
				return $data['no_kode_propinsi'];
			}
			else{
				
				$sql="select tabel_propinsi.no_kode_propinsi 
				from tabel_propinsi
				inner join tabel_kabupaten_kota on tabel_kabupaten_kota.no_kode_propinsi=tabel_propinsi.no_kode_propinsi
				where(
				tabel_kabupaten_kota.id_urut_kabupaten=$val
				or tabel_kabupaten_kota.no_kabupaten=$val
				)
				limit 1";
				$data=$this->db->query($sql)->row_array();
				return $data['no_kode_propinsi']!=null?$data['no_kode_propinsi']:'';
			}
		}
		else{
			return $val;
		}
	}
	
	public function checkLogIn(){
		// Retrieve uname and password from login form 
		$username		= $this->input->post('uname', TRUE);
		$password		= md5($this->input->post('password', TRUE));	
		
		// Match both of username and password into database
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		// Get query from tabel_login_user
		$query = $this->db->get('tabel_login_user');
		
		// Return the query as array stdObject
		return $query;
	}
	
	/**
	* Function register (Model)
	* This function shall insert new user list
	* into tabel_login_user as the new one
	* @return integer
	*/
	public function register(){
		// Retrieve uname and password from login form 
		$username		= $this->input->post('uname', TRUE);
		$password		= md5($this->input->post('password', TRUE));	
		$hak_akses		= $this->input->post('hak_akses', TRUE);
		$email		= $this->input->post('email', TRUE);
		$nama_unit		= $this->input->post('nama_unit', TRUE);
		$alamat		= $this->input->post('alamat', TRUE);
		$nomor_telpon		= $this->input->post('nomor_telpon', TRUE);
		if($hak_akses==4 or $hak_akses==3){
			$code = $this->input->post('no_kode_propinsi');
		} else if($hak_akses==5){
			$code = $this->input->post('nama_kabupaten');
		} else {
			$code = "";
		}
		// Set the new one and collect it into array variable
		$data				= array(
			'username'		=> $username,
			'password'		=> $password,
			'code'	=> $code,
			'id_r_hak_akses'	=> $hak_akses,
			'email'	=> $email,
			'nama_unit'	=> $nama_unit,
			'alamat'	=> $alamat,
			'nomor_telpon'	=> $nomor_telpon
		);
		
		// Insert new data or new user into tabel_login_user
		$query = $this->db->insert('tabel_login_user', $data);
		
		// If data successfully inserted, so system will return 1
		if($query)
			return 1;
		// Else if isn't inserted
		return 0;
	}

	
	
	/**
	* Function identifyRightAccess (Model)
	* This function will identify the right access
	* name which is needed by session userdata
	* @param $rightAccess
	* @return string else null
	*/
	public function identifyRightAccess($rightAccess){
		// if hak_akses as same as users right access token
		$this->db->where('id_hak_akses', $rightAccess);
		$query 		= $this->db->get('tabel_login_hak_akses');
		
		// If query num rows equal to 1
		if($query->num_rows()){
			if($query->result()){
				$query = $query->result();
				return $query[0]->hak_akses;
			} // End result
		} // End num rows
		
		return 0;
	}	
	
	/**
	* Function delUser (Model)
	* This function bring the admin 
	* result by deleted some user
	* @param $user
	* @return integer
	*/
	public function delUser($user){
		
		// Find the user record from user id 
		$this->db->where('id_user', $user);
		// Delete the record
		$query	= $this->db->delete('tabel_login_user');	
		// If query statement successful
		if($query)
			return 1;
		
		return 0;
	}
	
	/**
	* Function editUser (Model)
	* This function will post in order to update
	* user record and returns integer
	* as conditional statement
	* @param $user
	* @return integer
	*/
	public function editUser($user){
		// Retrieve uname, hak_akses, and password from login form 
		$username		= $this->input->post('uname', TRUE);
		if ($this->input->post('password') != "") {
			$password		= md5( $this->input->post('password', TRUE));	
		} else {
			$password       = $this->input->post('old_password');
		}

		$hak_akses		= $this->input->post('hak_akses', TRUE);

		if ($this->input->post('no_kode_propinsi') == 0) {
			$code = $this->input->post('code');
		} else {
			if($hak_akses==5){
				$code = $this->input->post('old_kabupaten', TRUE);
			} else if($hak_akses==4 or $hak_akses==3){
				$code = $this->input->post('no_kode_propinsi', TRUE);
			}
		}


		$email			= $this->input->post('email', TRUE);
		$nama_unit		= $this->input->post('nama_unit', TRUE);
		$alamat			= $this->input->post('alamat', TRUE);
		$nomor_telpon	= $this->input->post('nomor_telpon', TRUE);
		$picture        = $this->input->post('picture');

		// Update the new one and collect it into array variable
		if($hak_akses==5){
			$data			= array(
				'username'			=> $username,
				'password'			=> $password,
				'id_r_hak_akses'	=> $hak_akses,
				'code'				=> $code,
				'email'				=> $email,
				'nama_unit'	     	=> $nama_unit,
				'alamat'			=> $alamat,
				'nomor_telpon'		=> $nomor_telpon,
				'picture'		=> $picture
			);

		} else if($hak_akses==4 or $hak_akses==3){
			$data			= array(
				'username'			=> $username,
				'password'			=> $password,
				'id_r_hak_akses'	=> $hak_akses,
				'code'				=> $code,
				'email'				=> $email,
				'nama_unit'	     	=> $nama_unit,
				'alamat'			=> $alamat,
				'nomor_telpon'		=> $nomor_telpon,
				'picture'		=> $picture
			);

		} else {
			$data			= array(
				'username'			=> $username,
				'password'			=> $password,
				'id_r_hak_akses'	=> $hak_akses,
				'code'				=> $code,
				'email'				=> $email,
				'nama_unit'	     	=> $nama_unit,
				'alamat'			=> $alamat,
				'nomor_telpon'		=> $nomor_telpon,
				'picture'		=> $picture
			);
		}
		// Update user data with parameter called $user
		$this->db->where('id_user', $user);
		$query = $this->db->update('tabel_login_user', $data);

		// If query statement accomplished and the data successfully updated into tabel_login_user
		if($query)
			return 1;
		// Else failed to update user's data
		return 0;
	}

	// Fajar 
	public function get_provinsi()
	{
		if ($this->session->userdata('user_segment') == 3 OR $this->session->userdata('user_segment') == 4 ) {
			$this->db->where('no_kode_propinsi', $this->session->userdata('code'));
		}
		$this->db->order_by('nama_propinsi', 'asc');
		return $this->db->get('tabel_propinsi')->result();
	}

	public function get_kota()
	{
		if ($this->session->userdata('user_segment') == 5) {
			$this->db->where('id_urut_kabupaten', $this->session->userdata('code'));
		}
		$this->db->order_by('nm_kabupaten', 'asc');
		$this->db->join('tabel_propinsi', 'tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi');
		return $this->db->get('tabel_kabupaten_kota')->result();
	}

	function view()
	{
		$this->db->select('tabel_propinsi.*, tabel_login_user.*, tabel_login_hak_akses.*');
		$this->db->from('tabel_login_user');
		$this->db->join('tabel_propinsi', 'tabel_login_user.code = tabel_propinsi.no_kode_propinsi', 'left');
		$this->db->join('tabel_login_hak_akses', 'tabel_login_user.id_r_hak_akses = tabel_login_hak_akses.id_hak_akses', 'left');
		return $this->db->get();
	}

	function view_by_id($id)
	{
		$this->db->select('tabel_propinsi.*, tabel_login_user.*, tabel_login_hak_akses.*');
		$this->db->from('tabel_login_user');
		$this->db->join('tabel_propinsi', 'tabel_login_user.code = tabel_propinsi.no_kode_propinsi', 'left');
		$this->db->join('tabel_login_hak_akses', 'tabel_login_user.id_r_hak_akses = tabel_login_hak_akses.id_hak_akses', 'left');
		$this->db->where('tabel_login_user.id_user', $id);
		return $this->db->get();
	}
}

?>