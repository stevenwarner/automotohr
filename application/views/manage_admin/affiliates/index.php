<style>
    .deleteaffiliate {}
</style>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <!--                                            --><?php //if (check_access_permissions_for_view($security_details, 'add_new_type')) { 
                                                                                                ?>
                                            <!--                                                <a id="search_btn" href="--><?php //echo base_url('manage_admin/reports/incident_reporting/add_new_type')
                                                                                                                            ?>
                                            <!--" class="btn btn-success"><i class="fa fa-plus-square"> </i> Add New Type</a>-->
                                            <!--                                            --><?php //} 
                                                                                                ?>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <!--  -->
                                            <div class="row">
                                                <div class="col-sm-12 text-right">
                                                    <button class="btn btn-danger jsDeleteSelected">Delete Selected</button>
                                                </div>
                                                <br>
                                                <br>
                                            </div>
                                            <div class="table-responsive">
                                                <form name="multiple_actions" id="incident_types" method="POST">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th style="vertical-align: middle">
                                                                    <label class="control--checkbox">
                                                                        <input type="checkbox" name="affiliates_all" style="width: 20px; height: 20px" />
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </th>
                                                                <th>Name / Contact Status</th>
                                                                <th>Email / Date Applied</th>
                                                                <th>Country / Status
                                                                    <?php if ($uri_segment == "referred_affiliates" || $uri_segment == "referred_clients") { ?>
                                                                        <br>Referred By
                                                                    <?php  } ?>
                                                                </th>
                                                                <th class="last-col" width="1%" colspan="5">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (sizeof($affiliations) > 0) {
                                                                foreach ($affiliations as $affiliation) {  ?>
                                                                    <tr>
                                                                        <td>
                                                                            <label class="control--checkbox">
                                                                                <input type="checkbox" name="affiliates_ids" value="<?=$affiliation['sid'];?>"  style="width: 20px; height: 20px" />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </td>
                                                                        <td>
                                                                            <?= ucwords($affiliation['first_name'] . ' ' . $affiliation['last_name']) ?>&nbsp;
                                                                            <?php
                                                                            if ($page == 'affiliate' && $affiliation["status"] == 0) {
                                                                            ?>
                                                                                <img src="<?= base_url() ?>assets/images/new_msg.gif">
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <br><br>
                                                                            <!--<b>Contact Status:</b> -->
                                                                            <div class="candidate-status applicat-status-edit">
                                                                                <div class="label-wrapper-outer">
                                                                                    <?php $status_found = false;
                                                                                    foreach ($application_status as $status_code) {
                                                                                        if ($status_code['css_class'] == $affiliation["contact_status"]) {
                                                                                            $status_found = true; ?>
                                                                                            <div class="selected <?php echo $affiliation["contact_status"] ?>">
                                                                                                <?= $status_code['name'] ?>
                                                                                            </div>
                                                                                        <?php   }
                                                                                    }

                                                                                    if (!$status_found) { ?>
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
                                                                            <?= $affiliation['email'] ?><br>
                                                                            <?php echo date_with_time($affiliation['request_date']); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $affiliation['country'] ?> /
                                                                            <?php
                                                                            if ($affiliation['status'] == 1) {
                                                                                echo '<b style="color: green">Accepted</b>';
                                                                            } else if ($affiliation['status'] == 2) {
                                                                                echo '<b style="color: red">Rejected</b>';
                                                                            } else if ($affiliation['status'] == 3) {
                                                                                echo '<b style="color: red">Already Exist</b>';
                                                                            } else {
                                                                                echo '<b style="color: blue">Pending</b>';
                                                                            }
                                                                            ?>
                                                                            <?php if ($uri_segment == "referred_affiliates" || $uri_segment == "referred_clients") { ?>
                                                                                <br><?php echo $affiliation['full_name'];
                                                                                } ?>
                                                                        </td>
                                                                        <?php if ($affiliation['status'] == 2 || $affiliation['status'] == 0) { ?>
                                                                            <?php if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_accept')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_accept'))) { ?>
                                                                                <td>
                                                                                    <a href="javascipt:;" id="<?= $affiliation['sid'] ?>" class="btn btn-success btn-sm accept" title="Accept">Accept</a>
                                                                                </td>
                                                                            <?php }
                                                                            if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_reject')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_reject'))) { ?>
                                                                                <td>
                                                                                    <a href="javascipt:;" id="<?= $affiliation['sid'] ?>" class="btn btn-danger btn-sm reject" title="Reject">Reject</a>
                                                                                </td>
                                                                        <?php }
                                                                        } ?>

                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm deleteaffiliate" alliliateid="<?= $affiliation['sid'] ?>">Delete</button>
                                                                        </td>

                                                                        <?php if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_view')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_view'))) { ?>
                                                                            <td colspan="3" class="text-center">
                                                                                <a href="<?php echo base_url('manage_admin/' . $uri_segment . '/view_details/' . $affiliation['sid']) ?>" class="btn btn-info btn-sm" title="View Details">View Details</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="8" class="text-center">

                                                                        <?php if ($uri_segment == "referred_affiliates" || $uri_segment == "referred_clients") { ?>
                                                                            <span class="no-data">No Affiliate Referral Found</span>
                                                                        <?php  } else { ?>
                                                                            <span class="no-data">No Affiliate Request Found</span>
                                                                        <?php  } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="execute" value="multiple_action">
                                                    <input type="hidden" id="type" name="type" value="employer">
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
</div>


<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>
<!-- Loader End -->


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        //
        var affiliateDeleteIds = [];
        var currentId = 0;
        var xhr = null;

        //
        $('.jsDeleteSelected').click(function(event){
            //
            event.preventDefault();
            //
            SetAffiliateIds();
            //
            if(affiliateDeleteIds.length === 0){
                return alertify.alert(
                    'Warning!',
                    'Please select at least one affiliate.'
                )
            }
            //
            return alertify.confirm(
                'This action is non-revertible. <br/> Are you want you want to delete?',
                function(){
                    DeleteAffiliateRequests();
                }
            );
        });

        // Deletes an affiliate request
        $('.deleteaffiliate').click(function() {
            //
            affiliateDeleteIds = [];
            affiliateDeleteIds.push($(this).attr('alliliateid'));
            //
            xhr = null;
            //
            alertify.confirm('Confirmation', "This action is non-revertible. <br> Are you sure you want to delete this affiliate?",
                function() {
                    //
                    DeleteAffiliateRequests();
                }, function(){});
        });

        //
        function DeleteAffiliateRequests() {
            //
            if (affiliateDeleteIds[currentId] === undefined) {
                return MarkDone();
            }
            //
            $('#loader_text_div').text('Please wait, while we process your request.');
            $('#document_loader').show();
            //
            DeleteAffiliateRequest(currentId);
            //
            currentId++;
        }

        //
        function MarkDone() {
            //
            $('#loader_text_div').text('');
            //
            $('#document_loader').hide();
            //
            return alertify.alert('Affiliate is successfully deleted', function() {
                location.reload();
            });
        }

        //
        function DeleteAffiliateRequest(index) {
            //
            $.ajax({
                type: 'POST',
                data: {
                    id: affiliateDeleteIds[index]
                },
                url: '<?= base_url('manage_admin/' . $uri_segment . '/delete_affiliate') ?>',
                success: function(data) {
                    DeleteAffiliateRequests();
                }
            });
        }

        //
        $('input[name="affiliates_all"]').click(function(){
            //
            $('input[name="affiliates_ids"]').prop('checked', $(this).prop('checked'));
            //
            if(!$(this).prop('checked')){
                return affiliateDeleteIds = [];
            }
            //
            SetAffiliateIds();
        });
        //
        $('input[name="affiliates_ids"]').click(function(){
            //
            $('input[name="affiliates_all"]').prop('checked', $('input[name="affiliates_ids"]:checked').length === $('input[name="affiliates_ids"]').length);
            SetAffiliateIds();
        });

        function SetAffiliateIds(){
            //
            affiliateDeleteIds = [];
            //
            $('input[name="affiliates_ids"]:checked').map(function(){
                //
                affiliateDeleteIds.push($(this).val());
            });
        }

        //
        $('.accept').click(function() {
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Accept this Application?",
                function() {
                    $.ajax({
                        type: 'POST',
                        data: {
                            status: 1,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/' . $uri_segment . '/accept_reject') ?>',
                        success: function(data) {
                            if (data == 'exist') {
                                window.location.href = '<?php echo base_url('manage_admin/' . $uri_segment . '/view_details/') ?>/' + id;
                            } else {
                                window.location.href = '<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency/') ?>/' + data;
                            }
                        },
                        error: function() {

                        }
                    });
                },
                function() {
                    alertify.error('Canceled');
                });
        });

        $('.reject').click(function() {
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Reject this Application?",
                function() {
                    $.ajax({
                        type: 'POST',
                        data: {
                            status: 2,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/' . $uri_segment . '/accept_reject') ?>',
                        success: function(data) {
                            location.reload();
                        },
                        error: function() {

                        }
                    });
                },
                function() {
                    alertify.error('Canceled');
                });
        });

        $('.show-status-box').click(function() {
            $(this).next().show();
        });

        $('.selected').click(function() {
            $(this).next().next().css("display", "block");
        });

        $('.candidate').hover(function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");
        }, function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function() {

            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().prev().html($(this).find('#status').html());
            $(this).parent().prev().prev().css("background-color", $(this).css("background-color"));
            var id = $(this).parent().find('#id').html();
            var status_name = $(this).attr('data-status_name');
            var message_sid = $(this).attr('data-status_sid');
            var my_url = "<?= base_url() ?>manage_admin/<?= $this->uri->segment(2); ?>/ajax_handler";

            var my_request;

            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: {
                    "id": id,
                    "status": status_name,
                    "message_sid": message_sid,
                    "action": "ajax_update_status"
                }
            });

            my_request.done(function(response) {
                if (response == 'success') {
                    alertify.success("Contact status updated successfully.");
                } else {
                    alertify.error("Could not update Contact Status.");
                }
            });
        });

        $('.applicant').hover(function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function() {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function() {
            $(this).parent().css("display", "none");
        });

        $.each($(".selected"), function() {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });

        $('.show-status-box, .selected').click(function() {
            $(this).closest("tr").css({
                'height': '550px'
            });
        });
        $('.cross, .applicant').click(function() {
            $(this).closest("tr").css({
                'height': 'auto'
            });
        });


    });
</script>