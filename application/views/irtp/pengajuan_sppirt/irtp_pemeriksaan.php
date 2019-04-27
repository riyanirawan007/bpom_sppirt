<style type="text/css">
	table.center tr th{
		text-align: center;
		vertical-align: middle
	}
</style>
<script type="text/javascript">
/*
function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	return true;
*/
function getSelectedCheckboxes() {
	var list_selected = [];
	
	$.each($('tr input[type=checkbox]'), function(key, value){
		if($(this).is(':checked') == true){
			list_selected.push({
				checkbox: this,
				type: $(this).attr('class'),
				label: getCheckboxLabel(this)
			});
		}
	});

	return list_selected;
}


function makeRecords(jml){
	console.log(jml);
	var href = 'step-2';
	var obj = $('.' + href + '.table-appended');
	var rec = obj.find('tr').not('.table-head').length;
	var toRec = jml;
	var differ = Math.abs(rec - toRec);
	var temp = '\
		<tr>\
			<td style="vertical-align:middle">\
				<textarea class="form-control" readonly class="form-control" id="no_ketidaksesuaian" name="ketidaksesuaian[]" placeholder="">{{ label }}</textarea> \
			</td>\
			<td>\
				<textarea class="form-control" id="{{ id_plor }}" name="plor[]" placeholder=""></textarea> \
			</td>\
			<td>\
				<input type="text" readonly class="form-control" id="kode_ketidaksesuaian" placeholder="" value="{{ type }}"/>\
			</td>\
			<td>\
				<input type="text" class="form-control datetimepicker" id="date" name="tanggal_batas_waktu[]" placeholder="" value=""/>\
			</td>\
		</tr>';
			
	var base = "";

	var list_selected = getSelectedCheckboxes();
	var numberr = 1;	
	for(i in list_selected) {
		var selected = list_selected[i];
		var data = {
			id_plor : "step2_id_plor"+numberr,
			label: selected.label,
			type: getType(selected.type)
		};
		numberr++;
		var tr = renderTemplate(temp, data);
		base += tr;
	}

	obj.find('.table-init').html(base);
	$('.table-init .datetimepicker').datetimepicker();
	$("#no_record_ketidaksesuaian").val(list_selected.length);
}

function makeStep3() {
	var list_selected = getSelectedCheckboxes();
	
	var tempStep3 = $("template#row-step3").html();
	var $tbody = $("#tbody-step3");

	$tbody.html("");
	var numberr = 1;
	for(i in list_selected) {
		var selected = list_selected[i];
		var data = {
			id_plor : "step3_id_plor"+numberr,
			label: selected.label,
			type: getType(selected.type)
		};
		
		var content = renderTemplate(tempStep3, data);
		
		
		var rowStep3 = $(content);
		$tbody.append(rowStep3);
		
		$("#step3_id_plor"+numberr).html($("#step2_id_plor"+numberr).val());
		numberr++;
	}
	
	
	
	
	$("#step3-count-ketidaksesuaian").val(list_selected.length);
}


function getCheckboxLabel(checkbox) {
	var $label = $(checkbox).closest('tr').find('td').eq(1);
	return $label.text();
}

function releaseAnimate(obj, transition, behave){		
	setTimeout(function(obj, transition, behave){
		if(behave === 'out'){
			obj.removeClass(transition).addClass('hide');			
		}else{
			obj.removeClass(transition);
		}
	}, 400, obj, transition, behave);
}

function renderTemplate(template, data) {
	var result = template;
	for(key in data) {
		var regex = new RegExp('{{[ ]*'+key+'[ ]*}}', 'g');
		result = result.replace(regex, data[key]);
	}
	return result;
}

function getType(className) {
	switch(className) {
		case 'opt-se': return "Serius";
		case 'opt-kr': return "Kritis";
		case 'opt-mi': return "Minor";
		case 'opt-ma': return "Mayor";
	}
}

function knowledge(level){
	var freq;
	switch(level){
		case '1':
			freq = 'Setiap dua bulan';
		break;
		case '2':
			freq = 	'Setiap bulan';
		break;
		case '3':
			freq = 'Setiap dua minggu';
		break;
		case '4':
			freq = 'Setiap hari';
		break;
		default:
			freq = 'Default';
		break;
	}
	return freq;
}

function retr(){
	var minor = jQuery('.mi-res').html();		
	var mayor = jQuery('.ma-res').html();		
	var serius = jQuery('.se-res').html();		
	var kritis = jQuery('.kr-res').html();
	var level = jQuery('#level');
	var dom_level = jQuery('#DOM-level');
	var dom_freq = jQuery('#DOM-freq');
	var freq = jQuery('#frequency');
	

	if((mayor == 1) & (serius == 0) & (kritis == 0)){
		level.html('Level I');
		dom_level.val('Level I');
		dom_freq.val(knowledge('1'));
		freq.html(knowledge('1'));
	} else if((mayor > 1) & (mayor <= 3) & (serius == 0) & (kritis == 0)){
		level.html('Level II');
		dom_level.val('Level II');
		dom_freq.val(knowledge('2'));
		freq.html(knowledge('2'));
	} else if((serius >= 1) & (serius <= 4) & (kritis == 0)){
		level.html('Level III');
		dom_level.val('Level III');
		dom_freq.val(knowledge('3'));
		freq.html(knowledge('3'));
	} else if((mayor >= 4) & (serius == 0) & (kritis == 0)){
		level.html('Level III');
		dom_level.val('Level III');
		dom_freq.val(knowledge('3'));
		freq.html(knowledge('3'));
	} else if(kritis >= 1){
		level.html('Level IV');
		dom_level.val('Level IV');
		dom_freq.val(knowledge('4'));
		freq.html(knowledge('4'));
	} else if((kritis == 0) & (serius >= 5)){
		level.html('Level IV');
		dom_level.val('Level IV');
		dom_freq.val(knowledge('4'));
		freq.html(knowledge('4'));
	} else{
		level.html('Level I');
		dom_level.val('Level I');
		dom_freq.val(knowledge('1'));
		freq.html(knowledge('1'));
	}
}

function loadAndSetProvinsi(id_kabupaten) {
	$.ajax({
		url	: '<?=base_url()?>irtp/get_kabupaten_with_provinsi',
		type: 'POST',
		data: {
			id_kabupaten: id_kabupaten
		},
		dataType: 'json',
		success: function(data) {
			if ($.isArray(data) && data[0]) {
				$("input#provinsi").val(data[0].nama_propinsi);
				$("input#kabupaten_kota").val(data[0].nm_kabupaten);
			}
		}
	});
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

    var selected_checkboxes = [];
	
	$(document).on('change', 'input[type=checkbox]', function(){
		var data = $(this).attr('class');
		var i = 0;
		var list_selected = [];
		
		$.each($('input[type=checkbox].'+data), function(key, value){
			if($(this).is(':checked') == true){
				i++;
			}
		});

		$('.DOM-' + data).html(i);
		retr();

		selected_checkboxes = list_selected;
		$(window).trigger('checkboxes:update', [selected_checkboxes]);

		return 1;
	});

	
	
	$(document).on('change', '#nomor_permohonan', function(evt, key){
		var nama_pemilik = $('#nama_pemilik');
		var alamat = $('#alamat_pemilik');
		var nama_penanggung = $('#penanggung_jawab');
		var nama_jenis_pangan = $('#nama_jenis_pangan');
		var no_permohonan = $('#nomor_permohonan');
		var nama_fasilitas_periksa = $('#nama_fasilitas_periksa');
		var provinsi = $('#provinsi');
		var kabupaten_kota = $('#kabupaten_kota');
		
		var data = $(this).val();
		$('.nomor_permohonan').val(data);
		$.ajax({
			url	: '<?=base_url()?>irtp/get_irtp_raw',
			type	: 'POST',
			dataType: 'json',
			data	: 'nomor=' + data + '&mode=NULL',
			success: function(html){
				console.log(html);
				$.each(html, function(key, value){					
					nama_fasilitas_periksa.val(value.nama_perusahaan);		
					nama_pemilik.val(value.nama_pemilik);		
					alamat.val(value.alamat_irtp);
					provinsi.val(value.nama_propinsi);
					kabupaten_kota.val(value.nm_kabupaten);
					nama_penanggung.val(value.nama_penanggung_jawab);
					nama_jenis_pangan.val(value.jenis_pangan);
				});
			},error: function(e){
				console.log(e);
			}
		});
		
	});

	

	$(window).on('load_irt:success', function(event, result) {
		loadAndSetProvinsi(result[0].id_r_urut_kabupaten);
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
				$.each(html, function(val, key){
					temp += "<option value='" + key.no_kabupaten + "'>" + key.no_kabupaten + ". " + key.nm_kabupaten + "</option>";
				});				
				console.log($('#kabupaten'));
				
				//$('#kabupaten').css({'display' : 'block'}).removeClass('chzn-done');
				//$('#kabupaten').next().remove();
				console.log($('#kabupaten').html(temp));
				$('#kabupaten').trigger('liszt:updated').chosen({'width' : '100%'});
				
			},error: function(e){
				console.log(e);
			}
		});	
	});
	
	$('.form-bag').not(':eq(0)').addClass('hide');
	
	$('.form-bag').find('[data-req="next-btn"]').click(function(e){		
		$('html, body').animate({scrollTop : 0},400);
		
		var minor = parseInt($('.mi-res').html());
		var mayor = parseInt($('.ma-res').html());
		var serius= parseInt($('.se-res').html());
		var kritis= parseInt($('.kr-res').html());
		
		var jml 	= minor + mayor + serius + kritis;
		// mi = 1 sr = 1 kr = 2
		// jml = 4
		
		setTimeout(function(obj){
			var curr = $(obj).parents('.form-bag');
			var next = $(obj).parents('.form-bag').next();
			
			curr.addClass('animated fadeOutLeftBig');	 
			setTimeout(function(curr, next){
				next.addClass('animated fadeInRightBig').removeClass('hide');
				releaseAnimate(curr, 'animated fadeOutLeftBig', 'out');
			}, 400, curr, next);
			
			setTimeout(function(obj){			
				releaseAnimate(obj, 'animated fadeInRightBig', 'in');
			}, 800, next);
		}, 400, $(this));
		
	});
	
	$('.form-bag').find('[data-req="prev-btn"]').click(function(e){
		$('html, body').animate({scrollTop : 0},400);
		
		setTimeout(function(obj){
			var curr = $(obj).parents('.form-bag');
			var prev = $(obj).parents('.form-bag').prev();
			
			curr.addClass('animated fadeOutRightBig');	 
			setTimeout(function(curr, prev){
				prev.addClass('animated fadeInLeftBig').removeClass('hide');
				releaseAnimate(curr, 'animated fadeOutRightBig', 'out');	
			}, 400, curr, prev);
			
			setTimeout(function(obj){			
				releaseAnimate(obj, 'animated fadeInLeftBig', 'in');
			}, 800, prev);
		}, 400, $(this));
	});
	
	$('.records-update').chosen().change(function(e){
		var href = $(this).attr('data-href');
		// step-2
		var obj = $('.' + href + '.table-appended');
		// .step-2 .table-appended
		var rec = obj.find('tr').not('.table-head').length;
		var toRec = $(this).val();
		var differ = Math.abs(rec - toRec);
		if(href == 'step-2'){
			var temp = '<tr>\
								<td style="vertical-align:middle">\
									<select class="chosen-select col-sm-12" name="ketidaksesuaian[]">\
										<option value="">- Nomor Ketidaksesuaian-</option>\
										<?php foreach($nomor_ketidaksesuaian as $data): ?>
										<option value="<?=$data->kode_ketidaksesuaian?>"><?=$data->kode_ketidaksesuaian.". ".$data->nama_ketidaksesuaian?></option>\
										<?php endforeach ?>
									</select>\
								</td>\
								<td>\
									<select class="chosen-select col-sm-12" name="no_plor[]">\
										<option value="">- Pilih PLOR -</option>\
										<?php foreach($plor as $data): ?>
										<option value="<?=$data->kode_plor?>"><?=$data->kode_plor.". ".$data->nama_plor?></option>\
										<?php endforeach ?>
									</select>\
								</td>\
								<td>\
									<select class="chosen-select col-sm-12" name="kode_ketidaksesuaian[]">\
										<option value="">- Pilih kriteria kesesuaian -</option>\
										<?php foreach($sesuaian as $data): ?>
										<option value="<?=$data->kode_kriteria_ketidaksesuaian?>"><?=$data->kode_kriteria_ketidaksesuaian.". ".$data->nama_kriteria_ketidaksesuaian?></option>\
										<?php endforeach ?>
									</select>\
								</td>\
								<td style="vertical-align:middle" align="center"><input type="text" class="form-control datetimepicker" name="batas_waktu_pemeriksaan[]" placeholder=""/></td>\
							</tr>';
		} else {
			var temp = '<tr>\
                                <td style="vertical-align:top">\
                                     <select class="chosen-select col-sm-12" name="nomor_ketidaksesuaian[]">\
                                        <?php foreach($nomor_ketidaksesuaian as $data): ?>\
                                            <option value="<?=$data->kode_ketidaksesuaian?>"><?=$data->kode_ketidaksesuaian.'. '.$data->nama_ketidaksesuaian?></option>\
                                        <?php endforeach ?>\
                                    </select>\
                                </td>\
                                <td>\
                                    <select class="chosen-select col-sm-12" name="kriteria_plor[]">\
                                        <?php foreach($plor as $data): ?>\
                                            <option value="<?=$data->kode_plor?>"><?=$data->nama_plor?></option>\
                                        <?php endforeach ?>\
                                    </select>\
                                </td>\
                                <td>\
                                    <select class="chosen-select col-sm-12" name="kriteria_ketidaksesuaian[]">\
                                        <?php foreach($sesuaian as $data): ?>\
                                            <option value="<?=$data->kode_kriteria_ketidaksesuaian?>"><?=$data->nama_kriteria_ketidaksesuaian?></option>\
                                        <?php endforeach ?>\
                                    </select>\
                                </td>\
                                <td>\
                                    <input type="text" name="tindakan_perbaikan[]" class="form-control" data-validation="required" placeholder="">\
                                </td>\
                                <td style="vertical-align:top">\
                                    <select class="chosen-select col-sm-12" name="status_verifikasi[]">\
                                        <option value="1">Sesuai</option>\
                                        <option value="0">Tidak Sesuai</option>\
                                    </select>\
                                </td>\
                            </tr>';
		}
		var base = "";
		var choose = confirm('Data yang anda input sebelumnya akan hilang jika melakukan update record. Apakah anda yakin?');
		if(choose == true){
			for(i = 1; i <= toRec; i++){
				base += temp;
			}
					
			obj.find('.table-init').html(base);
			obj.find('.table-init select').trigger('chosen:updated');
			$('.datetimepicker').datetimepicker();
			obj.find('.table-init select').chosen();	
		}else{
			e.preventDefault();
			return false;
		}
	});
	
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

	
	// $('#nip_pengawas').change(function(){
	// 	var value = $(this).val();
		
	// 	if(value==''){
	// 		$('#box_nama_pengawas').show();
	// 		$('#nama_pengawas_lain').val('');
	// 		$('#nama_pengawas_lain').attr("data-validation","required");
			
	// 	} else {
	// 		$('#box_nama_pengawas').hide();
	// 		$('#nama_pengawas_lain').val('');
	// 		$('#nama_pengawas_lain').removeAttr("data-validation");
	// 	}
	// });
	
	
	$('#nip_verifikator').change(function(){
		var value = $(this).val();
		
		if(value==''){
			$('#box_nama_verifikator').show();
			$('#nama_verifikator_lain').val('');
			$('#nama_verifikator_lain').attr("data-validation","required");
			
		} else {
			$('#box_nama_verifikator').hide();
			$('#nama_verifikator_lain').val('');
			$('#nama_verifikator_lain').removeAttr("data-validation");
		}
	});

	$(document).on('click', '.btn-add', function(e){
		var DOM = $(this).parents('.anggota_form');
		var curr = $(this);
		DOM.after(DOM.clone());
		last_cl = $('.anggota_form').last();
		last_cl.find('.chzn-container').remove();
		last_cl.find('select').trigger('chosen:updated').chosen({'width' : '485px'});
		curr.html('Delete').removeClass('btn-primary btn-add').addClass('btn-danger btn-delete');
	});

	$(document).on('click', '.btn-delete', function(e){
		var curr = $(this);
		curr.parents('.anggota_form').remove();
	});

	$(document).on('click', '.btn-add-obs', function(e){
		var DOM = $(this).parents('.observer_form');
		var curr = $(this);
		DOM.after(DOM.clone());
		last_cl = $('.observer_form').last();
		last_cl.find('input').val('');
		curr.html('Delete').removeClass('btn-primary btn-add-obs').addClass('btn-danger btn-delete-obs');
	});

	$(document).on('click', '.btn-delete-obs', function(e){
		var curr = $(this);
		curr.parents('.observer_form').remove();
	});
});
</script>	

<!--<link href="<?php echo base_url();?>css/bpom.css" rel="stylesheet">	-->
<link href="<?php echo base_url();?>css/chosen/chosen.min.css" rel="stylesheet">	
<script type="text/javascript" src="<?=base_url()?>js/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/chosen/prism.js"></script>

<div class="widget-box">
									<div class="widget-header widget-header-blue widget-header-flat">
										<h4 class="widget-title lighter">Pemeriksaan Sarana IRTP</h4>

										<!-- <div class="widget-toolbar">
											<label>
												<small class="green">
													<b>Validation</b>
												</small>

												<input id="skip-validation" type="checkbox" class="ace ace-switch ace-switch-4" />
												<span class="lbl middle"></span>
											</label>
										</div> -->
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard-container">
												<div>
													<ul class="steps">
														<li data-step="1" class="active">
															<span class="step">1</span>
															<span class="title">Pemeriksaan Sarana Produksi P-IRT (Bagian 1)</span>
														</li>


														<li data-step="2">
															<span class="step">2</span>
															<span class="title">Finish</span>
														</li>

													</ul>
												</div>

												<hr />

										<div class="step-content pos-rel">
													<div class="step-pane active" data-step="1">
																	

																	<?= @$this->session->flashdata('error') ?>
																	<?= @$this->session->flashdata('status') ?>
																	<?= @$this->session->flashdata('message') ?>
																	
															        <?php $attr_form = array('onsubmit' => 'return cek_form()'); ?>  
																	<?=form_open_multipart('irtp/set_data_irtp_pemeriksaan', $attr_form, array('class' => 'form-horizontal', 'id' => 'sample-form'))?>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tujuan Pemeriksaan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="form-group">
																				<input type="text" readonly class="col-xs-12 col-sm-9" value="Pemberian SPP-IRT Baru">
																				
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text"></label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="form-group">
																				<input type="hidden" name="tujuan_pemeriksaan" value="1">
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Permohonan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="form-group">
																				<select class="col-xs-12 col-sm-9 select2" id="nomor_permohonan" data-validation="required" name="nomor_permohonan">
										                                        <option value="">- Pilih Nomor Permohonan IRTP -</option>
										                                    <?php foreach($no_sert as $data): ?>
										                                        <option value="<?=$data->nomor_permohonan?>"><?=$data->nomor_permohonan.' - '.$data->nama_perusahaan.' - '. $data->nama_pemilik.' - '.$data->nama_dagang?></option>
										                                    <?php endforeach ?>				
										                                    </select>
										                                 </div>
										                                <p class="col-xs-12 col-sm-9 help-block">Keterangan Pilihan : Nomor Permohonan - Nama Perusahaan - Nama Pemilik - Nama Dagang, Contoh : 123/4567/89 - IRTP Maju Jaya - Rivi Ahmad - Kentang Rivi</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama IRTP Yang Diperiksa</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" readonly id="nama_fasilitas_periksa" name="nama_fasilitas_periksa" data-validation="required" placeholder="Masukan Nama IRTP Yang Diperiksa" />					
                                												<p class="col-xs-12 col-sm-9 help-block">Masukan Nama IRTP yang diperiksa, Contoh : CV. Sukses Makmur</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Penanggung Jawab IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="penanggung_jawab" placeholder="Masukan Nama Penanggung Jawab IRTP" readonly />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Penanggung Jawab IRTP, Contoh : Hasan Sadikin S.Pd</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alamat IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<textarea name="alamat_irtp" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" id="alamat_irtp" class="form-control" data-validation="required" style="margin: 0px; width: 685px; height: 55px;"></textarea>
                                												<p class="col-xs-12 col-sm-9 help-block">Masukan Alamat IRTP yang diperiksa, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
																			</div>
																		</div>
																	</div>

																	
																	<?=form_close()?>
													</div>

													<div class="step-pane" data-step="2">
																<div class="center">
																	<h3 class="green">Semua Data Sudah Terisi!</h3>
																	Your product is ready to ship! Click finish to continue!
																</div>
															</div>

												</div>
											</div>

											<hr />
											<div class="wizard-actions">
												<button class="btn btn-prev">
													<i class="ace-icon fa fa-arrow-left"></i>
													Prev
												</button>

												<button class="btn btn-success btn-next" data-last="Finish">
													Next
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>
											</div>
										</div>


<section class="form-pemeriksaan">

<?php $attr_form = array('onsubmit' => 'return cek_form()'); ?>
<?=form_open('irtp/set_data_irtp_pemeriksaan', $attr_form)?>
    <div class="row form-bag" id="bag-1">
        <h2 class="heading-form">Pemeriksaan Sarana Produksi P-IRT (Bagian 1)</h2>	
		<?= @$this->session->flashdata('error'); ?>	
		<?= @$this->session->flashdata('message'); ?>	
		<?= @$this->session->flashdata('status'); ?>
        <div class="well">
            <div class="row">
                <div class="col-sm-7">	
                    <div class="form-group-cat">
						<div class="form-group">
                            <label>Tujuan Pemeriksaan</label>
                            <div>
								<input type="text" readonly class="form-control" value="Pemberian SPP-IRT Baru">
								<input type="hidden" name="tujuan_pemeriksaan" value="1">
                                <!-- <label class="checkbox-inline"> 
                                    <input type="radio" class="tujuan_pemeriksaan" id="pemberian_spp_irt" name="tujuan_pemeriksaan" value="1" checked> Pemberian SPP-IRT Baru 
                                </label>
                                <label class="checkbox-inline"> 
                                    <input type="radio" class="tujuan_pemeriksaan" id="pemeriksaan_rutin_irtp" name="tujuan_pemeriksaan" value="2"> Pemeriksaan Rutin IRTP
                                </label>
                                <label class="checkbox-inline"> 
                                    <input type="radio" class="tujuan_pemeriksaan" id="lainnya" name="tujuan_pemeriksaan" value="3"> Lainnya
                                </label> -->
                            </div>
                            <!-- <p class="help-block">Pilih salah satu Tujuan Pemeriksaan.</p> -->
                        </div><!-- form-group -->
					
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nomor Permohonan</label>
                                <div class="dropdown">
                                    <select class="chosen-select col-sm-12" id="nomor_permohonan" data-validation="required" name="nomor_permohonan">
                                        <option value="">- Pilih Nomor Permohonan IRTP -</option>
                                    <?php foreach($no_sert as $data): ?>
                                        <option value="<?=$data->nomor_permohonan?>"><?=$data->nomor_permohonan.' - '.$data->nama_perusahaan.' - '. $data->nama_pemilik.' - '.$data->nama_dagang?></option>
                                    <?php endforeach ?>				
                                    </select>
                                 </div>
                                <p class="help-block">Keterangan Pilihan : Nomor Permohonan - Nama Perusahaan - Nama Pemilik - Nama Dagang, Contoh : 123/4567/89 - IRTP Maju Jaya - Rivi Ahmad - Kentang Rivi</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nama IRTP Yang Diperiksa</label>
                                <input type="text" class="form-control" readonly id="nama_fasilitas_periksa" name="nama_fasilitas_periksa" data-validation="required" placeholder="Masukan Nama IRTP Yang Diperiksa" />					
                                <p class="help-block">Masukan Nama IRTP yang diperiksa, Contoh : CV. Sukses Makmur</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Alamat IRTP</label>
                                <textarea name="alamat_fasilitas_periksa" readonly id="alamat_pemilik" class="form-control" data-validation="required" placeholder="Masukan Alamat IRTP Yang Diperiksa"></textarea>
                                <p class="help-block">Masukan Alamat IRTP yang diperiksa, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
                            </div>
                        </div><!-- form-group row -->                       
                        
            
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Provinsi</label>
                                <input type="text" class="form-control" data-validation="required" readonly id="provinsi">
                                <!-- <div class="dropdown">
                                    <select class="chosen-select col-sm-12" name="provinsi" id="provinsi" data-validation="required">
                                    <option>- Pilih Provinsi -</option>
                                    <?php foreach($js_propinsi as $data): ?>
                                        <option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
                                    <?php endforeach ?>				
                                    </select>
                                </div> -->
                                <p class="help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
                            </div>
                        </div><!-- form-group row -->

                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Kabupaten/Kota</label>
                                <input type="text" class="form-control" data-validation="required" readonly id="kabupaten_kota">
                                <!-- <div class="dropdown">
                                    <select class="chosen-select col-sm-12" name="kabupaten" data-validation="required" id="kabupaten">
                                        <option value="">- Pilih Provinsi terlebih dulu -</option>                                    		
                                    </select>
                                </div> -->
                                <p class="help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan.</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nama Pemilik IRTP</label>
                                <input type="text" class="form-control" id="nama_pemilik" data-validation="required"  name="nama_pemilik" placeholder="Pilih Nomor Sertifikat Penyuluhan Pangan (PKP) terlebih dahulu" disabled>
								<p class="help-block">Nama Pemilik IRTP akan terisi otomatis setelah memilih Nomor Sertifikat PKP, Contoh : Rivi Ahmad</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nama Penanggung Jawab IRTP</label>
                                <input type="text" class="form-control" data-validation="required" id="penanggung_jawab" name="penanggung_jawab" placeholder="Pilih Nomor Sertifikat Penyuluhan Pangan (PKP) terlebih dahulu" disabled>
								<p class="help-block">Nama Penanggung Jawab IRTP akan terisi otomatis setelah memilih Nomor Sertifikat PKP, Contoh : Ahmad Rivi</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nama Jenis Pangan</label>
                                <input type="text" class="form-control" data-validation="required" id="nama_jenis_pangan" name="nama_jenis_pangan" placeholder="Pilih Nomor Sertifikat Penyuluhan Pangan (PKP) terlebih dahulu" disabled>
								<p class="help-block">Nama Jenis Pangan akan terisi otomatis setelah memilih Nomor Sertifikat PKP, Contoh : Bawang Goreng</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Tanggal Pemeriksaan IRTP</label>
                                <input type="text" class="form-control datetimepicker" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" placeholder="Pilih Tanggal Pemeriksaan IRTP"/>
                                <p class="help-block">Pilih Tanggal Pemeriksaan IRTP, Contoh : 2014-08-06</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Ketua Tim Pengawas Pangan</label>
                                <div class="dropdown">
                                    <select class="chosen-select col-sm-12" name="nip_pengawas" id="nip_pengawas">
										<option value="">- Pilih Nama Ketua Tim Pengawas Pangan -</option>
                                    <?php foreach($js_pengawas as $data): ?>
                                        <option value="<?=$data->kode_narasumber?>"><?=$data->nama_narasumber?></option>
                                    <?php endforeach ?>				
                                    </select>
                                </div>
                                <p class="help-block">Pilih Nama Ketua Tim Pengawas Pangan sesuai dengan list rujukan.</p>
                            </div>
                        </div><!-- form-group row -->
						
                        <div class="form-group row anggota_form">
                            <div class="col-xs-12">
                                <label>Anggota Pengawas Pangan</label>
                                <div class="dropdown">
                                    <select class="chosen-select col-sm-9" name="nip_anggota_pengawas[]">
										<option value="">- Pilih Nama Anggota Pengawas Pangan -</option>
                                    <?php foreach($js_pengawas as $data): ?>
                                        <option value="<?=$data->kode_narasumber?>"><?=$data->nama_narasumber?></option>
                                    <?php endforeach ?>				
                                    </select>
                                    <div class="col-sm-2 pull-right">
		                                <div class="btn-add btn button btn-primary" style="width:100%">Add</div>
		                            </div>
                                </div>
                                <p class="help-block">Pilih Nama Anggota Pengawas Pangan sesuai dengan list rujukan.</p>
                            </div>
                        </div>

                        <div class="form-group row observer_form">
                            <div class="col-xs-12">
                                <label>Observer Pengawas Pangan</label>
                                <input type="text" name="nip_observer_pengawas[]" class="form-control pull-left" style="width: 75%">
                                <div class="col-sm-2 pull-right">
	                                <div class="btn-add-obs btn button btn-primary" style="width:100%">Add</div>
	                            </div><br><br><br>
                                <p class="help-block">Isi Nama Observer Pengawas Pangan.</p>
                            </div>
                        </div><!-- form-group row -->
						
						<!-- <div class="form-group row" id="box_nama_pengawas" style="display:none">
							<div class="col-xs-12">
								<label>Nama Pengawas Pangan (Lainnya)</label>
								<input type="text" name="nama_pengawas_lain" class="form-control" id="nama_pengawas_lain" placeholder="Isi Nama Pengawas" />
								<p class="help-block">Isi Nama Pengawas lain, Contoh : Rivi Ahmad</p>
							</div>
						</div> -->
						
                        <!-- <div class="form-group">
                            <label>Jabatan Pengawas Pangan</label>
                            <div>
                                <label class="checkbox-inline"> 
                                    <input type="radio" class="jabatan" id="pemberian_spp_irt" name="jabatan_pengawas" value="Kepala" checked> Kepala/ Ketua 
                                </label>
                                <label class="checkbox-inline"> 
                                    <input type="radio" class="jabatan" id="pemeriksaan_rutin_irtp" name="jabatan_pengawas" value="Anggota"> Anggota
                                </label>
                                <label class="checkbox-inline"> 
                                    <input type="radio" class="jabatan" id="lainnya" name="jabatan_pengawas" value="Observer"> Observer
                                </label>
                            </div>
                            <p class="help-block">Pilih salah satu Jabatan Pengawas Pangan.</p>
                        </div> -->
                        
                        <b>Pemeriksaan Elemen</b>
                        <table class="table table-bordered">
                            <tr>
                                <th style="text-align:center">NO.</th>
                                <th style="text-align:center">ELEMEN YANG DIPERIKSA</th>
                                <th colspan="4" style="text-align:center">KETIDAKSESUAIAN</th>
                            </tr>
                            <tr>
                                <th style="text-align:center">A</th>
                                <th>LOKASI DAN LINGKUNGAN PRODUKSI</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                                <td style="text-align:center">1.</td>
                                <td align="justify">Lokasi dan lingkungan IRTP tidak terawat, kotor dan berdebu</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options1" value="1"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">B</th>
                                <th>BANGUNAN DAN FASILITAS</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                                <td style="text-align:center">2.</td>
                                <td align="justify">Ruang produksi sempit, sukar dibersihkan, dan digunakan untuk memproduksi produk selain pangan</td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input  class="opt-ma" type="checkbox" name="no_ketidaksesuaian[]" id="options2" value="2"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align:center">3.</td>
                                <td align="justify">Lantai, dinding, dan langit-langit, tidak terawat, kotor, berdebu dan atau berlendir</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options3" value="3"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align:center">4.</td>
                                <td align="justify">Ventilasi, pintu, dan jendela tidak terawat, kotor, dan berdebu</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-se" id="options4" value="4"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">C</th>
                                <th>PERALATAN PRODUKSI</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                                <td style="text-align:center">5.</td>
                                <td align="justify">Permukaan yang kontak langsung dengan pangan berkarat dan kotor</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-kr" id="options5" value="5"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">6.</td>
                                <td align="justify">Peralatan tidak dipelihara, dalam keadaan kotor, dan tidak menjamin efektifnya sanitasi.</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-se" id="options6" value="6"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">7.</td>
                                <td align="justify">Alat ukur / timbangan untuk mengukur / menimbang berat bersih / isi bersih tidak tersedia atau tidak teliti.</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-se" id="options7" value="7"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">D</th>
                                <th>SUPLAI AIR ATAU SARANA PENYEDIAAN AIR</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">8.</td>
                                <td align="justify">Air bersih tidak tersedia dalam jumlah yang cukup untuk memenuhi seluruh kebutuhan produksi</td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox"  class="opt-ma" name="no_ketidaksesuaian[]" id="options8" value="8"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">9.</td>
                                <td align="justify">Air berasal dari suplai yang tidak bersih</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options9" value="9"></td>
                            </tr>
                        
                            <tr>
                                <th style="text-align:center">E</th>
                                <th>FASILITAS DAN KEGIATAN HIGIENE DAN SANITASI</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">10.</td>
                                <td align="justify">Sarana untuk pembersihan / pencucian bahan pangan, peralatan, perlengkapan dan bangunan tidak tersedia dan tidak terawat dengan baik</td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options10" value="10"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">11.</td>
                                <td align="justify">Tidak tersedia sarana cuci tangan lengkap dengan sabun dan alat pengering tangan</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options11" value="11"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">12.</td>
                                <td align="justify">Sarana toilet / jamban kotor tidak terawat dan terbuka ke ruang produksi</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options12" value="12"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">13.</td>
                                <td align="justify">Tidak tersedia tempat pembuangan sampah tertutup</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options13" value="13"></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">F</th>
                                <th>KESEHATAN DAN HIGIENE KARYAWAN</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">14.</td>
                                <td align="justify">Karyawan di bagian produksi pangan ada yang tidak merawat kebersihan badannya dan atau ada yang sakit</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options14" value="14"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">15.</td>
                                <td align="justify">Karyawan di bagian produksi pangan tidak mengenakan pakaian kerja dan / atau mengenakan perhiasan</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options15" value="15"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">16.</td>
                                <td align="justify">Karyawan tidak mencuci tangan dengan bersih sewaktu memulai mengolah pangan, sesudah menangani bahan mentah, atau bahan / alat yang kotor, dan sesudah ke luar dari toilet / jamban</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options16" value="16"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">17.</td>
                                <td align="justify">Karyawan bekerja dengan perilaku yang tidak baik (seperti makan dan minum) yang dapat mengakibatkan pencemaran produk pangan</td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options17" value="17"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">18.</td>
                                <td align="justify">Tidak ada Penanggungjawab higiene karyawan</td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options18" value="18"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">G</th>
                                <th>PEMELIHARAAN DAN PROGRAM HIGIENE</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            
                            <tr>
                            <td style="text-align:center">19.</td>
                                <td align="justify">Bahan kimia pencuci tidak ditangani dan digunakan sesuai prosedur, disimpan di dalam wadah tanpa label</td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options19" value="19"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">20.</td>
                                <td align="justify">Program higiene dan sanitasi tidak dilakukan secara berkala</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options20" value="20"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">21.</td>
                                <td align="justify">Hewan peliharaan terlihat berkeliaran di sekitar dan di dalam ruang produksi pangan.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options21" value="21"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">22.</td>
                                <td align="justify">Sampah di lingkungan dan di ruang produksi tidak segera dibuang.</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options22" value="22"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">H</th>
                                <th>PENYIMPANAN</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">23.</td>
                                <td align="justify">Bahan pangan, bahan pengemas disimpan
								bersama-sama dengan produk akhir dalam
								satu ruangan penyimpanan yang kotor, lembab
								dan gelap dan diletakkan di lantai atau
								menempel ke dinding.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options23" value="23"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">24.</td>
                                <td align="justify">Peralatan yang bersih disimpan di tempat yang kotor</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options24" value="24"></td>
                            </tr>
                            
                            <tr>
                                <th style="text-align:center">I</th>
                                <th>PENGENDALIAN PROSES</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">25.</td>
                                <td align="justify">IRTP tidak memiliki catatan; menggunakan bahan baku yang sudah rusak, bahan berbahaya, dan bahan tambahan pangan yang tidak sesuai dengan persyaratan penggunaannya</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options25" value="25"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">26.</td>
                                <td align="justify">IRTP tidak mempunyai atau tidak mengikuti bagan alir produksi pangan</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options26" value="26"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">27.</td>
                                <td align="justify">IRTP tidak menggunakan bahan kemasan khusus untuk pangan</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options27" value="27"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">28.</td>
                                <td align="justify">BTP tidak diberi penandaan dengan benar</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options28" value="28"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">29.</td>
                                <td align="justify">Alat ukur / timbangan untuk mengukur / menimbang BTP tidak tersedia atau tidak teliti.</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options29" value="29"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">J</th>
                                <th>PELABELAN PROSES</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">30.</td>
                                <td align="justify">Label pangan tidak mencantumkan nama produk, daftar bahan yang digunakan, berat bersih/isi bersih, nama dan alamat IRTP, masa kedaluwarsa, kode produksi dan nomor P-IRT</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options30" value="30"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">31.</td>
                                <td align="justify">Label mencantumkan klaim kesehatan atau klaim gizi</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options31" value="31"></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">K</th>
                                <th>PENGAWASAN OLEH PENANGGUNG JAWAB</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">32.</td>
                                <td align="justify">IRTP tidak mempunyai penanggung jawab yang memiliki Sertifikat Penyuluhan Keamanan Pangan (PKP)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options32" value="32"></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">33.</td>
                                <td align="justify">IRTP tidak melakukan pengawasan internal secara rutin, termasuk monitoring dan tindakan koreksi</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options33" value="33"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">L</th>
                                <th>PENARIKAN PRODUK</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">34.</td>
                                <td align="justify">Pemilik IRTP tidak melakukan penarikan produk pangan yang tidak aman</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options34" value="34"></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">M</th>
                                <th>PENCATATAN DAN DOKUMENTASI</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">35.</td>
                                <td align="justify">IRTP tidak memiliki dokumen produksi</td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options35" value="35"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td style="text-align:center">36.</td>
                                <td align="justify">Dokumen produksi tidak mutakhir, tidak akurat, tidak tertelusur dan tidak disimpan selama 2 (dua) kali umur simpan produk pangan yang diproduksi</td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-mi" name="no_ketidaksesuaian[]" id="options36" value="36"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align:center">N</th>
                                <th>PELATIHAN KARYAWAN</td>
                                <th style="text-align:center">MI</th>
                                <th style="text-align:center">MA</th>
                                <th style="text-align:center">SE</th>
                                <th style="text-align:center">KR</th>
                            </tr>
                            <tr>
                            <td style="text-align:center">37.</td>
                                <td align="justify">IRTP tidak memiliki program pelatihan keamanan pangan untuk karyawan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options37" value="37"></td>
                            </tr>
                            <tr>
                            <td></td>
                                <td style="text-align:right">Jumlah Ketidaksesuaian KRITIS</td>
                                <td disabled></td>
                                <td disabled></td>
                                <td disabled></td>
                                <td class="DOM-opt-kr">0</td>
                            </tr>
                            <tr>
                            <td></td>
                                <td style="text-align:right">Jumlah Ketidaksesuaian SERIUS</td>
                                <td disabled></td>
                                <td disabled></td>
                                <td class="DOM-opt-se">0</td>
                                <td disabled></td>
                            </tr>
                            <tr>
                            <td></td>
                                <td style="text-align:right">Jumlah Ketidaksesuaian MAYOR</td>
                                <td disabled></td>
                                <td class="DOM-opt-ma">0</td>
                                <td disabled></td>
                                <td disabled></td>
                            </tr>
                            <tr>
                            <td></td>
                                <td style="text-align:right">Jumlah Ketidaksesuaian MINOR</td>
                                <td class="DOM-opt-mi">0</td>
                                <td disabled></td>
                                <td disabled></td>
                                <td disabled></td>
                            </tr>
                        </table>

                        <table class="table table-bordered center">
                    		<tr>
                    			<th rowspan="2">Level IRTP</th>
                    			<th rowspan="2">Frekuensi Audit Internal</th>
                    			<th colspan="4">Jumlah Penyimpangan</th>
                    		</tr>
                    		<tr>
                    			<th>Minor</th>
                    			<th>Mayor</th>
                    			<th>Serius</th>
                    			<th>Kritis</th>
                    		</tr>
                    		<tr>
                    			<td id="level">Level I</td>
                    			<td id="frequency">Setiap dua bulan</td>
                    			<td class="DOM-opt-mi mi-res">0</td>
                    			<td class="DOM-opt-ma ma-res">0</td>
                    			<td class="DOM-opt-se se-res">0</td>                    			
                    			<td class="DOM-opt-kr kr-res">0</td>
                    		</tr>
                        </table>

                        <input type="hidden" id="DOM-level" name="level" value="Level I">
                        <input type="hidden" id="DOM-freq" name="freq" value="Setiap dua bulan">
                        
                        <button type="button" class="btn btn-success col-sm-12 col-xs-12 col-md-12" data-req='next-btn' onclick="makeRecords(getSelectedCheckboxes().length)">Selanjutnya &gt;</button> 
                    </div><!-- form-group-cat -->
                </div><!-- col-sm-7 -->
            
                <div class="col-sm-5">
                    <div class="panel-instruction">
                        <h2>Lokasi Anda Saat Ini :</h2>
                        <p align="justify">
                            IRTP >> Pemeriksaan Sarana Produksi P-IRT
                         </p>
                    </div><!-- panel-instruction-->
                    
                    <div class="panel-instruction">
						<h3>Petunjuk Teknis Pengisian</h3>
						<p align="justify">
							Pengguna diharapkan untuk melakukan pengisian data sesuai dengan keterangan yang ada di bawah setiap kotak masukan/isian.
						 </p>
						<p align="justify">
							Pengguna diharapkan untuk melakukan pengecekan data sebelum menekan tombol <b>"Kirim &raquo;"</b> untuk menghindari terjadinya kesalahan pengisian / <i>Human Error</i>.
						 </p>
						 <p align="justify">
							Jika terjadi kesalahan pengisian data setelah menekan tombol <b>"Kirim &raquo;"</b>, maka data yang sudah dimasukan ke dalam <i>database</i> tidak bisa diubah.
						 </p>
						<p align="justify">
							Jika semua data sudah terisi dengan benar, maka klik tombol <b>"Kirim &raquo;"</b> untuk melakukan proses <i>submitting</i>.
						</p>
					</div>
                </div><!-- col-sm-5 -->
            </div><!-- row -->
        </div><!-- well -->
    </div><!-- row -->
    
    <div class="row form-bag" id="bag-2">
        <h2 class="heading-form">Pemeriksaan Sarana Produksi P-IRT (Bagian 2)</h2>
        <div class="well">
            <div class="row">
                <div class="col-sm-12">		
                    <div class="form-group-cat">
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nomor Permohonan SPP-IRT</label>
                                <input type="text" class="form-control nomor_permohonan" readonly="readonly" name="no_permohonan" placeholder="Pilih Nomor Sertifikat PKP terlebih dahulu" onkeypress="return isNumberKey(event)">
                                <p class="help-block">Nomor Permohonan SPP-IRT akan terisi otomatis setelah memilih Nomor Sertifikat PKP pada Bagian 1, Contoh : ABS-2210.1213-2014</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <h4>Rincian Laporan Ketidaksesuaian</h4>
                        <div class="row" style="margin-bottom : 13px;">
                            <div class="col-sm-3">
                                Jumlah Ketidaksesuaian :
                            </div>
                            <div class="col-sm-3">
								<input type="text" readonly class="form-control" id="no_record_ketidaksesuaian" name="no_record_ketidaksesuaian" placeholder=""/>
                                <!--<select class="chosen-select col-sm-3 records-update" data-href="step-2">
                                <?php for($i = 1; $i <= 37; $i++): ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php endfor; ?>
                                </select>-->
                            </div>
                        </div>
                        <table class="table table-bordered step-2 table-appended">
                            <tr class="table-head">
                                <th style="vertical-align:middle" class="col-sm-3" align="center">NO.</th>
                                <th style="vertical-align:middle" class="col-sm-3" align="center">KETIDAKSESUAIAN (PLOR = Problem, Location, Objective evidence, Reference)<br>
								<span style="font-weight:normal">Contoh PLOR: Keamanan produk pangan belum terjamin, terbukti di ruang pengadonan ditemukan air keruh digunakan untuk proses produksi, sehingga tidak sesuai dengan CPPB-IRT</span>
								</th>
                                <th style="vertical-align:middle" class="col-sm-3" align="center">KRITERIA KETIDAKSESUAIAN (Mayor, Minor, Serius, Kritis)</th>
                                <th style="vertical-align:middle" class="col-sm-3" align="center">BATAS WAKTU PENYELESAIAN KETIDAKSESUAIAN</th>
                            </tr>
                            <tbody class="table-init">
                                <tr>
                                    <td style="vertical-align:middle">
										<input type="text" readonly class="form-control" id="no_ketidaksesuaian" name="ketidaksesuaian[]" placeholder=""/>
                                        <?php /*<select class="chosen-select col-sm-12" name="no_ketidaksesuaian[]">
                                            <option value="">- Nomor -</option>
                                            <?php foreach($nomor_ketidaksesuaian as $data): ?>
                                            <option value="<?=$data->kode_ketidaksesuaian?>"><?=$data->kode_ketidaksesuaian.". ".$data->nama_ketidaksesuaian?></option>
                                            <?php endforeach ?>
                                        </select>*/ ?>
                                    </td>
                                    <td>
										<input type="text" class="form-control" id="no_plor" name="no_plor[]" placeholder=""/>
                                        <?php /*<select class="chosen-select col-sm-12" name="no_plor[]">
                                            <option value="">- Pilih PLOR -</option>
                                            <?php foreach($plor as $data): ?>
                                            <option value="<?=$data->kode_plor?>"><?=$data->kode_plor.". ".$data->nama_plor?></option>
                                            <?php endforeach ?>
                                        </select> */ ?>
                                    </td>
                                    <td>
										<input type="text" readonly class="form-control" id="kode_ketidaksesuaian" name="kode_ketidaksesuaian[]" placeholder=""/>
                                        <?php /*<select class="chosen-select col-sm-12" name="kode_ketidaksesuaian[]">
                                            <option value="">- Pilih Kriteria Kesesuaian -</option>
                                            <?php foreach($sesuaian as $data): ?>
                                            <option value="<?=$data->kode_kriteria_ketidaksesuaian?>"><?=$data->kode_kriteria_ketidaksesuaian.". ".$data->nama_kriteria_ketidaksesuaian?></option>
                                            <?php endforeach ?>
                                        </select> */?>
                                    </td>
                                    <td style="vertical-align:middle" align="center"><input type="text" class="form-control datetimepicker" name="batas_waktu_pemeriksaan[]" placeholder=""/></td>
                                </tr>
                            </tbody>
                        </table>
                        
                       <!--<div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nama Pengawas Pangan</label>
                                <div class="dropdown">
                                    <select class="chosen-select col-sm-12" >
                                    <?php foreach($js_pengawas as $data): ?>
                                        <option value="<?=$data->nip_auditor?>"><?=$data->nip_auditor.'. '.$data->nama_auditor?></option>
                                    <?php endforeach ?>				
                                    </select>
                                </div>
                                <p class="help-block">Masukan nama pengawas pangan sesuai dengan list rujukan.</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <button type="button" class="btn btn-warning col-sm-12" data-req='prev-btn'>&lt; Sebelumnya</button> 
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <button type="button" class="btn btn-success col-sm-12" data-req='next-btn' onclick="makeStep3()">Selanjutnya &gt;</button> 
                            </div>
                        </div>
                    </div><!-- form-group-cat -->
                </div><!-- col-sm-12 -->
            </div><!-- row -->
        </div><!-- well -->
    </div><!-- row -->
    
    <div class="row form-bag" id="bag-3">
        <h2 class="heading-form">Pemeriksaan Sarana Produksi P-IRT (Bagian 3)</h2>
        <div class="well">
            <div class="row">
                <div class="col-sm-12">		
                    <div class="form-group-cat">
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nomor Permohonan SPP-IRT</label>
                                <input type="text" class="form-control nomor_permohonan" readonly="readonly" name="no_permohonan" placeholder="Pilih Nomor Sertifikat SPP-IRT terlebih dahulu" onkeypress="return isNumberKey(event)">
                                <p class="help-block">Nomor Permohonan SPP-IRT akan terisi otomatis setelah memilih Nomor Sertifikat PKP pada Bagian 1, Contoh : ABS-2210.1213-2014</p>
                            </div>
                        </div><!-- form-group row -->
                        
                        <b>Rincian Laporan Ketidaksesuaian</b>
                        <div class="row" style="margin-bottom : 13px;">
                            <div class="col-sm-3">
                                Jumlah Ketidaksesuaian :
                            </div>
                            <div class="col-sm-9">
                               <input type="text" id="step3-count-ketidaksesuaian" class="form-control" readonly>
                               <!--  <select class="chosen-select col-sm-3 records-update" name="" data-href="step-3">
                                <?php for($i = 1; $i <= 37; $i++): ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php endfor; ?>
                                </select> -->
                            </div>
                        </div>
                        <table class="table table-bordered table-appended step-3">
                            <tr class="table-head">
                                <th style="vertical-align:middle" align="center" class="col-sm-3">NO.</th>
                                 <th style="vertical-align:middle" class="col-sm-3" align="center">KETIDAKSESUAIAN (PLOR = Problem, Location, Objective evidence, Reference)<br>
								<span style="font-weight:normal">Contoh PLOR: Keamanan produk pangan belum terjamin, terbukti di ruang pengadonan ditemukan air keruh digunakan untuk proses produksi, sehingga tidak sesuai dengan CPPB-IRT</span>
								</th>
                                <th style="vertical-align:middle" align="center" class="col-sm-2">KRITERIA KETIDAKSESUAIAN (Mayor, Minor, Serius, Kritis)</th>
                                <th style="vertical-align:middle" align="center" class="col-sm-3">TINDAKAN PERBAIKAN</th>
                                <th style="vertical-align:middle" align="center" class="col-sm-2">STATUS (Sesuai/Tidak Sesuai) Diverifikasi oleh Pengawas Pangan Kabupaten/Kota</th>
                            </tr>
                            <tbody id="tbody-step3"></tbody>
                        </table>
                        
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Nama Petugas Pemeriksa :</label>
                                <div class="dropdown">
                                    <select class="chosen-select col-sm-12" name='nip_verifikator' id='nip_verifikator'>
										<option value="">- Pilih Pengawas Pangan -</option>
                                    <?php foreach($js_pengawas as $data): ?>
                                        <option value="<?=$data->kode_narasumber?>"><?=$data->nip_pkp_dfi.' - '.$data->nama_narasumber?></option>
                                    <?php endforeach ?>				
                                    </select>
                                </div>
                                <p class="help-block">Keterangan Pilihan : NIP - Nama Pengawas Pangan, Contoh : 140 055 626 - H. M. Yusuf Ali</p>
                            </div>
                        </div><!-- form-group row -->
                        
						<div class="form-group row" id="box_nama_verifikator" style="display:none">
							<div class="col-xs-12">
								<label>Nama Verifikator(Lainnya)</label>
								<input type="text" name="nama_verifikator_lain" class="form-control" id="nama_verifikator_lain" placeholder="Isi Nama Verifikator" />
								<p class="help-block">Isi Nama Verifikator lain, Contoh : Rivi Ahmad</p>
							</div>
						</div>
						
						<div class="row">
                            <div class=" col-lg-6 col-sm-12 col-xs-12 col-md-12">
                           		<button type="button" class="btn btn-warning col-sm-12" data-req='prev-btn'> &lt; Sebelumnya </button> 
                            </div>
							<div class=" col-lg-6 col-sm-12 col-xs-12 col-md-12">
                            	<button type="submit" class="btn btn-primary col-sm-12">Kirim &raquo;</button>
                            </div>
                        </div> <!-- row -->
                    </div><!-- form-group-cat -->
                </div><!-- col-sm-7 -->
            </div><!-- row -->
        </div><!-- well -->
    </div><!-- row -->
<?=form_close()?>
</section>

<template class="hidden" id="row-step3">
	<tr>
	    <td style="vertical-align:top">
	    	<!--<input type="text" readonly name="nama_ketidaksesuaian[]" class="form-control" value="{{ label }}">-->
			<textarea readonly name="nama_ketidaksesuaian[]" class="form-control">{{ label }}</textarea>
	         
			 <?php /*
	         <select class="chosen-select col-sm-12" name="nomor_ketidaksesuaian[]">
	            <?php foreach($nomor_ketidaksesuaian as $data): ?>
	                <option value="<?=$data->kode_ketidaksesuaian?>"><?=$data->kode_ketidaksesuaian.'. '.$data->nama_ketidaksesuaian?></option>
	            <?php endforeach ?>
	        </select>
	        */
	        ?>
			
	    </td>
	    <td>
	        
			<!--<select class="form-control" name="kode_plor[]">
	            <?php foreach($plor as $data): ?>
	                <option value="<?=$data->kode_plor?>"><?=$data->nama_plor?></option>
	            <?php endforeach ?>
	        </select>-->
			
			<textarea class="form-control" readonly id="{{ id_plor }}" name="plor[]" placeholder=""></textarea>
	    </td>
	    <td>
	    	<input type="text" readonly name="level_ketidaksesuaian[]" class="form-control" value="{{ type }}">
	        
			<?php /*
	        <select class="form-control" name="kriteria_ketidaksesuaian[]">
	            <?php foreach($sesuaian as $data): ?>
	                <option value="<?=$data->kode_kriteria_ketidaksesuaian?>"><?=$data->nama_kriteria_ketidaksesuaian?></option>
	            <?php endforeach ?>
	        </select>
	        */
	        ?>
	    
		</td>
	    <td>
	        <!--<input type="text" name="tindakan_perbaikan[]" class="form-control" data-validation="required" placeholder=""></textarea>-->
			<textarea name="tindakan_perbaikan[]" class="form-control" data-validation="required" placeholder=""></textarea>
	    </td>
	    <td style="vertical-align:top">
	        <select class="form-control" name="status_verifikasi[]">
	            <option value="1">Sesuai</option>
	            <option value="0">Tidak Sesuai</option>
	        </select>
	    </td>
	</tr>
</template>