<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo STORE_NAME; ?></title>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/responsive.css">

    <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="main-content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					<div class="thankyou-page-wrp">
						<div class="thanks-page-icon">
							<div class="icon-circle-red"><i class="fa fa-times"></i></div>
						</div>
						<div class="thank-you-text">
							<h1>Error</h1>
							<span>No Record Found.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<style type="text/css">
	.icon-circle-red{
		display: inline-block;
	    width: 80px;
	    height: 80px;
	    font-size: 40px;
	    border-radius: 100%;
	    border: 2px solid #d62828;
	    line-height: 80px;
	    color: #d62828;
	}
</style>
</html>