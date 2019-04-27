<script type="text/javascript">
	function cek_add(){
		if($('#alasan_pencabutan').val()==""){
			alert('Alasan pencabutan belum diisi!');
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
		<li class="active">Tambah Alasan Pencabutan</li>
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
<form action="<?= base_url() ?>alasan_pencabutan/add" method="post" onsubmit="return cek_add()">
	<h1>Tambah Alasan Pencabutan</h1>
	<div class="form-group">
		<label for="alasan_pencabutan">Alasan pencabutan : </label>
		<input type="text" class="form-control" name="alasan_pencabutan" id="alasan_pencabutan"/>
	</div>
	<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
	<a href="<?php echo base_url()?>alasan_pencabutan" class="btn btn-warning"/>Batal</a>
</form>

						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>