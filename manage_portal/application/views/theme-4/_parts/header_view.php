<!doctype html>
<html prefix="og: http://ogp.me/ns#">

<head>
    <?php
    if (!empty($job_details)) {
        $heading_title = job_title_uri($job_details, true);
    } else if ($meta_title) {
        $heading_title = $meta_title;
    } ?>
    <title><?php echo $heading_title; ?></title>
    <meta charset="utf-8">
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= image_url('favicon_io'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= image_url('favicon_io'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= image_url('favicon_io'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= image_url('favicon_io'); ?>/site.webmanifest">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (!empty($job_details)) { ?>
        <meta name="description" content="<?php echo substr(strip_tags($job_details['JobDescription']) . ' - ' . strip_tags($job_details['JobRequirements']), 0, 125) . '...'; ?>" />
    <?php } else if ($meta_description) { ?>
        <meta name="description" content="<?php echo $meta_description; ?>" />
    <?php } ?>
    <?php if (!empty($job_details)) { ?>
        <meta name="keywords" content="<?php echo job_meta_keywords($job_details); ?>" />
    <?php } else if ($meta_keywords) { ?>
        <meta name="keywords" content="<?php echo $meta_keywords; ?>" />
    <?php } ?>
    <?php if ($this->uri->segment(1) == 'job_details') {
        $this->load->view('common/job_details_social_metatags_partial');
    } else if ($this->uri->segment(1) == 'testimonial') { ?>
        <!---Social Media Sharing Meta Tags Start-->
        <!--{*facebook meta*}-->
        <meta property="og:url" content="<?php echo base_url() ?>testimonial/<?php echo $testimonial['sid'] ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="<?php echo $testimonial['author_name'] ?>" />
        <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $testimonial['resource_name']; ?>" />
        <meta property="og:description" content="<?php echo $testimonial['short_description'] ?>" />
        <!--{*google meta*}-->
        <meta itemprop="name" content="<?php echo $testimonial['author_name'] ?>">
        <meta itemprop="description" content="<?php echo $testimonial['short_description'] ?>">
        <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $testimonial['resource_name']; ?>" />
        <!--{*twitter meta*}-->
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="<?php echo base_url() ?>testimonial/<?php echo $testimonial['sid'] ?>" />
        <meta name="twitter:title" content="<?php echo $testimonial['author_name'] ?>" />
        <meta name="twitter:description" content="<?php echo $testimonial['short_description'] ?>" />
        <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $testimonial['resource_name']; ?>" />
        <!---Social Media Sharing Meta Tags End-->
    <?php }
    ?>

    <?php $method = $this->router->fetch_method();
    if ($method == 'preview_job') { ?>
        <style>
            .navbar-default {
                display: none;
            }
        </style>
    <?php   } ?>

    <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/font-awesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/responsive.css'); ?>">
    <script src="<?php echo base_url('assets/' . $theme_name . '/js/jquery-1.11.2.min.js'); ?>"></script> <!-- Include jQuery Js -->
    <script src="<?php echo base_url('assets/' . $theme_name . '/js/bootstrap.min.js'); ?>"></script>
    <!--  -->
    <script>
        stopWindowScrollForHeader = <?= isset($site_settings['enable_header_bg']) && $site_settings['enable_header_bg'] == 1 ? 1 : 0 ?>;
    </script>
    <script src="<?php echo base_url('assets/' . $theme_name . '/js/custom.js?v=1.0'); ?>"></script><!-- Include Custom Js </-->


    <?php $custom_body_style = '';

    if (!empty($custom_font_details)) {
        if ($font_customization == 1) { // google font is selected
            $font_family = $custom_font_details['font_family'];
            $font_url = $custom_font_details['font_url'];
            $custom_body = "font-family: '" . $font_family . "'";
            $custom_body_style = 'style="' . $custom_body . ';"'; ?>
            <style>
                @font-face {
                    font-family: '<?php echo $font_family; ?>';
                    src: url('<?php echo $font_url; ?>') format('truetype');
                    /* Legacy iOS */
                }
            </style>
    <?php       } else { // web fonts is selected
            $font_family = $custom_font_details['web_fonts'];
            $custom_body = "font-family: '" . $font_family . "'";
            $custom_body_style = 'style="' . $custom_body . ';"';
        }
    } // custom font details end 
    ?>
    <style>
        <?php if ($theme4_search_container_bgcolor != NULL) { ?>.custom_search_bgcolor {
            background: <?= $theme4_search_container_bgcolor ?> !important;
        }

        .control input:checked~.control__indicator {
            background: <?= $theme4_search_container_bgcolor ?> !important;
        }

        .advance-search {
            box-shadow: 0 -3px 0px <?= $theme4_search_container_bgcolor ?>
        }

        <?php   }
        if ($theme4_search_btn_bgcolor != NULL) { ?>.theme4_search_btn {
            background: <?= $theme4_search_btn_bgcolor ?> !important;
        }

        <?php   }
        if ($theme4_search_btn_color != NULL) { ?>.theme4_search_btn {
            color: <?= $theme4_search_btn_color ?> !important;
        }

        <?php   }
        if ($theme4_btn_bgcolor != NULL) { ?>.showDetail,
        .custom-apply-now,
        .bg-color,
        .btn-close,
        .Pagination .active,
        .scrollToTop,
        .bg-color-v2 {
            background: <?= $theme4_btn_bgcolor ?> !important;
        }

        .locations_color {
            color: <?= $theme4_btn_bgcolor ?> !important;
        }

        .article-list:hover {
            border-top: 1px solid <?= $theme4_btn_bgcolor ?> !important;
            border-bottom: 1px solid <?= $theme4_btn_bgcolor ?> !important;
        }

        .down_btn {
            border: 1px solid <?= $theme4_btn_bgcolor ?> !important;
        }

        .down_btn:before {
            color: <?= $theme4_btn_bgcolor ?> !important;
        }

        <?php   }
        if ($theme4_btn_txtcolor != NULL) { ?>.showDetail,
        .custom-apply-now,
        .bg-color,
        .btn-close,
        .Pagination .active,
        .scrollToTop,
        .bg-color-v2 {
            color: <?= $theme4_btn_txtcolor ?> !important;
        }

        <?php   }
        if ($theme4_heading_color_span != NULL) { ?>.section-title span {
            color: <?= $theme4_heading_color_span ?>;
        }

        .testimonial .section-title {
            color: <?= $theme4_heading_color_span ?>;
        }

        .readmore-link {
            color: <?= $theme4_heading_color_span ?>;
        }

        <?php   }
        if ($theme4_heading_color != NULL) { ?>.section-title {
            color: <?= $theme4_heading_color ?> !important;
        }

        <?php    }
        if ($theme4_banner_text_l1_color != NULL) { ?>.slider-title {
            color: <?= $theme4_banner_text_l1_color ?> !important;
        }

        <?php    }
        if ($theme4_banner_text_l2_color != NULL) { ?>.slide-description {
            color: <?= $theme4_banner_text_l2_color ?> !important;
        }

        <?php    }
        if ($theme4_job_title_color != NULL) { ?>.job-title {
            color: <?= $theme4_job_title_color ?> !important;
        }

        .down_btn.same_color {
            border-color: <?= $theme4_job_title_color ?> !important;
        }

        .down_btn.same_color::before {
            color: <?= $theme4_job_title_color ?> !important;
        }

        <?php    } else { ?><?php if (strtoupper($pageName) == 'JOB_DETAILS' && !empty($jobs_detail_page_banner_data) && $jobs_detail_page_banner_data['banner_type'] == 'custom') { ?>.job-title {
            color: #0000ff !important;
        }

        .down_btn.same_color {
            border-color: #0000ff !important;
        }

        .down_btn.same_color::before {
            color: #0000ff !important;
        }

        <?php } ?><?php } ?>
    </style>

    <!--  -->
    <?php
    //
    if ($this->uri->segment(1) == 'job_details') {
        GetJobHeaderForGoogle($job_details, $company_details);
    }
    ?>
</head>

<body <?php echo $custom_body_style; ?>>
    <?php $this->load->view('common/header_menu'); ?>

    <?php if (isset($site_settings['enable_header_overlay']) && $site_settings['enable_header_overlay'] == 0) : ?>
        <style>
            .main-slider:after {
                background: none;
            }

            .comapny-video:after {
                background: none;
            }
        </style>
    <?php endif; ?>