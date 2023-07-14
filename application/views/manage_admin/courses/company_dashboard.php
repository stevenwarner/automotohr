<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!--  -->
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

                            <div class="row">
                                <div class="heading-title page-title">
                                    <div class="col-lg-10 col-md-10 col-xs-6 col-sm-6 text-left">
                                        <h1 class="page-title">
                                            <i class="fa fa-book"></i>
                                            LMS Courses - <?php echo getCompanyNameBySid($companyId); ?>
                                        </h1>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 text-right">
                                        
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div style="position: relative;">
                                <!-- Loader -->
                                <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
                                <!-- Main area -->
                                <div id="jsDefaultCoursesView"></div>
                                <!-- Main area -->
                                <div id="jsStoreCoursesView"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Load filter -->
                    <?php $this->load->view('manage_admin/courses/partials/company_dashboard'); ?>
                    <?php $this->load->view('manage_admin/courses/partials/store_course_filter'); ?>
                </div>
            </div>
        </div>
    </div>
</div>