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
	<li class="active">Data List Pencabutan SPP-IRT</li>
</ul>

</div>

<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Data List Pencabutan SPP-IRT
			</small>
		</h1>
	</div> -->

<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="smaller lighter blue">Tabel Data List Pencabutan SPP-IRT</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'pencabutan_sppirt/cetak';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-print bigger-120 blue"></i>
								Export Excel
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data List Pencabutan SPP-IRT"
					</div>

					<div class="table-responsive">
						<table id="data_table" class="table table-striped table-bordered table-hover" >
							<thead>
								<tr>
									<th>No</th>
									<th>Nomor PIRT</th>
									<th>Nama IRTP</th>
									<th>Jenis Pangan</th>
									<th>Pemilik Perusahaan</th>
									<th>Penanggung Jawab Perusahaan</th>
									<th>Provinsi</th>
									<th>Kabupaten/Kota</th>
									<th>Alasan Pencabutan</th>
									<th>Tanggal Pencabutan</th>
									<th>Scan Berita Acara Pencabutan</th>
									<?php if ($this->session->userdata('user_name') == 'admin') {
														# code...
														$aksi = '<th>aksi</th>';
													}
													else{
														$aksi = '';
													}?>
													<?php echo $aksi;?>
								</tr>
							</thead>

							<tbody>
							<?php
							$no=1;
							foreach ($datas as $field) {
								if ($this->session->userdata('user_name') == 'admin') {
													$button ='
													<td>
													<div class="action-buttons">
													'.anchor('pencabutan_sppirt/edit/'.$field->id_urut_pencabutan_pirt, '<i class="ace-icon fa fa-pencil bigger-130 tooltip-blue" data-rel="tooltip" data-placement="top" title="Edit Pencabutan"></i>
							 							', array("class" => "blue")).'
													
					                    			'.anchor('pencabutan_sppirt/delete/'.$field->id_urut_pencabutan_pirt, '<i class="ace-icon fa fa-trash bigger-130 tooltip-red" data-rel="tooltip" data-placement="bottom" title="Hapus Pencabutan"></i>
														', array("class" => "red", "onclick"=>"return confirm('Apakah anda yakin ingin menghapus data ini?')")).'
					                    			<div>
					                    		</td>
					                    			';
												}
												else{
													$button = '';
												}
							$label_type = strtolower(pathinfo($field->path_scan_pencabutan, PATHINFO_EXTENSION));
							$label_url = base_url('uploads/pencabutan/'.$field->path_scan_pencabutan);
							$label = "-";
							switch ($label_type) {
								case 'png':
								case 'gif':
								case 'jpg':
								case 'jpeg':
									$label = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
									break;

								case 'pdf':
									$label = "<a href='{$label_url}' target='_blank'>Unduh</a>";
									break;
							}

							if($field->kode_alasan_pencabutan!=0){
								$alasan_pencabutan = $field->alasan_pencabutan;
							} else {
								$alasan_pencabutan = $field->alasan_pencabutan_lain;
							}
							echo '
								<tr>
									<td>'.$no.'</td>
									<td>'.$field->nomor_pirt.'</td>
									<td>'.ucwords($field->nama_perusahaan).'</td>
									<td>'.ucwords($field->jenis_pangan).'</td>
									<td>'.ucwords($field->nama_pemilik).'</td>
									<td>'.ucwords($field->nama_penanggung_jawab).'</td>
									<td>'.ucwords($field->nama_propinsi).'</td>
									<td>'.ucwords($field->nm_kabupaten).'</td>
									<td>'.ucwords($alasan_pencabutan).'</td>
									<td>'.substr($field->tanggal_pencabutan,0,10).'</td>
									<td>'.$label.'</td>
									
					                    			

									'.$button.'
								</tr>
							';
							$no++;
							}

							// '.anchor('pencabutan_sppirt/edit/'.$field->id_urut_pencabutan_pirt, '<i class="ace-icon fa fa-pencil bigger-130 tooltip-blue" data-rel="tooltip" data-placement="top" title="Edit Pencabutan"></i>
							// 							', array("class" => "blue")).'

							 //echo $table; 
							?>
							</tbody>
						</table>
						<?php //echo $table; ?>
					</div>
				</div>
			</div>
		</div>
</div>

</div>
