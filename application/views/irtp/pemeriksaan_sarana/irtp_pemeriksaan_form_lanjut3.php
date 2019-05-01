<script>
	$(document).ready(function() {
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
	});
</script>
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
	<div class="row form-bag" id="bag-3">
		<?php $attr_form = array('onsubmit' => 'return cek_form()'); ?>
		<?=form_open('pemeriksaan_sarana/set_data_irtp_pemeriksaan_lanjut/'.$id_r_urut_periksa_sarana_produksi, $attr_form)?>
		<input type="hidden" name="id_r_urut_periksa_sarana_produksi" value="<?= $id_r_urut_periksa_sarana_produksi?>">
		<input type="hidden" name="level_irtp" value="<?= $level_irtp?>">
		<input type="hidden" name="freq_irtp" value="<?= $freq_irtp?>">
		<input type="hidden" name="nomor_permohonan" value="<?= $nomor_permohonan?>">
		<input type="hidden" name="tujuan_pemeriksaan" value="<?= $tujuan_pemeriksaan?>">
		<input type="hidden" name="tanggal_pemeriksaan" value="<?= $tanggal_pemeriksaan?>">
		<input type="hidden" name="nip_pengawas" value="<?= $nip_pengawas?>">
		<input type="hidden" name="nama_pengawas" value="<?= $nama_pengawas?>">
		<input type="hidden" name="jabatan_pengawas" value="<?= $jabatan_pengawas?>">
		<input type="hidden" name="nip_anggota_pengawas" value="<?= $nip_anggota_pengawas?>">
		<input type="hidden" name="nama_anggota_pengawas" value="<?= $nama_anggota_pengawas?>">
		<input type="hidden" name="nama_observer_pengawas" value="<?= $nama_observer_pengawas?>">

		<h2 class="heading-form">Pemeriksaan Sarana Produksi P-IRT (Bagian 3)</h2>
		<div class="well">
			<div class="row">
				<div class="col-sm-12">		
					<div class="form-group-cat">
						<div class="form-group row">
							<div class="col-xs-12">
								<label>Nomor Permohonan SPP-IRT</label>
								<input type="text" class="form-control nomor_permohonan" readonly="readonly" name="no_permohonan" placeholder="Pilih Nomor Sertifikat SPP-IRT terlebih dahulu" onkeypress="return isNumberKey(event)" value="<?= $nomor_permohonan?>">
								<p class="help-block">Nomor Permohonan SPP-IRT akan terisi otomatis setelah memilih Nomor Sertifikat PKP pada Bagian 1, Contoh : ABS-2210.1213-2014</p>
							</div>
						</div><!-- form-group row -->

						<b>Rincian Laporan Ketidaksesuaian</b>
						<div class="row" style="margin-bottom : 13px;">
							<div class="col-sm-3">
								Jumlah Ketidaksesuaian :
							</div>
							<div class="col-sm-9">
								<input type="text" id="step3-count-ketidaksesuaian" class="form-control" readonly value="<?= ($no_ketidaksesuaian!="")?count($no_ketidaksesuaian):"0" ?>">
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
                    	<tbody id="tbody-step3">
                    		<?php
                    		if($no_ketidaksesuaian!=""){
                    			foreach($no_ketidaksesuaian as $x => $nomor){
                    				if($status_ketidaksesuaian[$x]==1){

                    					?>
                    					<input type="hidden" name="status_ketidaksesuaian[]" value="1">
                    					<input type="hidden" name="no_ketidaksesuaian[]" value="<?= $nomor ?>">
                    					<input type="hidden" name="tanggal_batas_waktu[]" value="<?= $tanggal_batas_waktu[$x] ?>">
                    					<tr>
                    						<td style="vertical-align:top">
                    							<textarea readonly name="nama_ketidaksesuaian[]" class="form-control"><?= $nama_ketidaksesuaian[$x] ?></textarea>
                    						</td>
                    						<td>
                    							<textarea class="form-control" readonly name="plor[]"><?= $plor[$x] ?></textarea>
                    						</td>
                    						<td>
                    							<input type="text" name="level[]" readonly class="form-control" value="<?= $level[$x] ?>">
                    						</td>
                    						<td>
                    							<textarea name="tindakan_perbaikan[]" class="form-control" data-validation="required" placeholder="" ><?= $tindakan_sebelumnya[$x] ?></textarea>
                    						</td>
                    						<td style="vertical-align:top">
                    							<select class="form-control" name="status_verifikasi[]">
                    								<option value="1" <?= ($status_sebelumnya[$x]=="1"?"selected":"") ?>>Sesuai</option>
                    								<option value="0" <?= ($status_sebelumnya[$x]=="0"?"selected":"") ?>>Tidak Sesuai</option>
                    							</select>
                    						</td>
                    					</tr>
                    					<?php 
                    				} else {


                    					?>
                    					<input type="hidden" name="status_ketidaksesuaian[]" value="0">
                    					<input type="hidden" name="no_ketidaksesuaian[]" value="<?= $nomor ?>">
                    					<input type="hidden" name="tanggal_batas_waktu[]" value="<?= $tanggal_batas_waktu[$x] ?>">
                    					<tr style="background-color:#47A447">
                    						<td style="vertical-align:top">
                    							<textarea readonly name="nama_ketidaksesuaian[]" class="form-control"><?= $nama_ketidaksesuaian[$x] ?></textarea>

                    						</td>
                    						<td>
                    							<textarea class="form-control" readonly name="plor[]"><?= $plor[$x] ?></textarea>
                    						</td>
                    						<td>
                    							<input type="text" name="level[]" readonly class="form-control" value="<?= $level[$x] ?>">
                    						</td>
                    						<td>
                    							<textarea name="tindakan_perbaikan[]" class="form-control" data-validation="required"></textarea>
                    						</td>
                    						<td style="vertical-align:top">
                    							<select class="form-control" readonly name="status_verifikasi[]" onmousedown="return false">
                    								<option value="1" selected>Sesuai</option>
                    								<option value="0">Tidak Sesuai</option>
                    							</select>
                    						</td>
                    					</tr>
                    					<?php
                    				}
                    			} 
                    		}
                    		?>
                    	</tbody>
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
                    		<a onclick="window.history.go(-1)" class="btn btn-warning col-sm-12" >&lt; Sebelumnya</a>
                    	</div>
                    	<div class=" col-lg-6 col-sm-12 col-xs-12 col-md-12">
                    		<input type="submit" class="btn btn-primary col-sm-12" value="Kirim &raquo;">
                    	</div>
                    </div> <!-- row -->
                </div><!-- form-group-cat -->
            </div><!-- col-sm-7 -->
        </div><!-- row -->
    </div><!-- well -->
</form>
</div><!-- row -->
</div>