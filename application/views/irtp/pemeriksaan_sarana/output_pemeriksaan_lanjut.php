<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Pemeriksaan Sarana Produksi P-IRT (Lanjut)</li>
	</ul>

</div>

<div class="page-content">

<!-- <div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Data List Pelaksanaan PKP
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
							<h3 class="smaller lighter blue">Pemeriksaan Sarana Produksi P-IRT (Lanjut)</h3>
						</div>
						<!-- <dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'role_menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Role Menu
							</a>
						</dir> -->
					</div>

					<div class="table-header">
						Pemeriksaan Sarana Produksi P-IRT (Lanjut)
					</div>

					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="data_table">
							<thead>
								<tr>
									<th rowspan="3">No.</th>
									<th rowspan="3">Tanggal Pemeriksaan</th>
									<th rowspan="3">Nomor Permohonan</th>
									<th rowspan="3">Nama IRTP</th>
									<!-- <th rowspan="3">Alamat</th> -->
									<th rowspan="3">Nama Jenis Pangan</th>
									<th rowspan="3">Nama Produk Pangan</th>
									<th rowspan="3">Nama Dagang</th>
									<!-- <th rowspan="3">Jenis Kemasan</th> -->
									<th colspan="8">Jumlah Ketidaksesuain</th>
									<th rowspan="3">Level IRTP</th>
									<!-- <th rowspan="3">Frekuensi Audit Internal</th> -->
									
									<th rowspan="3">Pilihan</th>
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
										<td><?= $row->nomor_r_permohonan ?></td>
										<td><?= $row->nama_perusahaan ?></td>
										<!-- <td><?= $row->alamat_irtp ?></td> -->
										<td><?= $row->jenis_pangan ?></td>
										<td><?= $row->deskripsi_pangan ?></td>
										<td><?= $row->nama_dagang ?></td>
										<!-- <td><?= $row->jenis_kemasan ?></td> -->
										
										<?php
										$get_minor = $this->db->query("select * from tabel_periksa_sarana_produksi_detail 
											where id_r_urut_periksa_sarana_produksi = '".$row->id_urut_periksa_sarana_produksi."'
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
										$get_mayor = $this->db->query("select * from tabel_periksa_sarana_produksi_detail 
											where id_r_urut_periksa_sarana_produksi = '".$row->id_urut_periksa_sarana_produksi."'
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
										$get_serius = $this->db->query("select * from tabel_periksa_sarana_produksi_detail 
											where id_r_urut_periksa_sarana_produksi = '".$row->id_urut_periksa_sarana_produksi."'
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
										$get_kritis = $this->db->query("select * from tabel_periksa_sarana_produksi_detail 
											where id_r_urut_periksa_sarana_produksi = '".$row->id_urut_periksa_sarana_produksi."'
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
										<!-- <td><?= $row->frekuensi_audit ?></td> -->
										
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												<a href="<?= base_url() ?>pemeriksaan_sarana/output_pemeriksaan_history/<?= $row->id_urut_periksa_sarana_produksi ?>" class="green">
													<i class="ace-icon fa fa-history bigger-130 tooltip-success" data-rel="tooltip" data-placement="bottom" title="History Pemeriksaan"></i>
												History Pemeriksaan</a>
												<a href="<?= base_url() ?>pemeriksaan_sarana/form_pemeriksaan_lanjut/<?= $row->id_urut_periksa_sarana_produksi ?>" class="btn btn-primary">
													<i class="ace-icon fa fa-list-ol bigger-130 tooltip-info" data-rel="tooltip" data-placement="bottom" title="Pemeriksaan Lanjut"></i>
												Pemeriksaan Lanjut</a>
											</div>
											
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
						

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

