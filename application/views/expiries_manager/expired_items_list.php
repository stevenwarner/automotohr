<div class="page-header-area expired-title">
    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Expired</span>
</div>
<div class="table-responsive table-outer">

        <div class="table-wrp mylistings-wrp">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-xs-1">Sr. #</th>
                        <th class="col-xs-2">License Type</th>
                        <th class="col-xs-4">User Full Name</th>
                        <th class="col-xs-2">Expiration Date</th>
                        <th class="col-xs-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php foreach($expired as $item) { 
                        $license_date = !isset($item['license_details']) || !isset($item['license_details']['license_expiration_date']) || $item['license_details']['license_expiration_date'] == '' ? '01-01-1970' : $item['license_details']['license_expiration_date'];
                        ?>
                        <tr style="text-align: left;">
                            <td><?php echo $count; ?></td>
                            <td>
                                <?php if($item['license_type'] == 'drivers'){?>
                                    Drivers License
                                <?php } else if($item['license_type'] == 'occupational') {?>
                                    Occupational License
                                <?php } ?>
                            </td>
                            <td><?php echo remakeEmployeeName($item['user_details']); ?></td>
                            <td><?=reset_datetime(array('datetime' => $license_date, '_this' => $this, 'from_format' => 'm-d-Y', 'format' => 'M d Y, D')); ?></td>
                            <td>
                                <button 
                                    title="This action will send an email reminder to update the <?=$item['license_type'] == 'drivers' ? 'driver\'s' : 'occupational' ;?> license."
                                    placement="top"
                                    class="btn btn-success JsSendReminderEmailLI form-control"
                                    data-id="<?=$item['users_sid'];?>"
                                    data-type="<?=$item['users_type'];?>"
                                    data-slug="<?=$item['license_type'] == 'drivers' ? 'drivers-license' : 'occupational-license' ;?>"
                                >Send An Email Reminder</button>
                                <?php if($item['license_type'] == 'drivers'){?>
                                    <a class="btn btn-success form-control" href="<?php echo base_url('drivers_license_info') . '/' . 'employee' . '/' . $item['user_details']['sid']; ?>">View</a>
                                <?php } else if($item['license_type'] == 'occupational') {?>
                                    <a class="btn btn-success form-control" href="<?php echo base_url('occupational_license_info') . '/' . 'employee' . '/' . $item['user_details']['sid']; ?>">View</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $count++; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>


</div>