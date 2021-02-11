<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <?php if(isset($applicant)) { ?>
                        <a href="<?php echo base_url('onboarding/getting_started/' . $unique_sid); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Dashboard</a>
                    <?php } else { ?>
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Dashboard</a>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php if(isset($applicant)) { ?>
                            <div class="page-header">
                                <h1 class="section-ttile">My Events</h1>
                            </div>
                            <div class="row">,
                                <div class="col-xs-12">
                                    <?php if(!empty($applicant_events)) { ?>
                                        <?php foreach($applicant_events as $event) { ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Event : <?php echo ( $event['title'] != '' ? $event['title'] : 'No Title Specified'); ?>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                            <!--<tr>
                                                            <td class="col-lg-3"><strong>Title</strong></td>
                                                            <td colspan="3"><?php /* echo ( $event['title'] != '' ? $event['title'] : 'No Title Specified'); */ ?></td>
                                                        </tr>-->
                                                            <tr>
                                                                <th>Event Category</th>
                                                                <th class="text-center">Event Date</th>
                                                                <th class="text-center">Start Time</th>
                                                                <th class="text-center">End Time</th>
                                                            </tr>
                                                            <tr>
                                                                <td><?php echo ucwords($event['category']); ?></td>
                                                                <td class="text-center"><?php echo date("M jS, Y", strtotime($event['date'])) ?></td>
                                                                <td class="text-center"><?php echo $event['eventstarttime']; ?></td>
                                                                <td class="text-center"><?php echo $event['eventendtime']; ?></td>
                                                            </tr>
                                                            </tr>
                                                            <?php if ($event['subject'] != '' && $event['message'] != '') { ?>
                                                                <tr>
                                                                    <th class="col-lg-3">Message to Candidate</th>
                                                                    <td colspan="3">
                                                                        <h5 style="margin-top: 0;"><strong><?php echo $event['subject']; ?></strong></h5>
                                                                        <p><?php echo $event['message']; ?></p>
                                                                        <span class="pull-right">
                                                                        <?php if ($event['messageFile'] != '') { ?>
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
                                                                    <?php if(!empty($event['participants'])) { ?>
                                                                        <?php foreach($event['participants'] as $participant) { ?>
                                                                            <?php $employee_type = $participant['is_executive_admin'] == 1 ? 'Executive Admin' :  ucwords(str_replace('_', ' ', $participant['access_level'])); ?>
                                                                            <div style="font-weight: normal;" class="badge"><?php echo $participant['first_name'].'&nbsp;'.$participant['last_name'].' ( ' . $employee_type . ' )'; ?></div>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($event['goToMeetingCheck'] == 1) { ?>
                                                                <tr>
                                                                    <th>Meeting Call Details</th>
                                                                    <td colspan="3">
                                                                        <table class="table">
                                                                            <tr>
                                                                                <th class="col-lg-4 text-center">Number</th>
                                                                                <th class="col-lg-4 text-center">Meeting ID</th>
                                                                                <th class="col-lg-4 text-center">Meeting Url</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo $event['meetingCallNumber'] ?></td>
                                                                                <td class="text-center"><?php echo $event['meetingId'] ?></td>
                                                                                <td class="text-center"><?php echo $event['meetingURL'] ?></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            <?php if ($event['comment'] != '') { ?>
                                                                <tr>
                                                                    <td class="col-lg-3"><strong>Comment</strong></td>
                                                                    <td colspan="3">
                                                                        <?php echo $event['comment']; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <span class="no-data">No Events</span>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } else if ( isset($employee)) { ?>
                            <div class="page-header">
                                <h1 class="section-ttile">Calendar</h1>
                            </div>
                            <div id='calendar'></div>

                            <div id="my_loader" class="text-center my_loader">
                                <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                                <div class="loader-icon-box">
                                    <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                                    <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
                                    </div>
                                </div>
                            </div>


                        <?php } ?>
                    </div>
                    <?php if(isset($employee)) { ?>
                        <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                            <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
                        <!-- </div> -->
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('calendar/popup_modal_partial'); ?>

<?php $this->load->view('calendar/scripts_partial'); ?>