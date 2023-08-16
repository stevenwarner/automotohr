<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <div class="hr-items-count">
                                                <strong class="employerCount">Pages</strong>
                                            </div>

                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th>Slug</th>
                                                            <th width='18%'>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php foreach ($pagesList as $pageRow) { ?>
                                                            <tr>
                                                                <td><b><?php echo $pageRow['title']?></b></td>
                                                                <td><b><?php echo $pageRow['slug']?></td>
                                                                <td>
                                                                    <a href="<?= base_url('cms/pages/edit_page/'.$pageRow['sid']) ?>" class="btn btn-warning btn-sm" title="Edit Employer"><i class="fa fa-pencil"></i> Edit</a>
                                                                    <a href="<?= base_url($pageRow['slug']) ?>" class="btn btn-success btn-sm" title="Edit Employer" target="_blank"><i class="fa fa-eye"></i> View</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>

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
<!--  -->
<link rel="stylesheet" href="<?= base_url("assets/css/SystemModel.css"); ?>">
<script src="<?= base_url("assets/js/SystemModal.js"); ?>"></script>