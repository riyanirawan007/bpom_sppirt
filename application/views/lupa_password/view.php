<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Welcome :: Sistem Pelaporan Keamanan Pangan Online</title>
	
	<!-- css -->
	<link href="<?php echo base_url();?>assets/home/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/home/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/home/plugins/cubeportfolio/css/cubeportfolio.min.css">
	<link href="<?php echo base_url();?>assets/home/css/nivo-lightbox.css" rel="stylesheet" />
	<link href="<?php echo base_url();?>assets/home/css/nivo-lightbox-theme/default/default.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>assets/home/css/owl.carousel.css" rel="stylesheet" media="screen" />
	<link href="<?php echo base_url();?>assets/home/css/owl.theme.css" rel="stylesheet" media="screen" />
	<link href="<?php echo base_url();?>assets/home/css/animate.css" rel="stylesheet" />
	<link href="<?php echo base_url();?>assets/home/css/style.css" rel="stylesheet">

	<!-- boxed bg -->
	<link id="bodybg" href="<?php echo base_url();?>assets/home/bodybg/bg1.css" rel="stylesheet" type="text/css" />
	<!-- template skin -->
	<link id="t-colors" href="<?php echo base_url();?>assets/home/color/default.css" rel="stylesheet">
	<script>
		
		function cek_email(){
			if($('#email').val()==""){
				alert('Email belum diisi!');
				return false;
			} else if(validateEmail($('#email').val())==false){
				alert('Email tidak sesuai format!');
				return false;
			}
		}
		
		function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}
		
		
	</script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">

	<div id="wrapper">

		<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
			<div class="top-area">
				<div class="container">
					<div class="row">
						<div class="col-sm-6 col-md-4">
							<p class="bold text-left"><i class="fa fa-calendar-o"></i> 
								<?php 
								$tanggal = Date("Y-m-d");
								$tanggal1 = Date("d");
								$bln=date("m");
								$day = date('D', strtotime($tanggal));
								$dayList = array(
									'Sun' => 'Minggu',
									'Mon' => 'Senin',
									'Tue' => 'Selasa',
									'Wed' => 'Rabu',
									'Thu' => 'Kamis',
									'Fri' => 'Jumat',
									'Sat' => 'Sabtu'
								);
								echo $dayList[$day] .", ";
								echo $tanggal1;

								echo " " .bulan($bln);
								?>
								<?php $tanggal=getdate(); echo $tanggal['year']; ?> </p>
							</div>
							<div class="col-sm-6 col-md-8">
								<p class="bold text-right"><i class="fa fa-phone"></i> (+62) 21 42878701 / 42875738, <i class="fa fa-envelope-o"></i> subditp3d.pmpu@gmail.com </p>
							</div>
						</div>
					</div>
				</div>
				<div class="container navigation">

					<div class="navbar-header page-scroll">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
							<i class="fa fa-bars"></i>
						</button>
						<a class="navbar-brand" href="<?php echo base_url();?>http://www.pom.go.id/new">
							<img src="<?php echo base_url();?>assets/home/img/bpom.png" alt="" width="70" height="45" />
						</a>
						<div class="apps-title">
							Aplikasi Pelaporan Keamanan Pangan Online
						</div>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse navbar-right navbar-main-collapse">
						<ul class="nav navbar-nav">
							<li class="active"><a href="<?php echo base_url();?>#intro">Home</a></li>
							<li><a href="<?php echo base_url();?>#pelayanan">Pelayanan</a></li>
							<li><a href="<?php echo base_url();?>#tentangkami">Tentang Kami</a></li>
							<li><a href="<?php echo base_url();?>#kontak">Bantuan</a></li>

						</ul>
					</div>
					<!-- /.navbar-collapse -->
				</div>
				<!-- /.container -->
			</nav>


			<!-- Section: intro -->
			<section id="intro" class="intro">
				<div class="intro-content">
					<div class="container">
						<div class="row">
							<div class="col-lg-7">
								<div class="wow fadeInDown" data-wow-offset="0" data-wow-delay="0.1s">
									<h2 class="h-ultra">Selamat Datang</h2>
								</div>
								<div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.1s">
									<h5 class="h-light">Aplikasi Pelaporan Keamanan Pangan Online <span class="color">BPOM RI</span></h5>
								</div>
								<div class="well well-trans">
									<div class="wow fadeInRight" data-wow-delay="0.5s">

										<ul class="lead-list">
											<li>
												<span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Balai POM Provinsi</strong><br />Pengguna dengan hak akses tingkat provinsi</span>
											</li>
											<li>
												<span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Dinas Kesehatan</strong><br />Pengguna dengan hak akses tingkat dinas kesehatan</span>
											</li>
											<li>
												<span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>PTSP</strong><br />Pengguna dengan hak akses PTSP</span>
											</li>
										</ul>

									</div>
								</div>
							</div>
							<div class="col-lg-5">
								<div class="form-wrapper">
									<div class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.2s">

										<div class="panel panel-skin">
											<div class="panel-heading">
												<h3 class="panel-title"><span class="fa fa-unlock-alt"></span> Lupa Password <small>user</small></h3>
											</div>
											<div class="panel-body">
												<form action="<?= $action?>" method="post" onsubmit="return cek_email()">

													<div class="row">
														<div class="alert-info"><?= @$this->session->flashdata('message') ?></div>
														<div class="alert-danger" role="alert"><?= @$this->session->flashdata('errors') ?></div>
														<div class="col-xs-12 col-sm-12 col-md-12">
															<div class="form-group">
																<?php echo validation_errors(); ?>
																<label>Email</label>
																<br>
																<label id="message" style="color: blue"></label>
																<input type="text" name="email" id="email" placeholder="Email" class="form-control input-md">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-6 col-sm-6 col-md-6">
															<div class="form-group">
																<label>Captha</label> 
																<p id="captImg"><?php echo $captchaImg; ?></p>
																<a href="javascript:void(0);" class="refreshCaptcha" >&nbsp;&nbsp;<img src="<?php echo base_url().'assets//captha/images/refresh.png'; ?>"/></a>
																<input type="text" name="captcha" class="form-control input-md" style="max-width: 160px;">
															</div>
														</div>
													</div>
													<button class="btn btn-skin" type="submit" name="submit">Proses &raquo;</button>
													<a href="<?php echo site_url();?>" class="btn btn-warning">Kembali</a>


													<?= form_close() ?>
												</div>
											</div>				

										</div>
									</div>
								</div>					
							</div>		
						</div>
					</div>		
				</section>


			</div>
			<a href="<?php echo base_url();?>#" class="scrollup"><i class="fa fa-angle-double-up active"></i></a>

			<!-- Core JavaScript Files -->
			<script src="<?php echo base_url();?>assets/home/js/jquery.min.js"></script>	 
			<script src="<?php echo base_url();?>assets/home/js/bootstrap.min.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/jquery.easing.min.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/wow.min.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/jquery.scrollTo.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/jquery.appear.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/stellar.js"></script>
			<script src="<?php echo base_url();?>assets/home/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/owl.carousel.min.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/nivo-lightbox.min.js"></script>
			<script src="<?php echo base_url();?>assets/home/js/custom.js"></script>

			<!--Start of Tawk.to Script-->
			<script type="text/javascript">
				var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
				(
					function(){
						var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
						s1.async=true;
						s1.src='https://embed.tawk.to/5befb84c40105007f378688a/default';
						s1.charset='UTF-8';
						s1.setAttribute('crossorigin','*');
						s0.parentNode.insertBefore(s1,s0);
					}
					)
				();
			</script>
			<script type="text/javascript">
				$(document).ready(function() {
        /// make loader hidden in start
        $('#loading').hide(); 
        $('#email').blur(function(){
        	var email_val = $("#email").val();
        	var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        	if(filter.test(email_val)){
            // show loader
            $('#loading').show();
            $.post("<?php echo base_url()?>home/email_check", {
            	email: email_val
            }, function(response){
            	$('#loading').hide();
            	$('#message').html('').html(response.message).show().delay(4000).fadeOut();
            });
            return false;
        }
    });

    });   
</script>
<script>
	$(document).ready(function(){
		$('.refreshCaptcha').on('click', function(){
			$.get('<?php echo base_url().'home/refresh'; ?>', function(data){
				$('#captImg').html(data);
			});
		});
	});
</script>
<!--End of Tawk.to Script-->

</body>

</html>
