<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Rekap Pengajuan</li>
	</ul>

</div>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-3">
							<h3 class="smaller lighter blue">Tabel Rekap Pengajuan</h3>
						</div>
						<div class="col-xs-3" style="margin-top:15px;">
							<select id="fprov" class="form-control">
								<option value="">Seluruh Provinsi</option>
							</select>
						</div>
						<div class="col-xs-3" style="margin-top:15px;">
							<select id="fkab" class="form-control">
								<option value="">Seluruh Kabupaten</option>
							</select>
						</div>
						<dir class="col-xs-3" style="text-align: left; margin-top:15px;">
							<button id="fsend" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-filter bigger-120 blue"></i>
								Filter
							</button>					
							<button id="report" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-download bigger-120 blue"></i>
								Export Excel
							</button>
						</dir>
					</div>

					<div class="table-header">
						Hasil untuk "Data Rekap Pengajuan"
					</div>

					<div class="table-responsive">

						<table id="tbl_rkp_irtp_pengajuan" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Provinsi</th>
									<th>Jumlah Pengajuan SPP-IRT Per Provinsi</th>
									<th>Kabupaten</th>
									<th>Jumlah Pengajuan SPP-IRT Per Kabupaten</th>
								</tr>
							</thead>

							<tbody>
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$('document').ready(function(){
	var jum_prov=-1;
	//Per Prov
	var code='<?php echo $this->session->userdata('code_prov');?>';
	//Per Prov
	
	$('#tbl_rkp_irtp_pengajuan').dataTable({
		processing:true,
		initComplete: function(settings, json) {
				
		},
		ajax:{
			url:'<?php echo site_url();?>rekap_pengajuan/get',
			type:'GET',
			dataType:'json',
			data:function(e){
				$prov=$('#fprov').val();
				//Per Prov
				if(code!=''){
					$prov=code;
				}
				//Per Prov
				e.prov=$prov;
				e.kab=$('#fkab').val();
			},
			dataSrc:''
		},
		columns:[
		{
			data:'no'
		},
		{
			render: function (data, type, full, meta) {
				if(full.jumlah_per_prov!=jum_prov)	{
					return '<b>'+full.nama_propinsi+'</b>';
				}
				else{
					return full.nama_propinsi;
				}
			}
		},
		{
			render: function (data, type, full, meta) {
				if(full.jumlah_per_prov!=jum_prov)	{
					jum_prov=full.jumlah_per_prov;
					return '<b style="font-size:15px;">'+full.jumlah_per_prov+'</b>';
				}
				else{
					return '';
				}
			}
		},
		{
			render: function (data, type, full, meta) {
				if(full.jumlah_per_prov!=jum_prov)	{
					return '<b>'+full.nm_kabupaten+'</b>';
				}
				else{
					return full.nm_kabupaten;
				}
			}
		},
		{
			render: function (data, type, full, meta) {
				if(full.jumlah_per_prov!=jum_prov)	{
					return '<b>'+full.jumlah+'</b>';
				}
				else{
					return full.jumlah;
				}
			}
		}
		]
	});
	
	getProv();
	
	function getProv(){
		$.ajax({
			url:'<?php echo site_url();?>rekap_pengajuan/prov',
			type:'GET',
			dataType:'json',
			
			//Per Prov
			data:{
				id:code
			},
			//Per Prov
			
			success:function(response){
				var opt='<option value="">Seluruh Provinsi</option>';
				//Per Prov
				if(code!=''){
					opt='';
				}
				//Per Prov
				for(var i=0;i<response.length;i++)
				{
					opt+='<option value="'+response[i].no_kode_propinsi+'">'+response[i].nama_propinsi+'</option>';
				}
				$('#fprov').html(opt);
				
				//Per Prov
				if(code!=''){
					$('#fprov').val(code).trigger('change');
				}
				//Per Prov
				
			},
			error:function(err){
				alert(err);
			}
		});
	}
	
	function getKab(){
		$.ajax({
			url:'<?php echo site_url();?>rekap_pengajuan/kab',
			type:'GET',
			dataType:'json',
			data:{
				prov:$('#fprov').val()
			},
			success:function(response){
				var opt='<option value="">Seluruh Kabupaten</option>';
				for(var i=0;i<response.length;i++)
				{
					opt+='<option value="'+response[i].id_urut_kabupaten+'">'+response[i].nm_kabupaten+'</option>';
				}
				$('#fkab').html(opt);
			},
			error:function(err){
				alert(err);
			}
		});
	}
	
	$('#fprov').change(function(){
		getKab();
	});
	
	$('#fsend').click(function(){
		jum_prov=0;
		$('#tbl_rkp_irtp_pengajuan').DataTable().ajax.reload();
	});

	$('#report').click(function(){
		window.open(
			'<?php echo site_url();?>rekap_pengajuan/rekap' +
			'?prov=' + $('#fprov').val() +
			'&kab=' + $('#fkab').val()+
			'&prov_name=' + $('#fprov option:selected').text()+
			'&kab_name=' + $('#fkab option:selected').text()
		);
	});
});
</script>