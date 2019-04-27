<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="<?php echo site_url()?>">Dahboard</a>
		</li>

		<li class="active">User Profile</li>
	</ul><!-- /.breadcrumb -->


</div>
<div class="page-content">



	<div class="row">
		<div class="col-xs-12">
			<br>

			<div class="">
				<div id="user-profile-1" class="user-profile row">
					<div class="col-xs-12 col-sm-3 center">
						<div>
							<span class="profile-picture">
								<?php if ($user->picture == ""): ?>
									<center><img id="avatar" class="editable img-responsive editable-click editable-empty" alt="Alex's Avatar" src="<?php echo base_url()?>assets/dashboard/ace-master/assets/avatars/profile-pic.jpg">
										<?php else: ?>
											<img id="avatar" class="editable img-responsive editable-click editable-empty" alt="Alex's Avatar" src="<?php echo base_url('foto/'.$user->picture)?>"></center>
										<?php endif ?>
										<form enctype="multipart/form-data" method="post" action="<?= base_url('manajemen_user/edit') ?>">
											<input type="hidden" name="id_user" value="<?= $user->id_user ?>">
											<div class="form-group">
												<label>Ganti Foto</label>
												<input type="file" name="userfile"> 
											</div>
											<div class="form-group">
												<button type="submit" name="foto" class="btn btn-primary">Simpan</button>
											</div>
										</form>

									</span>

									<div class="space-4"></div>

									<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
										<div class="inline position-relative">
											<a href="#!" class="user-title-label">
												<i class="ace-icon fa fa-circle light-green"></i>
												&nbsp;
												<span class="white"><?= $user->username ?></span>
											</a>
										</div>
									</div>
								</div>

								<div class="space-6"></div>


							</div>

							<div class="col-xs-12 col-sm-9">


								<div class="space-12"></div>

								<div class="profile-user-info profile-user-info-striped">
									<div style="padding: 22px;">
										<form action="<?= base_url() ?>manajemen_user/edit" method="post" onsubmit="return cek_edit()" id="manajemen_user">
											<input type="hidden" name="id_user" value="<?= $user->id_user ?>">
											<input type="hidden" name="picture" value="<?= $user->picture ?>">

											<h1>Edit User</h1>
											<div class="form-group">
												<label for="uname">Username : </label>
												<input type="text" class="form-control" name="uname" id="uname" value="<?= $user->username ?>" />
											</div>
											<div class="form-group">
												<label for="password">Password : <i style="color: red">*kosongkan jika tidak diganti</i> </label>
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
											<?php if($this->session->userdata('user_segment')==1)
											{
												$a ='<a href="'.base_url().'manajemen_user" class="btn btn-warning"/>Batal</a>';  
											}
											else
											{
												$a ='<a href="'.base_url().'dashboard" class="btn btn-warning"/>Batal</a>';
											}
											echo $a;
											?>
											
											
											<br>
											<br>
										</form>
									</div>
								</div>

								<div class="space-20"></div>



								<!--<div class="hr hr2 hr-double"></div>-->
								<!--<div class="space-6"></div>-->

							</div>
						</div>
					</div>



					<!-- PAGE CONTENT ENDS -->
				</div><!-- /.col -->
			</div><!-- /.row -->
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