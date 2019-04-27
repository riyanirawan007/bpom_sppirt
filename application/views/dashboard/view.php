<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try {
					ace.settings.check('breadcrumbs', 'fixed')
				} catch (e) {}
			</script>

			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#" class="active">Dashboard</a>
				</li>
			</ul><!-- /.breadcrumb -->

		</div>

		<div class="page-content">


			<div class="page-header">
				<h1>
					Dashboard
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						overview &amp; stats
					</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="alert alert-block alert-success">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>
						</button>

						<i class="ace-icon fa fa-check green"></i>

						Welcome to
						<strong class="green">
							Aplikasi Pelaporan Keamanan Pangan Online - BPOM RI
							<small>(version 1.0.0)</small>
						</strong>,
						Untuk mempermudah pelaporan keamanan pangan berbasis online maka telah dikembangkan aplikasi pelaporan SPP-IRT
						online.
					</div>

					<div class="row" style="margin-left:8px;margin-right:16px;">
						<div class="col-sm-12">
							<button id="refresh" class="btn btn-round btn-small btn-flat btn-info">
							<i class="fa fa-refresh"></i> Refresh All Widgets
							</button>
							<span class="pull-right" style="font-size:12px;">Last sync data : <b id="refresh-time"></b></span>
						</div>
					</div>
					<div class="row">
						<div class="space-6"></div>

						<div class="col-sm-5 infobox-container">
							<div class="infobox infobox-green infobox-dark">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-users"></i>
								</div>

								<div class="infobox-data">
									<span id="dash_stat_user" class="infobox-data-number">0</span>
									<div class="infobox-content">SPP-IRT Users</div>
								</div>
							</div>

							
							<div class="infobox infobox-red infobox-dark">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-home"></i>
								</div>

								<div class="infobox-data">
									<span id="dash_stat_irtp" class="infobox-data-number">0</span>
									<div class="infobox-content">Total IRTP</div>
								</div>
							</div>

							<div class="space-2"></div>

							<div class="infobox infobox-blue infobox-dark">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-edit"></i>
								</div>

								<div class="infobox-data">
									<span id="dash_stat_pengajuan" class="infobox-data-number">0</span>
									<div class="infobox-content">Pengajuan SPP-IRT</div>
								</div>
							</div>

							<div class="infobox infobox-pink infobox-dark">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-check-square"></i>
								</div>

								<div class="infobox-data">
									<span id="dash_stat_penerbitan" class="infobox-data-number">0</span>
									<div class="infobox-content">Penerbitan SPP-IRT</div>
								</div>
							</div>
							
							
							<div class="space-2"></div>

							<div class="infobox infobox-purple2 infobox-dark">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-bullhorn"></i>
								</div>

								<div class="infobox-data">
									<span id="dash_stat_pkp" class="infobox-data-number">0</span>
									<div class="infobox-content">Pelaksanaan PKP</div>
								</div>
							</div>

							<div class="infobox infobox-orange2 infobox-dark">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-user"></i>
								</div>

								<div class="infobox-data">
									<span id="dash_stat_peserta_pkp" class="infobox-data-number">0</span>
									<div class="infobox-content">Peserta PKP</div>
								</div>
							</div>

						</div>

						<div class="vspace-12-sm"></div>

						<div class="col-sm-7">
							<div id="stat_dash_ratio" style="width:100%; height: 400px; margin: 0 auto"></div>
						</div><!-- /.col -->
					</div>


					<!-- PAGE CONTENT ENDS -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script>
$(document).ready(function () {
	$('.easy-pie-chart.percentage').each(function () {
		var $box = $(this).closest('.infobox');
		var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
		var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
		var size = parseInt($(this).data('size')) || 50;
		$(this).easyPieChart({
			barColor: barColor,
			trackColor: trackColor,
			scaleColor: false,
			lineCap: 'butt',
			lineWidth: parseInt(size / 10),
			animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
			size: size
		});
	})

	$('.sparkline').each(function () {
		var $box = $(this).closest('.infobox');
		var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
		$(this).sparkline('html', {
			tagValuesAttribute: 'data-values',
			type: 'bar',
			barColor: barColor,
			chartRangeMin: $(this).data('min') || 0
		});
	});
	

	getData();
	function getData(){
		var ele=$('#dash_stat_user');
		ele.html(0);
		$.ajax({
			url:"<?php echo base_url('statistik/get_users');?>",
			type:'GET',
			async:false,
			dataType:'json',
			success:function(res)
			{
				ele.html(res.data);
			}
		});
		
		ele=$('#dash_stat_irtp');
		ele.html(0);
		$.ajax({
			url:"<?php echo base_url('rekap/getIRTP?per_prov=true');?>",
			type:'GET',
			async:false,
			dataType:'json',
			success:function(res)
			{
				var total=0;
				for(var i=0;i<res.count_data;i++)
				{
					total+=parseInt(res.data[i].jumlah_per_prov);
				}

				ele.html(total);
			}
		});
		
		ele=$('#dash_stat_pengajuan');
		ele.html(0);
		$.ajax({
			url:"<?php echo base_url('rekap/getPengajuan?per_prov=true');?>",
			type:'GET',
			async:false,
			dataType:'json',
			success:function(res)
			{
				var total=0;
				for(var i=0;i<res.count_data;i++)
				{
					total+=parseInt(res.data[i].jumlah_per_prov);
				}

				ele.html(total);
			}
		});

		ele=$('#dash_stat_penerbitan');
		ele.html(0);
		$.ajax({
			url:"<?php echo base_url('rekap/getPenerbitan?per_prov=true');?>",
			type:'GET',
			async:false,
			dataType:'json',
			success:function(res)
			{
				var total=0;
				for(var i=0;i<res.count_data;i++)
				{
					total+=parseInt(res.data[i].jumlah_per_prov);
				}

				ele.html(total);
			}
		});

		ele=$('#dash_stat_pkp');
		ele.html(0);
		$.ajax({
			url:"<?php echo base_url('rekap/getPelaksanaanPKP?per_prov=true');?>",
			type:'GET',
			async:false,
			dataType:'json',
			success:function(res)
			{
				var total=0;
				for(var i=0;i<res.count_data;i++)
				{
					total+=parseInt(res.data[i].jumlah_per_prov);
				}

				ele.html(total);
			}
		});

		ele=$('#dash_stat_peserta_pkp');
		ele.html(0);
		$.ajax({
			url:"<?php echo base_url('rekap/getPelaksanaanPKP?per_prov=true');?>",
			type:'GET',
			async:false,
			dataType:'json',
			success:function(res)
			{
				var total=0;
				for(var i=0;i<res.count_data;i++)
				{
					total+=parseInt(res.data[i].jumlah_peserta_per_prov);
				}

				ele.html(total);
			}
		});

		var pChart=Highcharts.chart('stat_dash_ratio', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Rasio Total Pengajuan & Penerbitan'
			},
			tooltip: {
				pointFormat: 'Total: <b>{point.y:.0f}	</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.1f}%',
						style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						}
					},
					showInLegend: true
				}
			},
			series: [{
				name: 'Brands',
				colorByPoint: true,
				data: []
			}]
		});

		var data=[
			{
				sliced: false,
				selected: true,
				name: 'Total Pengajuan SPP-IRT',
				y: parseInt($('#dash_stat_pengajuan').html())
			}, {
				name: 'Total Penerbitan SPP-IRT',
				y: parseInt($('#dash_stat_penerbitan').html())
			}
		];

		pChart.series[0].setData(data);
		pChart.redraw();

		$('#refresh-time').html(new Date());
		
	}

	$('#refresh').click(function(){
		getData();
	});
	
});
</script>