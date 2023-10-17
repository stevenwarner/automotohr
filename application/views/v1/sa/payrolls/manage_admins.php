<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="heading-title page-title">
                                <h1 class="page-title">
                                    <i class="fa fa-users"></i>
                                    <?php echo $title; ?>
                                </h1>
                            </div>
                            <!-- Main body -->
                            <br />
                            <br />
                            <br />
                            <!-- Content area -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12 text-right">
                                    <!-- admins -->
                                    <a href="<?= base_url("sa/payrolls/" . $loggedInCompanyId); ?>" class="btn csW csBG4 csF16">
                                        <i class="fa fa-arrow-left csF16"></i>
                                        &nbsp;Back
                                    </a>
                                    <a href="<?= base_url("sa/payrolls/company/" . $loggedInCompanyId . "/admins/add"); ?>" class="btn btn-success csF16" title="Add and admin" placement="top">
                                        <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp;
                                        Add An Admin
                                    </a>
                                    <!--  -->
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <caption></caption>
                                            <thead>
                                                <th scope="col">First Name</th>
                                                <th scope="col" class="text-right">Last Name</th>
                                                <th scope="col" class="text-right">Email</th>
                                                <th scope="col" class="text-right">Created At</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($admins) {
                                                    foreach ($admins as $admin) {
                                                ?>
                                                        <tr>
                                                            <td class="vam text-left"><?= $admin['first_name']; ?></td>
                                                            <td class="vam"><?= $admin['last_name']; ?></td>
                                                            <td class="vam"><?= $admin['email_address']; ?></td>
                                                            <td class="vam">
                                                                <?= formatDateToDB(
                                                                    $admin['created_at'],
                                                                    DB_DATE_WITH_TIME,
                                                                    DATE_WITH_TIME
                                                                ); ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <th scope="row">
                                                            <div class="alert alert-info text-center">
                                                                <p>
                                                                    No records found.
                                                                </p>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                <tr>
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