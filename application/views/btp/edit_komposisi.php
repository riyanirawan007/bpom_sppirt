<script type="text/javascript">
	function cek_add(){
		if($('#nama_bahan_tambahan_pangan').val()==""){
			alert('Nama bahan tambahan pangan BTP belum diisi!');
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
		<li class="active">Data Grup BTP</li>
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
                        <form action="<?= base_url() ?>btp/edit_komposisi" method="post" onsubmit="return cek_add()">
                        	<h1>Edit Komposisi BTP</h1>
                        	<input type="hidden" name="id" value="<?php echo $komposisi['no_urut_btp'] ?>">
                        	<!--<div class="form-group">-->
                        	<!--	<label for="kode_btp">Kode BTP : </label>-->
                        		<input type="hidden" class="form-control" name="kode_btp" id="kode_btp" value="<?=$komposisi['kode_btp']?>"/>
                        	<!--</div>-->
                        	<div class="form-group">
                        		<label for="kode_btp">Grup BTP : </label>
                        		<select name="kode_r_grup_btp" class="form-control">
                        			<?php 
                        			foreach ($grup as $g) {
                        				if ($g->kode_grup_btp == $komposisi['kode_r_grup_btp']) {
                        					$selected = "selected";
                        				} else {
                        					$selected = "";
                        				}
                        				echo "<option value='$g->kode_grup_btp' $selected>$g->nama_grup_btp</option>";
                        			}
                        			?>
                        		</select>
                        	</div>
                        	<div class="form-group">
                        		<label for="nama_bahan_tambahan_pangan">Nama bahan tambahan pangan : </label>
                        		<textarea name="nama_bahan_tambahan_pangan" class="form-control"><?=$komposisi['nama_bahan_tambahan_pangan']?></textarea>
                        	</div>
                        	<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
                        	<a href="<?php echo base_url()?>btp/komposisi" class="btn btn-warning"/>Batal</a>
                        </form>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>