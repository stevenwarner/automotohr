<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><?= $title; ?></h1>
                    <div class="btn-panel float-right">
                        <a href="<?= base_url('refer-potential-clients') ?>" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="form-wrp">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" class="form-control" />
                                </div>
                            </div><div class="col-xl-6">
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" name="state" class="form-control" />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" name="postal_code" class="form-control" />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>Profile Picture</label>
                                    <div class="choose-file-wrp">
                                        <input type="file" name="postal_code" class="form-control choose-file" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>Select Country</label>
                                    <div class="select">
                                        <select class="form-control">
                                            <option selected="selected">Please Select</option>
                                            <option>Pakistan</option>
                                            <option>America</option>
                                            <option>London</option>
                                            <option>Japan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group auto-height">
                                    <label>Comments</label>
                                    <textarea class="form-control textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="btn-panel text-right">
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
