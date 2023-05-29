<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!--  -->
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <!-- Left column -->
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <!-- Main content area -->
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">

                            <!-- Title -->

                            <div class="row">
                                <div class="heading-title page-title">
                                    <div class="col-lg-10 col-md-10 col-xs-10 col-sm-10">
                                        <h1 class="page-title">
                                            <i class="fa fa-book"></i>
                                            LMS Courses
                                        </h1>
                                    </div>
                                    <div class="col-xs-2 text-right">
                                        <button class="btn btn-secondary jsExpandAdminView" title="Expand view" placement="top">
                                            <i class="fa fa-expand" aria-hidden="true"></i> Expand View
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div style="position: relative;">
                                <!-- Loader -->
                                <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
                                <!-- Main area -->
                                <div id="jsDefaultCoursesView"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Filter -->
<div class="csFilterSection jsFilterSection hidden" data-key="jsPageLoader">
    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-10">
                    <p class="csPanelHeading"><strong><i class="fa fa-filter"></i>&nbsp;Filter</strong></p>
                </div>
                <div class="col-xs-2 text-right">
                    <button class="btn btn-black jsFilterSectionHideBtn" data-key="jsPageLoader">
                        <i class="fa fa-times-circle"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body jsFilterPanel">
            <!-- Filter -->
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <label><strong>Title</strong></label>
                    <input type="text" class="jsCourseTitleDefaultCourse form-control" />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <label><strong>Status</strong></label>
                    <select class="jsCourseStatusDefaultCourse form-control">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">InActive</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <label><strong>Job Titles</strong></label>
                    <select class="jsCourseJobTitleDefaultCourse" style="width: 100%;" multiple></select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <button class="btn btn-theme form-control jsApplyFilterDefaultCourse">Apply Search</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <a href="<?= base_url('sa/lms/courses'); ?>" class="btn btn-black  form-control">Clear Search</a>
                </div>
            </div>
        </div>
    </div>

</div>