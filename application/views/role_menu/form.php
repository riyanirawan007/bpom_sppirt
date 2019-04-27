<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="dashboard">Dashboard</a>
	</li>
	<li>
		<a href="<?php echo base_url().'role_menu';?>">Data Role Menu</a>
	</li>
	<li class="active"><?php if($id_role_for_edit!=0){echo 'Edit Data';}else { echo 'Tambah Data';}?></li>
</ul>

</div>

<div class="page-content">

	<div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Data Role Menu
			</small>
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				<?php if($id_role_for_edit!=0){echo 'Edit Data';}else { echo 'Tambah Data';}?>
			</small>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" id="form-rolemenu">
				<input type="text" name="id_role_menu" id="id_role_menu" hidden="">
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="id_role_user">Role User<sup style="color:red">*</sup></label>

					<div class="col-xs-12 col-sm-9">
						<select id="id_role_user" name="id_role_user" class="select2" data-placeholder="Pilih Role User..." title="Role User">
							<option value="">&nbsp;</option>
							<?php foreach($roles_user as $role){
								echo '<option value="'.$role['id_hak_akses'].'">'.$role['hak_akses'].'</option>';
							}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="id_menu">Menu<sup style="color:red">*</sup></label>

					<div class="col-xs-12 col-sm-9">
						<select id="id_menu" name="id_menu" onchange="levelChange(this.value)" class="select2" data-placeholder="Pilih Menu..." title="Menu">
							<option value=""></option>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div id="container-sort-menu" class="form-group" hidden="">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="sort_index">Penempatan<sup style="color:red">*</sup></label>
					<div class="col-xs-12 col-sm-9">
						<select id="sort_index" name="sort_index" class="select2" data-placeholder="Pilih Penempatan Menu..." title="Penempatan Menu">
							<option value=""></option>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="active_stat">Status Menu<sup style="color:red">*</sup></label>

					<div class="col-xs-12 col-sm-9">
						<select id="active_stat" name="active_stat" class="select2" data-placeholder="Pilih Status Menu.." title="Status Menu">
							<option value="1">Aktif</option>
							<option value="0">Tidak Aktif</option>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<h6 class="control-label col-xs-12 col-sm-3 no-padding-right"><sup style="color:red">*</sup> Required </h6>
				<br>
				<br>
				<div class="space-2"></div>
				<div class="hr hr-dotted"></div>

				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="submit">
							<i class="ace-icon fa fa-check bigger-110"></i>
							<?php $label=''; ($id_role_for_edit!=0)? $label='Edit':$label='Tambah'; echo $label;?>
						</button>

						&nbsp; &nbsp; &nbsp;
						<a href="<?php echo base_url('role_menu'); ?>" class="btn btn-danger">
							<i class="ace-icon fa fa-remove bigger-110"></i>
							Batal
						</a>

						&nbsp; &nbsp; &nbsp;
						<button class="btn" type="reset">
							<i class="ace-icon fa fa-undo bigger-110"></i>
							Reset
						</button>
					</div>
				</div>

			</form>
			<!-- PAGE CONTENT ENDS -->
		</div>
	</div>
</div>
<script>var id_role_for_edit=<?php echo $id_role_for_edit;?></script>
<script src="<?php echo base_url()?>js/pages/role_menu/form.js"></script>
