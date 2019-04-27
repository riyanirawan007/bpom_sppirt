<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Rekap IRTP</li>
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
							<h3 class="smaller lighter blue">Tabel Rekap Jumlah IRTP</h3>
						</div>
						<div class="col-xs-3" style="margin-top:15px;">
							<select id="fprov" class="form-control">
								<option value="">Seluruh Provinsi</option>
							</select>
						</div>
						<!-- <div class="col-xs-3" style="margin-top:15px;">
							<select id="fkab" class="form-control">
								<option value="">Seluruh Kabupaten</option>
							</select>
						</div> -->
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
						Hasil untuk "Data Rekap IRTP"
					</div>

					<div class="table-responsive">

						<table style="font-size:14px;" id="tbl_rkp_irtp" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Provinsi</th>
									<th>Jumlah IRTP Per Provinsi</th>
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
var getPerKab=function(prov_id){
        $.ajax({
            url:'<?php echo base_url();?>'+'rekap/getIRTP'
            ,type:'GET'
            ,dataType:'json'
            ,data:{prov:prov_id}
            ,success:function(res)
            {
                var title="Rekap IRTP "+res.data[0].nama_propinsi;
                title+='<br/><text style="font-size:14px;">Total : '+res.data[0].jumlah_per_prov+'</text>';

                var msg='';
                msg='<table id="tbl_detail" class="table table-hover">\
                <thead>\
					<th>No</th>\
					<th>Kabupaten/Kota</th>\
					<th>Jumlah IRTP Per Kabupaten</th>\
                </thead><tbody>';

                for(var i=0;i<res.count_data;i++)
                {
                    var item=res.data[i];
                    msg+='<tr>\
                    <td>'+(i+1)+'</td>\
                    <td>'+item.nm_kabupaten+'</td>\
                    <td>'+item.jumlah_per_kab+'</td>\
                    </tr>';
                }
                msg+='</tbody></table>';
                
                bootbox.alert({
                size:"large",
                title: title,
                message: msg, 
                callback: function(){ }
                })
                .on('shown.bs.modal',function() {
                    $(this).hide();
                    $('#tbl_detail').dataTable({
                        pageLength:10,
                        "lengthChange": false
                    });
                    $(this).animate({ scrollTop: 0 }, 'slow');
                    $(this).show();
                });
            },
            error:function(stat,res,err)
            {
                alert(err);
            }
        });
}

$('document').ready(function(){
	var no=0;
	var code='<?php echo $this->session->userdata('code');?>';
	//Per Prov
	var code='<?php echo $this->session->userdata('code_prov');?>';
	//Per Prov
	
	$('#tbl_rkp_irtp').dataTable({
		processing:true,
		initComplete: function(settings, json) {
				
		},
		ajax:{
			url:'<?php echo base_url();?>'+'rekap/getIRTP',
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
				// e.kab=$('#fkab').val();
				e.per_prov=true;
			},
			dataSrc:'data'
		},
		columns:[
		{
			render: function (data, type, full, meta) {
				return no+=1;
			}
		},
		{
			render: function (data, type, full, meta) {
				return '<span onclick="getPerKab('+full.no_kode_propinsi+')" style="cursor:pointer;" class="label label-info">\
				<i class="ace-icon fa fa-external-link bigger-120"></i>\
				'+full.nama_propinsi+'</span>';
			}
		},
		{
			render: function (data, type, full, meta) {
				return full.jumlah_per_prov;
			}
		}
		]
	});
	
	getProv();
	
	function getProv(){
		$.ajax({
			url:'<?php echo base_url();?>rekap/getProv',
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
				for(var i=0;i<response.count_data;i++)
				{
					opt+='<option value="'+response.data[i].no_kode_propinsi+'">'
					+response.data[i].nama_propinsi+'</option>';
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
			url:'<?php echo base_url();?>rekap/getKabKota',
			type:'GET',
			dataType:'json',
			data:{
				prov:$('#fprov').val()
			},
			success:function(response){
				var opt='<option value="">Seluruh Kabupaten</option>';
				for(var i=0;i<response.count_data;i++)
				{
					opt+='<option value="'+response.data[i].id_urut_kabupaten+'">'
					+response.data[i].nm_kabupaten+'</option>';
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
		no=0;
		$('#tbl_rkp_irtp').DataTable().ajax.reload();
	});

	$('#report').click(function(){
		window.open(
			'<?php echo base_url();?>rekap/export_irtp' +
			'?prov=' + $('#fprov').val() +
			'&prov_name=' + $('#fprov option:selected').text()	
		);
	});
});
</script>