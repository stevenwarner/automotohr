<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Header -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title">
                                            <i class="fa fa-cogs"></i><?php echo $page_title; ?>
                                        </h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-right">
                                    <a href="<?= base_url('cn/dashboard'); ?>" class="btn btn-success">Dasboard</a>
                                </div>
                            </div>
                            <br />
                            <!--  -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <strong>ComplyNet Job Roles</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">ComplyNet Job Title</th>
                                                            <th scope="col">Linked Job Titles</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($job_roles)) { ?>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <p class="alert alert-info text-center">
                                                                        No job roles found.
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        <?php } else { ?>
                                                            <?php foreach ($job_roles as $role) { ?>
                                                                <tr data-id="<?= $role['sid']; ?>">
                                                                    <td class="vam">
                                                                        <strong>
                                                                            <?= $role['job_title']; ?>
                                                                        </strong>
                                                                    </td>
                                                                    <td class="vam">
                                                                        <?= $role['job_title_count']; ?>
                                                                    </td>
                                                                    <td class="vam">
                                                                        <button class="btn btn-success jsAddJobRole">
                                                                            <i class="fa fa-plus-circle"></i>&nbsp;
                                                                            Link Job Role
                                                                        </button>
                                                                        <button class="btn btn-success jsShowLinkedJobs">
                                                                            <i class="fa fa-eye"></i>&nbsp;
                                                                            Show Linked Job Roles
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php  } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Loader -->
                            <?php $this->load->view('loader', [
                                "props" => 'id="jsMainLoader"'
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .vam {
        vertical-align: middle !important;
    }
</style>