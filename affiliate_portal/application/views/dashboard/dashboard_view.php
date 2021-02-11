<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left">Dashboard</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if(check_access_permissions_for_view($security_details, 'refer_client')) { ?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('refer-potential-clients') ?>"><i class="fa fa-users"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('refer-potential-clients') ?>">refer potential clients</a></h4>
                                <p>Simple and easy form to refer potential clients</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } if(check_access_permissions_for_view($security_details, 'view_referred_clients')) {?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('view-referral-clients') ?>"><i class="fa fa-list"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('view-referral-clients') ?>">View Referred Clients</a></h4>
                                <p>Referred Client Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } if(check_access_permissions_for_view($security_details, 'paying_clients')) {?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('my-current-paying-clients') ?>"><i class="fa fa-users"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('my-current-paying-clients') ?>">My Current Paying Clients</a></h4>
                                <p>List of all clients generating revenue for you</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } if(check_access_permissions_for_view($security_details, 'payment_voucher')) {?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('invoice') ?>"><i class="fa fa-money"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('invoice') ?>">Payment Vouchers</a></h4>
                                <p>Payment Voucher Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
<!--            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">-->
<!--                <div class="card">-->
<!--                    <div class="card-body text-center">-->
<!--                        <figure><a href="--><?//= base_url('affiliate-advertising') ?><!--"><i class="fa fa-globe"></i></a></figure>-->
<!--                        <div class="text">-->
<!--                            <h4><a href="--><?//= base_url('affiliate-advertising') ?><!--">Affiliate advertising</a></h4>-->
<!--                            <p>Cards support a wide variety of content <br> including images</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <?php if(check_access_permissions_for_view($security_details, 'refer_affiliate')) {?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('refer-an-affiliate') ?>"><i class="fa fa-map-signs"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('refer-an-affiliate') ?>">Refer an Affiliate</a></h4>
                                <p>Simple and easy form to refer potential affiliates</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } if(check_access_permissions_for_view($security_details, 'view_referred_affiliates')) {?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('view-referral-affiliates') ?>"><i class="fa fa-list"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('view-referral-affiliates') ?>">View Referred Affiliates</a></h4>
                                <p>Referred Affiliate Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } if(check_access_permissions_for_view($security_details, 'private_messages')) {?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('inbox') ?>"><i class="fa fa-envelope"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('inbox') ?>">Private Messages</a></h4>
                                <p>Private Messages</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } //if(check_access_permissions_for_view($security_details, 'documents')) {?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <figure><a href="<?= base_url('documents') ?>"><i class="fa fa-file"></i></a></figure>
                            <div class="text">
                                <h4><a href="<?= base_url('documents') ?>">Documents</a></h4>
                                <p>documents</p>
                            </div>
                        </div>
                    </div>
                </div>    
            <?php //} ?>
        </div>
    </div>
</div>
