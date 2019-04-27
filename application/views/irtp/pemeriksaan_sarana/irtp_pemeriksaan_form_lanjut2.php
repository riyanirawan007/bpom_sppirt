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
	<div class="row form-bag" id="bag-2">
		<h2 class="heading-form">Pemeriksaan Sarana Produksi P-IRT (Bagian 2)</h2>
		<div class="well">
			<div class="row">
				<div class="col-sm-12">		
					<div class="form-group-cat">
						<div class="form-group row">
							<div class="col-xs-12">
								<label>Nomor Permohonan SPP-IRT</label>
								<input type="text" class="form-control nomor_permohonan" readonly="readonly" name="no_permohonan" placeholder="Pilih Nomor Sertifikat PKP terlebih dahulu" value="<?= $nomor_permohonan ?>" onkeypress="return isNumberKey(event)">
								<p class="help-block">Nomor Permohonan SPP-IRT akan terisi otomatis setelah memilih Nomor Sertifikat PKP pada Bagian 1, Contoh : ABS-2210.1213-2014</p>
							</div>
						</div><!-- form-group row -->

						<h4>Rincian Laporan Ketidaksesuaian</h4>
						<div class="row" style="margin-bottom : 13px;">
							<div class="col-sm-3">
								Jumlah Ketidaksesuaian :
							</div>
							<div class="col-sm-3">
								<input type="text" readonly class="form-control" id="no_record_ketidaksesuaian" name="no_record_ketidaksesuaian" value="<?= ($no_ketidaksesuaian!="")?count($no_ketidaksesuaian):"0" ?>" placeholder=""/>
                                <!--<select class="chosen-select col-sm-3 records-update" data-href="step-2">
                                <?php for($i = 1; $i <= 37; $i++): ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php endfor; ?>
                            </select>-->
                        </div>
                    </div>
                    <?php $attr_form = array('onsubmit' => 'return cek_form()'); ?>
                    <?=form_open('pemeriksaan_sarana/irtp_pemeriksaan_lanjut3', $attr_form)?>
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
                    		<?php
								//print_r($record); exit;
                    		if(count($record)>0){
                    			foreach($record as $rec){
                    				$udah[] = $rec['no_ketidaksesuaian'];
                    				if(@in_array($rec['no_ketidaksesuaian'],$no_ketidaksesuaian)){

                    					?>
                    					<tr>
                    						<input type="hidden" name="status_ketidaksesuaian[]" value="1">
                    						<input type="hidden" name="no_ketidaksesuaian[]" value="<?= $rec['no_ketidaksesuaian'] ?>">
                    						<input type="hidden" name="tindakan_sebelumnya[]" value="<?= $rec['tindakan'] ?>">
                    						<input type="hidden" name="status_sebelumnya[]" value="<?= $rec['status'] ?>">
                    						<td style="vertical-align:middle">
                    							<textarea class="form-control" readonly id="nama_ketidaksesuaian" name="nama_ketidaksesuaian[]"><?= $rec['nama_ketidaksesuaian'] ?></textarea>

                    						</td>
                    						<td style="vertical-align:middle">
                    							<textarea type="text" data-validation="required" class="form-control" id="plor" name="plor[]"><?= $rec['plor'] ?></textarea>

                    						</td>
                    						<td style="vertical-align:middle">
                    							<input type="text" readonly class="form-control" id="level" name="level[]" value="<?= $rec['level'] ?>"/>

                    						</td>
                    						<td style="vertical-align:middle" align="center"><input type="text" data-validation="required" class="form-control datetimepicker" name="batas_waktu_pemeriksaan[]" value="<?= $rec['tanggal_batas_waktu'] ?>"/></td>
                    					</tr>
                    					<?php 
                    				} else {
									//$gmaster_ketidaksesuaian = $this->db->query("select * from tabel_ketidaksesuaian where no_ketidaksesuaian = '".$no_ketidaksesuaian."'")->result_array();
                    					?>
                    					<tr style="background-color:#47A447">
                    						<input type="hidden" name="status_ketidaksesuaian[]" value="0">
                    						<input type="hidden" name="no_ketidaksesuaian[]" value="<?= $rec['no_ketidaksesuaian'] ?>">
                    						<input type="hidden" name="tindakan_sebelumnya[]" value="">
                    						<input type="hidden" name="status_sebelumnya[]" value="">

                    						<td style="vertical-align:middle">
                    							<textarea class="form-control" readonly id="nama_ketidaksesuaian" name="nama_ketidaksesuaian[]"><?= $rec['nama_ketidaksesuaian'] ?></textarea>

                    						</td>
                    						<td style="vertical-align:middle">
                    							<textarea class="form-control" readonly id="plor" name="plor[]" value=""><?= $rec['plor'] ?></textarea>

                    						</td>
                    						<td style="vertical-align:middle">
                    							<input type="text" readonly class="form-control" id="level" name="level[]" value="<?= $rec['level'] ?>"/>

                    						</td>
                    						<td style="vertical-align:middle" align="center"><input type="text" class="form-control" readonly name="batas_waktu_pemeriksaan[]" value="<?= $rec['tanggal_batas_waktu'] ?>" /></td>
                    					</tr>
                    					<?php
                    				}
                    			} 
                    		}
                    		?>

                    		<?php

                    		if($no_ketidaksesuaian!=""){
                    			foreach($no_ketidaksesuaian as $rec){
                    				if(!@in_array($rec,$udah)){

                    					$gmaster_ketidaksesuaian = $this->db->query("select * from tabel_ketidaksesuaian where no_ketidaksesuaian = '".$rec."'")->result_array();
                    					?>
                    					<tr>
                    						<input type="hidden" name="status_ketidaksesuaian[]" value="1">
                    						<input type="hidden" name="no_ketidaksesuaian[]" value="<?= $rec ?>">
                    						<input type="hidden" name="tindakan_sebelumnya[]" value="">
                    						<input type="hidden" name="status_sebelumnya[]" value="">
                    						<td style="vertical-align:middle">
                    							<textarea readonly class="form-control" id="nama_ketidaksesuaian" name="nama_ketidaksesuaian[]"><?= $gmaster_ketidaksesuaian[0]['nama_ketidaksesuaian'] ?></textarea>

                    						</td>
                    						<td style="vertical-align:middle">
                    							<textarea data-validation="required" class="form-control" id="plor" name="plor[]" value=""></textarea>

                    						</td>
                    						<td style="vertical-align:middle">
                    							<input type="text" readonly class="form-control" id="level" name="level[]" value="<?= $gmaster_ketidaksesuaian[0]['level'] ?>"/>

                    						</td>
                    						<td style="vertical-align:middle" align="center"><input type="text" data-validation="required" class="form-control datetimepicker" name="batas_waktu_pemeriksaan[]" value="" /></td>
                    					</tr>
                    					<?php 
                    				} 
                    			} 
                    		}
                    		?>
                    	</tbody>
                    </table>



                    <div class="row">
                    	<div class="col-lg-6 col-md-6 col-sm-12">
                    		<a onclick="window.history.go(-1)" class="btn btn-warning col-sm-12" >&lt; Sebelumnya</a> 
                    	</div>
                    	<div class="col-lg-6 col-md-6 col-sm-12">
                    		<input type="submit" class="btn btn-success col-sm-12" value="Selanjutnya &gt;">
                    	</div>
                    </div>
                </form>
            </div><!-- form-group-cat -->
        </div><!-- col-sm-12 -->
    </div><!-- row -->
</div><!-- well -->
</div><!-- row -->
</div>