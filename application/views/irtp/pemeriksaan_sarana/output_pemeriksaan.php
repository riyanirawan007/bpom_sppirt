<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data List Pemeriksaan Sarana</li>
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
							<h3 class="smaller lighter blue">Tabel Data List Pemeriksaan Sarana</h3>
						</div>
						<!-- <dir class="col-xs-6" style="text-align: right;">							
							<a href="<?php echo base_url().'role_menu/add';?>" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-edit bigger-120 blue"></i>
								Tambah Role Menu
							</a>
						</dir> -->
					</div>

					<div class="table-header">
						Hasil untuk "Data List Pemeriksaan Sarana"
					</div>

					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="data_table">
							<thead>
								<tr>
									<th rowspan="3">No.</th>
									<th rowspan="3">Nomor Permohonan</th>
									<th rowspan="3">Nama IRTP</th>
									<th rowspan="3">Alamat</th>
									<th rowspan="3">Nama Jenis Pangan</th>
									<th rowspan="3">Nama Produk Pangan</th>
									<th rowspan="3">Nama Dagang</th>
									<th rowspan="3">Jenis Kemasan</th>
									<th colspan="8">Jumlah Ketidaksesuain</th>
									<th rowspan="3">Level IRTP</th>
									<th rowspan="3">Frekuensi Audit Internal</th>
									<th rowspan="3">Tanggal Pemeriksaan</th>
									<th rowspan="3">Status Penyuluhan</th>
									<th rowspan="3">Jenis Form</th>
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
										<td><?= $row->nomor_r_permohonan ?></td>
										<td><?= $row->nama_perusahaan ?></td>
										<td><?= $row->alamat_irtp ?></td>
										<td><?= $row->jenis_pangan ?></td>
										<td><?= $row->deskripsi_pangan ?></td>
										<td><?= $row->nama_dagang ?></td>
										<td><?= ($row->kode_r_kemasan==6)?$row->jenis_kemasan_lain:$row->jenis_kemasan ?></td>

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
										<td><?= $row->frekuensi_audit ?></td>
										<td><?= date("d F Y", strtotime($row->tanggal_pemeriksaan)) ?></td>
										<td>
										<?php
										    if($row->penyuluhan == "Belum Penyuluhan") {
										        echo '<a href="#" data-toggle="tooltip" data-placement="left" title="'.$row->alasan_bp.'">'.$row->penyuluhan.'</a>';
										    } else {
										        echo $row->penyuluhan;
										    }
										?>
										
										
										</td>
										<td>
											<?php 
											if ($row->form == "Form Tidak Baku") {
											    echo '<a href="#" data-toggle="tooltip" data-placement="left" title="'.$row->alasan_tb.'">'.$row->form.'</a></br>';
											 	echo anchor('./dok_pemeriksaan/'.$row->dokumen, 'Unduh',array('class'=>'btn btn-success','title'=>'Unduh hasil pemeriksaan'));
											 } else {
											   echo $row->form;
											 } 
											 ?>
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


<div class="col-sm-6">
	<div class="row">
		<div class="col-xs-12">
			<div class="widget-box">
				<div class="widget-header widget-header-flat">
					<h4 class="widget-title smaller">Jumlah SPP-IRT yang Terdapat Ketidaksesuaian</h4>


				</div>

				<div class="widget-body">
					<div class="widget-main">

						<dl id="dt-list-1">

							<dd><ul class="list-unstyled spaced2">
								<li><i class="ace-icon fa fa-circle green"></i>Jumlah Ketidaksesuaian Minor : <?php echo $list_jumlah_irtp_kriteria['Minor']; ?></li>
								<li><i class="ace-icon fa fa-circle green"></i>Jumlah Ketidaksesuaian Mayor : <?php echo $list_jumlah_irtp_kriteria['Mayor']; ?></li>
								<li><i class="ace-icon fa fa-circle green"></i>Jumlah Ketidaksesuaian Serius : <?php echo $list_jumlah_irtp_kriteria['Serius']; ?></li>
								<li><i class="ace-icon fa fa-circle green"></i>Jumlah Ketidaksesuaian Serius : <?php echo $list_jumlah_irtp_kriteria['Serius']; ?></li>
								<li><i class="ace-icon fa fa-circle green"></i>
									Jumlah Ketidaksesuaian Kritis : <?php echo $list_jumlah_irtp_kriteria['Kritis']; ?></li>
								</ul></dd>

							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="row">
			<div class="col-xs-12">
				<div class="widget-box">
					<div class="widget-header widget-header-flat">
						<h4 class="widget-title smaller">Jumlah SPP-IRT Berdasarkan Level</h4>


					</div>

					<div class="widget-body">
						<div class="widget-main">

							<dl id="dt-list-1">

								<dd><ul class="list-unstyled spaced2">
									<li><i class="ace-icon fa fa-circle green"></i>Jumlah SPP-IRT Berdasarkan Level I : <?php echo $list_jumlah_irtp_level['Level I']; ?> </li>
									<li><i class="ace-icon fa fa-circle green"></i>Jumlah SPP-IRT Berdasarkan Level II : <?php echo $list_jumlah_irtp_level['Level II']; ?> </li>
									<li><i class="ace-icon fa fa-circle green"></i>Jumlah SPP-IRT Berdasarkan Level III : <?php echo $list_jumlah_irtp_level['Level III']; ?></li>
									<li><i class="ace-icon fa fa-circle green"></i>Jumlah SPP-IRT Berdasarkan Level IV : <?php echo $list_jumlah_irtp_level['Level IV']; ?></li>
								</ul></dd>

							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
