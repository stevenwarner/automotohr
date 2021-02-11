<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i>Reply Details</h1>
                                        <a id="back_btn" href="<?php echo base_url('manage_admin/'.$uri_segment.'/view_details/'.$reply[0]["affiliation_sid"])?>" class="black-btn pull-right"><i class="fa fa-arrow-left"> </i> Go Back</a> 
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form method="post">
                                                    <table class="table table-bordered table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Email</b></th>
                                                            <td><?php echo $reply[0]["email"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Subject</b></th>
                                                            <td><?php echo $reply[0]["subject"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Replied On</b></th>
                                                            <td><?php echo !empty($reply[0]["reply_date"]) && $reply[0]["reply_date"] != '0000-00-00 00:00:00' ? my_date_format($reply[0]["reply_date"]) : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Reply</b></th>
                                                            <td><?php echo $reply[0]["message"]; ?></td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </form>
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
