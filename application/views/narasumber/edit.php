<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Perbarui Narasumber</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<form action="<?= base_url() ?>narasumber/edit" method="post" enctype="multipart/form-data">
							<h1>Edit Narasumber</h1>
							<input type="hidden" name="kode_narasumber" value="<?php echo $narasumber->kode_narasumber ?>">
							<div class="form-group">
								<label for="idb">IDB : </label>
								<input type="text" class="form-control" name="idb" id="idb" value="<?=$narasumber->idb?>">
							</div>
							<div class="form-group">
								<label for="tot">TOT : </label>
								<!-- <input type="text" class="form-control" name="tot" id="tot" value="<?=$narasumber->tot?>"> -->
								<select name="tot" class="form-control">
									<?php 
									$tot = array(0 => 'DFI BALAI', 1 => 'DFI PUSAT', 2 => 'DFI-D BALAI', 3 => 'DFI-L BALAI', 4 => 'PKP BALAI', 5 => 'PKP PUSAT');
									$selected = "";

									for($i=0; $i<count($tot); $i++){
										if ($narasumber->tot == $tot[$i]) {
											$selected = "selected";
										}
										echo "<option value='$tot[$i]' $selected>$tot[$i]</option>";
									} 
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="status">Status</label>
								<?php 
								$status = $narasumber->status;
								$status = explode(",", $status);
								$total = count($status);
								$dfi = "";
								$pkp = "";
								// echo $total;
								
								for($x = 0; $x < $total; $x++) {
									if ($status[$x] == 'DFI') {
										$dfi = "checked";
									} else if($status[$x] == 'PKP') {
										$pkp = "checked";
									}
								}
								// $status = array(0=>'DFI',1=>'PKP');
								// for($x = 0; $x< count($status); $x++){
								// 	if ($narasumber->status == $status[$x]) {
								// 		$check = "checked";
								// 	}
								// 	echo '<input type="checkbox" name="status[]" value="'.$status[$x].'" '.$check.'> '.$status[$x].' &nbsp; ';
								// }
								?>
								
								<input type="checkbox" name="status[]" value="DFI" <?= $dfi; ?> > DFI&nbsp;
								<input type="checkbox" name="status[]" value="PKP" <?= $pkp; ?> > PKP&nbsp;
								</div>
								<div class="form-group">
									<label for="nama_narasumber">Nama Narasumber : </label>
									<input type="text" class="form-control" name="nama_narasumber" id="nama_narasumber" value="<?=$narasumber->nama_narasumber?>">
								</div>
								<div class="form-group">
									<label for="nip_pkp_dfi">NIP/PKP/DFI : </label>
									<input type="text" class="form-control" name="nip_pkp_dfi" id="nip_pkp_dfi" value="<?=$narasumber->nip_pkp_dfi?>">
								</div>
								<div class="form-group">
									<label for="tpt_tgl_lahir">Tempat tanggal lahir : </label>
									<input type="text" class="form-control" name="tpt_tgl_lahir" id="tpt_tgl_lahir" placeholder="Bogor, 15 Juni 1978" value="<?=$narasumber->tpt_tgl_lahir?>">
								</div>
								<div class="form-group">
									<label for="tingkat_pendidikan">Tingkat Pendidikan :</label>
									<select name="tingkat_pendidikan" class="form-control">
										<?php 
										foreach ($tp as $tp) {
											if ($tp->tingkat_pendidikan == $narasumber->tingkat_pendidikan) {
												$selected = "selected";
											} else {
												$selected = "";
											}
											echo "<option value='$tp->tingkat_pendidikan' $selected>$tp->tingkat_pendidikan</option>";
										}?>
									</select>
								</div>
								<div class="form-group">
									<label for="nm_jabatan">Jabatan :</label>
									<select name="nm_jabatan" class="select2">
										<?php 
										foreach ($j as $j) {
											if ($j->nm_jabatan == $narasumber->nm_jabatan) {
												$selected = "selected";
											} else {
												$selected = "";
											}
											echo "<option value='$j->nm_jabatan' $selected>$j->nm_jabatan</option>";
										}?>
									</select>
								</div>
								<div class="form-group">
									<label for="nm_golongan">Golongan :</label>
									<select name="nm_golongan" class="form-control">
										<?php 
										foreach ($g as $g) {
											if ($g->nm_golongan == $narasumber->nm_golongan) {
												$selected = "selected";
											} else {
												$selected = "";
											}
											echo "<option value='$g->nm_golongan' $selected>$g->nm_golongan</option>";
										}?>
									</select>
								</div>
								<div class="form-group">
									<label for="nama_instansi">Instansi :</label>
									<select name="nama_instansi" class="select2">
										<?php 
										foreach ($i as $i) {
											if ($i->kode_instansi == $narasumber->nama_instansi) {
												$selected = "selected";
											} else {
												$selected = "";
											}
											echo "<option value='$i->kode_instansi' $selected>$i->kode_instansi- $i->nama_instansi</option>";
										}?>
									</select>
								</div>
								<div class="form-group">
									<label for="idk">IDK : </label>
									<input type="text" class="form-control" name="idk" id="idk" value="<?=$narasumber->idk?>">
								</div>
								<div class="form-group">
									<label for="alamat_kantor">Alamat Kantor : </label>
									<textarea class="form-control" name="alamat_kantor" id="alamat_kantor"><?=$narasumber->alamat_kantor?></textarea>
								</div>
								<div class="form-group">
									<label for="alamat_pribadi">Alamat Pribadi : </label>
									<textarea class="form-control" name="alamat_pribadi" id="alamat_pribadi"><?=$narasumber->alamat_pribadi?></textarea>
								</div>
								<div class="form-group">
									<label for="no_tlp_kantor">Telpon Kantor : </label>
									<input type="number" class="form-control" name="no_tlp_kantor" id="no_tlp_kantor" value="<?=$narasumber->no_tlp_kantor?>">
								</div>
								<div class="form-group">
									<label for="no_fax_kantor">Fax Kantor : </label>
									<input type="number" class="form-control" name="no_fax_kantor" id="no_fax_kantor" value="<?=$narasumber->no_fax_kantor?>">
								</div>
								<div class="form-group">
									<label for="email_kantor">Email Kantor : </label>
									<input type="email" class="form-control" name="email_kantor" id="email_kantor" value="<?=$narasumber->email_kantor?>">
								</div>
								<div class="form-group">
									<label for="no_tlp_pribadi">Telpon Pribadi : </label>
									<input type="number" class="form-control" name="no_tlp_pribadi" id="no_tlp_pribadi" value="<?=$narasumber->no_tlp_pribadi?>">
								</div>
								<div class="form-group">
									<label for="hp">Nomor HP : </label>
									<input type="number" class="form-control" name="hp" id="hp" value="<?=$narasumber->hp?>">
								</div>
								<div class="form-group">
									<label for="th">Tahun : </label>
									<input type="number" class="form-control" name="th" id="th" value="<?=$narasumber->th?>">
								</div>
								<div class="form-group">
									<label for="kompetensi">Kompetensi : </label>
									<input type="text" class="form-control" name="kompetensi" id="kompetensi" value="<?=$narasumber->kompetensi?>">
								</div>
								<div class="form-group">
									<label for="ket_lulus_tdk">Keterangan lulus tidak : </label>
									<input type="file" class="form-control" name="ket_lulus_tdk" id="ket_lulus_tdk">
									<input type="hidden" class="form-control" name="old_ket_lulus_tdk" value="<?=$narasumber->ket_lulus_tdk?>">
								</div>
								<div class="form-group">
									<label for="file_sertifikat">Kepemilikan Sertifikat : </label>
									<?php 
									$checked_y = "";
									$checked_n = "";
									if ($narasumber->sertifikat == "Y") {
										$checked_y = "checked";
									} else {
										$checked_n = "checked";
									}

									?>
									<input type="radio" name="sertifikat" value="Y" <?=$checked_y?> > Memiliki
									<input type="radio" name="sertifikat" value="N"  <?=$checked_n?> > Tidak memiliki 
									<input type="file" class="form-control" name="file_sertifikat" id="file_sertifikat">
									<input type="hidden" class="form-control" name="old_file_sertifikat" value="<?=$narasumber->file_sertifikat?>">
								</div>
								<input type="submit" name="submit" class="btn btn-primary" value="Simpan"/>
								<a href="<?php echo base_url()?>narasumber" class="btn btn-warning"/>Batal</a>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>