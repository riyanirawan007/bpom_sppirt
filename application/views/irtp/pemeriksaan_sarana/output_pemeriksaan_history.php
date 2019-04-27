
<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">History Pemeriksaan Sarana Produksi P-IRT</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">

				<h2>History Pemeriksaan Sarana Produksi P-IRT</h2>
				<nav class="navbar navbar-default" role="navigation">
					<div class="container-fluid">
						<form class="form-horizontal">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Nomor Permohonan</label>
								<div class="col-sm-10">
									<?= $header[0]['nomor_r_permohonan'] ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Nama IRTP</label>
								<div class="col-sm-10">
									<?= $header[0]['nama_perusahaan'] ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Nama Jenis Pangan</label>
								<div class="col-sm-10">
									<?= $header[0]['jenis_pangan'] ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Nama Produk Pangan</label>
								<div class="col-sm-10">
									<?= $header[0]['deskripsi_pangan'] ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Nama Dagang</label>
								<div class="col-sm-10">
									<?= $header[0]['nama_dagang'] ?>
								</div>
							</div>
						</form>
						<left><a onclick="window.history.back(-1)" class="btn btn-warning">Kembali</a></left>
					</div><!-- /.container-fluid -->
				</nav>
			</div>
			<br>

			<div class="row">
				<div class="col-lg-12" >
					<table class="table table-bordered" id="data_table">
						<thead>
							<tr>
								<th rowspan="3">No.</th>
								<th rowspan="3">Tanggal Pemeriksaan</th>
								<th colspan="8">Jumlah Ketidaksesuain</th>
								<th rowspan="3">Level IRTP</th>
								<th rowspan="3">Frekuensi Audit Internal</th>


							</tr>
							<tr>
								<td colspan="2">Minor</td>
								<td colspan="2">Mayor</td>
								<td colspan="2">Serius</td>
								<td colspan="2">Kritis</td>
							</tr>
							<tr>
								<td>Jumlah</td>
								<td>Nomor</td>
								<td>Jumlah</td>
								<td>Nomor</td>
								<td>Jumlah</td>
								<td>Nomor</td>
								<td>Jumlah</td>
								<td>Nomor</td>


							</tr>
						</thead>
						<tbody>
							<?php 
							$no = 1;
							foreach($datas as $row){

								?>	
								<tr>
									<td><?php echo $no; $no++?></td>
									<td><?= date("d F Y", strtotime($row->tanggal_pemeriksaan)) ?></td>


									<?php
									$get_minor = $this->db->query("select * from tabel_periksa_sarana_produksi_record_detail 
										where id_record = '".$row->id_record."'
										and level = 'Minor'
										")->result_array();
									$arr_sesuai = array();
									foreach($get_minor as $rget){
										$arr_sesuai[] = $rget['no_ketidaksesuaian'];
									}
									$no_ketidaksesuaian = implode(', ', $arr_sesuai);
									?>
									<td><?= count($get_minor) ?></td>
									<td><?= $no_ketidaksesuaian ?></td>

									<?php
									$get_mayor = $this->db->query("select * from tabel_periksa_sarana_produksi_record_detail 
										where id_record = '".$row->id_record."'
										and level = 'Mayor'
										")->result_array();
									$arr_sesuai = array();
									foreach($get_mayor as $rget){
										$arr_sesuai[] = $rget['no_ketidaksesuaian'];
									}
									$no_ketidaksesuaian = implode(', ', $arr_sesuai);
									?>
									<td><?= count($get_mayor) ?></td>
									<td><?= $no_ketidaksesuaian ?></td>

									<?php
									$get_serius = $this->db->query("select * from tabel_periksa_sarana_produksi_record_detail 
										where id_record = '".$row->id_record."'
										and level = 'Serius'
										")->result_array();
									$arr_sesuai = array();
									foreach($get_serius as $rget){
										$arr_sesuai[] = $rget['no_ketidaksesuaian'];
									}
									$no_ketidaksesuaian = implode(', ', $arr_sesuai);
									?>
									<td><?= count($get_serius) ?></td>
									<td><?= $no_ketidaksesuaian ?></td>

									<?php
									$get_kritis = $this->db->query("select * from tabel_periksa_sarana_produksi_record_detail 
										where id_record = '".$row->id_record."'
										and level = 'Kritis'
										")->result_array();
									$arr_sesuai = array();
									foreach($get_kritis as $rget){
										$arr_sesuai[] = $rget['no_ketidaksesuaian'];
									}
									$no_ketidaksesuaian = implode(', ', $arr_sesuai);
									?>
									<td><?= count($get_kritis) ?></td>
									<td><?= $no_ketidaksesuaian ?></td>

									<td><?= $row->level_irtp ?></td>
									<td><?= $row->frekuensi_audit ?></td>


								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>






			</form>
		</div>		
	</div>
</div>	