<div class="main-content">
    <?php if(!empty($page_data)) { ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('resource_center/resource_center_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <?php echo $page_data['page_title']?>
                        </span>
                    </div>

                    <div class="applicant-filter">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <?php echo html_entity_decode($page_data['page_content']);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <?php $sections = $page_data['sections']; ?>
                            <?php if(!empty($sections)) { ?>
                                <?php foreach($sections as $key => $section) { ?>

                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="hr-box">
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <strong class="text-center" style="font-size: 16px;">
                                                            <?php echo $section['title']; ?>
                                                        </strong>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <?php echo html_entity_decode($section['description']); ?>
                                                    </div>
                                                </div>
                                                <hr />
                                                <?php if($section['video_status'] == 1) { ?>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div align="center" class="embed-responsive embed-responsive-16by9">
                                                                <video controls class="embed-responsive-item">
                                                                    <source src="https://hr-documents-videos.s3.amazonaws.com/<?php echo $section['video']; ?>" type="video/mp4">
                                                                </video>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                <?php } ?>
                                                <?php if($section['youtube_video_status'] == 1) { ?>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div align="center" class="embed-responsive embed-responsive-16by9">
                                                                <iframe src="https://www.youtube.com/embed/<?php echo $section['youtube_video']; ?>" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>


                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<!-- Main End -->	