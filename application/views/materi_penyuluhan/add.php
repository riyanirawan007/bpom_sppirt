<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Tambah Materi Penyuluhan</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<form action="<?= base_url() ?>materi_penyuluhan/add" method="post" enctype="multipart/form-data">
							<h1>Tambah materi penyuluhan</h1>
							<?= @$this->session->flashdata('errors') ?>
							<div class="form-group">
								<label for="nama_materi_penyuluhan">Nama materi penyuluhan : </label>
								<input type="text" class="form-control" name="nama_materi_penyuluhan" id="nama_materi_penyuluhan">
							</div>
							<div class="form-group">
								<label for="status_materi">Statu materi penyuluhan : </label>
								<select name="status_materi" class="form-control">
									<option value="utama">Utama</option>
									<option value="pendukung">Pendukung</option>
								</select>
							</div>
							<!--<div class="form-group">-->
								<!--	<label for="jenis">Jenis materi penyuluhan : </label>-->
								<!--	<select name="jenis" class="form-control">-->
									<!--		<?php foreach ($jenis_materi as $jenis_materi): ?>-->
									<!--			<option value="<?php echo $jenis_materi->jenis ?>"><?php echo $jenis_materi->jenis ?></option>-->
									<!--		<?php endforeach ?>-->
									<!--	</select>-->
									<!--</div>-->
									<!--<div class="form-group">-->
										<!--	<label for="cluster">Cluster : </label>-->
										<!--	<input type="text" class="form-control" name="cluster" id="cluster">-->
										<!--</div>-->
										<!--<div class="form-group">-->
											<!--	<label for="no_kode_propinsi">Propinsi : </label>-->
											<!--	<select name="no_kode_propinsi" class="form-control" id="propinsi">-->
												<!--		<?php foreach ($propinsi as $propinsi): ?>-->
												<!--			<option value="<?php echo $propinsi->no_kode_propinsi ?>"><?php echo $propinsi->nama_propinsi ?></option>-->
												<!--		<?php endforeach ?>-->
												<!--	</select>-->
												<!--</div>-->
												<!--<div class="form-group">-->
													<!--	<label for="id_urut_kabupaten">Kabupaten/Kota : </label>-->
													<!--	<select name="id_urut_kabupaten" class="id_urut_kabupaten form-control">-->
														<!--		<option value="0">-PILIH-</option>-->
														<!--	</select>-->
														<!--</div>-->
														<div class="form-group">
															<label for="dokumen">Dokumen : </label>
															<input type="file" class="form-control" name="dokumen" id="dokumen" accept="application/pdf">
															<span style="color: red">File PDF, Maksimal ukuran file 12MB</span>
														</div>
														<input type="submit" name="submit" class="btn btn-primary" value="Simpan">
														<a href="<?php echo base_url()?>materi_penyuluhan" class="btn btn-warning"/>Batal</a>
													</form>


												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<script type="text/javascript">
								$(document).ready(function(){
									$('#propinsi').change(function(){
										var id=$(this).val();
										$.ajax({
											url : "<?php echo base_url();?>materi_penyuluhan/get_kab_kota",
											method : "POST",
											data : {id: id},
											async : false,
											dataType : 'json',
											success: function(data){
												var html = '';
												var i;
												for(i=0; i<data.length; i++){
													html += '<option value='+data[i].id_urut_kabupaten+'>'+data[i].nm_kabupaten+'</option>';
												}
												$('.id_urut_kabupaten').html(html);

											}
										});
									});
								});
							</script>