<div class="page-header-area expiring-title">
    <span id="sevenDays" class="page-heading down-arrow">Expiring In 7 Days</span>
</div>
<div class="table-responsive table-outer">

        <div class="table-wrp mylistings-wrp">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-xs-1">Sr. #</th>
                        <th class="col-xs-3">License Type</th>
                        <th class="col-xs-5">User Full Name</th>
                        <th class="col-xs-2">Expiration Date</th>
                        <th class="col-xs-1">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php foreach($expiringInSevenDays as $item) { 
                        $license_date = !isset($item['license_details']) || !isset($item['license_details']['license_expiration_date']) || $item['license_details']['license_expiration_date'] == '' ? '01-01-1970' : $item['license_details']['license_expiration_date'];?>?>
                        <tr style="text-align: left;">
                            <td><?php echo $count; ?></td>
                            <td>
                                <?php if($item['license_type'] == 'drivers'){?>
                                    Drivers License
                                <?php } else if($item['license_type'] == 'occupational') {?>
                                    Occupational License
                                <?php } ?>
                            </td>
                            <td><?php echo ucwords($item['user_details']['first_name']) . ' ' . ucwords($item['user_details']['last_name']); ?></td>
                            <td><?=reset_datetime(array('datetime' => $license_date, '_this' => $this, 'from_format' => 'm-d-Y', 'format' => 'M d Y, D')); ?></td>
                            <td>
                                <?php if($item['license_type'] == 'drivers'){?>
                                    <a class="action-btn manage-btn bg-btn" href="<?php echo base_url('drivers_license_info') . '/' . 'employee' . '/' . $item['user_details']['sid']; ?>">View</a>
                                <?php } else if($item['license_type'] == 'occupational') {?>
                                    <a class="action-btn manage-btn bg-btn" href="<?php echo base_url('occupational_license_info') . '/' . 'employee' . '/' . $item['user_details']['sid']; ?>">View</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $count++; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>


</div>