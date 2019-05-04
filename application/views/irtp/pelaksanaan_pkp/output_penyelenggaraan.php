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
	<li class="active">Data List Pelaksanaan PKP</li>
</ul>

</div>

<div class="page-content">

<?= @$this->session->flashdata('status'); ?>
<?= @$this->session->flashdata('error'); ?>	
<?= @$this->session->flashdata('message'); ?>
<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Data List Pelaksanaan PKP
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
							<h3 class="smaller lighter blue">Tabel Data List Pelaksanaan PKP</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'pelaksanaan_pkp/cetak';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-print bigger-120 blue"></i>
								Export Excel
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data List Pelaksanaan PKP"
					</div>

					<div class="table-responsive">
						<table id="data_table" class="table table-striped table-bordered table-hover" >
							<thead>
								<tr>
									<th>No</th>
									<th>No. Permohonan Penyuluhan</th>
									<th>Tanggal Awal Penyuluhan</th>
									<th>Tanggal Akhir Penyuluhan</th>
									<th>Provinsi</th>
									<th>Kabupaten/Kota</th>
									<!--<th>Nama Narasumber Non PKP</th>-->
									<th>Narasumber Berdasarkan Materi Penyuluhan</th>
									<th>Materi Penyuluhan Tambahan</th>
									<th>Materi Penyuluhan Lainnya</th>
									<th style="width: 10%">Aksi</th>
								</tr>
							</thead>

							<tbody>
							<?php
							$no=1;
							foreach ($datas as $field) {
								if ($this->session->userdata('user_name') == 'admin') {
													$button =
						                    		'
						                    		'.anchor('pelaksanaan_pkp/edit/'.$field->nomor_permohonan_penyuluhan, '<i class="ace-icon fa fa-pencil bigger-130 tooltip-blue" data-rel="tooltip" data-placement="bottom" title="Edit Peserta"></i>
													', array("class" => "blue")).'
						                    		'.anchor('pelaksanaan_pkp/delete/'.$field->nomor_permohonan_penyuluhan, '<i class="ace-icon fa fa-trash bigger-130 tooltip-red" data-rel="tooltip" data-placement="bottom" title="Hapus PKP"></i>
															', array("class" => "red", "onclick"=>"return confirm('Apakah anda yakin ingin menghapus data ini?')")).'';
												}
												else{
													$button = '';
												}
							$get_materi = $this->db->query("select * from tabel_ambil_materi_penyuluhan ambil
							left join tabel_materi_penyuluhan materi on ambil.kode_r_materi_penyuluhan = materi.kode_materi_penyuluhan
							left join tabel_narasumber nara on ambil.kode_r_narasumber = nara.kode_narasumber
							where ambil.nomor_r_permohonan_penyuluhan = '".$field->nomor_permohonan_penyuluhan."'")->result();
							
							/* $no_materi = 0;
							$materi_penyuluhan = "";
							 */
							$arr_materi = array();
							foreach($get_materi as $row){
								//$no_materi++;
								$arr_materi[] = $row->kode_r_materi_penyuluhan;
								//$materi_penyuluhan .= $no_materi.". ".$row->nama_materi_penyuluhan." oleh : ".$row->nama_narasumber."<br>";
								
							}
							$materi_penyuluhan = implode(',', $arr_materi);
							
							
							$xplod_tambahan = explode(",",$field->materi_tambahan);
							$no_materi = 0;
							$materi_tambahan = "";
							foreach($xplod_tambahan as $row_tambahan)
							{
								$get_materi = $this->db->query("select * from tabel_materi_penyuluhan
								where kode_materi_penyuluhan = '".$row_tambahan."'")->result();
								
								foreach($get_materi as $row)
								{
									$no_materi++;
									$materi_tambahan .= $no_materi.". ".$row->nama_materi_penyuluhan."<br>";
								}
							}
							echo '
								<tr>
									<td>'.$no.'</td>
									<td>'.$field->nomor_permohonan_penyuluhan.'</td>
									<td>'.$field->tanggal_pelatihan_awal.'</td>
									<td>'.$field->tanggal_pelatihan_akhir.'</td>
									<td>'.$field->nama_propinsi.'</td>
									<td>'.$field->nm_kabupaten.'</td>
									<td>'.$materi_penyuluhan.'</td>
									<td>'.$materi_tambahan.'</td>
									<td>'.$field->materi_pelatihan_lainnya.'</td>
									<td>
													<div class="action-buttons">
													

													'.anchor('pelaksanaan_pkp/output_laporan_daftar_peserta/'.$field->nomor_permohonan_penyuluhan, '<i class="ace-icon fa fa-user bigger-130 tooltip-success" data-rel="tooltip" data-placement="bottom" title="Daftar Peserta"></i>
													', array("class" => "green", 'target' => '_blank')).'
				                    				
				                    				'.anchor('pelaksanaan_pkp/output_laporan_penyelenggaraan_unduh/'.$field->nomor_permohonan_penyuluhan, '<i class="ace-icon fa fa-print bigger-130 tooltip-info" data-rel="tooltip" data-placement="bottom" title="Laporan"></i>', array("class" => "primary", 'target' => '_blank')).'
									'.$button.'
									</div>
									</td>
								</tr>
							';
							$no++;
							}

							// '.anchor('pelaksanaan_pkp/edit/'.$field->nomor_permohonan_penyuluhan, '<i class="ace-icon fa fa-pencil bigger-130 tooltip-blue" data-rel="tooltip" data-placement="bottom" title="Edit Peserta"></i>
							// 						', array("class" => "blue")).'

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