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
		var DOM = $(this).parents('.appended');
		var curr = $(this);
		var temp = '<div class="row data-strip" style="margin-top : 10px"> \
						<div class="col-sm-7"> \
							<select class="chosen-select" data-placeholder="Masukan Komposisi Tambahan" style="width : 100%;" name="komposisi_tambah[]"> \
							<?php foreach($js_komp_tmbh as $data): ?> \
								<option value="<?=$data->no_urut_btp?>"><?=addslashes($data->nama_bahan_tambahan_pangan)?></option> \
							<?php endforeach ?>	\
							</select> \
						</div> \
                        <div class="col-sm-3"> \
							<input type="text" class="form-control" name="berat_bersih_tambahan[]" placeholder="Berat Bersih" data-validation="required"/> \
						</div> \
                        <div class="col-sm-2"> \
							<div class="btn-add btn button btn-primary" style="width:100%">Add</div> \
						</div> \
					</div>';
		DOM.append(temp);	
		DOM.find('select').trigger('chosen:updated').chosen({'width' : '100%'});
		
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
				temp = "<select class='chosen-select col-sm-12' name='jenis_pangan' id='jenis_pangan' data-validation='required'><option value=''>- Pilih Nama Jenis Pangan -</option>";
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

<!--<link href="<?php echo base_url();?>css/bpom.css" rel="stylesheet">	-->
<link href="<?php echo base_url();?>css/chosen/chosen.min.css" rel="stylesheet">	
<script type="text/javascript" src="<?=base_url()?>js/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/chosen/prism.js"></script>
<h2 class="heading-form">Informasi Kepemilikan</h2>	
<div class="well">
<div class="row">
	<div class="col-sm-7">		
		<?=validation_errors()?>
       <?= @$this->session->flashdata('error') ?>
		<?= @$this->session->flashdata('status') ?>
		<?= @$this->session->flashdata('message') ?>
		
		<?php $attr_form = array('onsubmit' => 'return cek_permohonan()'); ?>
		<?=form_open('irtp/set_data_irtp_spp', $attr_form)?>
        <div class="form-group-cat">			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Nama Pemilik IRTP</label>
					<div class="dropdown">
						<select name="pemilik_usaha" id="pemilik_usaha" class="chosen-select col-sm-12" data-validation="required">
							<option value="">- Pilih Nama Pemilik IRTP -</option>
						<?php foreach($js_perus as $data): ?>
							<option value="<?=$data->kode_perusahaan?>"><?=$data->kode_perusahaan." - ".$data->nama_pemilik?></option>
						<?php endforeach ?>				
						</select>
					</div>
					<p class="help-block">Masukan Nama Pemilik IRTP, Contoh : Dr. Joni Kawaldi</p>
				</div>
			</div><!-- form-group-row -->
            
            <div class="form-group row">
				<div class="col-xs-12">
					<label>Nama Penanggung Jawab IRTP</label>
					<input type="text" class="form-control" id="penanggung_jawab" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" readonly />					
					<p class="help-block">Nama Penanggung Jawab IRTP akan terisi secara otomatis setelah memilih Nama Pemilik IRTP, Contoh : Hasan Sadikin, S.Pd</p>
				</div>
			</div><!-- form-group -->
            
            <div class="form-group row">
				<div class="col-xs-12">
					<label>Alamat IRTP</label>
					<textarea id="alamat_irtp" class="form-control" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" readonly /></textarea>					
					<p class="help-block">Alamat IRTP akan terisi otomatis setelah memilih Nama Pemilik IRTP, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
				</div>
			</div><!-- form-group -->
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Nomor Telepon IRTP</label>
					<input type="text" class="form-control" id="telepon_irtp" placeholder="Pilih Nama Pemilik IRTP terlebih dahulu" readonly />					
					<p class="help-block">Nomor Telepon IRTP akan terisi otomatis setelah Memilih Nama Pemilik IRTP, Contoh : Jl. Margonda Raya No. 123, Pondok Cina</p>
				</div>
			</div><!-- form-group -->
		</div><!-- form-group-cat -->
                
		<div class="form-group-cat">
			<h2 class="heading-form">Informasi Produk Pangan</h2>
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Kategori Jenis Pangan</label>
					<div class="dropdown">				
						<select class="chosen-select col-sm-12" name="grup_jenis_pangan" id="grup_jenis_pangan" data-validation="required">
						<option value="">- Pilih Kategori Jenis Pangan -</option>
						<?php foreach($js_grup_pangan as $values): ?>
							<!-- <optgroup label="<?=$values->kode_grup_jenis_pangan." ".$values->nama_grup_jenis_pangan?>">
							<?php 
								// foreach($js_pangan as $data): 
								// 	if($data->kode_r_grup_jenis_pangan == $values->kode_grup_jenis_pangan):
							?> -->
								<option value="<?=$values->kode_grup_jenis_pangan?>"><?=$values->kode_grup_jenis_pangan." ".$values->nama_grup_jenis_pangan?></option>
							<!-- <?php 
								// 	endif;
								// endforeach 
							?>	 -->
							</optgroup>
						<?php endforeach ?>			
						</select>
					</div>
					<p class="help-block">Pilih Kategori Jenis Pangan sesuai dengan list rujukan, Contoh : Hasil Olahan Daging Kering</p>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-xs-12">
					<label id="label_jenis_pangan">Nama Jenis Pangan</label>
					<div class="dropdown">				
						<select class="chosen-select col-sm-12" name="jenis_pangan" id="jenis_pangan" data-validation="required">
							<option value="">- Pilih Nama Jenis Pangan -</option>	
						</select>
					</div>
					<p class="help-block">Pilih Nama Jenis Pangan sesuai dengan list rujukan, Contoh : Bihun</p>
				</div>
			</div>

			<div class="form-group row" id="box_jenis_pangan" style="display:none">
				<div class="col-xs-12">
					<label>Jenis Pangan (Lainnya)</label>
					<input type="text" name="jenis_pangan_lain" class="form-control" id="jenis_pangan_lain" placeholder="Isi Jenis Pangan Lain" />
					<p class="help-block">Masukan Jenis Pangan Lainnya, Contoh : Ganyong</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Nama Produk Pangan</label>
					<textarea name="deskripsi_pangan" class="form-control" id="deskripsi_pangan" data-validation="required" placeholder="Masukan Nama Produk Pangan" ></textarea>
					<p class="help-block">Masukan Nama Produk Pangan, Contoh : Bihun Rasa Bayam</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Nama Dagang</label>
					<input type="text" class="form-control" name="nama_dagang" id="nama_dagang" placeholder="Masukan Nama Dagang" />					
					<p class="help-block">Masukan Nama Dagang, Contoh : Kripik Maicih</p>
				</div>
			</div><!-- form-group -->
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Jenis Kemasan</label>
					<div class="dropdown">
						<select name="jenis_kemasan" class="chosen-select col-sm-12" id="jenis_kemasan" data-validation="required">
							<option value="">- Pilih Jenis Kemasan -</option>
						<?php foreach($js_kemasan as $data): ?>
							<option value="<?=$data->kode_kemasan?>"><?=$data->jenis_kemasan?></option>
						<?php endforeach ?>				
						</select>
					</div>
					<p class="help-block">Pilih Jenis Kemasan yang kontak langsung dengan pangan sesuai dengan list rujukan, Contoh : Plastik</p>
				</div>
			</div>
			
			<div class="form-group row" id="box_jenis_kemasan" style="display:none">
				<div class="col-xs-12">
					<label>Jenis Kemasan (Lainnya)</label>
					<input type="text" name="jenis_kemasan_lain" class="form-control" id="jenis_kemasan_lain" placeholder="Isi Jenis Kemasan" />
					<p class="help-block">Masukan Jenis Kemasan Lainnya, Contoh : Karung</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Berat Bersih / Isi Bersih</label>
                    <div class="row">
                    	<div class="col-xs-12">
							<input type="text" name="berat_bersih" class="form-control" id="berat_bersih" data-validation="required" placeholder="Masukan Berat Bersih / Isi Bersih"  />
                    	</div>
                        <!--<div class="col-xs-3">
							<select name="satuan_berat_bersih" class="form-control chosen-select" id="satuan">
                            <?php foreach($js_satuan as $data): ?>
                            	<option value="<?=$data->kode_satuan?>"><?=$data->nama_satuan?></option>
                             <?php endforeach ?>
                            </select>
                    	</div>-->
                    </div>
					<p class="help-block">Masukan Berat Bersih / Isi Bersih, Contoh : 100 gram</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Komposisi Bahan Utama</label>
					<textarea name="komposisi_utama" class="form-control" id="komposisi_utama" data-validation="required" placeholder="Masukan Komposisi Bahan Utama" ></textarea>
					<p class="help-block">Masukan Komposisi Bahan Utama (diisi menurut komposisi yang terbesar), Contoh : 1. Tepung Terigu, 2. Garam</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-lg-12 has_append">
					<label>Komposisi Bahan Tambahan Pangan</label>
					<div class="appended">
                    	<div class="row data-strip">
                            <div class="col-sm-7">
                                <select class="chosen-select" data-placeholder="Masukan Komposisi Tambahan" style="width : 100%" name="komposisi_tambah[]">
                                <?php foreach($js_komp_tmbh as $data): ?>
                                    <option value="<?=$data->no_urut_btp?>"><?php echo $data->nama_bahan_tambahan_pangan;?></option>
                                <?php endforeach ?>				
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="berat_bersih_tambahan[]" placeholder="Berat Bersih" data-validation="required"/>
                            </div>
                            <div class="col-sm-2">
                                <div class="btn-add btn button btn-primary" style="width:100%">Add</div>
                            </div>
                        </div> 
						<p class="help-block">Pilih Komposisi Bahan Tambahan Pangan sesuai dengan list rujukan dan isilah berat bersih lengkap dengan satuannya, Contoh : Kalsium alginat (Calcium alginate) | 20 miligram/kg bahan, bila tidak menggunakan bahan tambahan, pilih "Tanpa Bahan Tambahan Pangan" kemudian diisi angka 0</p>
					</div><!-- append -->
				</div><!-- col-lg-12 -->
			</div><!-- form-group -->
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Proses Produksi</label>
					<div class="dropdown">
						<select class="chosen-select col-sm-12" name="proses_produksi" id="proses_produksi" data-validation="required">
							<option value="">- Pilih Proses Produksi -</option>
						<?php foreach($js_tek_olah as $data): ?>
							<option value="<?=$data->kode_tek_olah?>"><?=$data->kode_tek_olah." - ".$data->tek_olah?></option>
						<?php endforeach ?>				
						</select>
					</div>
					<p class="help-block">Pilih Proses Produksi sesuai dengan list rujukan, Contoh : Menggoreng</p>
				</div>
			</div>
			
			<div class="form-group row" id="box_proses_produksi" style="display:none">
				<div class="col-xs-12">
					<label>Proses Produksi (Lainnya)</label>
					<input type="text" name="proses_produksi_lain" class="form-control" id="proses_produksi_lain" placeholder="Isi Proses Produksi" />
					<p class="help-block">Masukan Proses Produksi Lainnya, Contoh : Pengasapan</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Informasi Masa Simpan (Kedaluwarsa)</label>
					<input type="text" name="masa_simpan" class="form-control" data-validation="required" id="masa_simpan" placeholder="Masukan Informasi Masa Simpan (Kedaluwarsa)" />
					<p class="help-block">Masukan Informasi Masa Simpan (Kedaluwarsa) <b>harus dalam satuan Hari</b>, Contoh : 90 hari</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Informasi Kode Produksi</label>
					<input type="text" name="kode_produksi" class="form-control" id="info_kode_produksi" placeholder="Masukan Informasi Kode Produksi" />
					<p class="help-block">Masukan Informasi Kode Produksi dengan lengkap, Contoh : A.01.12.17 menunjukkan A = Shift Pagi; 01 = Tanggal 1; 12 = bulan Desember; 17 = tahun 2017. <b>Kode produksi dapat diisi sesuai dengan Kode Produksi yang dibuat oleh masing-masing IRTP. Jika belum ada, kode diatas bisa menjadi contoh untuk membuat kode produksi.</b></p>
				</div>
			</div>
		</div>
         
		<div class="form-group-cat">	
			
			<div class="form-group row">
				<div class="col-xs-12">
					<label>Tanggal Pengajuan</label>
					<input type="text" name="tanggal_pengajuan" class="form-control datetimepicker" placeholder="Pilih Tanggal Pengajuan"/>
					<p class="help-block">Pilih Tanggal Pengajuan, Contoh : 2014-08-04</p>
				</div>
			</div>
			
			<button type="submit" name="submit" class="btn btn-primary col-sm-12 col-xs-12 col-md-12"><b>Kirim &raquo;</b></button>
		<?=form_close()?>
		</div>
	</div>

	<div class="col-sm-5">
		<div class="panel-instruction">
	        <h3>Lokasi Anda Saat Ini :</h3>
	        <h3>IRTP >> Permohonan SPP IRT</h3>
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
</div>