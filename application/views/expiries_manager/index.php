<!-- Main Start -->
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">               
                    <!--Navigation bar-->   
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Expirations Manager</span>
                    </div>
                    <div class="box-wrapper">
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="dash-box">
                                    <div class="dashboard-widget-box">
                                        <figure><i class="fa fa-clock-o"></i></figure>
                                        <h1 class="post-title">
                                            <a href="javascript:void(0);">Expired</a>
                                        </h1>
                                        <div class="count-box">
                                            <h1><?php echo count($expired); ?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--
                            <div class="col-xs-3">
                                <div class="dash-box">
                                    <div class="dashboard-widget-box">
                                        <figure><i class="fa fa-clock-o"></i></figure>
                                        <h1 class="post-title">
                                            <a href="javascript:void(0);">Expiring In 1 Day</a>
                                        </h1>
                                        <div class="count-box">
                                            <h1><?php echo count($expiringInOneDay); ?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->
                            <!--
                            <div class="col-xs-3">
                                <div class="dash-box">
                                    <div class="dashboard-widget-box">
                                        <figure><i class="fa fa-clock-o"></i></figure>
                                        <h1 class="post-title">
                                            <a href="#sevenDays">Expiring In 7 Days</a>
                                        </h1>
                                        <div class="count-box">
                                            <h1><?php echo count($expiringInSevenDays); ?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->

                            <div class="col-xs-3">
                                <div class="dash-box">
                                    <div class="dashboard-widget-box">
                                        <figure><i class="fa fa-clock-o"></i></figure>
                                        <h1 class="post-title">
                                            <a href="javascript:void(0);">Expiring In 30 Days</a>
                                        </h1>
                                        <div class="count-box">
                                            <h1><?php echo count($expiringInThirtyDays); ?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="create-job-box">
                        <?php if(count($expired) != 0){?>
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $this->load->view('expiries_manager/expired_items_list'); ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <!--
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php //$this->load->view('expiries_manager/expiring_in_seven_days'); ?>
                                </div>
                            </div>
                        </div>
                        -->
                        <?php if(count($expiringInThirtyDays) != 0){?>
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $this->load->view('expiries_manager/expiring_in_thirty_days'); ?>
                                </div>
                            </div>
                        </div>
                        <?php }

                        if(count($expired) != 0 || count($expiringInThirtyDays) != 0){?>
                            <div class="form-col-100 view-task-btn">
                                <a class="btn btn-success btn-block" href="<?php echo current_url().'/index/export'?>" style="width: 30%;float: right;">Export CSV</a>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>                       		
        </div>        
    </div>
</div>
<!-- Main End -->

<script>
    window.sre = {};
    window.sre.url = "<?=base_url();?>";
</script>