<div class="job-main-content">
    <div class="job-container">
        <div class="job-feature-main">
            <div class="portalmid">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <h2>
                    <span><img src="<?= base_url() ?>assets/images/candidates4.png" alt="image"></span>
                    Manual Candidates 
                    <div class="job_top_section">
                        <a class="siteBtn redBtn" id="" href="<?= base_url() ?>dashboard">&laquo; BACK</a>
                    </div>
                </h2>         
                <div class="candidate-applied">
                    <!--//----------------Manual Candidates Starts-------------------->
                    <div id="hide_questions" class="candidate-record">
                        <span class="heading_application_tracking">Manual Candidates
                            <div class="jobsearch_box">
                                <a href="<?= base_url() ?>manual_candidate" class="siteBtn jbBtn" style="margin-right:5px;">+ Add Contact</a>
                            </div>
                        </span>
                        <table data-tablesaw-mode="stack">
                            <thead>
                                <tr>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="title mb_pf">Name</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">E-Mail</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Attachments</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Jobs</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Status</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($manual_candidates)) { ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="6">
                                            <h3 style="text-align: center;">No Contacts found! </h3>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                <form method="POST" name="ej_form" id="ej_form">
                                    <?php foreach ($manual_candidates as $item) { ?>
                                        <tr id="manual_row<?= $item["sid"] ?>">
                                            <td>
                                                <a href="javascript:;" onclick="view_contact(<?= $item["sid"] ?>)">
                                                    <?= $item["first_name"] ?> <?= $item["last_name"] ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?= $item["email"] ?>
                                            </td>

                                            <td>
                                                <a href="<?= $item["resume"] ?>" class="btn btn-info btn-xs <?php if ($item["resume"] == "javascript:;"){ ?>disabled <?php } ?>">View Attached Resume</a>
                                                    <br><a class="btn btn-info btn-xs <?php if ($item["cover_letter"] == "javascript:;"){ ?>disabled <?php } ?>" href="<?= $item["cover_letter"] ?>">View Cover Letter</a>
                                            </td>
                                            <td>
                                                <?= $item["Title"] ?>
                                            </td>
                                            <td>
                                                <div class="label-wrapper-outer">
                                                    <?php if ($item["status"] == 'Contacted') { ?>
                                                        <div class="selected contacted"><?= $item["status"] ?></div>
                                                    <?php } elseif ($item["status"] == 'Candidate Responded') { ?>
                                                        <div class="selected responded"><?= $item["status"] ?></div>
                                                    <?php } elseif ($item["status"] == 'Qualifying') { ?>
                                                        <div class="selected qualifying"><?= $item["status"] ?></div>
                                                    <?php } elseif ($item["status"] == 'Submitted') { ?>
                                                        <div class="selected submitted"><?= $item["status"] ?></div>
                                                    <?php } elseif ($item["status"] == 'Interviewing') { ?>
                                                        <div class="selected interviewing"><?= $item["status"] ?></div>
                                                    <?php } elseif ($item["status"] == 'Offered Job') { ?>
                                                        <div class="selected offered"><?= $item["status"] ?></div>
                                                    <?php } elseif ($item["status"] == 'Not In Consideration') { ?>
                                                        <div class="selected notin"><?= $item["status"] ?></div>
                                                    <?php } elseif ($item["status"] == 'Client Declined') { ?>
                                                        <div class="selected decline"><?= $item["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Placed/Hired' || $applicant_info["status"] == 'Ready to Hire') { ?>
                                                        <div class="selected placed">Ready to Hire</div>
                                                    <?php } elseif ($item["status"] == 'Not Contacted Yet') { ?>
                                                        <div class="selected not_contacted"><?= $item["status"] ?></div>
                                                    <?php } ?>
                                                    <div class="lable-wrapper">
                                                        <div id="id" style="display:none;"><?= $item["sid"] ?></div>
                                                        <div style="height:20px;"><i class="fa fa-times cross"></i></div>
                                                        <div class="label candidate not_contacted">
                                                            <div id="status">Not Contacted Yet</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate contacted">
                                                            <div id="status">Contacted</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate responded">
                                                            <div id="status">Candidate Responded</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate qualifying">
                                                            <div id="status">Qualifying</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate submitted">
                                                            <div id="status">Submitted</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate interviewing">
                                                            <div id="status">Interviewing</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate offered">
                                                            <div id="status">Offered Job</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate notin">
                                                            <div id="status">Not In Consideration</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate decline">
                                                            <div id="status">Client Declined</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                        <div class="label candidate placed">
                                                            <div id="status">Ready to Hire</div>
                                                            <i class="icon-check-sign check"></i></div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td class="action_td">
                                                <a href="<?= base_url() ?>edit_candidate/<?= $item["sid"] ?>">
                                                    <img src="<?= base_url() ?>assets/images/edit_icon1.png" class="edit" width="20" height="18" alt="edit contact">
                                                </a>
                                                <a href="javascript:;" id="<?= $item["sid"] ?>"  onclick="delete_manual_contact(<?= $item["sid"] ?>);">
                                                    <img src="<?= base_url() ?>assets/images/delete_icon2.png" width="20" height="18" class="delete" alt="delete contact">
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </form>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!--//----------------Manual Candidates Ends-------------------->
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function delete_manual_contact(id) {
        alertify.confirm("Please Confirm Delete", "Are you sure you want to delete manual contact?",
                function () {
                    $.ajax({
                        url: "<?= base_url() ?>application_tracking_system?del_id=" + id + "&action=del_manual_contact",
                        success: function (data) {
                            $('#manual_row' + id).hide();
                            alertify.notify('Success: <br><br>Manual contact is removed from your list', 'success');
                        }
                    });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }

    $(document).ready(function () {



        $('#pmmc_all_check').click(function () {
            if ($('#pmmc_all_check').is(":checked")) {
                $('.ej_checkbox:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.ej_checkbox:checkbox').each(function () {
                    this.checked = false;
                });
            }
        });



        $('#ej_controll_delete').click(function () {
            var butt = $(this);
            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to delete application(s)?",
                            function () {
                                $('#ej_form').append('<input type="hidden" name="delete_contacts" value="true" />');
                                $("#ej_form").submit();
                                alertify.success('Deleted');

                            },
                            function () {
                                alertify.error('Cancel');
                            });
                }
            } else {
                alertify.alert('Please select application(s) to delete');
            }
        });
        $('#ej_controll_hire').click(function () {
            var butt = $(this);
            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want Hire applicant(s)?",
                            function () {
                                $('#ej_form').append('<input type="hidden" name="hire_contacts" value="true" />');
                                $("#ej_form").submit();
                                alertify.success('Hired');
                            },
                            function () {
                                alertify.error('Cancel');
                            });
                }
            } else {
                alertify.alert('Please select applicant(s) to Hire');
            }
        });
        $('#ej_controll_decline').click(function () {
            var butt = $(this);
            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want Decline applicant(s)?",
                            function () {
                                $('#ej_form').append('<input type="hidden" name="decline_contacts" value="true" />');
                                $("#ej_form").submit();
                                alertify.success('Declined');
                            },
                            function () {
                                alertify.error('Cancel');
                            });
                }
            } else {
                alertify.alert('Please select applicant(s) to Decline');
            }
        });

        $('.selected').click(function () {
            $(this).next().css("display", "block");
        });

        $('.candidate').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            status = $(this).find('#status').html();
            id = $(this).parent().find('#id').html();

            $.ajax({
                url: "<?= base_url() ?>application_tracking_system?id=" + id + "&status=" + status + "&action=ajax_update_status_candidate",
                success: function (data) {
                    console.log('done');
                }
            });

        });
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
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            status = $(this).find('#status').html();
            id = $(this).parent().find('#id').html();

            $.ajax({
                url: "<?= base_url() ?>application_tracking_system?id=" + id + "&status=" + status + "&action=ajax_update_status",
                success: function (data) {
                    console.log('done');
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
        $.each($(".selected"), function () {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });
    });
</script>