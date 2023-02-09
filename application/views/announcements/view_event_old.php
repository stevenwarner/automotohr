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

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url() . 'list_announcements'; ?>"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo ucwords($event[0]["type"] . ' Announcement'); ?></span>
                            </div>

                            <div class="dashboard-conetnt-wrp">

                                <div class="announcement-detail">
                                    <figure>
                                        <img class="img-responsive" src="<?= !empty($event[0]['section_image']) ? AWS_S3_BUCKET_URL . $event[0]['section_image'] : base_url('assets/images/no-img.jpg'); ?>"/>
                                    </figure>
                                    <h2 style="color: #000;">
                                        <?php echo ucwords($event[0]["title"]); ?>
                                    </h2>
                                    <div class="post-options">
                                        <ul>
                                            <li><?php echo date_with_time($event[0]["display_start_date"]) ; ?></li>
                                            <li>Announcements</li>
                                        </ul>
                                        <span class="post-author">By <?php echo $event[0]["first_name"] . ' ' . $event[0]["last_name"]?></span>
                                    </div>
                                    <div class="text full-width">
                                        <?php echo !empty($event[0]["message"]) ? $event[0]["message"] : 'N/A'; ?>
                                    </div>
                                    <?php if($event[0]["type"] == 'New Hire'){?>
                                        <h4 style="color: #000;">
                                            New Hire Bio
                                        </h4>
                                        <div class="text full-width">
                                            <?php echo !empty($event[0]["new_hire_bio"]) ? $event[0]["new_hire_bio"] : 'N/A'; ?>
                                        </div>
                                    <?php }?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } else { ?>
    <?php $this->load->view('announcements/view_event_ems'); ?>
<?php } ?>