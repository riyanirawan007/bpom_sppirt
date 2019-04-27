
<?php
include"connect.php";
date_default_timezone_set('Asia/Jakarta');
$today = date("j F Y");
header("Content-type: application/x-msdownload");
header('Content-Disposition: attachment; filename="Laporan_Pemeriksaan_IRTP_'.$today.'.xls"');

if($tanggal_awal==""){
	$tanggal = "Per tanggal ".$today;
} else {
	$tanggal = date('j F Y', strtotime($tanggal_awal))." Sampai ".date('j F Y', strtotime($tanggal_akhir));
}
?>
<center>
<h3>BADAN PENGAWAS OBAT DAN MAKANAN</h3>
<h3>Daftar Pemeriksaan Sarana Produksi</h3>
<h4><?php echo $tanggal;?></h4>
</center>

<?php 
if($propinsi!=0){
	$get = mysqli_query($connect,"select * from tabel_propinsi where no_kode_propinsi='$propinsi'");
	while($r=mysqli_fetch_array($get)){
		$nama_propinsi = $r['nama_propinsi'];
	}
	echo"Provinsi : $nama_propinsi<br><br>";
}

if($kabupaten!=0){
	$get = mysqli_query($connect,"select * from tabel_kabupaten_kota where id_urut_kabupaten='$kabupaten'");
	while($r=mysqli_fetch_array($get)){
		$nama_kabupaten = $r['nm_kabupaten'];
	}
	echo"Kabupaten/Kota : $nama_kabupaten<br><br>";
}
?>
<table border="1">
<thead>
	<tr>
		<th rowspan="3">No.</th>
		<th rowspan="3">Nomor Permohonan</th>
		<th rowspan="3">Nama IRTP</th>
		<th rowspan="3">Alamat</th>
		<th rowspan="3">Nama Jenis Pangan</th>
		<th rowspan="3">Nama Produk Pangan</th>
		<th rowspan="3">Nama Dagang</th>
		<th rowspan="3">Jenis Kemasan</th>
		<th colspan="8">Jumlah Ketidaksesuain</th>
		<th rowspan="3">Level IRTP</th>
		<th rowspan="3">Frekuensi Audit Internal</th>
		<th rowspan="3">Tanggal Pemeriksaan</th>
	</tr>
	<tr>
		<td colspan="2">Minor</td>
		<td colspan="2">Mayor</td>
		<td colspan="2">Serius</td>
		<td colspan="2">Kritis</td>
	</tr>
	<tr>
		<td>Jumlah</td>
		<td>Nomor</td>
		<td>Jumlah</td>
		<td>Nomor</td>
		<td>Jumlah</td>
		<td>Nomor</td>
		<td>Jumlah</td>
		<td>Nomor</td>
	</tr>
</thead>
<tbody>
<?php
	if($propinsi!="0" and $propinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$propinsi'"; } else { $q_provinsi = ""; }
	if($kabupaten!="0" and $propinsi!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
	if($tanggal_awal!='' and $tanggal_akhir!=''){
		$query = mysqli_query($connect,"SELECT *
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
			");
	} else {
		$query = mysqli_query($connect,"SELECT *
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
			");
	}	
	
$no=1;
while($r=mysqli_fetch_array($query)){
	
	?>
	<tr>
					<td><?php echo $no; $no++?></td>
					<td><?= $r['nomor_r_permohonan'] ?></td>
					<td><?= $r['nama_perusahaan'] ?></td>
					<td><?= $r['alamat_irtp'] ?></td>
					<td><?= $r['jenis_pangan'] ?></td>
					<td><?= $r['deskripsi_pangan'] ?></td>
					<td><?= $r['nama_dagang'] ?></td>
					<td><?= ($r['kode_r_kemasan']==6)?$r['jenis_kemasan_lain']:$r['jenis_kemasan'] ?></td>
					
					<?php
					$get_minor = mysqli_query($connect,"select * from tabel_periksa_sarana_produksi_detail 
					where id_r_urut_periksa_sarana_produksi = '".$r['id_urut_periksa_sarana_produksi']."'
					and level = 'Minor'
					");
					$arr_sesuai = array();
					while($rget=mysqli_fetch_array($get_minor)){
						$arr_sesuai[] = $rget['no_ketidaksesuaian'];
					}
					$no_ketidaksesuaian = implode(', ', $arr_sesuai);
					?>
					<td><?= mysqli_num_rows($get_minor) ?></td>
					<td><?= $no_ketidaksesuaian ?></td>
					
					<?php
					$get_mayor = mysqli_query($connect,"select * from tabel_periksa_sarana_produksi_detail 
					where id_r_urut_periksa_sarana_produksi = '".$r['id_urut_periksa_sarana_produksi']."'
					and level = 'Mayor'
					");
					$arr_sesuai = array();
					while($rget=mysqli_fetch_array($get_mayor)){
						$arr_sesuai[] = $rget['no_ketidaksesuaian'];
					}
					$no_ketidaksesuaian = implode(', ', $arr_sesuai);
					?>
					<td><?= mysqli_num_rows($get_mayor) ?></td>
					<td><?= $no_ketidaksesuaian ?></td>
					
					<?php
					$get_serius = mysqli_query($connect,"select * from tabel_periksa_sarana_produksi_detail 
					where id_r_urut_periksa_sarana_produksi = '".$r['id_urut_periksa_sarana_produksi']."'
					and level = 'Serius'
					");
					$arr_sesuai = array();
					while($rget=mysqli_fetch_array($get_serius)){
						$arr_sesuai[] = $rget['no_ketidaksesuaian'];
					}
					$no_ketidaksesuaian = implode(', ', $arr_sesuai);
					?>
					<td><?= mysqli_num_rows($get_serius) ?></td>
					<td><?= $no_ketidaksesuaian ?></td>
					
					<?php
					$get_kritis = mysqli_query($connect,"select * from tabel_periksa_sarana_produksi_detail 
					where id_r_urut_periksa_sarana_produksi = '".$r['id_urut_periksa_sarana_produksi']."'
					and level = 'Kritis'
					");
					$arr_sesuai = array();
					while($rget=mysqli_fetch_array($get_kritis)){
						$arr_sesuai[] = $rget['no_ketidaksesuaian'];
					}
					$no_ketidaksesuaian = implode(', ', $arr_sesuai);
					?>
					<td><?= mysqli_num_rows($get_kritis) ?></td>
					<td><?= $no_ketidaksesuaian ?></td>
					
					<td><?= $r['level_irtp'] ?></td>
					<td><?= $r['frekuensi_audit'] ?></td>
					<td><?= date("d F Y", strtotime($r['tanggal_pemeriksaan'])) ?></td>
				</tr>
	<?php
	
}
?>
</table><br>
<?php
$jumlah_irtp = $no-1;
?>

	<?php 
	if($propinsi!="0" and $propinsi!=""){ $q_provinsi = "and PP.no_kode_propinsi='$propinsi'"; } else { $q_provinsi = ""; }
	if($kabupaten!="0" and $propinsi!=""){ $q_kabupaten = "and KK.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
	
	foreach(array('Mayor', 'Minor', 'Serius', 'Kritis') as $kriteria) {
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			$list_jumlah_irtp_kriteria[$kriteria] = mysqli_query($connect, "
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
			");
		} else {
			$list_jumlah_irtp_kriteria[$kriteria] = mysqli_query($connect, "
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
			");
		}
	}

	echo "Jumlah SPP-IRT yang Terdapat Ketidaksesuaian : <br>";
	echo "<ul>";
		while($data = mysqli_fetch_array($list_jumlah_irtp_kriteria['Minor'])){
			echo "<li>Jumlah Ketidaksesuaian Minor : ". $data['count'] ." </li>";
		}
		while($data = mysqli_fetch_array($list_jumlah_irtp_kriteria['Mayor'])){
			echo "<li>Jumlah Ketidaksesuaian Mayor : ". $data['count'] ." </li>";
		}
		while($data = mysqli_fetch_array($list_jumlah_irtp_kriteria['Serius'])){
			echo "<li>Jumlah Ketidaksesuaian Serius : ". $data['count'] ." </li>";
		}
		while($data = mysqli_fetch_array($list_jumlah_irtp_kriteria['Kritis'])){
			echo "<li>Jumlah Ketidaksesuaian Kritis : ". $data['count'] ." </li>";
		}	
	echo "</ul>";
	?>


	<?php 
	foreach(array('Level I', 'Level II', 'Level III', 'Level IV') as $level) {
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			$list_jumlah_irtp_level[$level] = mysqli_query($connect, "
			select count(distinct(PR.id_urut_periksa_sarana_produksi)) as count 
					from tabel_pen_pengajuan_spp PJ
						JOIN tabel_daftar_perusahaan PT ON PJ.kode_r_perusahaan = PT.kode_perusahaan
						JOIN tabel_jenis_pangan JP ON PJ.id_urut_jenis_pangan = JP.id_urut_jenis_pangan
						JOIN tabel_kemasan JK ON JK.kode_kemasan = PJ.kode_r_kemasan
						JOIN tabel_periksa_sarana_produksi PR ON PJ.nomor_permohonan = PR.nomor_r_permohonan
						JOIN tabel_kabupaten_kota KK ON PT.id_r_urut_kabupaten = KK.id_urut_kabupaten
						JOIN tabel_propinsi PP ON PP.no_kode_propinsi = KK.no_kode_propinsi
					where PR.level_irtp = '".$level."'
					$q_provinsi $q_kabupaten
					and tanggal_pemeriksaan>='".$tanggal_awal."' and tanggal_pemeriksaan<='".$tanggal_akhir."'
					group by PR.level_irtp
			");
		} else {
			$list_jumlah_irtp_level[$level] = mysqli_query($connect, "
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
			");
		}
	}
	
	echo "Jumlah SPP-IRT Berdasarkan Level : <br>";
	echo "<ul>";
		if(mysqli_num_rows($list_jumlah_irtp_level['Level I'])>0){
			while($data = mysqli_fetch_array($list_jumlah_irtp_level['Level I'])){
				echo "<li>Jumlah SPP-IRT Berdasarkan Level I : ". $data['count'] ." </li>";
			}
		} else {
			$data['count'] = 0;
			echo "<li>Jumlah SPP-IRT Berdasarkan Level I : ". $data['count'] ." </li>";
		}
		
			
		if(mysqli_num_rows($list_jumlah_irtp_level['Level II'])>0){
			while($data = mysqli_fetch_array($list_jumlah_irtp_level['Level II'])){
				echo "<li>Jumlah SPP-IRT Berdasarkan Level II : ". $data['count'] ." </li>";
			}
		} else {
			$data['count'] = 0;
			echo "<li>Jumlah SPP-IRT Berdasarkan Level II : ". $data['count'] ." </li>";
		}
		
		
		if(mysqli_num_rows($list_jumlah_irtp_level['Level III'])>0){
			while($data = mysqli_fetch_array($list_jumlah_irtp_level['Level III'])){
				echo "<li>Jumlah SPP-IRT Berdasarkan Level III : ". $data['count'] ." </li>";
			}
		} else {
			$data['count'] = 0;
			echo "<li>Jumlah SPP-IRT Berdasarkan Level III : ". $data['count'] ." </li>";
		}
		
		
		if(mysqli_num_rows($list_jumlah_irtp_level['Level IV'])>0){
			while($data = mysqli_fetch_array($list_jumlah_irtp_level['Level IV'])){
				echo "<li>Jumlah SPP-IRT Berdasarkan Level IV : ". $data['count'] ." </li>";
			}
		} else {
			$data['count'] = 0;
			echo "<li>Jumlah SPP-IRT Berdasarkan Level IV : ". $data['count'] ." </li>";
		}
		
			
	echo "</ul>";
	?>	