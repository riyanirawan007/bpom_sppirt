<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Output</title>
	
	
	
    <style type="text/css">
<!--
.style1 {font-family: Georgia, "Times New Roman", Times, serif}
.style6 {font-family: Georgia, "Times New Roman", Times, serif; font-size: 18px; }
.style7 {color: #FFFFFF}
.style8 {color: #000000}
.style9 {
	font-size: 16px;
	font-weight: bold;
}
.style10 {color: #000000; font-size: 14px; }
-->
#page-wrap {
    width: 21cm;
    min-height: 29.7cm;
    margin: 0.5cm auto;
    /*border: 1px #D3D3D3 solid;
    border-radius: 5px;
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);*/
    background: white;
    
}
@page-wrap {
    size: A4;
    margin: 0;
}
    </style>
</head>

<body>

	<div id="page-wrap">
	<div id="image2">
	    <!-- <img src="<?= base_url(); ?>images/logo.png" alt="logo" name="image" width="107" height="140"  />   -->
	</div>
<div id="header">
	<?php 
	foreach($query as $data): 
	$kota = $data->nm_kabupaten;	
	?>
	<div align="center">
		<p>&nbsp;</p>
		<h1 class="style6"><u>SERTIFIKAT PRODUKSI PANGAN INDUSTRI RUMAH TANGGA</u></h1>
		<br>
		<h1 class="style6">P-IRT NO : <?=$data->nomor_pirt?> </h1>
		
		<table bordercolor="#FFFFFF" bgcolor="#FFFFFF" id="meta">
			
			<tr>
				<td height="33%" class="meta-head style1">Diberikan kepada : </td>
				<!-- <td align="left">:</td>
				<td><div align="left">.............................................................................................................<div id="titik2"></div></td> -->
			</tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama IRT </td>
				<td align="left">:</td>
                <td align="left"><?=$data->nama_perusahaan?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Pemilik </td>
				<td align="left">:</td>
                <td align="left"><?=$data->nama_pemilik?><div id="titik2"></div></td>
            </tr>
            
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat </td>
				<td align="left">:</td>
                <td align="left"><?=$data->alamat_irtp?><div id="titik2"></div></td>
            </tr>
   			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jenis Pangan </td>
				<td align="left">:</td>
                <td align="left"><?=$data->jenis_pangan?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(sesuai nama jenis pangan IRT) </td>
				<td align="left"></td>
                <td align="left"><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kemasan Primer </td>
				<td align="left">:</td>
                <td align="left"><?= ($data->kode_r_kemasan==6)?$data->jenis_kemasan_lain:$data->jenis_kemasan ?><div id="titik2"></div></td>
            </tr>
           
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td colspan="3" height="33%" class="meta-head style1" align="justify">
					Yang telah memenuhi persyaratan Pemberian Sertifikat Produksi Pangan Industri Rumah Tangga (SPP-IRT) 
					berdasarkan Peraturan Badan Pengawas obat dan Makanan RI tentang Pedoman Pemberian Sertifikat Produksi Pangan Industri Rumah Tangga Nomor 22 pada tahun 2018, telah mengikuti Penyuluhan Keamanan Pangan (PKP) yang diselenggarakan di : </td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kabupaten/Kota *) </td>
				<td align="left">:</td>
                <td align="left"><?=$data->nm_kabupaten?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Propinsi </td>
				<td align="left">:</td>
                <td align="left"><?=$data->nama_propinsi?><div id="titik2"></div></td>
            </tr>
            
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pada tanggal </td>
				<td align="left">:</td>
                <!-- <td align="left"><?= date("d F Y", strtotime($data->tanggal_pemberian_pirt))?><div id="titik2"></div></td> -->
                <td align="left"><?= format_tanggal(date("d", strtotime($data->tanggal_pemberian_pirt)), date("m", strtotime($data->tanggal_pemberian_pirt)), date("Y", strtotime($data->tanggal_pemberian_pirt))); ?><div id="titik2"></div></td>
            </tr>
   			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
		</table>	
	</div>
	<?php 
	endforeach; 
	$kota = str_replace("Kota","",$kota); $kota = str_replace("Kab.","",$kota);
	?>	
	<div id="hormat" align="right">
	<table>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span> 
			</td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center"><?= $kota ?>, <?= tanggal() ?> </td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center">DINAS KESEHATAN KAB/KOTA <?= ($kota!="")?strtoupper($kota):".........." ?> </td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center">KEPALA, </td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr><tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr><tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr><tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center"><u> <?= $data->nama_kepala_dinas ?> </u></td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center">NIP. <?= $data->nip ?> </td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
	</table>
	</div>
	*) Coret yang tidak perlu 
	</div>
	</div>

</body>
</html>