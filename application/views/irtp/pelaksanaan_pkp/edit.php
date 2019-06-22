
<script>
	$(document).ready(()=>{
		Edit=function(){
			var id='<?php echo $this->uri->segment(3);?>';
			var site_url='<?php echo site_url()?>';
			return {
				id:id,
				site_url:site_url,
				getData:()=>{
					$.post(site_url+'get_edit/pelaksanaan_pkp',{
						id:id
					},(data,status,xhr)=>{
						console.log(data);
						Edit.formTab1(data);
						Edit.formTab2(data);
					},'json');
				},
				formTab1:(data)=>{
					var i=0;
					var j=0;
					$('input[name="id_urut[]"]').each(function()
					{
						if(data.daftar_narasumber[j]!=null && i+1==data.daftar_narasumber[j].kode_materi_penyuluhan)
						{
							$(this).val(data.daftar_narasumber[j].id_urut_ambil_penyuluhan);
							j++;
						}
						else{
							$(this).val('0');
						}

						i++;
					});

					$('#provinsi').select2('val',data.pkp.no_kode_propinsi).trigger('change');
					$('#kota').select2('val',data.pkp.id_r_urut_kabupaten);
					$('[name="tanggal_pelatihan_awal"]').val(data.pkp.tanggal_pelatihan_awal);
					$('[name="tanggal_pelatihan_akhir"]').val(data.pkp.tanggal_pelatihan_akhir);
					if(data.daftar_narasumber !=null && data.daftar_narasumber.length>0)
					{
						$('[id^="kd_materi_"]').each(function(index){
							var id=$(this).attr('id').replace('kd_materi_','');
							data.daftar_narasumber.forEach((val)=>{
								if(val.kode_materi_penyuluhan==id)
								{
									$('#kd_materi_'+val.kode_materi_penyuluhan).attr('checked','checked');
									//$('#select_narasumber_'+i).click();
									$('#select_narasumber_'+id).select2('val',val.kode_materi_penyuluhan+'.'+val.kode_narasumber);
								}
							});
						});
					}

					if(data.pkp.materi_tambahan!=null){
						var materi=data.pkp.materi_tambahan.split(',');
						$('#materi_tambahan').select2('val',materi);
					}
				},
				formTab2:(data)=>{
					
					var i=0;
					var form_peserta='';
					if(data.peserta!=null && data.peserta.length>0)
					{
						data.peserta.forEach((val)=>{
						form_peserta+='\
						<tr>\
							<td>\
								<select style="max-width: 380px" class="select2 sel_per" id="nomor_permohonan_irtp'+i+'" data-validation="required"\
									name="nomor_permohonan_irtp['+i+']">\
									<option value="">- Pilih Nomor Permohonan IRTP -</option>\
								</select>\
							</td>\
							<td>\
								<label class="checkbox-inline">\
									<input type="radio" class="ace status_pengajuan" id="pemilik'+i+'" name="status_peserta['+i+']"\
										value="0">\
									<span class="lbl"> Pemilik IRTP </span>\
								</label>\
								<label class="checkbox-inline">\
									<input type="radio" class="ace status_pengajuan" id="penanggung_jawab'+i+'" name="status_peserta['+i+']"\
										value="1">\
									<span class="lbl"> Penanggung Jawab IRTP </span>\
								</label>\
								<label class="checkbox-inline">\
									<input type="radio" class="ace status_pengajuan" id="lainnya'+i+'" name="status_peserta['+i+']"\
										value="2"><span class="lbl"> Lainnya </span>\
								</label>\
							</td>\
							<td>\
								<input type="text" name="nama_peserta['+i+']" id="nama_peserta'+i+'"\
									placeholder="Pilih Nomor Permohonan IRTP terlebih dahulu atau isi peserta lainnya" readonly />\
							</td>\
							<td>\
								<input type="text" name="no_sert_pangan['+i+']" id="no_sert_pangan'+i+'" />\
							</td>\
							<td>\
								<input type="text" name="nilai_pre_test['+i+']" data-validation="number"\
									data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)" maxlength="3"\
									placeholder="Masukan Nilai Pre Test" />\
							</td>\
							<td>\
								<input type="text" name="nilai_post_test['+i+']" data-validation="number"\
									data-validation-allowing="range[0;99999]" onkeypress="return isNumberKey(event)" maxlength="3"\
									placeholder="Masukan Nilai Post Test" />\
								<input name="id_urut_peserta[]" value="'+val.id_urut_ambil_materi+'" type="text"/>\
							</td>\
						</tr>';
						
						i++;
						});
						
						$('#form_peserta').html(form_peserta);

						$.get(site_url+'get_edit/pelaksanaan_irtp',{
						provinsi:$('#provinsi').val(),
						kabupaten:$('#kabupaten').val()
						},(list,status,xhr)=>{
							var opt='<option value="">- Pilih Nomor Permohonan IRTP -</option>';
							list.forEach((val)=>{
								opt+='<option value="'+val.nomor_permohonan+'">\
								'+val.nomor_permohonan+' - '+val.nama_perusahaan+' - '+val.nama_pemilik+' - '+val.nama_dagang+'\
								</option>';
							});
							$('.sel_per').html(opt);
							$('.sel_per').select2();

							$('[id^="nomor_permohonan_irtp"]').each(function(i){
								$(this).select2('val',data.peserta[i].nomor_permohonan).trigger('change');
								$('[name="status_peserta['+i+']"]').val([data.peserta[i].status_peserta]);
								$('#nama_peserta'+i).val(data.peserta[i].nama_peserta);
								$('#no_sert_pangan'+i).val(data.peserta[i].no_sert_pangan);
								$('[name="nilai_pre_test['+i+']"').val(data.peserta[i].nilai_pre_test);
								$('[name="nilai_post_test['+i+']"').val(data.peserta[i].nilai_post_test);

								$(this).change(function(){
									$('#nama_peserta'+i).val('');
									$('#pemilik'+i).click();
								});

								$(document).on('click', '#pemilik'+i, function(evt, key){
								var nama = $('#nama_peserta'+i);
								var data = $('#nomor_permohonan_irtp'+i).val();
								$.ajax({
									url	: '<?=base_url()?>pelaksanaan_pkp/get_irtp_raw',
									type	: 'POST',
									dataType: 'json',
									data	: 'nomor=' + data + '&mode=NULL',
									success: function(html){
										console.log(html);
										$.each(html, function(key, value){
											nama.val(value.nama_pemilik);
											
										});
									},error: function(e){
										console.log(e);
									}
								});
								nama.attr('readonly',true);
							});
							
							$(document).on('click', '#penanggung_jawab'+i, function(evt, key){
								var nama = $('#nama_peserta'+i);
								var data = $('#nomor_permohonan_irtp'+i).val();
								
								$.ajax({
									url	: '<?=base_url()?>pelaksanaan_pkp/get_irtp_raw',
									type	: 'POST',
									dataType: 'json',
									data	: 'nomor=' + data + '&mode=NULL',
									success: function(html){
										console.log(html);
										$.each(html, function(key, value){					
											nama.val(value.nama_penanggung_jawab);
											
										});
									},error: function(e){
										console.log(e);
									}
								});
								nama.attr('readonly',true);
							});
							
							$(document).on('click', '#lainnya'+i, function(evt, key){
								var nama = $('#nama_peserta'+i);
								nama.val('');
								nama.removeAttr('readonly');
								nama.focus();
								
							});
							
						});
							
						},'json');
					}
				}
			}
		}();
		Edit.getData();

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

	//nama perusahaan
    $(document).on('change', '#nomor_permohonan_penyuluhan', function(evt, key){
		var tgl_awal = $('#tanggal_pelatihan_awal');
		var tgl_akhir = $('#tanggal_pelatihan_akhir');
		var data = $(this).val();
		$.ajax({
			url	: '<?=base_url()?>pelaksanaan_pkp/get_penyuluhan_data',
			type	: 'POST',
			dataType: 'json',
			data	: 'nomor=' + data,
			success: function(html){
				$.each(html, function(key, value){					
					tgl_awal.val(value.tanggal_pelatihan_awal);
					tgl_akhir.val(value.tanggal_pelatihan_akhir);
				});
			},error: function(e){
				console.log(e);
			}
		});
	});

	<?php
	for ($i=1; $i <= $jumlah_peserta; $i++) { 	
	?>
    $(document).on('change', '#nomor_permohonan_irtp<?=$i?>', function(evt, key){
		var nama = $('#nama_peserta<?=$i?>');
		
		var data = $(this).val();
		
		$.ajax({
			url	: '<?=base_url()?>pelaksanaan_pkp/get_irtp_raw',
			type	: 'POST',
			dataType: 'json',
			data	: 'nomor=' + data + '&mode=NULL',
			success: function(html){
				console.log(html);
				$.each(html, function(key, value){		
					$("#pemilik<?=$i?>").prop('checked', true);
					nama.val(value.nama_pemilik);
				});
			},error: function(e){
				console.log(e);
			}
		});
	});
	
	$(document).on('click', '#pemilik<?=$i?>', function(evt, key){
		var nama = $('#nama_peserta<?=$i?>');
		var data = $('#nomor_permohonan_irtp<?=$i?>').val();
		$.ajax({
			url	: '<?=base_url()?>pelaksanaan_pkp/get_irtp_raw',
			type	: 'POST',
			dataType: 'json',
			data	: 'nomor=' + data + '&mode=NULL',
			success: function(html){
				console.log(html);
				$.each(html, function(key, value){
					nama.val(value.nama_pemilik);
					
				});
			},error: function(e){
				console.log(e);
			}
		});
		nama.attr('readonly',true);
	});
	
	$(document).on('click', '#penanggung_jawab<?=$i?>', function(evt, key){
		var nama = $('#nama_peserta<?=$i?>');
		
		var data = $('#nomor_permohonan_irtp<?=$i?>').val();
		
		$.ajax({
			url	: '<?=base_url()?>pelaksanaan_pkp/get_irtp_raw',
			type	: 'POST',
			dataType: 'json',
			data	: 'nomor=' + data + '&mode=NULL',
			success: function(html){
				console.log(html);
				$.each(html, function(key, value){					
					nama.val(value.nama_penanggung_jawab);
					
				});
			},error: function(e){
				console.log(e);
			}
		});
		nama.attr('readonly',true);
	});
	
	$(document).on('click', '#lainnya<?=$i?>', function(evt, key){
		var nama = $('#nama_peserta<?=$i?>');
		nama.val('');
		nama.removeAttr('readonly');
		nama.focus();
		
	});
	<?php
	}
	?>

	
	$(document).on('change', '.check-active', function(){
		if($(this).attr('checked') == "checked"){
			$(this).attr('checked', false);				
			console.log($(this).parents('.row.dropdown').find('select').prop('disabled', true).trigger('liszt:updated').select2);
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").hide();
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='jenis_narasumber_lain']").prop('disabled', true);
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='nama_narasumber_lain']").prop('disabled', true);
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='nip_narasumber_lain']").prop('disabled', true);
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='sertifikat_narasumber_lain']").prop('disabled', true);
		}else{			
			$(this).attr('checked', true);				
			$(this).parents('.row.dropdown').find('select').prop('disabled', false).trigger('liszt:updated').select2({'width' : '20%'});
			$(this).parents('.row.dropdown').find('select').find('option:first-child').prop('selected', true)
    		.end().trigger('liszt:updated').select2({'width' : '100%'});
    		// $(this).parents('.row.dropdown').find("div[id^='elemen_']").show();
    		$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='jenis_narasumber_lain']").prop('disabled', false);
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='nama_narasumber_lain']").prop('disabled', false);
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='nip_narasumber_lain']").prop('disabled', false);
			$(this).parents('.row.dropdown').find("div[id^='elemen_']").find("input[name^='sertifikat_narasumber_lain']").prop('disabled', false);
		}
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


function cek_narasumber(x){
	var select = $('#select_narasumber_'+x).val();
	
	if(select.substr(select.length - 1)=='-'){
		$('#elemen_'+x).html('');
		$('#form_narasumber_'+x).html('');
		$('#elemen_'+x).html('<div class="col-sm-6" style="padding-top : 10px"></div><div class="col-sm-1"></div> \
			<div class="col-sm-5"> \
                <div class="col-sm-6"><input type="radio" name="jenis_narasumber_lain['+x+']" onclick="cek_jenis('+x+', 1)" data-validation="required" value="Y">Bersertifikat</div> \
                <div class="col-sm-6"><input type="radio" name="jenis_narasumber_lain['+x+']" onclick="cek_jenis('+x+', 0)" data-validation="required" value="N">tidak Bersertifikat</div> \
			</div>\
		<div id="form_narasumber_'+x+'"></div>\
		');
		$('#elemen_'+x).show();
	} else {
		$('#elemen_'+x).html('');
		$('#form_narasumber_'+x).html('');
		$('#elemen_'+x).html('<div class="col-sm-6" style="padding-top : 10px"></div><div class="col-sm-1"></div> \
			<div class="col-sm-5"> \
                <div class="col-sm-6"><input type="radio" name="jenis_narasumber_lain['+x+']" onclick="cek_jenis('+x+', 1)" value="Y">Bersertifikat</div> \
                <div class="col-sm-6"><input type="radio" name="jenis_narasumber_lain['+x+']" onclick="cek_jenis('+x+', 0)" value="N" checked>tidak Bersertifikat</div> \
			</div>\
			<div class="col-sm-6" style="padding-top : 10px"></div><div class="col-sm-1"></div> \
			<div class="col-sm-5"> \
	            <div class="col-md-12">NIP Narasumber</div><br>\
				<div class="col-md-12"><input class="form-control" type="text" name="nip_narasumber_lain[]"></div>\
			</div>\
			<div class="col-sm-5"> \
	            <div class="col-md-12">Nama Narasumber</div><br>\
				<div class="col-md-12"><input class="form-control" type="text" name="nama_narasumber_lain[]"></div>\
			</div>\
			<input class="form-control" type="text" name="sertifikat_narasumber_lain[]" style="display: none">\
		');
		$('#elemen_'+x).hide();
	}
}

function cek_jenis(x, y){
	$('#form_narasumber_'+x).html('');
	if(y==1){
		var form_narasumber = '<div class="col-md-12">NIP Narasumber</div><br>\
		<div class="col-md-12"><input class="form-control" type="text" name="nip_narasumber_lain[]" data-validation="required"></div>\
		<div class="col-md-12">Nama Narasumber</div><br>\
			<div class="col-md-12"><input class="form-control" type="text" name="nama_narasumber_lain[]" data-validation="required"></div><br>\
		<div class="col-md-12">Upload Sertifikat (jpg, jpeg, png, pdf)</div><br>\
		<div class="col-md-12"><input class="form-control" type="file" name="sertifikat_narasumber_lain[]" data-validation="required size mime" data-validation-max-size="2M" data-validation-allowing="jpg, png, jpeg, pdf"></div>\
		';
	} else {
		var form_narasumber = '<div class="col-md-12">NIP Narasumber</div><br>\
		<div class="col-md-12"><input class="form-control" type="text" name="nip_narasumber_lain[]" data-validation="required"></div>\
		<div class="col-md-12">Nama Narasumber</div><br>\
			<div class="col-md-12"><input class="form-control" type="text" name="nama_narasumber_lain[]" data-validation="required"></div>\
		<input class="form-control" type="file" name="sertifikat_narasumber_lain[]" style="display: none">\
		';
	}
	$('#form_narasumber_'+x).html('<div class="col-sm-6" style="padding-top : 10px"></div><div class="col-sm-1"></div> \
		<div class="col-sm-5"> \
            '+form_narasumber+'\
		</div>\
	');
}

$.validate({
  modules : 'file'
});

function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	return true;
}
</script>

<style>
	.chzn-container-multi {
	height:34px
	}
	.chzn-choices{
	height:34px
	}
</style>

<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="dashboard">Dashboard</a>
	</li>
	<li class="active">Edit Data Pelaksanaan PKP</li>
</ul>

</div>

<div class="page-content">
    
<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Input Data Pelaksanaan PKP
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
							<h3 class="smaller lighter blue">Edit Data Pelaksanaan PKP</h3>
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
							<h4 class="widget-title lighter">Pelaksanaan PKP</h4>
						</div>

									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard-container">
												<div>
													<ul class="steps">
														<li data-step="1" class="active">
															<span class="step">1</span>
															<span class="title">Penyelenggaraan Penyuluhan Keamanan Pangan</span>
														</li>

														<li data-step="2">
															<span class="step">2</span>
															<span class="title">Nama Peserta PKP</span>
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
													
																	<?= @$this->session->flashdata('status'); ?>
																	<?= @$this->session->flashdata('error'); ?>	
																	<?= @$this->session->flashdata('message'); ?>
																	<?=form_open_multipart('#', array('class' => 'form-horizontal','id'=>'validation-form'))?>
																	<input type="hidden" name="id" value="<?= $nomor; ?>">

																	<?php if($this->session->userdata('user_segment') == 1 || $this->session->userdata('user_segment') == 2):?>
																	<div class="form-group">
						                                                <label class="control-label col-xs-12 col-sm-3 no-padding-right"
						                                                    for="text">Propinsi</label>
						                                                <div class="col-xs-12 col-sm-9">
						                                                    <div class="input-group col-xs-12 col-sm-9">
						                                                        <select class="select2" name="no_kode_propinsi"
						                                                            id="provinsi">
						                                                            <option value="">Pilih Propinsi</option>
						                                                            <?php
																						foreach ($provinsi as $prov) { ?>
													                                     <option value="<?php echo $prov->no_kode_propinsi ?>">
													                                     <?php echo $prov->nama_propinsi ?></option>
													                                     <?php } ?>
						                                                        </select>
						                                                    </div>
						                                                </div>
						                                            </div>
						                                            <div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"
						                                                    for="text">Kabupaten / Kota</label>
						                                                <div class="col-xs-12 col-sm-9">
						                                                    <div class="input-group col-xs-12 col-sm-9">
								                                                <select class="select2" name="nama_kabupaten" id="kota">
								                                                    <option value="0">Pilih Kabupaten/kota</option>
								                                                    <?php
																						foreach ($kota as $kot) { ?>
													                                    <option class="<?php echo $kot->no_kode_propinsi ?>" value="<?php echo $kot->id_urut_kabupaten ?>">
													                                    <?php echo $kot->nm_kabupaten ?></option>
													                                    <?php } ?>
								                                                </select>
						                                            		</div>
						                                            	</div>
																	</div>
																	<?php endif; ?>
																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Awal Penyuluhan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="input-group col-xs-12 col-sm-9">
																				<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="yyyy-mm-dd" name="tanggal_pelatihan_awal" placeholder="Pilih Tanggal Awal Penyuluhan" style="width: 560px;"/>
																				<span class="input-group-addon">
																					<i class="fa fa-calendar bigger-110"></i>
																				</span>
																				</div>

																				<p class="col-xs-12 col-sm-9 help-block">Pilih Tanggal Awal Penyuluhan, Contoh : 2014/08/04</p>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Akhir Penyuluhan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="input-group col-xs-12 col-sm-9">
																				<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="yyyy-mm-dd" name="tanggal_pelatihan_akhir" placeholder="Pilih Tanggal Akhir Penyuluhan" style="width: 560px;"/>
																				<span class="input-group-addon">
																					<i class="fa fa-calendar bigger-110"></i>
																				</span>
																				</div>

																				<p class="col-xs-12 col-sm-9 help-block">Pilih Tanggal Akhir Penyuluhan, Contoh : 2014/08/06</p>
																		</div>
																		
																	</div>
																	<label class="cotrol-label col-xs-12 cold-sm-3 no-padding-right" for="text" style="padding-left:456px; font-size:18px"><b>Daftar Narasumber</b></label><br>
																	<table border="0">
																		<?php $key=0; foreach($js_materi_utama as $data): $key++; ?>
																		<tr class="row dropdown">

																			<td style="width: 260px; text-align: right;">
																				<?=$data->nama_materi_penyuluhan.$key?>
																			</td>
																			<td style="width: 15px">
																			</td>
																			<td>
																				<input id="<?='kd_materi_'.$data->kode_materi_penyuluhan?>" name="form-field-checkbox" type="checkbox" class="ace input-lg form-control check-active" />
																				
																			<span class="lbl bigger-120"></span>
																			</td>

																			<td style="width: 20px">
																				<input name="id_urut[]" type="text"/>
																			</td>

																			<td style="width: 520px">
																				 <select style="max-width: 550px;" id="select_narasumber_<?= $key ?>" class="select2" name="nama_narasumber[]" onchange="cek_narasumber(<?= $key ?>)">
																			<?php foreach($js_narasumber as $data_cp1): ?>
												                                <option value="<?=$data->kode_materi_penyuluhan.".".$data_cp1->kode_narasumber?>"><?=$data_cp1->nama_narasumber?></option>


												                            <?php endforeach ?>
												                            <option value="<?=$data->kode_materi_penyuluhan?>.-">Lainnya</option>			
																			
												                            </select>

												                            
																			</td>
																			<tr>
																				<td>&nbsp;</td>
																				<td>&nbsp;</td>
																				<td>&nbsp;</td>
																				<td>&nbsp;</td>
																			</tr>
																		</tr>
																		<?php endforeach ?>

																	</table>

																<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Materi Penyuluhan Tambahan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<div class="dropdown">
																				<select style="width: 600px" id="materi_tambahan" class="select2" multiple name="materi_tambahan[]" data-placeholder="Pilih Materi Penyuluhan Tambahan">
																				<?php foreach($js_materi_pendukung as $data): ?>
																					<option value="<?=$data->kode_materi_penyuluhan?>"><?=$data->nama_materi_penyuluhan?></option>
																				<?php endforeach ?>				
																				</select>
																			</div>
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Materi Penyuluhan Tambahan, Contoh : Pencantuman Label Halal</p>
																			</div>
																		</div>
																	</div>

																	<!-- <div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Materi Penyuluhan Lainnya</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="materi_lainnya" placeholder="Masukan Materi Penyuluhan Lainnya" />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Materi Penyuluhan Lainnya, Contoh : Pengolahan Makanan Yang Benar</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Peserta PKP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="nama_narasumber_non_pkp" placeholder="Masukan Nama Narasumber Non PKP"  />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Nama Peserta PKP, Contoh : Dr. Sahidin</p>
																			</div>
																		</div>
																	</div> -->
																	
																
																	</div>
																	
													<div class="step-pane" data-step="2">
																<!-- <div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Narasumber</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="input-group col-xs-12 col-sm-9">
																				<select class="select2" id="nomor_permohonan_penyuluhan" data-validation="required" name="nomor_permohonan_penyuluhan">
																				<option value="">- Pilih Nomor Penyuluhan -</option>
																			<?php //foreach($no_penyuluhan as $data): ?>
																				<option value="<?=$data->nomor_permohonan_penyuluhan?>"><?=$data->nama_narasumber_non_pkp?> </option>
																			<?php //endforeach ?>				
																			</select>
																				</div>
																				<p class="col-xs-12 col-sm-9 help-block"></p>
																		</div>
																	</div> -->
																	<input type="hidden" name="jumlah_peserta" value="<?=$jumlah_peserta?>">
																	<div class="table-responsive text-nowrap">
																		<table border="1"  class="table table-striped table-bordered table-hover" >
																		<thead>
																			<tr>
																				<td>Nomor Permohonan IRTP</td>
																				<td>Status Peserta Penyuluhan</td>
																				<td>Nama Peserta Penyuluhan</td>
																				<td>Nomor sertfikat pangan</td>
																				<td>Nilai Pre Test</td>
																				<td>Nilai Post Test</td>
																				<!-- <td><div class="btn btn-sm btn-primary" style="width:65px">Add</div></td> -->
																			</tr>
																		</thead>
																			<tbody id="form_peserta">
																			</tbody>
																		</table>	
																	</div>
													</div>
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

												<button id='btn-submit' class="btn btn-warning btn-next" data-last="Selesai">
													Selanjutnya
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>
												
											</div>
                                        </div>

				</div>
			</div>
		</div>
	</div>

</div>



<script  type="text/javascript">
    
    $('#btn-submit').click(function(){
        if($(this).html().includes($(this).attr('data-last'))){
            save_data();
        }
    });
    
    function save_data()
    {
        $.ajax({
           url:'<?php echo base_url()?>pelaksanaan_pkp/proccess_edit',
           type:'POST',
           data:$("#validation-form").serialize(),
           dataType:'json',
           success:function(response){
           console.log(response);
           	// if(response.success)
            //    {
            //         //alert('Data Tersimpan!');
            //         window.location.href='<?php echo base_url()?>pelaksanaan_pkp/output_penyelenggaraan';
            //    }
            //    else{
            //        alert('Data Gagal Tersimpan');
            //    }
           },
           error:function(stat,res,err)
           {
               alert(err);
           }
        });
    }
    
    /*
    Non file
    data:JSON.stringify($('#form-input'))
    
    File
    new FormData($('#form-input'))
    
    */
    
</script>
