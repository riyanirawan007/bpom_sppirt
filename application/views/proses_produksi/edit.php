<script type="text/javascript">
	function cek_add(){
		if($('#proses_produksi').val()==""){
			alert('Nama Proses Produksi belum diisi!');
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
		<li class="active">Data Proses Produksi</li>
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
<form action="<?= base_url() ?>proses_produksi/edit" method="post" onsubmit="return cek_add()">
	<h1>Edit Proses Produksi</h1>
	<input type="hidden" name="id" value="<?php echo $proses_produksi['kode_tek_olah'] ?>">
	<div class="form-group">
		<label for="tek_olah">Proses Produksi : </label>
		<input type="text" class="form-control" name="tek_olah" required id="tek_olah" value="<?=$proses_produksi['tek_olah']?>"/>
	</div>
	<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
	<a href="<?php echo base_url()?>proses_produksi" class="btn btn-warning"/>Batal</a>
</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>