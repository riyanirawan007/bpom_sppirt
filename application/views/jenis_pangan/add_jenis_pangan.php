<script type="text/javascript">
	function cek_add(){
		if($('#kode_jenis_pangan').val()==""){
			alert('Kode jenis pangan belum diisi!');
			return false;
		} else if($('#jenis_pangan').val()==""){
			alert('Jenis pangan belum diisi!');
			return false;
		}
	}
</script>
<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Tambah Jenis Pangan</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
                        <?= @$this->session->flashdata('message') ?>
                        <?= @$this->session->flashdata('errors') ?>
                        <form action="<?= base_url() ?>jenis_pangan/add_jenis_pangan" method="post" onsubmit="return cek_add()">
                        	<h1>Tambah jenis pangan</h1>
                        	<div class="form-group">
                        		<label for="kode_jenis_pangan">Kode Jenis Pangan : </label>
                        		<input type="text" class="form-control" name="kode_jenis_pangan" id="kode_jenis_pangan" value="<?=set_value('kode_jenis_pangan')?>"/>
                        	</div>
                        	<div class="form-group">
                        		<label for="kode_r_grup_jenis_pangan">Grup Jenis Pangan : </label>
                        		<select name="kode_r_grup_jenis_pangan" class="form-control">
                        			<?php 
                        			foreach ($grup as $g) {
                        				echo "<option value='$g->kode_grup_jenis_pangan'>$g->nama_grup_jenis_pangan</option>";
                        			}
                        			?>
                        		</select>
                        	</div>
                        	<div class="form-group">
                        		<label for="jenis_pangan">Jenis Pangan : </label>
                        		<input type="text" class="form-control" name="jenis_pangan" id="jenis_pangan" value="<?=set_value('jenis_pangan')?>"/>
                        	</div>
                        	<div class="form-group">
                        		<label for="deskripsi">Deskripsi : </label>
                        		<textarea name="deskripsi" class="form-control" rows="5"></textarea>
                        	</div>
                        	<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
                        	<a href="<?php echo base_url()?>jenis_pangan/nama_jenis_pangan" class="btn btn-warning"/>Batal</a>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>