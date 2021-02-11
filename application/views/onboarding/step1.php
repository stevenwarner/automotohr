<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                <?php if (!empty($sections) || !empty($ems_notification)) { ?>
                    <div class="widget-box">
                        <h3 class="text-blue">Your Activities</h3>
                        <ul class="activities-links">
                            <?php
                            $ems_flag = 0;
                            if (!empty($ems_notification)) {
                                foreach ($ems_notification as $notification) {
                                    ?>
                                    <li class="section_links" id="ems_<?php echo $ems_flag; ?>" ><a href="javascript:func_show_section_ems('<?php echo $ems_flag++; ?>');"><?php echo $notification['title']; ?></a></li>
                                <?php }
                            } ?>

                            <?php
                            $section_flag = 0;
                            foreach ($sections as $section) { ?>
                                <li class="section_links" id="link_<?php echo $section_flag; ?>" ><a href="javascript:func_show_section('<?php echo $section_flag++; ?>');"><?php echo $section['section_title']; ?></a></li>
                    <?php   } ?>
                        </ul>
                    </div>
<?php           } ?>
                        <?php if (!empty($links)) { ?>
                    <div class="widget-box">
                        <h3 class="text-blue">Helpful Links</h3>
                        <ul class="quick-links border-gray">
    <?php foreach ($links as $link) { ?>
                                <li><a target="_blank" href="<?php echo $link['link_url']; ?>"><?php echo $link['link_title']; ?></a></li>
                    <?php } ?>
                        </ul>
                    </div>
<?php } ?>
                        <?php if (!empty($timings)) { ?>
                    <div class="widget-box">
                        <h3 class="text-blue">Office Hours</h3>
                        <ul class="quick-links border-gray">
    <?php foreach ($timings as $time) { ?>
                                <li>
                                    <strong><?php echo $time['title']; ?></strong>
                                    <div>
                                        <i class="fa fa-clock-o"></i> <?php echo DateTime::createFromFormat('H:i:s', $time['start_time'])->format('h:i a') . ' - ' . DateTime::createFromFormat('H:i:s', $time['end_time'])->format('h:i a'); ?>
                                    </div>
                                </li>
                    <?php } ?>
                        </ul>
                    </div>
<?php } ?>
                        <?php if (!empty($locations)) { ?>
                    <div class="widget-box">
                        <h3 class="text-blue">Office Location(s)</h3>
                        <ul class="quick-links border-gray">
    <?php foreach ($locations as $location) { ?>
                                <li>
                                    <table class="table table-condensed">
                                        <tr><th class="" colspan="2"><?php echo $location['location_title']; ?></th></tr>
                                        <tr>
                                            <th><i class="fa fa-map"></i></th>
                                            <td><?php echo $location['location_address']; ?></td>
                                        </tr>

                                        <tr>
                                            <th><i class="fa fa-phone"></i></th>
                                            <td><?php echo $location['location_telephone']; ?></td>
                                        </tr>

                                        <tr>
                                            <th><i class="fa fa-fax"></i></th>
                                            <td><?php echo $location['location_fax']; ?></td>
                                        </tr>
                                    </table>
                                </li>
                    <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
<?php if (!empty($ems_notification) && isset($ems_notification)) {
    $ems_flag = 0;
    ?>
                        <?php foreach ($ems_notification as $section) { ?>
                        <div id="ems_flag<?php echo $ems_flag++; ?>" class="section welcone-video-box full-width">
                            <h1 class="text-blue"><?php echo $section['title']; ?></h1>
                            <?php if ($section['video_status'] && !empty($section['video_url'])) { ?>
                                <?php if ($section['video_source'] == 'youtube') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $section['video_url']; ?>"></iframe>
                                    </div>
                                <?php } else if ($section['video_source'] == 'vimeo') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe src="https://player.vimeo.com/video/<?php echo $section['video_url']; ?>" width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
            <?php } else { ?>
                                    <video id="my-video" class="video-js" controls preload="auto" width="auto" height="151"
                                           poster="<?php echo base_url('assets/uploaded_videos/MY_VIDEO_POSTER.jpg'); ?>" src="<?php echo base_url('assets/uploaded_videos/' . $section['video_url']) ?>">
                                        <p class="vjs-no-js">
                                            To view this video please enable JavaScript, and consider upgrading to a web browser that
                                            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                        </p>
                                    </video>
                                <?php } ?>
                            <?php } else if ($section['image_status'] && !empty($section['image_code'])) { ?>
                                <div class="img-thumbnail">
                                    <img src="<?php echo AWS_S3_BUCKET_URL . $section['image_code']; ?>" class="img-responsive" />
                                </div>
                            <?php } ?>
                            <?php if (!empty($section['description'])) { ?>
                                <div class="welcome-text text-justify">
                            <?php echo html_entity_decode($section['description']); ?>
                                </div>
                        <?php } ?>
                        </div>
    <?php } ?>
                    <?php } if (!empty($sections) && isset($sections)) {
                        $section_flag = 0;
                        ?>
    <?php foreach ($sections as $section) { ?>
                        <div id="<?php echo $section_flag++; ?>" class="section welcone-video-box full-width">
                            <h1 class="text-blue"><?php echo $section['section_title']; ?></h1>
                            <?php if (!empty($section['section_video'])) { ?>
            <?php if ($section['section_video_source'] == 'youtube') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $section['section_video']; ?>"></iframe>
                                    </div>
                                <?php } else { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe src="https://player.vimeo.com/video/<?php echo $section['section_video']; ?>" width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } ?>
                            <?php } else if (!empty($section['section_image'])) { ?>
                                <div class="img-thumbnail">
                                    <img src="<?php echo AWS_S3_BUCKET_URL . $section['section_image']; ?>" class="img-responsive" />
                                </div>
                            <?php } ?>
                        <?php if (!empty($section['section_content'])) { ?>
                                <div class="welcome-text text-justify">
                            <?php echo html_entity_decode($section['section_content']); ?>
                                </div>
        <?php } ?>
                        </div>
                    <?php } ?>
<?php } else { ?>
                    <div class="text-center">
                        <img style="display: inline-block; width: 70%; height: auto; opacity: 0.60;" src="<?php echo base_url('assets/images/onboarding.png') ?>" />
                    </div>
<?php } ?>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <?php if (!empty($people)) { ?>
                    <div class="colleague-pics full-width bg-white">
                        <h3 class="bg-blue">Your Colleagues</h3>
                        <ul class="colleague-list">
                                    <?php foreach ($people as $person) { ?>
                                        <?php $employee_info = $person['employee_info']; ?>
                                <li>
                                    <a href="<?php echo base_url('onboarding/colleague_profile') . '/' . $unique_sid . '/' . $employee_info['sid']; ?>">
        <?php if (isset($employee_info['profile_picture']) && !empty($employee_info['profile_picture'])) { ?>
                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $employee_info['profile_picture']; ?>">
                                <?php } else { ?>
                                            <img class="img-responsive" src="<?php echo base_url(); ?>assets/images/default_pic.jpg">
                        <?php } ?>
                                    </a>
                                </li>
                    <?php } ?>
                        </ul>
                    </div>
                        <?php } ?>

<?php if (!empty($items)) { ?>
                    <div class="widget-box">
                        <h3 class="text-blue">Items To Bring</h3>
                        <ul class="quick-links border-gray">
                            <?php foreach ($items as $item) { ?>
                                <li>
                                    <strong><?php echo $item['item_title']; ?></strong>
                                    <div><?php echo $item['item_description']; ?></div>
                                </li>
    <?php } ?>
                        </ul>
                    </div>
<?php } ?>
<?php if ($enable_learbing_center) { ?>
                    <div class="widget-box">
                        <a href="<?php echo base_url('onboarding/learning_center/' . $unique_sid); ?>">
                            <div class="link-box bg-redish full-width">
                                <h2>Learning Center</h2>
                                <div class="status-panel">
                                    <h3>Training Sessions and Online Videos</h3>
                                    <span>Assigned to You</span>
                                </div>
                            </div>
                        </a>
                    </div>
<?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="<?php echo base_url('onboarding/e_signature/' . $unique_sid); ?>">
                    <div class="link-box bg-purple full-width">
                        <h2>E-Signature</h2>
                        <div class="status-panel">
                            <h3>Status</h3>
<?php echo $complete_steps['e_signature'] > 0 ? '<span>completed</span>' : '<span>skipped</span>' ?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="<?php echo base_url('onboarding/documents/' . $unique_sid); ?>">
                    <div class="link-box bg-redish full-width">
                        <h2>HR Documents</h2>
                        <div class="status-panel">
                            <h3>Status</h3>
<?php echo $complete_steps['documents'] > 0 ? '<span>completed</span>' : '<span>skipped</span>' ?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="javascript:void(0);">
                    <div class="link-box bg-blue full-width">
                        <h2></h2>
                        <div class="status-panel">
                            <h3>Status</h3>
                            <span>Skipped</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="<?php echo base_url('onboarding/calendar/' . $unique_sid); ?>">
                    <div class="link-box bg-orange full-width">
                        <h2>Calendar</h2>
                        <div class="current-date">
                            <span><?php echo date('d'); ?><sub><?php echo strtolower(date('D')); ?></sub></span>
                        </div>
                        <div class="status-panel">
                            <h3>View Appointments</h3>
                            <span>View Schedules</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
<?php if (sizeof($ems_notification) > 0) { ?>
            func_show_section_ems(0);
<?php } else { ?>
            func_show_section(0);
<?php } ?>
    });

    function func_show_section(section_id) {
        $('.section').slideUp();
        $('#' + section_id).slideDown();
        $('.section_links').removeClass('active');
        $('#link_' + section_id).addClass('active');
    }

    function func_show_section_ems(section_id) {
        $('.section').slideUp();
        $('#ems_flag' + section_id).slideDown();
        $('.section_links').removeClass('active');
        $('#ems_' + section_id).addClass('active');
    }
</script>