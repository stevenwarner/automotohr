<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$k0 = 0;
$total = count($applicantStatus["default"]) + count($applicantStatus["custom"]);
$mapped = count($mappedStatus);
$pending = $total - $mapped;
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <!--  -->
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <?php if ($this->session->flashdata('message')) { ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Total</strong>
                                        </div>
                                        <div class="panel-body text-center">
                                            <?= $total; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Mapped</strong>
                                        </div>
                                        <div class="panel-body text-center">
                                            <?= $mapped; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Pending</strong>
                                        </div>
                                        <div class="panel-body text-center">
                                            <?= $pending; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <form action="<?= base_url("manage_admin/indeed/disposition/status/map"); ?>" method="post">
                                <div class="panel panel-default">
                                    <div class="panel-footer text-right">
                                        <button class="btn btn-success">
                                            <i class="fa fa-save" aria-hidden="true"></i>
                                            Save changes
                                        </button>
                                    </div>
                                </div>
                                <!-- Default applicant status -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4>
                                            <strong>
                                                Default Applicant Status
                                            </strong>
                                        </h4>
                                    </div>

                                    <div class="panel-body">
                                        <?php foreach ($applicantStatus["default"] as $v0) : ?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Applicant Status</label>
                                                        <select name="status[<?= $k0; ?>][ats_name]" class="form-control">
                                                            <option value="<?= $v0["slug"]; ?>"><?= $v0["name"]; ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Indeed Status</label>
                                                        <select name="status[<?= $k0; ?>][indeed_name]" class="form-control">
                                                            <option value="0"></option>
                                                            <?php foreach ($indeedDispositionStatus as $v1) : ?>
                                                                <option value="<?= $v1["slug"]; ?>" <?= $mappedStatus[$v0["slug"]]["indeed_slug"] == $v1["slug"] ? "selected" : ""; ?>><?= $v1["status"]; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $k0++; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <!-- Custom applicant status -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4>
                                            <strong>
                                                Custom Applicant Status
                                            </strong>
                                        </h4>
                                    </div>

                                    <div class="panel-body">
                                        <?php foreach ($applicantStatus["custom"] as $v0) : ?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Applicant Status</label>
                                                        <select name="status[<?= $k0; ?>][ats_name]" class="form-control">
                                                            <option value="<?= $v0["slug"]; ?>"><?= $v0["name"]; ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Indeed Status</label>
                                                        <select name="status[<?= $k0; ?>][indeed_name]" class="form-control">
                                                            <option value="0"></option>
                                                            <?php foreach ($indeedDispositionStatus as $v1) : ?>
                                                                <option value="<?= $v1["slug"]; ?>" <?= $mappedStatus[$v0["slug"]]["indeed_slug"] == $v1["slug"]  ? "selected" : ""; ?>><?= $v1["status"]; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $k0++; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>


                                <div class="panel panel-default">
                                    <div class="panel-footer text-right">
                                        <button class="btn btn-success">
                                            <i class="fa fa-save" aria-hidden="true"></i>
                                            Save changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>