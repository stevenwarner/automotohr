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
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					<div class="page-banner 2 affiliates-banner text-center">
				    	<figure>
					        <img src="<?= base_url() ?>assets/images/affiliates/bannar-2.jpg" alt="246"/>
					        <figcaption class="affiliates-header-text-new">
					            <div class="thankyou-page-wrp">
									<div class="thanks-page-icon">
										<div class="icon_style"><i class="fa fa-check"></i></div>
									</div>
									<div>

										<h1 class="thank_you_heading">
											thank you
										</h1>
										<span class="thank_you_text">
											Your Submission has been received and we will contact you soon.
										</span>
									</div>
								</div>
					        </figcaption>
					    </figure>
					</div>
				</div>	
			</div>
		</div>
	</div>
</body>
<style type="text/css">
	.icon_style{
		display: inline-block; 
		width: 80px; 
		height: 80px; 
		font-size: 40px;
		border-radius: 100%;
    	border: 2px solid #fff; 
    	line-height: 80px;
	}
	.thank_you_heading{
		text-transform: capitalize; 
		font-size: 70px; 
		margin-top: 0;
	}
	.thank_you_text{
		font-size: 20px; 
		font-weight: 600;
	}
</style>
</html>