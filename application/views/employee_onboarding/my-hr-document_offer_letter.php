<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/style-tabs.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/responsive.css">
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
                                    <li  rel="tab1"><a href="javascript:;">Offer Letter</a></li>
                                    <li rel="tab2"><a href="javascript:;">Eligibility verification</a></li>
                                    <li rel="tab3" class="active"><a href="javascript:;">HR Docs</a></li>
                                </ul>
                                <div class="tab_container">
                                    <div id="tab3" class="tab_content">
                                        <div class="top-title-area">
                                            <h2>View and Acknowledge Document(s).</h2>
                                        </div>
                                        <div class="text-container-wrp">
                                            <header class="text-top-header-area">
                                                <h2>Additional HR Docs</h2>
                                            </header>
                                            <div class="middle-text-area">
                                                <div class="create-job-wrap">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <?php if (count($documents) == 0 && count($offerLetters) == 0) { ?>
                                                                <div class="archived-document-area">
                                                                    <div class="archived-heading-area">
                                                                        <h2>No Received Document!</h2>
                                                                    </div>
                                                                </div>
                                                            <?php } else {
                                                                ?>
                                                                <!--//hr documents section start-->
                                                                <?php
                                                                if (count($documents) > 0) {
                                                                    foreach ($documents as $document) {
                                                                        ?>
                                                                        <div class="additional-box-row">
                                                                            <div class="dash-box">
                                                                                <div class="row">
                                                                                    <div class="col-lg-5 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="additional-hr-heading">
                                                                                            <a href="javascript:;" data-toggle="modal" data-target="#document_<?php echo $document['sid'] ?>" ><i class="fa <?php if ($document['document_type'] == 'pdf' || $document['document_type'] == 'PDF') { ?>fa-file-pdf-o<?php } else if ($document['document_type'] == 'doc' || $document['document_type'] == 'docx' || $document['document_type'] == 'DOC' || $document['document_type'] == 'DOCX') { ?>fa-file-word-o<?php } else { ?>fa-file-text-o<?php } ?>"></i><?php echo $document['document_original_name'] ?>  (Document)</a>
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
                                                                                                            <?php if ($document['acknowledged'] == 0) { ?>
                                                                                                                <a href="javascript:;"  data-toggle="modal" data-target="#document_<?php echo $document['sid'] ?>" class="site-btn">Acknowledge</a>
                                                                                                            <?php } else if ($document['acknowledged'] == 1) { ?>
                                                                                                                <span class="site-btn no-bg"><i class="fa fa-check"></i><?php echo month_date_year($document['acknowledged_date']) ?></span> 
                                                                                                            <?php } ?>         
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php if ($document['action_required'] == 1) { ?>
                                                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                                    <div class="additional-hr-box">
                                                                                                        <h2>Step 2</h2>
                                                                                                        <h2>Download</h2>
                                                                                                        <div class="btnpanel-wrp">
                                                                                                            <div class="button-panel">
                                                                                                                <?php if ($document['downloaded'] == 0) { ?>
                                                                                                                    <a href="<?php echo AWS_S3_BUCKET_URL . $document['document_name'] ?>" id="download_<?php echo $document['user_document_sid']; ?>" onclick="document_download('download', 1, this.id)" download="download" target="_blank" class="site-btn">Download</a>
                                                                                                                <?php } else if ($document['downloaded'] == 1) { ?>
                                                                                                                    <span class="site-btn no-bg"><i class="fa fa-check"></i><?php echo month_date_year($document['downloaded_date']) ?></span> 
                                                                                                                <?php } ?>         
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                                    <div class="additional-hr-box <?php if ($document['uploaded'] == 0) { ?> custom-height <?php } ?>">
                                                                                                        <h2>Step 3</h2>
                                                                                                        <h2>Complete</h2>
                                                                                                        <?php if ($document['uploaded'] == 0) { ?>
                                                                                                            <div class="btnpanel-wrp">
                                                                                                                <form action="<?php echo base_url('upload_response_file') ?>" method="POST" enctype="multipart/form-data" id="myForm_<?php echo $document['user_document_sid']; ?>" >
                                                                                                                    <div class="button-panel">
                                                                                                                        <input type="hidden" name="action" value="upload_file" >
                                                                                                                        <input type="hidden" name="url" value="<?php echo current_url()?>" >
                                                                                                                        <input type="hidden" name="document_user_sid" value="<?php echo $document['user_document_sid']; ?>">
                                                                                                                        <input type="hidden" name="verification_key" value="<?php echo $document['verification_key']; ?>">
                                                                                                                        <input class="site-btn" type="file" name="uploadedFile" onchange="javascript:document.getElementById('myForm_<?php echo $document['user_document_sid']; ?>').submit();">
                                                                                                                        <a href="javascript:;" class="site-btn">upload</a>
                                                                                                                    </div>
                                                                                                                </form>
                                                                                                            </div>
                                                                                                            <a href="javascript:;"  id="marked-complete_<?php echo $document['user_document_sid']; ?>" onclick="document_actions('mark as complete', 1, this.id)" class="mark-as">Mark as Complete</a>
                                                                                                        <?php } else if ($document['uploaded'] == 1) { ?>
                                                                                                            <div class="btnpanel-wrp">
                                                                                                                <div class="button-panel">
                                                                                                                    <span class="site-btn no-bg"><i class="fa fa-check"></i><?php echo month_date_year($document['uploaded_date']) ?></span> 
                                                                                                                    <?php if ($document['uploaded_file'] != "") { ?>
                                                                                                                        <a href="javascript:;"   data-toggle="modal" data-target="#uploaded_document_<?php echo $document['user_document_sid'] ?>"  class="mark-as view-custom-upload">View Upload</a>
                                                                                                                    <?php } else { ?>
                                                                                                                        <a href="javascript:;" class="mark-as view-custom-upload">Manually Completed</a>
                                                                                                                    <?php } ?>
                                                                                                                </div>
                                                                                                            </div>


                                                                                                        <?php } ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php } else if ($document['action_required'] == 0) { ?>
                                                                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-6">
                                                                                                    <div class="additional-hr-box">
                                                                                                        <p class="info-tagline">Doc does not require additional completion</p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php } ?>         
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <?php
                                                                    }
                                                                }
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
                                                        <li><input class="site-btn custome-btn" type="submit" 
                                                            <?php
                                                            if (count($documents) > 0) {
                                                                foreach ($documents as $document) {
                                                                    if ($document['actionCheck'] == 'false') {
                                                                        ?> disabled
                                                                               <?php
                                                                           }
                                                                       }
                                                                   }
                                                                   ?>value="finish & LogIn"  id="<?php echo $employerDetail['sid'] ?>" onclick="return employerLogin(this.id)"></li>
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
                                    <p>&copy; <?php echo date('Y')?> <?php echo STORE_NAME; ?>. All Rights Reserved..</p>
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
        if (count($documents) > 0) {
            foreach ($documents as $document) {
                ?>
                <div id="document_<?php echo $document['sid'] ?>" class="modal fade file-uploaded-modal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo $document['document_name']; ?> </h4>
                                <?php if ($document['acknowledged'] == 0) { ?>
                                    <a href="javascript:;" id="acknowledge_<?php echo $document['user_document_sid']; ?>" onclick="document_actions('acknowledge', 1, this.id)" >Acknowledge</a>
                                <?php } else if ($document['acknowledged'] == 1) { ?>
                                    <span class=" no-bg"><i class="fa fa-check"></i>Acknowledged</span>
                                <?php } ?>
                            </div>
                            <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . urlencode($document['document_name']); ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
                <div id="uploaded_document_<?php echo $document['user_document_sid'] ?>" class="modal fade file-uploaded-modal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo $document['uploaded_file']; ?> </h4>
                            </div>
                            <?php
                            $user_uploaded_file = explode(".", $document['uploaded_file']);

                            if (end($user_uploaded_file) == 'pdf' || end($user_uploaded_file) == 'PDF' || end($user_uploaded_file) == 'doc' || end($user_uploaded_file) == 'docx' || end($user_uploaded_file) == 'DOC' || end($user_uploaded_file) == 'DOCX') { ?>
                                <iframe class="uploaded-file-preview"
                                        src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . urlencode($document['uploaded_file']); ?>&embedded=true"
                                        style="width:600px; height:500px;" frameborder="0"></iframe>
                            <?php } else { ?>
                                <img src="<?php echo AWS_S3_BUCKET_URL . urlencode($document['uploaded_file']); ?>"
                                     style="width:100%; height:500px;"/>
                            <?php } ?>
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

    function employerLogin(userId) {
        url_to = "<?= base_url() ?>manage_admin/employers/employer_login";
        $.post(url_to, {action: "login", sid: userId, system: "onboarding"})
                .done(function () {
                   window.location.assign("<?= base_url('dashboard') ?>");
                });
    }

</script>