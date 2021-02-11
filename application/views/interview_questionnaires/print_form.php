<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo STORE_NAME; ?>: <?= $title ?></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
</head>
<body>
<div class="hr-box candidate-interview-evaluation">
    <div class="page-header-area">
        <span class="page-heading down-arrow"><?php echo $title; ?></span>
    </div>
    <div class="info-text">Please select the sections you want to print and press <b>Print Interview Questionnaire</b></div>
    <div class="row custom-btns">
        <div class="col-xs-4"></div>
        <div class="col-xs-4">
            <a class="btn btn-success btn-block" onclick="func_print();" href="javascript:;">Print Interview Questionnaire</a>
        </div>
        <div class="col-xs-4"></div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12"></div>
    </div>
    <div class="hr-box">
        <div class="hr-box-header">
            <strong>Applicant Information</strong>
        </div>
        <div class="hr-innerpadding">
            <div class="row">
                <div class="col-xs-9">
                    <div class="table-responsive hr-innerpadding">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                            <tr>
                                <th class="col-xs-2">Full Name</th>
                                <td><?php echo $employer['first_name'] . ' ' . $employer['last_name']; ?></td>
                            </tr>
                            <tr>
                                <th class="col-xs-2">Address</th>
                                <td>
                                    <span><?php echo ($employer['address'] != '' ? $employer['address'] . ', ' : '') ; ?></span>
                                    <span><?php echo ($employer['city'] != '' ? $employer['city'] . ', ' : '') ; ?></span>
                                    <span><?php echo ($employer['zipcode'] != '' ? $employer['zipcode'] . ', '  : '') ; ?></span>
                                    <?php $state_info = db_get_state_name($employer['state']); ?>
                                    <?php if(!empty($state_info)) { ?>
                                        <span><?php echo $state_info['state_name'] . ', ' ; ?></span>
                                        <span><?php echo $state_info['country_name']; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="col-xs-2">Email</th>
                                <td><?php echo strtolower($employer['email']) ; ?></td>
                            </tr>
                            <tr>
                                <th class="col-xs-2">Phone</th>
                                <td><?php echo  $employer['phone_number']; ?></td>
                            </tr>
                            <tr>
                                <th class="col-xs-2">Job Title</th>
                                <td><?php echo isset($job_info['Title']) ? $job_info['Title'] : $employer['desired_job_title']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="img-thumbnail" style="margin-top:10px">
                        <?php if(!empty($employer['pictures'])) { ?>
                            <img src="<?php echo AWS_S3_BUCKET_URL . $employer['pictures']; ?>" width="180px;" class="img-responsive img-rounded" />
                        <?php } else { ?>
                            <img src="<?php echo base_url('assets/images/default_pic.jpg'); ?>" width="180px;" class="img-responsive img-rounded" />
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hr-box">
            <div class="hr-box-header">
                <strong>Questionnaire Information</strong>
            </div>
            <div class="hr-innerpadding">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive hr-innerpadding">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                <tr>
                                    <th class="col-xs-2">Name</th>
                                    <td><?php echo $questionnaire['title']; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-xs-2">Short Description</th>
                                    <td><?php echo $questionnaire['short_description'] ; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php $questionnaire_sections = $questionnaire['sections']; ?>
    <?php if(!empty($questionnaire_sections)) { ?>
    <?php $total_sections =  count($questionnaire_sections); ?>
    <?php //if($total_sections > 1) { ?>
            <div class="hr-box-header">
                <strong>Interview Questionnaire Sections</strong>                  
            </div>       
        <?php foreach($questionnaire_sections as $key => $questionnaire_section) { ?>               
            <div class="table-responsive hr-innerpadding" id="print_<?php echo $questionnaire_section['sid']; ?>">
                <table class="table table-bordered table-hover table-striped">            
                    <tbody>
                        <tr>
                            <th class="col-xs-2">Section Title</th>
                            <td><input type="checkbox" name="print_section" checked="checked" value="<?php echo $questionnaire_section['sid']; ?>"> <?php echo $questionnaire_section['title']; ?></td>
                        </tr>
                        <tr>
                            <th class="col-xs-2">&nbsp;</th>
                            <td class="col-xs-10">
                                <?php $candidate_questions = $questionnaire_section['candidate_questions'];
                                foreach ($candidate_questions as $candidate_question) {
                                    echo '<p><strong>Question: </strong>' . $candidate_question['question_text'] . '</p>';
                                    $answer_type = $candidate_question['answer_type'];
                                    switch ($answer_type) {
                                        case 'textual':
                                            echo '<p><strong>Answer Choice: </strong>Please write your feedback<br>';
                                            echo '<textarea rows="8" class="form-control"></textarea>';
                                            break;
                                        case 'mca_s':
                                            echo '<p><strong>Answer Choice: </strong>Please select one option<br>';
                                            $answer_options = $candidate_question['answer_options'];
                                            if (!empty($answer_options)) {
                                                foreach ($answer_options as $answer_required) { ?>
                                                    <input type="radio" value=""> <?php echo $answer_required; ?><br>
                                                <?php }
                                            }
                                            break;
                                        case 'mca_m':
                                            echo '<p><strong>Answer Choice: </strong>Multiple select choices<br>';
                                            $answer_options = $candidate_question['answer_options'];
                                            if (!empty($answer_options)) {
                                                foreach ($answer_options as $answer_required) { ?>
                                                    <input type="checkbox" value=""> <?php echo $answer_required; ?><br>
                                                <?php }
                                            }
                                            break;
                                        default:
                                            echo '<textarea rows="2" cols="100">Answer is not required!</textarea>';
                                            break;
                                    }
                                    echo '</p>';
                                } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>            
        <?php } ?>
        <hr />
        <div class="row custom-btns">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <div class="custom-btns">
                    <a class="btn btn-success btn-block" onclick="func_print();" href="javascript:;">Print Interview Questionnaire</a>
                </div>
            </div>
            <div class="col-xs-4"></div>
        </div>
        <br/>

    <?php //} // end of count?>
    <?php } else { ?>
        <div class="row ">
            <div class="col-xs-12 text-center">
                <span class="no-data">No Sections Defined!</span>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    function func_print(){
        var total_selected = $('input[name="print_section"]:checked').length;
        if (total_selected <= 0) {
            $('.custom-btns').hide();
            $('.info-text').hide();
            $('.down-arrow').html('Interview Questionnaire');

            printings();
        } else {
            $('input[name="print_section"]').each(function () {
                if ($(this).is(':checked')) {
                    // Let it print 
                } else {
                    var container = '#print_' + $(this).val();
                    $(container).hide();
                }
            });

            $('.custom-btns').hide();
            $('.info-text').hide();
            $('.down-arrow').html('Interview Questionnaire');
            printings();
        }
        
    }
    function printings() { 
        window.print(); 
        window.close(); 
    }
</script>