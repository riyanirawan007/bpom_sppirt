<?php

class Home extends APP_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
		//load library dan helper yang dibutuhkan
		//$this->load->library('lib_admin');
		//load library dan helper yang dibutuhkan
		
		/* $this->load->helper('url'); */
		$this->load->helper('captcha');
		$this->load->model('irtp_model','',TRUE);
		$this->load->model('menu_model');
		return $this->load->model('auth');	
		$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}
	
	//all display
	public function index()
	{
		
		//$this->load->view('home');
		// If auth token value hasn't registered
		$path = './captcha_images/';
		if ( !file_exists($path) )
		{
			$create = mkdir($path, 0777);
			if ( !$create)
				return;
		}

		// Captcha configuration
		$word = array_merge(range('1', '9'), range('A', 'Z'));
		$acak = shuffle($word);
		$str  = substr(implode($word), 0, 5);
		$data_ses = array('captcha_str' => $str);
		$this->session->set_userdata($data_ses);
		$config = array(
			'word'  => $str, 
			'img_path'  => $path,
			'img_url'   => base_url().'captcha_images/', 
			'img_width' => '160',
			'font_size' => 20,
			'font_path'  => './assets/fonts/Verdana.ttf',
			'img_height' => 50, 
			'expiration' => 7200 
		);
		$captcha = create_captcha($config);
		
		// Unset previous captcha and store new captcha word
		// $this->session->unset_userdata('captchaCode');
		// $this->session->set_userdata('captchaCode',$captcha['word']);
		
		// Send captcha image to view
		$data['captchaImg'] = $captcha['image'];
		
		if(!$this->session->userdata('user_token'))
			return $this->load->view('home', $data);
			// Else redirect to home function
		redirect('home/checkUserAuthorization');
	}

	public function refresh(){
		$path = './captcha_images/';
		if ( !file_exists($path) )
		{
			$create = mkdir($path, 0777);
			if ( !$create)
				return;
		}

		// Captcha configuration
		$word = array_merge(range('1', '9'), range('A', 'Z'));
		$acak = shuffle($word);
		$str  = substr(implode($word), 0, 5);
		$data_ses = array('captcha_str' => $str);
		$this->session->set_userdata($data_ses);
		$config = array(
			'word'  => $str, 
			'img_path'  => $path,
			'img_url'   => base_url().'captcha_images/', 
			'img_width' => '160',
			'font_size' => 20,
			'font_path'  => './assets/fonts/Verdana.ttf',
			'img_height' => 50, 
			'expiration' => 7200 
		);
		$captcha = create_captcha($config);
		
		// Unset previous captcha and store new captcha word
		// $this->session->unset_userdata('captchaCode');
		// $this->session->set_userdata('captchaCode',$captcha['word']);
		
		// Display captcha image
		echo $captcha['image'];
	}

	public function main()
	{
		return view_dashboard('menu/main');
	}
	
	public function checkUserAuthorization(){			
		// Users could not back into unauthorize group file
		if($this->preventCache()){
			// If user's exist inside user token session
			if($this->session->userdata('user_token'))
				echo $this->session->userdata('user_token');
			
				// redirect into home admin
				if($this->session->userdata('user_segment') == 1){ //Admin
					redirect('dashboard');
					
				} else if($this->session->userdata('user_segment') == 2){ //bpom
					redirect('dashboard');
					
				}  else if($this->session->userdata('user_segment') == 3){ //balai
					redirect('dashboard');
				} else if($this->session->userdata('user_segment') == 4){ //dinkes tingkat 1
					redirect('dashboard');
				} else if($this->session->userdata('user_segment') == 5){ //dinkes tingkat 2
					redirect('dashboard');
				} else {
					
					redirect('home');
				}
			}
		// Else redirect into login page
		/**
		* prevent from CSRF
		*/
		redirect('home/index');
	}
	
	public function logOut(){
			// users could not back into authorization group file

		if($this->preventCache()){
				// Unset the list of userdata
			$data	=	array(
				'user_token'		=> '',
				'user_id'			=> '',
				'user_name'			=> '',
				'user_segment'		=> '',
				'user_right_access'	=> '',
				'login'				=> FALSE
			);
				// Accepted to unset the user's token into null
			$this->session->unset_userdata($data);	
			if($this->delCookie()){						
					//echo "ECO";
					// Set user message
				$message = '<div class="alert alert-info"><strong>Selamat! </strong> Anda telah keluar dari sistem.</div>';
					// Set the user message to the interface and store into session
				$this->session->set_flashdata('message', $message);
					// After user flush the data, user will redirect into login page
				redirect('home');
			}
		}

			// If user can't logout in the same time caused by the system overloaded 
		$message = '<div class="alert alert-danger"><strong>Perhatian! </strong> Anda tidak dapat keluar dari sistem, silahkan mencoba lagi.</div>';
			// Set the user message to the interface
		$this->session->set_flashdata('message', $message);
			// Redirect into home function
			//redirect('home/index');			
	}			

		/**
		* Function register (GET)
		* New user will be registered here
		* @return string else null
		*/
		public function register(){
			
			if($this->session->userdata('user_token')){
				
				// Get hak_akses lists from table_hak_akses and insert data into register view
				$data['access'] = $this->db->get('tabel_login_hak_akses')->result();
				// return register view as interface
				$data['js_propinsi'] = $this->db->get('tabel_propinsi')->result();
				$this->lib_admin->display('admin/register', $data);
				//return $this->load->view('admin/register', $data);
			}
			// If user hasn't logged in
			/* 
			* Prevent CSRF attack
			*/
			//return redirect('home/index');
			
		}		
		
		/**
		* Function userList (GET)
		* To provide admin about the user
		* who registered into the system
		* @return view
		*/
		
		/**
		* Function delUser (GET)
		* Delete the user which has user_id as a parameter
		* and give admin feedback system
		* @param $user
		* @return null
		*/
		public function delUser($user){
			// Prevent user's token from CSRF attack
			if($this->session->userdata('user_token')){
				// Delete appropriate user data with parameter user
				$this->auth->delUser($user);	
				// If admin has deleted user successfully
				$message = '<div class="alert alert-info"><strong>Awesome! </strong> You have deleted user from database.</div>';
				$this->session->set_flashdata('message', $message);
				return redirect('home/userList');
			}
			// If user hasn't logged in
			/* 
			* Prevent CSRF attack
			*/			
			return redirect('home/index');	
		}
		
		/**
		* Function editUser (GET)
		* The existing user shall be updated here
		* by user id as the parameter
		* @param $user
		* @return view
		*/
		public function editUser($user){
			// Prevent user's token from CSRF attack
			if($this->session->userdata('user_token')){
				// Get hak_akses lists from table_hak_akses and insert data into register view
				$data['access'] = $this->db->get('tabel_login_hak_akses')->result();
				// Get user data from database where id_user equal to user variable to be edited later
				$data['query'] 	= $this->db->query('SELECT * FROM tabel_login_user, tabel_login_hak_akses WHERE
					tabel_login_user.id_r_hak_akses = tabel_login_hak_akses.id_hak_akses
					AND id_user = '.$user)->result();
				
				// Provide edit user page with user data instead
				$this->lib_admin->display('admin/editUser', $data);
				//$this->load->view('admin/editUser', $data);
			}else{
					// If user hasn't logged in
					/* 
					* Prevent CSRF attack
					*/			
					return redirect('home/index');	
				}

			}

			public function peraturan()
			{
		/*if(isset($_POST['filter'])):
			$provinsi = $this->input->post('no_kode_propinsi');
			$tgl_awal = substr($this->input->post('tanggal_awal'), 0, 10);
			$tgl_akhir = substr($this->input->post('tanggal_akhir'), 0, 10);
			
			if(empty($tgl_awal)){
				
			}
		endif;*/
		
		
		//load data permohonan
		//$datas = $this->db->get('tabel_pen_pengajuan_spp')->result();
		$datas = $this->irtp_model->peraturan_view()->result();
		
		//generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		
		//head
		$tmpl = array ( 'table_open'  => '<table class="table table-bordered">' );
		$this->table->set_template($tmpl);
		
		$this->table->set_heading(
			'No.',
			'Nama Dokumen',
			//'Nama Jenis Pangan',
			//'Nama Dagang',
			//'Jenis Kemasan',
			//'Berat Bersih',
			//'Komposisi',
			//'Proses Produksi',
			//'Masa Simpan',
			//'Kode Produksi',
			//'Alamat IRTP',
			//'Kode Pos',
			//'No. Telepon',
			//'Nama Pemilik',
			//'Nama Penanggung Jawab',
			#'Scan Alur Produksi',
			#'Scan SIUP',
			#'Scan Rancangan Label',
			//'Tanggal Pengajuan',
			'Aksi'
		);
		
		//isi
		$i = 0;
		foreach($datas as $field){
			$this->table->add_row(++$i,
			//$field->nomor_permohonan,
				$field->nama_peraturan,
			//$field->nama_dagang,
			//$field->jenis_kemasan,
			//$field->berat_bersih,
			//$field->komposisi_utama,
			//$field->tek_olah,
			//$field->masa_simpan,
			//$field->info_kode_produksi,
			//$field->alamat_irtp,
			//$field->kode_pos_irtp,
			//$field->nomor_telefon_irtp,
			//$field->nama_pemilik,
			//$field->nama_penanggung_jawab,
			//date('d-m-Y', strtotime($field->tanggal_pengajuan)),
				anchor(''.$field->file_peraturan, 'Download', array('class' => 'btn btn-info col-md-12'))
			#anchor('pb2kp/permohonan_output_detail/'.$field->nomor_permohonan,'Lihat Detail')
			);
		}
		
		$data['table'] = $this->table->generate();
		
		
		
		$this->load->view('peraturan',$data);
	}
	
	public function lupa_password(){
		if (isset($_POST['submit'])) {
			if ($this->input->post('captcha') != $this->session->userdata('captcha_str'))
			{
				echo '
				<script>
				alert("Captcha salah silahkan ulangi");
				window.location = "'.base_url().'home/lupa_password";
				</script>
				';   
			}
			else 
			{
				$email = $this->input->post('email');
				$this->db->where('email', $email);
				$x = $this->db->get('tabel_login_user')->row();
				if (count($x) > 0) {
					$subject = 'Atur ulang Password';
					$message = '<p>Kami menerima pemberitahuan untuk mengirimkan link atur ulang password untuk akun anda, abaikan email ini apabila anda tidak merasa meminta untuk mengatur ulang password. Klik link dibawah ini untuk mengatur ulang password <br>
					</p>';
					$body = '
					<body>
					' . $message . '
					<a href="'.base_url().'home/new_password/'.$x->id_user.'">Klik disini</a>
					</body>';

					$z = $this->db->get('tabel_konfig_email')->row();
					$result = $this->email
					->from($z->user)
					->to($email)
					->subject($subject)
					->message($body)
					->send();
				// 	echo '
				// 	<script>
				// 	alert("Email atur ulang password berhasil dikirim, cek email anda");
				// 	window.location = "'.base_url().'home/index";
				// 	</script>
				// 	';
				// 	exit;
				} else {
					echo '
					<script>
					alert("Email tidak terdaftar pada sistem");
					window.location = "'.base_url().'home/lupa_password";
					</script>
					'; 
				}
			}
		} else {
			$path = './captcha_images/';
			if ( !file_exists($path) )
			{
				$create = mkdir($path, 0777);
				if ( !$create)
					return;
			}

			$word = array_merge(range('1', '9'), range('A', 'Z'));
			$acak = shuffle($word);
			$str  = substr(implode($word), 0, 5);
			$data_ses = array('captcha_str' => $str);
			$this->session->set_userdata($data_ses);
			$config = array(
				'word'  => $str, 
				'img_path'  => $path,
				'img_url'   => base_url().'captcha_images/', 
				'img_width' => '160',
				'font_size' => 20,
				'font_path'  => './assets/fonts/Verdana.ttf',
				'img_height' => 50, 
				'expiration' => 7200 
			);
			$captcha = create_captcha($config);
			$data['captchaImg'] = $captcha['image'];
			$data['action'] = base_url('home/lupa_password');
			$this->load->view('lupa_password/view',$data);
		}

	}

	public function email_check()
	{
		$this->load->library('form_validation');
		if($this->input->is_ajax_request()) {
			$email = $this->input->post('email');
			if(!$this->form_validation->is_unique($email, 'tabel_login_user.email')) {
				$this->output->set_content_type('application/json')->set_output(json_encode(array('message' => 'Email terdaftar')));
			} else {
				$this->output->set_content_type('application/json')->set_output(json_encode(array('message' => 'Email tidak terdaftar')));

			}
		}
	}

	function new_password() {
		$this->load->library('form_validation');
		if (isset($_POST['submit'])) {
			$id_user = $this->input->post('id_user');

			if ($this->input->post('captcha') != $this->session->userdata('captcha_str'))
			{
				echo '
				<script>
				alert("Captcha salah silahkan ulangi");
				window.location = "'.base_url().'home/new_password/'.$id_user.'";
				</script>
				';   
			}
			else 
			{
				$this->form_validation->set_rules('password', 'password', 'required|min_length[6]');
				if ($this->form_validation->run() == FALSE)	
				{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('home/new_password/'.$id_user);
				} else {
					$password = md5($this->input->post('password'));
					$data = array('password' => $password);
					$this->db->where('id_user', $id_user);
					$update = $this->db->update('tabel_login_user', $data);
					if ($update) {
						echo '
						<script>
						alert("Password berhasil diperbarui");
						window.location = "'.base_url().'home/index";
						</script>
						'; 
					}
				}
				
			}
			
		} else {
			$id_user = $this->uri->segment(3);
			$this->db->where('id_user', $id_user);
			$path = './captcha_images/';
			if ( !file_exists($path) )
			{
				$create = mkdir($path, 0777);
				if ( !$create)
					return;
			}

			$word = array_merge(range('1', '9'), range('A', 'Z'));
			$acak = shuffle($word);
			$str  = substr(implode($word), 0, 5);
			$data_ses = array('captcha_str' => $str);
			$this->session->set_userdata($data_ses);
			$config = array(
				'word'  => $str, 
				'img_path'  => $path,
				'img_url'   => base_url().'captcha_images/', 
				'img_width' => '160',
				'font_size' => 20,
				'font_path'  => './assets/fonts/Verdana.ttf',
				'img_height' => 50, 
				'expiration' => 7200 
			);
			$captcha = create_captcha($config);
			$data['captchaImg'] = $captcha['image'];
			$data['action'] = base_url('home/new_password');
			$data['user'] = $this->db->get('tabel_login_user')->row();
			$this->load->view('lupa_password/new_password', $data);
		}
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */