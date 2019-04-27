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
      '.select2-deselect'  : {allow_single_deselect:true},
      '.select2-no-single' : {disable_search_threshold:10},
      '.select2-no-results': {no_results_text:'Oops, nothing found!'},
      '.select2-width'     : {width:"100%"}
    }
    // for (var selector in config) {
    //   $(selector).select2(config[selector]);
    // }
	
	
	// $('.datetimepicker').datetimepicker();
	
	/*
	$("#pil_perusahaan").autocomplete({
		source: "<?php echo base_url(); ?>pb2kp/get_perusahaan" 
	});
	*/

	$(document).on('change', '#nomor_pirt', function(e){
		var no_pirt = $(this).val().split('.');
		var index = parseInt(no_pirt[0]);
		
		$.ajax({
			url 	: '<?=base_url()?>pencabutan_sppirt/get_perusahaan_by_pirt',
			type	: 'POST',
			dataType: 'json',
			data 	: 'nomor=' + index,
			success	: function(html){
				var penanggung_jawab = $('#penanggung_jawab');
				var telepon_irtp = $('#telepon_irtp');
				var alamat_irtp = $('#alamat_irtp');
				var kode_perusahaan = $('#kode_perusahaan');
				var pemilik = $('#pemilik');
				var nama_perusahaan = $('#nama_perusahaan');

				$.each(html, function(key, html){
					penanggung_jawab.val(html.nama_penanggung_jawab);
					telepon_irtp.val(html.nomor_telefon_irtp);
					alamat_irtp.val(html.alamat_irtp);
					kode_perusahaan.val(html.kode_perusahaan);
					pemilik.val(html.nama_pemilik);
					nama_perusahaan.val(html.nama_perusahaan);
				});				
			}, error: function(e){
				console.log(e)
			}

		});
	});

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
	
	$('#alasan_pencabutan').change(function(){
		var value = $(this).val();
		if(value=='-'){
			$('#box_alasan_pencabutan').show();
			$('#alasan_pencabutan_lain').val('');
			$('#alasan_pencabutan_lain').attr("data-validation","required");
			
		} else {
			$('#box_alasan_pencabutan').hide();
			$('#alasan_pencabutan_lain').val('');
			$('#alasan_pencabutan_lain').removeAttr("data-validation");
		}
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
	<li class="active">Edit Data Pencabutan SPPIRT</li>
</ul>

</div>

<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Input Data Pencabutan SPPIRT
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
							<h3 class="smaller lighter blue">Edit Data Pencabutan SPPIRT</h3>
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
							<h4 class="widget-title lighter">Pencabutan SPPIRT</h4>
						</div>

									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard-container">
												<!-- <div>
													<ul class="steps">
														<li data-step="1" class="active">
															<span class="step">1</span>
															<span class="title">Pencabutan SPPIRT</span>
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
																	
																	
																	<?=form_open_multipart('pencabutan_sppirt/add', array('class' => 'form-horizontal','id'=>'validation-form'))?>
																	<input type="hidden" name="">

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Sertifikat P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<select class="select2" data-validation="required" name="nomor_pirt" id="nomor_pirt">
														                    	<option value="">- Pilih Nomor Sertifikat P-IRT -</option>
														                        <?php foreach($irtp_lama as $data): ?>
														                        <option value="<?=$data->id_urut_penerbitan_sert?>"><?=$data->nomor_pirt." . ".$data->nama_perusahaan?></option>
														                        <?php endforeach ?>
														                    </select>
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Nomor Sertifikat P-IRT, Contoh : 1234567890123-45</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" data-validation="required" id="nama_perusahaan" placeholder="Pilih Nomor Sertifikat P-IRT terlebih dahulu" readonly/>
																				<p class="col-xs-12 col-sm-9 help-block">Nama IRTP akan tampil setelah memilih Nomor Sertifikat P-IRT, Contoh : IRTP Rivi</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Pemilik IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" data-validation="required" id="pemilik" placeholder="Pilih Nomor Sertifikat P-IRT terlebih dahulu" readonly  />
																				<p class="col-xs-12 col-sm-9 help-block">Nama Pemilik IRTP akan tampil setelah memilih Nomor Sertifikat P-IRT, Contoh : Rivi Anggara</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nama Penanggung Jawab</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" data-validation="required" id="penanggung_jawab" placeholder="Pilih Nomor Sertifikat P-IRT terlebih dahulu" readonly />
																				<p class="col-xs-12 col-sm-9 help-block">Nama Penanggung Jawab IRTP akan tampil setelah memilih Nomor Sertifikat P-IRT, Contoh : Ahmad Rivi</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Telepon IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" data-validation="required" id="telepon_irtp" placeholder="Pilih Nomor Sertifikat P-IRT terlebih dahulu" readonly />
																				<p class="col-xs-12 col-sm-9 help-block">Nomor Telepon IRTP akan tampil setelah memilih Nomor Sertifikat P-IRT, Contoh : 021111222</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alamat IRTP</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<textarea id="alamat_irtp" class="input-xlarge" data-validation="required" placeholder="Pilih Nomor Sertifikat P-IRT terlebih dahulu" readonly  ></textarea>
																			<p class="col-xs-12 col-sm-9 help-block">Alamat Lengkap IRTP akan tampil setelah memilih Nomor Sertifikat P-IRT, Contoh : Jl. Dlima No. 101</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alasan Pencabutan Sertifikat P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<div class="dropdown">
																					<select class="select2" id="alasan_pencabutan" name="alasan_pencabutan" data-validation="required">
																						<option disabled selected>- Pilih Alasan Pencabutan Sertifikat P-IRT-</option>
																					<?php foreach($js_pencabutan as $data): ?>
																						<option value="<?=$data->kode_alasan_pencabutan?>"><?=$data->alasan_pencabutan?></option>
																					<?php endforeach ?>	
																						<option value="-">Alasan pencabutan (lainnya)</option>
																					</select>
																				</div><!-- dropdown -->
																			</div><!-- col-xs-12 -->
																				<p class="col-xs-12 col-sm-9 help-block">Pilih Alasan Pencabutan Sertfikat P-IRT sesuai dengan list rujukan</p>
																			</div>
																	</div>
																	
																	<div class="form-group" id="box_alasan_pencabutan" style="display:none">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Alasan Pencabutan (Lainnya)</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" name="alasan_pencabutan_lain" id="alasan_pencabutan_lain" placeholder="Isi Alasan Pencabutan lain" />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Alasan Pencabutan Lainnya</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Nomor Berita Acara Pencabutan Sertifikat P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="text" class="col-xs-12 col-sm-9" id="nomor_berita_acara" name="nomor_berita_acara" placeholder="Masukan Nomor Berita Acara" />
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Nomor Berita Acara Pencabutan Sertifikat P-IRT sesuai dengan ketentuan, Contoh : 1234567890123-45</p>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Tanggal Pencabutan Sertifikat P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="input-group col-xs-12 col-sm-9">
																				<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="yyyy-mm-dd" name="tanggal_pencabutan" placeholder="Pilih Tanggal Pencabutan Sertifikat P-IRT" />
																				<span class="input-group-addon">
																					<i class="fa fa-calendar bigger-110"></i>
																				</span>
																				</div>
																				<p class="col-xs-12 col-sm-9 help-block">Masukan Tanggal Pencabutan Sertifikat P-IRT</p>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="text">Upload Scan Berita Acara Pencabutan Sertifikat P-IRT</label>

																		<div class="col-xs-12 col-sm-9">
																			<div class="clearfix">
																				<input type="file" class="col-xs-12 col-sm-9" value="<?= isset($old_inputs['file_foto'])? $old_inputs['file_foto'] : '' ?>" name="file_foto" data-validation="required" accept=".jpg,.png,.gif,.pdf,.jpeg"/>
																				<p class="col-xs-12 col-sm-9 help-block">Scan format PDF/JPG maksimal berukuran 2 Mb</p>
																			</div>
																		</div>
																	</div>

																
																<!--<button type="submit" class="btn btn-primary col-sm-12 col-xs-12 col-md-12">Kirim &raquo;</button>-->
																
																

													</div>
													
													<!-- <button type="submit">ok</button> -->
													
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
												<!-- <button class="btn btn-prev">
													<i class="ace-icon fa fa-arrow-left"></i>
													Sebelumnya
												</button> -->

												<button type="submit" class="btn btn-warning btn-next" >
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

<script type="text/javascript">
    
    $('#btn-submit').click(function(){
        if($(this).html().includes($(this).attr('data-last'))){
            save_data();
        }
    });
    
    function save_data()
    {
        $.ajax({
           url:'<?php echo base_url()?>pencabutan_sppirt/add',
           type:'POST',
           data: $("#validation-form").serialize(),
           dataType:'json',
           success:function(response){
               if(response.success)
               {
                    //alert('Data Tersimpan!');
                    window.location.href='<?php echo base_url()?>pencabutan_sppirt/output_pencabutan';
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
    }
    
    /*
    Non file
    data:JSON.stringify($('#form-input'))
    
    File
    new FormData($('#form-input'))
    
    */
    
</script>


