<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Materi Penyuluhan</li>
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
							<h3 class="smaller lighter blue">Tabel Data Materi Penyuluhan</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'materi_penyuluhan/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Materi Penyuluhan
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data Materi Penyuluhan"
					</div>

					<div class="table-responsive">

<table id="datatable" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th>No</th>
			<!--<th>Propinsi</th>-->
			<!--<th>Kabupaten</th>-->
			<th>Materi Penyuluhan</th>
			<th>Kelompok Materi</th>
			<!--<th>Jenis</th>-->
			<!--<th>Cluster</th>-->
			<th>Dokumen</th>
			<th width="10%">Aksi</th>
		</tr>
	</thead>
	<tbody>	
		<?php
		$no = 1;
		foreach ($materi_penyuluhan as $mp) {
			echo '
			<tr>
			<td>'.$no.'</td>

			<td>'.$mp->nama_materi_penyuluhan.'</td>
			<td>'.$mp->status_materi.'</td>';
			if ($mp->dokumen == "") {
				echo '
				<td>Tidak ada dokumen</td>';
			} else {
				echo '
				<td>'.anchor('dok_materi/'.$mp->dokumen, '<i class="fa fa-download"></i>', array("class" => "btn btn-primary")).'</td>';	
			}
			echo '
			<td>
			<div class="hidden-sm hidden-xs action-buttons">

			'.anchor('materi_penyuluhan/edit/'.$mp->kode_materi_penyuluhan, '<i class="ace-icon fa fa-pencil bigger-130"></i>', array("class" => "green")).'

			'.anchor('materi_penyuluhan/delete/'.$mp->kode_materi_penyuluhan, '<i class="ace-icon fa fa-trash-o bigger-130"></i>', array("class" => "red", "onclick" => "return confirm('Hapus data?')")).'
			</div>
			</td>
			</tr>
			';
			$no++;
			// 			<td>'.$mp->nama_propinsi.'</td>
// 			<td>'.$mp->nm_kabupaten.'</td>
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