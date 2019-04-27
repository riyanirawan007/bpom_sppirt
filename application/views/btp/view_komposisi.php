<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="dashboard">Dashboard</a>
	</li>
	<li class="active">Data Komposisi</li>
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
							<h3 class="smaller lighter blue">Tabel Data Komposisi</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'btp/add_komposisi';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Komposisi
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data Komposisi"
					</div>

					<div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                    	<thead>
                    		<tr>
                    			<th>No</th>
                    			<!--<th>Kode BTP</th>-->
                    			<th>Grup BTP</th>
                    			<th>Nama Bahan Tambahan Pangan</th>
                    			<th width="10%">Aksi</th>
                    		</tr>
                    	</thead>
                    	<tbody>
                    		<?php
                    		$no = 1;
                    		foreach ($komposisi as $komposisi) {
                    			echo '
                    			<tr>
                    			<td>'.$no.'</td>
                    			<td>'.$komposisi->nama_grup_btp.'</td>
                    			<td>'.$komposisi->nama_bahan_tambahan_pangan.'</td>
                    			<td>
                    			<div class="hidden-sm hidden-xs action-buttons">
                    			'.anchor('btp/edit_komposisi/'.$komposisi->no_urut_btp, '<i class="ace-icon fa fa-pencil bigger-130"></i>', array("class" => "green")).'
                    			'.anchor('btp/delete_komposisi/'.$komposisi->no_urut_btp, '<i class="ace-icon fa fa-trash-o bigger-130"></i>', array("class" => "red", "onclick" => "return confirm('Hapus data?')")).'
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

			<!-- PAGE CONTENT ENDS -->
		</div>
	</div>