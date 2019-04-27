<style type="text/css">
.clearfix input {
	width: 600px;
}
.clearfix textarea {
	margin: 0px; 
	width: 601px; 
	height: 55px;
}
.form-group label {
	text-align: right;
}
</style>
<script type="text/javascript">


	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;
	}
	$(document).ready(function() {
	//nama perusahaan
	var config = {
		'.chosen-select'           : {},
		'.chosen-select-deselect'  : {allow_single_deselect:true},
		'.chosen-select-no-single' : {disable_search_threshold:10},
		'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
		'.chosen-select-width'     : {width:"100%"}
	}
	for (var selector in config) {
		$(selector).chosen(config[selector]);
	}
	$(document).on('click', '.btn-delete', function(e){
		var curr = $(this);
		curr.parents('.data-strip').remove();
	});
	
	$(document).on('click', '.btn-add', function(e){
		var DOM = $(this).parents('.appended');
		var curr = $(this);
		var temp = '<div class="row data-strip" style="margin-top : 10px"> \
		<div class="col-sm-7"> \
		<select class="chosen-select" data-placeholder="Masukan Komposisi Tambahan" style="width : 100%;" name="komposisi_tambah[]"> \
		<?php foreach($js_komp_tmbh as $data): ?> \
		<option value="<?=$data->no_urut_btp?>"><?=addslashes($data->nama_bahan_tambahan_pangan)?></option> \
		<?php endforeach ?>	\
		</select> \
		</div> \
		<div class="col-sm-3"> \
		<input type="text" class="form-control" name="berat_bersih_tambahan[]" placeholder="Berat Bersih" data-validation="required"/> \
		</div> \
		<div class="col-sm-2"> \
		<div class="btn-add btn button btn-primary" style="width:100%">Add</div> \
		</div> \
		</div>';
		DOM.append(temp);	
		DOM.find('select').trigger('chosen:updated').chosen({'width' : '100%'});
		
		curr.html('Delete').removeClass('btn-primary btn-add').addClass('btn-danger btn-delete');
	});
	
	$('#pemilik_usaha').change(function(){
		var value = $(this).val();
		var penanggung = $('#penanggung_jawab');
		var telepon = $('#telepon_irtp');
		var alamat = $('#alamat_irtp');
		var nik = $('#no_nik');
		
		$.ajax({
			url	: '<?=base_url()?>irtp/get_perusahaan_raw',
			dataType : 'json',
			type : 'POST',
			data : 'kode=' + value,
			success: function(html){
				$.each(html, function(key, value){
					penanggung.val(value.nama_penanggung_jawab);
					penanggung.removeAttr('readonly');
					telepon.val(value.nomor_telefon_irtp);
					alamat.val(value.alamat_irtp);
					nik.val(value.no_nik);
				});
			},error: function(e){
				
			}
		});	
	});
	
	$('#provinsi').change(function(){
		var provinsi = $(this).val();
		var kabupaten = $('#kabupaten').parents('.dropdown');
		jQuery.ajax({
			url	:	'<?=base_url()?>irtp/get_kabupaten'	,
			type : 'POST',
			dataType : 'json',
			data	: 'provinsi=' + provinsi,
			success: function(html){
				var temp;
				temp = "<select id='kabupaten' class='chosen-select'>";
				$.each(html, function(val, key){
					temp += "<option value='" + key.no_kabupaten + "'>" + key.nm_kabupaten + "</option>";
				});				
				temp += "</select>";
				console.log(temp);
				kabupaten.empty().append(temp);
				kabupaten.find('#kabupaten').trigger('chosen:updated').chosen({'width' : '100%'});
				
			},error: function(e){
				console.log(e);
			}
		});	
	});

	$('#grup_jenis_pangan').change(function(){
		var grup_jenis_pangan = $(this).val();
		var jenis_pangan = $('#jenis_pangan').parents('.dropdown');
		jQuery.ajax({
			url	:	'<?=base_url()?>irtp/get_jenis_pangan'	,
			type : 'POST',
			dataType : 'json',
			data	: 'grup_jenis_pangan=' + grup_jenis_pangan,
			success: function(html){
				var temp;
				temp = "<select class='chosen-select col-sm-12' name='jenis_pangan' id='jenis_pangan' data-validation='required'><option value=''>- Pilih Nama Jenis Pangan -</option>";
				$.each(html, function(val, key){
					temp += "<option value='" + key.id_urut_jenis_pangan + "'>" + key.jenis_pangan + "</option>";
				});		
				temp += "<option value='-'>Lainnya</option></select>";		
				console.log(temp);
				jenis_pangan.empty().append(temp);
				jenis_pangan.find('#jenis_pangan').trigger('chosen:updated').chosen({'width' : '100%'});

				jQuery.ajax({
					url	:	'<?=base_url()?>irtp/get_grup_jenis_pangan'	,
					type : 'POST',
					dataType : 'json',
					data	: 'grup_jenis_pangan=' + grup_jenis_pangan,
					success: function(html){
						$('#label_jenis_pangan').html('Nama Jenis Pangan ('+html+')');
					},error: function(e){
						console.log(e);
					}
				});	
			},error: function(e){
				console.log(e);
			}
		});	
	});
	
	
	
	$('.datetimepicker').datetimepicker();
	

	var perusahaan = $("#pil_perusahaan").val();
	
	$(".ui-autocomplete").click(function(){
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
	
	$('#proses_produksi').change(function(){
		var value = $(this).val();
		if(value=='11'){
			$('#box_proses_produksi').show();
			$('#proses_produksi_lain').val('');
			$('#proses_produksi_lain').attr("data-validation","required");
			
		} else {
			$('#box_proses_produksi').hide();
			$('#proses_produksi_lain').val('');
			$('#proses_produksi_lain').removeAttr("data-validation");
		}
	});
	
	$('#jenis_kemasan').change(function(){
		var value = $(this).val();
		if(value=='6'){
			$('#box_jenis_kemasan').show();
			$('#jenis_kemasan_lain').val('');
			$('#jenis_kemasan_lain').attr("data-validation","required");
			
		} else {
			$('#box_jenis_kemasan').hide();
			$('#jenis_kemasan_lain').val('');
			$('#jenis_kemasan_lain').removeAttr("data-validation");
		}
	});
	
});

$(document).delegate("#jenis_pangan", "change", function() {
	var value = $(this).val();
	if(value=='-'){
		$('#box_jenis_pangan').show();
		$('#jenis_pangan_lain').val('');
		$('#jenis_pangan_lain').attr("data-validation","required");
		
	} else {
		$('#box_jenis_pangan').hide();
		$('#jenis_pangan_lain').val('');
		$('#jenis_pangan_lain').removeAttr("data-validation");
	}
});

function cek_permohonan(){
	var masa_simpan = $('#masa_simpan').val();
	if(masa_simpan.indexOf('hari') > -1 || masa_simpan.indexOf('Hari') > -1){
		var r = confirm("Apakah Anda yakin?");
		if (r == true) {
			return true;
		} else {
			return false;
		}
	} else {
		alert('Masa simpan (kedaluwarsa) harus dalam format Hari, contoh : 90 hari');
		return false;
	}
}
</script>	

<!--<link href="<?php echo base_url();?>css/bpom.css" rel="stylesheet">	-->
<link href="<?php echo base_url();?>css/chosen/chosen.min.css" rel="stylesheet">	
<script type="text/javascript" src="<?=base_url()?>js/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/chosen/prism.js"></script>
<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Input Data Jenis IRTP lama</li>
	</ul>

</div>


<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="smaller lighter blue">Input Data Jenis IRTP Lama</h3>
						</div>

					</div>
					<div class="widget-box">
						<div class="widget-header widget-header-blue widget-header-flat">
							<h4 class="widget-title lighter">Informasi Kepemilikan</h4>
						</div>

						<div class="widget-body">
							<div class="widget-main">
								<div id="fuelux-wizard-container">
									<div>

									</div>
									<!-- <hr /> -->

									<div class="step-content pos-rel">
										<div class="row">
											<?=validation_errors()?>
											<?= @$this->session->flashdata('error') ?>
											<?= @$this->session->flashdata('status') ?>
											<?= @$this->session->flashdata('message') ?>

											<?php $attr_form = array('onsubmit' => 'return cek_permohonan()'); ?>
											<?=form_open_multipart('irtp/update_irtp_lama', $attr_form)?>
											<div class="form-group-cat">			
												<div class="form-group">
													<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pemilik_usaha">Nama Pemilik IRTP</label>

													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<select class="select2" name="pemilik_usaha" id="pemilik_usaha" >
																<option value="">- Pilih Nama Pemilik IRTP -</option>
																<?php foreach($js_perus as $data): ?>
																	<option value="<?=$data->kode_perusahaan?>"><?=$data->kode_perusahaan." - ".$data->nama_pemilik?></option>
																<?php endforeach ?>	
															</select>	
															<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Pemilik IRTP, Contoh : Dr. Joni Kawaldi</p>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Penanggung Jawab IRTP</label>
													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<input type="text" class="col-xs-12 col-sm-9" name="penanggung_jawab" id="penanggung_jawab" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu"/>
															<p class="col-xs-12 col-sm-9 help-block">Nama Penanggung Jawab IRTP akan terisi secara otomatis setelah memilih Nama Pemilik IRTP, Contoh : Hasan Sadikin, S.Pd</p>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="no_nik">No NIK Pemilik</label>

													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<input type="text" class="col-xs-12 col-sm-9" id="no_nik" name="no_nik" placeholder="Masukkan No NIK" data-validation="number" data-validation-allowing="range[0;9999999999999999]" onkeypress="return isNumberKey(event)" maxlength="16" />
															<p class="col-xs-12 col-sm-9 help-block">No NIK Pemilik IRTP (maksimal 16 digit angka)</p>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alamat IRTP</label>
													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<textarea id="alamat_irtp"  class="input-xlarge" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" readonly ></textarea>
															<p class="col-xs-12 col-sm-9 help-block">Alamat IRTP akan terisi otomatis setelah memilih Nama Pemilik IRTP, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Telepon IRTP</label>
													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<input type="text" class="col-xs-12 col-sm-9" id="telepon_irtp" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" readonly />
															<p class="col-xs-12 col-sm-9 help-block">Nomor Telepon IRTP akan terisi otomatis setelah Memilih Nama Pemilik IRTP, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Upload Izin Usaha</label>

													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<input type="file" name="file_pdf"><p class="col-xs-12 col-sm-9 help-block">Scan format PDF/JPG maksimal berukuran 2 Mb</p>
														</div>
													</div>
												</div>
												<div class="wizard-actions">
													<button class="btn btn-warning"  type="submit" name="submit">
														Selanjutnya
														<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
													</button>
												</div>
											</div><!-- form-group-cat -->




										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



