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
		<h2 class="heading-form">warning only admin can be accessed!</h2>
		<?=form_open('pemeriksaan_sarana/process_exec', array('role' => 'form', 'method' => 'post'))?>
		<div class="form-group">
			<h4 class="header red">Input Your Password</h4>
			<div >
				<input type="password" class="ace status_pengajuan" name="password" value="" placeholder="input your password">
			</div>
		</div><!-- form-group -->
		<button type="submit" class="btn btn-primary col-md-12 col-sm-12">Process</button>
		<?=form_close()?>
	</div>

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


