<script>
$(document).ready(function() {
	$('#provinsi').change(function(){
			var provinsi = $(this).val();
			var kabupaten = $('#kabupaten').parents('.dropdown');
			jQuery.ajax({
				url	:	'<?=base_url()?>irtp/get_kabupaten'	,
				type  :  'POST',
				dataType  :  'json',
				data	: 'provinsi=' + provinsi,
				success: function(html){
					var temp;
					$.each(html, function(val, key){
						temp += "<option value='" + key.id_urut_kabupaten + "'>" + key.no_kabupaten + ". " + key.nm_kabupaten + "</option>";
					});				
					
					console.log($('#kabupaten').html(temp));
					$('#kabupaten').trigger('liszt:updated').chosen();
					
				},error: function(e){
					console.log(e);
				}
			});	
		});
});
</script>



<div class="page-content">

<div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Data List Detail Permohonan
			</small>
		</h1>
	</div>

<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="smaller lighter blue">Tabel Data List Detail Permohonan</h3>
						</div>
						<!-- <dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'role_menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Role Menu
							</a>
						</dir> -->
					</div>

					<div class="widget-box widget-color-blue">
						<div class="widget-header">
							<h6 class="widget-title">Hasil untuk "Data List Detail Permohonan"</h6>
						</div>

					<?php foreach($query as $data): 
					$kota = $data->nm_kabupaten;
					?>
					<div class="alert">
					
					<table>
						<tr>
							<td style="width: 40%;"><label >No. Permohonan </label></td>
							<td><label >  : <b><?=$data->nomor_permohonan?></b></label></td>
						</tr>
						<tr>
							<td><label >Nama IRTP </label></td>
							<td><label >  : <b><?=$data->nama_perusahaan?></b></label></td>
						</tr>
						<tr>
							<td><label >Nama Pemilik IRTP </label></td>
							<td><label >  : <b><?=$data->nama_pemilik?></b></label></td>
						</tr>
						<tr>
							<td><label >Nama Penanggung Jawab IRTP </label></td>
							<td><label >  : <b><?=$data->nama_penanggung_jawab?></b></label></td>
						</tr>
						<tr>
							<td><label >Alamat IRTP </label>
							</td>
							<td><label >  : <b><?=$data->alamat_irtp?></b></label></td>
						</tr>
						<tr>
							<td><label >Provinsi </label>
							</td>
							<td><label >  : <b><?=$data->nama_propinsi?></b></label></td>
						</tr>
						<tr>
							<td><label >Kabupaten/Kota </label>
							</td>
							<td><label >  : <b><?=$data->nm_kabupaten?></b></label></td>
						</tr>
						<tr>
							<td><label >Kode Pos </label>
							</td>
							<td><label >  : <b><?=$data->kode_pos_irtp?></b></label></td>
						</tr>
						<tr>
							<td><label >No. Telepon </label>
							</td>
							<td><label >  : <b><?=$data->nomor_telefon_irtp?></b></label></td>
						</tr>
						<tr>
							<td><label >Nama Jenis Pangan </label>
							</td>
							<td><label >  : <b><?=$data->jenis_pangan?></b></label></td>
						</tr>
						<tr>
							<td><label >Nama Produk Pangan </label>
							</td>
							<td><label >  : <b><?=$data->deskripsi_pangan?></b></label></td>
						</tr>
						<tr>
							<td><label >Nama Dagang: </label>
							</td>
							<td><label >  : <b><?=$data->nama_dagang?></b></label></td>
						</tr>
						<tr>
							<td><label >Jenis Kemasan </label>
							</td>
							<td><label >  : <b><?=$data->jenis_kemasan?></b></label></td>
						</tr>
						<tr>
							<td><label >Berat Bersih </label>
							</td>
							<td><label >  : <b><?=$data->berat_bersih?></b></label></td>
						</tr>
						<tr>
							<td><label >Komposisi </label>
							</td>
							<td><label >  : <b><?=$data->komposisi_utama?></b></label></td>
						</tr>
						<tr>
							<td><label >Masa Simpan </label>
							</td>
							<td><label >  : <b><?=$data->masa_simpan?></b></label></td>
						</tr>
						<tr>
							<td><label >Kode Produksi </label>
							</td>
							<td><label >  : <b><?=$data->info_kode_produksi?></b></label></td>
						</tr>
						<tr>
							<td><label >Tanggal Pengajuan </label>
							
							</td>
							<td><label >  : <b><?=$data->tanggal_pengajuan?></b></label></td>
						</tr>

					</table>
					<br>
					<a href="<?php echo base_url('irtp/output_permohonan'); ?>" class="btn btn-danger">
							<i class="ace-icon fa fa-reply bigger-110"></i>
							Kembali
						</a>
					
					</div>
					

					</div>


				
					<?php endforeach; 
					$kota = str_replace("Kota","",$kota); $kota = str_replace("Kab.","",$kota);
					?>
				</div>

			</div>
		</div>
</div>

</div>
</div>
									
	


<script src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}" language='javascript1.4'></script>
<!-- <script>
jQuery(document).ready(function(){
	jQuery('.datetimepicker').datetimepicker();
	
	var dataPangan = <?php
		// echo "[['Jenis Pangan', 'Jumlah Jenis Pangan'],";
		// $i = 0;
		// $chart = "";
		// foreach($jml_pangan as $data){
		// 	$chart .= "['".$data->jenis_pangan."', ".$data->count_jenis."], ";
		// 	$i++;
		// }
		// echo $chart;
		// echo "]";
	?>;
	var dataKemasan = <?php
		// echo "[['Jenis Kemasan', 'Jumlah Kemasan'],";
		// $i = 0;
		// $chart = "";
		// foreach($jml_kemasan as $data){
		// 	$chart .= "['".$data->jenis_kemasan."', ".$data->count_kemasan."], ";
		// 	$i++;
		// }
		// echo $chart;
		// echo "]";
	?>;
	var obj = jQuery('.dom');
	var data = new Array();
	
	data = { 0  :  dataPangan, 1  :  dataKemasan};
	dataAttr = {
		0  :  { 0  :  'Jenis Pangan', 1  :  'Nama Jenis Pangan'}, 
		1  :  { 0  :  'Jenis Kemasan',1  :  'Nama Jenis Kemasan'}
	};
	var getId;
	var i = 0;
	jQuery.each(obj, function(key, val){
		getId = jQuery(val).attr('id');		
		drawChart(data[i], dataAttr[i], getId);
		i++;
	});
	
	i = 0;
	//google.load("visualization", "1", {packages:["corechart"]});
	function drawChart(data, dataAttr, obj) {
		var data = google.visualization.arrayToDataTable(data);

		var options = {
		  title: dataAttr[0],
		  hAxis: {title: dataAttr[1], titleTextStyle: {color: 'red'}}
		};

		var chart = new google.visualization.ColumnChart(document.getElementById(obj));
		chart.draw(data, options);
	}
});	
</script> -->