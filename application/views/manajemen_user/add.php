<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Tambah User</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<?= @$this->session->flashdata('message') ?>
						<?= @$this->session->flashdata('errors') ?>
						<form action="<?= base_url() ?>manajemen_user/add" method="post" onsubmit="return cek_add()" id="manajemen_user">
							<h1>Tambah User</h1>
							<div class="form-group">
								<label for="uname">Username : </label>
								<input type="text" class="form-control" name="uname" id="uname"/>
							</div>
							<div class="form-group">
								<label for="password">Password *minimal 6 karakter : </label>
								<input type="password" class="form-control" name="password" id="password"/>
							</div>
							<div class="form-group">
								<label for="code">Propinsi : </label>
								<select class="form-control" name="no_kode_propinsi" id="provinsi">
									<option value="">Pilih Propinsi</option>
									<?php
									foreach ($provinsi as $prov) {
										?>
										<option value="<?php echo $prov->no_kode_propinsi ?>"><?php echo $prov->nama_propinsi ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>Kabupaten / Kota</label>
								<select class="form-control" name="nama_kabupaten" id="kota">
									<option value="0">Pilih Kabupaten/kota</option>
									<?php
									foreach ($kota as $kot) {
										?>
										<!--di sini kita tambahkan class berisi id provinsi-->
										<option class="<?php echo $kot->no_kode_propinsi ?>" value="<?php echo $kot->id_urut_kabupaten ?>"><?php echo $kot->nm_kabupaten ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>Hak Akses</label>
								<select name="hak_akses" class="form-control">
									<?php 
									foreach ($hak_akses->result() as $ha) {
										echo "<option value='$ha->id_hak_akses'>$ha->hak_akses</option>";
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="email">Email : </label>
								<input type="email" class="form-control" name="email" id="email"/>
							</div>
							<div class="form-group">
								<label for="nama_unit">Nama Unit : </label>
								<input type="text" class="form-control" name="nama_unit" id="nama_unit"/>
							</div>
							<div class="form-group">
								<label for="alamat">Alamat : </label>
								<textarea name="alamat" class="form-control" rows="5"></textarea>
							</div>
							<div class="form-group">
								<label for="nomor_telpon">Nomor Telpon : </label>
								<input type="number" class="form-control" name="nomor_telpon" id="nomor_telpon"/>
							</div>
							<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
							<a href="<?php echo base_url()?>manajemen_user" class="btn btn-warning"/>Batal</a>
						</form>

						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function()
	{
		$("#manajemen_user").validate(
		{
			rules: 
			{
				password: 
				{
					required: true,
					minlength:6
				}
			}
		});	
	});
</script>