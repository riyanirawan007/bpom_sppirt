
<?php
include"connect.php";
date_default_timezone_set('Asia/Jakarta');
$today = date("j F Y");
header("Content-type: application/x-msdownload");
header('Content-Disposition: attachment; filename="Laporan_Penerbitan_IRTP_'.$today.'.xls"');

if($tanggal_awal==""){
	$tanggal = "Per tanggal ".$today;
} else {
	$tanggal = date('j F Y', strtotime($tanggal_awal))." Sampai ".date('j F Y', strtotime($tanggal_akhir));
}
?>
<center>
<h3>BADAN PENGAWAS OBAT DAN MAKANAN</h3>
<h3>Laporan Penerbitan Sertifikat IRTP</h3>
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
	<th>Nomor Permohonan</th>
	<th>Tanggal Pemberian PIRT</th>
	<th>Nomor PIRT</th>
	<th>Nomor HK</th>
	<th>Nama Kepala Dinas</th>
	<th>NIP</th>
</tr>
<?php
	if($propinsi!="0" and $propinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$propinsi'"; } else { $q_provinsi = ""; }
	if($kabupaten!="0" and $propinsi!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
	
	if($tanggal_awal!='' and $tanggal_akhir!=''){
		$query = mysqli_query($connect,"SELECT * FROM tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null");
	} else {
		$query = mysqli_query($connect,"SELECT * FROM tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null");
	}
	
$no=1;
while($r=mysqli_fetch_array($query)){
	
	echo"
	<tr>
		<td>$no</td>
		<td>$r[nomor_permohonan]</td>
		<td>".date('d-m-Y', strtotime($r['tanggal_pemberian_pirt']))."</td>
		<td>$r[nomor_pirt]</td>
		<td>$r[nomor_hk]</td>
		<td>$r[nama_kepala_dinas]</td>
		<td>$r[nip]</td>
	</tr>
	";
	$no++;
}
?>
</table><br>
<?php
if($propinsi!='0' or $kabupaten!='0'){
			
			
			if($tanggal_awal!='' and $tanggal_akhir!=''){
				$jml_pangan = mysqli_query($connect,"SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan");
				$jml_kemasan = mysqli_query($connect,"SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan");
			} else {
				$jml_pangan = mysqli_query($connect,"SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan");
				$jml_kemasan = mysqli_query($connect,"SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan");
			}
		} else {
		
			if($tanggal_awal!='' and $tanggal_akhir!=''){
				$jml_pangan = mysqli_query($connect,"SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan");
				$jml_kemasan = mysqli_query($connect,"SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan");
			} else {
				$jml_pangan = mysqli_query($connect,"SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan");
				$jml_kemasan = mysqli_query($connect,"SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan");
			}
		}
		
		$jumlah_irtp = $no-1;
		
?>
<?php echo "Jumlah Penerbitan SPP-IRT : <b>".$jumlah_irtp. "</b><br>";?>
	<?php echo "Jumlah SPP-IRT Berdasarkan Jenis Pangan : <!--<b>".mysqli_num_rows($jml_pangan). "</b>--><br>";
	echo "<ul>";
		while($r = mysqli_fetch_array($jml_pangan)){
			echo "<li>".$r['jenis_pangan'] ." sebanyak <b>". $r['count_jenis'] ." </b> SPP-IRT</li>";
		}
	echo "</ul>";
	?>
	<?php echo "Jumlah SPP-IRT Berdasarkan Jenis Kemasan : <!--<b>".mysqli_num_rows($jml_kemasan). "</b>--><br>";
	echo "<ul>";
		while($r = mysqli_fetch_array($jml_kemasan)){
			echo "<li>".$r['jenis_kemasan'] ." sebanyak <b>". $r['count_kemasan'] ." </b> SPP-IRT</li>";
		}
	echo "</ul>";
	?>	
	<!-- <?php echo "Jumlah SPP-IRT Berdasarkan Nilai Mutu Sarana : <b></b><br>";
	echo "<ul>";
		while($r = mysqli_fetch_array($jml_komposisi)){
			echo "<li>".$r['jenis_komposisi'] ." sebanyak <b>". $r['count_komposisi'] ." </b> SPP-IRT</li>";
		}
	echo "</ul>";
	?> -->
	