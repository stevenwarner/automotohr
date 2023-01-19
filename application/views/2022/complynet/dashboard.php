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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title">
                                            <i class="fa fa-users"></i><?php echo $page_title; ?>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-10">
                                    <label>Select a Company <strong class="text-danger">*</strong></label> <br>
                                    <select id="jsCompany" style="width: 100%;">
                                        <option value="0">[Select a Company]</option>
                                        <?php foreach ($companies as $company): ?>
                                            <option value="<?=$company['sid'];?>"><?=$company['CompanyName'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-2 text-right">
                                        <br />
                                        <button class="btn btn-success jsStartProcess">Start Onboard</button>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div id="jsIntegrationView"></div>
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