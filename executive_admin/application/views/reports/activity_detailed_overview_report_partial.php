
<?php foreach ($companies as $company) { ?>
    <?php $inactive_employers = $company['inactive_employers']; ?>
    <?php $active_employers = $company['active_employers']; ?>
    <div class="hr-box">
        <div class="hr-box-header bg-header-green">
            <h1 class="hr-registered pull-left"><span class="text-success"><?php echo ucwords($company['CompanyName']); ?></span></h1>
        </div>
        <div class="table-responsive hr-innerpadding daily-activity">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-xs-1 text-center"></th>
                        <th class="col-xs-2 text-left">Job Title<br />Access Level</th>
                        <th class="text-left">Contact Information</th>
                        <th class="text-left" colspan="2">Activity Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($active_employers)) { ?>
                        <tr class="">
                            <td rowspan="<?php echo count($active_employers) + 1; ?>" style="vertical-align: middle;"><span class="">Active</span></td>
                        </tr>
                        <?php foreach ($active_employers as $active_employer) { ?>
                            <tr class="">
                                <td class="">
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-black-tie"></i> &nbsp;
                                        <?php echo ($active_employer['job_title'] != '' ? ucwords($active_employer['job_title']) : 'Not Available'); ?>
                                    </p>
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-lock"></i> &nbsp;
                                        <?php if ($active_employer['is_executive_admin'] == 1) { ?>
                                            Executive Admin
                                        <?php } else { ?>
                                            <?php echo ucwords($active_employer['access_level']); ?>
                                        <?php } ?>
                                    </p>
                                </td>
                                <td class="">
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-user"></i> &nbsp;
                                        <?php echo ucwords($active_employer['first_name'] . ' ' . $active_employer['last_name']); ?>
                                    </p>
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-envelope"></i> &nbsp;
                                        <?php echo $active_employer['email']; ?>
                                    </p>
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-phone"></i> &nbsp;
                                        <?php echo ($active_employer['PhoneNumber'] == '' ? 'Not Available' : $active_employer['PhoneNumber']); ?>
                                    </p>
                                </td>
                                <?php if (!empty($active_employer['details'])) { ?>
                                    <?php $details = $active_employer['details']; ?>
                                    <td>
                                        <p style="font-size: 12px;">
                                            <i class="fa fa-clock-o"></i> &nbsp;
                                            <?php echo $details['total_time_spent']; ?> Mins
                                        </p>
                                    </td>
                                    <td>
                                        <?php $logs = $details['activity_logs']; ?>
                                        <?php foreach ($logs as $log_key => $log) { ?>
                                            <p style="font-size: 12px;">
                                                <?php echo str_replace('_', '.', $log_key); ?>,&nbsp;
                                                <?php echo $log['time_spent']; ?> Mins,&nbsp;
                                                <?php echo $log['act_details']['user_agent']; ?>
                                            </p>
                                        <?php } ?>
                                    </td>
                                <?php } else { ?>
                                    <td class="text-center" colspan="2">
                                        <span class="no-data">No Activity</span>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr class="">
                            <td rowspan="<?php echo count($active_employers) + 1; ?>" style="vertical-align: middle;"><span class="">Active</span></td>
                            <td colspan="5" class="text-center">
                                <div class="no-data">No Active Employers</div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($inactive_employers)) { ?>
                        <tr class="">
                            <td rowspan="<?php echo count($inactive_employers) + 1; ?>" style="vertical-align: middle;"><span class="">Inactive</span></td>
                        </tr>
                        <?php foreach ($inactive_employers as $inactive_employer) { ?>
                            <tr class="">
                                <td class="">
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-black-tie"></i> &nbsp;
                                        <?php echo ($inactive_employer['job_title'] != '' ? ucwords($inactive_employer['job_title']) : 'Not Available'); ?>
                                    </p>
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-lock"></i> &nbsp;
                                        <?php if ($inactive_employer['is_executive_admin'] == 1) { ?>
                                            Executive Admin
                                        <?php } else { ?>
                                            <?php echo ucwords($inactive_employer['access_level']); ?>
                                        <?php } ?>
                                    </p>
                                </td>
                                <td class="">
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-user"></i> &nbsp;
                                        <?php echo ucwords($inactive_employer['first_name'] . ' ' . $inactive_employer['last_name']); ?>
                                    </p>
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-envelope"></i> &nbsp;
                                        <?php echo $inactive_employer['email']; ?>
                                    </p>
                                    <p style="font-size: 12px;">
                                        <i class="fa fa-phone"></i> &nbsp;
                                        <?php echo ($inactive_employer['PhoneNumber'] == '' ? 'Not Available' : $inactive_employer['PhoneNumber']); ?>
                                    </p>
                                </td>
                                <td class="text-center" colspan="2">
                                    <span class="no-data">No Activity</span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr class="">
                            <th rowspan="<?php echo count($active_employers) + 1; ?>" style="vertical-align: middle;"><span class="">Inactive</span></th>
                            <td colspan="5" class="text-center">
                                <div class="no-data">No Inactive Employers</div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
