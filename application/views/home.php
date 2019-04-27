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
	
			function getCookie(cname) {
				var name = cname + "=";
				var ca = document.cookie.split(';');
				for(var i=0; i<ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1);
					if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
				}
				return "";
			}
			if(getCookie('cookie_token') == ''){
				//alert('tes');
			}else{
				window.reload();
			}
		
	</script>
     <style type="text/css">
		#captImg{float:left;}
		.refreshCaptcha {
			position:relative;
			bottom: -12px;
    		float: right;
			}

		form{float:left;width:100%;}
	</style>
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
				<!--
				<li class="dropdown">
				  <a href="<?php echo base_url();?>#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge custom-badge red pull-right">Extra</span>More <b class="caret"></b></a>
				  <ul class="dropdown-menu">
					<li><a href="<?php echo base_url();?>index.html">Home form</a></li>
					<li><a href="<?php echo base_url();?>index-video.html">Home video</a></li>
					<li><a href="<?php echo base_url();?>index-cta.html">Home CTA</a></li>
					<li><a href="<?php echo base_url();?>https://bootstrapmade.com">Download</a></li>
				  </ul>
				</li> -->
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
										<span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Badan POM Pusat</strong><br />Pengguna dengan hak akses superadmin</span>
									</li>
									<li>
										<span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Balai POM Provinsi</strong><br />Pengguna dengan hak akses tingkat provinsi</span>
									</li>
									<li>
										<span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Dinas Kesehatan Tingkat 1</strong><br />Pengguna dengan hak akses tingkat dinas kesehatan provinsi</span>
									</li>
									<li>
										<span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Dinas Kesehatan Tingkat 2</strong><br />Pengguna dengan hak akses tingkat dinas kesehatan kabupaten/kota</span>
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
									<h3 class="panel-title"><span class="fa fa-sign-in"></span> Login <small>Area</small></h3>
									</div>
									<div class="panel-body">
									<form action="<?php echo base_url();?>post_user/postLogIn" method="POST">
									<div class="alert-danger" role="alert">
										<?= @$this->session->flashdata('errors') ?>
									</div>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
											    <div class="alert-info"><?= @$this->session->flashdata('message') ?></div>
												<div class="form-group">
													<label>Username</label>
													<input type="text"  id="username" name="uname" class="form-control input-md">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
												<div class="form-group">
													<label>Password</label>
													<input type="Password"  name="password" id="Password" class="form-control input-md">
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
											
											
										
										<input type="submit" value="Login" class="btn btn-skin btn-block btn-lg">
										
										<p class="lead-footer"><span class="fa fa-unlock-alt"></span><a href="<?php echo base_url();?>home/lupa_password"> Lupa Password</a></p>
									
									
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
	
	<!-- /Section: intro -->

	<!-- Section: boxes -->
    <section id="pelayanan" class="home-section paddingtop-80">
    	<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="wow fadeInDown" data-wow-delay="0.1s">
					<div class="section-heading text-center">
					<h2 class="h-bold">Pelayanan</h2>
					<p>Pelayanan yang ada di dalam sistem SPPIRT Online</p>
					</div>
					</div>
					<div class="divider-short"></div>
				</div>
			</div>
		</div>
	
		<div class="container">
			<div class="row">
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-book fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Permohonan SPPIRT</h4>
							<p>
							PTSP atau Dinas Kesehatan kabupaten kota dapat mengajukan permohonan PIRT.
							</p>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-users fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Penyuluhan</h4>
							<p>
							Dinas Kesehatan melakukan penyuluhan kepada PTSP.
							</p>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							<i class="fa fa-user-md fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Pemeriksaan</h4>
							<p>
							Dinas kesehatan melakukan pemeriksaan sesuai dengan ketentuan yang ada.
							</p>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-check fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Penerbitan</h4>
							<p>
							PIRT diterbitkan setelah semua persyaratan dipenuhi sesuai ketentuan.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-exchange fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Perpanjangan</h4>
							<p>
							PTSP maupun dinas kesehatan dapat mengajukan perpanjangan PIRT 6 bulan sebelum masa berlaku habis.
							</p>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-list-alt fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Perubahan Data</h4>
							<p>
							Tersedia juga fitur perubahan data yang dapat dipergunakan jika terdapat kekeliruan.
							</p>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							<i class="fa fa-calendar fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Pencabutan</h4>
							<p>
							Tersedia fitur pencabutan PIRT apabila ada temuan terhadap suatu produk tidak sesuai dengan ketentuan.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
	<!-- /Section: boxes -->
	
	
	<section id="bantuan" class="home-section paddingtop-40">	
           <div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="callaction bg-gray">
							<div class="row">
								<div class="col-md-7">
									<div class="wow fadeInUp" data-wow-delay="0.1s">
									<div class="cta-text">
									<h3>Butuh bantuan?</h3>
									<p>Hubungi kami melalui informasi kontak yang tersedia atau bisa langsung chat kami.</p>
									</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="wow lightSpeedIn" data-wow-delay="0.1s">
										<div class="cta-btn">
										<a href="<?php echo base_url();?>#kontak" class="btn btn-warning btn-lg"><i class="fa fa-info-circle"></i> Lihat Kontak</a>	
										</div>
									</div>
									<div class="wow lightSpeedIn" data-wow-delay="0.1s">
										<div class="cta-btn">
										<a href="<?php echo base_url();?>#" class="btn btn-skin btn-lg"><i class="fa fa-comment"></i> Chat Kami</a>	
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
	</section>	
	

	<!-- Section: help -->
    <!-- <section id="help" class="home-section nopadding paddingtop-50">

		<div class="container">

        <div class="row">
			<div class="col-sm-6 col-md-6">
				<div class="wow fadeInUp" data-wow-delay="0.2s">
				<img src="assets/home/img/dummy/needhelp.png" class="img-responsive" alt="" />
				</div>
            </div>
			<div class="col-sm-3 col-md-3">
				
				<div class="wow fadeInRight" data-wow-delay="0.1s">
                <div class="service-box">
					<div class="service-icon">
						<span class="fa fa-info-circle fa-3x"></span> 
					</div>
					<div class="service-desc">
						
							<a href="<?php echo base_url();?>#kontak" class="btn btn-warning btn-lg"><i class="fa fa-info-circle"></i> Lihat Kontak</a>	
					</div>
                </div>
				</div>
            </div>
			<div class="col-sm-3 col-md-3">
				
				<div class="wow fadeInRight" data-wow-delay="0.1s">
                <div class="service-box">
					<div class="service-icon">
						<span class="fa fa-comment-o fa-3x"></span> 
					</div>
					<div class="service-desc">
						
							<a href="<?php echo base_url();?>#" class="btn btn-skin btn-lg"><i class="fa fa-comment"></i> Lihat Kontak</a>	
					</div>
                </div>
				</div>
            </div>
			
        </div>		
		</div>
	</section> -->

	<!-- Section: tentang kami -->		
	<section id="tentangkami" class="home-section paddingbot-60 paddingtop-80">	
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
					<h2 class="h-bold">Tentang Kami</h2>
					</div>
					</div>
					<div class="divider-short"></div>
				</div>
			</div>
		</div>
		
           <div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-12">
						Salah satu tugas pokok dan fungsi Deputi Bidang Pengawasan Pangan Olahan adalah adalah melaksanakan pengawasan dan pembinaan di bidang keamanan pangan. Dalam rangka meningkatkan pembinaan dan pengawasan Industri Rumah Tangga Pangan (IRTP) dan tata hubungan kerja dengan dengan Pemerintah Daerah khususnya Dinas Kesehatan Kabupaten/ Kota di seluruh Indonesi, BPOM telah menerbitkan Peraturan Badan Pengawas obat dan Makanan RI Nomor 22 pada tahun 2018 tentang Pedoman Pemberian Sertifikat Produksi Pangan Industri Rumah Tangga. Pada peraturan tersebut dicantumkan bahwa Bupati/Walikota menyampaikan informasi secara periodik kepada Kepala Badan POM. Untuk mempermudah pelaporan tersebut maka telah dikembangkan aplikasi pelaporan SPP-IRT online.
					</div>
				</div>
            </div>
	</section>	
	<section id="kontak">
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<!-- <div class="wow fadeInDown" data-wow-delay="0.1s">
							<div class="widget">
								<h5><b>Tentang SPPIRT</b></h5>
								<p>
								Dalam rangka meningkatkan pembinaan dan pengawasan Industri Rumah Tangga Pangan (IRTP) dan tata hubungan kerja dengan dengan Pemerintah Daerah khususnya Dinas Kesehatan Kabupaten/ Kota di seluruh Indonesi, BPOM telah menerbitkan Peraturan Kepala Badan Pengawas obat dan Makanan RI No. HK.03.1.23.04.12.2205 pada tahun 2012 tentang Pedoman Pemberian Sertifikat Produksi Pangan Industri Rumah Tangga.
								</p>
							</div>
						</div> -->
						<div class="wow fadeInDown" data-wow-delay="0.1s">
							<div class="widget">
								<h5><b>Informasi Terkait</b></h5>
								<p>
									Silahkan klink link di bawah ini apabila ingin mengetahui informasi lain yang berkaitan dengan Sistem Pelaporan Keamanan Pangan Online. 
								</p>
								<ul>
									<li><span class="fa fa-chevron-circle-right"></span>
										<a href="<?php echo base_url();?>http://www.pom.go.id/new" target="_blank">Website BPOM RI</a>
									</li>
									<li><span class="fa fa-chevron-circle-right"></span>
										<a href="<?php echo base_url();?>http://jdih.pom.go.id/" target="_blank">Peraturan (JDIH BPOM RI)</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="wow fadeInDown" data-wow-delay="0.1s">
						<div class="widget">
							<h5><b>SPPIRT Center</b></h5>
							<p>
							Anda dapat menghubungi kami pada hari kerja (senin s/d jum'at) melalui beberapa kontak berikut ini.
							</p>
							<ul>
								<li>
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-phone fa-stack-1x fa-inverse"></i>
									</span> (+62) 21 42878701 / 42875738
								</li>
								<li>
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-envelope-o fa-stack-1x fa-inverse"></i>
									</span> <a href="<?php echo base_url();?>mailto:subditp3d.pmpu@gmail.com"> subditp3d.pmpu@gmail.com </a>
								</li>
							</ul>
						</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="wow fadeInDown" data-wow-delay="0.1s">
						<div class="widget">
							<h5><b>Alamat Kami</b></h5>
							<p>Direktorat Pemberdayaan Masyarakat dan Pelaku Usaha. Badan Pengawas Obat dan Makanan Republik Indonesia, 
							Jl. Percetakan Negara No. 23 Jakarta Pusat 10560</p>		
							
						</div>
						</div>
						<!--<div class="wow fadeInDown" data-wow-delay="0.1s">-->
						<!--<div class="widget">-->
						<!--	<h5><b>Terhubung Dengan Kami</b></h5>-->
						<!--	<ul class="company-social">-->
						<!--		<a href="<?php echo base_url();?>#">-->
						<!--			<img src="assets/home/img/chat.png" alt="" />-->
						<!--		</a>-->
						<!--	</ul>-->
						<!--</div>-->
						<!--</div>-->
					</div>
				</div>	
			</div>
			<div class="sub-footer">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="wow fadeInLeft" data-wow-delay="0.1s">
						<div class="text-left">
						<p>Copyright &copy; 2018 - Sistem Pelaporan Keamanan Pangan Online. All rights reserved.</p>
						</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="wow fadeInRight" data-wow-delay="0.1s">
						<div class="text-right">
							<p>Powered by <a href="<?php echo base_url();?>http://www.pom.go.id/new">BPOM Republik Indonesia</a></p>
						</div>
						</div>
					</div>
				</div>	
			</div>
			</div>
		</footer>
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
    <script>
		$(document).ready(function(){
			$('.refreshCaptcha').on('click', function(){
				$.get('<?php echo base_url().'home/refresh'; ?>', function(data){
					$('#captImg').html(data);
				});
			});
		});
	</script>
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
    <!--End of Tawk.to Script-->


</body>

</html>
