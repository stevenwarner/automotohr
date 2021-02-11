<div class="row">
    <div class="col-xs-12">
        <br />
        <?php  if(!empty($events)) { ?>
            <?php foreach ($events as $key => $event) { 
                $show_remove_button = true;
                if(isset($event['is_expired']) && $event['is_expired'] == 1)
                    $show_remove_button = false;

                ?>
                <div class="hr-box" id="remove_li<?= $event["sid"] ?>">
                    <div class="hr-box-header">
                        <span class="pull-left">
                            <strong>Event : <?php echo ( $event['title'] != '' ? $event['title'] : 'No Title Specified'); ?> (<?= !empty($event['event_timezone']) ? $event['event_timezone'] : $employer_timezone ?>)</strong>
                        </span>
                        <span class="pull-right">
                            <i id="spinner_<?php echo $event['sid']; ?>" class="fa fa-cog fa-spin loader" style="font-size: 18px; color: rgb(77, 160, 0); margin-right: 10px;"></i>
                            <button class="btn btn-info btn-xs js-edit-event" data-toggle="tooltip" title="Edit Event" 
                            data-eid="<?=$event["sid"];?>" data-uid="<?=isset($applicant_sid) ? $applicant_sid : $employer_id;?>" data-jid="<?=isset($job_list_sid) ? $job_list_sid : 0;?>">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <?php if($show_remove_button){ ?>
                            <a href="javascript:;" class="btn btn-danger btn-xs" onclick="remove_event(<?= $event["sid"] ?>)">
                                <i class="fa fa-remove"></i>
                                <span class="btn-tooltip">Delete Event</span>
                            </a>
                            <?php } ?>
                        </span>
                    </div>
                    <div class="table-responsive hr-innerpadding">
                        <table class="table table-striped table-bordered">
                            <tbody>
                            <tr>
                                <th>Event Category</th>
                                <th class="text-center">Event Date</th>
                                <th class="text-center">Start Time</th>
                                <th class="text-center">End Time</th>
                            </tr>
                            <tr>
                                <td>
                                    <?php if($event['category'] == 'interview') { ?>
                                        In-Person Interview
                                    <?php } else if($event['category'] == 'interview-phone') { ?>
                                        Phone Interview
                                    <?php } else if($event['category'] == 'interview-voip') { ?>
                                        Voip Interview
                                    <?php } else if($event['category'] == 'call') { ?>
                                        Call
                                    <?php } else if($event['category'] == 'email') { ?>
                                        Email
                                    <?php } else if($event['category'] == 'meeting') { ?>
                                        Meeting
                                    <?php } else if($event['category'] == 'personal') { ?>
                                        Personal
                                    <?php } else if($event['category'] == 'other') { ?>
                                        Other
                                    <?php } else if($event['category'] == 'training-session') { ?>
                                        Training Session
                                    <?php } ?>
                                </td>
                                <td class="text-center"><?= date_format(date_create($event['date']),"m-d-Y") ?></td>
                                <td class="text-center"><?= $event['event_start_time']; ?></td>
                                <td class="text-center"><?= $event['event_end_time']; ?></td>
                            </tr>
                            <?php if($event['subject'] != '' && $event['message'] != '') { ?>
                                <tr>
                                    <th class="col-lg-3">Message to Candidate</th>
                                    <td colspan="3">
                                        <h5 style="margin-top: 0;"><strong><?php echo $event['subject']; ?></strong></h5>
                                        <p><?php echo $event['message']; ?></p>
                                        <span class="pull-right">
                                            <?php if($event['messageFile'] != '') { ?>
                                                <a href="<?php echo AWS_S3_BUCKET_URL . $event['messageFile']; ?>" class="btn btn-success btn-sm">Message Attachment</a>
                                            <?php } else { ?>
                                                <a href="javascript:void(0);" class="btn btn-success btn-sm disabled">Message Attachment</a>
                                            <?php } ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th class="col-lg-3">Participants</th>
                                <td colspan="3">
                                    <?php   $event['interviewer'] = explode(',', $event['interviewer']);
                                    foreach ($employees as $employee) {
                                        foreach ($event['interviewer'] as $interviewer) {
                                            if ($interviewer == $employee['sid']) { ?>
                                                <?php   if($employee['is_executive_admin'] == 1) {
                                                    $employee_type = 'Executive Admin';
                                                } else {
                                                    $employee_type = $employee['access_level'];
                                                } 
                                                $timezone = !empty($employee['timezone']) ? $employee['timezone'] : $company_timezone;
                                                ?>
                                                <div class="badge"><?php echo $employee['first_name'].'&nbsp;'.$employee['last_name'].' ('.$timezone.')'.' ('.$employee_type.')'; ?></div>
                                            <?php }
                                        }
                                    } ?>
                                    <?php if(isset($event['external_participants'])) { ?>
                                        <?php foreach($event['external_participants'] as $external_participant) { ?>
                                            <div class="badge"><?php echo $external_participant['name']; ?></div>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            </tr>

                            <?php if($event['goToMeetingCheck'] == 1) { ?>
                                <tr>
                                    <th>Meeting Call Details</th>
                                    <td colspan="3">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <th class="col-lg-4 text-center">Number</th>
                                                <th class="col-lg-4 text-center">Meeting ID</th>
                                                <th class="col-lg-4 text-center">Meeting Url</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center"><?php echo $event['meetingCallNumber']; ?></td>
                                                <td class="text-center"><?php echo $event['meetingId']; ?></td>
                                                <td class="text-center"><?php echo $event['meetingURL']; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if($event['comment'] != '') { ?>
                                <tr>
                                    <td class="col-lg-3"><strong>Comment</strong></td>
                                    <td colspan="3">
                                        <?php echo $event['comment']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if($event['address'] != '') { ?>
                                <tr>
                                    <td class="col-lg-3"><strong>Address</strong></td>
                                    <td colspan="3">
                                        <?php echo $event['address']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="text-center">
                <div class="no-data">No event scheduled!</div>
            </div>
        <?php } ?>
    </div>
</div>