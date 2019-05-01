
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
var config = {
      '.select2'           : {},
      '.select2-deselect'  : {allow_single_deselect:true},
      '.select2-no-single' : {disable_search_threshold:10},
      '.select2-no-results': {no_results_text:'Oops, nothing found!'},
      '.select2-width'     : {width:"100%"}
    }

function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	return true;
}


var getJenisPangan=function(){
		var grup_jenis_pangan = $('#grup_jenis_pangan').val();
		var jenis_pangan = $('#jenis_pangan').parents('.dropdown');
		jQuery.ajax({
			url	:	'<?=base_url()?>irtp/get_jenis_pangan'	,
			type : 'POST',
			dataType : 'json',
			async:false,
			data	: 'grup_jenis_pangan=' + grup_jenis_pangan,
			success: function(html){
				var temp;
				temp = "<select style='max-width : 600px' class='select2' name='jenis_pangan' id='jenis_pangan' data-validation='required'><option value=''>- Pilih Nama Jenis Pangan -</option>";
				$.each(html, function(val, key){
					temp += "<option value='" + key.id_urut_jenis_pangan + "'>" + key.jenis_pangan + " </option>";
				});		
				temp += "<option value='-'>Lainnya</option></select>";		
				console.log(temp);
				jenis_pangan.empty().append(temp);
				jenis_pangan.find('#jenis_pangan').trigger('select2:updated').select2({width : '100%'});

				jQuery.ajax({
					url	:	'<?=base_url()?>irtp/get_grup_jenis_pangan'	,
					type : 'POST',
					async:false,
					dataType : 'json',
					data	: 'grup_jenis_pangan=' + grup_jenis_pangan,
					success: function(html){
						$('#label_jenis_pangan').html('Nama Jenis Pangan <b>('+html+')</b>');
					},error: function(e){
							console.log(e);
						}
					});	
			},error: function(e){
				console.log(e);
			}
		});
	}

$(document).ready(function() {
	//nama perusahaan
	
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
	$(document).on('click', '.btn-delete', function(e){
		var curr = $(this);
		curr.parents('.data-strip').remove();
	});
	
	$(document).on('click', '.btn-add', function(e){
		var DOM = $(this).parents('.clearfix');
		var curr = $(this);
		var temp = '<div class="row data-strip" style="margin-top : 0px"> \
						<div class="col-sm-4"> \
							<select class="select2" data-placeholder="Masukan Komposisi Tambahan" style="max-width : 250px;" name="komposisi_tambah[]"> \
							<?php foreach($js_komp_tmbh as $data): ?> \
								<option value="<?=$data->no_urut_btp?>"><?=addslashes($data->nama_bahan_tambahan_pangan)?></option> \
							<?php endforeach ?>	\
							</select> \
						</div> \
                        <div class="col-sm-2"> \
							<input style="max-width: 100px;" type="text" class="form-control" name="berat_bersih_tambahan[]" placeholder="Berat Bersih" data-validation="required" data-validation="number" data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)"/> \
						</div> \
						<div class="col-sm-2">\
							<select style="max-width: 120px;" class="form-control" name="satuan_komposisi[]">\
								<option value="0"> Satuan</option>\
								<option value="mL">mL</option>\
								<option value="mg">mg</option>\
								<option value="gram">gram</option>\
								<option value="Kg">Kg</option>\
							</select>\
					    </div>\
                        <div class="col-sm-2"> \
							<div class="btn btn-sm btn-primary btn-add" >Add</div> \
						</div> \
					</div><br>';
		DOM.append(temp);	
		DOM.find('select2').trigger('select2:updated').select2({'width' : '100%'});
		
		curr.html('Delete').removeClass('btn btn-sm btn-primary btn-add').addClass('btn btn-sm btn-danger btn-delete');
	});
	
	$('#pemilik_usaha').change(function(){
		var value = $(this).val();
		var penanggung = $('#penanggung_jawab');
		var telepon = $('#telepon_irtp');
		var alamat = $('#alamat_irtp');
		var nik = $('#nik');
		
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
					nik.val(value.nik);
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
				temp = "<select id='kabupaten' class='select2'>";
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

	$('#grup_jenis_pangan').change(function(id){
			getJenisPangan();
	});

	
	// $('#jenis_pangan').change(function(){
	// 	var value = $(this).val();
	// 	alert(value);
	// 	if(value=='-'){
	// 		$('#box_jenis_pangan').show();
	// 		$('#jenis_pangan_lain').val('');
	// 		$('#jenis_pangan_lain').attr("data-validation","required");
			
	// 	} else {
	// 		$('#box_jenis_pangan').hide();
	// 		$('#jenis_pangan_lain').val('');
	// 		$('#jenis_pangan_lain').removeAttr("data-validation");
	// 	}
	// });

	

	/*
	$('.btn_add').click(function(e){
		var $stat = $('.has_append');
		var $last_appended = $stat.find('.appended').last();
		$last_appended.find('.col-sm-1').html('');
		$last_appended.find('.col-sm-11').removeClass('col-sm-11').addClass('col-sm-12').find('.chzn-container').css({'width' : '100%'});
		$stat.append('
			<div class="row appended"> \
				<div class="col-sm-11"> \
					<select class="chosen-select " style="width : 100%" name="komposisi_tambah">\
					<?php foreach($js_komp_tmbh as $data): ?> \
						<option value="<?=$data->no_urut_btp?>"><?=$data->nama_bahan_tambahan_pangan?></option>\
					<?php endforeach ?>	\
					</select>\
				</div>\
				<div class="col-sm-1">\
					<a class="btn btn-primary btn_add">+</a>\
				</div>\
			</div><!-- row -->'
		);
	});
	*/
	
	$('.datetimepicker').datetimepicker();
	
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

// function cek_permohonan(){
// 	var masa_simpan = $('#masa_simpan').val();
// 	if(masa_simpan.indexOf('hari') > -1 || masa_simpan.indexOf('Hari') > -1){
// 		var r = confirm("Apakah Anda yakin?");
// 		if (r == true) {
// 			return true;
// 		} else {
// 			return false;
// 		}
// 	} else {
// 		alert('Masa simpan (kedaluwarsa) harus dalam format Hari, contoh : 90 hari');
// 		return false;
// 	}
// }
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
		<a href="<?php echo site_url()?>">Dashboard</a>
	</li>
	<li class="active">Input Data Jenis IRTP Lama</li>
</ul>

</div>		

<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Input Data Jenis IRTP Baru
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
							<h3 class="smaller lighter blue">Input Data Jenis IRTP Lama</h3>
						</div>
						<!-- <dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'role_menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Role Menu
							</a>
						</dir> -->
					</div>
					<div class="widget-box">
									<div class="widget-header widget-header-blue widget-header-flat">
										<h4 class="widget-title lighter">Status Pengajuan</h4>

										<!--<div class="widget-toolbar">-->
										<!--	<label>-->
										<!--		<small class="green">-->
										<!--			<b>Validation</b>-->
										<!--		</small>-->

										<!--		<input id="skip-validation" type="checkbox" checked="true" class="ace ace-switch ace-switch-4" />-->
										<!--		<span class="lbl middle"></span>-->
										<!--	</label>-->
										<!--</div>-->
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard-container">
												<div>
													<ul class="steps">
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

													</ul>
												</div>

												<hr />

										<div class="step-content pos-rel">
											<div class="step-pane active" data-step="1">

														<?=validation_errors()?>
													       <?= @$this->session->flashdata('error') ?>
															<?= @$this->session->flashdata('status') ?>
															<?= @$this->session->flashdata('message') ?>
														
															<?=form_open_multipart('#', array('class' => 'form-horizontal', 'id' => 'validation-form'))?>
															<?php foreach($query as $data): 
															$kota = $data->nm_kabupaten;
															?>
															<input type="hidden" name="id_pengajuan" value="<?=$data->id_pengajuan?>">
															<input type="hidden" name="kode_perusahaan" value="<?=$data->kode_perusahaan?>">
															<input type="hidden" name="kode_r_perusahaan" value="<?=$data->kode_r_perusahaan?>">
															<input type="hidden" name="nomor_permohonan" value="<?=$data->nomor_permohonan?>">
														<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pemilik_usaha">Nama Pemilik IRTP</label>

																	<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<select class="select2" name="pemilik_usaha" id="pemilik_usaha" >
																				<option value="<?=$data->nomor_permohonan?>" selected=""><?=$data->nomor_permohonan?> - <?=$data->nama_pemilik?></option>
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
																				<input value="" type="text" class="col-xs-12 col-sm-9" id="penanggung_jawab" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" name="penanggung_jawab" />
																				<p class="col-xs-12 col-sm-9 help-block">Nama Penanggung Jawab IRTP akan terisi secara otomatis setelah memilih Nama Pemilik IRTP, Contoh : Hasan Sadikin, S.Pd</p>
																			</div>
																	</div>
														</div>
														<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="no_nik">No NIK Pemilik</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="nik" name="no_nik" placeholder="Masukkan No NIK" data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)" maxlength="16" />
																				<p class="col-xs-12 col-sm-9 help-block">No NIK Pemilik IRTP (maksimal 16 digit angka)</p>
																			</div>
																		</div>
														</div>
														<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alamat IRTP</label>
																	<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<textarea id="alamat_irtp" class="input-xlarge" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" name="alamat_irtp"></textarea>
																				<p class="col-xs-12 col-sm-9 help-block">Alamat IRTP akan terisi otomatis setelah memilih Nama Pemilik IRTP, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
																			</div>
																	</div>
														</div>
														<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Telepon IRTP</label>
																	<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="telepon_irtp" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" name="telepon_irtp" />
																				<p class="col-xs-12 col-sm-9 help-block">Nomor Telepon IRTP akan terisi otomatis setelah Memilih Nama Pemilik IRTP, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
																			</div>
																	</div>
														</div>

														<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Upload Izin Usaha</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																			
																				<input type="file" name="file_siup">
																				<span id="file_siup" class="col-xs-12 col-sm-9"></span>
																				<p class="col-xs-12 col-sm-9 help-block">Scan format PDF/JPG maksimal berukuran 2 Mb</p>
																			</div>
																		</div>
														</div>

														
											</div>

										<div class="step-pane" data-step="2">
														
																<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="grup_jenis_pangan">Kategori Jenis Pangan</label>

																	<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<select class="select2" name="grup_jenis_pangan" id="grup_jenis_pangan" required>
																				<option value="">- Pilih Kategori Jenis Pangan -</option>
																				<?php foreach($js_grup_pangan as $values): ?>
																						<option value="<?=$values->kode_grup_jenis_pangan?>"><?=$values->kode_grup_jenis_pangan." ".$values->nama_grup_jenis_pangan?></option>
																					
																				<?php endforeach ?>
																				</select>	
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Kategori Jenis Pangan sesuai dengan list rujukan, Contoh : Hasil Olahan Daging Kering</p>
																			</div>
																	</div>
																</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" id="label_jenis_pangan">Nama Jenis Pangan</label>

																	<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<div class="dropdown">
																				<select style="max-width: 600px" class="select2" name="jenis_pangan" id="jenis_pangan" data-validation="required">
																					<option value="">- Pilih Nama Jenis Pangan -</option>	
																				</select>
																			</div>
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Nama Jenis Pangan sesuai dengan list rujukan, Contoh : Bihun</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group" id="box_jenis_pangan" style="display:none">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Jenis Pangan (Lainnya)</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" name="jenis_pangan_lain" class="col-xs-12 col-sm-9" id="jenis_pangan_lain" placeholder="Isi Jenis Pangan Lain jika memilih nama jenis lainnya"/>
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Jenis Pangan Lainnya, Contoh : Ganyong</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Produk Pangan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<textarea class="input-xlarge" name="deskripsi_pangan" id="deskripsi_pangan"  placeholder="Masukan Nama Produk Pangan" ></textarea>
																				<p class="col-xs-12 col-sm-9 help-block">Masukan tanggal pengajuan perpanjangan Nomor P-IRT, Contoh : 2014/08/15</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Dagang</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="nama_dagang" id="nama_dagang" placeholder="Masukan Nama Dagang" />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Dagang, Contoh : Kripik Maicih</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Jenis Kemasan</label>
																	<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<select class="select2" name="jenis_kemasan" id="jenis_kemasan" >
																				<option value="">- Pilih Jenis Kemasan -</option>
																					<?php foreach($js_kemasan as $data): ?>
																						<option value="<?=$data->kode_kemasan?>"><?=$data->jenis_kemasan?></option>
																					<?php endforeach ?>	

																				</select>	
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Jenis Kemasan yang kontak langsung dengan pangan sesuai dengan list rujukan, Contoh : Plastik</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group" id="box_jenis_kemasan" style="display:none">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Jenis Kemasan (Lainnya)</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="jenis_kemasan_lain" id="jenis_kemasan_lain" placeholder="Isi Jenis Kemasan"/>
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Jenis Kemasan Lainnya, Contoh : Karung</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Berat Bersih / Isi Bersih</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																					<input type="text" class="col-xs-12 col-sm-9" name="berat_bersih" id="berat_bersih" placeholder="Masukan Berat Bersih dengan angka." style="width: 416px;" data-validation="number" data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)"/>
																				<div class="col-sm-3">
																					<select class="form-control" name="berat_bersih_satuan" id="berat_bersih_satuan">
																							<option value="0"> Satuan</option>
																							<option value="mL">mL</option>
																							<option value="mg">mg</option>
																							<option value="gram">gram</option>
																							<option value="Kg">Kg</option>
																					</select>
																				</div>
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Berat Bersih / Isi Bersih, Contoh : 100</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Komposisi Bahan Utama</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<textarea class="input-xlarge" name="komposisi_utama" id="komposisi_utama"  placeholder="Masukan Komposisi Bahan Utama"></textarea>
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Komposisi Bahan Utama (diisi menurut komposisi yang terbesar), Contoh : 1. Tepung Terigu, 2. Garam</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Komposisi Bahan Tambahan Pangan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<div class="row data-strip">
																					<div class="col-sm-4">
																						<select style="max-width: 250px;" class="select2" name="komposisi_tambah[]" data-placeholder="Masukan Komposisi Tambahan">
																				<?php foreach($js_komp_tmbh as $data): ?>
												                                    <option value="<?=$data->no_urut_btp?>"><?php echo $data->nama_bahan_tambahan_pangan;?></option>
												                                <?php endforeach ?>				
												                                </select>
																					</div>
																					<div class="col-sm-2">
																						<input type="text" class="form-control" style="max-width: 100px;" name="berat_bersih_tambahan[]" placeholder="Berat Bersih" data-validation="number" data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)"/>
																					</div>

																					<div class="col-sm-2">
																						<select style="max-width: 120px;" class="form-control" name="satuan_komposisi[]">
																							<option value="0"> Satuan</option>
																							<option value="mL">mL</option>
																							<option value="mg">mg</option>
																							<option value="gram">gram</option>
																							<option value="Kg">Kg</option>
																						</select>
																					</div>
																					<div class="col-sm-2">
																						<div class="btn-add btn btn-sm btn-primary" style="width:65px">Add</div>
																					</div>
												                                </div>
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Komposisi Bahan Tambahan Pangan sesuai dengan list rujukan dan isilah berat bersih lengkap dengan satuannya, Contoh : Kalsium alginat (Calcium alginate) | 20 miligram/kg bahan, bila tidak menggunakan bahan tambahan, pilih "Tanpa Bahan Tambahan Pangan" kemudian diisi angka 0</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Proses Produksi</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<select class="select2" name="proses_produksi" id="proses_produksi" data-validation="required">
																				<option value="">- Pilih Proses Produksi -</option>
																					<?php foreach($js_tek_olah as $data): ?>
																						<option value="<?=$data->kode_tek_olah?>"><?=$data->kode_tek_olah." - ".$data->tek_olah?></option>
																					<?php endforeach ?>				
																				</select>
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Proses Produksi sesuai dengan list rujukan, Contoh : Menggoreng</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Scan File Rancangan Label</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="File" name="file_rl">
																				<span id="file_rl" class="col-xs-12 col-sm-9"></span>
																				
																				<p class="col-xs-12 col-sm-9 help-block">Scan format PDF/JPG maksimal berukuran 2 Mb</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group" id="box_proses_produksi" style="display:none">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Proses Produksi (Lainnya)</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="proses_produksi_lain" id="proses_produksi_lain" placeholder="Isi Proses Produksi"  />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Proses Produksi Lainnya, Contoh : Pengasapan</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Informasi Masa Simpan (Kedaluwarsa)</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="masa_simpan" id="masa_simpan" placeholder="Masukan Berat Bersih dengan angka." style="width: 416px;" data-validation="number" data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)"/>
																				<div class="col-sm-3">
																					<select class="form-control" name="waktu" id="waktu">
																							<option value="0">-Pilih-</option>
																							<option value="hari">Hari</option>
																							<option value="bulan">Bulan</option>
																					</select>
																				</div>
																				<p class="col-xs-9 help-block">Masukan Informasi Masa Simpan (Kedaluwarsa) <b>harus dalam satuan Hari dan Bulan</b>, Contoh : 90 selanjutnya hari/bulan</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Informasi Kode Produksi</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="kode_produksi"  id="info_kode_produksi" placeholder="Masukan Informasi Kode Produksi" />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Informasi Kode Produksi dengan lengkap, Contoh : A.01.12.17 menunjukkan A = Shift Pagi; 01 = Tanggal 1; 12 = bulan Desember; 17 = tahun 2017. </p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Scan File Alur Produksi</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="File" name="file_ap">
																				<span id="file_ap" class="col-xs-12 col-sm-9"></span>
																				<p class="col-xs-12 col-sm-9 help-block">Scan File Alur Produksi</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Pengajuan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="input-group col-xs-12 col-sm-9">
																				<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="yyyy-mm-dd" name="tanggal_pengajuan" placeholder="Pilih Tanggal Pengajuan" style="width: 560px;"/>
																				<span class="input-group-addon">
																					<i class="fa fa-calendar bigger-110"></i>
																				</span>
																				</div>

																				<p class="col-xs-12 col-sm-9 help-block">Pilih Tanggal Pengajuan, Contoh : 2014-08-04</p>
																		</div>
																	</div>
														
											</div>
											<!-- <button type="submit" class="btn btn-primary col-sm-12 col-xs-12 col-md-12"><b>Kirim &raquo;</b></button> -->
											<?php endforeach; 
											$kota = str_replace("Kota","",$kota); 
											$kota = str_replace("Kab.","",$kota);
											?>
												<?=form_close()?>
														
											<div class="step-pane" data-step="3">
																<div class="center">
																	<h3 class="green">Data Anda Sudah Terisi Semua.</h3>
																	<b>Pastikan data inputan yang Anda masukkan sudah benar. Silahkan lakukan pengecekan jika terdapat kesalahan input. <br>Tekan tombol Selesai di bawah ini untuk mengakhiri proses inputan dan menyimpan data inputan.</b>
																</div>
											</div>
										</div>
									</div>
									
											<hr />
											<div class="wizard-actions">
												<button class="btn btn-prev">
													<i class="ace-icon fa fa-arrow-left"></i>
													Sebelumnya
												</button>

												<button id="btn-submit" class="btn btn-warning btn-next" data-last="Selesai">
													Selanjutnya
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>
											</div>
										</div>
	<!--KONTENNYA DISINI-->
	
    
</div>
					
					</div>
			</div>
		</div>
</div>
</div>			
			<!--<div class="form-group row">
				<div class="col-xs-12">
					<label>Alamat IRTP</label>
					<textarea id="alamat_irtp" name="alamat_irtp" class="form-control" data-validation="required" placeholder="Masukan Alamat Lengkap IRTP"></textarea>
					<p class="help-block">Masukan Alamat Lengkap IRTP (Tanpa Kabupaten/Kota dan Provinsi), Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
				</div>
			</div>-->			
			
<script  type="text/javascript">


	$(document).ready(function(){
		var id="<?php echo $this->uri->segment(3); ?>";
		getDataEdit(id);
		function getDataEdit(dataID)
		{
			$.ajax({
				url:"<?php echo base_url('irtp/editPengajuan')?>"
				,type:"GET"
				,dataType:"json"
				,data:{nomor_permohonan:dataID}
				,success:function(res)
				{
					if(res.success)
					{
						$('#pemilik_usaha').select2('val',res.pengajuan.nomor_permohonan);
						$('#penanggung_jawab').val(res.pemilik.nama_penanggung_jawab);
						$('#nik').val(res.pemilik.no_nik);
						$('#alamat_irtp').val(res.pemilik.alamat_irtp);
						$('#telepon_irtp').val(res.pemilik.nomor_telefon_irtp);
						if(res.pemilik.surat_izin.path_scan_data!=null && res.pemilik.surat_izin.path_scan_data!='')
						{
							$('#file_siup').html('<a href="<?php echo base_url()?>uploads/siup/'+res.pemilik.surat_izin.path_scan_data+'">Uploaded File</a>');
						}
						$('#grup_jenis_pangan').select2('val',res.produk_pangan.grup_jenis_pangan.kode_grup_jenis_pangan);
						getJenisPangan();
						$('#jenis_pangan').select2('val',res.pengajuan.id_urut_jenis_pangan);
						$('#jenis_pangan').val(res.pengajuan.id_urut_jenis_pangan);
						$('#deskripsi_pangan').html(res.pengajuan.deskripsi_pangan);
						$('#nama_dagang').val(res.pengajuan.nama_dagang);
						$('#jenis_kemasan').select2('val',res.produk_pangan.kemasan.kode_kemasan);
						$('#jenis_kemasan').val(res.produk_pangan.kemasan.kode_kemasan);

						if(res.produk_pangan.kemasan.kode_kemasan=='6')
						{
							
							$('#box_jenis_kemasan').show();
							$('#jenis_kemasan_lain').attr("data-validation","required");
							$('#jenis_kemasan_lain').val(res.pengajuan.jenis_kemasan_lain);
						}
						else{
							$('#box_jenis_kemasan').hide();
							$('#jenis_kemasan_lain').val('');
							$('#jenis_kemasan_lain').removeAttr("data-validation");
						}

						var bbersih=res.pengajuan.berat_bersih.split(" ");
						$('#berat_bersih').val(res.pengajuan.berat_bersih);
						$('#berat_bersih_satuan').val(res.pengajuan.satuan);
						$('#komposisi_utama').val(res.pengajuan.komposisi_utama);
						$('#proses_produksi').select2('val',res.produk_pangan.teknologi.kode_tek_olah);
						$('#proses_produksi').val(res.produk_pangan.teknologi.kode_tek_olah);
						if(res.produk_pangan.teknologi.kode_tek_olah=='11'){
							$('#box_proses_produksi').show();
							$('#proses_produksi_lain').val(res.pengajuan.proses_produksi_lain);
							$('#proses_produksi_lain').attr("data-validation","required");
							
						} else {
							$('#box_proses_produksi').hide();
							$('#proses_produksi_lain').val('');
							$('#proses_produksi_lain').removeAttr("data-validation");
						}
						$('#masa_simpan').val(res.pengajuan.masa_simpan);
						$('#waktu').val(res.pengajuan.waktu);
						$('[name="waktu"]').val(res.pengajuan.waktu);
						$('#info_kode_produksi').val(res.pengajuan.info_kode_produksi);
						$('#id-date-picker-1').val(res.pengajuan.tanggal_pengajuan);
						if(res.produk_pangan.file_rl.path_scan_data!=null)
						{
							$('#file_rl').html('<a href="<?php echo base_url()?>uploads/rancangan_label/'+res.produk_pangan.file_rl.path_scan_data+'">Uploaded File</a>');
						}
						if(res.produk_pangan.file_ap.path_scan_data!=null)
						{
							$('#file_ap').html('<a href="<?php echo base_url()?>uploads/alur_produksi/'+res.produk_pangan.file_ap.path_scan_data+'">Uploaded File</a>');
						}

						for(var i=0;i<res.produk_pangan.komposisi_tambahan.length-1;i++)
						{
							$('.btn-add').click();
						}
						$('[name="komposisi_tambah[]"]').each(function(i,item){
							var data=res.produk_pangan.komposisi_tambahan[i].kode_r_komposisi;
							$(this).select2('val',data);
							$(this).val(data);
						});
						$('[name="berat_bersih_tambahan[]"]').each(function(i,item){
							var data=res.produk_pangan.komposisi_tambahan[i].berat;
							$(this).val(data);
						});
						$('[name="satuan_komposisi[]"]').each(function(i,item){
							var data=res.produk_pangan.komposisi_tambahan[i].satuan;
							$(this).val(data);
						});
					}
					else
					{
						alert('cant get data!');
						window.location.href="<?php echo base_url('irtp/output_permohonan')?>";
					}
				}
				,error(err)
				{
					alert(err);
				}
			});
		}

		$('#btn-submit').click(function(){
        	if($(this).html().includes($(this).attr('data-last'))){
            	save_data();
        	}
		});
		
		function save_data()
		{
			$("#validation-form").submit();
		}

		$("#validation-form").submit(function(e){
				e.preventDefault();
				var formData=new FormData(this);
				formData.append('file_siup',$('[name="file_siup"]')[0].files[0]);
				formData.append('file_ap',$('[name="file_ap"]')[0].files[0]);
				formData.append('file_rl',$('[name="file_rl"]')[0].files[0]);
				formData.append('jenis_pangan',$('[name="jenis_pangan"]').val());
				formData.delete('komposisi_tambah[]');
				formData.delete('berat_bersih_tambahan[]');
				formData.delete('satuan_komposisi[]');

				$('[name="komposisi_tambah[]"]').each(function (i, item) {
					var value = $(item).val();
					formData.append('komposisi_tambah[]', value);
				});

				$('[name="berat_bersih_tambahan[]"]').each(function (i, item) {
					var value = $(item).val();
					formData.append('berat_bersih_tambahan[]', value);
				});

				$('[name="satuan_komposisi[]"]').each(function (i, item) {
					var value = $(item).val();
					formData.append('satuan_komposisi[]', value);
				});

				$.ajax({
				url:'<?php echo base_url()?>irtp/proccess_edit',
				type:'POST',
				data:formData,
				dataType:'json',
				async:false,
				contentType: false, 
        		processData: false,
				success:function(response){
					if(response.success)
					{
						alert('Data Berhasil Tersimpan');
						window.location.href='<?php echo base_url()?>irtp/edit/'+$('[name="nomor_permohonan"]').val();
					}
					else{
						alert('Data Gagal Tersimpan');
					}
				},
				error:function(stat,res,err)
				{
					alert(err);
				}
				});
		});
		
	});
    
    
</script>

<script type="text/javascript">
		jQuery(function($) {

				//documentation : http://docs.jquery.com/Plugins/Validation/validate
			
				$.mask.definitions['~']='[+-]';
				$('#phone').mask('(999) 999-9999');
			
				jQuery.validator.addMethod("phone", function (value, element) {
					return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
				}, "Enter a valid phone number.");
			
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						email: {
							required: true,
							email:true
						},
						password: {
							required: true,
							minlength: 5
						},
						pemilik_usaha: {
							required: true
						},
						grup_jenis_pangan: {
							required: true	
						},
						no_nik: {
							required: true	
						},
						phone: {
							required: true,
							phone: 'required'
						},
						state: {
							required: true
						}
					},
			
					messages: {
						email: {
							required: "Please provide a valid email.",
							email: "Please provide a valid email."
						},
						password: {
							required: "Please specify a password.",
							minlength: "Please specify a secure password."
						},
						no_nik: "<p class='col-xs-12 col-sm-9 help-block'>No NIK Belum Di Isi!</p>",
						grup_jenis_pangan: "<p class='col-xs-12 col-sm-9 help-block'>Silahkan Di Isi Terlebih Dulu!</p>",
						state: "Please choose state",
						pemilik_usaha: "<p class='col-xs-12 col-sm-9 help-block'>Anda Belum Memilih Pemilik Usaha</p>"
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}
				});
				})
</script>