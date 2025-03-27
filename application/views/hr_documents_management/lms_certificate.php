<div class="row" id="jsCertificateContainer_<?php echo $courseInfo['key']; ?>" style="float: left; margin-left: -1000px; width: 800px;">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <div class="container pm-certificate-container">
            <div class="outer-border"></div>
            <div class="inner-border"></div>

            <div class="pm-certificate-border col-xs-12">
                <div class="row pm-certificate-header">
                    <div class="pm-certificate-title s13 col-xs-12 text-center">
                        <h1><?php echo $courseInfo['companyName']; ?></h1>
                    </div>
                </div>

                <div class="row pm-certificate-body">

                    <div class="pm-certificate-block">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                <div class="pm-certificate-name underline margin-0 col-xs-8 text-center">
                                    <span class="pm-presented-text padding-0 block cursive">This Certificate is presented to</span>
                                    <span class="pm-name-text bold"><?php echo $courseInfo['employeeName']; ?></span>
                                </div>
                                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                            </div>
                        </div>



                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                <div class="pm-course-title col-xs-8 text-center">
                                    <span class="pm-entitled-text block cursive">on successfully completing the e-learning training course entitled</span>
                                </div>
                                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-1"><!-- LEAVE EMPTY --></div>
                                <div class="pm-course-title underline col-xs-10 text-center">
                                    <span class="pm-course-title-text block bold sans"><?php echo $courseInfo['course_title']; ?> (<?= ucfirst($courseInfo['course_language']); ?>)</span>
                                </div>
                                <div class="col-xs-1"><!-- LEAVE EMPTY --></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="pm-certificate-footer">
                                <?php if ($courseInfo['secondary_logo']) { ?>
                                    <div class="col-xs-4 pm-certified text-center">
                                        <span class="pm-credits-text block sans">Training provided by</span>
                                        <img class="image-responsive" src="<?php echo $courseInfo['AHRLogo']; ?>" alt="" style="width: 120px">
                                    </div>
                                    <div class="col-xs-4 pm-certified text-center">
                                        <span class="pm-credits-text block sans">Proudly Sponsored By</span>
                                        <?php
                                        if ($courseInfo['secondary_logo']) {
                                            //
                                            $url = AWS_S3_BUCKET_URL . $courseInfo['secondary_logo'];
                                            //make a curl call to fetch content
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
                                            curl_setopt($ch, CURLOPT_HEADER, 0);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_URL, $url);
                                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                                            $data = curl_exec($ch);
                                            curl_close($ch);
                                            //get mime type
                                            $mime_type = getMimeType($url);
                                            $str64 = base64_encode($data);
                                        }
                                        //
                                        ?>
                                        <img class="image-responsive" src="<?php echo "data:" . $mime_type . ";base64," . $str64; ?>" alt="" style="width: 120px">
                                    </div>
                                    <div class="col-xs-4 pm-certified text-center">
                                        <span class="pm-credits-text block sans">Completion Date</span>
                                        <span class="pm-completion-date-text block cursive"><?php echo $courseInfo['completedOn']; ?></span>
                                        <span class="pm-empty-space block underline"></span>
                                        <span class="bold block">Employee Number : <?php echo $courseInfo['AHRStudentID']; ?></span>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-xs-5 pm-certified text-center">
                                        <span class="pm-credits-text block sans">Training provided by</span>
                                        <img class="image-responsive" src="<?php echo $courseInfo['AHRLogo']; ?>" alt="" style="width: 120px">
                                    </div>
                                    <div class="col-xs-2">
                                        <!-- LEAVE EMPTY -->
                                    </div>
                                    <div class="col-xs-5 pm-certified text-center">
                                        <span class="pm-credits-text block sans">Completion Date</span>
                                        <span class="pm-completion-date-text block cursive"><?php echo $courseInfo['completedOn']; ?></span>
                                        <span class="pm-empty-space block underline"></span>
                                        <span class="bold block">Employee Number : <?php echo $courseInfo['AHRStudentID']; ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //
    $( window ).on( "load", function() {
        var draw = kendo.drawing;
        var key = "<?php echo $courseInfo['key']; ?>";
        draw.drawDOM($("#jsCertificateContainer_"+key), {
                avoidLinks: false,
                paperSize: "auto",
                multiPage: true,
                margin: {
                    bottom: "1cm"
                },
                scale: 0.8
            })
            .then(function(root) {
                return draw.exportPDF(root);
            })
            .done(function(data) {
                $.post("<?=base_url('hr_documents_management/upload');?>", {
                    data: data,
                    token: "<?=$token;?>",
                    employeeSid: "<?= $employeeSid; ?>",
                    userFullNameSlug: "<?=$userFullNameSlug;?>",
                    type: 'lms_certificate_'+key,
                }, () => {
                    m('Adding LMS certificate to export.')
                });
            });
    });        
</script>