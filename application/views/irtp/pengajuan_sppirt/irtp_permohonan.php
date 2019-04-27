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
	
	$('.status_pengajuan').change(function(){
		if($(this).val() == 'baru'){
			$(this).parents('.form-group').next().slideDown();	
		}else{
			var next = $(this).parents('.form-group').next();
			next.find('input[type=radio]').removeAttr('checked');
			next.slideUp();	
		}
	});
	
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

<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="<?php echo site_url()?>">Dashboard</a>
	</li>
	<li class="active">Input Data Permohonan SPPIRT</li>
</ul>

</div>
<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Input Data Permohonan SPPIRT
			</small>
		</h1>
	</div> -->

<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<!-- <div class="col-xs-6">
							<h3 class="smaller lighter blue">Input Data Pelaksanaan PKP</h3>
						</div> -->
						<!-- <dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'role_menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Role Menu
							</a>
						</dir> -->
					</div>
					<!-- isi -->


<div class="row">
	<div class="col-sm-7">
		<h2 class="heading-form">Penerimaan Pengajuan Permohonan SPP IRT</h2>
		<?=form_open('irtp/irtp_openStat', array('role' => 'form', 'method' => 'get'))?>
		<div class="form-group">
			<h4 class="header green">Silahkan Pilih Terlebih Dahulu</h4>
			<div>
				<!-- <div class="radio" >
					<label >
						<input name="form-field-radio status_pengajuan" type="radio" class="ace" id="baru"  value="baru"/>
						<span class="lbl"> Pengajuan SPP IRT Baru </span>
					</label>
				</div> -->


				<label class="checkbox-inline"> 
					<input type="radio" class="ace status_pengajuan" id="baru" name="status_pengajuan" value="baru"><span class="lbl">  Pengajuan SPP IRT Baru </span>
				</label>

				<!-- <div class="radio" >
					<label >
						<input name="form-field-radio status_pengajuan" type="radio" class="ace" id="perpanjangan"  value="perpanjangan"/>
						<span class="lbl"> Perpanjangan SPP IRT </span>
					</label>
				</div> -->

				<label class="checkbox-inline"> 
					<input type="radio" class="ace status_pengajuan" id="perpanjangan" name="status_pengajuan" value="perpanjangan"> <span class="lbl"> Perpanjangan SPP IRT </span>
				</label>
			</div>
		</div><!-- form-group -->
        <div class="form-group" style="display : none">
			<h4 class="header green">Pilih Jenis IRTP</h4>

			<div>
				<label class="checkbox-inline">
 
					<input type="radio" class="ace status_perus" name="status_perus" value="perus_lama"> <span class="lbl"> IRTP Lama</span> 			
				</label>
				<label class="checkbox-inline"> 
					<input type="radio" class="ace status_perus"  name="status_perus" value="perus_baru"> <span class="lbl"> IRTP Baru</span>
				</label>
			</div>
		</div><!-- form-group -->
		<button type="submit" class="btn btn-primary col-md-12 col-sm-12">Next &raquo;</button>
		<?=form_close()?>
	</div>

	<div class="col-sm-5">
		<div class="panel-instruction">
	        <h2>Tips Pengisian Form</h2>
	        <p>
	            Pilihlah menu disamping sesuai dengan spesifikasi yang telah tertera sebagai berikut
	         </p>
	        <ul>
	            <li>Pengajuan Baru : Jika Anda akan mengisi form pengajuan IRTP baru</li>
	            <li>Perpanjangan : Jika Anda akan memperpanjang masa IRTP yang telah habis masa berlakunya</li>
	        </ul>
	        <p>
	            Jika poin-poin sudah terisi, maka klik tombol Next untuk melanjutkan proses transaksi.
	        </p>
	    </div>

		<!-- <div class="panel-instruction">
	        <h2>Tips Pengisian Form</h2>
	        <p>
	            Pilihlah menu disamping sesuai dengan spesifikasi yang telah tertera sebagai berikut
	         </p>
	        <ul>
	            <li>Pengajuan Baru : Jika Anda akan mengisi form pengajuan IRTP baru</li>
	            <li>Perpanjangan : Jika Anda akan memperpanjang masa IRTP yang telah habis masa berlakunya</li>
	        </ul>
	        <p>
	            Jika poin-poin sudah terisi, maka klik tombol Next untuk melanjutkan proses transaksi.
	        </p>
	    </div> -->
	</div><!-- col-sm-5 -->
</div><!-- row -->
				</div>
			</div>
		</div>
</div>

</div>

								</div><!-- /.row -->

								</div><!-- /.row -->



<!-- <script src="<?php echo base_url();?>js/jquery-2.1.1.min.js"></script> -->
<script src="<?php echo base_url();?>js/jquery.js"></script>
<!-- <script src="<?php echo base_url();?>js/jquery.autocomplete.js"></script> -->
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript">
	$('.datetimepicker').datetimepicker();
</script>


