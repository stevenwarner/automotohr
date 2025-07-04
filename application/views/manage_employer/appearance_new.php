<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><a class="dashboard-link-btn" href="<?php echo base_url('my_settings'); ?>"><i class="fa fa-chevron-left"></i>Career Page Settings</a>Select Your Theme</span>
                        </div>
                        <div class="carousel">
                            <div class="mask">
                                <div class="slideset">
                                    <?php foreach ($themes as $theme) { ?>
                                        <?php if ($theme['is_paid'] == 1) { ?>
                                            <?php if ($theme['purchased'] == 1) { ?>
                                                <div class="slide <?php if ($theme['theme_status'] == 1) { ?> active_theme <?php } ?>">
                                                    <div class="theme_box">
                                                        <div class="theme_img">
                                                            <img src="<?php echo base_url(); ?>assets/images/<?php echo $theme['theme_image']; ?>" alt="">
                                                        </div>
                                                        <div class="theme_info">
                                                            <ul>
                                                                <li><a id="<?php echo $theme['sid'] ?>" href="javascript:void(0);" onclick="activeTheme(this.id)" ><?php if ($theme['theme_status'] == 1) { ?>Activated <?php } else { ?>Activate<?php } ?></a></li>
                                                                <li class="active_theme_btn"><a href="<?php echo base_url() ?>customize_appearance/<?php echo $theme['sid'] ?>" class="">Customize</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="theme_options">
                                                        <div class="theme_name">
                                                            <h2><?php
                                                                if ($theme['theme_name'] == 'theme-4') {
                                                                    echo "Deluxe Theme";
                                                                } else {
                                                                    echo $theme['theme_name'];
                                                                }
                                                                ?></h2>
                                                            <span>Select Your Theme</span>
                                                        </div>
                                                        <div class="theme_customize">
                                                            <a href="<?php echo base_url() ?>customize_appearance/<?php echo $theme['sid'] ?>">customize</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="slide <?php if ($theme['theme_status'] == 1) { ?> active_theme <?php } ?>">
                                                    <div class="theme_box">
                                                        <div class="theme_img">
                                                            <img src="<?php echo base_url(); ?>assets/images/<?php echo $theme['theme_image']; ?>" alt="">
                                                        </div>
                                                        <div class="theme_info">
                                                            <ul>
                                                                <li><a href="javascript:ShowDeluxeThemeDialog();">Activate</a></li>
                                                                <!--
                                                                <?php if ($company_upgrade == 'normal') { ?>
                                                                    <?php if ($company_expiry_days > 0) { ?>
                                                                        <li><a id="<?php echo $theme['sid'] ?>" href="<?php echo base_url() ?>enterprise_theme_activation" >Upgrade</a></li>
                                                                    <?php } else { ?>
                                                                        <li><a id="<?php echo $theme['sid'] ?>" href="javascript:void(0);" >Can't Upgrade</a></li>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <li><a id="<?php echo $theme['sid'] ?>" href="javascript:void(0);"  onclick="enterpriseThemeEmail();">Send Request to Admin</a></li>

                                                                <?php } ?>
                                                                -->

                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="theme_options">
                                                        <div class="theme_name">
                                                            <h2><?php
                                                                if ($theme['theme_name'] == 'theme-4') {
                                                                    echo "Enterprise Theme";
                                                                } else {
                                                                    echo $theme['theme_name'];
                                                                }
                                                                ?></h2>
                                                            <span>Select Your Theme</span>
                                                        </div>
                                                        <div class="theme_customize">
                                                            <a href="javascript:ShowDeluxeThemeDialog();">Customize</a>
                                                            <?php if ($company_upgrade == 'normal') { ?>
                                                                <?php if ($company_expiry_days > 0) { ?>
                                                                    <!--<a id="<?php echo $theme['sid'] ?>" href="<?php echo base_url() ?>enterprise_theme_activation" onclick="" >Upgrade</a>-->
                                                                <?php } else { ?>
                                                                    <!--<a id="<?php echo $theme['sid'] ?>" href="javascript:;" onclick="" >Company is expiring in less than Day</a>-->
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <!--<a id="<?php echo $theme['sid'] ?>" href="javascript:;" onclick="enterpriseThemeEmail()" >Send Request to Admin</a>-->
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="slide <?php if ($theme['theme_status'] == 1) { ?> active_theme <?php } ?>">
                                                <div class="theme_box">
                                                    <div class="theme_img">
                                                        <img src="<?php echo base_url(); ?>assets/images/<?php echo $theme['theme_image']; ?>" alt="">
                                                    </div>
                                                    <div class="theme_info">
                                                        <ul>
                                                            <li><a id="<?php echo $theme['sid'] ?>" href="javascript:void(0);" onclick="activeTheme(this.id)" ><?php if ($theme['theme_status'] == 1) { ?>Activated <?php } else { ?>Activate<?php } ?></a></li>
                                                            <li class="active_theme_btn"><a href="<?php echo base_url() ?>customize_appearance/<?php echo $theme['sid'] ?>" class="">Customize</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="theme_options">
                                                    <div class="theme_name">
                                                        <h2><?php
                                                            if ($theme['theme_name'] == 'theme-1') {
                                                                echo "Standard-1";
                                                            } elseif ($theme['theme_name'] == 'theme-2') {
                                                                echo "Standard-2";
                                                            } elseif ($theme['theme_name'] == 'theme-3') {
                                                                echo "Standard-3";
                                                            }
                                                            ?></h2>
                                                        <span>Select Your Theme</span>
                                                    </div>
                                                    <div class="theme_customize">
                                                        <a href="<?php echo base_url() ?>customize_appearance/<?php echo $theme['sid'] ?>">customize</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function ShowDeluxeThemeDialog(){
        var message = '';

        message += '<h4>Thank you for the interest in Upgrading your company account package.</h4>';
        message += '<p class="text-justify">If you would like to make a change to your account please contact your local <?php echo STORE_NAME; ?> representative or Contact one of our Talent Network Partners</p>';
        message += '<hr />';
        message += '<h4 class="text-center"><strong>Sales</strong></h4>';
        message += '<p class="text-center"><strong>Tel:</strong>&nbsp; 888-871-3096 ext 1</p>';
        message += '<p class="text-center"><strong>Email:</strong>&nbsp; <a style="color:#000;" href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"> <?php echo TALENT_NETWORK_SALES_EMAIL; ?></a></p>';
        message += '<div style="margin:10px 0;" class="separator"><div class="separator-inner"><span>or</span></div></div>';
        message += '<h4 class="text-center"><strong>Technical Support</strong></h4>';
        message += '<p class="text-center"><strong>Tel:</strong>&nbsp; 888-871-3096 ext 2</p>';
        message += '<p class="text-center"><strong>Email:</strong>&nbsp; <a style="color:#000;" href="mailto:<?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?>"> <?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></p>';
        message += '<hr />';
        message += '<p class="text-center"><strong>Thank you!</strong></p>';

        alertify.alert('Thank you', message, function () {
            //on ok
        });
    }



    function activeTheme(id) {
        console.log('I am In ' + id)
        url = "<?php echo base_url() ?>appearance/theme_status";
        alertify.confirm("Confirmation", "Are you sure you want to Activate selected Theme?",
                function () {
                    $.post(url, {
                        action: "update", id: id
                    })
                            .done(function (data) {
                                location.reload();
                            });
                    alertify.success('Activated');

                },
                function () {
                });
    }

    function enterpriseThemeEmail() {
        url = "<?php echo base_url() ?>appearance/enterprise_theme_email";
        alertify.confirm("Confirmation", "Are you sure to send Enterprise theme activation request?",
                function () {
                    $.post(url, {
                        action: "update"
                    })
                            .done(function (data) {
                                location.reload();
                            });
                    alertify.success('Mail sent.');
                },
                function () {
                });
    }
</script>