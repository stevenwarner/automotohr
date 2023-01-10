<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="container">
        <div class="row">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
            <div class="video-area">
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4"><h2 class="page-title">Latest Jobs</h2></div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
            <?php   if (!empty($company_details['YouTubeVideo'])) { ?>
                        <div class="header-video">
                            <?php $this->load->view('common/video_player_company_partial'); ?>
                        </div>
            <?php   } ?>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 join-our-talent">
                    <?php echo anchor("/join_our_talent_network", "Join Our Talent Network", array('class' => 'join-btn')); ?>
                </div>	
            </div>

            <div class="video-area text-center">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <?php       if($theme4_enable_job_fair_homepage == 1 && !empty($job_fairs)) { 
                        //
                        $jobFairs = explode(',', $job_fair_homepage_page_url);
                        //
                        foreach($jobFairs as $jf){
                            $fair_title = $job_fairs[$jf]['title'];  
                            $button_background_color = $job_fairs[$jf]['button_background_color'];   
                            $button_text_color = $job_fairs[$jf]['button_text_color']; 
                            ?>
                            <a href="<?php echo base_url('/job_fair').'/'.$jf; ?>" class="site-btn" style="background: <?=$button_background_color?>; color: <?=$button_text_color;?>">
                                <?php echo $fair_title; ?>
                            </a>
                            <?php
                        }?>

        <?php       } ?> 
                </div>   
            </div>
            
            <div class="col-md-12">
                <?php if (empty($job_listings)) { ?>
                    <div class="job-article-wrapper">
                        <article class="job-article">
                <?php   if (!empty($company_details['Logo'])) { ?>
                            <div class="icon-job">
                                <figure><img src="<?php echo AWS_S3_BUCKET_URL . $company_details['Logo']; ?>"></figure>
                            </div>
                <?php   } ?>
                            <div class="job-title">
                                <span class="title-no-jobs">
                                    <?php echo 'No Active Jobs Available at the moment!'; ?> 
                                </span>									
                            </div>		
                        </article>
                    </div>
                <?php } else {
                        foreach ($job_listings as $key => $value) {
                            if (empty($value['pictures']) && !empty($company_details['Logo'])) { 
                                $image = AWS_S3_BUCKET_URL . $company_details['Logo'];
                            } elseif(!empty($value['pictures'])){ 
                                $image = AWS_S3_BUCKET_URL . $value['pictures'];
                            } else { 
                                $company_logo = get_company_logo($value['user_sid']);

                                if(empty($company_logo)){
                                    $image = AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE;
                                } else {
                                    $image = AWS_S3_BUCKET_URL . $company_logo;
                                }
                            } ?>

                        <meta property="og:title" content="<?= $value['Title'] ?>" />
                        <meta property="og:site_name" content="<?= $value['JobCategory'] ?>"/>
                        <meta property="og:type" content="website" />
                        <meta property="og:url" content="<?= base_url() ?>job_details/<?= $value['sid'] ?>"/>
                        <meta property="og:image" content="<?= $image ?>" />
                        <meta itemprop="name" content="<?= $value['Title'] ?>">
                        <meta itemprop="description" content="<?= $value['JobCategory'] ?>">
                        <meta property="og:image" content="<?= $image ?>" />
                        <meta name="twitter:card" content="summary" />
                        <meta name="twitter:site" content="<?= base_url() ?>job_details/<?= $value['sid'] ?>" />
                        <meta name="twitter:title" content="<?= $value['Title'] ?>" />
                        <meta name="twitter:description" content="<?= $value['JobCategory'] ?>" />
                        <meta property="og:image" content="<?= $image ?>" />
                        <div class="job-article-wrapper">
                            <article class="job-article" id="full_time<?= $value['sid'] ?>">
                                <div class="icon-job">
                                    <figure>
                                        <?php 
                                            if (empty($value['pictures']) && !empty($company_details['Logo']) && ($value['user_sid']) == $company_details['sid']) { 
                                                if (get_company_logo_status($value['user_sid']) == 1) {
                                                    $image_com = AWS_S3_BUCKET_URL . $company_details['Logo'];
                                                    echo '<img src="'.$image_com.'">';
                                                }
                                            } elseif (!empty($value['pictures'])) {
                                                $image_com = AWS_S3_BUCKET_URL . $value['pictures'];
                                                echo '<img src="'.$image_com.'">';                                             
                                            } else {
                                                $company_logo = get_company_logo($value['user_sid']);

                                                if(empty($company_logo)){
                                                    $image_com = AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE;
                                                    echo '<img src="'.$image_com.'">';
                                                } else {
                                                    if (get_company_logo_status($value['user_sid']) == 1) {
                                                        $image_com = AWS_S3_BUCKET_URL . $company_logo;
                                                        echo '<img src="'.$image_com.'">';
                                                    }
                                                }
                                            } 
                                        ?>
                                    </figure>
                                </div>
                                <div class="job-title">
                                    <h2 class="post-title">
                                        <?php echo anchor(job_title_uri($value), $value['Title'], array('class' => 'color', 'id' => 'job_title' . $value['sid'])); ?> 
                                    </h2>
                                    <ul class="categories">
                                        <li><?php echo $value['JobCategory']; ?></li>
                                    </ul>
                                    <div class="social-media">
                            <?php       if(isset($value['share_links'])) { 
                                            echo $value['share_links']; 
                                        } ?>
                                    </div>
                                    <div class = "job-location">
                                        <ul>
                                            <?php if (!empty($value['Location_City']) || !empty($value['Location_State']) || !empty($value['Location_Country'])) { ?>
                                                <li>
                                                    <i class = "color fa fa-map-marker"></i>
                                                    <div class = "text"><?php if(!empty($value['Location_City'])) {
                                                                                echo $value['Location_City'].', ';
                                                                            }
                                                                            if (!empty($value['Location_State'])) {
                                                                                echo $value['Location_State'].', ';
                                                                            }   echo $value['Location_Country']; ?>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>	
                                </div>							
                                <div class="show-job">
                                    <a class="siteBtn apply-now-btn" href="javascript:void(0)" onclick="show_popup(<?= $value['sid'] ?>,<?php echo get_company_sms_status($value['user_sid'])?>)">apply now</a>
                                    <a style="display:none;" id="show_hide<?= $value['sid'] ?>" data-toggle="modal" data-target="#myModal">&nbsp;</a>
                                    <a class="siteBtn showDetail" href="javascript:void(0)" id="show_full_time<?= $value['sid'] ?>">show details</a>
                                </div>
                                <div class="description-wrapper">
                                    <div class="job-description">
                                        <h2 class="post-title color">job description</h2>
                                        <?php echo $value['JobDescription']; ?>
                                    </div>
                                    <?php if (!empty($value['JobRequirements'])) { ?>
                                        <div class="job-description">
                                            <h2 class="post-title color">job requirement</h2>
                                            <?php echo $value['JobRequirements']; ?>
                                        </div>
                                    <?php } ?>
                                    <p id="questionnaire_sid<?php echo $value['sid']; ?>" style="display:none;"><?php echo $value['questionnaire_sid']; ?></p> 
                                    <div style="display:none" id="questions<?php echo $value['sid']; ?>"> 
                                        <label>Attach Resume (.pdf .docx .doc .jpg .jpe .jpeg .png .gif) Attach Cover (.pdf .docx .doc .jpg .jpe .jpeg .png .gif)</label>
                                        <?php if ($value['questionnaire_sid'] > 0) { ?>
                                            <div class="wrap-container">
                                                <div class="wrap-inner">
                                                    <h2 class="post-title color">Questionnaire</h2>
                                                    <input type='hidden' name="q_name" value="<?php echo $value['q_name']; ?>">
                                                    <input type='hidden' name="q_passing" value="<?php echo $value['q_passing']; ?>">
                                                    <input type='hidden' name="q_send_pass" value="<?php echo $value['q_send_pass']; ?>">
                                                    <input type='hidden' name="q_pass_text" value="<?php echo $value['q_pass_text']; ?>">
                                                    <input type='hidden' name="q_send_fail" value="<?php echo $value['q_send_fail']; ?>">
                                                    <input type='hidden' name="q_fail_text" value="<?php echo $value['q_fail_text']; ?>">
                                                    <input type='hidden' name="my_id" value="<?php echo $value['my_id']; ?>">
                                                    <?php $my_id = $value['my_id'];
                                                          foreach ($value[$my_id] as $questions_list) { ?>
                                                        <input type="hidden" name="all_questions_ids[]" value="<?php echo $questions_list['questions_sid']; ?>">
                                                        <input type="hidden" name="caption<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['caption']; ?>">
                                                        <input type="hidden" name="type<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['question_type']; ?>">
                                                        <p>
                                                            <label><?php echo $questions_list['caption']; ?>: <?php if ($questions_list['is_required'] == 1) { ?><samp class="red"> * </samp><?php } ?></label>
                                                            <?php if ($questions_list['question_type'] == 'string') { ?>
                                                                <input type="text" class="form-fields" name="string<?php echo $questions_list['questions_sid']; ?>" placeholder="<?php echo $questions_list['caption']; ?>" value="" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                            <?php } ?>
                                                            <?php if ($questions_list['question_type'] == 'boolean') { ?>
                                                                <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                <?php foreach ($value[$answer_key] as $answer_list) { ?>
                                                                    <input type="radio" name="boolean<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>> <?php echo $answer_list['value']; ?>&nbsp; 
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <?php if ($questions_list['question_type'] == 'list') { ?>
                                                                <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-fields" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                                    <option value="">-- Please Select --</option>
                                                                    <?php foreach ($value[$answer_key] as $answer_list) { ?>
                                                                        <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>"> <?php echo $answer_list['value']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } ?>
                                                            <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                                <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                            <div class="checkbox-wrap">
                                                                <?php $iterate = 0; ?>
                                                                <?php foreach ($value[$answer_key] as $answer_list) { ?>
                                                                    <div class="label-wrap">
                                                                        <div class="squared">
                                                                            <input type="checkbox" name="multilist<?php echo $questions_list['questions_sid']; ?>[]" id="squared<?php echo $iterate; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>">
                                                                            <label for="squared<?php echo $iterate; ?>"></label>
                                                                        </div>
                                                                        <span><?php echo $answer_list['value']; ?></span>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>
                                            </div> 
                                        <?php } ?> 
                                    </div>
                                    <div class="btn-wrap">
                                    <?php echo anchor(job_title_uri($value), "more details", array('class' => 'siteBtn btn-deatil-more')); ?> 
                                        <a class="siteBtn apply-now-btn" href="javascript:void(0)" onclick="show_popup(<?= $value['sid'] ?>,<?php echo get_company_sms_status($value['user_sid'])?>)">apply now</a>
                                        <a style="display:none;" id="show_hide<?= $value['sid'] ?>" data-toggle="modal" data-target="#myModal">&nbsp;</a>
                                        <a class="siteBtn hideDetail btn-hide" href="javascript:void(0)" id="hide_full_time<?= $value['sid'] ?>">hide details</a>
                                    </div>									
                                </div>
                            </article>
                        </div>	
                        <script>
                            $(document).ready(function () {
                                $('#show_full_time' +<?= $value['sid'] ?>).click(function () {
                                    $('#full_time' +<?= $value['sid'] ?>).addClass("add_bg");
                                });
                                $('#hide_full_time' +<?= $value['sid'] ?>).click(function () {
                                    $('#full_time' +<?= $value['sid'] ?>).removeClass("add_bg");
                                });
                            });
                        </script>
                    <?php } ?>
                <?php } ?>
                <div class="Pagination">
                    <?php //= $links ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('/common/apply_now_modal_for_index'); ?>
