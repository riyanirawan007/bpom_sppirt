<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Kategori Jenis Pangan</li>
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
							<h3 class="smaller lighter blue">Tabel Data Kategori Jenis Pangan</h3>
						</div>
						<dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'jenis_pangan/add_grup_jenis_pangan';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Kategori Jenis Pangan
							</a>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data Kategori Jenis Pangan"
					</div>

					<div class="table-responsive">

                    <table id="datatable" class="table table-striped table-bordered table-hover">
                    	<thead>
                    		<tr>
                    			<th width="15%">Kode Grup Jenis Pangan</th>
                    			<th>Grup Jenis Pangan</th>
                    			<th>Status</th>
                    			<th>Aksi</th>
                    		</tr>
                    	</thead>
                    
                    	<tbody>
                    		
                    		<?php
                    		foreach ($jenis_pangan as $jp) {
                    			if ($jp->status == 0) {
                    				$status = '<a href="'.base_url('jenis_pangan/aktif/'.$jp->kode_grup_jenis_pangan).'" class="btn btn-danger btn-sm">Tidak Aktif</a>';
                    			} else {
                    				$status = '<a href="'.base_url('jenis_pangan/non_aktif/'.$jp->kode_grup_jenis_pangan).'" class="btn btn-primary btn-sm">Aktif</a>';
                    			}
                    			echo '
                    			<tr>
                    			<td>'.$jp->kode_grup_jenis_pangan.'</td>
                    			<td>'.$jp->nama_grup_jenis_pangan.'</td>
                    			<td>'.$status.'</td>
                    
                    			<td>
                    			<div class="hidden-sm hidden-xs action-buttons">
                    
                    			'.anchor('jenis_pangan/edit_grup_jenis_pangan/'.$jp->kode_grup_jenis_pangan, '<i class="ace-icon fa fa-pencil bigger-130"></i>', array("class" => "green")).'
                    
                    			'.anchor('jenis_pangan/delete_grup_jenis_pangan/'.$jp->kode_grup_jenis_pangan, '<i class="ace-icon fa fa-trash-o bigger-130"></i>', array("class" => "red", "onclick" => "return confirm('Hapus data?')")).'
                    			</div>
                    			</td>
                    			</tr>
                    			';
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