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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i><?php echo $page_title;?></h1>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <form method="post" class="private-msg">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Company Name / Contact Status</th>
                                                        <th>Demo Source</th>
                                                        <th>Date</th>
                                                        <th width="1%" class="actions" colspan="5">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php  if(sizeof($referred)>0) {
                                                            foreach ($referred as $affiliation) {  ?>
                                                                <tr>
                                                                    <td>
                                                                        <?= ucwords($affiliation['company_name'])?>&nbsp;
                                                                        <?php 
                                                                            if ($page == 'affiliate' && $affiliation["status"] == 0) { 
                                                                        ?>
                                                                                <img src="<?= base_url() ?>assets/images/new_msg.gif">
                                                                        <?php 
                                                                            } 
                                                                        ?>
                                                                        <br><br><!--<b>Contact Status:</b> -->
                                                                        <div class="candidate-status applicat-status-edit">
                                                                                <div class="label-wrapper-outer">
                                                                                <?php   $status_found = false;
                                                                                        foreach ($application_status as $status_code) { 
                                                                                            if ($status_code['css_class'] == $affiliation["contact_status"]) {
                                                                                                $status_found = true; ?>
                                                                                                <div class="selected <?php echo $affiliation["contact_status"] ?>">
                                                                                                    <?= $status_code['name'] ?>
                                                                                                </div>
                                                                                    <?php   } 
                                                                                        }
                                                                                   
                                                                                            if(!$status_found) { ?>
                                                                                                <div class="selected not_contacted">
                                                                                                    No Status Found!
                                                                                                </div>
                                                                                    <?php   } ?>
                                                                                    <div class="show-status-box" title="Edit Contact Status">
                                                                                        <i class="fa fa-pencil"></i>
                                                                                    </div>
                                                                                    <div class="lable-wrapper">
                                                                                        <div id="id" style="display:none;">
                                                                                            <?= $affiliation["sid"] ?>
                                                                                        </div>
                                                                                        <div style="height:20px;">
                                                                                            <i class="fa fa-times cross"></i>
                                                                                        </div>

                                                                                        <?php 
                                                                                            foreach ($application_status as $status) { 
                                                                                        ?>
                                                                                                <div data-status_sid="<?php echo $affiliation['sid']; ?>" data-status_class="<?php echo $status['css_class']; ?>" data-status_name="<?php echo $status['css_class']; ?>" class="label applicant <?php echo $status['css_class']; ?>">
                                                                                                    <div id="status"><?php echo $status['name']; ?></div>
                                                                                                    <i class="fa fa-check-square check"></i>
                                                                                                </div>
                                                                                        <?php
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    </td>
                                                                    <td>
                                                                        <?= $affiliation['email']?><br>
                                                                        <?php echo date_with_time($affiliation['date_requested']); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $affiliation['country']?> /
                                                                        <?php           
                                                                            if($affiliation['status'] == 1) {
                                                                                echo '<b style="color: green">Accepted</b>';
                                                                            } else if($affiliation['status'] == 2) {
                                                                                echo '<b style="color: red">Rejected</b>';
                                                                            } else if($affiliation['status'] == 3) {
                                                                                echo '<b style="color: red">Already Exist</b>';
                                                                            } else {
                                                                                echo '<b style="color: blue">Pending</b>';
                                                                            } 
                                                                        ?>
                                                                        <?php  if($uri_segment == "referred_affiliates" || $uri_segment == "referred_clients") { ?>
                                                                            <br><?php echo $affiliation['full_name'] ; }?>
                                                                    </td>
                                                                    <td colspan="3" class="text-center">
                                                                        <a href="<?php echo base_url('manage_admin/referred_clients/view_details'); ?>/<?= $affiliation["sid"] ?>"class="btn btn-info btn-sm" title="View Details">View Details</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="no-data">No Referral Found</span>   
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function todo(action, id) {
        url = "<?= base_url() ?>manage_admin/private_messages/message_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Message?",
            function () {
                $.post(url, {action: action, sid: id})
                        .done(function (data) {
                            messagesCount = parseInt($(".messagesCounter").html());
                            messagesCount--;
                            $(".messagesCounter").html(messagesCount);

                            alertify.success('Selected message have been ' + action + 'd.');
                            $("#parent_" + id).remove();
                        });

            },
            function () {
                alertify.error('Canceled');
            });
    }


    $(document).ready(function () {
        $('.show-status-box').click(function () {
            $(this).next().show();
        });

        $('.selected').click(function () {
            $(this).next().next().css("display", "block");
        });

//        $('.candidate').click(function () {
//            $(this).parent().find('.check').css("visibility", "hidden");
//            $(this).parent().prev().html($(this).find('#status').html());
//            $(this).find('.check').css("visibility", "visible");
//            $(this).parent().prev().css("background-color", $(this).css("background-color"));
//            var status = $(this).find('#status').html();
//            var id = $(this).parent().find('#id').html();
//            console.log('trigger update');
//            console.log(status);
//            console.log(id);
////            url = "<?= base_url() ?>application_tracking_system/update_status";
////            $.post(url, {"id": id, "status": status, "action": "ajax_update_status_candidate"})
////                .done(function (data) {
////                    alertify.success("Candidate status updated successfully.");
////                });
//        });

        $('.candidate').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");
        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function () {

            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().prev().html($(this).find('#status').html());
            $(this).parent().prev().prev().css("background-color", $(this).css("background-color"));
            var id = $(this).parent().find('#id').html();
            var status_name = $(this).attr('data-status_name');
            var message_sid = $(this).attr('data-status_sid');
                        var my_url = "<?= base_url() ?>manage_admin/referred_clients/ajax_handler";
           
            var my_request;

            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: {
                    "id": id,
                    "status": status_name,
                    "message_sid": message_sid,
                    "action": "ajax_update_status"}
            });

            my_request.done(function (response) {
                if (response == 'success') {
                    alertify.success("Contact status updated successfully.");
                } else {
                    alertify.error("Could not update Contact Status.");
                }
            });
        });

        $('.applicant').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function () {
            $(this).parent().css("display", "none");
        });

        $.each($(".selected"), function () {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });

        $('.show-status-box, .selected').click(function () { 
            $(this).closest("tr").css({'height': '550px'}); 
        });
        $('.cross, .applicant').click(function () { 
            $(this).closest("tr").css({'height': 'auto'}); 
        });
    });
</script>