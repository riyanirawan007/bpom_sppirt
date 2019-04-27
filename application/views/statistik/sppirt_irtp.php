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
							<h3 class="smaller lighter blue">Statistik IRTP SPP-IRT</h3>
						</div>
						<div class="col-xs-3" style="margin-top:15px;text-margin:right;">
							<select id="fprov" class="form-control">
								<option value="">Seluruh Provinsi</option>
							</select>
						</div>
					</div>
					
					<div id="container" style="min-width: 310px; height: 1000px; margin: 0 auto"></div>

					
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
		height: (16 / 9 * 50) + '%'
    },
    title: {
        text: 'Statistik IRTP SPP-IRT'
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
        valueSuffix: ' buah'
    },
    plotOptions: {
		legend: {
			enabled: false
		},
        bar: {
            dataLabels: {
                enabled: true
            }
        },
		series: {
                     colorByPoint:true,
                 }
		
    },
    credits: {
        enabled: false
    },
    series: [{
		showInLegend: false,
        data: []
		}]
	
	});

	getProv();
	
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
				mChart.series[0].setData(values);
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
				$('#fkab').html(opt);
				mChart.xAxis[0].categories=cats;
				mChart.series[0].setData(values);
				mChart.redraw();
				
				get_stat_prov();
			},
			error:function(err){
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
	
	function get_stat_prov(){
		$.ajax({
			url:'<?php echo base_url();?>rekap/getIRTP',
			type:'GET',
			dataType:'json',
			data:{
				prov:$('#fprov').val(),
				per_prov:($('#fprov').val()!='')?null:true
			},
			success:function(res){
				
				var values=[];
				for(var i=0;i<res.count_data;i++)
				{
					if($('#fprov').val()!=''){
						values[i]=res.data[i].jumlah_per_kab!=null?parseInt(res.data[i].jumlah_per_kab):0;	
					}
					else{
						values[i]=res.data[i].jumlah_per_prov!=null?parseInt(res.data[i].jumlah_per_prov):0;
					}
				}
				mChart.series[0].setData(values);
				mChart.setTitle(null,{text:$('#fprov option:selected').text()});
				
				mChart.redraw();
			},
			error:function(data,err){
				alert(err);
			}
		});
	}


});
</script>