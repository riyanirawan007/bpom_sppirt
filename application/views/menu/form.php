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
		<a href="<?php echo base_url().'menu';?>">Data Menu</a>
	</li>
	<li class="active"><?php if($id_menu_for_edit!=0){echo 'Edit Data';}else { echo 'Tambah Data';}?></li>
</ul>

</div>

<div class="page-content">

	<div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Data Menu
			</small>
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				<?php if($id_menu_for_edit!=0){echo 'Edit Data';}else { echo 'Tambah Data';}?>
			</small>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" id="form-menu">
				<input type="text" name="id_menu" id="id_menu" hidden="">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="title">Judul<sup style="color:red">*</sup></label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" name="title" id="title" placeholder="Judul Menu" class="col-xs-12 col-sm-6" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="place">Halaman Menu<sup style="color:red">*</sup></label>

					<div class="col-xs-12 col-sm-9">
						<select id="place" name="place" class="select2" disabled="" data-placeholder="Pilih Halaman Menu..." title="Halaman Menu">
							<option value="">&nbsp;</option>
							<?php foreach($places as $place){
								echo '<option value="'.$place.'">'.$place.'</option>';
							}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="level">Level Menu<sup style="color:red">*</sup></label>

					<div class="col-xs-12 col-sm-9">
						<select id="level" name="level" onchange="levelChange(this.value)" class="select2" data-placeholder="Pilih Level Menu..." title="Level Menu">
							<?php foreach($levels as $level){
								echo '<option value="'.$level.'">'.$level.'</option>';
							}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div id="container-parent-menu" class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="parent_menu_id">Parent Menu<sup style="color:red">*</sup></label>
					<div class="col-xs-12 col-sm-9">
						<select id="parent_menu_id" name="parent_menu_id" class="select2" data-placeholder="Pilih Parent Menu..." title="Parent Menu">
						<option value="0" selected >This is Parent</option>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<!-- <div id="container-sort-index" class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="sort_index">Sort Index<sup style="color:red">*</sup></label>
					<div class="col-xs-12 col-sm-9">
						<select id="sort_index" name="sort_index" class="select2" data-placeholder="Pilih Sort Index..." title="Parent Menu">
						<option value="0" selected >First</option>
						</select>
					</div>
				</div>
				<div class="space-2"></div> -->

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="link_type">Tipe Link<sup style="color:red">*</sup></label>

					<div class="col-xs-12 col-sm-9">
						<select id="link_type" name="link_type" class="select2" data-placeholder="Pilih Tipe Link.." title="Tipe Link">
							<option value="">&nbsp;</option>
							<?php foreach($link_types as $link_type){
								echo '<option value="'.$link_type.'">'.$link_type.'</option>';
							}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="link">Link</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" name="link" id="link" placeholder="Link Menu" class="col-xs-12 col-sm-6" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="fa_icon">Font Awesome Icon</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" onkeyup="prevIcon(this.value)" name="fa_icon" id="fa_icon" placeholder="Font Awesome Icon (fa-*)" class="col-xs-12 col-sm-6" /> <span style="font-size: 1.5em" class="col-xs-12 col-sm-6"><i id="prev_icon"/></span>
						</div>
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
							<?php $label=''; ($id_menu_for_edit!=0)? $label='Edit':$label='Tambah'; echo $label;?>
						</button>


						&nbsp; &nbsp; &nbsp;
						<a href="<?php echo base_url('menu'); ?>" class="btn btn-danger">
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
<script>var id_menu_for_edit=<?php echo $id_menu_for_edit;?></script>
<script src="<?php echo base_url()?>js/pages/menu/form.js"></script>
