<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile"><?php echo $title; ?></h1>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="dashboard-conetnt-wrp">

                            <?php if($events) { ?>
                                <div class="announcements-listing">
                                    <?php $flag = 0; foreach ($events as $event) {
                                        if ($event['status'] && (strtotime(date('Y-m-d H:i:s')) >= strtotime($event['display_start_date'])) && ($event['display_end_date'] == NULL || strtotime(date('Y-m-d H:i:s')) < strtotime($event['display_end_date']))) { ?>
                                            <article class="listing-article">
                                                <figure>
                                                    <a href="<?php echo base_url('announcements/view') . '/' . $event['sid']; ?>">
                                                        <img src="<?= !empty($event['section_image']) ? AWS_S3_BUCKET_URL . $event['section_image'] : base_url('assets/images/no-img.jpg'); ?>"/>
                                                    </a>
                                                </figure>
                                                <div class="text">
                                                    <h3><a href="<?php echo base_url('announcements/view') . '/' . $event['sid']; ?>"><?=  $event["title"];?></a></h3>
                                                    <div class="post-options">
                                                        <ul>
                                                            <li><?=reset_datetime(array('datetime' => $event["display_start_date"], '_this' => $this), true); ?></li>
                                                            <!-- <li><?php //echo date_with_time($event["display_start_date"]); ?></li> -->
                                                            <li>Announcements</li>
                                                        </ul>
                                                        <span class="post-author">By <?= $event["first_name"] . ' ' . $event["last_name"]?></span>
                                                    </div>
                                                    <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                        <?php echo strlen($event["message"]) > 100 ? substr(strip_tags($event["message"], 'img'),0,100)." ..." : $event["message"]; ?>
                                                    </div>
                                                </div>
                                            </article>
                                        <?php }
                                            else{
                                                $flag++;
                                            }
                                        } ?>
                                        <?php if(sizeof($events) == $flag){ ?>
                                            <div class="panel panel-default ems-documents">
                                                <div class="panel-heading">
                                                    <strong>Announcements Details</strong>
                                                </div>
                                                <div class="panel-body text-center">
                                                    <span class="no-data">No Announcements Found!</span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                </div>
                            <?php } ?>
<!--                            <div class="full-width">-->
<!--                                --><?php //if($events) { ?>
<!--                                <div class="table-responsive table-outer">-->
<!--                                    <tr class="table-wrp data-table">-->
<!--                                        <table class="table table-bordered table-stripped" id="reference_network_table">-->
<!--                                            <thead>-->
<!--                                            <tr>-->
<!--                                                <th class="col-lg-6">Title</th>-->
<!--                                                <th class="col-lg-4">Type</th>-->
<!--                                                <th class="col-lg-2 text-center">Actions</th>-->
<!--                                            </tr>-->
<!--                                            </thead>-->
<!--                                            <tbody>-->
<!--                                                --><?php //$flag = 0; foreach ($events as $event) {
//                                                    if ($event['status'] && (strtotime(date('Y-m-d H:i:s')) > strtotime($event['display_start_date'])) && ($event['display_end_date'] == NULL || strtotime(date('Y-m-d H:i:s')) < strtotime($event['display_end_date']))) {
//                                                        ?>
<!--                                                        <tr>-->
<!--                                                            <td>--><?php //echo $event['type']; ?><!--</td>-->
<!--                                                            <td class="text-center">-->
<!--                                                                <a class="btn btn-success btn-block"-->
<!--                                                                   href="--><?php //echo base_url('announcements/view') . '/' . $event['sid']; ?><!--">View</a>-->
<!--                                                            </td>-->
<!--                                                        </tr>-->
<!--                                                    --><?php //}
//                                                    else{
//                                                        $flag++;
//                                                    }
//                                                }?>
<!--                                                --><?php //if(sizeof($events) == $flag){ ?>
<!--                                                    <tr>-->
<!--                                                        <td colspan="3" class="text-center"><span class="no-data">No Announcement Found!</span> </td>-->
<!--                                                    </tr>-->
<!--                                                --><?php //} ?>
<!--                                            </tbody>-->
<!--                                        </table>-->
<!--                                    </div>-->
<!---->
<!--                                --><?php //} else { ?>
<!--                                    <div class="table-responsive table-outer">-->
<!--                                        <tr class="table-wrp data-table">-->
<!--                                            <table class="table table-bordered table-stripped" id="reference_network_table">-->
<!--                                                <thead>-->
<!--                                                <tr>-->
<!--                                                    <th class="col-lg-6">Title</th>-->
<!--                                                    <th class="col-lg-4">Type</th>-->
<!--                                                    <th class="col-lg-2 text-center">Actions</th>-->
<!--                                                </tr>-->
<!--                                                </thead>-->
<!--                                                <tbody>-->
<!--                                                        <tr>-->
<!--                                                            <td colspan="3" class="text-center"><span class="no-data">No Announcement Found!</span> </td>-->
<!--                                                        </tr>-->
<!--                                                </tbody>-->
<!--                                            </table>-->
<!--                                    </div>-->
<!--                                --><?php //} ?>
<!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>