<!--  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/style.css'); ?>"> -->
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/timeoff/css/theme2021.css'); ?>">
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
    display: none;
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

.panel-theme .panel-heading{
    background-color: #3554DC !important;
    color:#fff !important;
}

</style>


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
    //
    $lastApprover = [];
    //
    if(!empty($request['history'])){
        //

        foreach($request['history'] as $ap){
            //
            if(!empty($lastApprover)){
                continue;
            }
            //
            $det = json_decode($ap['note']);
            //
            if(isset($det->canApprove)){
                
                $lastApprover = [
                    'Id' => $ap['userId'],
                    'Name' => ucwords($ap['first_name'].' '.$ap['last_name']),
                    'Comment' => $det->comment,
                    'Status' => strtoupper($det->status)
                ];
            }
        }
    }
?>

<div class="jsAddTimeOff">
    <div class="container-fluid">
        <div class="js-page" data-page="main">
            <div class="row" style="margin-top: 12px;">
                <!-- Left Bar -->
                <div class="col-sm-4">
                    <div class="csSidebar csRadius5">
                        <!-- Sidebar head -->
                        <div class="csSidebarHead csRadius5 csRadiusBL0 csRadiusBR0" style="height: 118px">
                            <div id="jsEmployeeInfoEdit">
                                <figure>
                                    <img src="<?=getImageURL($request['profile_picture']);?>"
                                        class="csRadius50" alt="" />
                                    <div class="csTextBox">
                                        <p><?php echo $request['first_name']. ' ' .$request['last_name'];?></p>
                                        <p class="csTextSmall">
                                            <?php echo '('.$request['job_title']. ') [' .$request['access_level'] .']';?>
                                        </p>
                                        <p class="csTextSmall"><?php echo $request['email'];?></p>
                                    </div>
                                </figure>
                            </div>
                        </div>
                        <div class="csSidebarApproverSection">
                            <h4>Approvers</h4>
                            <div id="jsApproversListingEdit" class="p10">
                                <?php if (!empty($approvers)) { ?>
                                <?php foreach ($approvers as $approver) { ?>
                                <div class="csApproverBox" title=""
                                    data-content="<?php echo $approver['first_name']. ' ' .$approver['last_name'] . '' . '('.$approver['job_title']. ') [' .$approver['access_level'] .']';?>"
                                    data-original-title="Approver">
                                    <img src="https://automotohrattachments.s3.amazonaws.com/<?php echo !empty($approver['profile_picture']) ? $approver['profile_picture']: 'img-applicant.jpg'; ?>"
                                        alt="" />
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <!--  -->
                            <div id="jsApproversListingoteEdit" class="p10">
                                <hr />
                                <p><strong>Info:</strong></p>
                                <p><i class="fa fa-check-circle text-success" aria-hidden="true"></i>&nbsp; Approved by
                                    the approver.</p>
                                <p><i class="fa fa-times-circle text-danger" aria-hidden="true"></i>&nbsp; Rejected by
                                    the approver.</p>
                                <p><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp; Waiting for approver to
                                    approve/reject.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main area -->
                <div class="col-sm-8">

                    <!--  -->
                    <div class="panel panel-theme">
                        <div class="panel-heading">
                            <strong>Time off Information</strong>
                        </div>
                        <div class="panel-body">
                        <!--  -->
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <strong>
                                        Current Time-off Status:
                                        <?php 
                                        if($request['status'] == 'rejected'){
                                            ?>
                                        <strong class="text-danger">
                                            <?=strtoupper($request['status']);?>
                                        </strong>
                                        <?php
                                        } else if($request['status'] == 'approved'){
                                            ?>
                                        <strong class="text-success">
                                            <?=strtoupper($request['status']);?>
                                        </strong>
                                        <?php
                                        } else if($request['status'] == 'cancel' || $request['status'] == 'cancelled'){
                                            ?>
                                        <strong class="text-danger">
                                            <?=strtoupper($request['status']);?>
                                        </strong>
                                        <?php
                                        } else if($request['status'] == 'pending'){
                                            ?>
                                        <strong class="text-warning">
                                            <?=strtoupper($request['status']);?>
                                        </strong>
                                        <?php
                                        }
                                    ?>
                                    </strong> <br>
                                    <?php if ($request['status'] == 'approved') { ?>
                                        <?=$request['status'];?>
                                        <strong>Last Approved by "<?=$lastApprover['Name'];?>"</strong>
                                    <?php } ?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <strong style="float: right;">Created Date: 
                                    <?php echo formatDate(
                                        $request['created_at'],
                                        'Y-m-d H:i:s',
                                        'M d Y, D H:i:s'
                                    );?>
                                    </strong>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
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
                                            ?>
                                            </h4>
                                            <span><?=$request['title'];?></span><br />
                                            <span><?=$request['breakdown']['text'];?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <label>Employee's Reason</label>
                                    <p><?=!empty($request['reason']) ? $request['reason'] : '';?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(count($request['history']) && $user_type == 'approvers') { ?>
                        <!--  -->
                        <div class="panel panel-theme">
                            <div class="panel-heading">
                                <strong> Time-off History</strong>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-bordered">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Approver</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Comment</th>
                                            <th scope="col">Action Taken On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach($request['history'] as $his){ 
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
                                                        else if($note['status'] == 'cancelled' || $note['status'] == 'cancel') $act = "Time-off cancelled";
                                                        //
                                                        if(isset($note['comment']) && $note['comment'] != '') $comment = $note['comment'];
                                                    } else{
                                                        $act = "";
                                                    }
                                                    //
                                                    if($his['action'] == 'update' && $act == '') {
                                                        $act = "Time-off updated.";
                                                    }
                                                }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <h4><?=$his['first_name'];?> <?=$his['last_name'];?></h4>
                                                        <p><?=remakeEmployeeName($his);?></p>
                                                        <p>
                                                            <a>
                                                                Id:<?=!empty($his['employee_number']) ? $his['employee_number'] : $his['userId'];?>
                                                            </a>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p><?=$act;?></p>
                                                    </td>
                                                    <td>
                                                        <p><?=$comment;?></p>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            <?=formatDate(
                                                                $his['created_at'],
                                                                'Y-m-d H:i:s',
                                                                'M d Y, D H:i:s'
                                                            ) ;?>
                                                        </p>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if(count($request['approvers'])) { ?>
                    <!--  -->
                    <div class="panel panel-theme">
                        <div class="panel-heading"><strong>Approver(s)</strong></div>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Approver</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Comment</th>
                                        <th scope="col">Action Taken On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach($request['approvers'] as $approver){ 
                                            //
                                            $resp = !isset($handledApproval[$approver['userId']]) ? ['Status' => 'Pending', 'Date' => '-', 'Comment' => '-'] : $handledApproval[$approver['userId']];
                                            ?>
                                            <tr>
                                                <td>
                                                    <h4><?=$approver['first_name'];?> <?=$approver['last_name'];?></h4>
                                                    <p><?=remakeEmployeeName($approver);?></p>
                                                    <p>
                                                        <a>
                                                            Id:<?=!empty($approver['employee_number']) ? $approver['employee_number'] : $approver['userId'];?>
                                                        </a>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-<?=$resp['Status'] == 'Approved' ? 'success' : 'danger';?>"><strong><?=strtoupper($resp['Status']);?></strong></p>
                                                </td>
                                                <td>
                                                    <p><?=$resp['Comment'];?></p>
                                                </td>
                                                <td>
                                                    <p><?=$resp['Date'] != '-' ? formatDate(
                                                            $resp['Date'],
                                                            'Y-m-d H:i:s',
                                                            'M d Y, D H:i:s'
                                                        ) : '-';?></p>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }?>

                    <?php if(count($policies)) { ?>
                        <!--  -->
                        <div class="panel panel-theme">
                            <div class="panel-heading"><strong>As Of Today</strong></div>
                            <div class="panel-body">
                                <table class="table table-striped table-bordered">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Policy Title</th>
                                            <th scope="col">Remaining Time</th>
                                            <th scope="col">Employment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($policies as $policy){
                                            if(!empty($policy['Reason'])) {
                                                 continue;
                                            }
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
                    <?php }?>

                    
                    <div class="panel panel-theme">
                        <div class="panel-heading">
                            <strong>Action</strong>
                        </div>

                        <div class="panel-body">
                            <?php if ($user_type == 'approvers' && $request['status'] != 'pending') { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php 
                                            if($request['status'] === 'cancelled'){
                                                ?>
                                                <p>The time-off was canceled by "<?=$request['first_name'];?> <?=$request['last_name'];?>" with the following comment.</p>
                                                <?php
                                            } else{
                                                ?>
                                                <p>The time-off "<?=$lastApprover['Status'];?>" was made by "<?=$lastApprover['Name'];?>" with the following comment.</p>
                                                <?php
                                            }
                                        ?>
                                            <p>"<?=$lastApprover['Comment'];?>"</p>
                                    </div>
                                </div>
                            <?php } ?>    

                            <hr />

                            <div class="row">
                                <div class="col-sm-12">
                                    <?php if ($allow_update == 'no') { ?>
                                        <label>Message</label>
                                        <p>The Request is already submitted</p>
                                    <?php } else { ?>
                                        <form action="" method="POST">
                                            <input type="hidden" name="company_sid" value="<?php echo $company_sid; ?>">
                                            <input type="hidden" name="employer_sid" value="<?php echo $employerId; ?>">
                                            <input type="hidden" name="employee_sid" value="<?php echo $employee_sid; ?>">
                                            <input type="hidden" name="request_sid" value="<?php echo $requestId; ?>">
                                            <input type="hidden" name="status" value="<?php echo $request_status; ?>">
                                            <label>Comment <small>(Write a note, why you are approving/rejecting this request)</small></label>
                                            <textarea class="ckeditor" id="jsCommentEdit" name="comment"
                                                style="margin-bottom: 10px;"></textarea>
                                            <?php echo form_error("comment"); ?>
                                            <?php if ($request['status'] == 'rejected' ||  $request_status == 'approved') { ?>
                                            <button style="margin-top: 10px" type="submit"
                                                class="btn btn-orange form-control jsRequestBtn"><i class="fa fa-clock-o"
                                                    aria-hidden="true"></i>&nbsp;Approve Time Off</button>
                                            <?php } else if ($request['status'] == 'approved'||  $request_status == 'rejected') { ?>
                                            <button style="margin-top: 10px" type="submit"
                                                class="btn alert-danger btn-theme form-control jsRequestBtn"><i
                                                    class="fa fa-times-circle-o" aria-hidden="true"></i>&nbsp;Reject Time Off</button>
                                            <?php } else if ($request['status'] == 'pending') { ?> 
                                                <button style="margin-top: 10px" type="submit"
                                                class="btn alert-danger btn-theme form-control jsRequestBtn"><i
                                                    class="fa fa-times-circle-o" aria-hidden="true"></i>&nbsp;Cancel Time Off</button>       
                                            <?php } ?>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<script>
CKEDITOR.config.toolbar = [
    ['Bold', 'Italic', 'Underline']
];
</script>
