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
	<li class="active">Data List Perpanjangan Sertifikat</li>
</ul>

</div>

<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Data List Perpanjangan Sertifikat
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
							<h3 class="smaller lighter blue">Tabel Data List Perpanjangan Sertifikat</h3>
						</div>
						 <dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'perpanjangan_sertifikat/cetak';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-print bigger-120 blue"></i>
								Export Excel
							</a>
						</dir> 
					</div>

					<div class="table-header">
						Hasil untuk "Data List Perpanjangan Sertifikat"
					</div>

					<div class="table-responsive">
						<table id="data_table" class="table table-striped table-bordered table-hover" >
							<thead>
								<tr>
									<th>No</th>
									<th>Nomor PIRT Lama</th>
									<th>Nomor PIRT Baru</th>
									<th>Nama IRTP</th>
									<th>Nama Jenis Pangan</th>
									<th>Nama Produk Pangan</th>
									<th>Nama Dagang</th>
									<th>Tanggal Perpanjangan</th>
									<th>Nomor Permohonan</th>
									<th>Label Baru</th>
									
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
													$button ='<td>
															<div class="action-buttons">
															'.anchor('perpanjangan_sertifikat/edit/'.$field->id_urut_penerbitan_sert, '<i class="ace-icon fa fa-pencil bigger-130 tooltip-blue" data-rel="tooltip" data-placement="bottom" title="Edit Perpanjangan"></i>
							 										', array("class" => "blue")).'

								                    			'.anchor('perpanjangan_sertifikat/delete/'.$field->id_urut_penerbitan_sert, '<i class="ace-icon fa fa-trash bigger-130 tooltip-red" data-rel="tooltip" data-placement="bottom" title="Hapus Perpanjangan"></i>
																	', array("class" => "red", "onclick"=>"return confirm('Apakah anda yakin ingin menghapus data ini?')")).'
								                    			</div>
								                    		</td>
								                    		';
												}
												else{
													$button = '';
												}
							$label_type = strtolower(pathinfo($field->label_final, PATHINFO_EXTENSION));
							$label_url = base_url('uploads/'.$field->label_final);
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
							echo '
								<tr>
									<td>'.$no.'</td>
									<td>'.$field->nomor_pirt.'</td>
									<td>'.$field->nomor_pirt_baru.'</td>
									<td>'.$field->nama_perusahaan.'</td>
									<td>'.$field->jenis_pangan.'</td>
									<td>'.$field->deskripsi_pangan.'</td>
									<td>'.$field->nama_dagang.'</td>
									<td>'.$field->tanggal_pengajuan_perpanjangan.'</td>
									<td>'.$field->nomor_permohonan.'</td>
									<td>'.$label.'</td>
									
								                    			

									'.$button.'
								                    		
									
								</tr>
							';
							$no++;
							}

							// '.anchor('perpanjangan_sertifikat/edit/'.$field->id_urut_penerbitan_sert, '<i class="ace-icon fa fa-pencil bigger-130 tooltip-blue" data-rel="tooltip" data-placement="bottom" title="Edit Perpanjangan"></i>
							// 										', array("class" => "blue")).'

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


<div class="col-sm-12">
										<div class="row">
											<div class="col-xs-12">
												<div class="widget-box">
													<div class="widget-header widget-header-flat">
														<h4 class="widget-title smaller">Information</h4>

														
													</div>

													<div class="widget-body">
														<div class="widget-main">
															
															<dl id="dt-list-1">
																<dd><?php echo "Jumlah SPP-IRT Yang Mengajukan Perpanjangan : <b>".$jumlah_irtp. " SPP-IRT</b><br>";?></dd>
																<dd><?php echo "Jumlah SPP-IRT Yang Belum Mengajukan Perpanjangan : <b>".$jumlah_irtp_tidak. " SPP-IRT</b><br>";?></dd>
															</dl>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

<div class="row">
	
	
	
	</form>
</div>