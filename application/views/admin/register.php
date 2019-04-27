<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<style type="text/css">
	nav ul li{
		display				: inline;
		list-style-type	: circle;
		margin-right		: 14px;	
	}
	nav ul li:last-child{
		margin-right		: 0;	
	}

</style>

<script>
	$(document).ready(function() {
		
		$('#input_propinsi').hide();
		$('#input_kabupaten').hide();
		
		$('#hak_akses').change(function(){
			var hak_akses = $(this).val();
			if(hak_akses=='4' || hak_akses=='3'){
				$('#input_propinsi').show();
				$('#input_kabupaten').hide();	
			} else if(hak_akses=='5'){
				$('#input_propinsi').show();
				$('#input_kabupaten').show();
			} else {
				$('#input_propinsi').hide();
				$('#input_kabupaten').hide();
			}
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
						temp += "<option value='" + key.id_urut_kabupaten + "'>" + key.no_kabupaten + ". " + key.nm_kabupaten + "</option>";
					});				
					
					console.log($('#kabupaten').html(temp));
					$('#kabupaten').trigger('liszt:updated').chosen();
					
				},error: function(e){
					console.log(e);
				}
			});	
		});
	});
	
	function cek_register(){
		if($('#uname').val()==""){
			alert('Username belum diisi!');
			return false;
		} else if($('#password').val()=="" || $('#password_again').val()==""){
			alert('Password belum diisi!');
			return false;
		} else if($('#password').val()!=$('#password_again').val()){
			alert('Password tidak sesuai!');
			return false;
		} else if($('#hak_akses').val()==""){
			alert('Hak ases belum dipilih!');
			return false;
		} else if($('#email').val()==""){
			alert('Email belum diisi!');
			return false;
		} else if(validateEmail($('#email').val())==false){
			alert('Email tidak sesuai format!');
			return false;
		}
	}
	
	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
</script>	

<body>
 	
    <?= @$this->session->flashdata('message') ?>
    <?= @$this->session->flashdata('errors') ?>
    
	<form action="<?= base_url() ?>post_user/postRegister" method="post" onsubmit="return cek_register()">
	<h1>Register Baru</h1>
	<hr>
        <div class="form-group">
            <label for="uname">Username : </label>
            <input type="text" class="form-control" name="uname" id="uname" value="<?=set_value('uname')?>"/>
        </div>
        <div class="form-group">
            <label for="password">Password : </label>
            <input type="password" class="form-control" name="password" id="password"/>
        </div>
        <div class="form-group">
            <label for="password">Password Again: </label>
            <input type="password" class="form-control" name="password_again" id="password_again"/>
        </div>
        <div class="form-group">
            <label for="password">Hak Akses: </label>
            <select class="form-control" name="hak_akses" id="hak_akses">
            	<option value="">-- Pilih Hak Akses --</option>
                <?php foreach($access as $data): ?>
                	<option value="<?=$data->id_hak_akses?>"><?=$data->hak_akses?></option>
                <?php endforeach ?>
            </select>
        </div>
		<div class="form-group" id="input_propinsi">
            <label for="uname">Provinsi : </label>
            <select class="form-control chosen-select" name="no_kode_propinsi" id="provinsi">
				<?php foreach($js_propinsi as $data): ?>			
					<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
				<?php endforeach ?>
			</select>
        </div>
		<div class="form-group" id="input_kabupaten">
            <label for="uname">Kabupaten : </label>
            <select class="form-control chosen-select" name="nama_kabupaten" id="kabupaten">
				<?php $kab = $this->db->query("select * from tabel_kabupaten_kota where no_kode_propinsi = '11'")->result();
						foreach($kab as $data){
								if($data->id_urut_kabupaten==$userdata->code){
								?>			
							<option selected value="<?=$data->id_urut_kabupaten?>"><?=$data->nm_kabupaten?></option>
								<?php
								} else {
							?>			
							<option value="<?=$data->id_urut_kabupaten?>"><?=$data->nm_kabupaten?></option>
								<?php }} ?>
			</select>
        </div>
		<div class="form-group">
            <label for="email">Email: </label>
            <input type="text" class="form-control" name="email" id="email"/>
        </div>
        <input type="submit" name="register" class="btn btn-primary" value="Daftar"/>
    <?= form_close() ?>
</body>
</html>