<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!--  -->
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <!-- Left column -->
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <!-- Main content area -->
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding jsExpandContent">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Title -->
                            <div class="heading-title page-title">
                                <h1 class="page-title">
                                    <i class="fa fa-book"></i>
                                    Benefits
                                </h1>
                            </div>

                            <br />
                            <br />
                            <br />

                            <!--  -->
                            <div id="jsBenefitCategoryBox"></div>
                            <div id="jsBenefitBox"></div>

                            <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>