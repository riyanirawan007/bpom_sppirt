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
		<h1 class="style6">DAFTAR PESERTA</h1>
		<h1 class="style6">PENYULUHAN KEAMANAN PANGAN DALAM RANGKA PEMBERIAN</h1>
		<h1 class="style6">SPP-IRT</h1>
		
		<table cellpadding="5" bordercolor="#000" border="1px" bgcolor="#FFF" id="meta" style="border-collapse: collapse">
			<tr>
				<td class="meta-head style1">NO </td>
				<td class="meta-head style1">NAMA </td>
				<td class="meta-head style1">JABATAN (PEMILIK / PENANGGUNGJAWAB) </td>
				<td class="meta-head style1">SERTIFIKAT PKP NO. </td>
				<td class="meta-head style1">NAMA DAN ALAMAT IRTP</td>
                <td class="meta-head style1">NILAI<div id="titik2"></div></td>
            </tr>
   			<?php $kota = ""; $no = 1; foreach($query as $row):
			$kota = $row->nm_kabupaten;
			if($row->nilai_post_test>=60){
				$keterangan = "Lulus";
			} else {
				$keterangan = "Tidak Lulus";
			}
			?>
			
			<tr>
				<td class="meta-head style1"><?php echo $no."."; $no++;?></td>
				<td class="meta-head style1">
					<?=$row->nama_peserta?>
				</td>
				<td class="meta-head style1">
					<?php
					if($row->status_peserta == 0){
						echo "Pemilik";
					} else if($row->status_peserta == 1){
						echo "Penanggung Jawab";
					} else {
						echo "Lainnya";
					}
					?>
				</td>
				<td class="meta-head style1"><?=$row->no_sert_pangan?></td>
				<td class="meta-head style1"><?=$row->nama_perusahaan." ".$row->alamat_irtp?></td>
                <td class="meta-head style1"><?=$keterangan?><div id="titik2"></div></td>
            </tr>
			<?php endforeach; 
			$kota = str_replace("Kota","",$kota); $kota = str_replace("Kab.","",$kota);
			$kota2 = (isset($cek_penerbitan[0]['nm_kabupaten']))?$cek_penerbitan[0]['nm_kabupaten']:'..........';
			$kota2 = str_replace("Kota","",$kota2); $kota2 = str_replace("Kab.","",$kota2);
			?>
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

</body>
</html>