<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Input Data Pemeriksaan Sarana</li>
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
							<h3 class="smaller lighter blue">Input Data Pemeriksaan Sarana</h3>
						</div>
					</div>

					

					<div class="table-responsive">
						<form action="<?= base_url() ?>pemeriksaan_sarana/input" method="post" onsubmit="return cek_add()">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Status Penyuluhan</th>
										<th>Jenis Form</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<div class="form-group">
												<label for="penyuluhan">Penyuluhan : </label>
												<input type="radio" name="data_penyuluhan" value="penyuluhan" onclick="javascript:pbpCheck();" id="noCheck">
											</div>
											<hr>
											<div class="form-group">
												<label for="penyuluhan">Belum Penyuluhan : </label>
												<input type="radio" name="data_penyuluhan" value="belum_penyuluhan"  onclick="javascript:pbpCheck();" id="yesCheck">
												<div id="ifYes" style="display:none">
													* Alasan belum penyuluhan: <textarea class="form-control" name="alasan_belum_penyuluhan" rows="10"></textarea>
												</div>
											</div>
										</td>


										<td>
											<div class="form-group">
												<label for="data_form">Form baku : </label>
												<input type="radio" name="data_form" value="form_baku" onclick="javascript:btkCheck();" id="nCheck">
											</div>
											<hr>
											<div class="form-group">
												<label for="data_form">Form tidak baku : </label>
												<input type="radio" name="data_form" value="form_tidak_baku"  onclick="javascript:btkCheck();" id="yCheck">
												<div id="oke" style="display:none">
													* Alasan menggunakan form tidak baku: <textarea name='alasan_form_tidak_baku' class="form-control" rows="10"></textarea>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" name="submit" class="btn btn-primary" value="Kirim"/>
								<a href="<?php echo base_url()?>pemeriksaan_sarana/output_pemeriksaan" class="btn btn-warning"/>Batal</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		function pbpCheck() {
			if (document.getElementById('yesCheck').checked) {
				document.getElementById('ifYes').style.display = 'block';
			}
			else document.getElementById('ifYes').style.display = 'none';

		}

		function btkCheck() {
			if (document.getElementById('yCheck').checked) {
				document.getElementById('oke').style.display = 'block';
			}
			else document.getElementById('oke').style.display = 'none';

		}

	</script>