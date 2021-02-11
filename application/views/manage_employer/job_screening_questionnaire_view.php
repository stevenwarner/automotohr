<?php $this->load->view('main/static_header'); ?>

<body>
    <?php   if($status == 'answered') { ?>
                <div class="main-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="end-user-agreement-wrp">
                                    <div class="thankyou-page-wrp">
                                        <div class="thanks-page-icon">
                                            <div class="icon-circle"><i class="fa fa-check"></i></div>
                                        </div>
                                        <div class="thank-you-text">
                                            <h1>THANK YOU</h1>
                                            <span>
                                                You Have successfully answered the questionnaire.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php   }  else if($status == 'not_found') { ?>
                <div class="main-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="credit-card-authorization">
                                    <div class="top-logo text-center">
                                        <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                                    </div>
                                    <span class="page-heading down-arrow"><?php echo $page_title; ?></span>
                                    <div class="hr-box">
                                        <div class="col-lg-12">
                                            <div class="recurring-payment-text-area">
                                                <p>&nbsp;</p>
                                                <p>You have successfully applied to the following position: </p>
                                                <h2><?php echo $job_title; ?></h2>
                                                <p>We will get in touch with you.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php   } else { ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="credit-card-authorization">
                        <div class="top-logo text-center cs_img_setting">
                          <?php 
                            if(empty($logo_info[0]['logo']))  echo $logo_info[0]['CompanyName'] ;
                            else echo ' <img src="'.( AWS_S3_BUCKET_URL . $logo_info[0]['logo']).'" style="width:200px;">';
                          ?>
                        </div>
                        <div class="panel panel-default full-width">
                            <div class="page-heading down-arrow"><?php echo $page_title; ?></div>
                            <div class="panel-body full-width">
                                <div class="recurring-payment-text-area">
                                    <p>You have successfully applied to the following position: </p>
                                    <h2><?php echo $job_title; ?></h2>
                                    <p>Please complete the following Questionnaire to enhance your chances to be short listed</p>
                                </div>
                                <h2 class="credit-card-form-heading">Questionnaire</h2>
                                <div class="form-wrp">
                                    <form id="job_screen_view" action="<?php echo base_url('Job_screening_questionnaire' . '/' . $verification_key); ?>" method="post" enctype="multipart/form-data">
                                        <input type='hidden' name="q_name" value="<?php echo $job_details['q_name']; ?>">
                                        <input type='hidden' name="q_passing" value="<?php echo $job_details['q_passing']; ?>">
                                        <input type='hidden' name="q_send_pass" value="<?php echo $job_details['q_send_pass']; ?>">
                                        <input type='hidden' name="q_pass_text" value="<?php echo $job_details['q_pass_text']; ?>">
                                        <input type='hidden' name="q_send_fail" value="<?php echo $job_details['q_send_fail']; ?>">
                                        <input type='hidden' name="q_fail_text" value="<?php echo $job_details['q_fail_text']; ?>">
                                        <input type='hidden' name="my_id" value="<?php echo $job_details['my_id']; ?>">

                                        <?php $my_id = $job_details['my_id'];
                                        foreach ($job_details[$my_id] as $questions_list) { ?>
                                            <input type="hidden" name="all_questions_ids[]" value="<?php echo $questions_list['questions_sid']; ?>">
                                            <input type="hidden" name="caption<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['caption']; ?>">
                                            <input type="hidden" name="type<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['question_type']; ?>">

                                            <div class="form-group autoheight">
                                                <label><?php echo $questions_list['caption']; ?>: <?php if ($questions_list['is_required'] == 1) { ?><samp class="red"> * </samp><?php } ?></label>
                                                <div class="row">
                                                    <?php if ($questions_list['question_type'] == 'string') { ?>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <input type="text" class="form-control" name="string<?php echo $questions_list['questions_sid']; ?>" placeholder="<?php echo $questions_list['caption']; ?>" value="" <?php if ($questions_list['is_required'] == 1) { ?> required data-rule-required="true" <?php } ?>>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($questions_list['question_type'] == 'boolean') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                        <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                <div class="form-group autoheight">
                                                                    <label class="control control--radio">
                                                                        <?php echo $answer_list['value']; ?>
                                                                        <input type="radio" name="boolean<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required data-rule-required="true" <?php } ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                            <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                            <?php $iterate = 0; ?>
                                                            <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--checkbox">
                                                                            <?php   echo $answer_list['value']; ?>
                                                                            <input type="checkbox" name="multilist<?php echo $questions_list['questions_sid']; ?>[]" id="squared<?php echo $iterate; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> data-rule-required="true" <?php } ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($questions_list['question_type'] == 'list') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="select">
                                                                <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-control" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                                    <option value="">-- Please Select --</option>
                                                                    <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                        <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>"> <?php echo $answer_list['value']; ?></option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group autoheight">
                                            <input type="hidden" name="job_sid" id="job_sid" value="<?php echo $job_sid; ?>">
                                            <input type="hidden" name="questionnaire_sid" id="questionnaire_sid" value="<?php echo $questionnaire_sid; ?>">
                                            <input type="hidden" name="action" value="job_applicant">
                                            <input class="btn btn-success" type="submit" value="Submit">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <script>
        $(document).ready(function () {
            $('#job_screen_view').validate();
        });
    </script>
    <style>
    .cs_img_setting{
        padding:20px;
        font-size: 40px;
        font-weight: bold;
    }
    </style>
</body>
</html>