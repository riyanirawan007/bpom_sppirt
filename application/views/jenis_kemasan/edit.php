<script type="text/javascript">
	function cek_add(){
		if($('#jenis_kemasan').val()==""){
			alert('Nama grup jenis pangan belum diisi!');
			return false;
		} 
	}
</script>
<script type="text/javascript">
	function cek_add(){
		if($('#jenis_kemasan').val()==""){
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
		<li class="active">Perbarui Jenis Kemasan</li>
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
<form action="<?= base_url() ?>jenis_kemasan/edit" method="post" onsubmit="return cek_add()">

	<input type="hidden" name="id" value="<?php echo $jenis_kemasan['kode_kemasan'] ?>">
	<div class="form-group">
		<label for="jenis_kemasan">Jenis Kemasan : </label>
		<input type="text" class="form-control" name="jenis_kemasan" id="jenis_kemasan" value="<?=$jenis_kemasan['jenis_kemasan']?>"/>
	</div>
	<div class="form-group">
		<label for="ket_kemasan">Keterangan Kemasan: </label>
		<textarea name="ket_kemasan" class="form-control"><?php echo $jenis_kemasan['ket_kemasan'] ?></textarea>
	</div>
	<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
	<a href="<?php echo base_url()?>jenis_kemasan" class="btn btn-warning"/>Batal</a>
</form>

						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>