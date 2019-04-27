
<script>
	$(document).ready(function() {
		$('#provinsi').change(function(){
			var provinsi = $(this).val();
			var kabupaten = $('#kabupaten').parents('.dropdown');
			jQuery.ajax({
				url	:	'<?=base_url()?>irtp/get_kabupaten'	,
				type : 'POST',
				dataType : 'json',
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


<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="<?php echo site_url()?>">Dashboard</a>
		</li>
		<li class="active">Data List Permohonan SPP-IRT</li>
	</ul>

</div>


<div class="page-content">

	<!--<div class="page-header">-->
		<!--		<h1>-->
			<!--			Dashboard-->
			<!--			<small>-->
				<!--				<i class="ace-icon fa fa-angle-double-right"></i>-->
				<!--				Data List Permohonan-->
				<!--			</small>-->
				<!--		</h1>-->
				<!--	</div>-->

				<div class="row">
					<div class="col-xs-12">
						<!-- PAGE CONTENT BEGINS -->
						<div class="row">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-6">
										<h3 class="smaller lighter blue">Tabel Data List Permohonan SPP-IRT</h3>
									</div>
									<dir class="col-xs-6" style="text-align: right;">							
										<!--<a id="filter" class="btn btn-white btn-warning btn-bold">-->
											<!--	<i class="ace-icon fa fa-filter bigger-120 orange"></i>-->
											<!--	Filter Search-->
											<!--</a>-->
											<a href="<?php echo base_url().'irtp/cetak';?>" class="btn btn-white btn-info btn-bold">
												<i class="ace-icon fa fa-print bigger-120 blue"></i>
												Export Excel
											</a>
										</dir>
										<form>
											<div class="row" style="margin-left:1em; display:none;" id="form">

												<div class="col-md-3" style="margin-bottom:5px;">
													<label class="control-label">No Permohonan</label><br>
													<select class="select2" style="max-width:80%;">
														<option value="">-</option>
														
													</select>         
												</div>
												<div class="col-md-3" style="margin-bottom:5px;">
													<label class="control-label">No Permohonan</label><br>
													<select class="select2" style="max-width:80%;">
														<option value="">-</option>
														
													</select>         
												</div>
												<div class="col-md-3" style="margin-bottom:5px;">
													<label class="control-label">No Permohonan</label><br>
													<select class="select2" style="max-width:80%;">
														<option value="">-</option>
														
													</select>         
												</div>
												<div class="col-md-3" style="margin-bottom:5px;">
													<label class="control-label">No Permohonan</label><br>
													<select class="select2" style="max-width:80%;">
														<option value="">-</option>
														
													</select>         
												</div>
												
											</div>
										</form>
										
										<br>
									</div>

									<div class="table-header">
										Hasil untuk "Data List Permohonan SPP-IRT"
									</div>

									<div class="table-responsive">
										<table id="data_table" class="table table-striped table-bordered table-hover" >
											<thead>
												<tr>
													<th>No</th>
													<th>No Permohonan</th>
													<th>Nama IRTP</th>
													<th>Nama Pemilik IRTP</th>
													<th>Nama Penanggung Jawab IRTP</th>
													<th>No. Telepon</th>
													<th width="13%">Provinsi</th>
													<th>Nama Produk Pangan</th>
													<th>Komposisi Produk Pangan</th>
													<th>Tanggal Pengajuan</th>
													<th>aksi</th>
													
												</tr>
											</thead>

											<tbody>
												<?php
												$no=1;
												foreach ($datas as $field) {
													if ($this->session->userdata('user_name') == 'admin') {
													$button ='
													'.anchor('irtp/delete/'.$field->id_pengajuan, '<i class="ace-icon fa fa-trash bigger-130 tooltip-red" data-rel="tooltip" data-placement="bottom" title="Hapus Pengajuan"></i>
													', array("class" => "red", "onclick"=>"return confirm('Apakah anda yakin ingin menghapus data ini?')")).'
							                    		';
												}
												else{
													$button = '';
												}
													$get_tambahan = $this->db->query("select * from tabel_ambil_komposisi_tambahan a left join tabel_bahan_tambahan_pangan b on a.kode_r_komposisi = b.no_urut_btp where nomor_r_permohonan = '".$field->nomor_permohonan."'")->result_array();
													$tambahan = "";
													foreach($get_tambahan as $val){
														if($val['berat']==""){ $berat=0; } else { $berat = $val['berat']; }
														$tambahan .=$val['nama_bahan_tambahan_pangan']." : ".$berat."<br><br>";
													}
													
								//scan alur_produksi
													$label_type = strtolower(pathinfo($field->path_ap, PATHINFO_EXTENSION));
													$label_url = base_url('uploads/alur_produksi/'.$field->path_ap);
													$label_ap = "-";
													switch ($label_type) {
														case 'png':
														case 'gif':
														case 'jpg':
														case 'jpeg':
														$label_ap = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
														break;

														case 'pdf':
														$label_ap = "<a href='{$label_url}' target='_blank'>Unduh</a>";
														break;
													}
													
								//scan siup
													$label_type = strtolower(pathinfo($field->path_siup, PATHINFO_EXTENSION));
													$label_url = base_url('uploads/siup/'.$field->path_siup);
													$label_siup = "-";
													switch ($label_type) {
														case 'png':
														case 'gif':
														case 'jpg':
														case 'jpeg':
														$label_siup = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
														break;

														case 'pdf':
														$label_siup = "<a href='{$label_url}' target='_blank'>Unduh</a>";
														break;
													}
													
								//scan siup
													$label_type = strtolower(pathinfo($field->path_rl, PATHINFO_EXTENSION));
													$label_url = base_url('uploads/rancangan_label/'.$field->path_rl);
													$label_rl = "-";
													switch ($label_type) {
														case 'png':
														case 'gif':
														case 'jpg':
														case 'jpeg':
														$label_rl = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
														break;

														case 'pdf':
														$label_rl = "<a href='{$label_url}' target='_blank'>Unduh</a>";
														break;
													}
													
								//Jenis Kemasan
													if($field->kode_r_kemasan==6){
														$jenis_kemasan = $field->jenis_kemasan_lain;
													} else {
														$jenis_kemasan = $field->jenis_kemasan;
													}
													
								//Proses Produksi
													if($field->kode_r_tek_olah==11){
														$proses_produksi = $field->proses_produksi_lain;
													} else {
														$proses_produksi = $field->tek_olah;
													}
													echo '
													<tr>
													<td>'.$no.'</td>
													<td>'.$field->nomor_permohonan.'</td>
													<td>'.$field->nama_perusahaan.'</td>
													<td>'.$field->nama_pemilik.'</td>
													<td>'.$field->nama_penanggung_jawab.'</td>
													<td>'.$field->nomor_telefon_irtp.'</td>
													<td>'.$field->nama_propinsi.'</td>
													<td>'.$field->deskripsi_pangan.'</td>
													<td>'.$field->komposisi_utama.'</td>
													<td>'.$field->tanggal_pengajuan.'</td>
													<td>
													<div class="action-buttons">
														'.anchor('irtp/detail_permohonan/'.$field->nomor_permohonan, '<i class="ace-icon fa fa-eye bigger-130 tooltip-info"  title="Detail"></i>', array("class" => "primary")).'
													
													'.anchor('irtp/edit/'.$field->nomor_permohonan, '<i class="ace-icon fa fa-pencil bigger-130 tooltip-default"  title="Edit"></i>', array("class" => "default")).'
													
													'.anchor('irtp/output_laporan_permohonan_unduh/'.$field->nomor_permohonan, '<i class="ace-icon fa fa-print bigger-130 tooltip-success"  title="Cetak"></i>
														', array("class" => "green", 'target' => '_blank')).'
													
													'.anchor('irtp/download_zip/'.$field->nomor_permohonan, '<i class="ace-icon fa fa-archive bigger-130 tooltip-warning"  title="Unduh sebagai zip"></i>
														', array("class" => "yellow", 'target' => '_blank')).'
													'.$button.'
													</div>
													</td>
													
													</tr>
													';
													$no++;
												}

							 //echo $table; 
												?>
											</tbody>
										</table>
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
	
	data = { 0 : dataPangan, 1 : dataKemasan};
	dataAttr = {
		0 : { 0 : 'Jenis Pangan', 1 : 'Nama Jenis Pangan'}, 
		1 : { 0 : 'Jenis Kemasan',1 : 'Nama Jenis Kemasan'}
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