<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Rekap Jumlah PKP dan DFI</li>
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
							<h3 class="smaller lighter blue">Tabel Rekap Jumlah PKP dan DFI</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'rekap_pengajuan/cetak';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-print bigger-120 blue"></i>
								Export Excel
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data Rekap Jumlah PKP dan DFI"
					</div>

					<div class="table-responsive">

						<table id="datatable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Jumlah PKP Bersertifikat</th>
									<th>Jumlah PKP tidak bersertifikat</th>
									<th>Jumlah DFI Bersertifikat</th>
									<th>Jumlah DFI Tidak bersertifikat</th>
								</tr>
							</thead>

							<tbody>
								
								<?php
								$no = 1;
								
								?>
								
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>