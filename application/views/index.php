<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>Aplikasi Pelaporan Keamanan Pangan Online</title>

	<meta name="description" content="overview &amp; stats" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/font-awesome/4.2.0/css/font-awesome.min.css" />

	<!-- page specific plugin styles -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/css/jquery.gritter.min.css" />

	<!-- text fonts -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/fonts/fonts.googleapis.com.css" />

	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/css/select2.min.css" />

	<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/css/datepicker.min.css" />


	<!-- ace styles -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
	<link href="<?php echo base_url();?>css/jquery.datetimepicker.css" rel="stylesheet">

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/ace-master/assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/html5shiv.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/respond.min.js"></script>
	<![endif]-->
	<!-- backup before -->
	<!-- <link rel="stylesheet/less" type="text/css" href="<?php echo base_url();?>/assets/less/index.less" /> -->
	<!-- <link rel="stylesheet" href="<?php echo base_url();?>css/responsiveslides.css"> -->

	<!-- Base url for javascript -->
	<script type="text/javascript"> var base_url='<?php echo base_url();?>';</script>

	<script>
		less = {
			env: "development",
			async: false,
			fileAsync: false,
			poll: 1000,
			functions: {},
			dumpLineNumbers: "comments",
			relativeUrls: false,
			rootpath: ":/a.com/"
		};			
	</script>
	<script src="<?php echo base_url();?>js/less-1.7.4.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>/js/jquery-2.1.1.min.js"></script>	

	<script src="<?php echo base_url();?>/js/form_validator/jquery.form-validator.min.js"></script>	
	<script>
		jQuery(function(){
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
			if(getCookie('cookie_token') != ''){

			}else{
				location.reload();
			}
		});

		function cek_form(){
			var r = confirm("Apakah Anda yakin?");
			if (r == true) {
				return true;
			} else {
				return false;
			}
		}
	</script>
	<!-- end backup before -->
</head>

<body class="no-skin">
	<div id="navbar" class="navbar navbar-default">
		<script type="text/javascript">
			try{ace.settings.check('navbar' , 'fixed')}catch(e){}
		</script>

		<div class="navbar-container" id="navbar-container">
			<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
				<span class="sr-only">Toggle sidebar</span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>
			</button>

			<div class="navbar-header pull-left">
				<a href="https://www.pom.go.id/" class="navbar-brand">
					<small style="color: black;">
						<img src="<?php echo base_url()?>assets/home/img/bpom.png" style="width: 45px; font-weight: bold; font-size: 20px;">
						Aplikasi Pelaporan Keamanan Pangan Online
					</small>
				</a>
			</div>

			<div class="navbar-buttons navbar-header pull-right" role="navigation">
				<ul class="nav ace-nav">

					<li class="green"  style="background-color: #2e8965;">
						<!-- <a data-toggle="dropdown" class="dropdown-toggle" href="#"> -->
							<?php 
							$id = $this->session->userdata('code');
							$masa_tenggat = $this->db->get('tabel_notifikasi_sertifikat')->row();
							$tenggat = $masa_tenggat->notifikasi_sertifikat;
							$tenggat = $tenggat * 30;
							$query = "SELECT a.nomor_r_permohonan, a.nomor_pirt, a.tanggal_pemberian_pirt, a.nomor_hk, a.nama_kepala_dinas, a.nama_kepala_dinas, d.id_urut_kabupaten, d.nm_kabupaten, e.no_kode_propinsi, e.nama_propinsi,
							DATEDIFF(DATE_ADD(a.tanggal_pemberian_pirt, INTERVAL 5 YEAR),CURDATE()) AS selisih,
							DATE_ADD(a.tanggal_pemberian_pirt, INTERVAL 5 YEAR) AS jatuh_tempo, 
							DATE_ADD(DATE_ADD(a.tanggal_pemberian_pirt, INTERVAL 5 YEAR) ,INTERVAL -6 month) AS hitung_mundur 
							FROM tabel_penerbitan_sert_pirt a
							LEFT JOIN tabel_pen_pengajuan_spp b ON a.nomor_r_permohonan = b.nomor_permohonan
							LEFT JOIN tabel_daftar_perusahaan c ON b.kode_r_perusahaan = c.kode_perusahaan
							LEFT JOIN tabel_kabupaten_kota d ON c.id_r_urut_kabupaten = d.id_urut_kabupaten
							LEFT JOIN tabel_propinsi e ON d.no_kode_propinsi = e.no_kode_propinsi
							HAVING selisih >= 0 AND selisih <= $tenggat";
							if ($this->session->userdata('user_segment')==3 or $this->session->userdata('user_segment')==4) {
								$query .= " AND e.no_kode_propinsi = $id";
							} else if ($this->session->userdata('user_segment') == 5) {
								$query .= " AND d.id_urut_kabupaten = $id";
							} 
							$x = $this->db->query($query);
							$total = $x->num_rows();
							
							?>
							<a href="<?= base_url('sertifikat_cek') ?>" title="Masa berlaku <?= $total ?> sertfikat akan habis">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
								<span class="badge badge-important"><?= $total ?></span>
							</a>

						<!-- <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
							<li class="dropdown-header">
								<i class="ace-icon fa fa-exclamation-triangle"></i>
								8 Notifications
							</li>

							<li class="dropdown-content">
								<ul class="dropdown-menu dropdown-navbar navbar-pink">
									<li>
										<a href="#">
											<div class="clearfix">
												<span class="pull-left">
													<i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
													New Comments
												</span>
												<span class="pull-right badge badge-info">+12</span>
											</div>
										</a>
									</li>

									<li>
										<a href="#">
											<i class="btn btn-xs btn-primary fa fa-user"></i>
											Bob just signed up as an editor ...
										</a>
									</li>

									<li>
										<a href="#">
											<div class="clearfix">
												<span class="pull-left">
													<i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
													New Orders
												</span>
												<span class="pull-right badge badge-success">+8</span>
											</div>
										</a>
									</li>

									<li>
										<a href="#">
											<div class="clearfix">
												<span class="pull-left">
													<i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>
													Followers
												</span>
												<span class="pull-right badge badge-info">+11</span>
											</div>
										</a>
									</li>
								</ul>
							</li>

							<li class="dropdown-footer">
								<a href="#">
									See all notifications
									<i class="ace-icon fa fa-arrow-right"></i>
								</a>
							</li>
						</ul> -->
					</li>

					<li class="light-blue" style="background-color: #62a8d1;">
						<a data-toggle="dropdown" href="#" class="dropdown-toggle">
							<?php if ($this->session->userdata('picture') == ""): ?>
								<img class="nav-user-photo" src="<?php echo base_url();?>assets/dashboard/ace-master/assets/avatars/avatar2.png" alt="Default Photo" />
								<?php else: ?>
									<img class="nav-user-photo" src="<?php echo base_url('foto/'.$this->session->userdata('picture'));?>" />
								<?php endif ?>

								<span class="user-info">
									<small>Welcome,</small>
									<?php echo $this->session->userdata('user_name');?> 
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
							<!-- <li>
								<a href="#">
									<i class="ace-icon fa fa-cog"></i>
									Settings
								</a>
							</li> -->

							<li>
								<a href="<?php echo site_url('manajemen_user/edit/'.$this->session->userdata('user_id'))?>">
									<i class="ace-icon fa fa-user"></i>
									Profile
								</a>
							</li>

							<li class="divider"></li>

							<li>
								<a href="<?php echo base_url();?>home/logOut">
									<i class="ace-icon fa fa-power-off"></i>
									Logout
								</a>
							</li>
						</ul>
					</li>


						<!-- <li class="green">
							<a class="dropdown-toggle" href="<?php //echo base_url();?>home/logOut" style="background-color: #ffffff;">
								
								<span class="badge badge-danger"><i class="glyphicon glyphicon-off"></i> logout</span>
							</a>
						</li>
					-->


						<!-- <li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo base_url();?>assets/dashboard/ace-master/assets/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
									Jason
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>

								<li>
									<a href="profile.html">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="#">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li> -->


					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-purple">
							B
						</button>

						<button class="btn btn-success">
							P
						</button>

						<button class="btn btn-success">
							O
						</button>

						<button class="btn btn-success">
							M
						</button>
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-purple"></span>

						<span class="btn btn-purple"></span>

						<span class="btn btn-success"></span>

						<span class="btn btn-success"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->


				<!-- Start Dynamic Menus -->
				<ul class="nav nav-list">
					<?php
					$role_user=$this->session->userdata('user_segment');
					$menu_level1=load_menu_by_criteria($role_user,'Level 1');
					$menu_place=array('Admin'=>'Administrator Page','Home'=>'Landing Page');

					foreach($menu_level1 as $menu_lvl1)
					{
						if($menu_lvl1['place']==$menu_place['Admin'])
						{
						// Render menu level 1
							$menu_level2=load_menu_by_criteria($role_user,'Level 2',$menu_lvl1['id_menu']);
							$current_url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

							$active_stat='';
							$link='';
							$icon='';
							$class_dropdown='';
							$arrow_dropdown='';

							if(count($menu_level2)>0)
							{						
								foreach($menu_level2 as $menu_lvl2)
								{
								//$menu_context_url=str_replace('http://','',base_url().$menu_lvl2['link']);
								//$menu_context_url=str_replace('https://','',$menu_context_url);
								// if(strpos($current_url,$menu_context_url)!==false)
								// {
								// 	$active_stat='class="active"';
								// 	break;
								// }
									$menu_context_url=str_replace('http://','',base_url().$menu_lvl2['link']);
									$menu_context_url=str_replace('https://','',$menu_context_url);
									if($current_url==$menu_context_url)
									{
										$active_stat='class="active open highlight"';
										break;
									}

									$menu_level3=load_menu_by_criteria($role_user,'Level 3',$menu_lvl2['id_menu']);
									if(count($menu_level3)>0)
									{
										foreach($menu_level3 as $menu_lvl3)
										{
										// $menu_context_url=str_replace('http://','',base_url().$menu_lvl3['link']);
										// $menu_context_url=str_replace('https://','',$menu_context_url);
										// if(strpos($current_url,$menu_context_url)!==false)
										// {
										// 	$active_stat='class="active"';
										// 	break;
										// }
											$menu_context_url=str_replace('http://','',base_url().$menu_lvl3['link']);
											$menu_context_url=str_replace('https://','',$menu_context_url);
											if($current_url==$menu_context_url)
											{
												$active_stat='class="active open highlight"';
												break;
											}
										}
									}
								}	
							}
							else
							{

							// $menu_context_url=str_replace('http://','',base_url().$menu_lvl1['link']);
							// $menu_context_url=str_replace('https://','',$menu_context_url);
							// if(strpos($current_url,$menu_context_url)!==false)
							// {
							// 	$active_stat='class="active"';
							// }
							// 
								$menu_context_url=str_replace('http://','',base_url().$menu_lvl1['link']);
								$menu_context_url=str_replace('https://','',$menu_context_url);
								if($current_url==$menu_context_url)
								{
									$active_stat='class="active open highlight"';
								}

							}


							if($menu_lvl1['link_type']=='Internal')
							{
								$link=base_url().$menu_lvl1['link'];	
							}
							else
							{
								$link=$menu_lvl1['link'];
							}
							if($menu_lvl1['fa_icon']!=null)
							{
								$icon=$menu_lvl1['fa_icon'];	
							}
							if(count($menu_level2)>0)
							{
								$class_dropdown='class="dropdown-toggle"';
								$arrow_dropdown='<b class="arrow fa fa-angle-down"></b>';
							}

							echo '
							<li '.$active_stat.'>
							<a href="'.$link.'" '.$class_dropdown.'>
							<i class="menu-icon fa '.$icon.'"></i>
							<span class="menu-text">
							'.$menu_lvl1['title'].'
							</span>
							'.$arrow_dropdown.'
							</a>
							<b class="arrow"></b>';

						// Render menu level 2
							if(count($menu_level2)>0)
							{
								echo'<ul class="submenu">';	
								foreach($menu_level2 as $menu_lvl2)
								{
									if($menu_lvl2['place']==$menu_place['Admin'])
									{
										$menu_level3=load_menu_by_criteria($role_user,'Level 3',$menu_lvl2['id_menu']);

										$active_stat='';
										$link='';
										$icon='';
										$class_dropdown='';
										$arrow_dropdown='';

										if(count($menu_level3)>0)
										{
											foreach($menu_level3 as $menu_lvl3)
											{
											//$menu_context_url=str_replace('http://','',base_url().$menu_lvl3['link']);
											//$menu_context_url=str_replace('https://','',$menu_context_url);
											// if(strpos($current_url,$menu_context_url)!==false)
											// {
											// 	$active_stat='class="active"';
											// 	break;
											// }
												$menu_context_url=str_replace('http://','',base_url().$menu_lvl3['link']);
												$menu_context_url=str_replace('https://','',$menu_context_url);
												if($current_url==$menu_context_url)
												{
													$active_stat='class="active open highlight"';
													break;
												}
											}	
										}
										else
										{

										// $menu_context_url=str_replace('http://','',base_url().$menu_lvl2['link']);
										// $menu_context_url=str_replace('https://','',$menu_context_url);
										// if(strpos($current_url,$menu_context_url)!==false)
										// {
										// 	$active_stat='class="active"';
										// }
											$menu_context_url=str_replace('http://','',base_url().$menu_lvl2['link']);
											$menu_context_url=str_replace('https://','',$menu_context_url);
											if($current_url==$menu_context_url)
											{
												$active_stat='class="active open highlight"';
											}
										}

										if($menu_lvl2['link_type']=='Internal')
										{
											$link=base_url().$menu_lvl2['link'];	
										}
										else
										{
											$link=$menu_lvl2['link'];
										}
										if($menu_lvl2['fa_icon']!=null)
										{
											$icon=$menu_lvl2['fa_icon'];	
										}
										if(count($menu_level3)>0)
										{
											$class_dropdown='class="dropdown-toggle"';
											$arrow_dropdown='<b class="arrow fa fa-angle-down"></b>';
										}

										echo '
										<li '.$active_stat.'>
										<a href="'.$link.'" '.$class_dropdown.'>
										<i class="menu-icon fa fa-caret-right"></i>
										'.$menu_lvl2['title'].'
										'.$arrow_dropdown.'
										</a>
										<b class="arrow"></b>';
									}


									if(count($menu_level3)>0)
									{
										echo '<ul class="submenu">';
										foreach($menu_level3 as $menu_lvl3)
										{
											if($menu_lvl3['place']==$menu_place['Admin'])
											{
												$menu_context_url=str_replace('http://','',base_url().$menu_lvl3['link']);
												$menu_context_url=str_replace('https://','',$menu_context_url);

												$active_stat='';
												$link='';
												$icon='';
												$class_dropdown='';
												$arrow_dropdown='';

											// if(strpos($current_url,$menu_context_url)!==false)
											// {
											// 	$active_stat='class="active"';
											// }
												if($current_url==$menu_context_url)
												{
													$active_stat='class="active open highlight"';
												}

												if($menu_lvl3['link_type']=='Internal')
												{
													$link=base_url().$menu_lvl3['link'];	
												}
												else
												{
													$link=$menu_lvl3['link'];
												}
												if($menu_lvl2['fa_icon']!=null)
												{
													$icon=$menu_lvl3['fa_icon'];	
												}

												echo '
												<li '.$active_stat.'>
												<a href="'.$link.'">
												<i class="menu-icon fa fa-caret-right"></i>
												'.$menu_lvl3['title'].'
												</a>
												<b class="arrow"></b>
												</li>';
											}

										}
										echo '</ul>';
									}

									echo'</li>';
								}
								echo'</ul>';
							}

							echo '</li>';

						}
					}



					?>
				</ul>
				<!-- End Dynamic Menus -->



				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<div class="main-content">

				<?php
				echo $_content;
				?>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Aplikasi Pelaporan Keamanan Pangan Online</span> - BPOM RI
							&copy; <?php $tanggal=getdate(); echo $tanggal['year']; ?> 
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<!-- <script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.2.1.1.min.js"></script> -->

		<!-- <![endif]-->

		<!--[if IE]>
<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.1.11.1.min.js"></script>
<![endif]-->

<!--[if !IE]> -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/excanvas.min.js"></script>
		<![endif]-->
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery-ui.custom.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.ui.touch-punch.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.easypiechart.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.sparkline.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.flot.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.flot.pie.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.flot.resize.min.js"></script>

		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/fuelux.wizard.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/bootstrap-datepicker.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.validate.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/additional-methods.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/bootbox.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/jquery.maskedinput.min.js"></script>
		<script src="<?php echo base_url();?>js/jquery.redirect.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/select2.min.js"></script>
		
		
		<!-- Data Table -->

		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url()?>assets/dashboard/ace-master/assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url()?>assets/dashboard/ace-master/assets/js/jquery.dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url()?>assets/dashboard/ace-master/assets/js/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url()?>assets/dashboard/ace-master/assets/js/dataTables.colVis.min.js"></script>

		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/bootstrap-editable.min.js"></script>
		<!--<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/ace-editable.js"></script>-->

		<!-- ace scripts -->
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo base_url();?>assets/dashboard/ace-master/assets/js/ace.min.js"></script>

		<script>
			$(document).ready(function(){
				$("#filter").click(function(){
					$("#form").toggle();

				});
			});
		</script>
		
		
		
		<script type="text/javascript">
			jQuery(function($) {

				$('[data-rel=tooltip]').tooltip();

				$(".select2").css('width','600px').select2({allowClear:true})
				.on('change', function(){
					$(this).closest('form').validate().element($(this));
				}); 


				var $validation = false;
				$('#fuelux-wizard-container')
				.ace_wizard({
					//step: 2 //optional argument. wizard will jump to step "2" at first
					//buttons: '.wizard-actions:eq(0)'
				})
				.on('actionclicked.fu.wizard' , function(e, info){
					if(info.step == 1 && $validation) {
						if(!$('#validation-form').valid()) e.preventDefault();
					}
				})
				.on('finished.fu.wizard', function(e) {
					bootbox.dialog({
						message: "Terima Kasih Data Anda Sudah Kami Simpan Kedalam Sistem!", 
						
						buttons: {
							"success" : {
								"label" : "OK",
								"className" : "btn-sm btn-primary"
							}
						}
					});
				}).on('stepclick.fu.wizard', function(e){
					//e.preventDefault();//this will prevent clicking and selecting steps
				});


				$('#skip-validation').removeAttr('checked').on('click', function(){
					$validation = this.checked;
					if(this.checked ) {
						$('#sample-form').hide();
						$('#validation-form').removeClass('hide');
					}
					else {
						$('#validation-form').addClass('hide');
						$('#sample-form').show();
					}
				})

				
				
				
				$('#modal-wizard-container').ace_wizard();
				$('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
				
				
				/**
				$('#date').datepicker({autoclose:true}).on('changeDate', function(ev) {
					$(this).closest('form').validate().element($(this));
				});
				
				$('#mychosen').chosen().on('change', function(ev) {
					$(this).closest('form').validate().element($(this));
				});
				*/

				$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
				})
				//show datepicker when clicking on the icon
				.next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				//or change it into a date range picker
				$('.input-daterange').datepicker({autoclose:true});


				//to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
				// $('input[name=date-range-picker]').daterangepicker({
				// 	'applyClass' : 'btn-sm btn-success',
				// 	'cancelClass' : 'btn-sm btn-default',
				// 	locale: {
				// 		applyLabel: 'Apply',
				// 		cancelLabel: 'Cancel',
				// 	}
				// })
				// .prev().on(ace.click_event, function(){
				// 	$(this).next().focus();
				// });


				// $('#timepicker1').timepicker({
				// 	minuteStep: 1,
				// 	showSeconds: true,
				// 	showMeridian: false
				// }).next().on(ace.click_event, function(){
				// 	$(this).prev().focus();
				// });
				
				// $('#date-timepicker1').datetimepicker().next().on(ace.click_event, function(){
				// 	$(this).prev().focus();
				// });

				
				
				$(document).one('ajaxloadstart.page', function(e) {
					//in ajax mode, remove remaining elements before leaving page
					$('[class*=select2]').remove();
				});
			})
		</script>
		
	
		<!-- inline scripts related to this page -->

		<!-- backup -->
		<script src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>	
		<link href="<?php echo base_url();?>css/chosen/chosen.css" rel="stylesheet">	
		<script type="text/javascript" src="<?=base_url()?>js/chosen/chosen.jquery.min.js"></script>
		<script type="text/javascript" src="<?=base_url()?>js/chosen/prism.js"></script>
		<script src="<?php echo base_url()?>assets/dashboard/ace-master/assets/js/jquery.chained.min.js"></script>

		<script>
		// You can also use "$(window).load(function() {"
		$(function () {
		// Slideshow 1
		$.validate();  
	});
		
		var config = {
			'.chosen-select'           : {},
			'.chosen-select-deselect'  : {allow_single_deselect:true},
			'.chosen-select-no-single' : {disable_search_threshold:10},
			'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
			'.chosen-select-width'     : {width:"100%"}
		}
		for (var selector in config) {
			$(selector).chosen(config[selector]);
		}
		
		$('.datetimepicker').datetimepicker();
	</script>
	<!-- backup -->



	<script type="text/javascript">
		$('#data_table').dataTable({
			"language": {
				"emptyTable":     "Data tidak ditemukan"
			}
		});
	</script>
	<script>

		$('#datatable').dataTable({});
		 $("#kota").chained("#provinsi");
	</script>
	<script src="<?php echo base_url()?>assets/dashboard/ace-master/assets/js/jquery.gritter.min.js"></script>

	<script type="text/javascript">
		var $path_assets = "dist";//this will be used in gritter alerts containing images

		$('[data-rel=tooltip]').tooltip();
		$('[data-rel=popover]').popover({html:true});

	</script>
	



</body>
</html>
