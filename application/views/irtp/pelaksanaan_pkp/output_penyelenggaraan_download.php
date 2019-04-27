
<?php
include"connect.php";
date_default_timezone_set('Asia/Jakarta');
$today = date("j F Y");
header("Content-type: application/x-msdownload");
header('Content-Disposition: attachment; filename="Laporan_Penyelenggaraan_IRTP_'.$today.'.xls"');

if($tanggal_awal==""){
	$tanggal = "Per tanggal ".$today;
} else {
	$tanggal = date('j F Y', strtotime($tanggal_awal))." Sampai ".date('j F Y', strtotime($tanggal_akhir));
}
?>
<center>
<h3>BADAN PENGAWAS OBAT DAN MAKANAN</h3>
<h3>Laporan Penyelenggaraan IRTP</h3>
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
	<th>No. Permohonan</th>
	<th>Tanggal Awal Penyuluhan</th>
	<th>Tanggal Akhir Penyuluhan</th>
	<th>Provinsi</th>
	<th>Kabupaten/Kota</th>
	<th>Nama Narasumber Non PKP</th>
	<th>Narasumber Berdasarkan Materi Penyuluhan</th>
	<th>Materi Penyuluhan Tambahan</th>
	<th>Materi Penyuluhan Lainnya</th>
</tr>
<?php
	if($propinsi!="0" and $propinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$propinsi'"; } else { $q_provinsi = ""; }
	if($kabupaten!="0" and $propinsi!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
	
	if($tanggal_awal!='' and $tanggal_akhir!=''){
		$query = mysqli_query($connect,"SELECT * FROM 
		tabel_penyelenggara_penyuluhan,
		tabel_propinsi,tabel_kabupaten_kota WHERE 
		tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
		tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten
		and ((tanggal_pelatihan_awal>='".$tanggal_awal."' and tanggal_pelatihan_awal<='".$tanggal_akhir."')
		or (tanggal_pelatihan_awal>='".$tanggal_awal."' and tanggal_pelatihan_akhir<='".$tanggal_akhir."')
		or (tanggal_pelatihan_akhir='".$tanggal_awal."'))
		");
	} else {
		$query = mysqli_query($connect,"SELECT * FROM 
		tabel_penyelenggara_penyuluhan,
		tabel_propinsi,tabel_kabupaten_kota WHERE 
		tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and 
		tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten");

	}
$no=1;
$jumlah_peserta_lulus=0;
$jumlah_peserta_tidak_lulus=0;
while($r=mysqli_fetch_array($query)){
	$get_materi = mysqli_query($connect, "select * from tabel_ambil_materi_penyuluhan ambil
	left join tabel_materi_penyuluhan materi on ambil.kode_r_materi_penyuluhan = materi.kode_materi_penyuluhan
	left join tabel_narasumber nara on ambil.kode_r_narasumber = nara.kode_narasumber
	where ambil.nomor_r_permohonan_penyuluhan = '".$r['nomor_permohonan_penyuluhan']."'");
	$no_materi = 0;
	$materi_penyuluhan = "";
	while($rget_materi=mysqli_fetch_array($get_materi)){
		$no_materi++;
		$materi_penyuluhan .= $no_materi.". ".$rget_materi['nama_materi_penyuluhan']." oleh : ".$rget_materi['nama_narasumber']."<br>";
	}
	
	$xplod_tambahan = explode(",",$r['materi_tambahan']);
	$no_materi = 0;
	$materi_tambahan = "";
	foreach($xplod_tambahan as $row_tambahan){
		$get_materi = mysqli_query($connect, "select * from tabel_materi_penyuluhan
		where kode_materi_penyuluhan = '".$row_tambahan."'");
		
		while($rget_materi=mysqli_fetch_array($get_materi)){
			$no_materi++;
			$materi_tambahan .= $no_materi.". ".$rget_materi['nama_materi_penyuluhan']."<br>";
		}
	}
	$tanggal_pelatihan_awal = date('d-m-Y', strtotime($r['tanggal_pelatihan_awal']));
	$tanggal_pelatihan_akhir = date('d-m-Y', strtotime($r['tanggal_pelatihan_akhir']));
	echo"
	<tr>
		<td style='vertical-align:top'>$no</td>
		<td style='vertical-align:top'>$r[nomor_permohonan_penyuluhan]</td>
		<td style='vertical-align:top'>".$tanggal_pelatihan_awal."</td>
		<td style='vertical-align:top'>".$tanggal_pelatihan_akhir."</td>
		<td style='vertical-align:top'>$r[nama_propinsi]</td>
		<td style='vertical-align:top'>$r[nm_kabupaten]</td>
		<td style='vertical-align:top'>$r[nama_narasumber_non_pkp]</td>
		<td style='vertical-align:top'>$materi_penyuluhan</td>
		<td style='vertical-align:top'>$materi_tambahan</td>
		<td style='vertical-align:top'>$r[materi_pelatihan_lainnya]</td>
	</tr>
	";
	$no++;
}
?>
</table><br>

	