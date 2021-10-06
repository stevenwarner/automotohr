<!doctype html>
<html>
    <head>
        <?php    
            if(!empty($job_details)) {
                $heading_title = job_title_uri($job_details,true);
            }else if ($meta_title) { $heading_title = $meta_title; } ?>
        <title><?php echo $heading_title; ?></title>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php   if ($meta_description) { ?>
                    <meta name="description" content="<?php echo $meta_description; ?>" />
        <?php   } ?>
        <?php   if(!empty($job_details)) {?>
            <meta name="keywords" content= "<?php echo job_meta_keywords($job_details); ?>" />
        <?php   }else if ($meta_keywords) { ?>
                    <meta name="keywords" content= "<?php echo $meta_keywords; ?>" />
        <?php   } ?>
        <?php if ($this->uri->segment(1) == 'job_details' || $this->uri->segment(1) == 'display-job') {
                    $this->load->view('common/job_details_social_metatags_partial');
                } else if($this->uri->segment(1) == 'testimonial'){ ?>
                    <!---Social Media Sharing Meta Tags Start-->
                    <!--{*facebook meta*}-->
                    <meta property="og:url" content="<?php echo base_url() ?>testimonial/<?php echo $testimonial['sid'] ?>"/>
                    <meta property="og:type" content="website" />
                    <meta property="og:title" content="<?php echo $testimonial['author_name'] ?>" />
                    <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $testimonial['resource_name'];?>" />
                    <meta property="og:description" content="<?php echo $testimonial['short_description'] ?>" />
                    <!--{*google meta*}-->
                    <meta itemprop="name" content="<?php echo $testimonial['author_name'] ?>">
                    <meta itemprop="description" content="<?php echo $testimonial['short_description'] ?>">
                    <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $testimonial['resource_name'];?>" />
                    <!--{*twitter meta*}-->
                    <meta name="twitter:card" content="summary" />
                    <meta name="twitter:site" content="<?php echo base_url() ?>testimonial/<?php echo $testimonial['sid'] ?>" />
                    <meta name="twitter:title" content="<?php echo $testimonial['author_name'] ?>" />
                    <meta name="twitter:description" content="<?php echo $testimonial['short_description'] ?>" />
                    <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $testimonial['resource_name'];?>" />
                    <!---Social Media Sharing Meta Tags End-->
        <?php } ?>
        <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/bootstrap.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/font-awesome.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/style.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/' . $theme_name . '/css/responsive.css'); ?>">        
        <script src="<?php echo base_url('assets/' . $theme_name . '/js/jquery-1.11.2.min.js'); ?>"></script> <!-- Include jQuery Js -->        
        <script src="<?php echo base_url('assets/' . $theme_name . '/js/bootstrap.min.js'); ?>"></script>
        <!--  -->
        <script>
            stopWindowScrollForHeader = <?=isset($site_settings['enable_header_bg']) && $site_settings['enable_header_bg'] == 1 ? 1 : 0?>;
        </script>
        <script src="<?php echo base_url('assets/' . $theme_name . '/js/custom.js?v=1.0.0'); ?>"></script><!-- Include Custom Js </-->
        
        <?php   $custom_body_style = '';
                
                if(!empty($custom_font_details)) { 
                    if($font_customization==1){ // google font is selected
                        $font_family = $custom_font_details['font_family'];
                        $font_url = $custom_font_details['font_url'];
                        $custom_body = "font-family: '".$font_family."'";                    
                        $custom_body_style = 'style="'.$custom_body.';"'; ?>
                        <style>
                            @font-face {
                                font-family: '<?php echo $font_family;?>';
                                src: url('<?php echo $font_url;?>')  format('truetype'); /* Legacy iOS */
                            }
                        </style>
        <?php       } else { // web fonts is selected
                        $font_family = $custom_font_details['web_fonts'];
                        $custom_body = "font-family: '".$font_family."'";                    
                        $custom_body_style = 'style="'.$custom_body.';"'; 
                    } 
                } // custom font details end ?>
        <style>
        <?php   if($theme4_search_container_bgcolor!=NULL){ ?>
                        .custom_search_bgcolor {
                            background: <?= $theme4_search_container_bgcolor ?> !important;
                        }  
                        .advance-search{
                            box-shadow: 0 -3px 0px <?= $theme4_search_container_bgcolor ?>
                        }
        <?php   } if($theme4_search_btn_bgcolor!=NULL){ ?>                   
                        .theme4_search_btn {
                            background: <?= $theme4_search_btn_bgcolor ?> !important;
                        }                     
        <?php   } if($theme4_search_btn_color!=NULL){ ?>                    
                        .theme4_search_btn {
                            color: <?= $theme4_search_btn_color ?> !important;
                        }                    
        <?php   } if($theme4_btn_bgcolor!=NULL){ ?>                    
                        .showDetail, .custom-apply-now, .bg-color, .btn-close, .Pagination .active, .scrollToTop, .bg-color-v2 {
                            background: <?= $theme4_btn_bgcolor ?> !important;
                        }  
                        .locations_color {
                            color: <?= $theme4_btn_bgcolor ?> !important;
                        }  
                        .article-list:hover{
                                border-top: 1px solid <?= $theme4_btn_bgcolor ?> !important;
                                border-bottom: 1px solid <?= $theme4_btn_bgcolor ?> !important;
                        }
                        .down_btn {
                                border: 1px solid <?=$theme4_btn_bgcolor?> !important;
                        }
                        .down_btn:before {
                            color: <?= $theme4_btn_bgcolor ?> !important;
                        }
        <?php   } if($theme4_btn_txtcolor!=NULL){ ?>                    
                        .showDetail, .custom-apply-now, .bg-color, .btn-close, .Pagination .active, .scrollToTop, .bg-color-v2 {
                            color: <?= $theme4_btn_txtcolor ?> !important;
                        }                                 
        <?php   } if($theme4_heading_color_span!=NULL) { ?>                       
                        .section-title span {
                            color: <?= $theme4_heading_color_span ?> ;
                        }
                        .testimonial .section-title {
                            color: <?= $theme4_heading_color_span ?> ;
                        }
                        .readmore-link {
                            color: <?= $theme4_heading_color_span ?> ;
                        }                      
        <?php   } if($theme4_heading_color!=NULL) { ?>                       
                        .section-title {
                            color: <?= $theme4_heading_color ?> !important;
                        }                        
        <?php    } if($theme4_banner_text_l1_color!=NULL) { ?>                       
                        .slider-title {
                            color: <?= $theme4_banner_text_l1_color ?> !important;
                        }                        
        <?php    } if($theme4_banner_text_l2_color!=NULL) { ?>                       
                        .slide-description {
                            color: <?= $theme4_banner_text_l2_color ?> !important;
                        }                        
        <?php    } if($theme4_job_title_color!=NULL) { ?>                       
                        .job-title{
                            color: <?= $theme4_job_title_color ?> !important;
                        }
                        .down_btn.same_color{
                            border-color:<?= $theme4_job_title_color ?> !important;
                        }
                        .down_btn.same_color::before {
                            color: <?= $theme4_job_title_color ?> !important;
                        }
        <?php    }  ?>
        <?php   if(!empty($job_fairs)) {
                    if($job_fairs['button_background_color'] != NULL) { ?>
                        .fair_customizations {
                            background:<?=$job_fairs['button_background_color']?> !important;
                        }
        <?php       }
                    
                    if($job_fairs['button_text_color'] != NULL) { ?>
                        .fair_customizations {
                            color:<?=$job_fairs['button_text_color']?> !important;
                        }
        <?php       }
                } ?>
        </style>
    </head>
    
    <body <?php echo $custom_body_style; ?>>
    <!-- Header Section -->
    <?php $this->load->view('common/header_menu'); ?>

    <!-- Header Section -->