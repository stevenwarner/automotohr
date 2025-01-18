<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-title">Company Name : <?php echo $com_emp[0]['CompanyName']?> </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-title"> Reported By &nbsp;&nbsp;: <?php echo $que_ans[0]['report_type'] == 'confidential' ? ucwords($com_emp[0]['fname'] . " " . $com_emp[0]['lname']) : 'Anonymous';?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/reports/incident_reporting/reported_incidents')?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                        </div>
                                    </div>
                                        <?php if(sizeof($que_ans)>0) { 
                                            foreach ($que_ans as $q_a) {
                                                ?>
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <b><?= $q_a['question']; ?></b>
                                                                <tbody>
                                                                <!--All records-->
                                                                    <tr>
                                                                        <td>
                                                                            <?php
                                                                                if ($q_a['question'] == 'signature') {
                                                                            ?>    
                                                                                    <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>px;" src="<?php echo $q_a['answer']; ?>"  />
                                                                            
                                                                            <?php    
                                                                                } else {
                                                                                    $ans = @unserialize($q_a['answer']);
                                                                                    if ($ans !== false) {
                                                                                        echo implode(',', $ans);
                                                                                    } else {
                                                                                        echo ucfirst($q_a['answer']);
                                                                                    }
                                                                                }
                                                                                
                                                                            ?>
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <b>Report Type</b>
                                                                <tbody>
                                                                <!--All records-->
                                                                <tr>
                                                                    <td>
                                                                        <?= ucfirst($que_ans[0]['report_type']);?>
                                                                    </td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <b>Related Documents</b>
                                                                <tbody>
                                                                <?php if(sizeof($files)>0){
                                                                    foreach($files as $file){?>
                                                                        <tr>
                                                                            <td><a href="<?php echo AWS_S3_BUCKET_URL . $file['file_code']?>" download="docs"><?php echo $file['file_name']; ?></a></td>
                                                                        </tr>
                                                                    <?php }
                                                                } else{?>
                                                                    <tr>
                                                                        <td>No Documents Found</td>
                                                                    </tr>
                                                                <?php }?>
                                                                </tbody>
                                                            </table>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">                       
                                                    <b>Related Response</b>
                                                    <div class="respond">
                                                        <?php if(sizeof($comments)>0){
                                                            foreach($comments as $comment){
                                                                $name = empty($comment['user2']) ? ucfirst($comment['user1']) : ucfirst($comment['user2']);
                                                                $pp = empty($comment['user2']) ? $comment['pp1'] : $comment['pp2'];
                                                                $url = empty($pp) ? base_url() . "assets/images/attachment-img.png" : AWS_S3_BUCKET_URL . $pp;


                                                                ?>
                                                                <article <?php echo empty($comment['user2']) ? '' : 'class="reply"';?>>
                                                                    <figure><img class="img-responsive" src="<?= $url ?>"></figure>
                                                                    <div class="text">
                                                                        <div class="message-header">
                                                                            <div class="message-title"><h2><?php echo $name . " (" . $comment['response_type'] . ')';?></h2></div>
                                                                            <ul class="message-option">
                                                                                <li>
                                                                                    <time><?php echo date('d M, Y',strtotime($comment['date_time']));?></time>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <!--                                            <span class="span">This is CV</span>-->
                                                                        <p><?php echo strip_tags($comment['comment']);?></p>
                                                                    </div>
                                                                </article>
                                                            <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td>No Response Found</td>
                                                            </tr>
                                                        <?php }?>
                                                    </div>                                                    
                                                </div>
                                            </div>

<!--                                            <div class="hr-box assigned-admin">-->
<!--                                                <div class="hr-innerpadding">-->
<!--                                                    <form method="POST" id="assigned-admin">-->
<!--                                                        <div class="field-row field-row-autoheight">-->
<!--                                                            <label for="admin">Assigned To</label>-->
<!--                                                            <select data-rule-required="true" id="admin" name="admin[]" multiple="multiple" class="hr-form-fileds">-->
<!--                                                                --><?php //foreach($com_admins as $admin){?>
<!--                                                                    <option value="--><?php //echo $admin['sid']?><!--" --><?//= in_array($admin['sid'],$assigned_admins) ? 'selected="selected"': ""?><!--><?php //echo $admin['first_name']." ".$admin['last_name'];?><!--</option>-->
<!--                                                                --><?php //}?>
<!--                                                            </select>-->
<!--                                                        </div>-->
<!--                                                        <div class="row">-->
<!--                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">-->
<!--                                                                <input type="submit" value="Assign" name="assign" class="btn btn-success pull-right">-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!---->
<!--                                                    </form>-->
<!--                                                </div>-->
<!--                                            </div>-->

                                            <?php } else{ ?>
                                                <div class="hr-box">
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                        <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <tbody>
                                                                <!--All records-->
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="no-data">No Incident Reported Yet</span>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </form>
                                                    </div>
                                                    </div>
                                                </div>

                                            <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $('#admin').chosen();
        $('#assigned-admin').submit(function () {
            if($('#admin').val()==null){
                alertify.error('Please Assign An Admin');
                return false;
            }
        });
    });
</script>