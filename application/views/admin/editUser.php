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
<link href="<?php echo base_url();?>css/chosen/chosen.min.css" rel="stylesheet">	
<script type="text/javascript" src="<?=base_url()?>js/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/chosen/prism.js"></script>
<script type="text/javascript">
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
$(document).ready(function() {
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
					temp += "<option value='" + key.id_urut_kabupaten + "'>" + key.nm_kabupaten + "</option>";
				});				
				//console.log($('#kabupaten'));
				
				//$('#kabupaten').css({'display' : 'block'}).removeClass('chzn-done');
				//$('#kabupaten').next().remove();
				
				$('#kabupaten').html(temp);
				$('#kabupaten').trigger('liszt:updated').chosen();
				
			},error: function(e){
				console.log(e);
			}
		});	
	});
	

	$('#hak_akses').change(function(){
		
		if($('#hak_akses').val()=='5'){
			$('#view_prov').show();
			$('#view_prov_1').hide();
		} else if($('#hak_akses').val()=='4' || $('#hak_akses').val()=='3') { 
			$('#view_prov_1').show();
			$('#view_prov').hide();
		} else {
			$('#view_prov').hide();
			$('#view_prov_1').hide();
		}
	});
});
</script>

</head>

<body>
	<h1>Ubah data User</h1>
	<hr>
    <?= @$this->session->flashdata('message') ?>
    <?= ($this->session->flashdata('errors')!="")?'<div class="alert alert-danger" role="alert">'.$this->session->flashdata('errors').'</div>':'' ?>
    <?= form_open('post_user/postEditUser') ?>
    <?php foreach($query as $userdata): ?>
    	<input type="hidden" value="<?=$userdata->id_user?>" name="id_user"/>
        <div class="form-group">
            <label for="uname">Username : </label>
            <input type="text" class="form-control" name="uname" value="<?=$userdata->username?>"/>
        </div>
        <div class="form-group">
            <label for="password">Password : </label>
            <input type="password" class="form-control" name="password"/>
        </div>
        <div class="form-group">
            <label for="password">Password Again: </label>
            <input type="password" class="form-control" name="password_again"/>
        </div>
        <div class="form-group">
            <label for="password">Hak Akses: </label>
            <select class="form-control" name="hak_akses" id="hak_akses">
            	<option value="<?=$userdata->id_hak_akses?>"><?=$userdata->hak_akses?></option>
                <?php foreach($access as $data): ?>
                	<option value="<?=$data->id_hak_akses?>"><?=$data->hak_akses?></option>
                <?php endforeach ?>
            </select>
        </div>
		<?php if($userdata->id_hak_akses==5){ ?>
		<script>$(document).ready(function() { $('#view_prov').show(); $('#view_prov_1').hide(); });</script>
		<div class="form-group row" id="view_prov">                
			<div class="col-xs-12">
				<label>Provinsi</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_propinsi" id="provinsi" data-validation="required">
						
						<?php 
						$prov = $this->db->query("select * from tabel_propinsi")->result();
						$aa = $this->db->query("select * from tabel_kabupaten_kota where id_urut_kabupaten = '".$userdata->code."'")->result_array();
						
						foreach($prov as $data){
							if($data->no_kode_propinsi==$aa[0]['no_kode_propinsi']){
							?>			
						<option selected value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php
							} else {
						?>			
						<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php }} ?>		
					</select>
				</div>
				<p class="help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
			</div>
			<div class="col-xs-12">
				<label>Kabupaten/Kota</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_kabupaten" id="kabupaten">
						<?php $kab = $this->db->query("select * from tabel_kabupaten_kota where no_kode_propinsi = '".$aa[0]['no_kode_propinsi']."'")->result();
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
				<p class="help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan.</p>
			</div><!-- col-xs-12 -->
		</div>
		<div class="form-group row" id="view_prov_1">                
			<div class="col-xs-12">
				<label>Provinsi</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_propinsi" id="provinsi" data-validation="required">
						
						<?php 
						$prov = $this->db->query("select * from tabel_propinsi")->result();
						
						foreach($prov as $data){
						$aa = $this->db->query("select * from tabel_kabupaten_kota where id_urut_kabupaten = '".$userdata->code."'")->result_array();
						
						
						
							if($data->no_kode_propinsi==$aa[0]['no_kode_propinsi']){
							?>			
						<option selected value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php
							} else {
						?>			
						<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php }} ?>
					</select>
				</div>
				<p class="help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
			</div>
			
		</div>
		<?php } else if($userdata->id_hak_akses==4 or $userdata->id_hak_akses==3){ ?>
		<script>$(document).ready(function() { $('#view_prov').hide(); $('#view_prov_1').show(); });</script>
		<div class="form-group row" id="view_prov">                
			<div class="col-xs-12">
				<label>Provinsi</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_propinsi" id="provinsi" data-validation="required">
						
						<?php 
						$prov = $this->db->query("select * from tabel_propinsi")->result();
						$aa = $this->db->query("select * from tabel_kabupaten_kota where id_urut_kabupaten = '".$userdata->code."'")->result_array();
						
						foreach($prov as $data){
						if($data->no_kode_propinsi==$userdata->code){
							?>			
						<option selected value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
						<?php } else {
						?>			
						<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
						<?php } }?>		
					</select>
				</div>
				<p class="help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
			</div>
			<div class="col-xs-12">
				<label>Kabupaten/Kota</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_kabupaten" id="kabupaten">
						<?php $kab = $this->db->query("select * from tabel_kabupaten_kota where no_kode_propinsi = '".$userdata->code."'")->result();
						foreach($kab as $data){
								
							?>			
							<option value="<?=$data->id_urut_kabupaten?>"><?=$data->nm_kabupaten?></option>
								<?php } ?>
					</select>
				</div>
				<p class="help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan.</p>
			</div><!-- col-xs-12 -->
		</div>
		<div class="form-group row" id="view_prov_1">                
			<div class="col-xs-12">
				<label>Provinsi</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_propinsi" id="provinsi" data-validation="required">
						
						<?php 
						$prov = $this->db->query("select * from tabel_propinsi")->result();
						
						
						foreach($prov as $data){
							if($data->no_kode_propinsi==$userdata->code){
							?>			
						<option selected value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php
							} else {
						?>			
						<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php }} ?>		
					</select>
				</div>
				<p class="help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
			</div> 
		</div> 
		<?php } else { ?>
		<script>$(document).ready(function() { $('#view_prov').hide(); $('#view_prov_1').hide(); });</script>
		<div class="form-group row" id="view_prov">                
			<div class="col-xs-12">
				<label>Provinsi</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_propinsi" id="provinsi" data-validation="required">
						
						<?php 
						$prov = $this->db->query("select * from tabel_propinsi")->result();
						$aa = $this->db->query("select * from tabel_kabupaten_kota where id_urut_kabupaten = '".$userdata->code."'")->result_array();
						
						foreach($prov as $data){
							
						?>			
						<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php } ?>		
					</select>
				</div>
				<p class="help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
			</div>
			<div class="col-xs-12">
				<label>Kabupaten/Kota</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_kabupaten" id="kabupaten">
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
				<p class="help-block">Pilih Kabupaten/Kota sesuai dengan list rujukan.</p>
			</div><!-- col-xs-12 -->
		</div>
		<div class="form-group row" id="view_prov_1">                
			<div class="col-xs-12">
				<label>Provinsi</label>
				<div class="dropdown">
					<select class="chosen-select col-sm-12" name="nama_propinsi" id="provinsi" data-validation="required">
						
						<?php 
						$prov = $this->db->query("select * from tabel_propinsi")->result();
						
						foreach($prov as $data){
							
						?>			
						<option value="<?=$data->no_kode_propinsi?>"><?=$data->nama_propinsi?></option>
							<?php } ?>		
					</select>
				</div>
				<p class="help-block">Pilih Provinsi sesuai dengan list rujukan.</p>
			</div>
			
		</div>
		<?php } ?>
		<div class="form-group">
            <label for="email">Email: </label>
            <input type="text" class="form-control" name="email" id="email" value="<?=$userdata->email?>"/>
        </div>
        <?php endforeach ?>
        <input type="submit" name="register" class="btn btn-primary" value="Simpan"/>
    <?= form_close() ?>
</body>
</html>