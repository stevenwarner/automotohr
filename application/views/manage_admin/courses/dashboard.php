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

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title">
                                            <i class="fa fa-book"></i>
                                            LMS Courses
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <br />

                            <div style="position: relative;">
                                <!-- Loader -->
                                <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
                                <!-- Tabs -->
                                <div class="row">
                                    <!-- Active courses -->
                                    <div class="col-xs-12 col-md-2">
                                        <div class="thumbnail success-block">
                                            <div class="caption">
                                                <h3 id="">0</h3>
                                                <h4><strong>Active</strong></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- InActive courses -->
                                    <div class="col-xs-12 col-md-2">
                                        <div class="thumbnail error-block">
                                            <div class="caption">
                                                <h3 id="">0</h3>
                                                <h4><strong>InActive</strong></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Total courses -->
                                    <div class="col-xs-12 col-md-2">
                                        <div class="thumbnail post-block">
                                            <div class="caption">
                                                <h3 id="">0</h3>
                                                <h4><strong>Total</strong></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Main table -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4">
                                                <p class="csPanelHeading"><strong>Default Courses</strong></p>
                                            </div>
                                            <div class="col-sm-12 col-md-8 text-right">
                                                <button class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Course</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">

                                        <!-- Filter -->
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4">
                                                <label><strong>Title</strong></label>
                                                <input type="text" class="jsCourseTitle form-control" />
                                            </div>
                                            <div class="col-xs-12 col-md-4">
                                                <label><strong>Status</strong></label>
                                                <select class="jsCourseTitle form-control">
                                                    <option value="all">All</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">InActive</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 col-md-4">
                                                <label><strong>Job Titles</strong></label>
                                                <select class="jsCourseTitle form-control">
                                                    <option value="all">All</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 text-right">
                                                <button class="btn btn-success">Apply Search</button>
                                                <button class="btn btn-inverse">Clear Search</button>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-black">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Title</th>
                                                                <th scope="col">Description</th>
                                                                <th scope="col text-center">Job Titles</th>
                                                                <th scope="col text-center">Status</th>
                                                                <th scope="col text-center">Created At</th>
                                                                <th scope="col text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="vam"><strong>Harassment 101</strong></td>
                                                                <td class="vam">Some text goes here and here....</td>
                                                                <td class="vam text-center">
                                                                    <p><strong>(20) job titles found</strong></p>
                                                                    <button class="btn btn-link">View All</button>
                                                                </td>
                                                                <td class="vam text-center bg-success">
                                                                    <strong>ACTIVE</strong>
                                                                </td>
                                                                <td class="vam text-center">
                                                                    19 April 2023, Tuesday
                                                                </td>
                                                                <td class="vam text-center">
                                                                    <button class="btn btn-danger" title="Disable the course" placement="top">
                                                                        <i class="fa fa-ban"></i>
                                                                    </button>
                                                                    <button class="btn btn-success" title="Activate the course" placement="top">
                                                                        <i class="fa fa-shield"></i>
                                                                    </button>
                                                                    <button class="btn btn-success" title="View course details" placement="top">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                    <button class="btn btn-warning" title="View course" placement="top">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>