<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data User</li>
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
							<h3 class="smaller lighter blue">Tabel Data User</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'manajemen_user/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah User
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data User"
					</div>

					<div class="table-responsive">

						<table id="datatable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Username</th>
									<th>Propinsi</th>
									<th>Level</th>
									<th>Email</th>
									<th>Nama Unit</th>
									<th>Alamat</th>
									<th>No Telpon</th>
									<th width="10%">Aksi</th>
								</tr>
							</thead>

							<tbody>

								<?php
								$no = 1;
								foreach ($manajemen_user as $mu) {
									echo '
									<tr>
									<td>'.$no.'</td>
									<td>'.$mu->username.'</td>
									<td>'.$mu->nama_propinsi.'</td>
									<td>'.$mu->hak_akses.'</td>
									<td>'.$mu->email.'</td>
									<td>'.$mu->nama_unit.'</td>
									<td>'.$mu->alamat.'</td>
									<td>'.$mu->nomor_telpon.'</td>

									<td>
									<div class="hidden-sm hidden-xs action-buttons">

									'.anchor('manajemen_user/edit/'.$mu->id_user, '<i class="ace-icon fa fa-pencil bigger-130"></i>', array("class" => "green")).'

									'.anchor('manajemen_user/delete/'.$mu->id_user, '<i class="ace-icon fa fa-trash-o bigger-130"></i>', array("class" => "red", "onclick" => "return confirm('Hapus data?')")).'
									</div>
									</td>
									</tr>
									';
									$no++;
								}
								?>

							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>