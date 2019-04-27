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
							<h3 class="smaller lighter blue">Statistik Pelaksanaan PKP</h3>
						</div>
						<div class="col-xs-3" style="margin-top:15px;text-margin:right;">
							<select id="fprov" class="form-control">
								<option value="">Seluruh Provinsi</option>
							</select>
						</div>
						<div class="col-xs-3" style="margin-top:15px;text-margin:right;">
							<select id="ftahun" class="form-control">
								<option value="">Seluruh Tahun</option>
							</select>
						</div>
					</div>
					
					<div id="container" style="min-width: 310px; height: 100%; margin: 0 auto"></div>

					
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script>
$('document').ready(function(){
	var jum_prov=-1;
	var mChart;
	var cats=[];
	
	//Per Prov
	var code='<?php echo $this->session->userdata('code_prov');?>';
	//Per Prov
	
	mChart=Highcharts.chart('container', {
    chart: {
        type: 'bar',
		height: (16 / 9 * 100) + '%'
    },
    title: {
        text: 'Statistik Pelaksanaan PKP'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
		categories: cats,
        labels: {
                        enabled:true,
                        rotation: 0,
                        style: {
                            fontSize:'14px'
                        }
                    },
                    title: {
                        text: null
                    }
    },
    yAxis: {
        allowDecimals: false,
                    min: 0,
                    title: {
                        userHTML:true,
                        text: '<b style="font-size:16px;">Total</b>'
                    },
                    labels: {
                        overflow: 'justify'
                    }
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
		legend: {
			enabled: false
		},
        bar: {
            dataLabels: {
                enabled: true
            }
        }
		// ,series: {
        //              colorByPoint:false,
        //              pointPadding: 0,
        //              pointWidth:16,
        //              groupPadding: 0.05
        //          }
		
    },
    credits: {
        enabled: false
    },
    series: [{
		name:"Total Pelaksanaan",
		color:'#4572A7',
		showInLegend: true,
        data: []
		},
		{
		name:"Total Peserta",
		color:'#AA4643',
		showInLegend: true,
        data: []
		}]
	
	});

	getProv();
	getTahun();
	
	function getProv(){
		$.ajax({
			url:'<?php echo base_url("rekap/getProv");?>',
			type:'GET',
			dataType:'json',
			data:{
				id:code
			},
			success:function(response){
				var values=[];
				cats=[];
				var opt='<option value="">Seluruh Provinsi</option>';
				//Per Prov
				if(code!=''){
					opt='';
				}
				//Per Prov
				
				for(var i=0;i<response.count_data;i++)
				{
					values[i]=0;
					cats[i]=response.data[i].nama_propinsi;
					opt+='<option value="'+response.data[i].no_kode_propinsi+'">'+response.data[i].nama_propinsi+'</option>';
				}
				$('#fprov').html(opt);
				
				//Per Prov
				if(code!=''){
					$('#fprov').val(code).trigger('change');
				}
				//Per Prov
				
				
				mChart.xAxis[0].categories=cats;
				mChart.series[0].setData(null);
				mChart.series[1].setData(null);
				mChart.series[0].setData(values);
				mChart.series[1].setData(values);
				mChart.redraw();
				
				get_stat_prov();
				
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
				var values=[];
				cats=[];
				var opt='<option value="">Seluruh Kabupaten</option>';
				for(var i=0;i<response.count_data;i++)
				{
					values[i]=0;
					cats[i]=response.data[i].nm_kabupaten;
					opt+='<option value="'+response.data[i].id_urut_kabupaten+'">'+response.data[i].nm_kabupaten+'</option>';
				}

				mChart.xAxis[0].categories=cats;
				mChart.series[0].setData(null);
				mChart.series[1].setData(null);
				mChart.series[0].setData(values);
				mChart.series[1].setData(values);
				mChart.redraw();
				
				get_stat_prov();
			},
			error:function(err){
				alert(err);
			}
		});
	}

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
	
	$('#fprov').change(function(){
		jum_prov=0;
		if($('#fprov').val()!=''){
			getKab();
		}
		else{
			getProv();
		}
	});

	$('#ftahun').change(function(){
		$('#fprov').trigger('change');
	});
	
	function get_stat_prov(){
		$.ajax({
			url:'<?php echo base_url();?>rekap/getPelaksanaanPKP',
			type:'GET',
			dataType:'json',
			data:{
				prov:$('#fprov').val(),
				tahun:$('#ftahun').val(),
				per_prov:($('#fprov').val()!='')?null:true
			},
			success:function(res){
				
				var values=[];
				var values2=[];
				for(var i=0;i<res.count_data;i++)
				{
					if($('#fprov').val()!=''){
						values[i]=res.data[i].jumlah_per_kab!=null?parseInt(res.data[i].jumlah_per_kab):0;	
						values2[i]=res.data[i].jumlah_peserta_per_kab!=null?parseInt(res.data[i].jumlah_peserta_per_kab):0;
					}
					else{
						values[i]=res.data[i].jumlah_per_prov!=null?parseInt(res.data[i].jumlah_per_prov):0;	
						values2[i]=res.data[i].jumlah_peserta_per_prov!=null?parseInt(res.data[i].jumlah_peserta_per_prov):0;
					}
				}
				mChart.series[0].setData(values);
				mChart.series[1].setData(values2);
				mChart.redraw();
			},
			error:function(data,err){
				alert(err);
			}
		});
	}

});
</script>