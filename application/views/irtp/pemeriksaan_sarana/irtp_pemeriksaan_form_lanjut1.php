<style type="text/css">
table.center tr th{
	text-align: center;
	vertical-align: middle
}
</style>
<script type="text/javascript">


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
		var minor = jQuery('.mi-res').val();		
		var mayor = jQuery('.ma-res').val();		
		var serius = jQuery('.se-res').val();		
		var kritis = jQuery('.kr-res').val();
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

		$('.DOM-' + data).val(i);
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
	
	
	
	$('.datetimepicker').datetimepicker();
	
	
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

	
	$('#nip_pengawas').change(function(){
		var value = $(this).val();
		
		if(value==''){
			$('#box_nama_pengawas').show();
			$('#nama_pengawas_lain').val('');
			$('#nama_pengawas_lain').attr("data-validation","required");
			
		} else {
			$('#box_nama_pengawas').hide();
			$('#nama_pengawas_lain').val('');
			$('#nama_pengawas_lain').removeAttr("data-validation");
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
		<li class="active">Pemeriksaan Lanjut</li>
	</ul>

</div>
<div class="page-content">
	<section class="form-pemeriksaan">


		<?php $attr_form = array('onsubmit' => 'return cek_form()'); ?>
		<?=form_open('pemeriksaan_sarana/irtp_pemeriksaan_lanjut2', $attr_form)?>
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
									<input type="text" readonly class="form-control" value="Pemeriksaan Lanjutan Sarana Produksi">
									<input type="hidden" name="tujuan_pemeriksaan" value="2">

								</div>

							</div><!-- form-group -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Nomor Permohonan</label>
									<div class="dropdown">
										<input type="text" name="nomor_permohonan" readonly class="form-control" value="<?= $record[0]['nomor_r_permohonan'] ?>">
									</div>

								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Nama IRTP Yang Diperiksa</label>
									<input type="text" readonly class="form-control" value="<?= $record[0]['nama_perusahaan'] ?>">
								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Alamat IRTP</label>
									<input type="text" readonly class="form-control" value="<?= $record[0]['alamat_irtp'] ?>">
								</div>
							</div><!-- form-group row -->                       


							<div class="form-group row">
								<div class="col-xs-12">
									<label>Provinsi</label>
									<input type="text" readonly class="form-control" value="<?= $record[0]['nama_propinsi'] ?>">
								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Kabupaten/Kota</label>
									<input type="text" readonly class="form-control" value="<?= $record[0]['nm_kabupaten'] ?>">
								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Nama Pemilik IRTP</label>
									<input type="text" readonly class="form-control" value="<?= $record[0]['nama_pemilik'] ?>">
								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Nama Penanggung Jawab IRTP</label>
									<input type="text" readonly class="form-control" value="<?= $record[0]['nama_penanggung_jawab'] ?>">
								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Nama Jenis Pangan</label>
									<input type="text" readonly class="form-control" value="<?= $record[0]['jenis_pangan'] ?>">
								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Tanggal Pemeriksaan IRTP</label>
									<input type="text" class="form-control datetimepicker" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" placeholder="Pilih Tanggal Pemeriksaan IRTP" data-validation="required" />
									<p class="help-block">Pilih Tanggal Pemeriksaan IRTP, Contoh : 2016-08-06</p>
								</div>
							</div><!-- form-group row -->

							<div class="form-group row">
								<div class="col-xs-12">
									<label>Kepala Pengawas Pangan</label>
									<div class="dropdown">
										<select class="chosen-select col-sm-12" name="nip_pengawas" id="nip_pengawas">
											<option value="">- Pilih Nama Kepala Pengawas Pangan -</option>
											<?php foreach($js_pengawas as $data): ?>
												<option value="<?=$data->kode_narasumber?>"><?=$data->nama_narasumber?></option>
											<?php endforeach ?>				
										</select>
									</div>
									<p class="help-block">Pilih Nama Kepala Pengawas Pangan sesuai dengan list rujukan.</p>
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
									<label>Observer Pengawas Pangan</label> <br>
									<input type="text" name="nip_observer_pengawas[]" class="form-control pull-left" style="width: 75%">
									<div class="col-sm-2 pull-right">
										<div class="btn-add-obs btn button btn-primary" style="width:100%">Add</div>
									</div><br><br>
									<p class="help-block">Isi Nama Observer Pengawas Pangan.</p>
								</div>
							</div><!-- form-group row -->

							<?php
						//get ketidaksesuaian
							$get_nomor_ketidaksesuaian = $this->db->query("select * from tabel_periksa_sarana_produksi_detail where id_r_urut_periksa_sarana_produksi = '".$record[0]['id_urut_periksa_sarana_produksi']."'")->result_array();
							?>

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
										<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options1" value="1" 
											<?php
											$jml_mayor = 0;
											$jml_minor = 0;
											$jml_serius = 0;
											$jml_kritis = 0;
											foreach($get_nomor_ketidaksesuaian as $nomor){ 
												if($nomor['level']=="Mayor"){ $jml_mayor++; }
												else if($nomor['level']=="Minor"){ $jml_minor++; }
												else if($nomor['level']=="Serius"){ $jml_serius++; }
												else if($nomor['level']=="Kritis"){ $jml_kritis++; }
												if($nomor['no_ketidaksesuaian']==1){ 
													echo "checked"; 
												} 
											}
											?>></td>
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
												<td style="vertical-align:middle" align="center"><input  class="opt-ma" type="checkbox" name="no_ketidaksesuaian[]" id="options2" value="2" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==2){ echo "checked"; } }?>></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td style="text-align:center">3.</td>
												<td align="justify">Lantai, dinding, dan langit-langit, tidak terawat, kotor, berdebu dan atau berlendir</td>
												<td></td>
												<td></td>
												<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options3" value="3" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==3){ echo "checked"; } }?>></td>
												<td></td>
											</tr>
											<tr>
												<td style="text-align:center">4.</td>
												<td align="justify">Ventilasi, pintu, dan jendela tidak terawat, kotor, dan berdebu</td>
												<td></td>
												<td></td>
												<td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-se" id="options4" value="4" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==4){ echo "checked"; } }?>></td>
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
													<td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-kr" id="options5" value="5" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==5){ echo "checked"; } }?>></td>
												</tr>
												<tr>
													<td style="text-align:center">6.</td>
													<td align="justify">Peralatan tidak dipelihara, dalam keadaan kotor, dan tidak menjamin efektifnya sanitasi.</td>
													<td></td>
													<td></td>
													<td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-se" id="options6" value="6" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==6){ echo "checked"; } }?>></td>
													<td></td>
												</tr>
												<tr>
													<td style="text-align:center">7.</td>
													<td align="justify">Alat ukur / timbangan untuk mengukur / menimbang berat bersih / isi bersih tidak tersedia atau tidak teliti.</td>
													<td></td>
													<td></td>
													<td style="vertical-align:middle" align="center"><input type="checkbox" name="no_ketidaksesuaian[]" class="opt-se" id="options7" value="7" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==7){ echo "checked"; } }?>></td>
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
														<td style="vertical-align:middle" align="center"><input type="checkbox"  class="opt-ma" name="no_ketidaksesuaian[]" id="options8" value="8" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==8){ echo "checked"; } }?>></td>
														<td></td>
														<td></td>
													</tr>
													<tr>
														<td style="text-align:center">9.</td>
														<td align="justify">Air berasal dari suplai yang tidak bersih</td>
														<td></td>
														<td></td>
														<td></td>
														<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options9" value="9" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==9){ echo "checked"; } }?>></td>
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
															<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options10" value="10" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==10){ echo "checked"; } }?>></td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<td style="text-align:center">11.</td>
															<td align="justify">Tidak tersedia sarana cuci tangan lengkap dengan sabun dan alat pengering tangan</td>
															<td></td>
															<td></td>
															<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options11" value="11" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==11){ echo "checked"; } }?>></td>
															<td></td>
														</tr>
														<tr>
															<td style="text-align:center">12.</td>
															<td align="justify">Sarana toilet / jamban kotor tidak terawat dan terbuka ke ruang produksi</td>
															<td></td>
															<td></td>
															<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options12" value="12" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==12){ echo "checked"; } }?>></td>
															<td></td>
														</tr>
														<tr>
															<td style="text-align:center">13.</td>
															<td align="justify">Tidak tersedia tempat pembuangan sampah tertutup</td>
															<td></td>
															<td></td>
															<td></td>
															<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options13" value="13" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==13){ echo "checked"; } }?>></td>
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
																<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options14" value="14" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==14){ echo "checked"; } }?>></td>
															</tr>
															<tr>
																<td style="text-align:center">15.</td>
																<td align="justify">Karyawan di bagian produksi pangan tidak mengenakan pakaian kerja dan / atau mengenakan perhiasan</td>
																<td></td>
																<td></td>
																<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options15" value="15" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==15){ echo "checked"; } }?>></td>
																<td></td>
															</tr>
															<tr>
																<td style="text-align:center">16.</td>
																<td align="justify">Karyawan tidak mencuci tangan dengan bersih sewaktu memulai mengolah pangan, sesudah menangani bahan mentah, atau bahan / alat yang kotor, dan sesudah ke luar dari toilet / jamban</td>
																<td></td>
																<td></td>
																<td></td>
																<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options16" value="16" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==16){ echo "checked"; } }?>></td>
															</tr>
															<tr>
																<td style="text-align:center">17.</td>
																<td align="justify">Karyawan bekerja dengan perilaku yang tidak baik (seperti makan dan minum) yang dapat mengakibatkan pencemaran produk pangan</td>
																<td></td>
																<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options17" value="17" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==17){ echo "checked"; } }?>></td>
																<td></td>
																<td></td>
															</tr>
															<tr>
																<td style="text-align:center">18.</td>
																<td align="justify">Tidak ada Penanggungjawab higiene karyawan</td>
																<td></td>
																<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options18" value="18" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==18){ echo "checked"; } }?>></td>
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
																	<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-ma" name="no_ketidaksesuaian[]" id="options19" value="19" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==19){ echo "checked"; } }?>></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr>
																	<td style="text-align:center">20.</td>
																	<td align="justify">Program higiene dan sanitasi tidak dilakukan secara berkala</td>
																	<td></td>
																	<td></td>
																	<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options20" value="20" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==20){ echo "checked"; } }?>></td>
																	<td></td>
																</tr>
																<tr>
																	<td style="text-align:center">21.</td>
																	<td align="justify">Hewan peliharaan terlihat berkeliaran di sekitar dan di dalam ruang produksi pangan.</td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options21" value="21" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==21){ echo "checked"; } }?>></td>
																</tr>
																<tr>
																	<td style="text-align:center">22.</td>
																	<td align="justify">Sampah di lingkungan dan di ruang produksi tidak segera dibuang.</td>
																	<td></td>
																	<td></td>
																	<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options22" value="22" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==22){ echo "checked"; } }?>></td>
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
																		<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options23" value="23" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==23){ echo "checked"; } }?>></td>
																	</tr>
																	<tr>
																		<td style="text-align:center">24.</td>
																		<td align="justify">Peralatan yang bersih disimpan di tempat yang kotor</td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options24" value="24" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==24){ echo "checked"; } }?>></td>
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
																			<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options25" value="25" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==25){ echo "checked"; } }?>></td>
																		</tr>
																		<tr>
																			<td style="text-align:center">26.</td>
																			<td align="justify">IRTP tidak mempunyai atau tidak mengikuti bagan alir produksi pangan</td>
																			<td></td>
																			<td></td>
																			<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options26" value="26" c<?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==26){ echo "checked"; } }?>></td>
																			<td></td>
																		</tr>
																		<tr>
																			<td style="text-align:center">27.</td>
																			<td align="justify">IRTP tidak menggunakan bahan kemasan khusus untuk pangan</td>
																			<td></td>
																			<td></td>
																			<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options27" value="27" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==27){ echo "checked"; } }?>></td>
																			<td></td>
																		</tr>
																		<tr>
																			<td style="text-align:center">28.</td>
																			<td align="justify">BTP tidak diberi penandaan dengan benar</td>
																			<td></td>
																			<td></td>
																			<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options28" value="28" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==28){ echo "checked"; } }?>></td>
																			<td></td>
																		</tr>
																		<tr>
																			<td style="text-align:center">29.</td>
																			<td align="justify">Alat ukur / timbangan untuk mengukur / menimbang BTP tidak tersedia atau tidak teliti.</td>
																			<td></td>
																			<td></td>
																			<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options29" value="29" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==29){ echo "checked"; } }?>></td>
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
																				<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options30" value="30" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==30){ echo "checked"; } }?>></td>
																			</tr>
																			<tr>
																				<td style="text-align:center">31.</td>
																				<td align="justify">Label mencantumkan klaim kesehatan atau klaim gizi</td>
																				<td></td>
																				<td></td>
																				<td></td>
																				<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options31" value="31" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==31){ echo "checked"; } }?>></td>
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
																					<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options32" value="32" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==32){ echo "checked"; } }?>></td>
																				</tr>
																				<tr>
																					<td style="text-align:center">33.</td>
																					<td align="justify">IRTP tidak melakukan pengawasan internal secara rutin, termasuk monitoring dan tindakan koreksi</td>
																					<td></td>
																					<td></td>
																					<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options33" value="33" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==33){ echo "checked"; } }?>></td>
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
																						<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options34" value="34" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==34){ echo "checked"; } }?>></td>
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
																							<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-se" name="no_ketidaksesuaian[]" id="options35" value="35" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==35){ echo "checked"; } }?>></td>
																							<td></td>
																						</tr>
																						<tr>
																							<td style="text-align:center">36.</td>
																							<td align="justify">Dokumen produksi tidak mutakhir, tidak akurat, tidak tertelusur dan tidak disimpan selama 2 (dua) kali umur simpan produk pangan yang diproduksi</td>
																							<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-mi" name="no_ketidaksesuaian[]" id="options36" value="36" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==36){ echo "checked"; } }?>></td>
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
																								<td style="vertical-align:middle" align="center"><input type="checkbox" class="opt-kr" name="no_ketidaksesuaian[]" id="options37" value="37" <?php foreach($get_nomor_ketidaksesuaian as $nomor){ if($nomor['no_ketidaksesuaian']==37){ echo "checked"; } }?>></td>
																							</tr>
																							<tr>
																								<td></td>
																								<td style="text-align:right">Jumlah Ketidaksesuaian KRITIS</td>
																								<td disabled></td>
																								<td disabled></td>
																								<td disabled></td>
																								<!-- <td class="DOM-opt-kr"><?= $jml_kritis ?></td> -->
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-kr" value="<?= $jml_kritis ?>"></td>
																							</tr>
																							<tr>
																								<td></td>
																								<td style="text-align:right">Jumlah Ketidaksesuaian SERIUS</td>
																								<td disabled></td>
																								<td disabled></td>
																								<!-- <td class="DOM-opt-se"><?= $jml_serius ?></td> -->
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-se" value="<?= $jml_serius ?>"></td>
																								<td disabled></td>
																							</tr>
																							<tr>
																								<td></td>
																								<td style="text-align:right">Jumlah Ketidaksesuaian MAYOR</td>
																								<td disabled></td>
																								<!-- <td class="DOM-opt-ma"><?= $jml_mayor ?></td> -->
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-ma" value="<?= $jml_mayor ?>"></td>
																								<td disabled></td>
																								<td disabled></td>
																							</tr>
																							<tr>
																								<td></td>
																								<td style="text-align:right">Jumlah Ketidaksesuaian MINOR</td>
																								<!-- <td class="DOM-opt-mi"><?= $jml_minor ?></td> -->
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-mi" value="<?= $jml_minor ?>"></td>
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
																								<td id="level"><?= $record[0]['level_irtp'] ?></td>
																								<td id="frequency"><?= $record[0]['frekuensi_audit'] ?></td>
																								<!-- <td class="DOM-opt-mi mi-res"><?= $jml_minor ?></td> -->
																								<!-- <td class="DOM-opt-ma ma-res"><?= $jml_mayor ?></td> -->
																								<!-- <td class="DOM-opt-se se-res"><?= $jml_serius ?></td> -->                    			
																								<!-- <td class="DOM-opt-kr kr-res"><?= $jml_kritis ?></td> -->
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-mi mi-res" value="<?= $jml_minor ?>"></td>
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-ma ma-res" value="<?= $jml_mayor ?>"></td>
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-se se-res" value="<?= $jml_serius ?>"></td>
																								<td><input type="text" disabled style="width:22px;border: 0px solid;background-color: transparent" class="DOM-opt-kr kr-res" value="<?= $jml_kritis ?>"></td>
																							</tr>
																						</table>

																						<input type="hidden" id="DOM-level" name="level_irtp" value="<?= $record[0]['level_irtp'] ?>">
																						<input type="hidden" id="DOM-freq" name="freq_irtp" value="<?= $record[0]['frekuensi_audit'] ?>">
																						<input type="hidden" name="id_r_urut_periksa_sarana_produksi" value="<?= $record[0]['id_urut_periksa_sarana_produksi']?>">
																						<input type="submit" class="btn btn-success col-sm-12 col-xs-12 col-md-12" value="Selanjutnya &gt;">
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




																	<?=form_close()?>
																</section>
															</div>
