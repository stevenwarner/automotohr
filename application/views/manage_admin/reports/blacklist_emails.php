<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">

                                <div class="hr-box">
                                    <div class="hr-box-header bg-header-green">
                                        <span class="pull-left">
                                            <h1 class="hr-registered">Facebook Job Status</h1>
                                        </span>
                                        <span class="pull-right">
                                            <h1 class="hr-registered">Total Records Found :
                                                <?php echo count($jobs);?></h1>
                                        </span>
                                    </div>
                                    <div class="hr-innerpadding">

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Email</th>
                                                                <th>Bounce</th>
                                                                <th>Complaint</th>
                                                                <th>DateTime</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($jobs as $job){?>
                                                                <tr>
                                                                    <td><?=$job['email'];?></td>
                                                                    <td class="text-<?=$job['is_bounced'] == 1 ? "success" : "danger" ;?>"><strong><?=$job['is_bounced'] == 1 ? "YES" : "NO" ;?><strong></td>
                                                                    <td class="text-<?=$job['is_complaint'] == 1 ? "success" : "danger" ;?>"><strong><?=$job['is_complaint'] == 1 ? "YES" : "NO" ;?><strong></td>
                                                                    <td><?=DateTime::createfromformat('Y-m-d H:i:s', $job['created_at'])->format('M d, D Y H:i');?></td>
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
</div>
