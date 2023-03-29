<?php
$company_sid = $session['company_detail']['sid'];

$footer_logo_data  = get_footer_logo_data($company_sid);
$footer_logo_status = $footer_logo_data['footer_powered_by_logo'];
$footer_logo_type = $footer_logo_data['footer_logo_type'];
$footer_logo_text = $footer_logo_data['footer_logo_text'];
$footer_logo_image = $footer_logo_data['footer_logo_image'];

$footer_copyright_data  = get_footer_copyright_data($company_sid);
$copyright_status = $footer_copyright_data['copyright_company_status'];
$copyright_company_name = $footer_copyright_data['copyright_company_name']; ?>

<footer class="ob-footer <?= in_array('iframe', $this->uri->segment_array()) ? 'hidden' : ''; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                    <div class="hr-lanugages">
                        <div id="google_translate_element"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="copy-right-text">
                        <?php if ($copyright_status == 1) {
                            $company_name = $copyright_company_name;
                        } else {
                            $company_name = STORE_NAME;
                        }

                        $footer_copyright_text = "Copyright &copy; " . date('Y') . ' ' . $company_name . " All Rights Reserved"; ?>
                        <p title="<?php echo $footer_copyright_text; ?>"><?php echo $footer_copyright_text; ?></p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 text-center">
                    <?php if ($footer_logo_status == 1) { ?>
                        <a class="<?php if ($footer_logo_type == 'text') {
                                        echo 'copy-right-text text-white';
                                    } else {
                                        echo 'footer-text-logo';
                                    } ?>" href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                            <?php if ($footer_logo_type == 'default') { ?>
                                Powered by <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png'); ?>" alt="Logo" />
                            <?php       } else if ($footer_logo_type == 'text') { ?>
                                Powered by <?php echo $footer_logo_text; ?>
                            <?php       } else if ($footer_logo_type == 'logo') { ?>
                                Powered by <img src="<?php echo AWS_S3_BUCKET_URL . $footer_logo_image; ?>" class="upload_logo_image" alt="Logo" />
                            <?php       } ?>
                        </a>
                    <?php   } ?>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="social-links">
                        <ul>
                            <li>
                                <a class="google-plus" href="<?php
                                                                $g_url = get_slug_data('google_plus_url', 'settings');
                                                                $g_url = $g_url ?? "https://plus.google.com/u/0/b/102383789585278120218/+Automotosocialjobs/posts";
                                                                echo $g_url; ?>" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a class="twitter" href="<?php
                                                            $t_url = get_slug_data('twitter_url', 'settings');
                                                            $t_url = $t_url ?? "https://twitter.com/AutomotoSocial";
                                                            echo $t_url; ?>" target="_blank"><i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a class="facebook" href="<?php
                                                            $f_url = get_slug_data('facebook_url', 'settings');

                                                            if (!empty($f_url)) {
                                                                echo $f_url;
                                                            } else {
                                                                echo "https://www.facebook.com/automotosocialjobs";
                                                            }
                                                            ?>" target="_blank"><i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li><a class="linkedin" href="<?php
                                                            $l_url = get_slug_data('linkedin_url', 'settings');
                                                            if (!empty($l_url)) {
                                                                echo $l_url;
                                                            } else {
                                                                echo "https://www.linkedin.com/grp/home?gid=6735083&goback=%2Egna_6735083";
                                                            }
                                                            ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <?php
                            $y_url = get_slug_data('youtube_url', 'settings');
                            if (!empty($y_url)) {
                            ?>
                                <li><a class="youtube" href="<?php echo $y_url; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
                            <?php } ?>
                            <?php
                            $i_url = get_slug_data('instagram_url', 'settings');
                            if (!empty($i_url)) {
                            ?>
                                <li><a class="instagram" href="<?php echo $i_url; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                            <?php } ?>
                            <?php $gl_url = get_slug_data('glassdoor_url', 'settings');
                            if (!empty($gl_url)) { ?>
                                <li><a class="glassdoor" href="<?php echo $gl_url; ?>" target="_blank"><img src="<?= base_url() ?>assets/images/glassdoor.png"></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .notification-bell {
        border: none;
        margin: 0px 20px 0 0;
        margin-top: -10px;
        font-size: 25px;
        border-radius: 5px;
        cursor: pointer;
        border: none;
        color: #fff;
        height: 65px;
        background-color: transparent;
        position: relative;
    }

    .notification-count {
        position: absolute;
        top: 4px;
        right: -10px;
        font-size: 12px;
        color: #fff;
        padding: 5px;
        width: 25px;
        height: 25px;
        line-height: 15px;
        background-color: #81b431;
        border-radius: 100%;
    }

    .count-increament {
        background-color: #b4052c;
    }

    .notify-me .dropdown-menu.dropdown-menu-wide {
        max-width: 350px !important;
        width: 100%;
        left: auto;
        right: 125px;
        margin-top: 12px;
        padding: 0;
        max-height: 300px;
        border-radius: 0;
        z-index: 9999;
        border: none;
    }

    .notify-me .dropdown-menu.dropdown-menu-wide {
        min-height: 300px;
        max-height: 400px;
    }

    .notify-me .dropdown-menu:before {
        content: '';
        position: absolute;
        top: -21px;
        left: 0;
        right: 0;
        width: 0;
        height: 0;
        margin: auto;
        border-left: 16px solid transparent;
        border-right: 16px solid transparent;
        border-bottom: 20px solid #fff;
    }

    .notify-me .dropdown-menu>li {
        display: inline-block;
        width: 100%;
        vertical-align: top;
        margin-left: 0;
    }

    .notify-me .dropdown-menu>li a {
        display: block;
        float: left;
        width: 100%;
        text-transform: capitalize;
        padding: 25px 10px;
        border: 2px solid #e0e0e0;
        margin: -1px 0;
        background-color: #FFFFFF;
        color: #000000;
    }

    .notify-me .dropdown-menu>li a:hover {
        background-color: #81b431;
        color: #ffffff;
    }

    .notify-me .dropdown-menu:before {
        content: '';
        position: absolute;
        top: -21px;
        right: 17px;
        width: 0;
        height: 0;
        border-left: 16px solid transparent;
        border-right: 16px solid transparent;
        border-bottom: 20px solid #fff;
    }

    .faa-shake,
    .faa-shake.animated,
    .faa-shake.animated-hover:hover {
        -webkit-animation: bell-shake 8s ease infinite;
        animation: bell-shake 8s ease infinite;
    }

    @media only screen and (max-width: 576px) {
        .notify-me {
            text-align: center !important;
            float: none !important;
        }

        .notify-me .fa {
            color: #ffffff !important;
        }

        .notify-me .dropdown-menu.dropdown-menu-wide {
            right: 10px !important;
        }
    }

    @-webkit-keyframes bell-shake {
        1% {
            -webkit-transform: rotateZ(15deg);
            transform-origin: 50% 10%;
        }

        2% {
            -webkit-transform: rotateZ(-15deg);
            transform-origin: 50% 10%;
        }

        3% {
            -webkit-transform: rotateZ(20deg);
            transform-origin: 50% 10%;
        }

        4% {
            -webkit-transform: rotateZ(-20deg);
            transform-origin: 50% 10%;
        }

        5% {
            -webkit-transform: rotateZ(15deg);
            transform-origin: 50% 10%;
        }

        6% {
            -webkit-transform: rotateZ(-15deg);
            transform-origin: 50% 10%;
        }

        7% {
            -webkit-transform: rotateZ(0);
            transform-origin: 50% 10%;
        }

        100% {
            -webkit-transform: rotateZ(0);
            transform-origin: 50% 10%;
        }
    }

    @-moz-keyframes bell-shake {
        1% {
            -moz-transform: rotateZ(15deg);
            transform-origin: 50% 0%;
        }

        2% {
            -moz-transform: rotateZ(-15deg);
            transform-origin: 50% 0%;
        }

        3% {
            -moz-transform: rotateZ(20deg);
            transform-origin: 50% 0%;
        }

        4% {
            -moz-transform: rotateZ(-20deg);
            transform-origin: 50% 0%;
        }

        5% {
            -moz-transform: rotateZ(15deg);
            transform-origin: 50% 0%;
        }

        6% {
            -moz-transform: rotateZ(-15deg);
            transform-origin: 50% 0%;
        }

        7% {
            -moz-transform: rotateZ(0);
            transform-origin: 50% 0%;
        }

        100% {
            -moz-transform: rotateZ(0);
            transform-origin: 50% 0%;
        }
    }

    @keyframes bell-shake {
        1% {
            transform: rotateZ(15deg);
            transform-origin: 50% 0%;
        }

        2% {
            transform: rotateZ(-15deg);
            transform-origin: 50% 0%;
        }

        3% {
            transform: rotateZ(20deg);
            transform-origin: 50% 0%;
        }

        4% {
            transform: rotateZ(-20deg);
            transform-origin: 50% 0%;
        }

        5% {
            transform: rotateZ(15deg);
            transform-origin: 50% 0%;
        }

        6% {
            transform: rotateZ(-15deg);
            transform-origin: 50% 0%;
        }

        7% {
            transform: rotateZ(0);
            transform-origin: 50% 0%;
        }

        100% {
            transform: rotateZ(0);
            transform-origin: 50% 0%;
        }
    }
</style>
<!-- Footer scripts -->
<?php $this->load->view('2022/footer_scripts_2022'); ?>
</body>

</html>