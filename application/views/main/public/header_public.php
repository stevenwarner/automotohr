<!doctype html>
<html lang="en">

    <head>
        <?php $class = strtolower($this->router->fetch_class()); ?>
        <?php $method = $this->router->fetch_method(); ?>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <!-- favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="<?= image_url('favicon_io'); ?>/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= image_url('favicon_io'); ?>/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= image_url('favicon_io'); ?>/favicon-16x16.png">
        <link rel="manifest" href="<?= image_url('favicon_io'); ?>/site.webmanifest">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSS -->
        <link rel="stylesheet" type="text/css"
            href="<?= main_url("public/v1/plugins/bootstrap/css/bootstrap.min.css?v=3.0"); ?>">
        <link rel="stylesheet" type="text/css"
            href="<?php echo base_url('public/v1/plugins/fontawesome/4/font-awesome.min.css?v=3.0') ?>">
        <?php if ($loadView) { ?>
            <link rel="stylesheet" type="text/css"
                href="<?php echo base_url('assets/employee_panel/css/style.min.css?v=3.0') ?>">
        <?php } else { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css?v=3.0.0') ?>">
        <?php } ?>
        <?php if ($loadJsFiles) { ?>
            <script src="<?= main_url("public/v1/plugins/jquery/jquery-3.7.1.min.js?v=3.0"); ?>"></script>
            <script src="<?= getPlugin("validator", "js"); ?>"></script>
        <?php } ?>
        <link rel="stylesheet" type="text/css"
            href="<?php echo base_url('public/v1/plugins/ms_modal/main.css?v=3.0') ?>">
        <?= $pageCSS ? GetCss($pageCSS) : ''; ?>
        <!-- CSS bundles -->
        <?= $appCSS ?? ""; ?>
        <?= bundleCSS([
            "v1/app/css/global",
        ], "public/v1/app/", "global", true); ?>
    </head>

    <body>

        <div class="wrapper-outer">
            <?= str_replace(["max-width: 1000px;", "margin: 0 auto;"], "", $template["header"]); ?>