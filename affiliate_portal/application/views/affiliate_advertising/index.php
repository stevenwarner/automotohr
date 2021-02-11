<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><?= $title; ?></h1>
                    <div class="btn-panel float-right">
                        <a href="<?= base_url() ?>dashboard" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr class="d-flex">
                                <th class="col-1">Sr No.</th>
                                <th class="col-3">Name</th>
                                <th class="col-3">Email</th>
                                <th class="col-3">Address</th>
                                <th class="col-2 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="d-flex">
                                <td class="col-sm-1">01</td>
                                <td class="col-sm-3">Ali Hassan</td>
                                <td class="col-sm-3">ah@egenienext.com</td>
                                <td class="col-sm-3">Lahore Pakistan</td>
                                <td class="col-sm-2 text-center">
                                    <a href="<?= base_url('affiliate-advertising/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <tr class="d-flex">
                                <td class="col-sm-1">02</td>
                                <td class="col-sm-3">Adil Sultan</td>
                                <td class="col-sm-3">sultan@egenienext.com</td>
                                <td class="col-sm-3">Lahore Pakistan</td>
                                <td class="col-sm-2 text-center">
                                    <a href="<?= base_url('affiliate-advertising/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <tr class="d-flex">
                                <td class="col-sm-1">03</td>
                                <td class="col-sm-3">Imran Khan</td>
                                <td class="col-sm-3">khan@egenienext.com</td>
                                <td class="col-sm-3">Islamabad Pakistan</td>
                                <td class="col-sm-2 text-center">
                                    <a href="<?= base_url('affiliate-advertising/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <tr class="d-flex">
                                <td class="col-sm-1">04</td>
                                <td class="col-sm-3">Muhammad Ali Jinnah</td>
                                <td class="col-sm-3">jinnah@pakistan.com</td>
                                <td class="col-sm-3">Karachi Pakistan</td>
                                <td class="col-sm-2 text-center">
                                    <a href="<?= base_url('affiliate-advertising/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <tr class="d-flex">
                                <td class="col-sm-1">05</td>
                                <td class="col-sm-3">Jone Doe</td>
                                <td class="col-sm-3">Doe@egenienext.com</td>
                                <td class="col-sm-3">Newyork America</td>
                                <td class="col-sm-2 text-center">
                                    <a href="<?= base_url('affiliate-advertising/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <tr class="d-flex">
                                <td class="col-sm-1">06</td>
                                <td class="col-sm-3">Tome Cruise</td>
                                <td class="col-sm-3">tome@egenienext.com</td>
                                <td class="col-sm-3">London England</td>
                                <td class="col-sm-2 text-center">
                                    <a href="<?= base_url('affiliate-advertising/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
