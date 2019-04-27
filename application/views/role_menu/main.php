<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="dashboard">Dashboard</a>
	</li>
	<li class="active">Data Role Menu</li>
</ul>

</div>

<div class="page-content">

	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="smaller lighter blue">Tabel Data Role Menu</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'role_menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Role Menu
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data Role Menu"
					</div>

					<div class="table-responsive">
						<table id="table-rolemenu" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Role User</th>
									<th>Menu</th>
									<th>Status Aktif</th>
									<th>Aksi</th>
								</tr>
							</thead>

							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- PAGE CONTENT ENDS -->
		</div>
	</div>
</div>
<script src="<?php echo base_url()?>js/pages/role_menu/main.js"></script>