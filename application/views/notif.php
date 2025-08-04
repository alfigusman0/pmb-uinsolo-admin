<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SIPUKT | Notifikasi</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Icon -->
	<link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('assets/img/icon/apple-icon-57x57.png') ?>">
	<link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('assets/img/icon/apple-icon-60x60.png') ?>">
	<link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('assets/img/icon/apple-icon-72x72.png') ?>">
	<link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/icon/apple-icon-76x76.png') ?>">
	<link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('assets/img/icon/apple-icon-114x114.png') ?>">
	<link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('assets/img/icon/apple-icon-120x120.png') ?>">
	<link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('assets/img/icon/apple-icon-144x144.png') ?>">
	<link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/img/icon/apple-icon-152x152.png') ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/img/icon/apple-icon-180x180.png') ?>">
	<link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/img/icon/android-icon-192x192.png') ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/img/icon/favicon-32x32.png') ?>">
	<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/img/icon/favicon-96x96.png') ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/icon/favicon-16x16.png') ?>">
	<link rel="manifest" href="<?= base_url('assets/img/icon/manifest.json') ?>">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?= base_url('assets/img/icon/ms-icon-144x144.png') ?>">
	<meta name="theme-color" content="#ffffff">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url('assets/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url('assets/admin-lte/bower_components/font-awesome/css/font-awesome.min.css') ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?= base_url('assets/admin-lte/bower_components/Ionicons/css/ionicons.min.css') ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url('assets/admin-lte/dist/css/AdminLTE.min.css') ?>">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= base_url('assets/admin-lte/plugins/iCheck/square/blue.css') ?>">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-8B6ZFJ7NN0"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-8B6ZFJ7NN0');
	</script>
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="<?= base_url('Auth/') ?>"><b>SIPUKT</b></a>
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Notifikasi</p>

			<!-- Notification -->
			<?php $this->load->view('layout/notification'); ?>
			<a href="<?= redirect($_ENV['SSO']) ?>" class="btn btn-block btn-primary">Back to SSO</a>
		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery 3 -->
	<script src="<?= base_url('assets/admin-lte/bower_components/jquery/dist/jquery.min.js') ?>"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= base_url('assets/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
</body>

</html>