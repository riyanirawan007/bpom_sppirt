<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Konfigurasi Email</li>
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
							<h3 class="smaller lighter blue">Tabel Data Konfigurasi Email</h3>
						</div>
					</div>

					<div class="table-header">
						Hasil untuk "Data Konfigurasi Email"
					</div>

					<div class="table-responsive">

						<table id="datatable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Protocol</th>
									<th>Host</th>
									<th>Auth</th>
									<th>User</th>
									<th>Port</th>
									<th>Timeout</th>
									<th>Crypto</th>
									<th width="10%">Aksi</th>
								</tr>
							</thead>

							<tbody>

								<?php
								$no = 1;
								foreach ($konfig_email->result() as $r) {
									echo '
									<tr>
									<td>'.$no.'</td>
									<td>'.$r->protocol.'</td>
									<td>'.$r->host.'</td>
									<td>'.$r->auth.'</td>
									<td>'.$r->user.'</td>
									<td>'.$r->port.'</td>
									<td>'.$r->timeout.'</td>
									<td>'.$r->crypto.'</td>

									<td>
									<div class="hidden-sm hidden-xs action-buttons">

									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
									Edit
									</button>

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

<?php foreach ($konfig_email->result() as $r2): ?>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Perbarui data</h4>
				</div>
				<form action="<?= $action ?>" method="post">
					<input type="hidden" name="id" value="<?= $r2->id ?>">
					<div class="modal-body">
						<div class="form-group">
							<label>Protocol</label>
							<input type="text" class="form-control" name="protocol" value="<?= $r2->protocol ?>">
						</div>
						<div class="form-group">
							<label>Host</label>
							<input type="text" class="form-control" name="host" value="<?= $r2->host ?>">
						</div>
						<div class="form-group">
							<label>Auth</label>
							<select name="auth" class="form-control">
								<option value="true">true</option>
								<option value="false">false</option>
							</select>
						</div>
						<div class="form-group">
							<label>User</label>
							<input type="text" class="form-control" name="user" value="<?= $r2->user ?>">
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" name="pass" value="<?= $r2->pass ?>">
						</div>
						<div class="form-group">
							<label>Port</label>
							<input type="number" class="form-control" name="port" value="<?= $r2->port ?>">
						</div>
						<div class="form-group">
							<label>Timeout</label>
							<input type="number" class="form-control" name="timeout" value="<?= $r2->timeout ?>">
						</div>
						<div class="form-group">
							<label>Crypto</label>
							<input type="text" class="form-control" name="crypto" value="<?= $r2->crypto ?>">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
						<button type="submit" class="btn btn-primary">Simpan perubahan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php endforeach ?>