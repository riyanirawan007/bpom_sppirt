<script type="text/javascript">
	function cek_add(){
		if($('#nama_grup_jenis_pangan').val()==""){
			alert('Nama grup jenis pangan belum diisi!');
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
		<li class="active">Perbarui data Grup Jenis Pangan</li>
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
                    <form action="<?= base_url() ?>jenis_pangan/edit_grup_jenis_pangan" method="post" onsubmit="return cek_add()">
                    	<h1>Edit grup jenis pangan</h1>
                    	<div class="form-group">
                    		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                    		<label for="nama_grup_jenis_pangan">Grup Jenis Pangan : </label>
                    		<input type="text" class="form-control" name="nama_grup_jenis_pangan" id="nama_grup_jenis_pangan" value="<?php echo $jenis_pangan['nama_grup_jenis_pangan'] ?>"/>
                    	</div>
                    	<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
                    	<a href="<?php echo base_url()?>jenis_pangan" class="btn btn-warning"/>Batal</a>
                    </form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>