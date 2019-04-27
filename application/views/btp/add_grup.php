<script type="text/javascript">
	function cek_add(){
		if($('#nama_grup_btp').val()==""){
			alert('Nama grup BTP belum diisi!');
			return false;
		} 
	}
</script>
<?= @$this->session->flashdata('message') ?>
<?= @$this->session->flashdata('errors') ?>
<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Tambah Grup BTP</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
                    <form action="<?= base_url() ?>btp/add_grup" method="post" onsubmit="return cek_add()">
                    	<h1>Tambah Grup BTP</h1>
                    	<div class="form-group">
                    		<label for="nama_grup_btp">Nama Grup BTP : </label>
                    		<input type="text" class="form-control" name="nama_grup_btp" id="nama_grup_btp" value="<?=set_value('nama_grup_btp')?>"/>
                    	</div>
                    	<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
                    	<a href="<?php echo base_url()?>btp" class="btn btn-warning"/>Batal</a>
                    </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>