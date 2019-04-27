<a href="<?php echo base_url() ?>narasumber/add" class="btn btn-primary">Tambah</a>
<br>
<br>
<table id="datatable" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Narasumber</th>
			<th>Jabatan</th>
			<th>Instansi</th>
			<th>Telp Kantor</th>
			<th>Alamat Kantor</th>
			<th>Alamat Pribadi</th>
			<!-- <th width="10%">Aksi</th> -->
		</tr>
	</thead>

	<tbody>
		
		<!-- <?php
		$no = 1;
		foreach ($narasumber as $n) {
			echo '
			<tr>
			<td>'.$no.'</td>
			<td>'.$n->nama_narasumber.'</td>
			<td>'.$n->nm_jabatan.'</td>
			<td>'.$n->nama_instansi.'</td>
			<td>'.$n->no_tlp_kantor.'</td>
			<td>'.$n->alamat_kantor.'</td>
			<td>'.$n->alamat_pribadi.'</td>

			<td>
			<div class="hidden-sm hidden-xs action-buttons">

			'.anchor('narasumber/edit/'.$n->kode_narasumber, '<i class="ace-icon fa fa-pencil bigger-130"></i>', array("class" => "green")).'

			'.anchor('narasumber/delete/'.$n->kode_narasumber, '<i class="ace-icon fa fa-trash-o bigger-130"></i>', array("class" => "red", "onclick" => "return confirm('Hapus data?')")).'
			</div>
			</td>
			</tr>
			';
			$no++;
		}
		?> -->
		
	</tbody>
</table>