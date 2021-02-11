<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
    <title>Time Off</title>
    <style>
        .center-col{
            float: left;
            width: 100%;
            text-align: center;
            margin-top: 14px;
        }
        .center-col h2,
        .center-col p{
            margin: 0 0 5px 0;
        }
        .sheet-header {
            float: left;
            width: 100%;
            padding: 0 0 2px 0;
            margin: 0 0 5px 0;
            border-bottom: 5px solid #000;
        }
        .sheet.padding-10mm { padding: 10mm }
        .header-logo{
            float: left;
            width: 100%;
        }

        /**/
        .cs-box{
            margin-top: 10px;
            margin-bottom: 10px;
            /*border: 1px solid #ccc;*/
            padding: 15px;
        }
    </style>
</head>
<body cz-shortcut-listen="true">
    <!--  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <!--  -->
    <div class="container">
        <div id="js-preview">
            <div class="cs-box">
                <!-- 1 -->
                <div class="row">
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-sm-2 col-xs-2">
                                <img src="<?=$Request['Info']['img'] == '' ? base_url('assets/images/img-applicant.jpg') : AWS_S3_BUCKET_URL.$Request['Info']['img'];?>" width="100%" />
                            </div>
                            <div class="col-xs-10">
                                <strong><?=remakeEmployeeName($Request['Info']);?> </strong><br /> 
                                <span style="font-size: 12px;">Employee Number: <?=$Request['Info']['employee_number'] == '' ? '-' : $Request['Info']['employee_number'];?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <span class="pull-right text-<?=$Request['Info']['status'] == 'approved' ? 'success' : 'danger';?>" style="font-size: 16px;">
                            <strong>
                                <?=strtoupper($Request['Info']['status']);?>
                            </strong>
                        </span>
                    </div>
                    <div class="clearfix"></div>
                    <hr />
                </div>
                <!-- 2  -->
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Policy Category</label>
                            <p><?=$Request['Info']['Category'];?></p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Policy</label>
                            <p><?=$Request['Info']['policy_title'];?></p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Status</label>
                            <p><?=ucwords($Request['Info']['status']);?></p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Level</label>
                            <p><?=$Request['Info']['level_at'] == 3 ? 'Approver' : ($Request['Info']['level_at'] == 2 ? 'Supervisor' : 'Team Lead');?></p>
                        </div>
                    </div>
                </div>
                <!-- 3  -->
                <div class="row">
                    <hr />
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Date</label>
                            <p>
                                <?php if(sizeof($Request['Info']['timeoff_days']) == 1) { ?>
                                <?=DateTime::createFromFormat('Y-m-d', $Request['Info']['request_from_date'] )->format('M d D, Y');?> (1 Day)
                                <?php } else { ?>
                                <?=DateTime::createFromFormat('Y-m-d', $Request['Info']['request_from_date'] )->format('M d D, Y');?>-<?=DateTime::createFromFormat('Y-m-d', $Request['Info']['request_to_date'] )->format('M d D, Y');?>  (<?=sizeof($Request['Info']['timeoff_days']);?> Days)
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Time</label>
                            <p><?=$Request['Info']['timeoff_breakdown']['text'];?></p>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <!--  -->
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Duration Type</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($Request['Info']['timeoff_days'] as $day) { ?>
                                    <tr>
                                        <td><?=DateTime::createFromFormat('m-d-Y', $day['date'] )->format('M d D, Y');?></td>
                                        <td><?=$day['partial'] == 'fullday' ? 'Full Day' : 'Partial Day';?></td>
                                        <td><?=$day['breakdown']['text'];?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if(in_array(strtolower($Request['Info']['Category']), ['vacation', 'bereavement', 'compensatory/ in lieu time', 'fmla'])) { ?>
                <!-- 4 -->
                <div class="row">
                    <hr />
                    <?php if(strtolower($Request['Info']['Category']) == 'vacation') { ?>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Emergency Contact Number</label>
                            <p><?=$Request['Info']['emergency_contact_number'] == '' ? '-' : $Request['Info']['emergency_contact_number'];?></p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Return Date</label>
                            <p><?=$Request['Info']['return_date'] != '' && $Request['Info']['return_date'] != null ? '-' : DateTime::createFromFormat('Y-m-d', $Request['Info']['return_date'])->format('M d D, Y');?></p>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Relationship</label>
                            <p><?=$Request['Info']['temporary_address'] == '' ? '-' : $Request['Info']['temporary_address'];?></p>
                        </div>
                    </div>
                    <?php } else if(strtolower($Request['Info']['Category']) == 'bereavement') { ?>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Emergency Contact Number</label>
                            <p><?=$Request['Info']['relationship'] == '' ? '-' : $Request['Info']['relationship'];?></p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Return Date</label>
                            <p><?=$Request['Info']['return_date'] != '' && $Request['Info']['return_date'] != null ? '-' : DateTime::createFromFormat('Y-m-d', $Request['Info']['return_date'])->format('M d D, Y');?></p>
                        </div>
                    </div>
                    <?php } else if(strtolower($Request['Info']['Category']) == 'compensatory/ in lieu time') { ?>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Compensation Date</label>
                            <p><?=$Request['Info']['return_date'] != '' && $Request['Info']['return_date'] != null ? '-' : DateTime::createFromFormat('Y-m-d', $Request['Info']['return_date'])->format('M d D, Y');?></p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Compensation Start Time</label>
                            <p><?=$Request['Info']['compensation_start_time'] != '' && $Request['Info']['compensation_start_time'] != null ? '-' : $Request['Info']['compensation_start_time'];?></p>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Compensation End Time</label>
                            <p><?=$Request['Info']['compensation_end_time'] != '' && $Request['Info']['compensation_end_time'] != null ? '-' : $Request['Info']['compensation_end_time'];?></p>
                        </div>
                    </div>
                    <?php } else if(strtolower($Request['Info']['Category']) == 'fmla') { ?>
                    <div class="col-xs-5">
                        <div class="form-group">
                            <label>Is this time off under FMLA? </label>
                            <p><?=$Request['Info']['fmla'] != '' && $Request['Info']['fmla'] != null ? 'Yes' : 'No';?></p>
                        </div>
                    </div>  
                    <div class="col-xs-7">
                        <div class="form-group">
                            <label>FMLA</label>
                            <p>
                                <?php 
                                    if ($Request['Info']['fmla'] == 'designation') {
                                        echo "Designation Notice";
                                    }else if ($Request['Info']['fmla'] == 'health') {
                                        echo "Certification of Health Care Provider for Employee’s Serious Health Condition";
                                    }else{
                                        echo "Notice of Eligibility and Rights & Responsibilities ";
                                    }
                                ?>
                            </p>
                        </div>
                    </div>  

                    <?php } ?>
                </div>
                <?php } ?>
                <!-- 5 -->
                <div class="row">
                    <hr />
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Reason</label>
                            <p><?=$Request['Info']['reason'] == '' ? '-' : html_entity_decode($Request['Info']['reason']);?></p>
                        </div>
                    </div>
                </div>
                <?php if($Request['Info']['level_at'] == 3){ ?>
                <!-- 6 -->
                <div class="row">
                    <hr />
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Comment</label>
                            <p><?=$Request['History'][0]['reason'] == '' ? '-' : html_entity_decode($Request['History'][0]['reason']);?></p>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if(sizeof($Request['Assigned'])) { ?>
                <!-- 7 -->
                <div class="row">
                    <hr />
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Assigned To</label>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Role</th>
                                        <th>Responded</th>
                                        <th>Assigned Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($Request['Assigned'] as $employee) { ?>
                                        <tr>
                                            <td><?=remakeEmployeeName($employee);?></td>
                                            <td><?=$employee['role'] == 'teamlead' ? 'Team Lead' : ucwords($employee['role']) ;?></td>
                                            <td><?=$employee['is_responded'] == 1 ? 'Yes' : 'No';?></td>
                                            <td><?=DateTime::createFromFormat('Y-m-d H:i:s', $employee['created_at'])->format('M d D, Y');?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if(sizeof($Request['History'])) { ?>
                <!-- 8 -->
                <div class="row">
                    <hr />
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Responses</label>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Respond Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($Request['History'] as $employee) { ?>
                                        <tr>
                                            <td><?=remakeEmployeeName($employee);?></td>
                                            <td><?=$employee['reason'] == '' ? '-' : html_entity_decode($employee['reason']) ;?></td>
                                            <td><?=ucwords($employee['status']);?></td>
                                            <td><?=DateTime::createFromFormat('Y-m-d H:i:s', $employee['created_at'])->format('M d D, Y');?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php
                //
                $hasGeneratedDocument = false;
                // 
                if(sizeof($Request['Attachments'])) { ?>
                <!-- 9 -->
                <div class="row">
                    <hr />
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Attachments</label>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>File Type</th>
                                        <th>Attach Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($Request['Attachments'] as $attachment) { ?>
                                        <?php if($attachment['document_type'] == 'generated' && ( $attachment['s3_filename'] == '' || $attachment['s3_filename'] == null )) $hasGeneratedDocument = $attachment; ?>
                                        <tr>
                                            <td><?=$attachment['document_title'];?></td>
                                            <td><?=ucwords($attachment['document_type']);?></td>
                                            <td><?php
                                                $t = explode('.', $attachment['s3_filename']);
                                                echo strtoupper( $t[sizeof($t) - 1] );
                                            ?></td>
                                            <td><?=DateTime::createFromFormat('Y-m-d H:i:s', $attachment['created_at'])->format('M d D, Y');?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if($hasGeneratedDocument) {  ?>
                <!-- 9 -->
                <?php $this->load->view('timeoff/fmla/preview/'.( $hasGeneratedDocument['document_title'] ).'', array('FMLA' => $hasGeneratedDocument, 'Data' => [], 'Print' => true)); ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <script>
        if("<?=$Action;?>" == 'download'){
            var imgs = $('#js-preview').find('img');
            var i = 0;
            if(imgs.length){
                //
                i = imgs.length;
                //
                $(imgs).each(function(ind,v) {
                    var imgSrc = $(this).attr('src');
                    var _this = this;

                    var p = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm; 

                    if (imgSrc.match(p)) {
                        $.ajax({
                            url: '<?= base_url('hr_documents_management/getbase64/')?>',
                            data:{
                                url: imgSrc.trim()
                            },
                            type: "GET",
                            async: false,
                            success: function (resp){
                                resp = JSON.parse(resp);
                                $(_this).attr("src", "data:"+resp.type+";base64,"+resp.string);
                                --i;
                                download_document();
                            },
                            error: function(){

                            }
                        });
                    } else { 
                        --i; 
                        download_document();
                    }
                });
            } else{
                download_document();
            }

            function download_document(){
                if(i != 0) return;
                var draw = kendo.drawing;
                draw.drawDOM($("#js-preview"), {
                    avoidLinks: false,
                    paperSize: "A4",
                    multiPage: true,
                    margin: { bottom: "2cm" },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    // Load loader
                    $('#js-loader').show();
                    var pdf;
                    pdf = data;
                    // Send the generated PDF to server to save as file
                    $.ajax({
                        url: "<?=base_url('timeoff/public/pd/save/'.( $RequestSid ).'');?>",
                        type: 'POST',
                        data: {
                            employeeName: "<?=$Request['Info']['first_name'].'_'.$Request['Info']['last_name'];?>",
                            file: pdf
                        },
                    })
                    .done(function(resp) {
                        $('#js-loader-text').text('Download will begin shortly..');
                        window.location = "<?=base_url('timeoff/download_file');?>/"+resp;
                        setTimeout(function(){
                            window.close();
                        }, 2000);
                    })
                    
                    // window.close();
                });
            }

        } else {
            //
            $(window).on( "load", function() { 
                setTimeout(function(){
                    window.print();
                }, 2000);  
            });
            //
            window.onafterprint = function(){
                window.close();
            }
        }   
    </script>

<style>
    .my_loader{ display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99; background-color: rgba(0,0,0,.7); }
    .loader-icon-box{ position: absolute; top: 50%; left: 50%; width: auto; z-index: 9999; -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -ms-transform: translate(-50%, -50%); -o-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
    .loader-icon-box i{ font-size: 14em; color: #81b431; }
    .loader-text{ display: inline-block; padding: 10px; color: #000; background-color: #fff !important; border-radius: 5px; text-align: center; font-weight: 600; }
</style>

<!-- Loader -->
<div id="js-loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader cs-loader-file" style="height: 1353px;"></div>
    <div class="loader-icon-box cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text cs-loader-text"  id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we are preparing file for download...
        </div>
    </div>
</div>

</body>
</html>