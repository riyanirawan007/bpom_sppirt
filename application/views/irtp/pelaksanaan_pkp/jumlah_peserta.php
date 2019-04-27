<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Input Data Pelaksanaan PKP</li>
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
							<h3 class="smaller lighter blue">Jumlah Peserta PKP</h3>
						</div>
					</div>
					<div class="table-responsive">
						<form action="<?= base_url() ?>pelaksanaan_pkp/input" method="post" onsubmit="return cek_add()">
							<table class="table table-bordered">
							
								<tbody>
									<tr>
										<td>

											<div class="form-group">
                                              <label for="jumlah_peserta">Jumlah peserta</label>
                                              <input type="text" name="jumlah_peserta" id="jumlah_peserta" class="form-control" placeholder="" aria-describedby="helpId">
                                            </div>
										</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" name="submit" class="btn btn-primary" value="Kirim"/>
								<a href="<?php echo base_url()?>pelaksanaan_pkp/output_penyelenggaraan" class="btn btn-warning">Batal</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

