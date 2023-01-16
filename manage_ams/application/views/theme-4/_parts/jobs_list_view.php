<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="advance-search custom_search_bgcolor">
                    <form method="get" action="">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <input id="keyword" name="keyword" type="text"<?php if(isset($search_params)){ echo 'value="' . urldecode($search_params['keyword']) . '"'; } ?> class="input-field search-form-field" placeholder="Keywords">
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <div class="hr-select-dropdown search-form-field">
                                        <select id="category" name="category" class="input-field" name="JobType">
                                            <option value="">Any Category</option>
                        <?php               if(!empty($categories_in_active_jobs)) {   
                                                foreach($categories_in_active_jobs as $job_cat_key => $job_category){ ?>
                                                    <?php if($job_category == '') continue; ?>
                                                    <option <?php if(isset($search_params)){ if($job_cat_key == $search_params['category']){ echo 'selected="selected"'; } } ?>  value="<?php echo $job_cat_key; ?>" data-name="<?php echo $job_category; ?>"><?php echo $job_category; ?></option>
                        <?php                   } 
                                           } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <a href="<?php echo base_url('/jobs/'); ?>" id="btn-clear-search" name="btn-clear-search" class="input-field form-btn theme4_search_btn">Clear Search</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <div class="hr-select-dropdown">
                                        <select class="input-field search-form-field" id="search_section_country" name="search_section_country" onchange="getStatesForSearch(this.value, <?php echo $states; ?>);" >
                                            <option value="">Select Country</option>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <option <?php if(isset($search_params)){ if($active_country['sid'] == $search_params['country']){ echo 'selected="selected"'; } } ?>   value="<?php echo $active_country['sid']; ?>">
                                                    <?php echo $active_country["country_name"]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <div class="hr-select-dropdown">
                                        <select class="input-field search-form-field" id="search_section_state" name="search_section_state">
                                            <option value="">Select State</option>
                                            <option value="">Please Select your country</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <div class="search-form-field">
                                        <input <?php if(isset($search_params)){ echo 'value="' . urldecode($search_params['city']) . '"'; } ?>  id="city" name="city" type="text" class="input-field search-form-field" placeholder="City">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <a href="" id="btn-search" name="btn-search" class="input-field form-btn theme4_search_btn">Search</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                </div>
                <div class="job-listing">
                    <header class="heading-title">
                        <div class="pull-left join-our-telentnetwork col-lg-4 col-md-4 col-xs-12 col-sm-4">
                            <h1 class="section-title">All<span>Jobs</span></h1>
                        </div>
                        <div class="pull-right join-our-telentnetwork col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                <a href="https://auto.careers/signup" target="_blank" class="btn site-btn bg-color" style="font-size: 20px">Join Our Talent Network</a>
                        </div>
                    </header>
                    <?php 
                        if(!empty($featured_jobs)) { 
                            foreach($featured_jobs as $job) { ?>              
                            <?php   if (empty($job['pictures']) && !empty($job['Logo'])) { ?>
                            <?php       $image = AWS_S3_BUCKET_URL . $job['Logo']; ?>
                            <?php   } elseif (!empty($job['pictures'])) { ?>
                            <?php       $image = AWS_S3_BUCKET_URL . $job['pictures']; ?>
                            <?php   } else {
                                        $image = AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE;
                                    } ?>

                            <!-- Job *** START *** -->
                            <article class="article-list">
                            <figure> <img class="img-responsive" alt="<?php echo $job['CompanyName']; ?> Logo" src="<?php echo $image; ?>"> </figure>
                            
                            <div class="text">
                                <div class="title-area">
                                    <h2 class="post-title"><a id="job_title<?php echo $job['sid']; ?>" href="<?= base_url(job_title_uri($job)) ?>"><?php echo $job['Title'];?></a></h2>
                                    <div class="post-option">
                                        <ul>
                                            <li>
                                                <i class="locations_color fa fa-map-marker"></i>
                                                <div class="op-text">
                                                    
                                        <?php   if (!empty($job['Location_City'])) {
                                                        echo $job['Location_City'] . ', ';
                                                    }
                                                    
                                                if (!empty($job['Location_State'])) {
                                                        echo $job['Location_State'] . ', ';
                                                    }
                                                    echo $job['Location_Country']; ?>
                                                </div>
                                            </li>
                                            <li>
                                                <i class="locations_color fa fa-suitcase"></i>
                                                <div class="op-text"><?php echo $job['JobCategory']; ?></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="btn-area">
                                    <ul>
                                        <li><button type="button" class="site-btn bg-color" data-sm="<?=$job['sms_module_status'];?>" onclick="show_popup(<?php echo $job['sid'] ?>, <?=$job['sms_module_status'];?>)">Apply Now</button></li>
                                        <li><a href="<?= base_url(job_title_uri($job)) ?>" class="site-btn bg-color-v2">View Details</a></li>
                                        <a style="display:none;" id="show_hide<?php echo $job['sid'] ?>" data-toggle="modal" data-target="#myModal">&nbsp;</a>
                                    </ul>
                                </div>
                            </div>
                        </article>
                            <!-- Job *** END *** -->
                            <!-- screening questionnaire data *** START *** -->
                            <p id="questionnaire_sid<?php echo $job['sid']; ?>" style="display:none;"><?php echo $job['questionnaire_sid']; ?></p>
                            <div style="display:none" id="questions<?php echo $job['sid']; ?>">
                                <label>Attach Resume (.pdf .docx .doc .jpg .jpe .jpeg .png .gif) Attach Cover (.pdf .docx .doc .jpg .jpe .jpeg .png .gif)</label>
                                <?php if($job['questionnaire_sid'] > 0) { 
                                        $q_send_pass = '';
                                        
                                        if(isset($job['q_send_pass'])) {
                                            $q_send_pass = $job['q_send_pass'];
                                        }
                                        
                                        $q_send_fail = '';
                                        
                                        if(isset($job['q_send_fail'])) {
                                            $q_send_fail = $job['q_send_fail'];
                                        }
                                        
                                        $q_fail_text = '';
                                        
                                        if(isset($job['q_fail_text'])) {
                                            $q_send_fail = $job['q_fail_text'];
                                        }
                                        
                                        $q_pass_text = '';
                                        
                                        if(isset($job['q_pass_text'])) {
                                            $q_pass_text = $job['q_pass_text'];
                                        }
                                        
                                        $my_id = '';
                                        
                                        if(isset($job['my_id'])) {
                                            $my_id = $job['my_id'];
                                        } ?>

                                    <div class="wrap-container">
                                        <div class="wrap-inner">
                                            <h2 class="post-title">Questionnaire</h2>
                                            <input type='hidden' name="q_name" value="<?php echo $job['q_name']; ?>">
                                            <input type='hidden' name="q_passing" value="<?php echo $job['q_passing']; ?>">
                                            <input type='hidden' name="q_send_pass" value="<?php echo $q_send_pass; ?>">
                                            <input type='hidden' name="q_pass_text" value="<?php echo $q_pass_text; ?>">
                                            <input type='hidden' name="q_send_fail" value="<?php echo $q_send_fail; ?>">
                                            <input type='hidden' name="q_fail_text" value="<?php echo $q_fail_text; ?>">
                                            <input type='hidden' name="my_id" value="<?php echo $my_id; ?>">
                                <?php       //$my_id = $job['my_id'];
                                            if(isset($job[$my_id])) {
                                            foreach ($job[$my_id] as $questions_list) { ?>
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
                                                        <?php foreach ($job[$answer_key] as $answer_list) { ?>
                                                            <input type="radio" name="boolean<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>> <?php echo $answer_list['value']; ?>&nbsp;
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php if ($questions_list['question_type'] == 'list') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                        <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-fields" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                            <option value="">-- Please Select --</option>
                                                            <?php foreach ($job[$answer_key] as $answer_list) { ?>
                                                                <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>"> <?php echo $answer_list['value']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                    <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                    <div class="checkbox-wrap">
                                                        <?php $iterate = 0; ?>
                                                        <?php foreach ($job[$answer_key] as $answer_list) { ?>
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
                                            <?php } 
                                            } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- screening questionnaire data ***  END *** -->
                    <?php   }
                    }
                    if(!empty($job_listings)) { 
                            foreach($job_listings as $job) { ?>              
                            <?php   if (empty($job['pictures']) && !empty($job['Logo'])) { ?>
                            <?php       $image = AWS_S3_BUCKET_URL . $job['Logo']; ?>
                            <?php   } elseif (!empty($job['pictures'])) { ?>
                            <?php       $image = AWS_S3_BUCKET_URL . $job['pictures']; ?>
                            <?php   } else {
                                        $image = AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE;
                                    } ?>

                            <article class="article-list">
                            <figure> <img class="img-responsive" alt="Company Logo" src="<?php echo $image; ?>"> </figure>
                            
                            <div class="text">
                                <div class="title-area">
                                    <h2 class="post-title"><a id="job_title<?php echo $job['sid']; ?>" href="<?= base_url(job_title_uri($job)) ?>"><?php echo $job['Title'];?></a></h2>
                                    <div class="post-option">
                                        <ul>
                                            <li>
                                                <i class="locations_color fa fa-map-marker"></i>
                                                <div class="op-text">
                                                    
                                        <?php   if (!empty($job['Location_City'])) {
                                                        echo $job['Location_City'] . ', ';
                                                    }
                                                    
                                                if (!empty($job['Location_State'])) {
                                                        echo $job['Location_State'] . ', ';
                                                    }
                                                    echo $job['Location_Country']; ?>
                                                </div>
                                            </li>
                                            <li>
                                                <i class="locations_color fa fa-suitcase"></i>
                                                <div class="op-text"><?php echo $job['JobCategory']; ?></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="btn-area">
                                    <ul>
                                        <li><button type="button" class="site-btn bg-color" data-sm="<?=$job['sms_module_status'];?>"  onclick="show_popup(<?php echo $job['sid'] ?>, <?=$job['sms_module_status'];?>)">Apply Now</button></li>
                                        <li><a href="<?= base_url(job_title_uri($job)) ?>" class="site-btn bg-color-v2">View Details</a></li>
                                        <a style="display:none;" id="show_hide<?php echo $job['sid'] ?>" data-toggle="modal" data-target="#myModal">&nbsp;</a>
                                    </ul>
                                </div>
                            </div>
                        </article>

                            <!-- screening questionnaire data *** START *** -->
                            <p id="questionnaire_sid<?php echo $job['sid']; ?>" style="display:none;"><?php echo $job['questionnaire_sid']; ?></p>
                            <div style="display:none" id="questions<?php echo $job['sid']; ?>">
                                <label>Attach Resume (.pdf .docx .doc .jpg .jpe .jpeg .png .gif) Attach Cover (.pdf .docx .doc .jpg .jpe .jpeg .png .gif)</label>
                                <?php if($job['questionnaire_sid'] > 0) { 
                                        $q_send_pass = '';
                                        
                                        if(isset($job['q_send_pass'])) {
                                            $q_send_pass = $job['q_send_pass'];
                                        }
                                        
                                        $q_send_fail = '';
                                        
                                        if(isset($job['q_send_fail'])) {
                                            $q_send_fail = $job['q_send_fail'];
                                        }
                                        
                                        $q_fail_text = '';
                                        
                                        if(isset($job['q_fail_text'])) {
                                            $q_send_fail = $job['q_fail_text'];
                                        }
                                        
                                        $q_pass_text = '';
                                        
                                        if(isset($job['q_pass_text'])) {
                                            $q_pass_text = $job['q_pass_text'];
                                        }
                                        
                                        $my_id = '';
                                        
                                        if(isset($job['my_id'])) {
                                            $my_id = $job['my_id'];
                                        }
                                    ?>
                                    <div class="wrap-container">
                                        <div class="wrap-inner">
                                            <h2 class="post-title">Questionnaire</h2>
                                            <input type='hidden' name="q_name" value="<?php echo $job['q_name']; ?>">
                                            <input type='hidden' name="q_passing" value="<?php echo $job['q_passing']; ?>">
                                            <input type='hidden' name="q_send_pass" value="<?php echo $q_send_pass; ?>">
                                            <input type='hidden' name="q_pass_text" value="<?php echo $q_pass_text; ?>">
                                            <input type='hidden' name="q_send_fail" value="<?php echo $q_send_fail; ?>">
                                            <input type='hidden' name="q_fail_text" value="<?php echo $q_fail_text; ?>">
                                            <input type='hidden' name="my_id" value="<?php echo $my_id; ?>">
                                <?php       //$my_id = $job['my_id'];
                                            if(isset($job[$my_id])) {
                                            foreach ($job[$my_id] as $questions_list) { ?>
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
                                                        <?php foreach ($job[$answer_key] as $answer_list) { ?>
                                                            <input type="radio" name="boolean<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>> <?php echo $answer_list['value']; ?>&nbsp;
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php if ($questions_list['question_type'] == 'list') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                        <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-fields" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                            <option value="">-- Please Select --</option>
                                                            <?php foreach ($job[$answer_key] as $answer_list) { ?>
                                                                <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>"> <?php echo $answer_list['value']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                    <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                    <div class="checkbox-wrap">
                                                        <?php $iterate = 0; ?>
                                                        <?php foreach ($job[$answer_key] as $answer_list) { ?>
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
                                            <?php } 
                                            } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- screening questionnaire data ***  END *** -->
                    <?php   }  // end of foreach?>
                    <?php } 
                    if(empty($job_listings) && empty($featured_jobs)){ ?>
                        <article class="article-list">
                        <p>No Jobs Found.</p>
                        </article>
                    <?php } ?>

                    <div id="lazy_load">

                    </div>

                    <div style="vertical-align: top; display: inline-block;">
                        <div id="loader" class="loader" style="display: none">
                            <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                            <span>Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getStatesForSearch(val, states) {
        var html = '';
        
        if (val == '') {
            $('#search_section_state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#search_section_state').html(html);
        }
    }
    
    $(document).ready(function () {
       var $searchFormFields = $('.search-form-field');
        $searchFormFields.each(function () {
            $(this).on('keyup change', function () {
                var sKeyword = $('#keyword').val();
                var sCategory = $('#category').val();
                var sCountry = $('#search_section_country').val();
                var sState = $('#search_section_state').val();
                var sCity = $('#city').val();

                if(sKeyword == ''){
                    sKeyword = 'All';
                }

                if(sCategory == ''){
                    sCategory = 'All';
                }

                if(sCountry == ''){
                    sCountry = 'All';
                }

                if(sState == ''){
                    sState = 'All';
                }

                if(sCity == ''){
                    sCity = 'All';
                }

                var sUrl = '<?php echo base_url('/jobs/') ?>' + '/' + encodeURI(sCountry) + '/' + encodeURI(sState) + '/' + encodeURI(sCity) + '/' + encodeURI(sCategory) + '/'+ encodeURI(sKeyword) + '/';
                $('#btn-search').attr('href', sUrl);
                //console.log(sUrl);
            });
        });
        
        $('#search_section_country').trigger('change');
        setTimeout(function() {
            $('#search_section_state').val(<?php if(isset($search_params)){ echo $search_params['state']; } ?>);
        }, 1200);

        var iScrollPos = 0;
        var page = 2;
        var company_logo = '<?= $company_details['Logo']; ?>';
        var company_sid = '<?= $company_details['sid']; ?>';
        var total_calls = '<?= $total_calls; ?>';
//        console.log(total_calls);
        $(window).scroll(function() {
            var iCurScrollPos = $(this).scrollTop();
            var window_height = $(window).height();
            var window_half_height = Math.ceil($(window).height()/2);
            var window_third_height = Math.ceil($(window).height()/4);
            var current_height = $(window).scrollTop() + window_height + window_height;
            var min_height = $(document).height() - window_half_height;
            var max_height = $(document).height() - window_third_height;
//            console.log($(window).scrollTop() + window_height + window_half_height + ' = ' + $(window).scrollTop() + window_height + window_half_height + window_third_height + ' = ' + $(document).height());
//            console.log(current_height + ' = ' + min_height + ' = ' + max_height) ;
//            console.log(window_height + '=' + window_half_height + '=' + window_third_height + '=' + $(document).height());
            if(iCurScrollPos > iScrollPos && current_height >= min_height && current_height < max_height && page <= total_calls){
                $('#loader').show();
//                setTimeout(function(){
                    var sKeyword = $('#keyword').val();
                    var sCategory = $('#category').val();
                    var sCountry = $('#search_section_country').val();
                    var sState = $('#search_section_state').val();
                    var sCity = $('#city').val();

                    if(sKeyword == ''){
                        sKeyword = 'All';
                    }

                    if(sCategory == ''){
                        sCategory = 'All';
                    }

                    if(sCountry == ''){
                        sCountry = 'All';
                    }

                    if(sState == ''){
                        sState = 'All';
                    }

                    if(sCity == ''){
                        sCity = 'All';
                    }
                    
                    $.ajax({
                        type: 'GET',
                        async: false,
                        url: '<?php echo base_url('/jobs') ?>' + '/' + encodeURI(sCountry) + '/' + encodeURI(sState) + '/' + encodeURI(sCity) + '/' + encodeURI(sCategory) + '/' + encodeURI(sKeyword) + '/' + page++ + '/' + 1 ,
                        success: function(data) {
                            
                            data = JSON.parse(data);
//                            console.log(data);
                            $.each(data,function(index,object){
                                if((object['pictures'] == '' || object['pictures'] == null) && (object['Logo'] != '' || object['Logo'] != null)) {
                                    if(object['Logo'] != null) {
                                        var image = '<?php echo AWS_S3_BUCKET_URL; ?>' + object['Logo'];
                                    } else {
                                        var image = '<?php echo AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE; ?>';
                                    }
                                } else if (object['pictures'] != '' && object['pictures'] != null) {
                                    var image = '<?php echo AWS_S3_BUCKET_URL; ?>' + object['pictures'];
                                } else {
                                    var image = '<?php echo AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE; ?>';
                                }
                                
                                var city = '';
                                var state = '';
                                var country = '';
                                
                                if (object['Location_City']!= '') {
                                    var city = object['Location_City'] + ', ';
                                }

                                if (object['Location_State']!= '') {
                                    var state = object['Location_State'] + ', ';
                                }
                                
                                var country = object['Location_Country'];
                                var q_send_pass = '';
                                var q_send_fail = '';
                                var q_fail_text = '';
                                var q_pass_text = '';
                                var my_id = '';
                                var q_a_div = '';
                                
                                if(object['questionnaire_sid'] > 0) {

                                    if(object['q_send_pass'].length > 0) {
                                        q_send_pass = object['q_send_pass'];
                                    }

                                    if(object['q_send_fail'].length > 0) {
                                        q_send_fail = object['q_send_fail'];
                                    }

                                    if(object['q_fail_text'].length > 0) {
                                        q_fail_text = object['q_fail_text'];
                                    }

                                    if(object['q_pass_text'].length > 0) {
                                        q_pass_text = object['q_pass_text'];
                                    }

                                    if(object['my_id'].length > 0) {
                                        my_id = object['my_id'];
                                    }

                                    var q_a_div_loop = '';
                                    
                                    if(object[my_id].length > 0) {
                                        $.each(object[my_id] ,function(index,questions_list) {
                                            var estarik = '';
                                            var string_type = '';
                                            var boolean_type = '';
                                            var select_type = '';
                                            var multilist = '';
                                            var required = '';
                                            if(questions_list['is_required'] == 1){
                                                estarik = '<samp class="red"> * </samp>';
                                            }
                                            
                                            if (questions_list['is_required'] == 1) {
                                                required = 'required';
                                            }
                                            
                                            if (questions_list['question_type'] == 'string') {
                                                string_type = '<input type="text" class="form-fields" name="string'+questions_list['questions_sid']+'" placeholder="'+questions_list['caption']+'" value="" '+required+'>';
                                            }

                                            if (questions_list['question_type'] == 'boolean') {
                                                var answer_key = 'q_answer_' + questions_list['questions_sid'];
                                                 $.each(object[answer_key], function(index,answer_list) {
                                                        boolean_type = '<input type="radio" name="boolean'+questions_list['questions_sid']+'" value="'+answer_list['value']+' @#$ '+answer_list['score']+'" '+required+'> '+answer_list['value']+'&nbsp;';
                                                 });
                                            }
                                            
                                            var answer_key = 'q_answer_' + questions_list['questions_sid'];
                                            
                                            if (questions_list['question_type'] == 'list') {
                                                var options='';
                                                
                                                $.each(object[answer_key], function(index,answer_list) {
                                                    options = '<option value="'+answer_list['value']+' @#$ '+answer_list['score']+'"> '+answer_list['value']+'</option>';
                                                });
                                                
                                                select_type = '<select name="list'+questions_list['questions_sid']+'" class="form-fields" '+required+'><option value="">-- Please Select --</option>'+options+'</select>';
                                            }

                                            if (questions_list['question_type'] == 'multilist') {
                                                var multians = '';
                                                $.each(object[answer_key], function(index, answer_list) {
                                                    multians = '<div class="label-wrap"><div class="squared"><input type="checkbox" name="multilist'+questions_list['questions_sid']+'[]" id="squared'+index+'" value="'+answer_list['value']+' @#$ '+answer_list['score']+'"><label for="squared'+index+'"></label></div><span>'+answer_list['value']+'</span></div>';
                                                });
                                                
                                                multilist = '<div class="checkbox-wrap">'+multians+'</div>';
                                            }

                                        q_a_div_loop = '<input type="hidden" name="all_questions_ids[]" value="'+questions_list['questions_sid']+'"><input type="hidden" name="caption'+questions_list['questions_sid']+'" value="'+questions_list['caption']+'"><input type="hidden" name="type'+questions_list['questions_sid']+'" value="'+questions_list['question_type']+'"><p><label>'+questions_list['caption']+':'+estarik+'</label>'+string_type+boolean_type+select_type+multilist;

                                        });
                                    }

                                    q_a_div = '<div class="wrap-container"><div class="wrap-inner"><h2 class="post-title">Questionnaire</h2><input type="hidden" name="q_name" value="'+object['q_name']+'"><input type="hidden" name="q_passing" value="'+object['q_passing']+'"><input type="hidden" name="q_send_pass" value="'+q_send_pass+'"><input type="hidden" name="q_pass_text" value="'+q_pass_text+'"><input type="hidden" name="q_send_fail" value="'+q_send_fail+'"><input type="hidden" name="q_fail_text" value="'+q_fail_text+'"><input type="hidde" name="my_id" value="'+my_id+'">'+q_a_div_loop+'</div></div>';
                                }
                                
                                var append_div = '<article class="article-list"><figure><img class="img-responsive" alt="Logo" src="'+image+'"></figure><div class="text"><div class="title-area"><h2 class="post-title"><a id="job_title'+object['sid']+'" href="<?php echo base_url('/display-job');?>'+'/'+object['sid']+'/">'+object['Title']+'</a></h2><div class="post-option"><ul><li><i class="locations_color fa fa-map-marker"></i><div class="op-text">'+city+state+country+'</div></li><li><i class="locations_color fa fa-suitcase"></i><div class="op-text">'+object['JobCategory']+'</div></li></ul></div></div><div class="btn-area"><ul><li><button type="button" class="site-btn bg-color" data-sm="'+(object['sms_module_status'])+'"  onclick="show_popup('+"'"+object['sid']+"'"+', '+(object['sms_module_status'])+')">Apply Now</button></li><li><a href="<?php echo base_url('/display-job');?>'+'/'+object['sid']+'" class="site-btn bg-color-v2">View Details</a></li><a style="display:none;" id="show_hide'+object['sid']+'" data-toggle="modal" data-target="#myModal">&nbsp;</a></ul></div></div></article>';
                                var question_div = '<p id="questionnaire_sid'+object['sid']+'" style="display:none;">'+object['questionnaire_sid']+'</p><div style="display:none" id="questions'+object['sid']+'"><label>Attach Resume (.pdf .docx .doc .jpg .jpe .jpeg .png .gif) Attach Cover (.pdf .docx .doc .jpg .jpe .jpeg .png .gif)</label>'+q_a_div+'</div></p>';
                                $('#lazy_load').append(append_div+question_div);
                            });
                            $('#loader').hide();
//                            console.log(data);
                        },
                        error: function(){

                        }
                    });
//                }, 300);
            }
            iScrollPos = iCurScrollPos;
        });
    });
</script>