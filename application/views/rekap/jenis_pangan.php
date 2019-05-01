<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="dashboard">Dashboard</a>
		</li>
		<li class="active">Data Rekap SPP-IRT Per Jenis Pangan</li>
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
							<h3 class="smaller lighter blue">Tabel Rekap SPP-IRT Per Jenis Pangan</h3>
						</div>
						<div class="col-xs-2" style="margin-top:15px;">
							<select id="fprov" class="form-control">
								<option value="">Seluruh Provinsi</option>
							</select>
						</div>
						<div class="col-xs-2" style="margin-top:15px;">
							<select id="fgrup" class="form-control">
							</select>
						</div>
						<div class="col-xs-2" style="margin-top:15px;">
							<select id="fjenis" class="form-control">
								<option value="">Seluruh Jenis Pangan</option>
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
						Hasil untuk "Data Rekap SPP-IRT Per Jenis Pangan"
					</div>

					<div class="table-responsive">

						<table style="font-size:14px;" id="tbl_rkp" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Provinsi</th>
									<th>Grup Jenis Pangan</th>
									<th>Jenis Pangan</th>
									<th>Jumlah</th>
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
            url:'<?php echo base_url();?>'+'rekap/getJenisPanganSPPIRT'
            ,type:'GET'
            ,dataType:'json'
            ,data:{prov:prov_id,grup:$('#fgrup').val(),jenis:$('#fjenis').val()}
            ,success:function(res)
            {
                var grup=$('#fgrup option:selected').text();
                var jenis=$('#fjenis option:selected').text();
                var title="SPP-IRT Per Jenis Pangan "+res.data[0].nama_propinsi;
                title+=' '+grup+' - '+jenis;
                title+='<br/><text style="font-size:14px;">Total : '+res.data[0].jumlah_per_prov+'</text>';

                var msg='';
                msg='<table id="tbl_detail" class="table table-hover">\
                <thead>\
					<th>No</th>\
					<th>Provinsi</th>\
					<th>Grup Jenis Pangan</th>\
					<th>Jenis Pangan</th>\
					<th>Jumlah</th>\
                </thead><tbody>';

				var grup=res.head.grup[0].nama_grup_jenis_pangan;
				var jenis=(res.head.jenis.length>0)?res.head.jenis[0].jenis_pangan:"Semua Jenis Pangan";
                for(var i=0;i<res.count_data;i++)
                {
                    var item=res.data[i];
                    msg+='<tr>\
                    <td>'+(i+1)+'</td>\
                    <td>'+item.nm_kabupaten+'</td>\
                    <td>'+grup+'</td>\
                    <td>'+jenis+'</td>\
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
	//Per Prov
	var code='<?php echo $this->session->userdata('code_prov');?>';
	//Per Prov
	
	getProv();
    getGrup();

    function getGrup(){
        $.ajax({
            url:'<?php echo base_url();?>'+'rekap/getGrupJenisPangan',
            type:'GET',
			async:false,
            dataType:'json',
            success:function(res)
            {
				var opt="";
                for(var i=0;i<res.count_data;i++)
                {
                    opt+='<option value="'+res.data[i].kode_grup_jenis_pangan+'">'+res.data[i].nama_grup_jenis_pangan+'</option>';
                }
                $('#fgrup').html(opt);
				getJenis();
            }
            ,error:function(stat,res,err)
            {
                alert(err);
            }
        });
    }

	function getJenis(){
        $.ajax({
            url:'<?php echo base_url();?>'+'rekap/getJenisPangan',
            type:'GET',
            dataType:'json',
			data:{grup:$('#fgrup').val()},
            success:function(res)
            {
				var opt='<option value="">Seluruh Jenis Pangan</option>';
                for(var i=0;i<res.count_data;i++)
                {
                    opt+='<option value="'+res.data[i].id_urut_jenis_pangan+'">'
					+res.data[i].jenis_pangan+'</option>';
                }
                $('#fjenis').html(opt);
            }
            ,error:function(stat,res,err)
            {
                alert(err);
            }
        });
    }


	$('#fgrup').change(function(){
		getJenis();
	});
	
	$('#tbl_rkp').dataTable({
		processing:true,
		initComplete: function(settings, json) {
				
		},
		ajax:
        {
			url:'<?php echo base_url();?>'+'rekap/getJenisPanganSPPIRT',
			type:'GET',
			dataType:'json',
			data:function(e)
            {
				$prov=$('#fprov').val();
                $grup=$('#fgrup').val();
                $jenis=$('#fjenis').val();
				//Per Prov
				if(code!=''){
					$prov=code;
				}
				//Per Prov
				e.prov=$prov;
                e.grup=$grup;
                e.jenis=$jenis;
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
		}
		,
		{
			render: function (data, type, full, meta) {
                return $('#fgrup option:selected').text();
			}
		}
		,
		{
			render: function (data, type, full, meta) {
                return $('#fjenis option:selected').text();
			}
		}
		,
		{
			render: function (data, type, full, meta) {
                return full.jumlah_per_prov;
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
    
	
	$('#fsend').click(function(){
		no_prov=-1;
		no=0;
		$('#tbl_rkp').DataTable().ajax.reload();
	});

	$('#report').click(function(){
        //alert('Sorry this feature is under construction');
		window.open(
			'<?php echo base_url();?>rekap/export_jenis_pangan' +
			'?prov=' + $('#fprov').val() +
			'&grup=' + $('#fgrup').val()+
			'&jenis=' + $('#fjenis').val()+
			'&grup_name=' + $('#fgrup option:selected').text()+
			'&jenis_name=' + $('#fjenis option:selected').text()+
			'&prov_name=' + $('#fprov option:selected').text()
		);
	});
});
</script>