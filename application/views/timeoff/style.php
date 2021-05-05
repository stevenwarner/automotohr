<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css">
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/pagination.min.css"/>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/jquery.datetimepicker.css"/>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/mFileUploader/index<?=$GLOBALS['minified_version'];?>.css"/>

<?php if (isset($theme) && $theme == 1) { ?>
	<link rel="stylesheet" href="<?=base_url('assets/timeoff/css/main'.( $GLOBALS['minified_version'] ).'.css');?>?v=<?= ENVIRONMENT == 'development' ? $GLOBALS['asset_version'] : '1.0';?>" />
<?php } else if (isset($theme) && $theme == 2) { ?>
	<link rel="stylesheet" href="<?=base_url('assets/timeoff/css/theme2021'.( $GLOBALS['minified_version'] ).'.css');?>?v=<?= ENVIRONMENT == 'development' ? $GLOBALS['asset_version'] : '1.0';?>" />
<?php } ?>
<link rel="stylesheet" href="<?=base_url('assets/bootstrapToggle/main.css');?>" />