<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Tambah Narasumber</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">

						<form action="<?= base_url() ?>narasumber/add" method="post" onsubmit="return cek_add()" enctype="multipart/form-data">
							<h1>Tambah Narasumber</h1>
							<div class="form-group">
								<label for="idb">IDB : </label>
								<input type="text" class="form-control" name="idb" id="idb">
							</div>
							<div class="form-group">
								<label for="tot">TOT : </label>
								<!-- <input type="text" class="form-control" name="tot" id="tot"> -->
								<select name="tot" class="form-control">
									<?php 
									$tot = array(0 => 'DFI BALAI', 1 => 'DFI PUSAT', 2 => 'DFI-D BALAI', 3 => 'DFI-L BALAI', 4 => 'PKP BALAI', 5 => 'PKP PUSAT');
									for($i=0; $i<count($tot); $i++){
										echo "<option value='$tot[$i]'>$tot[$i]</option>";
									} 
									?>
								</select>
							</div>

							<div class="form-group">
								<label for="status">Status</label>
								<?php 
								$status = array(0=>'DFI',1=>'PKP');
								for($x = 0; $x< count($status); $x++){
									echo '<input type="checkbox" name="status[]" value="'.$status[$x].'"> '.$status[$x].' &nbsp; ';
								}
								?>
							
							</div>
							<div class="form-group">
								<label for="nama_narasumber">Nama Narasumber : </label>
								<input type="text" class="form-control" name="nama_narasumber" id="nama_narasumber">
							</div>
							<div class="form-group">
								<label for="nip_pkp_dfi">NIP/PKP/DFI : </label>
								<input type="text" class="form-control" name="nip_pkp_dfi" id="nip_pkp_dfi">
							</div>
							<div class="form-group">
								<label for="tpt_tgl_lahir">Tempat tanggal lahir : </label>
								<input type="text" class="form-control" name="tpt_tgl_lahir" id="tpt_tgl_lahir" placeholder="Bogor, 15 Juni 1978">
							</div>
							<div class="form-group">
								<label for="tingkat_pendidikan">Tingkat Pendidikan :</label>
								<select name="tingkat_pendidikan" class="form-control">
									<?php 
									foreach ($tp as $tp) {
										echo "<option value='$tp->tingkat_pendidikan'>$tp->tingkat_pendidikan</option>";
									}?>
								</select>
							</div>
							<div class="form-group">
								<label for="nm_jabatan">Jabatan :</label>
								<select name="nm_jabatan" class="select2">
									<?php 
									foreach ($j as $j) {
										echo "<option value='$j->nm_jabatan'>$j->nm_jabatan</option>";
									}?>
								</select>
							</div>
							<div class="form-group">
								<label for="nm_golongan">Golongan :</label>
								<select name="nm_golongan" class="form-control">
									<?php 
									foreach ($g as $g) {
										echo "<option value='$g->nm_golongan'>$g->nm_golongan</option>";
									}?>
								</select>
							</div>
							<div class="form-group">
								<label for="nama_instansi">Instansi :</label>
								<select name="nama_instansi" class="select2">
									<?php 
									foreach ($i as $i) {
										echo "<option value='$i->kode_instansi'>$i->kode_instansi- $i->nama_instansi</option>";
									}?>
								</select>
							</div>
							<div class="form-group">
								<label for="idk">IDK : </label>
								<input type="text" class="form-control" name="idk" id="idk">
							</div>
							<div class="form-group">
								<label for="alamat_kantor">Alamat Kantor : </label>
								<textarea class="form-control" name="alamat_kantor" id="alamat_kantor"></textarea>
							</div>
							<div class="form-group">
								<label for="alamat_pribadi">Alamat Pribadi : </label>
								<textarea class="form-control" name="alamat_pribadi" id="alamat_pribadi"></textarea>
							</div>
							<div class="form-group">
								<label for="no_tlp_kantor">Telpon Kantor : </label>
								<input type="number" class="form-control" name="no_tlp_kantor" id="no_tlp_kantor">
							</div>
							<div class="form-group">
								<label for="no_fax_kantor">Fax Kantor : </label>
								<input type="number" class="form-control" name="no_fax_kantor" id="no_fax_kantor">
							</div>
							<div class="form-group">
								<label for="email_kantor">Email Kantor : </label>
								<input type="email" class="form-control" name="email_kantor" id="email_kantor">
							</div>
							<div class="form-group">
								<label for="no_tlp_pribadi">Telpon Pribadi : </label>
								<input type="number" class="form-control" name="no_tlp_pribadi" id="no_tlp_pribadi">
							</div>
							<div class="form-group">
								<label for="hp">Nomor HP : </label>
								<input type="number" class="form-control" name="hp" id="hp">
							</div>
							<div class="form-group">
								<label for="th">Tahun : </label>
								<input type="number" class="form-control" name="th" id="th">
							</div>
							<div class="form-group">
								<label for="kompetensi">Kompetensi : </label>
								<input type="text" class="form-control" name="kompetensi" id="kompetensi">
							</div>
							<div class="form-group">
								<label for="ket_lulus_tdk">Keterangan lulus tidak : </label>
								<input type="file" class="form-control" name="ket_lulus_tdk" id="ket_lulus_tdk">
							</div>
							<div class="form-group">
								<label for="file_sertifikat">Kepemilikan Sertifikat : </label>
								<input type="radio" name="sertifikat" value="Y"> Memiliki
								<input type="radio" name="sertifikat" value="N"> Tidak memiliki
								<input type="file" class="form-control" name="file_sertifikat" id="file_sertifikat">
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