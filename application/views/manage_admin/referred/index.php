<?php
$end_offset = $offset + $per_page;
if ($end_offset > $total_rows) {
    $end_offset = $total_rows;
}
if ($offset == 0) {
    $offset ++;
}
?>
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
<!--                                    <div class="add-new-promotions">
                                        <a class="site-btn" href="<?php //echo site_url('manage_admin/free_demo/add_demo_request');?>"> Add Potential Client </a> 
                                    </div>-->
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">

                                                <div class="hr-box-header">
                                                    <div class="hr-items-count">
                                                        <strong class="messagesCounter"><?php echo $total; ?></strong> Enquire(s)
                                                    </div>
                                                </div>

                                            <?php echo( $links ); ?>
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
                                                    <?php if($total==0) {
                                                        echo '<tr><td colspan="5">No Enquiries Found!</td></tr>';
                                                    } ?>
                                                    <?php foreach ($messages as $message) {
                                                        
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $message["company_name"]; ?> &nbsp;
                                                                <?php 
                                                                    if ($page == 'inbox' && $message["status"] == 0) { 
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
                                                                                if ($status_code['css_class'] == $message["contact_status"]) {
                                                                                    $status_found = true; ?>
                                                                                    <div class="selected <?php echo $message["contact_status"] ?>">
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
                                                                                <?= $employer_job["sid"] ?>
                                                                            </div>
                                                                            <div style="height:20px;">
                                                                                <i class="fa fa-times cross"></i>
                                                                            </div>

                                                                            <?php 
                                                                                foreach ($application_status as $status) { 
                                                                            ?>
                                                                                    <div data-status_sid="<?php echo $message['sid']; ?>" data-status_class="<?php echo $status['css_class']; ?>" data-status_name="<?php echo $status['css_class']; ?>" class="label applicant <?php echo $status['css_class']; ?>">
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
                                                                <?php if ($message["ppc"] == 0) { ?>
                                                                    <?php if ($message["is_reffered"] == 1) { ?>
                                                                        Affiliate Referred By: <?php echo getreferralusername($message["refferred_by_sid"]); ?>
                                                                    <?php } else {?>
                                                                        SCHEDULE YOUR FREE DEMO
                                                                    <?php } ?>
                                                                    
                                                                <?php } elseif ($message["ppc"] == 1) { ?>
                                                                    PPC Demo
                                                                <?php } ?>
                                                            </td>
                                                            <td><?php echo date_with_time($message["date_requested"]); ?></td>
                                                            <?php if ($page == 'inbox') { ?>
                                                                <td><a href="<?php echo base_url('manage_admin/referred_clients/view_details'); ?>/<?php echo $message["sid"]; ?>"><input type="button" class="site-btn" value="View Details"></a></td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>

                                                <div class="hr-box-header hr-box-footer">
                                                    <p><?php if($total_rows != 0) { echo 'Displaying <b>' . $offset . ' - ' . $end_offset . '</b> of ' . $total_rows . ' records'; }?></p>
                                                </div>
                                                <?php echo( $links ); ?>
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
            //var status = $(this).find('#status').html();
            var id = $(this).parent().find('#id').html();
            var status_name = $(this).attr('data-status_name');
            //var message_sid = '<?php echo $message["sid"]?>';
            var message_sid = $(this).attr('data-status_sid');
            // alert(message_sid);
            // console.log('trigger update');
           // console.log(status_name + ' ' + status_sid);
            var my_url = "<?= base_url() ?>manage_admin/free_demo/ajax_handler";
           
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