<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Batas waktu pengingat masa berlaku sertifikat</li>
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
							<h3 class="smaller lighter blue">Batas waktu pengingat masa berlaku sertifikat</h3>
						</div>
					</div>

					<div class="table-header">
						Hasil untuk "Batas waktu pengingat masa berlaku sertifikat"
					</div>

					<div class="table-responsive">

						<table id="datatable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Batas waktu</th>
									<th width="10%">Aksi</th>
								</tr>
							</thead>

							<tbody>

								<?php
								$no = 1;
								foreach ($batas_waktu->result() as $bt) {
									echo '
									<tr>
									<td>'.$no.'</td>
									<td>'.$bt->notifikasi_sertifikat.' Bulan</td>

									<td>
									<div class="hidden-sm hidden-xs action-buttons">
									<a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Edit</a>


									
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

<?php foreach ($batas_waktu->result() as $b): ?>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit</h5>
				</div>
				<form action="<?= base_url('sertifikat_cek/notifikasi_sertifikat') ?>" method="post">
					<input type="hidden" name="id" value="<?= $b->id ?>">
					<div class="modal-body">
						<div class="form-group">
							<label for="notifikasi_sertifikat">Batas waktu</label>
							<input type="number" name="notifikasi_sertifikat" placeholder="Batas waktu pengingat masa berlaku sertifikat" id="notifikasi_sertifikat" class="form-control" value="<?= $b->notifikasi_sertifikat ?>">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
						<button type="submit" class="btn btn-primary" name="submit">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php endforeach ?>