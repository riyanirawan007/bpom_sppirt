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

function loadAndSetKabupaten(id_provinsi, id_kabupaten) {
	var kabupaten = $('#kabupaten').parents('.dropdown');
	jQuery.ajax({
		url	:	'<?=base_url()?>penerbitan_sertifikat/get_kabupaten'	,
		type : 'POST',
		dataType : 'json',
		data	: 'provinsi=' + id_provinsi,
		success: function(html){
			var temp;
			temp = "<select id='kabupaten' class='chosen-select'>";
			temp += "<option value=''>-- pilih kabupaten --</option>";
			$.each(html, function(val, key){
				temp += "<option value='" + key.no_kabupaten+ "'>" + key.nm_kabupaten + "</option>";
			});				
			temp += "</select>";
			kabupaten.empty().append(temp);

			if (id_kabupaten) {
				$("#kabupaten").val(id_kabupaten).trigger('chosen:updated').chosen({'width' : '100%'});
			} else {
				$('#kabupaten').trigger('chosen:updated').chosen({'width' : '100%'});
			}
		},error: function(e){
			console.log(e);
		}
	});	
}

$(document).ready(function() {
	//nama perusahaan
	var config = {
      '.select2'           : {},
      '.chosen-select2-deselect'  : {allow_single_deselect:true},
      '.chosen-select2-no-single' : {disable_search_threshold:10},
      '.chosen-select2-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select2-width'     : {width:"100%"}
    }
    // for (var selector in config) {
    //   $(selector).chosen(config[selector]);
    // }
	
	$(document).on('change', '#nomor_permohonan_irtp', function(evt, key){
		var npirt = $('#nomor_pirt');
		var tgl_perm = $('#tanggal_perhomonan_pirt');
		
		var data = $(this).val();
		$.ajax({
			url	: '<?=base_url()?>penerbitan_sertifikat/get_irtp_raw',
			type	: 'POST',
			dataType: 'json',
			data	: 'nomor=' + data + '&mode="all"' ,
			success: function(data){
				npirt.val(data.no_pirt);
				$("#provinsi").val(data.provinsi);
				$("#kabupaten").val(data.kabupaten);
			},error: function(e){
				console.log(e);
			}
		});
	});
	
	// $('.datetimepicker').datetimepicker();
	
	/*
	$("#pil_perusahaan").autocomplete({
		source: "<?php echo base_url(); ?>pb2kp/get_perusahaan" 
	});
	*/
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
		<a href="<?php echo site_url()?>">Dashboard</a>
	</li>
	<li class="active">Input Data Penerbitan Sertifikat</li>
</ul>

</div>

<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Input Data Penerbitan Sertifikat
			</small>
		</h1>
	</div> -->

<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="smaller lighter blue">Input Data Penerbitan Sertifikat</h3>
						</div>
						<!-- <dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'role_menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Role Menu
							</a>
						</dir> -->
					</div>
					<!-- isi -->

<div class="widget-box">
						<div class="widget-header widget-header-blue widget-header-flat">
							<h4 class="widget-title lighter">Penerbitan Sertifikat</h4>
						</div>

									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard-container">
												<!-- <div>
													<ul class="steps">
														<li data-step="1" class="active">
															<span class="step">1</span>
															<span class="title">Penyelenggaraan Penyuluhan Keamanan Pangan</span>
														</li>

														<li data-step="2">
															<span class="step">2</span>
															<span class="title">Finish</span>
														</li>

													</ul>
												</div> -->

												<hr />

										<div class="step-content pos-rel">
													<!-- <div class="step-pane active" data-step="1"> -->
													
																	<?= @$this->session->flashdata('status'); ?>
																	<?= @$this->session->flashdata('error'); ?>	
																	<?= @$this->session->flashdata('message'); ?>
																	<?php $attr_form = array('onsubmit' => 'return cek_form()'); ?>	
																	<?=form_open_multipart('penerbitan_sertifikat/add', array('class' => 'form-horizontal','id'=>'validation-form'))?>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Pemberian Nomor Sertifikat P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="input-group col-xs-12 col-sm-9">
																				<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy" value="<?= isset($old_inputs['tanggal_pemberian_pirt'])? $old_inputs['tanggal_pemberian_pirt'] : '' ?>" name="tanggal_pemberian_pirt" required placeholder="Pilih Tanggal Pemberian Nomor Sertifikat PIRT" >
																				<span class="input-group-addon">
																					<i class="fa fa-calendar bigger-110"></i>
																				</span>
																			</div>
																				<p class="col-xs-12 col-sm-9 help-block">
																			Pilih Tanggal pemberian Nomor Sertifikat P-IRT, Contoh : 2014-08-07</p>
																			
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Permohonan IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<select class="select2" id="nomor_permohonan_irtp" data-validation="required" name="nomor_permohonan_irtp">
																					<option value="">- Pilih Nomor Permohonan IRTP -</option>
																					<?php foreach($no_irtp as $data): ?>
																						<option <?php (isset($old_inputs['nomor_permohonan_irtp']) AND $old_inputs['nomor_permohonan_irtp'] == $data->nomor_permohonan)? 'selected' : '' ?> 
																							value="<?=$data->nomor_permohonan?>">
																								<?=$data->nomor_permohonan.' - '.$data->nama_perusahaan.' - '. $data->nama_pemilik.' - '.$data->nama_dagang?>
																						</option>
																					<?php endforeach ?>				
																					</select>
																				<p class="col-xs-12 col-sm-9 help-block">Keterangan Pilihan : Nomor Permohonan - Nama Perusahaan - Nama Pemilik - Nama Dagang, Contoh : 17331151114001 - Usaha Rivi - Rivi Anggara - Kentang Rivi</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Sertifikat P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="nomor_pirt" value="<?= isset($old_inputs['nomor_pirt'])? $old_inputs['nomor_pirt'] : '' ?>" name="nomor_pirt" data-validation="required length" placeholder="Masukan Nomor Sertifikat P-IRT" data-validation-length="max16" />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Nomor Sertifikat P-IRT (Maksimum 15 digit). Nomor ini dapat disesuaikan dengan sistem penomoran di masing-masing Dinkes, Contoh : 1234567890123-45</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Provinsi</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" value="<?= isset($old_inputs['provinsi'])? $old_inputs['provinsi'] : '' ?>" name="provinsi" id="provinsi" readonly />
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Provinsi sesuai dengan list rujukan</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Kabupaten/Kota</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" value="<?= isset($old_inputs['kabupaten'])? $old_inputs['kabupaten'] : '' ?>" name="kabupaten" id="kabupaten" readonly />
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor HK</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="nomor_hk" name="nomor_hk" data-validation="required" value="HK.03.1.23.04.12.2205" placeholder="HK.03.1.23.04.12.2205" />
																				
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Kepala Dinas</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" value="<?= isset($old_inputs['nama_kepala_dinas'])? $old_inputs['nama_kepala_dinas'] : '' ?>" name="nama_kepala_dinas" data-validation="required" placeholder="Masukan Nama Kepala Dinas"  />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Kepala Dinas, Contoh : Samsuri, SE., MM.</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">NIP Kepala Dinas</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" value="<?= isset($old_inputs['nip'])? $old_inputs['nip'] : '' ?>" name="nip" data-validation="required" placeholder="Masukan NIP Kepala Dinas" onkeypress="return isNumberKey(event)"  />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan NIP Kepala Dinas, Contoh : 123456789012345</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Scan (Label Final)</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="file" class="col-xs-12 col-sm-9" value="<?= isset($old_inputs['file_foto'])? $old_inputs['file_foto'] : '' ?>" name="file_foto" data-validation="required" accept=".jpg,.png,.gif,.pdf" />
																				<p class="col-xs-12 col-sm-9 help-block">Scan format PDF/JPG maksimal berukuran 2 Mb</p>
																			</div>
																		</div>
																	</div>

																<!--<button type="submit" class="btn btn-primary col-sm-12 col-xs-12 col-md-12">Kirim &raquo;</button>-->
																
																	</div>
																	
													


													<!-- <div class="step-pane" data-step="2">
																<div class="center">
																	<h3 class="green">Data Anda Sudah Terisi Semua.</h3>
																	<b>Pastikan data inputan yang Anda masukkan sudah benar. Silahkan lakukan pengecekan jika terdapat kesalahan input. <br>Tekan tombol Selesai di bawah ini untuk mengakhiri proses inputan dan menyimpan data inputan.</b>
																</div>
															</div> -->

												<!-- </div> -->
											</div>

											<hr />
											<div class="wizard-actions">
												

												<button type="submit" class="btn btn-warning btn-next">
													Kirim
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>
												<?=form_close()?>
											</div>


				</div>
			</div>
		</div>
		</div>
</div>

</div>

