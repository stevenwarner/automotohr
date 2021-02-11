<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />-->
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />-->
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery.datetimepicker.css" />-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/public-form-style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/responsive.css">
        <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
        <script src="<?php echo base_url('assets') ?>/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
        <!--<script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>-->
        <!--<script src="<?php echo base_url('assets') ?>/alertifyjs/alertify.min.js"></script>-->
        <!--<script src="<?php // echo base_url('assets') ?>/js/functions.js"></script>-->
    </head>
    <body>
        <div class="wrapper">
            <header class="header header-position">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                            <h2 style="color: #fff; text-align: center;"><?php echo $title; ?></h2>
                        </div>
                    </div>
                </div>
            </header>
            <div class="clear"></div>
            <div class="main" style="margin-top: 50px;">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php           foreach ($jobs as $job) { 

                                if (empty($job['pictures']) && !empty($job['company_logo'])) {
                                    $image = AWS_S3_BUCKET_URL . $job['company_logo'];
                                } else if (!empty($job['pictures'])){
                                    $image = AWS_S3_BUCKET_URL . $job['pictures'];
                                } else {
                                    $image = base_url('assets/images/no-preview.jpg');
                                } ?>

                                <article class="article-list">
                                    <figure>
                                        <img class="img-responsive" alt="Company Logo" src="<?php echo $image; ?>">
                                    </figure>
                                    <div class="text">
                                        <div class="title-area">
                                            <h2 class="post-title"><a target="_blank" href="<?php echo STORE_PROTOCOL_SSL . $job['Job_Url']; ?>"><?php echo $job['Title']; ?></a></h2>
                                            <div class="post-option">
                                                <ul>
                                                    <li>
                                                        <i class="color fa fa-map-marker"></i>
                                                        <div class="op-text">
                                <?php
                                                            if (!empty($job['Location_City'])) {
                                                                echo $job['Location_City'] . ', ';
                                                            }
                                                            
                                                            if (!empty($job['Location_State'])) {
                                                                echo $job['Location_State'] . ', ';
                                                            }
                                                            
                                                            echo $job['Location_Country']; ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <i class=" color fa fa-suitcase"></i>
                                                        <div class="op-text"><?php echo $job['JobCategory']; ?></div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="btn-area">
                                            <ul>
                                                <li><a target="_blank" href="<?php echo STORE_PROTOCOL_SSL . $job['Job_Url']; ?>" class="site-btn">View Details</a></li>
                                                <li><a class="site-btn" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo STORE_PROTOCOL_SSL . $job['Job_Url']; ?>" target="_blank">Share</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </article>
<?php                   } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </body>
</html>
