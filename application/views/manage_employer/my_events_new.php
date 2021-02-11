<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">Calender / Events</span>
                        </div><?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="job-feature-main m_job">
                            <div class="portalmid">
                                <div id='calendar'></div>

                                <div id="my_loader" class="text-center my_loader">
                                    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                                    <div class="loader-icon-box">
                                        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                                        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
                                        </div>
                                    </div>
                                </div>

                                <div id="popup1" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header bg-header-green">
                                                <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Event Management</h4>
                                            </div>
                                            <form class="date_form" id="event_form" method="post">
                                                <input type="hidden" id="event_id" name="event_id" value="" />

                                                <div class="modal-body">
                                                    <div class="event-modal-inner">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <label style="margin-right: 20px;">Event For:</label>
                                                                        <label class="control control--radio">
                                                                            Applicant
                                                                            <input class="users_type" type="radio" id="users_type_applicant" name="users_type" value="applicant" />
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio">
                                                                            Employee
                                                                            <input class="users_type" type="radio" id="users_type_employee" name="users_type" value="employee" />
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                                <div id="employee_select" class="row">
                                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                                                        <div class="form-group">
                                                                            <label>Employee</label>
                                                                            <select class="form-control" id="employee_sid" name="employee_sid">
                                                                                <option value="#">Select an Employee</option>
                                                                                <?php foreach ($company_accounts as $account) { ?>
                                                                                    <option class="emp" id="emp_<?php echo $account['sid']; ?>" value="<?php echo $account["sid"] ?>">
                                                                                        <?php if ($employer_id == $account["sid"]) { ?>
                                                                                            You
                                                                                        <?php } else {
                                                                                            if($account["is_executive_admin"] == 1) {
                                                                                                $employee_type = "Executive Admin";
                                                                                            } else {
                                                                                                $employee_type = $account["access_level"];
                                                                                            }
                                                                                            echo $account["first_name"]."&nbsp;".$account["last_name"].' ('.$employee_type.')';
                                                                                        } ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label class="hidden-xs">&nbsp;</label>
                                                                            <a id="employee_profile_link" href="#" class="btn btn-success btn-block" target="_blank">Employee Profile</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="applicant_select" class="row">
                                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                                                        <div class="form-group">
                                                                            <label>Applicant</label>
                                                                            <select class="form-control" id="applicant_sid" name="applicant_sid">
                                                                                <option value="#">Select an Applicant</option>
                                                                                <?php foreach ($applicants as $contact) { ?>
                                                                                    <option value="<?= $contact['sid'] ?>">
                                                                                        <strong><?= $contact['first_name'] ?> <?= $contact['last_name'] ?></strong>
                                                                                        (<?= $contact['email'] ?>)
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label class="hidden-xs">&nbsp;</label>
                                                                            <a id="applicant_profile_link" href="#" class="btn btn-success btn-block" target="_blank">Applicant Profile</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <div class="form-group">
                                                                            <label>Event Title</label>
                                                                            <input type="text" id="title" name="title" placeholder="Enter title here" class="form-control eventtitle">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Category</label>
                                                                            <select id="category" name="category" class="form-control">
                                                                                <option value="call">Call</option>
                                                                                <option value="email">Email</option>
                                                                                <option value="meeting">Meeting</option>
                                                                                <option selected="selected" value="interview">Interview</option>
                                                                                <option value="personal">Personal</option>
                                                                                <option value="other">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Event Date</label>
                                                                            <input name="eventdate" id="eventdate" type="text" class="form-control datepicker" required="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Start Time</label>
                                                                            <input name="eventstarttime" id="eventstarttime" readonly="readonly" type="text" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>End Time</label>
                                                                            <input name="eventendtime" id="eventendtime" readonly="readonly" type="text" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <div class="form-group">
                                                                            <label>Participants</label>
                                                                            <select id="interviewer" class="form-control" multiple
                                                                                    name="interviewer[]" required="required">
                                                                                <?php foreach ($company_accounts as $account) { ?>
                                                                                    <option class="int" id="int_<?php echo $account['sid']; ?>" value="<?= $account["sid"] ?>">
                                                                                        <?php if ($employer_id == $account["sid"]) { ?>
                                                                                            You
                                                                                        <?php } else {
                                                                                            if($account["is_executive_admin"] == 1) {
                                                                                                $employee_type = "Executive Admin";
                                                                                            } else {
                                                                                                $employee_type = $account["access_level"];
                                                                                            }
                                                                                            echo $account["first_name"]."&nbsp;".$account["last_name"].' ('.$employee_type.')';
                                                                                        } ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="control control--checkbox">
                                                                        Comment for Interviewer
                                                                        <input id="interviewer_comment" class="interviewer_comment" value="1" name="commentCheck" type="checkbox" />
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row comment-div">
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Comment:</label>
                                                                    <textarea id="interviewerComment" name="comment" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr class="comment-div" />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="control control--checkbox">
                                                                        Message To Candidate
                                                                        <input class="message-to-candidate" id="candidate_msg" name="messageCheck" value="1" type="checkbox" />
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row message-div">
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Subject:</label>
                                                                    <input class="message-subject form-control" id="applicantSubject" name="subject" type="text" value="" placeholder="Enter Subject (required)" />
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Message:</label>
                                                                    <textarea id="applicantMessage" name="message" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Attachment:</label>
                                                                    <input type="file" id="messageFile" name="messageFile" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr class="message-div" />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Address:</label>
                                                                    <input class="form-control" name="address" id="address" type="text" value="" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="control control--checkbox">
                                                                        Meeting Call Details:
                                                                        <input id="goto_meeting" class="goto_meeting" value="1" name="goToMeetingCheck" type="checkbox" />
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row meeting-div">
                                                            <div class="col-xs-6">
                                                                <div class="form-group">
                                                                    <label>Meeting Call In #: </label>
                                                                    <input class="form-control" id="meetingCallNumber" name="meetingCallNumber" type="text" value="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <div class="form-group">
                                                                    <label>Meeting ID #: </label>
                                                                    <input class="form-control" id="meetingId" name="meetingId" type="text" value="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Webinar/Meeting Login URL: </label>
                                                                    <input class="form-control" id="meetingURL" name="meetingURL" type="text" value="" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" id="">
                                                    <div class="row">
                                                        <div class="col-xs-1">
                                                            <div id="loader" class="loader" style="display: none;">
                                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-11">
                                                            <input class="btn btn-default" name="event_submit" type="button" style="display: none;" value="Delete" id="delete">
                                                            <input class="btn btn-default" name="event_submit" type="button" style="display: none;" value="Cancel Event" id="cancel">
                                                            <input class="btn btn-default" name="event_submit" type="button" style="display: none;" value="Update" id="update">
                                                            <input class="btn btn-default" name="event_submit" style="display: none;" type="button" value="Save" id="save">
                                                            <a id="close_btn" href="javascript:;" class="btn btn-default" data-dismiss="modal">Close</a>
                                                        </div>
                                                    </div>
                                                    <h3 id="cancelled_message" style="display: none;" class="text-danger text-center">Event Cancelled!</h3>
                                                </div>
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
<link href="<?= base_url() ?>assets/calendar/fullcalendar.css" rel="stylesheet"/>
<link href="<?= base_url() ?>assets/calendar/fullcalendar.print.css" rel="stylesheet" media="print"/>
<script src="<?= base_url() ?>assets/calendar/moment.min.js"></script>
<script src="<?= base_url() ?>assets/calendar/fullcalendar.min.js"></script>

<script>
    $(document).ready(function () {
        $('body').on('click', '.users_type', function () {
            if ($(this).val() == 'applicant') {
                $('#applicant_sid').prop('disabled', false);
                $('#applicant_select').show();
                $('#employee_sid').prop('disabled', true);
                $('#employee_select').hide();
            } else {
                $('#applicant_sid').prop('disabled', true);
                $('#applicant_select').hide();
                $('#employee_sid').prop('disabled', false);
                $('#employee_select').show();
            }
        });

        $('#employee_sid').on('change', function(){
            var selected = $(this).val();

            var interviewer_val = $('#interviewer').val();

            for(emp in interviewer_val)
            {
                interviewer_val.remove(selected);
            }

            $('#interviewer').select2('val', interviewer_val);

            $('.int').prop('disabled', false);
            $('#interviewer').select2();

            $('#int_' + selected).prop('disabled', true);
            $('#interviewer').select2();

        });

        $('#interviewer').on('change', function(){
            var selected = $(this).val();



        });

        $('.users_type:checked').trigger('click');

        if ($('.users_type:checked').length <= 0) {
            $('#users_type_applicant').trigger('click');
        }

        $('body').on('change', '#employee_sid', function () {
            var selected = $(this).val();
            var url = '<?php echo base_url('employee_profile'); ?>/' + selected;
            $('#employee_profile_link').attr('href', url);
        });

        $('body').on('change', '#applicant_sid', function () {
            var selected = $(this).val();
            var url = '<?php echo base_url('applicant_profile'); ?>/' + selected;
            $('#applicant_profile_link').attr('href', url);
        });

        $('#interviewer').select2();

        $('#applicant_sid').select2();

        $('#employee_sid').select2();

        $('#candidate_msg').click(function () {
            if ($('#candidate_msg').is(":checked")) {
                $('.message-div').fadeIn();
                $('#applicantMessage').prop('required', true);
            } else {
                $('.message-div').hide();
                $('#applicantMessage').prop('required', false);
            }
        });

        $('.interviewer_comment').click(function () {
            if ($('.interviewer_comment').is(":checked")) {
                $('.comment-div').fadeIn();
                $('#interviewerComment').prop('required', true);
            } else {
                $('.comment-div').hide();
                $('#interviewerComment').prop('required', false);
            }
        });

        $('.goto_meeting').click(function () {
            if ($('.goto_meeting').is(":checked")) {
                $('.meeting-div').fadeIn();
                $('#meetingId').prop('required', true);
                $('#meetingCallNumber').prop('required', true);
                $('#meetingURL').prop('required', true);
            } else {
                $('.meeting-div').hide();
                $('#meetingId').prop('required', false);
                $('#meetingCallNumber').prop('required', false);
                $('#meetingURL').prop('required', false);
            }
        });

        $('#file_loader').click(function () {
            $('#select_new').hide();
            $('#update').hide();
            $('#save').hide();
            $('#title').val('');
            $('#eventdate').val('');
            $('#eventstarttime').val('');
            $('#eventendtime').val('');
            $('#file_loader').css("display", "none");
            $('#popup1').modal('hide');
            $('body').css("overflow", "auto");
        });

        $(".js-modal-close").click(function () {
            $('#select_new').hide();
            $('#update').hide();
            $('#save').hide();
            $('#title').val('');
            $('#eventdate').val('');
            $('#eventstarttime').val('');
            $('#eventendtime').val('');
            $('#popup1').modal('hide');
            $('#file_loader').css("display", "none");
        });

    });


    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function convertTime(time) {
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var AMPMposition = time.indexOf(":");
        var AMPM = time.substr(AMPMposition + 3, time.length);
        if (AMPM == "pm" && hours < 12) hours = hours + 12;
        if (AMPM == "am" && hours == 12) hours = hours - 12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if (hours < 10) sHours = "0" + sHours;
        if (minutes < 10) sMinutes = "0" + sMinutes;
        return sHours + ":" + sMinutes;
    }

    function tConvert(time) {
        // Check correct time format and split into components
        time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
        if (time.length > 1) { // If time format correct
            time = time.slice(1); // Remove full string match value
            time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join(''); // return adjusted time or original string
    }

    $(document).ready(function () {
        var eventt = new Event('main');
        //populating date and time in Popup
        $(".datepicker").datepicker({dateFormat: 'mm-dd-yy'}).val();
        $('#eventendtime').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {
                time = $('#eventstarttime').val();
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                        minTime: $('#eventstarttime').val() ? timeFinal : false
                    }
                )
            }
        });

        $('#eventstarttime').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {
                this.setOptions({
                    maxTime: $('#eventendtime').val() ? $('#eventendtime').val() : false
                });
            }
        });

        function form_check() {
            alertify.defaults.glossary.title = 'Event Management!';
            if ($('#eventdate').val() == '') {
                alertify.alert("Please Provide Event Date");
                return false;
            }

            if ($('#eventstarttime').val() == '') {
                alertify.alert("Please provide Event Start Time");
                return false;
            }

            if ($('#eventendtime').val() == '') {
                alertify.alert("Please provide Event End Time");
                return false;
            }

            if ($('.users_type:checked').val() == 'applicant') {
                if ($('#applicant_sid').val() == null || $('#applicant_sid').val() <= 0 || $('#applicant_sid').val() == ' ') {
                    alertify.alert("Please select an Applicant");
                    return false;
                }
            } else if ($('.users_type:checked').val() == 'employee') {
                if ($('#employee_sid').val() == null || $('#employee_sid').val() <= 0 || $('#employee_sid').val() == ' ') {
                    alertify.alert("Please select an Employee");
                    return false;
                }
            }

            if ($('#interviewer').val() == null || $('#interviewer').val() <= 0 || $('#interviewer').val() == ' ') {
                alertify.alert("Please select an Interviewer");
                return false;
            }
            return true;
        }

        //send ajax request to save new data
        $('#update').click(function () {
            if (form_check()) {
                //getting form data by ID to save
                event_id = $('#event_id').val();
                applicant_sid = $('#applicant_sid').val();
                employee_sid = $('#employee_sid').val();
                title = $('#title').val();
                category = $('#category').val();
                date = $('#eventdate').val();
                eventstarttime = $('#eventstarttime').val();
                eventendtime = $('#eventendtime').val();
                interviewer = $('#interviewer').val();
                address = $('#address').val();
                users_type = $('.users_type:checked').val();
                user_id = 0;
                if (users_type == 'applicant') {
                    user_id = $('.applicant_sid').val();
                } else {
                    user_id = $('#employee_id').val();
                }


                myArray = {
                    'action': 'update_event',
                    'sid': event_id,
                    'applicant_sid': applicant_sid,
                    'employee_sid': employee_sid,
                    'title': title,
                    'category': category,
                    'date': date,
                    'eventstarttime': eventstarttime,
                    'eventendtime': eventendtime,
                    'interviewer': interviewer,
                    'address': address,
                    'users_type': users_type
                };

                myArray.commentCheck = '0';
                if ($('.interviewer_comment').is(":checked")) {
                    myArray.commentCheck = '1';
                    myArray.comment = $('#interviewerComment').val();
                }


                myArray.messageCheck = '0';
                if ($('#candidate_msg').is(":checked")) {
                    myArray.messageCheck = '1';
                    myArray.subject = $('#applicantSubject').val();
                    myArray.message = $('#applicantMessage').val();
                }

                myArray.goToMeetingCheck = '0';
                if ($('#goto_meeting').is(":checked")) {
                    myArray.goToMeetingCheck = '1';
                    myArray.meetingCallNumber = $('#meetingCallNumber').val();
                    myArray.meetingId = $('#meetingId').val();
                    myArray.meetingURL = $('#meetingURL').val();
                }

                var file_data = $('#messageFile').prop('files')[0];
                var form_data = new FormData();
                form_data.append('messageFile', file_data);
                for (myKey in myArray) {
                    form_data.append(myKey, myArray[myKey]);
                }

                $('#loader').show();
                $('.btn').addClass('disabled');
                $('.btn').prop('disabled', true);

                $.ajax({
                    url: "<?= base_url() ?>dashboard/calendar_task",
                    type: 'POST',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);

                        //console.log(msg);
                        alertify.success(msg);
                        location.reload();
                    },
                    always: function () {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);
                    }
                });
            } else {
                return false;
            }
        });

        //delete an event

        $('#delete').click(function () {
            event_id = $('#event_id').val();

            $('#loader').show();
            $('.btn').addClass('disabled');
            $('.btn').prop('disabled', true);

            $.ajax({
                url: "<?= base_url() ?>dashboard/deleteEvent?sid=" + event_id,
                type: 'GET',
                success: function (msg) {
                    $('#loader').hide();
                    $('.btn').removeClass('disabled');
                    $('.btn').prop('disabled', false);

                    $('#popup1').modal('hide');
                    alertify.alert(msg, function () {
                        location.reload();
                    });
                },
                always: function () {
                    $('#loader').hide();
                    $('.btn').removeClass('disabled');
                    $('.btn').prop('disabled', false);
                }
            });
        });

        //Cancel Event
        $('#cancel').click(function () {
            event_id = $('#event_id').val();
            var my_request;

            $('#loader').show();
            $('.btn').addClass('disabled');
            $('.btn').prop('disabled', true);

            my_request = $.ajax({
                url: '<?php echo base_url('dashboard/calendar_task');?>',
                type: 'POST',
                data: {'event_id': event_id, 'action': 'cancel_event'}
            });
            my_request.done(function (response) {
                $('#loader').hide();
                $('.btn').removeClass('disabled');
                $('.btn').prop('disabled', false);

                if (response == 'success') {
                    $('#popup1').modal('toggle');
                    alertify.success('Event Cancelled!');
                    window.location = window.location.href;
                } else {
                    $('#popup1').modal('toggle');
                    alertify.error('Could Not Cancel Event!');
                    window.location = window.location.href;
                }
            });

            my_request.always(function () {
                $('#loader').hide();
                $('.btn').removeClass('disabled');
                $('.btn').prop('disabled', false);
            });
        });


        //Save new event
        $('#save').click(function () {
            if (form_check()) {
                //getting form data by ID to save
                applicant_sid = $('#applicant_sid').val();
                employee_sid = $('#employee_sid').val();
                title = $('#title').val();
                category = $('#category').val();
                date = $('#eventdate').val();
                eventstarttime = $('#eventstarttime').val();
                eventendtime = $('#eventendtime').val();
                interviewer = $('#interviewer').val();
                address = $('#address').val();
                users_type = $('.users_type:checked').val();
                myArray = {
                    action: 'save_event',
                    applicant_sid: applicant_sid,
                    employee_sid: employee_sid,
                    title: title,
                    category: category,
                    date: date,
                    eventstarttime: eventstarttime,
                    eventendtime: eventendtime,
                    interviewer: interviewer,
                    address: address,
                    users_type: users_type
                };

                if ($('.interviewer_comment').is(":checked")) {
                    myArray.commentCheck = '1';
                    myArray.comment = $('#interviewerComment').val();
                }

                if ($('#candidate_msg').is(":checked")) {
                    myArray.messageCheck = '1';
                    myArray.subject = $('#applicantSubject').val();
                    myArray.message = $('#applicantMessage').val();
                }

                if ($('#goto_meeting').is(":checked")) {
                    myArray.goToMeetingCheck = '1';
                    myArray.meetingCallNumber = $('#meetingCallNumber').val();
                    myArray.meetingId = $('#meetingId').val();
                    myArray.meetingURL = $('#meetingURL').val();
                }

                var file_data = $('#messageFile').prop('files')[0];
                var form_data = new FormData();
                form_data.append('messageFile', file_data);

                for (myKey in myArray) {
                    form_data.append(myKey, myArray[myKey]);
                }

                //console.log(event_id);

                $('#loader').show();
                $('.btn').addClass('disabled');
                $('.btn').prop('disabled', true);

                $.ajax({
                    url: "<?= base_url() ?>dashboard/calendar_task",
                    type: 'POST',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);

                        $('#file_loader').css("display", "none");
                        $('#popup1').modal('hide');
                        $('body').css("overflow", "auto");
                        alertify
                            .alert(msg, function () {
                                location.reload();
                            });
                    },
                    always: function () {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);
                    }
                });
            } else {
                return false;
            }
        });

        //start of calendar

        $('#calendar').fullCalendar({
            header: {
                left: 'prevYear,prev,next,nextYear',
                center: 'title',
                right: 'today'
            },
            forceEventDuration: true,
            selectHelper: true,
            select: function (start, end) {
                var title = prompt('Event Title:');
                var eventData;
                if (title) {
                    eventData = {
                        title: title,
                        start: start,
                        end: end
                    };
                    $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                }

                $('#calendar').fullCalendar('unselect');
            },

            eventLimit: true, // allow "more" link when too many events
            eventClick: function (calEvent, jsEvent, view) {
                //Reset Event Popup
                $('#event_id').val('');
                $('#title').val('');
                $('#applicant_sid').select2('val', '');
                $('#employee_sid').select2('val', '');
                $('#title').val('');
                $('#category').val('');
                $('#eventdate').val('');
                $('#eventstarttime').val('');
                $('#eventendtime').val('');
                $('#interviewerComment').val('');
                $('#applicantSubject').val('');
                $('#applicantMessage').val('');
                $('#meetingCallNumber').val('');
                $('#meetingId').val('');
                $('#meetingURL').val('');

                //console.log(calEvent);

                if (calEvent.users_type == "applicant") {
                    $('#users_type_applicant').trigger('click');
                    $('#applicant_sid').select2('val', calEvent.applicant_sid);
                } else {
                    $('#users_type_employee').trigger('click');
                    $('#employee_sid').select2('val', calEvent.applicant_sid);
                }


                if (calEvent.messageCheck == "0") {
                    $('#candidate_msg').prop('checked', false);
                    $('.message-div').hide();
                }

                if (calEvent.commentCheck == "0") {
                    $('#interviewer_comment').prop('checked', false);
                    $('.comment-div').hide();
                }

                if (calEvent.goToMeetingCheck == "0") {
                    $('#goto_meeting').prop('checked', false);
                    $('.meeting-div').hide();
                }

                //Reset Event Popup End


                //open popup
                $('#select_new').show();
                if (calEvent.event_status == 'confirmed') {
                    $('#cancelled_message').hide();
                    $('#update').show();
                    $('#cancel').show();
                    $('#delete').show();
                    $('#save').hide();
                    $('#close_btn').show();
                } else {
                    $('#cancelled_message').show();
                    $('#update').hide();
                    $('#cancel').hide();
                    $('#delete').hide();
                    $('#save').hide();
                    $('#close_btn').hide();
                }


                //fill up the popup
                $('#event_id').val(calEvent.event_id);
                $('#title').val(calEvent.event_title);

                $('#category').val(calEvent.category);
                $('#eventdate').val(calEvent.eventdate);
                $('#eventstarttime').val(calEvent.eventstarttime_12hr);
                $('#eventendtime').val(calEvent.eventendtime_12hr);
                var myarr = calEvent.interviewer;
                if (myarr instanceof Array) {
                } else {
                    var myarr = calEvent.interviewer.split(",");
                    if (myarr[0] == "") {
                        myarr.shift();
                    }
                }
                $('#interviewer').select2('val', myarr);

                $('#address').val(calEvent.address);
                if (calEvent.commentCheck == "1") {
                    $('#interviewer_comment').prop('checked', true);
                    $('.comment-div').fadeIn();
                    $('#interviewerComment').val(calEvent.comment);
                }

                if (calEvent.messageCheck == "1") {
                    $('#candidate_msg').prop('checked', true);
                    $('.message-div').fadeIn();
                    $('#applicantSubject').val(calEvent.subject);
                    $('#applicantMessage').val(calEvent.message.toString().replace(new RegExp('<br>', 'g'), '\r\n'));
                }

                if (calEvent.goToMeetingCheck == "1") {
                    $('#goto_meeting').prop('checked', true);
                    $('.meeting-div').fadeIn();
                    $('#meetingCallNumber').val(calEvent.meetingCallNumber);
                    $('#meetingId').val(calEvent.meetingId);
                    $('#meetingURL').val(calEvent.meetingURL);
                }

                $('.users_type:checked').val(calEvent.users_type);
                eventt = calEvent;

                $('#popup1').modal('show');
                $('html,body').animate({
                    scrollTop: $("#popup1").offset().top
                });
            },

            events: [
                <?php foreach ($events as $event) { ?>
                {
                    title: '<?= $event['category_uc'] ?><?= addslashes($event['f_name_uc']) ?> <?= addslashes($event['l_name_uc']) ?>',
                    start: '<?= $event['backDate'] ?>T<?= $event['eventstarttime24Hr'] ?>',
                    end: '<?= $event['date'] ?>T<?= $event['eventendtime24Hr'] ?>}',
                    <?php if($event['event_status'] == 'confirmed') { ?>
                    <?php if ($event['category'] == 'interview') { ?>
                    color: '#466b1d'
                    <?php } else if ($event['category'] == 'call') { ?>
                    color: 'rgb(221, 118, 0)'
                    <?php } else if ($event['category'] == 'email') { ?>
                    color: 'rgb(185, 16, 255)'
                    <?php } else if ($event['category'] == 'meeting') { ?>
                    color: 'rgb(0, 145, 221)'
                    <?php } else if ($event['category'] == 'personal') { ?>
                    color: 'rgb(255, 16, 80)'
                    <?php } else if ($event['category'] == 'other') { ?>
                    color: 'rgb(126, 123, 123)'
                    <?php } ?>
                    <?php } else { ?>
                    color: '#ff0000'
                    <?php } ?>,
                    event_title: "<?= addslashes($event['title']) ?>",
                    applicant_sid: "<?= $event['applicant_job_sid'] ?>",
                    event_id: "<?= $event['sid'] ?>",
                    category: "<?= $event['category'] ?>",
                    date: "<?= $event['date'] ?>",
                    eventdate: "<?= $event['frontDate'] ?>",
                    eventstarttime: "<?= $event['eventstarttime'] ?>",
                    eventendtime: "<?= $event['eventendtime'] ?>",
                    eventstarttime: "<?= $event['eventstarttime'] ?>",
                    eventendtime: "<?= $event['eventendtime'] ?>",
                    eventstarttime_12hr: "<?= $event['eventstarttime'] ?>",
                    eventendtime_12hr: "<?= $event['eventendtime'] ?>",
                    interviewer: "<?= addslashes($event['interviewer']) ?>",
                    address: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['address'])) ?>",
                    commentCheck: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['commentCheck'])) ?>",
                    comment: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['comment'])) ?>",
                    messageCheck: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['messageCheck'])) ?>",
                    subject: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes(addslashes($event['subject']))) ?>",
                    message: "<?= str_replace(array("\n", "\r"), '<br>', addslashes($event['message'])); ?>",
                    goToMeetingCheck: "<?= $event['goToMeetingCheck'] ?>",
                    meetingId: "<?= $event['meetingId'] ?>",
                    meetingCallNumber: "<?= $event['meetingCallNumber'] ?>",
                    meetingURL: "<?= $event['meetingURL'] ?>",
                    editable: true,
                    users_type: "<?= $event["users_type"] ?>",
                    event_status: "<?= $event["event_status"] ?>"
                },
                <?php } ?>
            ],

            eventDrop: function (event, delta, revertFunc) {

                //.................eventstarttime..............................................
                datetime = new Date(event.start.format());
                eventDate = datetime.getDate() + '-' + (datetime.getMonth() + 1) + '-' + datetime.getFullYear();
                hours = event.start.format().substr(event.start.format().indexOf('T') + 1, 2);
                if (datetime.getUTCMinutes() == 0) {
                    mins = '00';
                } else {
                    mins = datetime.getUTCMinutes();
                }

                if (hours == '0') {
                    hours = '00';
                }

                eventstarttime = hours + ':' + mins;
                eventstarttime12hr = tConvert(eventstarttime);

                //.,................eventendtime.........................................................
                datetime = new Date(event.end.format());
                hours = event.end.format().substr(event.end.format().indexOf('T') + 1, 2);
                if (datetime.getUTCMinutes() == 0) {
                    mins = '00';
                } else {
                    mins = datetime.getUTCMinutes();
                }

                if (hours == '0') {
                    hours = '00';
                }

                eventendtime = hours + ':' + mins;
                eventendtime12hr = tConvert(eventendtime);

                //............................//
                olddate = eventDate;
                newdate = eventDate.split('-');
                eventDate = newdate[1] + '-' + newdate[0] + '-' + newdate[2];

                //console.log(event);

                func_show_loader();

                $.ajax({
                    url: "<?= base_url() ?>dashboard/calendar_task",
                    type: 'POST',
                    data: {
                        action: 'drop_update_event',
                        sid: event.event_id,
                        applicant_sid: event.applicant_sid,
                        employee_sid: event.applicant_sid,
                        title: event.event_title,
                        category: event.category,
                        date: eventDate,
                        eventstarttime: event.eventstarttime_12hr,
                        eventendtime: event.eventendtime_12hr,
                        interviewer: event.interviewer,
                        users_type: event.users_type
                    },

                    success: function (msg) {
                        func_hide_loader();
                        alertify.success(msg);
                        event.eventdate = eventDate;
                        event.eventstarttime = eventstarttime;
                        event.eventendtime = eventendtime;
                        event.eventstarttime_12hr = eventstarttime12hr;
                        event.eventendtime_12hr = eventendtime12hr;
                    }
                });
            },

            /*
            eventResize: function (event, delta, revertFunc) {
                //.................eventstarttime..............................................
                datetime = new Date(event.start.format());
                eventDate = datetime.getDate() + '-' + (datetime.getMonth() + 1) + '-' + datetime.getFullYear();
                hours = event.start.format().substr(event.start.format().indexOf('T') + 1, 2);

                if (datetime.getUTCMinutes() == 0)
                    mins = '00';
                else
                    mins = datetime.getUTCMinutes();

                if (hours == '0')
                    hours = '00';

                eventstarttime = hours + ':' + mins;

                eventstarttime12hr = tConvert(eventstarttime);

                //.,................eventendtime.........................................................

                datetime = new Date(event.end.format());
                hours = event.end.format().substr(event.end.format().indexOf('T') + 1, 2);
                if (datetime.getUTCMinutes() == 0)
                    mins = '00';
                else
                    mins = datetime.getUTCMinutes();
                if (hours == '0')
                    hours = '00';
                eventendtime = hours + ':' + mins;
                eventendtime12hr = tConvert(eventendtime);
                //..............................................
                olddate = eventDate;
                newdate = eventDate.split('-');
                eventDate = newdate[1] + '-' + newdate[0] + '-' + newdate[2];
                $.ajax({
                    url: '<?= base_url() ?>dashboard/calendar_task',
                    type: 'POST',
                    data: {
                        action: 'drop_update_event',
                        sid: event.event_id,
                        title: event.event_title,
                        applicant_job_sid: event.applicant_sid,
                        category: event.category,
                        date: eventDate,
                        eventstarttime: eventstarttime12hr,
                        eventendtime: eventendtime12hr,
                    },

                    success: function (msg) {
                        alertify.success(msg);
                        event.eventstarttime = eventstarttime;
                        event.eventendtime = eventendtime;
                        event.eventstarttime_12hr = eventstarttime12hr;
                        event.eventendtime_12hr = eventendtime12hr;
                    }
                });
            },
            */


            dayClick: function (date, allDay, jsEvent, view) {
                //fill up the popup
                //$('#contact_id').select2({}).select2('val', ' ');
                $('#select_new').show();
                $('#update').hide();
                $('#delete').hide();
                $('#save').show();
                $('#title').val('');
                $('#cancel').hide();
                $('#cancelled_message').hide();
                $('#close_btn').show();
                newdate = moment(date).format('MM-DD-YYYY');
                $('#eventdate').val(newdate);
                $('#eventstarttime').val('');
                $('#eventendtime').val('');
                $('#address').val('');
                $('#interviewer_comment').prop('checked', false);
                $('.comment-div').fadeOut();
                $('#interviewerComment').val('');
                $('#candidate_msg').prop('checked', false);
                $('.message-div').fadeOut();
                $('#applicantSubject').val('');
                $('#applicantMessage').val('');
                $('#goto_meeting').prop('checked', false);
                $('.meeting-div').fadeOut();
                $('#meetingCallNumber').val('');
                $('#meetingId').val('');
                $('#meetingURL').val('');
                //$('.contact_id').select2({}).select2('val', '<?= $employer_id ?>');
                $('#popup1').modal('show');
                $('html,body').animate({
                    scrollTop: $("#popup1").offset().top
                });
            },

            allDaySlot: false,
            timeFormat: 'h(:mm)'
        });


        func_hide_loader();
        /*
        $('#file_loader').css("display", "none");
        $('.my_spinner').css("visibility", "hidden");
        $('.loader_message').css("display", "none");
        */


    });

    function func_hide_loader() {
        $('#file_loader').css("display", "none");
        $('.my_spinner').css("visibility", "hidden");
        $('.loader-text').css("display", "none");
        $('.my_loader').css("display", "none");
    }

    function func_show_loader() {
        $('#file_loader').css("display", "block");
        $('.my_spinner').css("visibility", "visible");
        $('.loader-text').css("display", "block");
        $('.my_loader').css("display", "block");
    }
    /*
    $('#contact_id').select2({
        placeholder: "Enter applicant name",
        allowClear: true
    });
    */

    $('.select2-dropdown').css('z-index', '99999999999999999999999');
</script>





