<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Masa Berlaku Sertifikat</li>
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
							<h3 class="smaller lighter blue">Tabel Data Masa Berlaku Sertifikat</h3>
						</div>
					</div>

					<div class="table-header">
						Hasil untuk "Masa Berlaku Sertifikat"
					</div>

					<div class="table-responsive">

						<table id="table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Nomor Permohonan</th>
									<th>Nomor PIRT</th>
									<th>Tanggal Pemberian PIRT</th>
									<th>Nomor HK</th>
									<th>Nama Kepala Dinas</th>
									<th>Tanggal Berakhir Sertifikat</th>
									<th>Status Sertifikat</th>
								</tr>
							</thead>

							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#table').DataTable({
			"ajax": {
				url : "<?php echo base_url('sertifikat_cek/data_sertifikat')?>",
				type : 'GET'
			},
		});
	});
</script>

