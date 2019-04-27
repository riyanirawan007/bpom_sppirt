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
	<!-- <div id="image2" style="text-align:center">
	    <img src="<?= base_url() ?>images/garuda.png" alt="logo" name="image" width="53" height="50"  /><br>
		<span style="font-size:12px; font-weight:bold">
			BADAN PENGAWAS OBAT DAN MAKANAN<br>
			REPUBLIK INDONESIA

		</span>
	</div> -->
<div id="header">
	<div align="center">
		<p>&nbsp;</p>
		<h1 class="style6">LAPORAN PENYELENGGARAAN</h1>
		<h1 class="style6">PENYULUHAN KEAMANAN PANGAN DALAM RANGKA PEMBERIAN</h1>
		<h1 class="style6">SPP-IRT</h1>
		<?php 
			
			$kota = ""; $no = 0; foreach($query as $row):
			$kota = $row->nm_kabupaten;
			$tanggal_awal = $row->tanggal_pelatihan_awal;
			$tanggal_akhir = $row->tanggal_pelatihan_akhir;
			$no++;
			endforeach; 
			$kota = str_replace("Kota","",$kota); $kota = str_replace("Kab.","",$kota);
			$kota2 = (isset($cek_penerbitan[0]['nm_kabupaten']))?$cek_penerbitan[0]['nm_kabupaten']:'..........';
			$kota2 = str_replace("Kota","",$kota2); $kota2 = str_replace("Kab.","",$kota2);
			?>
		<table bordercolor="#FFFFFF" bgcolor="#FFFFFF" id="meta" >
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr> 
				<td colspan="3"  height="33%" class="meta-head style1" align="justify">
					Berdasarkan Peraturan Badan Pengawas obat dan Makanan RI Nomor 22 pada tahun 2018 tentang Pedoman Pemberian SPP-IRT, Pemerintah Kabupaten/Kota*) <?= $kota ?> 
					cq. Dinas Kesehatan Kab/Kota <?= $kota ?> 
					telah menyelenggarakan Penyuluhan Keamanan Pangan (PKP) 
					dalam rangka Pemberian SPP-IRT pada tanggal <?= format_tanggal(date("d", strtotime($tanggal_awal)), date("m", strtotime($tanggal_awal)), date("Y", strtotime($tanggal_awal))) ?> s/d <?= format_tanggal(date("d", strtotime($tanggal_akhir)), date("m", strtotime($tanggal_akhir)), date("Y", strtotime($tanggal_akhir))) ?> 
					dengan jumlah peserta <?= $jumlah_peserta ?> orang. 
				</td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td colspan="3"  height="33%" class="meta-head style1" align="justify">Kepada pemilik / penanggungjawab IRTP yang mengikuti PKP dalam rangka pemberian SPP-IRT dengan baik telah diberikan Sertifikat Penyuluhan Keamanan Pangan (PKP) Nomor ............... s/d ............ </td>
            </tr>
			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td colspan="3"  height="33%" class="meta-head style1" align="justify">Sedangkan kepada IRTP yang produk pangannya telah memenuhi persyaratan SPPIRT telah diberikan SPP-IRT seperti terlampir. </td>
            </tr>
            
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
			
			<tr>
				<td colspan="3" height="33%" class="meta-head style1" align="justify">Penyelenggaraan PKP dan pemberian SPP-IRT telah sesuai. </td>
            </tr>
			
   			
			<tr>
				<td colspan="2">
                <td align="left"><span class="style7"></span> 
					<div id="titik2"></div>
				</td>
            </tr>
		</table>	
	<br>
	<div id="hormat" align="right">
	<table>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span> 
			</td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center"><?= ($kota!="")?$kota:$kota2 ?>, <?= tanggal() ?> </td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center">DINAS KESEHATAN KAB/KOTA <?= ($kota!="")?strtoupper($kota):strtoupper($kota2) ?> </td>
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
			<td class="meta-head style1" align="center"><u> ............................................................. </u></td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
		<tr>
			<td class="meta-head style1" align="center">NIP. ................................ </td>
		</tr>
		<tr>
			<td colspan="2">
            <td align="left"><span class="style7"></span></td>
        </tr>
	</table>
	</div>
	</div>
	*) Coret yang tidak perlu 
	</div>
	</div>
	

</body>
</html>