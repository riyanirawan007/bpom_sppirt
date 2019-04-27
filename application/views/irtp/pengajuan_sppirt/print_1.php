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
	<?php foreach($query as $data): 
	$kota = $data->nm_kabupaten;
	?>
	<div align="center">
		<p>&nbsp;</p>
	    <h1 class="style6">FORMULIR</h1>
		<h1 class="style6"><u>PERMOHONAN SERTIFIKAT PRODUKSI PANGAN INDUSTRI RUMAH TANGGA</u></h1>
		<h1 class="style6"><u>(SPP-IRT)</u></h1>
		
		<table bordercolor="#FFFFFF" bgcolor="#FFFFFF" id="meta" width="100%">
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">1.&nbsp;&nbsp;&nbsp;Nama jenis pangan </td>
				<td align="left">:</td>
				<td><div align="left"><?=$data->jenis_pangan?><div id="titik2"></div></div></td>
			</tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;(sesuai nama jenis pangan IRT)</td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">2.&nbsp;&nbsp;&nbsp;Nama dagang </td>
				<td align="left">:</td>
                <td align="left"><?=$data->nama_dagang?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">3.&nbsp;&nbsp;&nbsp;Jenis kemasan </td>
				<td align="left">:</td>
                <td align="left"><?=($data->kode_r_kemasan==6)?$data->jenis_kemasan_lain:$data->jenis_kemasan?><div id="titik2"></div></td>
            </tr>
            
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">4.&nbsp;&nbsp;&nbsp;Berat bersih / isi bersih </td>
				<td align="left">:</td>
                <td align="left"><?=$data->berat_bersih?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(g/mg/kg atau l/ml/kl)</td>
            </tr>
   			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">5.&nbsp;&nbsp;&nbsp;Komposisi </td>
				<td align="left">:</td>
                <td align="left"><?=$data->komposisi_utama?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">6.&nbsp;&nbsp;&nbsp;Proses produksi </td>
				<td align="left">:</td>
                <td align="left"><?=($data->kode_r_tek_olah==11)?$data->proses_produksi_lain:$data->tek_olah?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">7.&nbsp;&nbsp;&nbsp;Informasi tentang masa simpan </td>
				<td align="left">:</td>
                <td align="left"><?=$data->masa_simpan?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(kedaluwarsa)</td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">8.&nbsp;&nbsp;&nbsp;Informasi tentang kode produksi </td>
				<td align="left">:</td>
                <td align="left"><?=$data->info_kode_produksi?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">9.&nbsp;&nbsp;&nbsp;Nama, alamat, kode pos </td>
				<td align="left">:</td>
                <td align="left"><?=$data->nama_perusahaan?><div id="titik2"></div></td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dan nomor telepon IRTP</td>
				<td align="left"></td>
                <td align="left"><?=$data->alamat_irtp?><div id="titik2"></div></td>
			</tr>
			
			<tr>
				<td height="33%" class="meta-head style1"></td>
				<td align="left"></td>
                <td align="left"><?=$data->nomor_telefon_irtp?>&nbsp<?=$data->kode_pos_irtp?><div id="titik2"></div></td>
			</tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td height="33%" class="meta-head style1">10. Nama pemilik </td>
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
				<td height="33%" class="meta-head style1">11. Nama penanggung jawab </td>
				<td align="left">:</td>
                <td align="left"><?=$data->nama_penanggung_jawab?><div id="titik2"></div></td>
            </tr>
		</table>	
	</div> 
	<?php endforeach; 
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
            <td align="left"><span class="style7"></span> 
			</td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center">Pemilik/ Penanggungjawab </td>
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
        </tr><tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center">ttd, </td>
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
        </tr><tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center"><u> 
				<!-- <?= (isset($cek_penerbitan[0]['nama_kepala_dinas']))?$cek_penerbitan[0]['nama_kepala_dinas']:'.............................................................' ?>  -->
				<?=$data->nama_pemilik?>
			</u></td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<!-- <tr>
			<td class="meta-head style1" align="center">NIP. ................................ </td>
		</tr> -->
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
	</table>
	</div>
	*) Coret yang tidak perlu 
</div>
</div><!-- header -->
</body>
</html>