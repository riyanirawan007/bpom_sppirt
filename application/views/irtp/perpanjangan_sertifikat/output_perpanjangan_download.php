
<?php
include"connect.php";
date_default_timezone_set('Asia/Jakarta');
$today = date("j F Y");
header("Content-type: application/x-msdownload");
header('Content-Disposition: attachment; filename="Laporan_Perpanjangan_IRTP_'.$today.'.xls"');

$tanggal = "Per tanggal ".$today;
?>
<center>
<h3>BADAN PENGAWAS OBAT DAN MAKANAN</h3>
<h3>Laporan Perpanjangan Sertifikat P-IRTP</h3>
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
	<th>Nomor PIRT Lama</th>
	<th>Nomor PIRT Baru</th>
	<th>Nama IRTP</th>
	<th>Nama Jenis Pangan</th>
	<th>Nama Produk Pangan</th>
	<th>Nama Dagang</th>
	<th>Tanggal Perpanjangan</th>
	<th>Nomor Permohonan</th>
</tr>
<?php
	if($propinsi!="0" and $propinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$propinsi'"; } else { $q_provinsi = ""; }
	if($kabupaten!="0" and $propinsi!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
	
	if($tanggal_awal!='' and $tanggal_akhir!=''){
		$query = mysqli_query($connect,"SELECT * FROM tabel_perpanjangan_sert_pirt, tabel_jenis_pangan, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan and tabel_perpanjangan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and tanggal_pengajuan_perpanjangan>='$tanggal_awal' and tanggal_pengajuan_perpanjangan<='$tanggal_akhir'");
	} else {
		$query = mysqli_query($connect,"SELECT * FROM tabel_perpanjangan_sert_pirt, tabel_jenis_pangan, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan and tabel_perpanjangan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten");
	}
$no=1;
while($r=mysqli_fetch_array($query)){
	//$nomor_pirt_infix = substr($r['nomor_pirt'], 15);
	//$nomor_pirt_prefix = substr($r['nomor_pirt'], 0, 14);
	
	echo"
	<tr>
			<td>$no</td>
			<td>$r[nomor_pirt]</td>
			<td>$r[nomor_pirt_baru]</td>
			<td>$r[nama_perusahaan]</td>
			<td>$r[jenis_pangan]</td>
			<td>$r[deskripsi_pangan]</td>
			<td>$r[nama_dagang]</td>
			<td>".date('d-m-Y', strtotime($r['tanggal_pengajuan_perpanjangan']))."</td>
			<td>$r[nomor_permohonan]</td>
			
	</tr>
	";
	$no++;
}

if($propinsi!='0' or $kabupaten!='0'){
			
			
			$penerbitan = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten"));
		} else {
			$penerbitan = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan"));
		}
		
		
		
		
		$jumlah_irtp = $no-1;
		$jumlah_irtp_tidak = $penerbitan-$jumlah_irtp;
?>
</table><br>
<?php echo "Jumlah SPP-IRT Yang Mengajukan Perpanjangan : <b>".$jumlah_irtp. " </b>SPP-IRT<br>";?>
	<?php echo "Jumlah SPP-IRT Yang Tidak Mengajukan Perpanjangan : <b>".$jumlah_irtp_tidak. " </b>SPP-IRT<br>";?>

	