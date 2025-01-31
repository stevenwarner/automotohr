<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p style="color: #cc0000;"><b><i>We suggest that you only use Google Chrome to access your account
                            and use its Features. Internet Explorer is not supported and may cause certain feature
                            glitches and security issues.</i></b></p>
                <?= onboardingHelpWidget($company_info['sid']); ?>
            </div>
            <div class="col-lg-12">
                <div class="btn-panel">
                    <a href="<?= base_url('onboarding/e_signature/' . $unique_sid) ?>" class="btn btn-success" id="go_next"> Let's Start <i class="fa fa-angle-right"></i></a>
                </div>
                <div class="applicant-welcome-video">
                    <?php if (!empty($welcome_video)) { ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="well well-sm">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <?php if ($welcome_video['video_source'] == 'youtube') { ?>
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        <?php } elseif ($welcome_video['video_source'] == 'vimeo') { ?>
                                            <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        <?php } else { ?>
                                            <video controls>
                                                <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $welcome_video['video_url']; ?>" type='video/mp4'>
                                            </video>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="applicant-bio">
                    <h1 class="text-blue"><?php echo ucwords($applicant['first_name']); ?>, You're All Ready to Go.</h1>
                    <!--                    <p>We're looking forward to having you here at <br><b><?php echo $company_info['CompanyName']; ?></b>.</p>-->
                    <p><?php echo $onboarding_instructions; ?></p>
                    <?php if (!empty($onboarding_disclosure)) { ?>
                        <hr>
                        <p><?php echo $onboarding_disclosure; ?></p>
                    <?php } ?>
                    <!--                    <p>--><?php //echo '<pre>';print_r($company_info);die(); 
                                                    ?>
                    <!--</p>-->
                </div>
                <!-- <div class="other-instructions text-center mb-2">
                    <h1 class="text-blue">Instructions</h1>

                </div>-->

            </div>
        </div>
        <div class="row">
            <div class="col-lg-10">
                <div class="full-width applicant-bio">
                    <div class="joining-date">
                        <h2 class="text-blue">Your first day is <?php if (!empty($joining_date)) { ?>
                                <?php
                                                                    $date = DateTime::createFromFormat('m/d/Y', $joining_date)->format('Y-m-d');
                                                                    echo reset_datetime(array('datetime' => $date, '_this' => $this, 'new_zone' => 'PST'));
                                ?>


                            <?php } ?></h2>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 calendar-icon text-right">
                            <h4>
                                <?php
                                //
                                if (empty($locations) && !empty($companyDefaultAddress)) {
                                    $companyDefaultAddress['location_address'] = ltrim($companyDefaultAddress['location_address'], ', ');
                                    $locations[] = $companyDefaultAddress;
                                }
                                $address = '';
                                if (!empty($locations)) {
                                    $address = $locations[0]['location_address'];
                                    $map_url = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($address) . "&zoom=13&size=300x200&key=" . GOOGLE_MAP_API_KEY . "&markers=color:blue|label:|" . urlencode($address);
                                    $map_anchor = '<a href = "https://maps.google.com/maps?z=12&t=m&q=' . urlencode($address) . '"><img src = "' . $map_url . '" alt = "No Map Found!" ></a>';
                                    $show_map = '<p><b>Address:</b> ' . $address . ' </p>';
                                    $show_map .= '<p> ' . $map_anchor . ' </p>';
                                    echo $show_map;
                                } elseif (!empty($company_info['Location_Address'])) {
                                    $address = $company_info['Location_Address'];
                                    $map_url = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($address) . "&zoom=13&size=300x200&key=" . GOOGLE_MAP_API_KEY . "&markers=color:blue|label:|" . urlencode($address);
                                    $map_anchor = '<a href = "https://maps.google.com/maps?z=12&t=m&q=' . urlencode($address) . '"><img src = "' . $map_url . '" alt = "No Map Found!" ></a>';
                                    $show_map = '<p><b>Address:</b> ' . $address . ' </p>';
                                    $show_map .= '<p> ' . $map_anchor . ' </p>';
                                    echo $show_map;
                                }
                                ?>
                                </h4>


                            <!-- <figure><i class="fa fa-calendar-check-o fa-5x"></i></figure>-->
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-left">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right">
                                <figure><i class="fa fa-calendar-check-o fa-5x"></i></figure>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-left">
                                <div class="text">
                                    <?php if (!empty($timings)) { ?>
                                        <div class="text">
                                            <i class="fa fa-clock-o"></i> <b><?php echo $timings[0]['title']; ?></b>, <?php echo DateTime::createFromFormat('H:i:s', $timings[0]['start_time'])->format('h:i a') . ' - ' . DateTime::createFromFormat('H:i:s', $timings[0]['end_time'])->format('h:i a'); ?>
                                        </div>
                                    <?php               } ?>
                                    <?php if ($custom_office_timings): ?>
                                        <?php foreach($custom_office_timings as $tim):?>
                                         <div class="text">
                                            <i class="fa fa-clock-o"></i> <b><?php echo $tim['hour_title']; ?></b>, <?php echo DateTime::createFromFormat('H:i:s', $tim['hour_start_time'])->format('h:i a') . ' - ' . DateTime::createFromFormat('H:i:s', $tim['hour_end_time'])->format('h:i a'); ?>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($locations)) { ?>
                                        <div class="text">
                                            <i class="fa fa-map-marker"></i>
                                            <b><?php echo $locations[0]['location_title']; ?></b>, <b><?php echo $locations[0]['location_address']; ?></b>
                                        </div>
                                        <?php $phone = isset($locations[0]['location_telephone']) && !empty($locations[0]['location_telephone']) ? $locations[0]['location_telephone'] : '' ?>
                                        <div class="text">
                                            <i class="fa fa-phone"></i>
                                            <b>Office Contact No: </b><?php echo $phone; ?>
                                        </div>
                                        <div class="text">
                                            <i class="fa fa-fax"></i>
                                            <b>Office Fax No: </b><?php echo $phone; ?>
                                        </div>
                                    <?php               } else { ?>
                                        <div class="text">
                                            <i class="fa fa-fax"></i>
                                          <b>  <?php echo $company_info['Location_Address']; ?></b>
                                        </div>
                                    <?php               } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (sizeof($locations) > 1) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="full-width applicant-bio">
                        <div class="joining-date">
                            <h2 class="text-blue">Other Addresses</h2>
                            <?php for ($i = 1; $i < sizeof($locations); $i++) { ?>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 text-right">
                                        <figure><i class="fa fa-calendar-check-o fa-3x" style="font-size: 90px;"></i></figure>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 text-left">
                                        <div class="text">
                                            <div class="text">
                                                <i class="fa fa-map-marker"></i>
                                                <b><?php echo $locations[$i]['location_title']; ?></b>, <?php echo $locations[$i]['location_address']; ?>
                                            </div>
                                            <div class="text">
                                                <i class="fa fa-phone"></i>
                                                <b>Office Contact No: </b><?php echo isset($locations[$i]['location_telephone']) ? $locations[$i]['location_telephone'] : $locations[$i]['location_phone']; ?>
                                            </div>
                                            <div class="text">
                                                <i class="fa fa-fax"></i>
                                                <b>Office Fax No: </b><?php echo $locations[$i]['location_fax']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php   }
        if (!empty($people)) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-blue">Your Colleagues</h2>
                </div>
                <div class="full-width applicant-bio">
                    <?php foreach ($people as $person) { ?>
                        <?php if (!empty($person['employee_info'])) { ?>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="applicant-details-info">
                                    <article class="people-to-contact full-width">
                                        <figure>
                                            <?php if (isset($person['employee_info']['profile_picture']) && !empty($person['employee_info']['profile_picture'])) { ?>
                                                <a href="<?php echo base_url('onboarding/colleague_profile/' . $unique_sid . '/' . $person['employee_info']['sid']); ?>" style="color:#000;"><img src="<?php echo AWS_S3_BUCKET_URL . $person['employee_info']['profile_picture']; ?>"></a>
                                            <?php                               } else { ?>
                                                <a href="<?php echo base_url('onboarding/colleague_profile/' . $unique_sid . '/' . $person['employee_info']['sid']); ?>" style="color:#000;"><span><?php echo substr(ucwords($person['employee_info']['first_name']), 0, 1) . substr(ucwords($person['employee_info']['last_name']), 0, 1); ?></span></a>
                                            <?php                               } ?>
                                        </figure>
                                        <div class="text">
                                            <h4><a href="<?php echo base_url('onboarding/colleague_profile/' . $unique_sid . '/' . $person['employee_info']['sid']); ?>" style="color:#000;"><?php echo ucwords($person['employee_info']['first_name']) . '&nbsp;' . ucwords($person['employee_info']['last_name']); ?></a></h4>
                                            <p class="text-muted">
                                                <i class="fa fa-envelope"></i> <a href="mailto:<?php echo $person['employee_info']['email']; ?>"><?php echo $person['employee_info']['email']; ?></a><br />
                                                <i class="fa fa-phone"></i> <?php echo $person['employee_info']['PhoneNumber']; ?>
                                            </p>
                                        </div>
                                    </article>
                                </div>
                            </div>
                    <?php            }
                    }   ?>
                </div>

            </div>
        <?php   } ?>
        <div class="row">
            <div class="col-lg-12">
                <?php if (!empty($links) || !empty($custom_useful_link)) { ?>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="widget-box plane-widget">
                            <h3 class="text-blue">Helpful Links</h3>
                            <ul class="quick-links">
                                <?php foreach ($links as $link) { ?>
                                    <?php if (!empty($link['link_url'])) { ?>
                                        <li>
                                            <a target="_blank" href="<?php echo $link['link_url']; ?>"><?php echo $link['link_title']; ?></a>
                                            <p><?php echo $link['link_description']; ?></p>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="javascript:;"><?php echo $link['link_title']; ?></a>
                                            <p><?php echo $link['link_description']; ?></p>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <?php foreach ($custom_useful_link as $link) { ?>
                                    <?php if (!empty($link['link_url'])) { ?>
                                        <li>
                                            <a target="_blank" href="<?php echo $link['link_url']; ?>"><?php echo $link['link_title']; ?></a>
                                            <p><?php echo $link['link_description']; ?></p>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="javascript:;"><?php echo $link['link_title']; ?></a>
                                            <p><?php echo $link['link_description']; ?></p>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if (!empty($items)) { ?>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="widget-box plane-widget">
                            <h3 class="text-blue">Items To Bring</h3>
                            <ul class="quick-links list-with-icons">
                                <?php foreach ($items as $item) { ?>
                                    <li>
                                        <strong><?php echo $item['item_title']; ?></strong>
                                        <div><?php echo $item['item_description']; ?></div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <div class="btn-panel text-right">
                    <a href="<?= base_url('onboarding/e_signature/' . $unique_sid) ?>" class="btn btn-success" id="go_next"> Let's Start <i class="fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>