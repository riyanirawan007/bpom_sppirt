<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="dashboard">Dashboard</a>
	</li>
	<li class="active">Data Menu</li>
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
							<h3 class="smaller lighter blue">Tabel Data Menu</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Menu
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data Menu"
					</div>

					<div class="table-responsive">
						<table id="table-menu" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Judul</th>
									<th>Halaman</th>
									<th>Level</th>
									<th>Parent</th>
									<th>Tipe Link</th>
									<th>Link</th>
									<th>FA Icon</th>
									<th>Sort Index</th>
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
<script src="<?php echo base_url()?>js/pages/menu/main.js"></script>