
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo STORE_NAME; ?>: <?= $title ?></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/star-rating.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/easy-responsive-tabs.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.nicescroll.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.parallax-scroll.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/easyResponsiveTabs.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/functions.js"></script>
    <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
    <!-- Bootstrap File Select -->
    <script type="text/javascript" src="<?= base_url() ?>assets/bootstrap-filestyle/js/bootstrap-filestyle.min.js"></script>
    <!-- Bootstrap File Select -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css"/>
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css"/>
    <script src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
    <script type="text/javascript" src="<?= base_url('/assets/js/jquery.timepicker.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('/assets/css/jquery.timepicker.css') ?>">
    <script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
</head>
<body>
	<?php $header = str_replace(['max-width: 1000px;'], '', $company_template_header_footer['header']);?>
    <?php 
        $header = preg_replace('/<div class="body-content" (.*?)><\/div>/', '', $header);
    ?>
    <?=$header;?>
    </div>
    <script>
        $('.body-content').remove();
    </script>
    <div style="margin-top: 50px;">

		
