<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Edit User</li>
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
						<form action="<?= base_url() ?>manajemen_user/edit" method="post" onsubmit="return cek_edit()" id="manajemen_user">
							<input type="hidden" name="id_user" value="<?= $user->id_user ?>">
							<h1>Edit User</h1>
							<div class="form-group">
								<label for="uname">Username : </label>
								<input type="text" class="form-control" name="uname" id="uname" value="<?= $user->username ?>" />
							</div>
							<div class="form-group">
								<label for="password">Password *minimal 6 karakter: <i style="color: red">*kosongkan jika tidak diganti</i> </label>
								<input type="password" class="form-control" name="password" id="password"/>
								<input type="hidden" name="old_password" value="<?= $user->password ?>">
							</div>
							<?php if ($this->session->userdata('user_segment') != 5): ?>
								<div class="form-group">
									<label for="code">Propinsi : <i style="color: red">*kosongkan jika tidak diganti</i></label>
									<input type="hidden" name="code" value="<?= $user->code ?>">
									<select class="form-control" name="no_kode_propinsi" id="provinsi">
										<option value="0">Pilih Propinsi</option>
										<?php
										foreach ($provinsi as $prov) {
											?>
											<option 
											value="<?php echo $prov->no_kode_propinsi ?>"><?php echo $prov->nama_propinsi ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Kabupaten / Kota <i style="color: red">*kosongkan jika tidak diganti</i></label>
									<select class="form-control" name="nama_kabupaten" id="kota">
										<option value="0">Pilih Kabupaten/kota</option>
										<?php
										foreach ($kota as $kot) {
											?>
											<option   
											class="<?php echo $kot->no_kode_propinsi ?>" value="<?php echo $kot->id_urut_kabupaten ?>"><?php echo $kot->nm_kabupaten ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<?php if ($this->session->userdata('user_segment') == 1 OR $this->session->userdata('user_segment') == 2 OR $this->session->userdata('user_segment') == 3): ?>
								<div class="form-group">
									<label>Hak Akses</label>
									<select name="hak_akses" class="form-control">
										<?php foreach ($hak_akses->result() as $ha): ?>
											<option <?php echo $user->id_r_hak_akses == $ha->id_hak_akses ? 'selected="selected"' : '' ?>   value="<?= $ha->id_hak_akses ?>"><?= $ha->hak_akses ?></option>
										<?php endforeach ?>
									</select>
								</div>
							<?php endif ?>
							<?php else: ?>
								<input type="hidden" name="hak_akses" value="<?= $user->id_r_hak_akses ?>">
								<input type="hidden" name="code" value="<?= $user->code ?>">
								<input type="hidden" name="old_kabupaten" value="<?= $user->code ?>">

							<?php endif ?>

							<div class="form-group">
								<label for="email">Email : </label>
								<input type="email" class="form-control" name="email" id="email" value="<?php echo $user->email ?>"/>
							</div>
							<div class="form-group">
								<label for="nama_unit">Nama Unit : </label>
								<input type="text" class="form-control" name="nama_unit" id="nama_unit" value="<?php echo $user->nama_unit ?>"/>
							</div>
							<div class="form-group">
								<label for="alamat">Alamat : </label>
								<textarea name="alamat" class="form-control" rows="5"><?= $user->alamat ?></textarea>
							</div>
							<div class="form-group">
								<label for="nomor_telpon">Nomor Telpon : </label>
								<input type="number" class="form-control" name="nomor_telpon" id="nomor_telpon" value="<?php echo $user->nomor_telpon ?>"/>
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