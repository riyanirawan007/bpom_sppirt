<h2>Penerimaan Pengajuan Permohonan SPP IRT</h2>
		
<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.autocomplete.css" /> -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/ >
<style type="text/css">
	#no-pirt-field{
		display : none;
	}
</style>

<script type="text/javascript" src="<?=base_url()?>js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/pages/irtp_permohonan.js"></script>

<script type="text/javascript">
/*
function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	return true;
*/
$(document).ready(function() {
	//nama perusahaan
	
	$("#pil_perusahaan").autocomplete({
		source: "<?php echo base_url(); ?>pb2kp/get_perusahaan" 
	});
	
	var perusahaan = $("#pil_perusahaan").val();
	
	$(".ui-autocomplete").click(function(){
		//$("#alamat_perusahaan").val('tes');
		var perusahaan = $("#pil_perusahaan").val();
		
		$.ajax({
			type: "POST",
			url:"<?php echo base_url(); ?>pb2kp/get_alamat_perusahaan",
			data:"perusahaan="+perusahaan,
			success: function(data){
				$("#alamat_perusahaan").val(data);
			}
		});
		
		$.ajax({
			type: "POST",
			url:"<?php echo base_url(); ?>pb2kp/get_telp_perusahaan",
			data:"perusahaan="+perusahaan,
			success: function(data){
				$("#telp_perusahaan").val(data);
			}
		}); 
	});
});

</script>	
<div class="well">
	<form role="form">

	<label for="name">Silahkan Pilih Terlebih Dahulu</label>
	<div>
		<label class="checkbox-inline"> <input type="radio" name="optionsRadiosinline" class="status_pengajuan" id="baru" value="baru" checked> Pengajuan Baru </label>
		<label class="checkbox-inline"> <input type="radio" name="optionsRadiosinline" class="status_pengajuan" id="perpanjangan" value="perpanjangan"> Perpanjangan </label>
	</div>
	<br>    
	<label>Nama Jenis Pangan</label>
	<div class="dropdown">
		<button type="button" class="btn dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown"> - Pilih Nama Jenis Pangan - <span class="caret"></span> </button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Jenis Pangan 1</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Jenis Pangan 2</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Jenis Pangan 3</a> </li>
		</ul>
	</div>
	<br>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Nama Dagang</label>
			<input type="text" class="form-control" id="nama_dagang" placeholder="Masukan Nama Dagang" />
		</div>
    </div>
	<label>Jenis Kemasan</label>
	<div class="dropdown">
		<button type="button" class="btn dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown"> - Pilih Jenis Kemasan - <span class="caret"></span> </button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Jenis Kemasan 1</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Jenis Kemasan 2</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Jenis Kemasan 3</a> </li>
		</ul>
	</div>
	<br>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Berat Bersih / Isi Bersih</label>
			<input type="text" class="form-control" id="berat_bersih" placeholder="Masukan Berat Bersih / Isi Bersih" />
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Komposisi Bahan Utama</label>
			<input type="text" class="form-control" id="komposisi_utama" placeholder="Masukan Komposisi Bahan Utama" />
		</div>
    </div>
	<label>Komposisi Bahan Tambahan</label>
	<div class="dropdown">
		<button type="button" class="btn dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown"> - Pilih Komposisi Bahan Tambahan - <span class="caret"></span> </button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Komposisi Bahan Tambahan 1</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Komposisi Bahan Tambahan 2</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Komposisi Bahan Tambahan 3</a> </li>
		</ul>
	</div>
	<br>
	<label>Proses Produksi</label>
	<div class="dropdown">
		<button type="button" class="btn dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown"> - Pilih Proses Produksi - <span class="caret"></span> </button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Komposisi Bahan Tambahan 1</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Komposisi Bahan Tambahan 2</a> </li>
			<li role="presentation"> <a role="menuitem" tabindex="-1" href="#">Komposisi Bahan Tambahan 3</a> </li>
		</ul>
	</div>
	<br>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Informasi Masa Simpan (Kadaluarsa)</label>
			<input type="text" class="form-control" id="masa_simpan" placeholder="Masukan Informasi Masa Simpan (Kadaluarsa)" />
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Informasi Kode Produksi</label>
			<input type="text" class="form-control" id="info_kode_produksi" placeholder="Masukan Informasi Kode Produksi" />
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Alamat IRTP</label>
			<textarea id="alamat_irtp" class="form-control" placeholder="Masukan Alamat IRTP"></textarea>
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Kode Pos IRTP</label>
			<input type="text" class="form-control" id="kode_pos_irtp" placeholder="Masukan Kode Pos IRTP" />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Nomor Telepon IRTP</label>
			<input type="text" class="form-control" id="nomor_telepon_irtp" placeholder="Masukan Nomor Telepon IRTP" />
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Nama Pemilik</label>
			<input type="text" class="form-control" id="nama_pmilik" placeholder="Masukan Nama Pemilik" />
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Nama Penanggung Jawab</label>
			<input type="text" class="form-control" id="nama_penanggung_jawab" placeholder="Masukan Nama Penanggung Jawab" />
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
		<label>Scan (Alur Produksi, SIUP, Rancangan Label)</label>
			<input type="file" name="file_foto"><p class="help-block"><b>Scan format JPEG maksimal berukuran 1280 x 1280 px</p>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Tanggal Pengajuan</label>
			<input type="text" class="form-control datetimepicker" />
		</div>
    </div>
	<div class="form-group row">
		<div class="col-xs-6">
			<label>Nomor Permohonan</label>
			<input type="number" class="form-control" id="no_permohonan" placeholder="Masukan Nomor Permohonan" onkeypress="return isNumberKey(event)">
		</div>
	</div>
	<button type="submit" class="btn btn-default">Kirim</button>
</form>
</div>


<!-- <script src="<?php echo base_url();?>js/jquery-2.1.1.min.js"></script> -->
<script src="<?php echo base_url();?>js/jquery.js"></script>
<!-- <script src="<?php echo base_url();?>js/jquery.autocomplete.js"></script> -->
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript">
			$('.datetimepicker').datetimepicker();
		</script>
