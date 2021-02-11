<?php foreach ($companies as $company) { ?>
    <?php $inactive_employers = $company['inactive_employers']; ?>
    <?php if (!empty($inactive_employers)) { ?>
        <div class="hr-box">
            <div class="hr-box-header bg-header-green">
                <h1 class="hr-registered pull-left"><span class="text-success"><?php echo ucwords($company['CompanyName']); ?></span></h1>
            </div>
            <div class="table-responsive hr-innerpadding daily-activity">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-left col-xs-2">Job Title</th>
                            <th class="text-left col-xs-2">Access Level</th>
                            <th class="text-left col-xs-3">Name</th>
                            <th class="text-left col-xs-2">Email</th>
                            <th class="text-left col-xs-2">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inactive_employers as $inactive_employer) { ?>
                            <tr>
                                <td class="" style="vertical-align: middle;">
                                    <strong class="text-success">
                                        <?php echo ($inactive_employer['job_title'] != '' ? ucwords($inactive_employer['job_title']) : 'Not Available'); ?>
                                    </strong>
                                </td>
                                <td class="" style="vertical-align: middle;">
                                    <?php echo ucwords($inactive_employer['access_level']); ?>
                                </td>
                                <td class="" style="vertical-align: middle;">
                                    <?php echo ucwords($inactive_employer['first_name'] . ' ' . $inactive_employer['last_name']); ?>
                                </td>
                                <td class="" style="vertical-align: middle;">
                                    <?php echo $inactive_employer['email']; ?>
                                </td>
                                <td class="" style="vertical-align: middle;">
                                    <?php echo ($inactive_employer['PhoneNumber'] == '' ? 'Not Available' : $inactive_employer['PhoneNumber']); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } else { ?>
        <div class="hr-box">
            <div class="hr-box-header bg-header-green">
                <h1 class="hr-registered pull-left"><span class="text-success"><?php echo ucwords($company['CompanyName']); ?></span></h1>
            </div>
            <div class="table-responsive hr-innerpadding daily-activity">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-left col-xs-2">Job Title</th>
                            <th class="text-left col-xs-2">Access Level</th>
                            <th class="text-left col-xs-3">Name</th>
                            <th class="text-left col-xs-2">Email</th>
                            <th class="text-left col-xs-2">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">No inactive employees found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
<?php } ?>