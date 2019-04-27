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
$(document).ready(function() {
	//nama perusahaan
	var config = {
      '.select2'           : {},
      '.chosen-select2-deselect'  : {allow_single_deselect:true},
      '.chosen-select2-no-single' : {disable_search_threshold:10},
      '.chosen-select2-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select2-width'     : {width:"100%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
	
	
	//$('.datetimepicker').datetimepicker();
	
	/*
	$("#pil_perusahaan").autocomplete({
		source: "<?php echo base_url(); ?>pb2kp/get_perusahaan" 
	});
	*/
	var perusahaan = $("#pil_perusahaan").val();
	
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
								<option value="<?=$data->no_urut_btp?>"><?=$data->nama_bahan_tambahan_pangan?></option> \
							<?php endforeach ?>	\
							</select> \
						</div><!-- col-sm-7 --> \
                        <div class="col-sm-3"> \
							<input type="text" class="form-control" name="berat_bersih[]" placeholder="Berat Bersih" data-validation="number"/> \
						</div><!-- col-sm-3 --> \
                        <div class="col-sm-2"> \
							<div class="btn-add btn button btn-primary" style="width:100%">Add</div> \
						</div><!-- col-sm-2 --> \
					</div>';
		DOM.append(temp);	
		DOM.find('select').trigger('chosen:updated').chosen({'width' : '100%'});
		
		curr.html('Delete').removeClass('btn-primary btn-add').addClass('btn-danger btn-delete');
	});

	$(document).on('change', '#nomor_pirt', function(e){
		var no_pirt = $(this).val();
		
		var suffix = no_pirt.substring(0, 14);
		var infix = no_pirt.substring(14);
		var obj_pirt_baru = $('#nomor_pirt_baru');
		console.log(infix);
		obj_pirt_baru.val(suffix + (parseInt(infix) + 5));

		$.ajax({
			url 	: '<?=base_url()?>perpanjangan_sertifikat/get_no_permohonan_by_pirt',
			type	: 'POST',
			dataType: 'json',
			data 	: 'nomor=' + no_pirt,
			success	: function(html){
				var penanggung_jawab = $('#penanggung_jawab');
				var telepon_irtp = $('#telepon_irtp');
				var alamat_irtp = $('#alamat_irtp');
				var kode_perusahaan = $('#kode_perusahaan');
				var pemilik = $('#pemilik');
				var nama_perusahaan = $('#nama_perusahaan');
				var no_permohonan = $('#no_permohonan');
				var id_urut_penerbitan_sert = $('#id_urut_penerbitan_sert');

				$.each(html, function(key, html){
					//console.log(html.nama_perusahaan);
					nama_perusahaan.val(html.nama_perusahaan);
					penanggung_jawab.val(html.nama_penanggung_jawab);
					telepon_irtp.val(html.nomor_telefon_irtp);
					alamat_irtp.val(html.alamat_irtp);
					kode_perusahaan.val(html.kode_perusahaan);
					pemilik.val(html.nama_pemilik);
					no_permohonan.val(html.nomor_r_permohonan);
					id_urut_penerbitan_sert.val(html.id_urut_penerbitan_sert);
				});				
			}, error: function(e){
				console.log("singit");
				console.log(e);
				
			}

		});
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

<!--<link href="<?php echo base_url();?>css/bpom.css" rel="stylesheet">	-->
<!--<link href="<?php echo base_url();?>css/chosen/chosen.min.css" rel="stylesheet">	-->
<!--<script type="text/javascript" src="<?=base_url()?>js/chosen/chosen.jquery.min.js"></script>-->
<!--<script type="text/javascript" src="<?=base_url()?>js/chosen/prism.js"></script>-->

<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="<?php echo site_url()?>">Dashboard</a>
	</li>
	<li class="active">Input Data Perpanjangan Sertifikat</li>
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
							<h3 class="smaller lighter blue">Input Data Perpanjangan Sertifikat</h3>
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
							<h4 class="widget-title lighter">Perpanjangan Sertifikat</h4>
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
																	<?=form_open_multipart('perpanjangan_sertifikat/add', array('class' => 'form-horizontal', 'id' => 'sample-form'))?>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Sertifikat P-IRT Lama</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<select class="select2" name="nomor_pirt" id="nomor_pirt">
														                    	<option value="">- Pilih Nomor Sertifikat P-IRT Lama -</option>
														                        <?php foreach($irtp_lama as $data): ?>
														                        <option data-index="" value="<?=$data->nomor_pirt?>"><?=$data->nomor_pirt." . ".$data->nama_perusahaan.' - '.$data->nama_pemilik.' - '.$data->nama_dagang?></option>
														                        <?php endforeach ?>
														                    </select>
																				<p class="col-xs-12 col-sm-9 help-block">Keterangan Pilihan : No. Sertifikat P-IRT Lama . Nama Perusahaan - Nama Pemilik - Nama Dagang, Contoh : 1234567890123-45 . IRTP Rivi - Rivi Anggara - Kentang Rivi</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Sertifikat P-IRT Baru</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="nomor_pirt_baru" name="nomor_pirt_baru" data-validation="required" placeholder="Pilih Nomor Sertifikat P-IRT Lama terlebih dahulu" />
																				<p class="col-xs-12 col-sm-9 help-block">Nomor Sertifikat P-IRT Lama akan terisi otomatis setelah memilih Nomor Sertifikat IRTP Lama, Contoh : 1234567890123-45</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="nama_perusahaan" placeholder="Pilih Nomor Sertifikat P-IRT Lama terlebih dahulu" readonly />
																				<p class="col-xs-12 col-sm-9 help-block">
																				Nama IRTP akan terisi otomatis setelah memilih Nomor Sertifikat IRTP Lama, Contoh : CV. Aroma</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Pemilik IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="pemilik" placeholder="Pilih Nomor Sertifikat P-IRT Lama terlebih dahulu" readonly>
																			<p class="col-xs-12 col-sm-9 help-block">Nama Pemilik IRTP akan terisi otomatis setelah memilih Nomor Sertifikat IRTP Lama, Contoh : Rivi Anggara</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Penanggung Jawab IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="penanggung_jawab" placeholder="Pilih Nomor Sertifikat P-IRT Lama terlebih dahulu" readonly>
																			<p class="col-xs-12 col-sm-9 help-block">Nama Penanggung Jawab IRTP akan terisi otomatis setelah memilih Nomor Sertifikat IRTP Lama, Contoh : Ahmad Rivi</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Telepon IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="telepon_irtp" placeholder="Pilih Nomor Sertifikat P-IRT Lama terlebih dahulu" readonly >
																			<p class="col-xs-12 col-sm-9 help-block">Nomor Telepon IRTP akan terisi otomatis setelah memilih Nomor Sertifikat P-IRT Lama, Contoh : 02111122211</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alamat IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<textarea class="input-xlarge" placeholder="Pilih Nomor Sertifikat P-IRT Lama terlebih dahulu" readonly ></textarea>
																			<p class="col-xs-12 col-sm-9 help-block">Alamat IRTP akan terisi otomatis setelah memilih Nomor Sertifikat P-IRT Lama, Contoh : Jl. Bangau No. 23</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Pengajuan Perpanjangan Nomor P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="input-group col-xs-12 col-sm-9">
																				<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="yyyy-mm-dd" name="tanggal_pengajuan_perpanjangan" placeholder="Masukan Tanggal Pengajuan Perpanjangan Nomor P-IRT" style="width: 560px;"/>
																				<span class="input-group-addon">
																					<i class="fa fa-calendar bigger-110"></i>
																				</span>
																				</div>
																			<p class="col-xs-12 col-sm-9 help-block">Masukan tanggal pengajuan perpanjangan Nomor P-IRT, Contoh : 2014/08/15</p>
																			
																		</div>
																	</div>

																	 <div class="form-group" style="display:none;">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Permohonan</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="no_permohonan" id="no_permohonan" placeholder="Masukan Nomor Permohonan" onkeypress="return isNumberKey(event)" >
																				<input type="hidden" name="id_urut_penerbitan_sert" id="id_urut_penerbitan_sert">
																			<p class="col-xs-12 col-sm-9 help-block">Masukan nomor permohonan SPP-IRT (Maksimum 18 digit), Contoh : ABS-2210.1213-2014</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Scan (Label Final)</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="file" class="col-xs-12 col-sm-9" name="file_foto" data-validation="required" >
																			<p class="col-xs-12 col-sm-9 help-block">Scan format PDF/JPG maksimal berukuran 2 Mb</p>
																			</div>
																		</div>
																	</div>
																
																<!--<button type="submit" class="btn btn-primary col-sm-12 col-xs-12 col-md-12">Kirim &raquo;</button>-->
																<button type="submit">ok</button>
																<?=form_close()?>
																	</div>
																	
													


													<div class="step-pane" data-step="2">
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

												<button class="btn btn-warning btn-next" data-last="Selesai">
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


