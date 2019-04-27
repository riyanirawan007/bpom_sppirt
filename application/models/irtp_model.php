<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class irtp_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }

    public function get_jumlah_irtp_by_kriteria($kriteria,$q_provinsi,$q_kabupaten,$tanggal_awal,$tanggal_akhir)
    {
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			$query = "
				select count(PR.id_urut_periksa_sarana_produksi) as count 
				FROM tabel_pen_pengajuan_spp PJ
						JOIN tabel_daftar_perusahaan PT ON PJ.kode_r_perusahaan = PT.kode_perusahaan
						JOIN tabel_jenis_pangan JP ON PJ.id_urut_jenis_pangan = JP.id_urut_jenis_pangan
						JOIN tabel_kemasan JK ON JK.kode_kemasan = PJ.kode_r_kemasan
						JOIN tabel_periksa_sarana_produksi PR ON PJ.nomor_permohonan = PR.nomor_r_permohonan
						JOIN tabel_periksa_sarana_produksi_detail PD ON PD.id_r_urut_periksa_sarana_produksi = PR.id_urut_periksa_sarana_produksi
						JOIN tabel_kabupaten_kota KK ON PT.id_r_urut_kabupaten = KK.id_urut_kabupaten
						JOIN tabel_propinsi PP ON PP.no_kode_propinsi = KK.no_kode_propinsi
				where PD.level = '{$kriteria}'
				and tanggal_pemeriksaan>='".$tanggal_awal."' and tanggal_pemeriksaan<='".$tanggal_akhir."'
				$q_provinsi $q_kabupaten
			";
		} else {
			$query = "
				select count(PR.id_urut_periksa_sarana_produksi) as count 
				FROM tabel_pen_pengajuan_spp PJ
						JOIN tabel_daftar_perusahaan PT ON PJ.kode_r_perusahaan = PT.kode_perusahaan
						JOIN tabel_jenis_pangan JP ON PJ.id_urut_jenis_pangan = JP.id_urut_jenis_pangan
						JOIN tabel_kemasan JK ON JK.kode_kemasan = PJ.kode_r_kemasan
						JOIN tabel_periksa_sarana_produksi PR ON PJ.nomor_permohonan = PR.nomor_r_permohonan
						JOIN tabel_periksa_sarana_produksi_detail PD ON PD.id_r_urut_periksa_sarana_produksi = PR.id_urut_periksa_sarana_produksi
						JOIN tabel_kabupaten_kota KK ON PT.id_r_urut_kabupaten = KK.id_urut_kabupaten
						JOIN tabel_propinsi PP ON PP.no_kode_propinsi = KK.no_kode_propinsi
				where PD.level = '{$kriteria}'
				$q_provinsi $q_kabupaten
			";
		}
    	$result = $this->db->query($query)->result();

    	return isset($result[0])? $result[0]->count : 0; 
    }

    public function get_jumlah_irtp_by_level($level,$q_provinsi,$q_kabupaten,$tanggal_awal,$tanggal_akhir)
    {
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			$query = "
				select count(distinct(PR.id_urut_periksa_sarana_produksi)) as count 
				from tabel_pen_pengajuan_spp PJ
					JOIN tabel_daftar_perusahaan PT ON PJ.kode_r_perusahaan = PT.kode_perusahaan
					JOIN tabel_jenis_pangan JP ON PJ.id_urut_jenis_pangan = JP.id_urut_jenis_pangan
					JOIN tabel_kemasan JK ON JK.kode_kemasan = PJ.kode_r_kemasan
					JOIN tabel_periksa_sarana_produksi PR ON PJ.nomor_permohonan = PR.nomor_r_permohonan
					JOIN tabel_kabupaten_kota KK ON PT.id_r_urut_kabupaten = KK.id_urut_kabupaten
					JOIN tabel_propinsi PP ON PP.no_kode_propinsi = KK.no_kode_propinsi
				where PR.level_irtp = '{$level}'
				$q_provinsi $q_kabupaten
				and tanggal_pemeriksaan>='".$tanggal_awal."' and tanggal_pemeriksaan<='".$tanggal_akhir."'
				group by PR.level_irtp
			";
		} else {
			$query = "
				select count(distinct(PR.id_urut_periksa_sarana_produksi)) as count 
				from tabel_pen_pengajuan_spp PJ
					JOIN tabel_daftar_perusahaan PT ON PJ.kode_r_perusahaan = PT.kode_perusahaan
					JOIN tabel_jenis_pangan JP ON PJ.id_urut_jenis_pangan = JP.id_urut_jenis_pangan
					JOIN tabel_kemasan JK ON JK.kode_kemasan = PJ.kode_r_kemasan
					JOIN tabel_periksa_sarana_produksi PR ON PJ.nomor_permohonan = PR.nomor_r_permohonan
					JOIN tabel_kabupaten_kota KK ON PT.id_r_urut_kabupaten = KK.id_urut_kabupaten
					JOIN tabel_propinsi PP ON PP.no_kode_propinsi = KK.no_kode_propinsi
				where PR.level_irtp = '{$level}'
				$q_provinsi $q_kabupaten
				group by PR.level_irtp
			";
		}
    	$result = $this->db->query($query)->result();

    	return isset($result[0])? $result[0]->count : 0; 
    }

    // mulai koneksi database : variabel <> name, field <> variabel
	public function add_data_irtp_spp(){
    	#informasi utama
    	$grup_jenis_pangan = $this->input->post('grup_jenis_pangan');
    	if($this->input->post('jenis_pangan')!='-'){
    		$jenis_pangan = $this->input->post('jenis_pangan');
    	} else {
    		$max = $this->db->query('select max(kode_jenis_pangan) as max_pangan from tabel_jenis_pangan where kode_r_grup_jenis_pangan = "'.$grup_jenis_pangan.'" group by kode_r_grup_jenis_pangan')->result_array();
    		
    		if(count($max)>0){
    			if(intval($max[0]['max_pangan']+1)<10){
    				$kode_jenis_pangan = "0".intval($max[0]['max_pangan']+1);
    			} else {
    				$kode_jenis_pangan = $max[0]['max_pangan']+1;
    			}
    		} else {
    			$kode_jenis_pangan = "01";
    		}
    		$post_data = array('kode_jenis_pangan' => $kode_jenis_pangan, 'kode_r_grup_jenis_pangan'=>$grup_jenis_pangan, 'jenis_pangan'=> $this->input->post('jenis_pangan_lain'));
    		$this->db->insert('tabel_jenis_pangan', $post_data);
   			$jenis_pangan = $this->db->insert_id();
    	}
		//print_r($kode_jenis_pangan); exit();
    	
    	$deskripsi_pangan = $this->input->post('deskripsi_pangan');
    	$nama_dagang = $this->input->post('nama_dagang');
    	$jenis_kemasan = $this->input->post('jenis_kemasan');
    	$jenis_kemasan_lain = $this->input->post('jenis_kemasan_lain');
    	$berat_bersih = $this->input->post('berat_bersih');
		$satuan = $this->input->post('satuan_berat_bersih');

    	$komposisi_utama = $this->input->post('komposisi_utama');
    	$komposisi_tambah = $this->input->post('komposisi_tambah');
    	$berat_bersih_tambahan = $this->input->post('berat_bersih_tambahan');

    	$proses_produksi = $this->input->post('proses_produksi');
    	$proses_produksi_lain = $this->input->post('proses_produksi_lain');
    	$masa_simpan = $this->input->post('masa_simpan');
    	$kode_produksi = $this->input->post('kode_produksi');

		#informasi kepemilikan
    	$pemilik_usaha = $this->input->post('pemilik_usaha');
    	//echo $pemilik_usaha;
    	$penanggung_jawab = $this->input->post('penanggung_jawab');

    	#data pengajuan
    	$tanggal_pengajuan = $this->input->post('tanggal_pengajuan');
    	$no_permohonan = $this->input->post('no_permohonan');

    	// tambahan field di tabel_pen_pengajuan_spp 
    	//$lainnya = $this->input->post('lainnya');
    	$berat_bersih_satuan = $this->input->post('berat_bersih_satuan');
    	$waktu = $this->input->post('waktu');

    	// tambahan field di tabel_ambil_komposisi_tambahan
    	$satuan_komposisi = $this->input->post('satuan_komposisi');

    	$query_get_pemilik = $this->db->query('SELECT * FROM tabel_daftar_perusahaan, tabel_kabupaten_kota WHERE tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND tabel_daftar_perusahaan.kode_perusahaan = "'.$pemilik_usaha.'" ');

    	if($query_get_pemilik->num_rows() >= 1){
	    	foreach($query_get_pemilik->result() as $data){
	    		$nama_propinsi = $data->no_kode_propinsi;
	    		$nama_kabupaten = $data->no_kabupaten;
	    		$nama_perusahaan = $data->kode_perusahaan;
	    	}
	    }

    	$query = $this->db->query("SELECT LPAD( IFNULL( SUBSTRING( nomor_permohonan, 12, 4 ) +1, 1 ) , 4,  '0' ) AS id
		FROM tabel_pen_pengajuan_spp
		WHERE SUBSTRING( nomor_permohonan, 4, 2 ) =".$nama_propinsi."
		AND SUBSTRING( nomor_permohonan, 2, 2 ) =".$nama_kabupaten."
		ORDER BY substring( nomor_permohonan, 12, 4 ) DESC
		LIMIT 1");
		
		
		$getNumRow = $query->num_rows();
		$getRow = $query->result();
		if($getNumRow == 0){
			$getRow = '0001';
		}else{
			foreach($getRow as $data){
				$getRow = ($data->id);	
			}
		}
		# kode 1 untuk IRTP
		$no_permohonan = '1'.$nama_kabupaten.$nama_propinsi.date('dmy', strtotime(date('Y-m-d'))).$getRow;

    	$dataSPP = array(
    		'nomor_permohonan' => $no_permohonan,
    		'kode_r_perusahaan' => $nama_perusahaan,
			#'kode_r_perusahaan' => 
    		'kode_r_satuan' => $satuan,
    		#'lainnya' => $lainnya,
    		'waktu' => $waktu,
    		'satuan' => $berat_bersih_satuan,
    		'komposisi_utama' => $komposisi_utama,
    		'berat_bersih' => $berat_bersih,
    		'kode_r_satuan' => $satuan,
    		'kode_r_kemasan' => $jenis_kemasan,
    		'jenis_kemasan_lain' => $jenis_kemasan_lain,
    		'nama_dagang' => $nama_dagang,
    		'deskripsi_pangan' => $deskripsi_pangan,
    		'id_urut_jenis_pangan' => $jenis_pangan,
    		'masa_simpan' => $masa_simpan,
    		'info_kode_produksi' => $kode_produksi,
    		'kode_r_tek_olah' => $proses_produksi,
    		'proses_produksi_lain' => $proses_produksi_lain,
    		'tanggal_pengajuan' => $tanggal_pengajuan
    	);
		
    	$this->db->insert('tabel_pen_pengajuan_spp', $dataSPP);
    	foreach($komposisi_tambah as $key => $data){
    		$arr = array(
    			"id_urut_komposisi" => NULL,
    			"nomor_r_permohonan" => $no_permohonan,
    			"kode_r_komposisi" => $data,
				"berat" => $berat_bersih_tambahan[$key],
				"satuan" => $satuan_komposisi[$key]
    		);
    		$this->db->insert('tabel_ambil_komposisi_tambahan', $arr);
    	}
    	$update_daftar_peusahaan = array('no_nik' => $this->input->post('nik'));
    	$this->db->where('kode_perusahaan', $pemilik_usaha);
      	$this->db->update('tabel_daftar_perusahaan',$update_daftar_peusahaan);

     $this->izin_usaha();
     $this->rancangan_label();
     $this->alur_produksi();
    }

	function izin_usaha()
	{
		// upload data izin usaha
      	$config['upload_path'] = './uploads/siup/';
        $config['allowed_types'] = 'jpg|png|pdf|jpeg';
	    $config['max_size']	= '20000';
        $this->load->library('upload',$config);
        $this->upload->initialize($config);
        $this->upload->do_upload();
        $upload_siup  = $this->upload->data();

        $data = array('kode_r_perusahaan' => $nama_perusahaan,
        			  'path_scan_data'    => $upload_siup['path_scan_data']);
        $this->db->insert('tabel_scan_data_pengajuan_siup',$data);
	}

	function rancangan_label()
	{
		// upload data izin usaha
      	$config['upload_path'] = './uploads/rancangan_label/';
        $config['allowed_types'] = 'jpg|png|pdf|jpeg';
	    $config['max_size']	= '20000';
        $this->load->library('upload',$config);
        $this->upload->initialize($config);
        $this->upload->do_upload();
        $upload_label  = $this->upload->data();

        $data = array('kode_r_perusahaan' => $nama_perusahaan,
        			  'path_scan_data'    => $upload_label['path_scan_data']);
        $this->db->insert('tabel_scan_data_pengajuan_rl',$data);
	}

	function alur_produksi()
	{
		// upload data izin usaha
      	$config['upload_path'] = './uploads/alur_produksi/';
        $config['allowed_types'] = 'jpg|png|pdf|jpeg';
	    $config['max_size']	= '20000';
        $this->load->library('upload',$config);
        $this->upload->initialize($config);
        $this->upload->do_upload();
        $upload_ap  = $this->upload->data();

        $data = array('kode_r_perusahaan' => $nama_perusahaan,
        			  'path_scan_data'    => $upload_ap['path_scan_data']);
        $this->db->insert('tabel_scan_data_pengajuan_ap',$data);
	}

	public function add_data_irtp_permohonan_perusahaan_siup($filename){
		$pemilik_usaha = $this->input->post('pemilik_usaha', TRUE);

		$data = array(
			'kode_r_perusahaan' => $pemilik_usaha,
			'path_scan_data' => $filename,
		);

		$query = $this->db->insert('tabel_scan_data_pengajuan_siup', $data);
		if($query){
			return 1;
		}
		return 0;
	}
	
	public function add_data_irtp_permohonan_perusahaan_rl($filename){
		$perusahaan = $this->input->post('pemilik_usaha', TRUE);
		$this->db->select('nomor_permohonan AS last_id');
		$this->db->where('kode_r_perusahaan', $perusahaan);
		$row = $this->db->get('tabel_pen_pengajuan_spp')->row();
		$pemilik_usaha = $row->last_id;
		

		$data = array(
			'nomor_r_permohonan' => $pemilik_usaha,
			'path_scan_data' => $filename,
		);

		$query = $this->db->insert('tabel_scan_data_pengajuan_rl', $data);
		if($query){
			return 1;
		}
		return 0;
	}
	
	public function add_data_irtp_permohonan_perusahaan_ap($filename){
		$pemilik_usaha = $this->input->post('pemilik_usaha', TRUE);

		$data = array(
			'kode_r_perusahaan' => $pemilik_usaha,
			'path_scan_data' => $filename,
		);

		$query = $this->db->insert('tabel_scan_data_pengajuan_ap', $data);
		if($query){
			return 1;
		}
		return 0;
	}
	
	

	public function add_data_irtp_penyelenggaraan(){
		// $files = array();

        $config['upload_path'] = './uploads/sertifikat_narasumber';
        $config['allowed_types'] = 'jpg|png|pdf|jpeg';
		$config['max_size']  = '20000';
		$config['overwrite'] = TRUE;

        $this->load->library('upload');
        $files = $_FILES;
        $nama_file_sertifikat = array();
	    // for($i=0; $i<= count($_FILES['sertifikat_narasumber_lain']['name']); $i++)
	    foreach ($_FILES['sertifikat_narasumber_lain']['name'] as $i => $value)
	    {       
	        $_FILES['sertifikat_narasumber_lain']['name']= $files['sertifikat_narasumber_lain']['name'][$i];
	        $_FILES['sertifikat_narasumber_lain']['type']= $files['sertifikat_narasumber_lain']['type'][$i];
	        $_FILES['sertifikat_narasumber_lain']['tmp_name']= $files['sertifikat_narasumber_lain']['tmp_name'][$i];
	        $_FILES['sertifikat_narasumber_lain']['error']= $files['sertifikat_narasumber_lain']['error'][$i];
	        $_FILES['sertifikat_narasumber_lain']['size']= $files['sertifikat_narasumber_lain']['size'][$i];
	        $config['file_name'] = time().$files['sertifikat_narasumber_lain']['name'][$i];
	        $this->upload->initialize($config);

	        $up = $this->upload->do_upload('sertifikat_narasumber_lain');
	        if($up){
	        	array_push($nama_file_sertifikat, $config['file_name']);
	        } else {
	        	array_push($nama_file_sertifikat, '');
	        }
	        
	    }
        
    	#informasi penyelenggara
    	$tanggal_pelatihan_awal = $this->input->post('tanggal_pelatihan_awal');
		$tanggal_pelatihan_akhir = $this->input->post('tanggal_pelatihan_akhir');
		$id_narasumber = $this->input->post('nama_narasumber');
    	$nama_narasumber_non_pkp = $this->input->post('nama_narasumber_non_pkp');
    	$materi_tambahan = $this->input->post('materi_tambahan');
    	$temp_jenis = $this->input->post('jenis_narasumber_lain');
    	$nama_narasumber_lain = $this->input->post('nama_narasumber_lain');
    	$nip_narasumber_lain = $this->input->post('nip_narasumber_lain');
		if($materi_tambahan==""){
			$materi_tambahan = "";
		} else {
			$materi_tambahan = implode(",",$materi_tambahan);
		}
    	$materi_penyuluhan_lainnya = $this->input->post('materi_lainnya');
		$id_r_urut_kabupaten = $this->session->userdata('code');
		
		$query = $this->db->query("SELECT LPAD( IFNULL( nomor_permohonan_penyuluhan +1, 1 ) , 12,  '0' ) AS id FROM tabel_penyelenggara_penyuluhan ORDER BY  `nomor_permohonan_penyuluhan` DESC LIMIT 1");
		
		if($query->num_rows() == 0){
			$getId = "000000000001";	
		}else{
			foreach($query->result() as $data){
				$getId = $data->id;
			}
		}
		
		$data = array(
			'nomor_permohonan_penyuluhan' => $getId,
    		'tanggal_pelatihan_awal' => $tanggal_pelatihan_awal,
    		'tanggal_pelatihan_akhir' => $tanggal_pelatihan_akhir,
    		'nama_narasumber_non_pkp' => $nama_narasumber_non_pkp,
    		'materi_pelatihan_lainnya' => $materi_penyuluhan_lainnya,
			'id_r_urut_kabupaten' => $id_r_urut_kabupaten,
			'materi_tambahan' => $materi_tambahan
    	);

		$jenis_narasumber_lain = array();
		foreach ($temp_jenis as $key => $value) {
			array_push($jenis_narasumber_lain, $value);
		}
    	$this->db->insert('tabel_penyelenggara_penyuluhan', $data);
    	
		foreach($id_narasumber as $key=>$nama){
			$piece = explode('.', $nama);
			$id_materi = $piece[0];
			$narasumber = $piece[1];
			
			if($narasumber=='-'){
				// print_r($nama_narasumber_lain[$key]); exit();
				$post_data = array('tot' => 'PKP BALAI', 'nama_narasumber'=>$nama_narasumber_lain[$key], 'nip_pkp_dfi'=>$nip_narasumber_lain[$key], 'dinkes_asal'=> $this->session->userdata('code'), 'sertifikat'=>$jenis_narasumber_lain[$key], 'file_sertifikat'=>$nama_file_sertifikat[$key]);
	    		$this->db->insert('tabel_narasumber', $post_data);
	   			$narasumber = $this->db->insert_id();
			}

			$data = array(
				'nomor_r_permohonan_penyuluhan' => $getId,
				'kode_r_materi_penyuluhan' => $id_materi,
				'kode_r_narasumber' => $narasumber
			);
    		$this->db->insert('tabel_ambil_materi_penyuluhan', $data);
		} 
		// exit;
		return 1;

    }
    
    
    public function add_data_pkp(){
		
    
    	#informasi penyelenggara
    	$tanggal_pelatihan_awal = $this->input->post('tanggal_pelatihan_awal');
		$tanggal_pelatihan_akhir = $this->input->post('tanggal_pelatihan_akhir');
		$id_narasumber = $this->input->post('nama_narasumber');
    	$nama_narasumber_non_pkp = $this->input->post('nama_narasumber_non_pkp');
    	$materi_tambahan = $this->input->post('materi_tambahan');
    	$temp_jenis = $this->input->post('jenis_narasumber_lain');
    	$nama_narasumber_lain = $this->input->post('nama_narasumber_lain');
    	$nip_narasumber_lain = $this->input->post('nip_narasumber_lain');
		if($materi_tambahan==""){
			$materi_tambahan = "";
		} else {
			$materi_tambahan = implode(",",$materi_tambahan);
		}
    	$materi_penyuluhan_lainnya = $this->input->post('materi_lainnya');
		$id_r_urut_kabupaten = $this->session->userdata('code');
		
		$query = $this->db->query("SELECT LPAD( IFNULL( nomor_permohonan_penyuluhan +1, 1 ) , 12,  '0' ) AS id FROM tabel_penyelenggara_penyuluhan ORDER BY  `nomor_permohonan_penyuluhan` DESC LIMIT 1");
		
		if($query->num_rows() == 0){
			$getId = "000000000001";	
		}else{
			foreach($query->result() as $data){
				$getId = $data->id;
			}
		}
		
		$data = array(
			'nomor_permohonan_penyuluhan' => $getId,
    		'tanggal_pelatihan_awal' => $tanggal_pelatihan_awal,
    		'tanggal_pelatihan_akhir' => $tanggal_pelatihan_akhir,
    		'nama_narasumber_non_pkp' => $nama_narasumber_non_pkp,
    		'materi_pelatihan_lainnya' => $materi_penyuluhan_lainnya,
			'id_r_urut_kabupaten' => $id_r_urut_kabupaten,
			'materi_tambahan' => $materi_tambahan
    	);

		$jenis_narasumber_lain = array();
    	$this->db->insert('tabel_penyelenggara_penyuluhan', $data);
    	
		foreach($id_narasumber as $key=>$nama){
			$piece = explode('.', $nama);
			$id_materi = $piece[0];
			$narasumber = $piece[1];
			
			if($narasumber=='-'){
				// print_r($nama_narasumber_lain[$key]); exit();
				$post_data = array('tot' => 'PKP BALAI', 'nama_narasumber'=>$nama_narasumber_lain[$key], 'nip_pkp_dfi'=>$nip_narasumber_lain[$key], 'dinkes_asal'=> $this->session->userdata('code'), 'sertifikat'=>$jenis_narasumber_lain[$key], 'file_sertifikat'=>$nama_file_sertifikat[$key]);
	    		$this->db->insert('tabel_narasumber', $post_data);
	   			$narasumber = $this->db->insert_id();
			}

			$data = array(
				'nomor_r_permohonan_penyuluhan' => $getId,
				'kode_r_materi_penyuluhan' => $id_materi,
				'kode_r_narasumber' => $narasumber
			);
    		$this->db->insert('tabel_ambil_materi_penyuluhan', $data);
		} 
		// exit;

		return 1;
    }
	
	public function add_irtp_peserta(){
		$nomor_penyuluhan = $this->input->post('nomor_permohonan_penyuluhan');
		$nomor_irtp = $this->input->post('nomor_permohonan_irtp');
		$nama_peserta = $this->input->post('nama_peserta');
		$status_peserta = $this->input->post('status_peserta');
		$no_sert_pangan = $this->input->post('no_sert_pangan');
		$pre_test = $this->input->post('nilai_pre_test');
		$post_test = $this->input->post('nilai_post_test');	
		
		if($post_test > 60){
			$status = 1; # Lulus
		}else{
			$status = 0; # Tidak Lulus
		}
		
		$data = array(
			'nomor_r_permohonan_penyuluhan' => $nomor_penyuluhan,
			'nomor_r_permohonan' => $nomor_irtp,
			'nilai_post_test' => $post_test,
			'nilai_pre_test' => $pre_test,
			'no_sert_pangan' => $no_sert_pangan,
			'nama_peserta' => $nama_peserta,
			'status_peserta' => $status_peserta,
			'status_uji' => $status
		);
		
		$set = $this->db->insert('tabel_ambil_penyuluhan', $data);
		if($set){
			return 1;
		}
		return 0;
	}
	
	public function add_data_irtp_penerbitan($filename){
    	$tanggal_pemberian_pirt = $this->input->post('tanggal_pemberian_pirt');
		$nomor_irtp = $this->input->post('nomor_permohonan_irtp');
		$nomor_pirt= $this->input->post('nomor_pirt');
		$no_hk = $this->input->post('nomor_hk');
		#$nm_kabupaten = $this->input->post('nm_kabupaten');
    	#$nama_propinsi = $this->input->post('nama_propinsi');
    	$nama_kepala_dinas = $this->input->post('nama_kepala_dinas');
    	$nip = $this->input->post('nip');
    	$data = array(
    		'tanggal_pemberian_pirt' => $tanggal_pemberian_pirt,
			'nomor_r_permohonan' => $nomor_irtp,
    		'nomor_pirt' => $nomor_pirt,
    		#'id_urut_r_kabutapen' => $nm_kabupaten,
    		#'nama_propinsi' => $nama_propinsi,
    		'nama_kepala_dinas' => $nama_kepala_dinas,
    		'nip' => $nip,
			'label_final' => $filename
    	);

    	$this->db->insert('tabel_penerbitan_sert_pirt', $data);
    }
	
	public function add_data_irtp_perpanjangan($filename){		
    	$nomor_pirt = $this->input->post('nomor_pirt');
		$nomor_pirt_baru = $this->input->post('nomor_pirt_baru');
		$tanggal_pengajuan_perpanjangan = $this->input->post('tanggal_pengajuan_perpanjangan');
		$no_permohonan = $this->input->post('no_permohonan');
		
    	$data = array(
			'nomor_pirt' => $nomor_pirt,
    		'nomor_pirt_baru' => $nomor_pirt_baru,
			'tanggal_pengajuan_perpanjangan' => $tanggal_pengajuan_perpanjangan,
    		'nomor_r_permohonan' => $no_permohonan,
    		'label_final' => $filename
    		// 'update_at' => date('Y-m-d')
    	    );

    	//Insert ke perpanjangan
		$query = $this->db->insert('tabel_perpanjangan_sert_pirt', $data);
		
		$data = array(
			'nomor_pirt' => $nomor_pirt_baru
		);
		
		//update ke penerbitan
		$this->db->where('id_urut_penerbitan_sert', $this->input->post('id_urut_penerbitan_sert'));
    	$query = $this->db->update('tabel_penerbitan_sert_pirt', $data);
		
    	if($query){
    		return 1;
    	}
    	return 0;
    }
	
	public function add_data_irtp_perubahan($filename){
    	$nomor_pirt = explode(" ", $this->input->post('nomor_pirt'));
    	$nomor_pirt = $nomor_pirt[0];
		$tanggal_pengajuan_perubahan = $this->input->post('tanggal_pengajuan_perubahan');
    	$komposisi_tambah = $this->input->post('komposisi_tambah');
		$berat_bersih_tambahan= $this->input->post('berat_bersih_tambahan');
		
		$query = $this->db->query('SELECT * FROM tabel_penerbitan_sert_pirt WHERE id_urut_penerbitan_sert = "'.$nomor_pirt.'" LIMIT 1');

    	if($query->num_rows() == 1){
    		foreach($query->result() as $data){
    			$nomor_permohonan = $data->nomor_r_permohonan;
    		}
    	}
		
		
		$komposisi_utama = $this->input->post('komposisi_utama');
		$alamat = $this->input->post('alamat_irtp');
    	$pos = $this->input->post('pos_irtp');
    	$telepon = $this->input->post('telepon_irtp');
    	$pemilik = $this->input->post('nama_pemilik');
    	$pen_jawab = $this->input->post('penanggung_jawab');
		
		$komposisi_utama_old = $this->input->post('komposisi_utama_old');
		$alamat_old = $this->input->post('alamat_irtp_old');
    	$pos_old = $this->input->post('pos_irtp_old');
    	$telepon_old = $this->input->post('telepon_irtp_old');
    	$pemilik_old = $this->input->post('nama_pemilik_old');
    	$pen_jawab_old = $this->input->post('penanggung_jawab_old');
		
		$detil = array();
		if($komposisi_utama!=$komposisi_utama_old){
			$detil[] = "Komposisi Utama";
		} 
		if($alamat!=$alamat_old){
			$detil[] = "Alamat";
		}
		if($pos!=$pos_old){
			$detil[] = "Kode Pos";
		}
		if($telepon!=$telepon_old){
			$detil[] = "Telepon";
		}
		if($pemilik!=$pemilik_old){
			$detil[] = "Pemilik";
		}
		if($pen_jawab!=$pen_jawab_old){
			$detil[] = "Penanggung Jawab";
		}
    	if($filename!=""){
			$detil[] = "Scan Label";
		}
		//cek perubahan BTP
		$jml_btp_old = $this->db->query("select count(*) as jml_btp_old from tabel_ambil_komposisi_tambahan where
				nomor_r_permohonan = '".$nomor_permohonan."'
				")->result_array();
		if($jml_btp_old[0]['jml_btp_old']!=count($komposisi_tambah)){
			$detil[] = "Komposisi Tambahan";
		} else {
			$jumlah_btp_sama = 0;
			foreach($komposisi_tambah as $key=>$data){
				$cek = $this->db->query("select * from tabel_ambil_komposisi_tambahan where
				nomor_r_permohonan = '".$nomor_permohonan."' and
				kode_r_komposisi = '".$data."' and
				berat = '".$berat_bersih_tambahan[$key]."'
				")->result_array();
				$cekk = count($cek);
				if($cekk>0){
					$jumlah_btp_sama++;
				}
			}
			if($jumlah_btp_sama!=count($komposisi_tambah)){
				$detil[] = "Komposisi Tambahan";
			}
		}
		$detil_perubahan = implode(", ",$detil);
		
		$pemegang_spkp = $this->input->post('pemegang_spkp');
		
    	/* insert into tabel_ambil_komposisi_tambahan */
    	
		
    	$this->db->where('nomor_r_permohonan', $nomor_permohonan);
    	$this->db->delete('tabel_ambil_komposisi_tambahan'); 

    	foreach($komposisi_tambah as $key=>$data){
    		$tambah = array(
	    		'nomor_r_permohonan'	=> $nomor_permohonan,
	    		'kode_r_komposisi'		=> $data,
	    		'berat'					=> $berat_bersih_tambahan[$key]
	    	);
			//print_r($tambah); 
	    	$this->db->insert('tabel_ambil_komposisi_tambahan', $tambah);
    	}
		//exit;	
		
    	/* end */
    	/* Update tabel_penerbitan_sert_pirt */
		
		if($filename!=""){
			$data = array(
				'label_final' => $filename
			);

			$this->db->where('id_urut_penerbitan_sert', $nomor_pirt);
			$query = $this->db->update('tabel_penerbitan_sert_pirt', $data);
			
			//get kode_perusahaan
			$data = array(
				'path_scan_data' => $filename
			);
			$this->db->where('nomor_r_permohonan', $nomor_permohonan);
			$query = $this->db->update('tabel_scan_data_pengajuan_rl', $data);
	
    	}
		/* end */

    	$data = array(
    		'komposisi_utama' => $komposisi_utama, 	
    	);

    	$this->db->where('nomor_permohonan', $nomor_permohonan);
    	$this->db->update('tabel_pen_pengajuan_spp', $data);

    	$query = $this->db->query('UPDATE tabel_pen_pengajuan_spp, tabel_daftar_perusahaan SET tabel_daftar_perusahaan.alamat_irtp = "'.$alamat.'", tabel_daftar_perusahaan.kode_pos_irtp = "'.$pos.'", tabel_daftar_perusahaan.nomor_telefon_irtp = "'.$telepon.'", tabel_daftar_perusahaan.nama_pemilik = "'.$pemilik.'", tabel_daftar_perusahaan.nama_penanggung_jawab = "'.$pen_jawab.'" WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.nomor_permohonan = "'.$nomor_permohonan.'" ');
		
		//Last Input to tabel_perubahan_detail_data
		$data = array(
			'id_r_urut_penerbitan_sert_pirt' => $nomor_pirt,
			'tanggal_pengajuan_perubahan' => $tanggal_pengajuan_perubahan,
			'nama_pemegang_sertifikat' => $pemegang_spkp,
			'detil_perubahan' => $detil_perubahan,
		);
		$this->db->insert('tabel_perubahan_detail_data', $data);
		//end
		
    	if($query){
    		return 1;
    	}
    	return 0;
    }
	
	public function add_data_irtp_pencabutan(){
    	$nomor_pirt = $this->input->post('nomor_pirt');
		$alasan_pencabutan = $this->input->post('alasan_pencabutan');
		$alasan_pencabutan_lain = $this->input->post('alasan_pencabutan_lain');
		$berita_acara = $this->input->post('nomor_berita_acara');
		$tanggal_pencabutan	= str_replace('/', '-', $this->input->post('tanggal_pencabutan'));
		
		$userdata = $this->upload->data();
		$filename = $userdata['file_name'];

    	$data = array(
    		'id_r_urut_penerbitan_sert_pirt' => $nomor_pirt,
    		'kode_alasan_pencabutan' => $alasan_pencabutan,
    		'alasan_pencabutan_lain' => $alasan_pencabutan_lain,
    		'nomor_berita_acara_pencabutan' => $berita_acara,
			'tanggal_pencabutan'	=> $tanggal_pencabutan,
			'path_scan_pencabutan' => $filename,
    		'created_at' => date('Y-m-d H:i:s')
    	);

    	$query = $this->db->insert('tabel_pencabutan_pirt', $data);

    	if($query)
    		return 1;

    	return 0;
    }
	
	public function add_perusahaan(){
		$nama_perusahaan = $this->input->post('nama_perusahaan');
		$nama_pemilik = $this->input->post('nama_pemilik');
		$no_nik = $this->input->post('no_nik');
		$penanggung_jawab = $this->input->post('penanggung_jawab');
		$alamat = $this->input->post('alamat_irtp');	
		$nama_propinsi = $this->input->post('nama_propinsi');
		$nama_kabupaten = $this->input->post('nama_kabupaten');
		$pos = $this->input->post('pos_irtp');
		$telepon = $this->input->post('telepon_irtp');
		
		$get_id_kab = $this->db->query("select * from tabel_kabupaten_kota where id_urut_kabupaten = '".$nama_kabupaten."'")->result();
		foreach($get_id_kab as $data){
			$kabid = ($data->no_kabupaten);	
		}
		
		
		
		$query = $this->db->query("SELECT LPAD( IFNULL( SUBSTRING( kode_perusahaan, 5, 4 ) +1, 1 ) , 4,  '0' ) AS id
FROM tabel_daftar_perusahaan
WHERE SUBSTRING( kode_perusahaan, 1, 2 ) =".$nama_propinsi."
AND SUBSTRING( kode_perusahaan, 3, 2 ) =".$kabid."
ORDER BY kode_perusahaan DESC 
LIMIT 1");
		
		$getNumRow = $query->num_rows();
		$getRow = $query->result();
		if($getNumRow == 0){
			$getRow = '0001';
		}else{
			foreach($getRow as $data){
				$getRow = ($data->id);	
			}
		}
		
		$kode_perus = $nama_propinsi.$kabid.$getRow;
		
		$values = array(
			'kode_perusahaan' => $kode_perus,
			'nama_perusahaan' => $nama_perusahaan,
			'no_nik' => $no_nik,
			'nama_pemilik' => $nama_pemilik,
			'nama_penanggung_jawab' => $penanggung_jawab,
			'alamat_irtp' => $alamat,
			'id_r_urut_kabupaten' => $nama_kabupaten,
			'kode_pos_irtp' => $pos,
			'nomor_telefon_irtp' => $telepon
		);
		
		$insert = $this->db->insert('tabel_daftar_perusahaan', $values);
		if($insert){
			return $kode_perus;
		}
		return 0;
	}
	
	public function getKabupatenID($propinsi, $kabupaten){
		$this->db->where('no_kode_propinsi', $propinsi);
		$this->db->where('no_kabupaten', $kabupaten);
		
		return $this->db->get('tabel_kabupaten_kota')->result();
	}
	
	public function permohonan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("SELECT *,
			tabel_scan_data_pengajuan_siup.path_scan_data as path_siup, 
			tabel_scan_data_pengajuan_rl.path_scan_data as path_rl, 
			tabel_scan_data_pengajuan_ap.path_scan_data as path_ap 
			FROM 
			tabel_pen_pengajuan_spp
			left join tabel_scan_data_pengajuan_rl on tabel_scan_data_pengajuan_rl.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan, 
			tabel_teknologi_pengolahan, 
			tabel_jenis_pangan, 
			tabel_kemasan, 
			tabel_propinsi,
			tabel_kabupaten_kota, 
			tabel_daftar_perusahaan
			left join tabel_scan_data_pengajuan_siup on tabel_scan_data_pengajuan_siup.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
			left join tabel_scan_data_pengajuan_ap on tabel_scan_data_pengajuan_ap.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
			WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah 
			AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan 
			AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan 
			AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
			and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten 
			and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten 
			and tanggal_pengajuan>='$tanggal_awal' 
			and tanggal_pengajuan<='$tanggal_akhir'
			order by tanggal_pengajuan desc
			"
			);
		} else {
			return $this->db->query("SELECT *,
			tabel_scan_data_pengajuan_siup.path_scan_data as path_siup, 
			tabel_scan_data_pengajuan_rl.path_scan_data as path_rl, 
			tabel_scan_data_pengajuan_ap.path_scan_data as path_ap
			FROM
			tabel_pen_pengajuan_spp
			left join tabel_scan_data_pengajuan_rl on tabel_scan_data_pengajuan_rl.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan,
			tabel_teknologi_pengolahan,
			tabel_jenis_pangan,
			tabel_kemasan,
			tabel_propinsi,
			tabel_kabupaten_kota,
			tabel_daftar_perusahaan
			left join tabel_scan_data_pengajuan_siup on tabel_scan_data_pengajuan_siup.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
			left join tabel_scan_data_pengajuan_ap on tabel_scan_data_pengajuan_ap.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
			WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah 
			AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan 
			AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan 
			AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
			and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten 
			and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten
			order by tanggal_pengajuan desc
			"
			);
		}	
		
	}
	
	public function penyelenggaraan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("SELECT * FROM tabel_penyelenggara_penyuluhan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
				and ((tanggal_pelatihan_awal>='".$tanggal_awal."' and tanggal_pelatihan_awal<='".$tanggal_akhir."')
				or (tanggal_pelatihan_awal>='".$tanggal_awal."' and tanggal_pelatihan_akhir<='".$tanggal_akhir."')
				or (tanggal_pelatihan_akhir='".$tanggal_awal."') ORDER BY tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan ASC)
			");
		} else {
			return $this->db->query("SELECT * FROM tabel_penyelenggara_penyuluhan,
			tabel_propinsi,tabel_kabupaten_kota WHERE 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
			tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
			");
		}
	}
	
	public function peserta_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten) and tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("SELECT * FROM 
			tabel_penyelenggara_penyuluhan, 
			tabel_daftar_perusahaan, 
			tabel_pen_pengajuan_spp, 
			tabel_ambil_penyuluhan,
			tabel_propinsi,
			tabel_kabupaten_kota WHERE 
			tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan and 
			tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND
			tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
			and ((tanggal_pelatihan_awal>='".$tanggal_awal."' and tanggal_pelatihan_awal<='".$tanggal_akhir."')
				or (tanggal_pelatihan_awal>='".$tanggal_awal."' and tanggal_pelatihan_akhir<='".$tanggal_akhir."')
				or (tanggal_pelatihan_akhir='".$tanggal_awal."'))
			");
		
		} else {
			
			return $this->db->query("SELECT * FROM 
			tabel_penyelenggara_penyuluhan, 
			tabel_daftar_perusahaan, 
			tabel_pen_pengajuan_spp, 
			tabel_ambil_penyuluhan,
			tabel_propinsi,
			tabel_kabupaten_kota WHERE 
			tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan and 
			tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND
			tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten");
		
		}
	}
				
		//return $this->db->query("SELECT * FROM tabel_penyelenggara_penyuluhan, tabel_daftar_perusahaan, tabel_pen_pengajuan_spp, tabel_ambil_penyuluhan WHERE tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan and tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan");
		//return $this->db->get('tabel_peserta_penyuluhan');
	
	public function pemeriksaan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("
			SELECT *
			FROM 
				tabel_periksa_sarana_produksi, 
				tabel_pen_pengajuan_spp,
				tabel_daftar_perusahaan,
				tabel_jenis_pangan,
				tabel_kemasan,
				tabel_kabupaten_kota,
				tabel_propinsi
			WHERE 
				tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
				tabel_periksa_sarana_produksi.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
				tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
				tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
				tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND
				tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND
				tanggal_pemeriksaan>='".$tanggal_awal."' and tanggal_pemeriksaan<='".$tanggal_akhir."'
				$q_provinsi $q_kabupaten
			ORDER BY id_urut_periksa_sarana_produksi desc
			");
		} else {
			return $this->db->query("
			SELECT *
			FROM 
				tabel_periksa_sarana_produksi, 
				tabel_pen_pengajuan_spp,
				tabel_daftar_perusahaan,
				tabel_jenis_pangan,
				tabel_kemasan,
				tabel_kabupaten_kota,
				tabel_propinsi
			WHERE 
				tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
				tabel_periksa_sarana_produksi.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
				tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
				tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
				tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND
				tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan
				$q_provinsi $q_kabupaten
			ORDER BY id_urut_periksa_sarana_produksi desc
			");
		}
	}
	
	
	public function penerbitan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("SELECT * FROM tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null");
		} else {
			return $this->db->query("SELECT * FROM 
			tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt, 
			tabel_pen_pengajuan_spp,
			tabel_daftar_perusahaan,
			tabel_propinsi,
			tabel_kabupaten_kota WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
			tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND 
			tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null");
		}
	}
	
	public function perpanjangan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("SELECT * FROM tabel_perpanjangan_sert_pirt, tabel_jenis_pangan, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan and tabel_perpanjangan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and tanggal_pengajuan_perpanjangan>='$tanggal_awal' and tanggal_pengajuan_perpanjangan<='$tanggal_akhir'");
		} else {
			return $this->db->query("SELECT * FROM tabel_perpanjangan_sert_pirt, tabel_jenis_pangan, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan and tabel_perpanjangan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten");
		}
	}
	
	public function perubahan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("SELECT * FROM tabel_perubahan_detail_data, tabel_pen_pengajuan_spp, tabel_penerbitan_sert_pirt, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_perubahan_detail_data.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten");
		} else {
			return $this->db->query("SELECT * FROM tabel_perubahan_detail_data, tabel_pen_pengajuan_spp, tabel_penerbitan_sert_pirt, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_perubahan_detail_data.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten");
		}
	}
	
    	public function pencabutan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir){
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			return $this->db->query("
			SELECT * FROM 
			tabel_pencabutan_pirt, 
			tabel_penerbitan_sert_pirt, 
			tabel_alasan_pencabutan, 
			tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan,
			tabel_propinsi,
			tabel_kabupaten_kota,
			tabel_jenis_pangan WHERE 
			tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert 
			AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan 
			AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan 
			AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
			and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten 
			and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten 
			and tabel_alasan_pencabutan.kode_alasan_pencabutan=tabel_pencabutan_pirt.kode_alasan_pencabutan 
			and tabel_jenis_pangan.id_urut_jenis_pangan = tabel_pen_pengajuan_spp.id_urut_jenis_pangan 
			and tanggal_pencabutan>='$tanggal_awal' and tanggal_pencabutan<='$tanggal_akhir'
			");
		} else {
			return $this->db->query("
			SELECT * FROM 
			tabel_pencabutan_pirt, 
			tabel_penerbitan_sert_pirt, 
			tabel_alasan_pencabutan, 
			tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan,
			tabel_propinsi,
			tabel_kabupaten_kota,
			tabel_jenis_pangan WHERE 
			tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert 
			AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan 
			AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan 
			AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
			AND tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten 
			and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten 
			and tabel_alasan_pencabutan.kode_alasan_pencabutan=tabel_pencabutan_pirt.kode_alasan_pencabutan 
			and tabel_jenis_pangan.id_urut_jenis_pangan = tabel_pen_pengajuan_spp.id_urut_jenis_pangan
			");
		}
	}
	
	public function get_perusahaan_exist($q)
    {
		$this->db->select('nama');
		$this->db->like('nama', $q);
		$query = $this->db->get("perusahaan");
		if($query->num_rows > 0){
			foreach($query->result_array() as $row){
				$row_set[] = htmlentities(stripslashes($row['nama'])); //build an array
			}
			echo json_encode($row_set); //format the array into json data
		}
    }
	
	public function get_info_perusahaan($x)
    {
		$this->db->where("nama",$x);
		return $this->db->get("perusahaan");
        
    }
	
	public function get_kabupaten_with_provinsi($id_urut_kabupaten) {
		return $this->db->query("
			select p.*, k.* 
			from tabel_propinsi p
			join tabel_kabupaten_kota k on k.no_kode_propinsi = p.no_kode_propinsi
			where k.id_urut_kabupaten = {$id_urut_kabupaten}
			limit 1
		");
	}

	public function get_kabupaten($param){
		$this->db->where('no_kode_propinsi', $param);		
		return $this->db->get('tabel_kabupaten_kota');
	}
	
	public function get_perusahaan($param){
		$this->db->where('kode_perusahaan', $param);		
		return $this->db->get('tabel_daftar_perusahaan');
	}
	
	public function peraturan_view(){
		
		return $this->db->get("tabel_peraturan");
			
	}

	public function data_pkp(){
		return $this->db->query('select kode_narasumber, nip_pkp_dfi, nama_narasumber from tabel_narasumber where tot like "PKP%" order by nama_narasumber asc');
	}

	public function data_dfi($where=''){
		return $this->db->query('select kode_narasumber, nip_pkp_dfi, nama_narasumber from tabel_narasumber where tot like "DFI%" '.$where.' order by nama_narasumber asc');
	}

	public function edit_data_pengajuan()
	{

		// yang sudah untuk fungsi update : 
		// - Scan File Alur Produksi [done]
		// - Scan File Rancangan Label [done]
		// - Upload Izin Usaha [done]
		// - Nama Jenis Pangan [done]
		// - Jenis Kemasan (sudah bisa tapi ketika pilihan lain-lain formnya ga muncul) [done]
		// - Jenis prpses produksi (sudah bisa tapi ketika pilihan lain-lain formnya ga muncul) [done]
		// - Komposisi Bahan Tambahan Pangan[done]

		$nomor_permohonan 		= $this->input->post('pemilik_usaha');
		$penanggung_jawab 		= $this->input->post('penanggung_jawab');
		$nik 		   			= $this->input->post('no_nik');
		$alamat_irtp 			= $this->input->post('alamat_irtp');
		$nomor_telefon_irtp 	= $this->input->post('telepon_irtp');
		$grup_jenis_pangan  	= $this->input->post('grup_jenis_pangan');
		$jenis_pangan 			= $this->input->post('jenis_pangan');
		$jenis_pangan_lain		= $this->input->post('jenis_pangan_lain');
		$deskripsi_pangan		= $this->input->post('deskripsi_pangan');
		$nama_dagang			= $this->input->post('nama_dagang');
		$jenis_kemasan 			= $this->input->post('jenis_kemasan');
		$jenis_kemasan_lain		= $this->input->post('jenis_kemasan_lain');
		$berat_bersih 			= $this->input->post('berat_bersih');
		$satuan 				= $this->input->post('berat_bersih_satuan');
		$komposisi_utama 		= $this->input->post('komposisi_utama');
		$proses_produksi 		= $this->input->post('proses_produksi');
		$proses_produksi_lain	= $this->input->post('proses_produksi_lain');
		$masa_simpan			= $this->input->post('masa_simpan');
		$info_kode_produksi 	= $this->input->post('kode_produksi');
		$tanggal_pengajuan 		= $this->input->post('tanggal_pengajuan');
		$waktu 					= $this->input->post('waktu');
		$siup					= $this->input->post('file_pdf');
		$komposisi_tambah 		= $this->input->post('komposisi_tambah');
		$berat_bersih_tambahan	= $this->input->post('berat_bersih_tambahan');
		$satuan_komposisi		= $this->input->post('satuan_komposisi');



		// update untuk tabel 'tabel_pen_pengajuan_spp'
		$data_tabel_pen_pengajuan_spp = array('nomor_permohonan'=> $nomor_permohonan,
										 'komposisi_utama'		=> $komposisi_utama,
										 'kode_r_kemasan'		=> $jenis_kemasan,
										 'jenis_kemasan_lain'	=> $jenis_kemasan_lain,
										 'masa_simpan'			=> $masa_simpan,
										 'waktu'				=> $waktu,
										 'berat_bersih'			=> $berat_bersih,
										 'satuan'				=> $satuan,
										 'kode_r_tek_olah'		=> $proses_produksi,
										 'proses_produksi_lain'	=> $proses_produksi_lain,
										 'info_kode_produksi'	=> $info_kode_produksi,
										 'nama_dagang'			=> $nama_dagang,
										 'tanggal_pengajuan'	=> $tanggal_pengajuan,
										 'deskripsi_pangan'		=> $deskripsi_pangan,
										 'id_urut_jenis_pangan' => $jenis_pangan
										 );
		$this->db->where('id_pengajuan', $this->input->post('id_pengajuan'));
   		$update = $this->db->update('tabel_pen_pengajuan_spp',$data_tabel_pen_pengajuan_spp);
		
    	// daftar perusahaan
    	$data_tabel_daftar_perusahaan = array('nama_penanggung_jawab' => $penanggung_jawab,
    										  'no_nik'					  => $nik,
    										  'alamat_irtp'		  	  => $alamat_irtp,
    										  'nomor_telefon_irtp'	  => $nomor_telefon_irtp
    										);
    	$this->db->where('kode_perusahaan', $this->input->post('kode_perusahaan'));
   		$update = $this->db->update('tabel_daftar_perusahaan',$data_tabel_daftar_perusahaan);
			 
		//komposisi tambahan
		$much=count($komposisi_tambah);
		$nomor_permohonan=$this->input->post('nomor_permohonan');
		$this->db->where('nomor_r_permohonan',$nomor_permohonan);
		$this->db->delete('tabel_ambil_komposisi_tambahan');

		for($i=0;$i<$much;$i++)
		{
			$kode=$komposisi_tambah[$i];
			$berat=$berat_bersih_tambahan[$i];
			$satuan=$satuan_komposisi[$i];
			$this->db->insert('tabel_ambil_komposisi_tambahan'
			,array(
				'nomor_r_permohonan'=>$nomor_permohonan
				,'kode_r_komposisi'=>$kode
				,'berat'=>$berat
				,'satuan'=>$satuan
			)
			);
		}

		if($update)
    	{
    		return 1;
    	}

		
	}

	public function delete_pengajuan($param="")
	{
		if($param!="")
	    {
	      $this->db->where($param);
	    }
	    $this->db->delete("tabel_pen_pengajuan_spp");
	}

	public function delete_pelaksanaan_pkp($param="")
	{
		if($param!="")
	    {
	      $this->db->where($param);
	    }
	    $this->db->delete("tabel_penyelenggara_penyuluhan");
	}

	public function delete_penerbitan_sertifikat($param="")
	{
		if($param!="")
	    {
	      $this->db->where($param);
	    }
	    $this->db->delete("tabel_penerbitan_sert_pirt");
	}

	 public function delete_perpanjangan($param="")
	{
		if($param!="")
	    {
	      $this->db->where($param);
	    }
	    $this->db->delete("tabel_perpanjangan_sert_pirt");
	}

	public function delete_pencabutan($param="")
	{
	if($param!="")
    {
      $this->db->where($param);
    }
    $this->db->delete("tabel_pencabutan_pirt");
	}

}
?>