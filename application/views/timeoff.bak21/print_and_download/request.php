<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/style.css'); ?>">
    <title><?php echo $page_title; ?></title>
    <style>
    .content {
        font-size: 100%;
        line-height: 1.6em;
        display: block;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
        position: relative;
    }

    .header {
        width: 100%;
        float: left;
        padding: 5px 10px;
        text-align: center;
        box-sizing: border-box;
        background-color: #000;
    }

    .body-content {
        width: 100%;
        float: left;
        padding: 20px 12;
        /* margin-top: 90px; */
        box-sizing: padding-box;
    }

    .header h2 {
        color: #fff;
    }

    .footer {
        width: 100%;
        float: left;
        background-color: #000;
        padding: 20px 30px;
        box-sizing: border-box;
    }

    .footer_contant {
        float: left;
        width: 100%;
    }

    .footer_text {
        color: #fff;
        float: left;
        text-align: center;
        font-style: italic;
        line-height: normal;
        font-family: "Open Sans", sans-serif;
        font-weight: 600;
        font-size: 14px;
    }

    .footer_text a {
        color: #fff;
        text-decoration: none;
    }

    .employee-info figure {
        width: 50px !important;
        height: 50px !important;
    }

    .employee-info figure {
        float: left;
        width: 50px;
        height: 50px;
        border-radius: 100%;
        border: 1px solid #ddd;
    }

    .employee-info figure img {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        border-radius: 3px !important;
    }

    .employee-info .text {
        /* margin: 0 0 0 60px; */
    }

    .employee-info .text h4 {
        font-weight: 600;
        font-size: 18px !important;
        margin: 0;
    }

    #js-data-area .text p {
        color: #000 !important;
    }

    .employee-info .text p {
        font-weight: 400;
        font-size: 14px;
        margin: 0;
    }

    .upcoming-time-info .icon-image {
        float: left;
        width: 40px;
        height: 40px;
        display: inline-block;
    }

    .upcoming-time-info .icon-image img {
        width: 100%;
        height: 100%;
    }

    .upcoming-time-info .text {
        margin: 5px 0 0 0;
    }

    .upcoming-time-info .text h4 {
        font-weight: 600;
        font-size: 16px;
        margin: 0;
    }

    .upcoming-time-info .text p {
        font-weight: 400;
        font-size: 14px;
        margin: 0;
    }

    .upcoming-time-info .text p span {
        font-weight: 700;
    }

    .section_heading {
        font-weight: 700;
    }

    .approvers_panel {
        margin-top: 18px;
    }

    .approver_row:nth-child(odd) {
        background-color: #F5F5F5;
    }
    </style>
</head>

<?php 
    //
    $handledApproval = [];
    //
    if(count($request['history'])){
        foreach($request['history'] as $his){
            //
            if(isset($handledApproval[$his['userId']])) continue;
            //
            $handledApproval[$his['userId']] = ['Status' => 'Pending', 'Date' => '-', 'Comment' => '-'];
            //
            if($his['action'] == 'create') continue;
            if($his['note'] == '{}') continue;
            //
            $action = json_decode($his['note'], true);
            //
            if(!isset($action['canApprove'])) continue;
            //
            if($action['status'] == 'pending') continue;
            if($action['status'] == 'approved') {
                $handledApproval[$his['userId']]['Status'] = 'Approved';
                $handledApproval[$his['userId']]['Date'] = $his['created_at'];
                $handledApproval[$his['userId']]['Comment'] = $action['comment'];
            }
            else if($action['status'] == 'rejected') {
                $handledApproval[$his['userId']]['Status'] = 'Rejected';
                $handledApproval[$his['userId']]['Date'] = $his['created_at'];
                $handledApproval[$his['userId']]['Comment'] = $action['comment'];
            }
        }
    }
    $image = !empty($request['image']) ? $request['image'] : 'download-cylw2M.jpg';
?>

<body cz-shortcut-listen="true">
    <div class="content" id="download_timeoff_action">
        <div class="body-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong><?=strtoupper($request['status']);?></strong> <strong
                                style="float: right;">Created Date: <?php echo formatDate(
                                    $request['created_at'],
                                    'Y-m-d H:i:s',
                                    'M d Y, D H:i:s'
                                );?></strong></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 style="font-weight: 700">Employee</h5>
                                    <div class="employee-info">
                                        <div class="text">
                                            <h4><?=$request['first_name'];?> <?=$request['last_name'];?></h4>
                                            <p><?=remakeEmployeeName($request);?></p>
                                            <p>
                                                <a>
                                                    Id:<?=!empty($request['employee_number']) ? $request['employee_number'] : $request['userId'];?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <h5 style="font-weight: 700">Policy</h5>
                                    <div class="upcoming-time-info">
                                        <div class="text">
                                            <h4><?php 
                                                        if($request['request_from_date'] != $request['request_to_date']){
                                                            echo  formatDate(
                                                                $request['request_from_date'],
                                                                'Y-m-d',
                                                                'M d Y, D'
                                                            ), ' - ',  formatDate(
                                                                $request['request_to_date'],
                                                                'Y-m-d',
                                                                'M d Y, D'
                                                            );
                                                        } else{
                                                            echo  formatDate(
                                                                $request['request_from_date'],
                                                                'Y-m-d',
                                                                'M d Y, D'
                                                            );
                                                        }
                                                    ?></h4>
                                            <span><?=$request['title'];?></span><br />
                                            <span><?=$request['breakdown']['text'];?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Reason</label>
                                    <p><?=!empty($request['reason']) ? $request['reason'] : '';?></p>
                                </div>
                            </div>
                            <hr />
                            <?php if(count($request['approvers'])) { ?>
                            <!--  -->
                            <div class="panel panel-default approvers_panel">
                                <div class="panel-heading"><strong>Approvers</strong></div>
                                <div class="panel-body">
                                    <?php foreach($request['approvers'] as $approver){ 
                                        $image = !empty($approver['image']) ? $approver['image'] : 'download-cylw2M.jpg';
                                        $resp = !isset($handledApproval[$approver['userId']]) ? ['Status' => 'Pending', 'Date' => '-', 'Comment' => '-'] : $handledApproval[$approver['userId']];
                                        ?>
                                    <div class="row approver_row">
                                        <div class="col-xs-4">
                                            <h5 style="font-weight: 700">Employee</h5>
                                            <div class="employee-info">
                                                <div class="text">
                                                    <h4><?=$approver['first_name'];?> <?=$approver['last_name'];?></h4>
                                                    <p><?=remakeEmployeeName($approver);?></p>
                                                    <p>
                                                        <a>
                                                            Id:<?=!empty($approver['employee_number']) ? $approver['employee_number'] : $approver['userId'];?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <h5 style="font-weight: 700">Status</h5>
                                            <p><?=$resp['Status'];?></p>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Comment</h5>
                                            <p><?=$resp['Comment'];?></p>
                                        </div>
                                        <div class="col-xs-3">
                                            <h5 style="font-weight: 700">Action Taken</h5>
                                            <p><?=$resp['Date'] != '-' ? formatDate(
                                                                $resp['Date'],
                                                                'Y-m-d H:i:s',
                                                                'M d Y, D H:i:s'
                                                            ) : '-';?></p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr />
                            <?php }?>

                            <?php if(count($request['history'])) { ?>
                            <!--  -->
                            <div class="panel panel-default approvers_panel">
                                <div class="panel-heading"><strong>History</strong></div>
                                <div class="panel-body">
                                    <?php foreach($request['history'] as $his){ 
                                        $image = !empty($approver['image']) ? $approver['image'] : 'download-cylw2M.jpg';
                                        //
                                        $note = json_decode($his['note'], true);
                                        //
                                        $act = '';
                                        $comment = '-';
                                        if($his['action'] == 'create'){
                                            $act = "Time-off created.";
                                        } else{
                                            if(isset($note['status'])){
                                                //
                                                if($note['status'] == 'archive') $act = "Time-off marked as archive";
                                                else if($note['status'] == 'activate') $act = "Time-off marked as active";
                                                else if($note['status'] == 'approved' && $note['canApprove'] == 1) $act = "Time-off approved 100%";
                                                else if($note['status'] == 'approved' && $note['canApprove'] == 0) $act = "Time-off approved 50%";
                                                else if($note['status'] == 'rejected' && $note['canApprove'] == 1) $act = "Time-off rejected 100%";
                                                else if($note['status'] == 'rejected' && $note['canApprove'] == 0) $act = "Time-off rejected 50%";
                                                else if($note['status'] == 'pending' && $note['canApprove'] == 0) $act = "Time-off updated";
                                                else if($note['status'] == 'cancelled') $act = "Time-off cancelled";
                                                //
                                                if(isset($note['comment']) && $note['comment'] != '') $comment = $note['comment'];
                                            } else{
                                                $act = "";
                                            }
                                            if($his['action'] == 'update' && $act == '') $act = "Time-off updated.";
                                        }
                                        
                                        ?>
                                    <div class="row approver_row">
                                        <div class="col-xs-3">
                                            <h5 style="font-weight: 700">Employee</h5>
                                            <div class="employee-info">
                                                <div class="text">
                                                    <h4><?=$his['first_name'];?> <?=$his['last_name'];?></h4>
                                                    <p><?=remakeEmployeeName($his);?></p>
                                                    <p>
                                                        <a>
                                                            Id:<?=!empty($his['employee_number']) ? $his['employee_number'] : $his['userId'];?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php if(isset($note['details'])) { ?>
                                            <h5 style="font-weight: 700">Policy</h5>
                                            <div class="upcoming-time-info">
                                                <div class="text">
                                                    <h4><?php 
                                                        if($note['details']['startDate'] != $note['details']['endDate']){
                                                            echo  formatDate(
                                                                $note['details']['startDate'],
                                                                'm/d/Y',
                                                                'M d Y, D'
                                                            ), ' - ',  formatDate(
                                                                $note['details']['endDate'],
                                                                'm/d/Y',
                                                                'M d Y, D'
                                                            );
                                                        } else{
                                                            echo  formatDate(
                                                                $note['details']['startDate'],
                                                                'm/d/Y',
                                                                'M d Y, D'
                                                            );
                                                        }
                                                    ?></h4>
                                                    <span><?=$note['details']['policyTitle'];?></span><br />
                                                    <span><?=get_array_from_minutes(
                                                        $note['details']['time'],
                                                        $request['user_shift_hours'],
                                                        rtrim((isset($request['breakdown']['active']['hours']) ? 'H:' : '').
                                                        (isset($request['breakdown']['active']['minutes']) ? 'M:' : ''), ':')
                                                    )['text'];?></span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Action</h5>
                                            <p><?=$act;?></p>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Comment</h5>
                                            <p><?=$comment;?></p>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Action Taken</h5>
                                            <p><?=formatDate(
                                                                $his['created_at'],
                                                                'Y-m-d H:i:s',
                                                                'M d Y, D H:i:s'
                                                            ) ;?></p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr />
                            <?php }?>


                            <?php if(count($policies)) { ?>
                            <!--  -->
                            <div class="panel panel-default approvers_panel">
                                <div class="panel-heading"><strong>As Of Today</strong></div>
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Policy Title</th>
                                                <th>Remaining Time</th>
                                                <th>Employment Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($policies as $policy){
                                            if(!empty($policy['Reason']))  continue;
                                        ?>
                                            <tr>
                                                <td><?=$policy['Title'];?></td>
                                                <td> <?=$policy['AllowedTime']['text'];?></td>
                                                <td> <?=ucwords($policy['EmployementStatus']);?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr />
                            <?php }?>
                            <!--  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

    <script id="script">
    if ("<?php echo $download; ?>" == 'yes') {
        download_document();
    } else {
        $(window).on("load", function() {
            setTimeout(function() {
                window.print();
            }, 2000);
        });

        window.onafterprint = function() {
            window.close();
        }
    }

    function download_document() {
        var draw = kendo.drawing;
        draw.drawDOM($("#download_timeoff_action"), {
                avoidLinks: false,
                paperSize: "A4",
                multiPage: true,
                margin: {
                    bottom: "2cm"
                },
                scale: 0.8
            })
            .then(function(root) {
                return draw.exportPDF(root);
            })
            .done(function(data) {
                var pdf = data;
                kendo.saveAs({
                    dataURI: pdf,
                    fileName: "Time-off of <?=$request['first_name'];?> <?=$request['last_name'];?> for <?=$request['request_from_date'] != $request['request_to_date'] ? formatDate($request['request_from_date']).' '.formatDate($request['request_to_date']) : formatDate($request['request_from_date']);?> against <?=$request['title'];?> policy.pdf",
                });
                window.close();
            });
    }
    </script>
</body>

</html>