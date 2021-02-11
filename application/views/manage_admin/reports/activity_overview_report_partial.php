

<?php foreach($companies as $company) { ?>

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
                            <th class="text-center"></th>
                            <th class="text-left col-xs-2">Job Title</th>
                            <th class="text-left col-xs-2">Access Level</th>
                            <th class="text-left col-xs-3">Name</th>
                            <th class="text-left col-xs-2">Email</th>
                            <th class="text-left col-xs-2">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($active_employers)) { ?>
                            <tr class="bg-success">
                                <th rowspan="<?php echo count($active_employers) + 1; ?>" style="vertical-align: middle;"><span class="text-success">Active</span></th>
                            </tr>
                            <?php foreach($active_employers as $active_employer) { ?>

                                <tr class="bg-success">
                                    <!--
                                        <td class="" style="vertical-align: middle;">
                                            <div class="thumbnail">
                                                <?php /*if($inactive_employer['profile_picture'] == '') { */?>
                                                    <img src="<?php echo STORE_FULL_URL_SSL; ?>/assets/images/img-applicant.jpg" class="img-responsive" />
                                                <?php /*} else { */?>
                                                    <img src="<?php echo AWS_S3_BUCKET_URL; ?><?php /*echo $inactive_employer['profile_picture']; */?>" class="img-responsive" />
                                                <?php /*} */?>
                                            </div>
                                        </td>
                                        -->
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo ($active_employer['job_title'] != '' ? ucwords($active_employer['job_title']) : 'Not Available'); ?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php if($active_employer['is_executive_admin'] == 1) { ?>
                                            Executive Admin
                                        <?php } else { ?>
                                            <?php echo ucwords($active_employer['access_level']);?>
                                        <?php } ?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo ucwords($active_employer['first_name'] . ' ' . $active_employer['last_name']); ?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo $active_employer['email'];?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo ($active_employer['PhoneNumber'] == '' ? 'Not Available' : $active_employer['PhoneNumber']);?>
                                    </td>
                                </tr>

                            <?php } ?>
                        <?php } else { ?>
                            <tr class="bg-success">
                                <th rowspan="<?php echo count($active_employers) + 1; ?>" style="vertical-align: middle;"><span class="text-success">Active</span></th>
                                <td colspan="5" class="text-center">
                                    <div class="no-data">No Active Employers</div>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if(!empty($inactive_employers)) { ?>
                            <tr class="bg-danger">
                                <th rowspan="<?php echo count($inactive_employers) + 1; ?>" style="vertical-align: middle;"><span class="text-danger">Inactive</span></th>
                            </tr>
                            <?php foreach($inactive_employers as $inactive_employer) { ?>

                                <tr class="bg-danger">
                                    <!--
                                    <td class="" style="vertical-align: middle;">
                                        <div class="thumbnail">
                                            <?php /*if($inactive_employer['profile_picture'] == '') { */?>
                                                <img src="<?php echo STORE_FULL_URL_SSL; ?>/assets/images/img-applicant.jpg" class="img-responsive" />
                                            <?php /*} else { */?>
                                                <img src="<?php echo AWS_S3_BUCKET_URL; ?><?php /*echo $inactive_employer['profile_picture']; */?>" class="img-responsive" />
                                            <?php /*} */?>
                                        </div>
                                    </td>
                                    -->
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo ($inactive_employer['job_title'] != '' ? ucwords($inactive_employer['job_title']) : 'Not Available'); ?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php if($inactive_employer['is_executive_admin'] == 1) { ?>
                                            Executive Admin
                                        <?php } else { ?>
                                            <?php echo ucwords($inactive_employer['access_level']);?>
                                        <?php } ?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo ucwords($inactive_employer['first_name'] . ' ' . $inactive_employer['last_name']); ?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo $inactive_employer['email'];?>
                                    </td>
                                    <td class="" style="vertical-align: middle;">
                                        <?php echo ($inactive_employer['PhoneNumber'] == '' ? 'Not Available' : $inactive_employer['PhoneNumber']);?>
                                    </td>
                                </tr>

                            <?php } ?>
                        <?php } else { ?>
                            <tr class="bg-danger">
                                <th rowspan="<?php echo count($active_employers) + 1; ?>" style="vertical-align: middle;"><span class="text-danger">Inactive</span></th>
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

