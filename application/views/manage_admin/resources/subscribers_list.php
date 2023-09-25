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
                                        <h1 class="page-title"><i class="fa fa-address-card-o"></i>Subscribers </h1>
                                    <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/resources/') ?>"><i class="fa fa-long-arrow-left"></i> Back to subscribers</a>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-3 ">Email</th>
                                                                    <th class="col-xs-3 ">Created At</th>
                                                                    <th class="col-xs-3 ">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($pages_data as $dataRow) { ?>
                                                                    <tr>
                                                                        <td><?php echo $dataRow['email']; ?></td>
                                                                        <td>
                                                                            <?php echo  date_with_time($dataRow['created_at']); ?>
                                                                        </td>
                                                                        <td class=" <?php echo  $dataRow['status'] ==1 ? ' text-success' :' text-danger' ?>"> <strong> <?php echo  strtoupper($dataRow['status'] ==1 ? "Subscribed" :"Unsubscribed"); ?> </strong>
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Email Logs End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>