
<?php
include"connect.php";
date_default_timezone_set('Asia/Jakarta');
$today = date("j F Y");
header("Content-type: application/x-msdownload");
header('Content-Disposition: attachment; filename="Laporan_Pencabutan_IRTP_'.$today.'.xls"');

if($tanggal_awal==""){
	$tanggal = "Per tanggal ".$today;
} else {
	$tanggal = date('j F Y', strtotime($tanggal_awal))." Sampai ".date('j F Y', strtotime($tanggal_akhir));
}
?>
<center>
<h3>BADAN PENGAWAS OBAT DAN MAKANAN</h3>
<h3>Laporan Pencabutan Sarana Produksi IRTP</h3>
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
<tr>
	<th>No.</th>
	<th>Nomor PIRT</th>
	<th>Nama IRTP</th>
	<th>Jenis Pangan</th>
	<th>Pemilik Perusahaan</th>
	<th>Penanggung Jawab Perusahaan</th>
	<th>Provinsi</th>
	<th>Kabupaten/Kota</th>
	<th>Alasan Pencabutan</th>
	<th>Tanggal Pencabutan</th>
</tr>
<?php
	if($propinsi!="0" and $propinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$propinsi'"; } else { $q_provinsi = ""; }
	if($kabupaten!="0" and $propinsi!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
	
	if($tanggal_awal!='' and $tanggal_akhir!=''){
		$query = mysqli_query($connect,"
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
		$query = mysqli_query($connect,"
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
$no=1;
while($r=mysqli_fetch_array($query)){
	if($r['kode_alasan_pencabutan']!=0){
		$alasan_pencabutan = $r['alasan_pencabutan'];
	} else {
		$alasan_pencabutan = $r['alasan_pencabutan_lain'];
	}
	echo"
	<tr>
		<td>$no</td>
		<td>$r[nomor_pirt]</td>
		<td>$r[nama_perusahaan]</td>
		<td>$r[jenis_pangan]</td>
		<td>$r[nama_pemilik]</td>
		<td>$r[nama_penanggung_jawab]</td>
		<td>$r[nama_propinsi]</td>
		<td>$r[nm_kabupaten]</td>
		<td>$alasan_pencabutan</td>
		<td>".date('d-m-Y', strtotime($r['tanggal_pencabutan']))."</td>
	</tr>
	";
	$no++;
}
	if($propinsi!='0' or $kabupaten!='0'){
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			$jml_alasan = mysqli_query($connect,"SELECT 
			count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM 
			tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota 
			WHERE 
			tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND 
			tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND 
			tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and
			tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."'
			GROUP BY alasan_pencabutan
			");
			$jml_pangan = mysqli_query($connect,"SELECT 
			count(jenis_pangan) as count_jenis, jenis_pangan FROM 
			tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, 
			tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota 
			WHERE 
			tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and 
			tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and 
			tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND 
			tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND 
			tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and
			tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."'
			GROUP BY jenis_pangan
			");
		} else {
			$jml_alasan = mysqli_query($connect,"SELECT count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY alasan_pencabutan");
			$jml_pangan = mysqli_query($connect,"SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY jenis_pangan");

		}
	} else {
		
		if($tanggal_awal!='' and $tanggal_akhir!=''){
			$jml_alasan = mysqli_query($connect,"SELECT 
			count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM 
			tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan 
			WHERE 
			tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND 
			tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND 
			tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and
			tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."'
			GROUP BY alasan_pencabutan
			");
			$jml_pangan = mysqli_query($connect,"SELECT 
			count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pencabutan_pirt, 
			tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, 
			tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan 
			WHERE 
			tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and 
			tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and 
			tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND 
			tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND 
			tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and
			tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."'
			GROUP BY jenis_pangan
			");
		} else {
			$jml_alasan = mysqli_query($connect,"SELECT count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan GROUP BY alasan_pencabutan");
			$jml_pangan = mysqli_query($connect,"SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan GROUP BY jenis_pangan");
		}
	}
	
	$jumlah_pencabutan_irtp = $no-1;
?>

</table><br>
<?php echo "Jumlah SPP-IRT yang dicabut : <b>".$jumlah_pencabutan_irtp. "</b><br>";?>
	<?php echo "Jumlah SPP-IRT yang dicabut berdasarkan Alasan Pencabutan : <!--<b>".mysqli_num_rows($jml_alasan). "</b>--><br>";
	echo "<ul>";
		while($data = mysqli_fetch_array($jml_alasan)){
			$nm_alasan = ($data['alasan_pencabutan']!='-')?$data['alasan_pencabutan']:"(Lainnya)";
			echo "<li>".$nm_alasan ." sebanyak <b>". $data['count_alasan'] ." </b> SPP-IRT</li>";
		}
	echo "</ul>";
	?>
	
	<?php echo "Jumlah SPP-IRT yang dicabut berdasarkan Jenis Pangan : <!--<b>".mysqli_num_rows($jml_pangan). "</b>--><br>";
	echo "<ul>";
		while($data = mysqli_fetch_array($jml_pangan)){
			echo "<li>".$data['jenis_pangan'] ." sebanyak <b>". $data['count_jenis'] ." </b> SPP-IRT</li>";
		}
	echo "</ul>";
	?>

	