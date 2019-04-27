<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Rekap Pelaksanaan PKP</li>
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
							<h3 class="smaller lighter blue">Tabel Rekap Pelaksanaan PKP</h3>
						</div>
						<div class="col-xs-3" style="margin-top:15px;">
							<select id="fprov" class="form-control">
								<option value="">Seluruh Provinsi</option>
							</select>
						</div>
						<div class="col-xs-3" style="margin-top:15px;">
							<select id="ftahun" class="form-control">
								<option value="">Seluruh Tahun</option>
							</select>
						</div>
						<div class="col-xs-3" style="text-align: left; margin-top:15px;">
							<button id="fsend" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-filter bigger-120 blue"></i>
								Filter
							</button>					
							<button id="report" class="btn btn-white btn-info btn-bold">
								<i class="ace-icon fa fa-download bigger-120 blue"></i>
								Export Excel
							</button>
						</div>
					</div>

					<div class="table-header">
						Hasil untuk "Data Rekap Pelaksanaan PKP"
					</div>

					<div class="table-responsive">

						<table style="font-size:14px;" id="tbl_rkp_irtp_pkp" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Provinsi</th>
									<th>Jumlah Pelaksanaan PKP</th>
									<th>Jumlah Peserta</th>
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
            url:'<?php echo base_url();?>'+'rekap/getPelaksanaanPKP'
            ,type:'GET'
            ,dataType:'json'
            ,data:{prov:prov_id,tahun:$('#ftahun').val()}
            ,success:function(res)
            {
                var tahun=$('#ftahun option:selected').text();
                var title="Pelaksanaan PKP "+res.data[0].nama_propinsi;
                title+=(tahun!="Seluruh Tahun"?" Tahun ":" ")+tahun
                title+='<br/><text style="font-size:14px;">Total Pelaksanaan : '+res.data[0].jumlah_per_prov+', Total Peserta : '
                +res.data[0].jumlah_peserta_per_prov+'</text>';

                var msg='';
                msg='<table id="tbl_detail" class="table table-hover">\
                <thead>\
                    <th>No</th>\
                    <th>Kabupaten/Kota</th>\
                    <th>Jumlah Pelaksanaan PKP</th>\
                    <th>Jumlah Peserta</th>\
                </thead><tbody>';

                for(var i=0;i<res.count_data;i++)
                {
                    var item=res.data[i];
                    msg+='<tr>\
                    <td>'+(i+1)+'</td>\
                    <td>'+item.nm_kabupaten+'</td>\
                    <td>'+item.jumlah_per_kab+'</td>\
                    <td>'+item.jumlah_peserta_per_kab+'</td>\
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
	//Per Prov
	var code='<?php echo $this->session->userdata('code_prov');?>';
	//Per Prov
	
	getProv();
    getTahun();

    function getTahun(){
        $.ajax({
            url:'<?php echo base_url();?>'+'rekap/getTahunPKP',
            type:'GET',
            dataType:'json',
            success:function(res)
            {
                var opt='<option value="">Seluruh Tahun</option>';
                for(var i=0;i<res.count_data;i++)
                {
                    opt+='<option value="'+res.data[i].tahun+'">'+res.data[i].tahun+'</option>';
                }
                $('#ftahun').html(opt);
            }
            ,error:function(stat,res,err)
            {
                alert(err);
            }
        });
    }
	
	$('#tbl_rkp_irtp_pkp').dataTable({
		processing:true,
		initComplete: function(settings, json) {
				
		},
		ajax:
        {
			url:'<?php echo base_url();?>'+'rekap/getPelaksanaanPKP',
			type:'GET',
			dataType:'json',
			data:function(e)
            {
				$prov=$('#fprov').val();
                $tahun=$('#ftahun').val();
				//Per Prov
				if(code!=''){
					$prov=code;
				}
				//Per Prov
				e.prov=$prov;
                e.tahun=$tahun;
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
		},
		{
			render: function (data, type, full, meta) {
                return full.jumlah_peserta_per_prov;
			}
		}
		]
	});
	
	
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
    
	$('#fprov').change(function(){
		
	});
	
	$('#fsend').click(function(){
		no=0;
		$('#tbl_rkp_irtp_pkp').DataTable().ajax.reload();
	});

	$('#report').click(function(){
        //alert('Sorry this feature is under construction');
		window.open(
			'<?php echo base_url();?>rekap/export_pkp' +
			'?prov=' + $('#fprov').val() +
			'&tahun=' + $('#ftahun').val()+
			'&tahun_name=' + $('#ftahun option:selected').text()+
			'&prov_name=' + $('#fprov option:selected').text()
		);
	});
});
</script>