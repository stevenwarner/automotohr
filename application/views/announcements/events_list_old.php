<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
<!--                            --><?php //$this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url() . 'my_settings'; ?>"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo 'hod'.$title; ?></span>
                            </div>

                            <div class="dashboard-conetnt-wrp">
                                <div class="btn-panel text-right">
                                    <?php if(check_access_permissions_for_view($security_details, 'announcements')) {?>
                                        <a class="btn btn-success" href="<?php echo base_url() . 'announcements'; ?>">Announcements Management</a>
                                    <?php }?>
                                </div>

<!--                                <div class="table-responsive table-outer">-->
                                    <?php if($events) { ?>

                                        <div class="announcements-listing">
                                            <?php $flag = 0; foreach ($events as $event) {
                                                if ($event['status'] && (strtotime(date('Y-m-d H:i:s')) > strtotime($event['display_start_date'])) && ($event['display_end_date'] == NULL || strtotime(date('Y-m-d H:i:s')) < strtotime($event['display_end_date']))) { ?>
                                                    <article class="listing-article">
                                                        <figure>
                                                            <a href="<?php echo base_url('announcements/view') . '/' . $event['sid']; ?>">
                                                                <img src="<?= !empty($event['section_image']) ? AWS_S3_BUCKET_URL . $event['section_image'] : base_url('assets/images/no-img.jpg'); ?>"/>
                                                            </a>
                                                        </figure>
                                                        <div class="text">
                                                            <h3><a href="<?php echo base_url('announcements/view') . '/' . $event['sid']; ?>"><?= $event['type'] . ': ' . $event["title"];?></a></h3>
                                                            <div class="post-options">
                                                                <ul>
                                                                    <li><?php echo date_with_time($event["display_start_date"]);?></li>
                                                                    <li>Announcements</li>
                                                                </ul>
                                                                <span class="post-author">By <?= $event["first_name"] . ' ' . $event["last_name"]?></span>
                                                            </div>
                                                            <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                <?php echo strlen($event["message"]) > 100 ? substr($event["message"],0,100)." ..." : $event["message"]; ?>
                                                            </div>
                                                        </div>
                                                    </article>
                                                <?php }
                                                else{
                                                    $flag++;
                                                }
                                            } ?>
                                            <?php if(sizeof($events) == $flag){ ?>

                                            <?php } ?>

                                        </div>

<!--                                        <div class="table-wrp">-->
<!--                                            <table class="table">-->
<!--                                                <thead>-->
<!--                                                <tr>-->
<!--                                                    <th class="col-xs-4 text-center">Title</th>-->
<!--                                                    <th class="col-xs-4 text-center">Type</th>-->
<!--                                                    <th class="col-xs-4 text-center">Actions</th>-->
<!--                                                </tr>-->
<!--                                                </thead>-->
<!--                                                <tbody>-->
<!--                                                --><?php //$flag = 0; foreach ($events as $event) {
//                                                    if ($event['status'] && (strtotime(date('Y-m-d H:i:s')) > strtotime($event['display_start_date'])) && ($event['display_end_date'] == NULL || strtotime(date('Y-m-d H:i:s')) < strtotime($event['display_end_date']))) {
//                                                        ?>
<!--                                                        <tr>-->
<!--                                                            <td class="text-center">--><?php //echo $event['title']; ?><!--</td>-->
<!--                                                            <td class="text-center">--><?php //echo $event['type']; ?><!--</td>-->
<!--                                                            <td class="text-center">-->
<!--                                                                <a class="btn btn-success active-btn" href="--><?php //echo base_url('announcements/view') . '/' . $event['sid']; ?><!--">View</a>-->
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
<!--                                                </tbody>-->
<!--                                            </table>-->
<!--                                        </div>-->

                                    <?php } else { ?>
                                        <div id="show_no_jobs" class="table-wrp">
                                            <span class="applicant-not-found">No Announcement Found! </span>
                                        </div>
                                    <?php } ?>
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
    <?php $this->load->view('announcements/events_list_ems'); ?>
<?php } ?>