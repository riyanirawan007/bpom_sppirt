<style type="text/css">
table.center tr th{
	text-align: center;
	vertical-align: middle
}
</style>
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
		<input type="text" class="form-control date-picker" id="date" name="tanggal_batas_waktu[]" placeholder="" value=""/>\
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
			url	: '<?=base_url()?>pemeriksaan_sarana/get_kabupaten_with_provinsi',
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
			url	: '<?=base_url()?>pemeriksaan_sarana/get_irtp_raw',
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
			url	:	'<?=base_url()?>pemeriksaan_sarana/get_kabupaten',
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
		var DOM = $(this).parents('.clearfix');
		var curr = $(this);
		DOM.after(DOM.clone());
		last_cl = $('.clearfix').last();
		last_cl.find('input').val('');
		curr.html('Delete').removeClass('btn-primary btn-add-obs').addClass('btn-danger btn-delete-obs');
	});

	$(document).on('click', '.btn-delete-obs', function(e){
		var curr = $(this);
		curr.parents('.clearfix').remove();
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
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Input Data Pemeriksaan Sarana</li>
	</ul>

</div>

<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Input Data Pemeriksaan Sarana
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
							<h3 class="smaller lighter blue">Edit Data Pemeriksaan Sarana</h3>
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
							<h4 class="widget-title lighter">Pemeriksaan PKP</h4>
						</div>

						<div class="widget-body">
							<div class="widget-main">
								<div id="fuelux-wizard-container">
									<!-- <div>
										<ul class="steps">
											<li data-step="1" class="active">
												<span class="step">1</span>
												<span class="title">Pemeriksaan Sarana Produksi P-IRT (Bagian 1)</span>
											</li>

											<li data-step="2">
												<span class="step">2</span>
												<span class="title">Pemeriksaan Sarana Produksi P-IRT (Bagian 2)</span>
											</li>

											<li data-step="3">
												<span class="step">3</span>
												<span class="title">Pemeriksaan Sarana Produksi P-IRT (Bagian 3)</span>
											</li>

											<li data-step="4">
												<span class="step">4</span>
												<span class="title">Pemeriksaan Sarana Produksi P-IRT (Bagian 4)</span>
											</li>

											<li data-step="5">
												<span class="step">5</span>
												<span class="title">Finish</span>
											</li>

										</ul>
									</div> -->

									<hr />

									<div class="step-content pos-rel">
										<div class="step-pane active" data-step="1">
											<?php $attr_form = array('onsubmit' => 'return cek_form()'); ?>	
											<?=form_open_multipart('pemeriksaan_sarana/input_form_tidak_baku', array('class' => 'form-horizontal', 'id' => 'sample-form'), $attr_form)?>
											<?= @$this->session->flashdata('status'); ?>
											<?= @$this->session->flashdata('error'); ?>	
											<?= @$this->session->flashdata('message'); ?>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tujuan Pemeriksaan</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<input id="tujuan" type="text" class="col-xs-12 col-sm-9" value="Pemberian SPP-IRT Baru" readonly="" />
														<input type="hidden" name="tujuan_pemeriksaan" value="1">
														<p class="col-xs-12 col-sm-9 help-block"></p>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Permohonan</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<select id="nomor_permohonan" data-validation="required" name="nomor_permohonan" class="select2">
															<option value="">- Pilih Nomor Permohonan IRTP -</option>
															<?php foreach($no_sert as $data): ?>
																<option value="<?=$data->nomor_permohonan?>"><?=$data->nomor_permohonan.' - '.$data->nama_perusahaan.' - '. $data->nama_pemilik.' - '.$data->nama_dagang?></option>
															<?php endforeach ?>				
														</select>
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
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alamat IRTP</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<textarea name="alamat_fasilitas_periksa" readonly id="alamat_pemilik" class="input-xlarge" data-validation="required" placeholder="Masukan Alamat IRTP Yang Diperiksa"></textarea>
														<p class="col-xs-12 col-sm-9 help-block">Masukan Alamat IRTP yang diperiksa, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Provinsi</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<input type="text" class="col-xs-12 col-sm-9" data-validation="required" readonly id="provinsi" />
														<p class="col-xs-12 col-sm-9 help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Kabupaten/Kota</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<input type="text" class="col-xs-12 col-sm-9" data-validation="required" readonly id="kabupaten_kota" />
														<p class="col-xs-12 col-sm-9 help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan.</p>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Pemilik IRTP</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<input type="text" class="col-xs-12 col-sm-9" id="nama_pemilik" data-validation="required"  name="nama_pemilik" placeholder="Pilih Nomor Sertifikat Penyuluhan Pangan (PKP) terlebih dahulu" disabled />
														<p class="col-xs-12 col-sm-9 help-block">Nama Pemilik IRTP akan terisi otomatis setelah memilih Nomor Sertifikat PKP, Contoh : Rivi Ahmad</p>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Penanggung Jawab IRTP</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<input type="text" class="col-xs-12 col-sm-9" data-validation="required" id="penanggung_jawab" name="penanggung_jawab" placeholder="Pilih Nomor Sertifikat Penyuluhan Pangan (PKP) terlebih dahulu" disabled />
														<p class="col-xs-12 col-sm-9 help-block">Nama Penanggung Jawab IRTP akan terisi otomatis setelah memilih Nomor Sertifikat PKP, Contoh : Ahmad Rivi</p>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Jenis Pangan</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<input type="text" class="col-xs-12 col-sm-9" data-validation="required" id="nama_jenis_pangan" name="nama_jenis_pangan" placeholder="Pilih Nomor Sertifikat Penyuluhan Pangan (PKP) terlebih dahulu" disabled />
														<p class="col-xs-12 col-sm-9 help-block">Nama Jenis Pangan akan terisi otomatis setelah memilih Nomor Sertifikat PKP, Contoh : Bawang Goreng</p>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Pemeriksaan IRTP</label>

												<div class="col-xs-12 col-sm-9">
													<div class="input-group col-xs-12 col-sm-9">
														<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" placeholder="Pilih Tanggal Pemeriksaan IRTP" />
														<span class="input-group-addon">
															<i class="fa fa-calendar bigger-110"></i>
														</span>
													</div>
													<p class="col-xs-12 col-sm-9 help-block">Pilih Tanggal Pemeriksaan IRTP, Contoh : 2014-08-06</p>

												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Ketua Tim Pengawas Pangan</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
														<div class="dropdown">
															<select class="select2" name="nip_pengawas" id="nip_pengawas">
																<option value="">- Pilih Nama Ketua Tim Pengawas Pangan -</option>
																<?php foreach($js_pengawas as $data): ?>
																	<option value="<?=$data->kode_narasumber?>"><?=$data->nama_narasumber?></option>
																<?php endforeach ?>				
															</select>
														</div>
														<p class="col-xs-12 col-sm-9 help-block">Pilih Nama Ketua Tim Pengawas Pangan sesuai dengan list rujukan.</p>
													</div>
												</div>
											</div>

											<div class="form-group row anggota_form">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Anggota Pengawas Pangan</label>

												<!-- <div class="col-xs-12"> -->
													<!-- <label>Anggota Pengawas Pangan</label> -->
													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<div class="dropdown">
																<select class="chosen-select col-sm-5" name="nip_anggota_pengawas[]">
																	<option value="">- Pilih Nama Anggota Pengawas Pangan -</option>
																	<?php foreach($js_pengawas as $data): ?>
																		<option value="<?=$data->kode_narasumber?>"><?=$data->nama_narasumber?></option>
																	<?php endforeach ?>				
																</select>
																<div class="col-sm-2 pull-right">
																	<div class="btn-add btn button btn-primary" id="add_anggota"  style="width:100%">Add</div>
																</div>
															</div>
															<p class="help-block">Pilih Nama Anggota Pengawas Pangan sesuai dengan list rujukan.</p>
															<!-- </div> -->
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Observer Pengawas Pangan</label>

													<div class="col-xs-12 col-sm-9">
														<div class="clearfix">
															<input type="input" class="col-xs-12 col-sm-9" name="nip_observer_pengawas[]" class="form-control pull-left" >
															<div class="col-sm-2 pull-right">
																<div class="btn-add-obs btn button btn-primary" style="width:100%">Add</div>
															</div>
															<p class="col-xs-12 col-sm-9 help-block">Isi Nama Observer Pengawas Pangan</p>
														</div>
													</div>
												</div>

												<!-- <input type="hidden" name="penyuluhan" value="<?= $data_penyuluhan ?>">
												<input type="hidden" name="alasan_bp" value="<?= $alasan_belum_penyuluhan ?>">
												<input type="hidden" name="form" value="<?= $data_form ?>">
												<input type="hidden" name="alasan_tb" value="<?= $alasan_form_tidak_baku ?>"> -->


												<input type="hidden" id="DOM-level" name="level" value="Level I">
												<input type="hidden" id="DOM-freq" name="freq" value="Setiap dua bulan">


												<!--<button type="button" class="btn btn-success col-sm-12 col-xs-12 col-md-12" data-req='next-btn' onclick="makeRecords(getSelectedCheckboxes().length)">Selanjutnya &gt;</button> -->
												
											</div>

											
										</div>
									</div>

									<hr />
									<div class="wizard-actions">


										<button class="btn btn-primary" data-last="Selesai" type="submit" name="submit">
											Kirim
										</button>
									</div>
								</div>
								<?=form_close()?>
							</div>
						</div>
					</div>
				</div>

			</div>




<script>
	$(document).ready(()=>{
		var site_url='<?php echo site_url(); ?>';
		var nomor_permohonan='<?php echo $this->uri->segment('3');?>';
		var Edit=function(){
			return {
				getData:()=>{
					$.ajax({
						async:true,
						url:site_url+'get_edit/pemeriksaan_sarana',
						type:'GET',
						dataType:'json',
						data:{
							nomor_permohonan:nomor_permohonan
						}
						,success:(res)=>{
							console.log(res.data);
							Edit.fillDataForm1(res);
						}
						,error:(xhr,res,err)=>
						{
							alert(err);
						}
					});
				},
				fillDataForm1:(data)=>{
					var permohonan=data.permohonan[0];
					var pemeriksaan=data.pemeriksaan.data[0];
					$('#nomor_permohonan').select2('val',permohonan.nomor_permohonan).trigger('change');
					// $('#nomor_permohonan').val(data.nomor_permohonan).trigger('change');
					$('#id-date-picker-1').val(pemeriksaan.tanggal_pemeriksaan);
					$('#nip_pengawas').select2('val',data.pemeriksaan.ketua[0].kode_narasumber);
					
					for(var i=0;i<data.pemeriksaan.anggota.length-1;i++)
					{
						$('[class="btn-add btn button btn-primary"]').click();	
					}
					$('[name="nip_anggota_pengawas[]"]').each(function(i){
							$(this).chosen();
							$(this).val(data.pemeriksaan.anggota[i][0].kode_narasumber);
							$(this).trigger('chosen:updated');
							$(this).trigger("liszt:updated");
							// alert($(this).val());
					});	

					for(var i=0;i<data.pemeriksaan.observer.length-1;i++)
					{
						$('[class="btn-add-obs btn button btn-primary"]').click();	
					}
					$('[name="nip_observer_pengawas[]"]').each(function(i){
							$(this).val(data.pemeriksaan.observer[i]);
							// alert($(this).val());
					});

					$('[name="tujuan_pemeriksaan"]').val(pemeriksaan.tujuan_pemeriksaan);	
					switch(pemeriksaan.tujuan_pemeriksaan)
					{
						case '1':{
							$('#tujuan').val("Pemberian SPP-IRT Baru");
							break;
						}
						case '2':{
							$('#tujuan').val("Perpanjangan SPP-IRT");
							break;
						}
					}

					
				}
				
			};
		}();

		Edit.getData();
	});
</script>