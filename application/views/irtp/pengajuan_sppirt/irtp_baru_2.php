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
		<select style="max-width: 120px;" class="form-control" name="satuan_baru">\
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

		curr.html('Delete').removeClass('btn-primary btn-add').addClass('btn-danger btn-delete');
	});
	
	$('#pemilik_usaha').change(function(){
		var value = $(this).val();
		var penanggung = $('#penanggung_jawab');
		var telepon = $('#telepon_irtp');
		var alamat = $('#alamat_irtp');
		
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
				temp = "<select class='chosen-select col-sm-6' name='jenis_pangan' id='jenis_pangan' data-validation='required'><option value=''>- Pilih Nama Jenis Pangan -</option>";
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
							<h4 class="widget-title lighter">IRTP baru - Informasi Produk Pangan untuk nomor registrasi : <?= $perus_id ?> </h4>


						</div>

						<div class="widget-body">
							<div class="widget-main">
								<div id="fuelux-wizard-container">
									<div>
										<!-- <ul class="steps">
											<li data-step="1">
												<span class="step">1</span>
												<span class="title">Informasi Kepemilikan</span>
											</li>

											<li data-step="2" class="active">
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
										<!-- <div class="step-pane active" data-step="2"> -->
											<div class="row">
												<div class="col-sm-12">		
													<div class="form-group-cat">
														<?= @$this->session->flashdata('error') ?>
														<?= @$this->session->flashdata('status') ?>
														<?= @$this->session->flashdata('message') ?>

														<?php $attr_form = array('onsubmit' => 'return cek_form()','class' => 'form-horizontal'); ?>  
														<?=form_open_multipart('irtp/set_data_irtp_spp_baru', $attr_form)?>

														<input type="hidden" name="pemilik_usaha" value="<?= $perus_id ?>">

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Kategori Jenis Pangan</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">

																	<select class="select2" name="grup_jenis_pangan" id="grup_jenis_pangan" >
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
																		<select class="chosen-select col-sm-6" name="jenis_pangan" id="jenis_pangan" >
																			<option value="">- Pilih Nama Jenis Pangan -</option>	
																		</select>
																		<p class="col-xs-12 col-sm-9 help-block">Pilih Nama Jenis Pangan sesuai dengan list rujukan, Contoh : Bihun</p>
																	</div>
																</div>
															</div>
														</div>

														<div class="form-group" id="box_jenis_pangan" style="display:none">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Jenis Pangan (Lainnya)</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input type="text" name="jenis_pangan_lain" class="col-xs-12 col-sm-9" id="jenis_pangan_lain" placeholder="Isi Jenis Pangan Lain"/>
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Jenis Pangan Lainnya, Contoh : Ganyong</p>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Produk Pangan</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<textarea style="margin: 0px; width: 601px; height: 55px;" class="input-xlarge" name="deskripsi_pangan"id="deskripsi_pangan"  placeholder="Masukan Nama Produk Pangan" /></textarea>
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Produk Pangan, Contoh : Bihun Rasa Bayam</p>
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
																	<input type="text" class="col-xs-12 col-sm-9" name="berat_bersih" id="berat_bersih"  placeholder="Masukan Berat Bersih / Isi Bersih" />
																	<p class="col-xs-12 col-sm-9 help-block">Masukan Berat Bersih / Isi Bersih, Contoh : 100 gram</p>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Komposisi Bahan Utama</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<textarea style="margin: 0px; width: 601px; height: 55px;" class="input-xlarge" name="komposisi_utama" id="komposisi_utama"  placeholder="Masukan Komposisi Bahan Utama"  /></textarea>
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
																			<input type="text" class="form-control" style="max-width: 100px;" name="berat_bersih_tambahan[]" placeholder="Berat Bersih" />
																		</div>

																		<div class="col-sm-2">
																			<select style="max-width: 120px;" class="form-control" name="satuan_baru">
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
																	<input type="File" name="rancangan_label">
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
																	<input type="text" class="col-xs-12 col-sm-9" name="masa_simpan" id="masa_simpan" placeholder="Masukan Berat Bersih dengan angka." style="width: 416px;" data-validation="number" data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)">
																	<div class="col-sm-3">
																		<select class="form-control" name="waktu">
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
													</div>

													<div class="form-group-cat">	

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Scan File Alur Produksi</label>

															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<input type="File" name="alur_produksi">
																	<p class="col-xs-12 col-sm-9 help-block">Scan File Alur Produksi</p>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Pengajuan</label>

															<div class="col-xs-12 col-sm-9">
																<div class="input-group col-xs-12 col-sm-9">
																	<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="yyyy-mm-dd" name="tanggal_pengajuan" placeholder="Pilih Tanggal Pengajuan"/>
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
																</div>
																<p class="col-xs-12 col-sm-9 help-block">Pilih Tanggal Pengajuan, Contoh : 2014-08-04</p>

															</div>
														</div>


													</div>

													<div class="wizard-actions">
														<!-- <a href="<?= base_url('irtp/batal') ?>" class="btn btn-danger" onclick="return confirm('Dengan menekan tombol ini semua data yang telah tersimpan untuk nomor registrasi <?= $perus_id ?> akan dihapus')">Batal</a> -->
														<button class="btn btn-warning"  type="submit">
															Selesai
															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
													<?=form_close()?>
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

</div>