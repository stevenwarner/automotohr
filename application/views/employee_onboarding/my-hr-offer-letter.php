<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/style.css">
<!--        <link rel="stylesheet" type="text/css" href="--><?php //echo base_url('assets/employee_onboarding') ?><!--/css/style-tabs.css">-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/responsive.css">
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/functions.js"></script>
        <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" /> 
    </head>
    <body>
        <!-- Wrapper Start -->
        <div class="wrapper">
            <!-- Header Start -->
            <header class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                            <div class="logo"><a href="javascript:;"><?php echo $companyDetail['CompanyName']; ?></a></div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                            <!-- Main Navigation Start -->
                            <nav class="navigation">
                                <ul >
                                    <li><a href="javascript:;">Welcome <?php echo $employerDetail['first_name']; ?> <?php echo $employerDetail['last_name']; ?>,</a></li>
                                </ul>
                            </nav>
                            <!-- Main Navigation End -->
                        </div>
                    </div>
                </div>
            </header>
            <!-- Header End -->
            <!-- Main Start -->	
            <div class="main">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="tabs-wrp">			
                                <ul class="tabs">
                                    <li class="active" rel="tab1"><a href="<?php echo base_url('received_offer_letter/' . $verificationKey);?>">Offer Letter <i class="fa fa-arrow-down"></i></a></li>
                                    <li rel="tab2"><a href="<?php echo base_url('onboard_eligibility_verification/' . $verificationKey);?>">Eligibility verification</a></li>
                                    <li rel="tab3"><a href="<?php echo base_url('onboard_received_document/' . $verificationKey);?>">HR Docs</a></li>
                                </ul>
                                <div class="tab_container">
                                    <div id="tab1" class="tab_content">
                                        <div class="top-title-area">
                                            <h2>View and Acknowledge Offer Letter(s).</h2>
                                        </div>
                                        <div class="text-container-wrp">
                                            <header class="text-top-header-area">
                                                <h2>Additional HR Docs</h2>
                                            </header>
                                            <div class="middle-text-area">
                                                <div class="create-job-wrap">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <?php if (count($offerLetters) == 0) { ?>
                                                                <div class="archived-document-area">
                                                                    <div class="archived-heading-area">
                                                                        <h2>No Received Document!</h2>
                                                                    </div>
                                                                </div>
                                                            <?php } else {
                                                                ?>
                                                                <?php
                                                                if (count($offerLetters) > 0) {
                                                                    foreach ($offerLetters as $offerLetter) {
                                                                        ?>
                                                                        <div class="additional-box-row">
                                                                            <div class="dash-box">
                                                                                <div class="row">
                                                                                    <div class="col-lg-5 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="additional-hr-heading">
                                                                                            <a href="javascript:;" data-toggle="modal" data-target="#offerLetter_<?php echo $offerLetter['sid'] ?>" ><i class="fa fa-envelope"></i><?php echo $offerLetter['letter_name'] ?> (Offer letter)</a>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-7 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                                <div class="additional-hr-box">
                                                                                                    <h2>Step 1</h2>
                                                                                                    <h2>Acknowledge</h2>
                                                                                                    <div class="btnpanel-wrp">
                                                                                                        <div class="button-panel">
                                                                                                            <?php if ($offerLetter['acknowledged'] == 0) { ?>
                                                                                                                <a href="javascript:;"  data-toggle="modal" data-target="#offerLetter_<?php echo $offerLetter['sid'] ?>" class="site-btn">Acknowledge</a>
                                                                                                            <?php } else if ($offerLetter['acknowledged'] == 1) { ?>
                                                                                                                <span class="site-btn no-bg"><i class="fa fa-check"></i><?php echo month_date_year($offerLetter['acknowledged_date']) ?></span> 
                                                                                                            <?php } ?>         
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                                <div class="additional-hr-box">
                                                                                                    <h2>Step 2</h2>
                                                                                                    <h2>Download</h2>
                                                                                                    <div class="btnpanel-wrp">
                                                                                                        <div class="button-panel">
                                                                                                            <?php if ($offerLetter['downloaded'] == 0) { ?>
                                                                                                                <a href="<?php echo base_url('my_hr_documents') ?>/textToWordFile/<?php echo $offerLetter['user_document_sid'] ?>" id="download_<?php echo $offerLetter['user_document_sid']; ?>"  onclick="document_download('download', 1, this.id)"   target="_blank"  class="site-btn">Download</a>
                                                                                                            <?php } else if ($offerLetter['downloaded'] == 1) { ?>
                                                                                                                <span class="site-btn no-bg"><i class="fa fa-check"></i><?php echo month_date_year($offerLetter['downloaded_date']) ?></span> 
                                                                                                            <?php } ?>         
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                                <div class="additional-hr-box <?php if ($offerLetter['uploaded'] == 0) { ?> custom-height <?php } ?>">
                                                                                                    <h2>Step 3</h2>
                                                                                                    <h2>Complete</h2>
                                                                                                    <?php if ($offerLetter['uploaded'] == 0) { ?>
                                                                                                        <div class="btnpanel-wrp">
                                                                                                            <form action="<?php echo base_url('upload_response_file') ?>" method="POST" enctype="multipart/form-data" id="myForm_<?php echo $offerLetter['user_document_sid']; ?>" >
                                                                                                                <div class="button-panel">
                                                                                                                    <input type="hidden" name="action" value="upload_file" >
                                                                                                                    <input type="hidden" name="document_user_sid" value="<?php echo $offerLetter['user_document_sid']; ?>">
                                                                                                                    <input class="site-btn" type="file" name="uploadedFile" onchange="javascript:document.getElementById('myForm_<?php echo $offerLetter['user_document_sid']; ?>').submit();">
                                                                                                                    <a href="javascript:;" class="site-btn">upload</a>
                                                                                                                </div>
                                                                                                            </form>
                                                                                                        </div>
                                                                                                        <a href="javascript:;"  id="marked-complete_<?php echo $offerLetter['user_document_sid']; ?>" onclick="document_actions('mark as complete', 1, this.id)" class="mark-as">Mark as Complete</a>
                                                                                                    <?php } else if ($offerLetter['uploaded'] == 1) { ?>
                                                                                                        <div class="btnpanel-wrp">
                                                                                                            <div class="button-panel">
                                                                                                                <span class="site-btn no-bg"><i class="fa fa-check"></i><?php echo month_date_year($offerLetter['uploaded_date']) ?></span> 
                                                                                                                <?php if ($offerLetter['uploaded_file'] != "") { ?>
                                                                                                                    <a href="<?php echo base_url('my_hr_documents') ?>/textToWordUploadedFile/<?php echo $offerLetter['user_document_sid'] ?>" class="mark-as view-custom-upload" target="_blank"  >View Upload</a>
                                                                                                                <?php } else { ?>
                                                                                                                    <a href="javascript:;" class="mark-as view-custom-upload">Manually Completed</a>
                                                                                                                <?php } ?>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    <?php } ?>    
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            <?php } ?>
                                                            <!--//hr documents section ends-->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="btn-panel">
                                                    <ul>
                                                        <li><input class="site-btn custome-btn" type="submit" onClick="document.location.href = '<?= base_url('onboard_eligibility_verification') ?>/<?php echo $verificationKey ?>'"
                                                            <?php
                                                            if (count($offerLetters) > 0) {
                                                                foreach ($offerLetters as $offerLetter) {
                                                                    if ($offerLetter['acknowledged'] == 0) {
                                                                        ?> disabled
                                                                               <?php
                                                                           }
                                                                       }
                                                                   }
                                                                   ?>value="continue"></li>
                                                    </ul>
                                                </div>
                                            </div>	
                                        </div>	         
                                    </div><!-- #tab3 -->
                                </div>	
                            </div>				
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main End -->		
            <!-- Footer Start -->
            <footer class="footer">
                <!-- CopyRight Start -->
                <div class="copyright">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="copy-right-text">
                                    <p>&copy; 2016 <?php echo STORE_NAME; ?>. All Rights Reserved..</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CopyRight End -->
            </footer>
            <!-- Footer End -->
            <div class="clear"></div>
        </div>
        <!-- Wrapper End -->
        <?php
        if (count($offerLetters) > 0) {
            foreach ($offerLetters as $offerLetter) {
                ?>
                <div class="modal fade file-uploaded-modal" id="offerLetter_<?php echo $offerLetter['sid']; ?>" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo $offerLetter['letter_name']; ?> (Offer letter)</h4>
                                <?php if ($offerLetter['acknowledged'] == 0) { ?>
                                    <a href="javascript:;" id="acknowledge_<?php echo $offerLetter['user_document_sid']; ?>" onclick="document_actions('acknowledge', 1, this.id)" >Acknowledge</a>
                                <?php } else if ($offerLetter['acknowledged'] == 1) { ?>    
                                    <span class=" no-bg"><i class="fa fa-check"></i>Acknowledged</span> 
                                <?php } ?>
                            </div>
                            <div class="modal-body">
                                <div class="description-editor">
                                    <div class="offer-letter-text-block">
                                        <?php echo $offerLetter['offer_letter_body']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </body>
</html>
<script>
    function document_actions(action, value, longId) {
        res = longId.split("_");
        id = res[1];

        url = "<?= base_url() ?>Received_documents_onboarding/document_tasks";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Document?",
                function () {
                    $.post(url, {type: action, sid: id, value: value, action: action})
                            .done(function (data) {
                                location.reload();
                            });
                },
                function () {
                });
    }

    function document_download(action, value, longId) {
        res = longId.split("_");
        id = res[1];
        url = "<?= base_url() ?>Received_documents_onboarding/document_tasks";
        $.post(url, {type: action, sid: id, value: value, action: action})
                .done(function (data) {
                    location.reload();
                });

    }
</script>