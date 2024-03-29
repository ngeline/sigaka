<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Sistem Informasi Penggajian Selkom Group">
	<meta name="keywords" content="Sistem Informasi Penggajian Selkom Group">
	<meta name="author" content="Jihad">
	<title><?= $title ?> - PRIMKOPABRI</title>
	<link rel="apple-touch-icon" href="<?= base_url() ?>assets/images/logo koperasi.png">
	<link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/images/logo koperasi.png">
	<link href="<?= base_url() ?>assets/css/fonts/css93c2.css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">

	<!-- BEGIN: Vendor CSS-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/vendors.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/forms/toggle/switchery.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/plugins/forms/switch.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/core/colors/palette-switch.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/charts/chartist.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/charts/chartist-plugin-tooltip.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/tables/datatable/datatables.min.css">

	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/pickers/daterange/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/pickers/pickadate/default.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/pickers/pickadate/default.date.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/pickers/pickadate/default.time.css">

	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/vendors/css/forms/selects/select2.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

	<link rel="stylesheet" href="<?= base_url() ?>assets/css/plugins/easy-autocomplete/easy-autocomplete.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/plugins/easy-autocomplete/easy-autocomplete.themes.min.css">
	<!-- END: Vendor CSS-->

	<!-- BEGIN: Theme CSS-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap-extended.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/colors.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/components.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/fonts/line-awesome/css/line-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/fonts/line-awesome/1.1/css/line-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/fonts/feather/style.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/fonts/simple-line-icons/style.min.css">
	<!-- END: Theme CSS-->

	<!-- BEGIN: Page CSS-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/core/menu/menu-types/vertical-menu.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/core/colors/palette-gradient.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/core/colors/palette-gradient.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/pages/chat-application.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/pages/dashboard-analytics.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/plugins/pickers/daterange/daterange.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/plugins/animate/animate.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.min.css">
	<!-- END: Page CSS-->

	<!-- BEGIN: Custom CSS-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
	<!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns   fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">

	<!-- BEGIN: Header-->
	<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light d-print-none">
		<div class="navbar-wrapper">
			<div class="navbar-container content">
				<div class="collapse navbar-collapse show" id="navbar-mobile">
					<ul class="nav navbar-nav mr-auto float-left">
						<li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
						<li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
						<li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
					</ul>
					<ul class="nav navbar-nav float-right">
						<li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"> <span class="avatar avatar-online"><img src="<?= base_url() ?>assets/images/portrait/small/profil-circle-512.png" alt="avatar"></span></a>
							<div class="dropdown-menu dropdown-menu-right">
								<div class="arrow_box_right"><a class="dropdown-item" href="#"><span class="avatar avatar-online"><strong><?= $this->session->userdata('session_nama'); ?></strong></span><br><br>
										<?= $this->session->userdata('session_hak_akses'); ?>
										</span></a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="javascript:void(0)" class="edit-password" data-toggle="modal" data-target="#ubahPasswordModal"><i class="ft-lock"></i>
										Ubah Password</a>
									<a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="ft-power"></i>
										Logout</a>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<!-- END: Header-->

	<!-- BEGIN: Main Menu-->
	<div class="main-menu menu-fixed menu-light menu-accordion d-print-none menu-shadow " data-scroll-to-active="true" data-img="<?= base_url() ?>assets/images/backgrounds/02.jpg">
		<div class="navbar-header">
			<ul class="nav navbar-nav flex-row">
				<li class="nav-item mr-auto"><a class="navbar-brand" href="<?= base_url('dashboard') ?>">
						<img class="brand-logo" alt="Chameleon admin logo" src="<?= base_url() ?>assets/images/logo koperasi.png" />
						<h3 class="brand-text">PRIMKOPPABRI</h3>
					</a></li>
				<li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
			</ul>
		</div>
		<div class="navigation-background"></div>
		<div class="main-menu-content">
			<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
				<li class=" nav-item <?php if ($this->uri->segment(1) == 'dashboard') echo 'active' ?>"><a href="<?= base_url('dashboard') ?>"><i class="ft-home"></i><span class="menu-title" data-i18n="">Dashboard</span></a>
				</li>
				<?php if ($this->session->userdata('session_karyawan_status') == 'kasir' || $this->session->userdata('session_karyawan_status') == null) : ?>
					<li class=" nav-item"><a href="#"><i class="ft-layers"></i><span class="menu-title" data-i18n="">Data Master</span></a>
						<ul class="menu-content">
							<li class="<?php if ($this->uri->segment(1) == 'karyawan') echo 'active' ?>"><a class="menu-item" href="<?= base_url('karyawan') ?>"><i class="ft-users"> </i> Data
									Karyawan</a>
							</li>
							<!-- <li class="<?php if ($this->uri->segment(1) == 'jabatan') echo 'active' ?>"><a class="menu-item" href="<?= base_url('jabatan') ?>"><i class="ft-award"></i> Data Jabatan</a>
						</li> -->
						</ul>
					</li>
					<li class=" nav-item"><a href="#"><i class="ft-layers"></i><span class="menu-title" data-i18n="">Data Potongan</span></a>
						<ul class="menu-content">
							<?php if ($this->session->userdata('session_hak_akses') != 'pimpinan') : ?>
								<li class="<?php if ($this->uri->segment(1) == 'potongan_gaji') echo 'active' ?>"><a class="menu-item" href="<?= base_url('potongan_gaji') ?>"><i class="icon-wallet"> </i> Potongan Gaji</a>
								</li>
							<?php endif; ?>
							<?php if ($this->session->userdata('session_hak_akses') != 'koordinator') : ?>
								<li class="<?php if ($this->uri->segment(1) == 'pinjaman') echo 'active' ?>"><a class="menu-item" href="<?= base_url('pinjaman') ?>"><i class="ft-calendar"></i> Pinjaman</a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>
				<li class=" nav-item <?php if ($this->uri->segment(1) == 'absensi') echo 'active' ?>"><a href="<?= base_url('absensi') ?>"><i class="ft-user-check"></i><span class="menu-title" data-i18n="">Absensi</span></a>
				</li>
				<?php if ($this->session->userdata('session_karyawan_status') != 'rekap tetap' && $this->session->userdata('session_karyawan_status') != 'rekap training' && $this->session->userdata('session_karyawan_status') != 'kasir') : ?>
					<li class=" nav-item <?php if ($this->uri->segment(1) == 'storting') echo 'active' ?>"><a href="<?= base_url('storting') ?>"><i class="ft-user-check"></i><span class="menu-title" data-i18n="">Storting</span></a>
					</li>
				<?php endif; ?>
				<?php if ($this->session->userdata('session_hak_akses') == 'karyawan' && $this->session->userdata('session_karyawan_status') == 'rekap tetap' || $this->session->userdata('session_karyawan_status') == 'lapangan tetap') : ?>
					<li class=" nav-item <?php if ($this->uri->segment(1) == 'pinjaman') echo 'active' ?>"><a href="<?= base_url('pinjaman') ?>"><i class="ft-calendar"></i><span class="menu-title" data-i18n="">Pinjaman</span></a>
					</li>
				<?php endif; ?>
				<?php if ($this->session->userdata('session_hak_akses') != 'karyawan' || $this->session->userdata('session_karyawan_status') == 'rekap tetap' || $this->session->userdata('session_karyawan_status') == 'lapangan tetap' || $this->session->userdata('session_karyawan_status') == 'kasir') : ?>
					<li class=" nav-item <?php if ($this->uri->segment(1) == 'tabungan') echo 'active' ?>"><a href="<?= base_url('tabungan') ?>"><i class="icon-wallet"></i><span class="menu-title" data-i18n="">Tabungan</span></a>
					</li>
				<?php endif; ?>
				<?php if ($this->session->userdata('session_hak_akses') != 'pimpinan') : ?>
					<li class=" nav-item <?php if ($this->uri->segment(1) == 'gaji') echo 'active' ?>"><a href="<?= base_url('gaji') ?>"><i class="icon-wallet"></i><span class="menu-title" data-i18n="">Gaji</span></a>
					</li>
				<?php endif; ?>
				<?php if ($this->session->userdata('session_karyawan_status') == 'kasir' || $this->session->userdata('session_hak_akses') == 'owner') : ?>
					<li class=" nav-item"><a href="<?= base_url('laporan') ?>"><i class="ft-file"></i><span class="menu-title" data-i18n="">Laporan</span></a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<!-- END: Main Menu-->

	<!-- BEGIN: Content-->
	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-wrapper-before"></div>
			<div class="content-header row">
			</div>
			<!-- Modal ubah -->
			<div class="modal fade text-left" id="ubahPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title" id="myModalLabel35"> Ubah Password</h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<?= form_open('pengguna/ubah/password') ?>
						<div class="modal-body">
							<fieldset class="form-group floating-label-form-group">
								<label for="hadir">Kata sandi</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Masukkan sandi baru" autocomplete="off" required>
							</fieldset>
							<fieldset class="form-group floating-label-form-group">
								<label for="hadir">Konfirmasi Kata Sandi</label>
								<input type="password" class="form-control" name="confirm_password" id="confirm-password" placeholder="Masukkan konfrimasi sandi baru" autocomplete="off" required>
							</fieldset>
						</div>
						<div class="modal-footer">
							<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
							<input type="submit" class="btn btn-primary btn-bg-gradient-x-blue-cyan" name="simpan" value="Simpan">
						</div>
						<?= form_close() ?>
					</div>
				</div>
			</div>
			<div class="content-body"><!-- Revenue, Hit Rate & Deals -->
				<?php
				if ($this->session->flashdata('alert') == 'login_sukses') :
				?>
					<div class="alert alert-success alert-dismissible animated fadeInDown" style="position: absolute; width: 100%; z-index: 2" id="feedback" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						Berhasil Login <?= $this->session->userdata('session_hak_akses'); ?>
						, <?= $this->session->userdata('session_nama'); ?>
					</div>
				<?php
				elseif ($this->session->flashdata('alert') == 'sudah_login') :
				?>
					<div class="alert alert-warning alert-dismissible animated fadeInDown" style="position: absolute; width: 100%; z-index: 2" id="feedback" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						Sudah Login
					</div>
				<?php
				endif;
				?>
				<?php
				if ($this->session->flashdata('alert') == 'insert') :
				?>
					<div class="alert alert-success alert-dismissible animated fadeInDown" id="feedback" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						Data berhasil ditambahkan
					</div>
				<?php
				elseif ($this->session->flashdata('alert') == 'update') :
				?>
					<div class="alert alert-success alert-dismissible animated fadeInDown" id="feedback" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						Data berhasil diupdate
					</div>
				<?php
				elseif ($this->session->flashdata('alert') == 'delete') :
				?>
					<div class="alert alert-danger alert-dismissible animated fadeInDown" id="feedback" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						Data berhasil dihapus
					</div>
				<?php
				elseif ($this->session->flashdata('alert') == 'error') :
				?>
					<div class="alert alert-danger alert-dismissible animated fadeInDown" id="feedback" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?= $this->session->flashdata('message'); ?>
					</div>
				<?php
				endif;
				$this->session->set_flashdata('alert', '');
				$this->session->set_flashdata('message', '');
				?>
