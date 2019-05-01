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
				$.each(html, function(val, key){
					temp += "<option value='" + key.no_kabupaten + "'>" + key.no_kabupaten + ". " + key.nm_kabupaten + "</option>";
				});				
				console.log($('#kabupaten'));
				
				$('#kabupaten').css({'display' : 'block'}).removeClass('chzn-done');
				$('#kabupaten').next().remove();
				console.log($('#kabupaten').html(temp));
				$('#kabupaten').trigger('chosen:updated').chosen({'width' : '100%'});
				
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
});


</script>	

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
		<li class="active">Input Data Jenis IRTP Baru</li>
	</ul>

</div>


<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="smaller lighter blue">Input Data Jenis IRTP Baru</h3>
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
										<!-- <ul class="steps">
											<li data-step="1" class="active">
												<span class="step">1</span>
												<span class="title">Informasi Kepemilikan</span>
											</li>

											<li data-step="2">
												<span class="step">2</span>
												<span class="title">Informasi Produk Pangan</span>
											</li>

											<li data-step="3">
												<span class="step">3</span>
												<span class="title">Finish</span>
											</li>

										</ul> -->
									</div>
									<!-- <hr /> -->

									<div class="step-content pos-rel">
										<!-- <div class="step-pane active" data-step="1"> -->
											<div class="row">
												<div class="col-sm-12">		
													<div class="form-group-cat">
														<?= @$this->session->flashdata('error') ?>
														<?= @$this->session->flashdata('status') ?>
														<?= @$this->session->flashdata('message') ?>

														<?php $attr_form = array('onsubmit' => 'return cek_form()','class' => 'form-horizontal'); ?>  
														<?=form_open_multipart('irtp/set_perusahaan', $attr_form)?>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama IRTP</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input type="text" class="col-xs-12 col-sm-9" id="nama_perusahaan" name="nama_perusahaan" placeholder="Masukkan Nama IRTP" data-validation="required"/>
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Nama IRTP, Contoh : CV. Aroma</p>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Pemilik IRTP</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input type="text" class="col-xs-12 col-sm-9" id="nama_pemilik" name="nama_pemilik" placeholder="Masukkan Nama Pemilik IRTP" data-validation="required"/>
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Pemilik IRTP, Contoh : Dr. Joni Kawaldi</p>
																</div>
															</div>
														</div>

														

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">No NIK Pemilik</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input type="text" class="col-xs-12 col-sm-9" id="no_nik" name="no_nik" placeholder="Masukkan No NIK" data-validation="number" data-validation-allowing="range[0;9999999999999999]" onkeypress="return isNumberKey(event)" maxlength="16" />
																	<p class="col-xs-12 col-sm-9 help-block">No NIK Pemilik IRTP (maksimal 16 digit angka)</p>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Penanggung Jawab IRTP</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input name="penanggung_jawab" type="text" class="col-xs-12 col-sm-9" id="penanggung_jawab" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" data-validation="required" />
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Penanggung Jawab IRTP, Contoh : Hasan Sadikin S.Pd</p>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alamat IRTP</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<textarea style="margin: 0px; width: 601px; height: 55px;" class="input-xlarge" id="alamat_irtp" name="alamat_irtp" data-validation="required" placeholder="Masukan Alamat Lengkap IRTP" ></textarea>
																	<p class="col-xs-12 col-sm-9 help-block">		
																	Alamat IRTP akan terisi otomatis setelah memilih Nama Pemilik IRTP, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
																</div>
															</div>
														</div>

														<div class="form-group row">                
															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Provinsi</label>

																<div class="col-xs-12 col-sm-9">
																	<div class="clearfix">
																		<select class="select2 " name="nama_propinsi" id="provinsi" data-validation="required">
																			<?php echo $filter; ?>
																			<?php foreach($data_select as $data): ?>			
																				<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
																			<?php endforeach ?>				
																		</select>
																		<p class="col-xs-12 col-sm-9 help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Kabupaten/Kota</label>
																<div class="col-xs-12 col-sm-9">
																	<div class="clearfix">
																		<div class="dropdown">
																			<select class="chosen-select col-sm-6" name="nama_kabupaten" id="kabupaten" data-validation="required">
																				<?php echo $filter_kab; ?>
																			</select>
																			<p class="col-xs-12 col-sm-9 help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan.</p>
																		</div>
																	</div>
																</div>
															</div>

															<!-- <div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Kabupaten/Kota</label>

																<div class="col-xs-12 col-sm-9">
																	<div class="clearfix">
																		<select class="select2 " name="nama_kabupaten" id="kabupaten" data-validation="required">
																			<?php echo $filter_kab; ?>
																		</select>
																		<p class="col-xs-12 col-sm-9 help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan.</p>
																	</div>
																</div>
															</div> -->
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Kode Pos</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input type="text" class="col-xs-12 col-sm-9" placeholder="Masukan Kode Pos IRTP" name="pos_irtp" data-validation="number" data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)" maxlength="5" />
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Kode Pos IRTP (maksimal 5 digit angka), Contoh : 14211, Jika Kode Pos belum diketahui isi dengan 0</p>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Telepon IRTP</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input name="telepon_irtp" type="text" class="col-xs-12 col-sm-9" data-validation="number" data-validation-allowing="range[0;99999999999999]" id="nomor_telepon_irtp" placeholder="Masukan Nomor Telepon IRTP" onkeypress="return isNumberKey(event)" maxlength="14" />
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Nomor Telepon IRTP (maksimal 14 digit angka), Contoh : 02112345678901</p>
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

														<!-- <button type="submit" class="btn btn-primary col-md-12 col-sm-12"><b>Kirim &raquo;</b></button> -->
														<div class="wizard-actions">
															<button class="btn btn-warning"  type="submit">
																Selanjutnya
																<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
															</button>
														</div>
														<?=form_close()?>
													</div>
												</div>
											</div>
											<!-- </div> -->
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