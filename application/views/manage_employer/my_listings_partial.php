<div class="table-responsive table-outer">
    <?php if ($listings) { ?>
        <form action="selected_jobs_xml_export.php" method="POST" id="xml_form">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input name="" type="checkbox" value="" id="selectall"></th>
                        <th class="col-xs-6">Job Title</th>
                        <th class="col-xs-2 text-center">Posted On</th>
                        <!--<th class="col-xs-2 text-center">Active</th>-->
                        <th class="col-xs-2 text-center">Job Views</th>
                        <?php if ($job_approval_module_status == 1) { ?>
                            <th class="col-xs-1">Status</th>
                        <?php } ?>
                        <?php $function_names = array('add_listing', 'add_listing_advertise', 'add_listing_share', 'clone_listing', 'activate_deactivate_job', 'delete_archive_job'); ?>
                        <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                            <th class="col-xs-3 text-center last-col">Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listings as $listing) { ?>
                        <?php
                            // Add CSC to job title
                            // $country = "United States";
                            if (isset($listing['Location_City']) && $listing['Location_City'] != NULL) {
                                $listing['Title'] .= ' - '.ucfirst($listing['Location_City']);
                            }
                            if (isset($listing['Location_State']) && $listing['Location_State'] != NULL) {
                                $listing['Title'] .= ', '.db_get_state_name($listing['Location_State'])['state_name'];
                            }
                            // if (isset($listing['Location_Country']) && $listing['Location_Country'] != NULL) {
                            //     $country = db_get_country_name($listing['Location_Country'])['country_name'];
                            // }
                            // $listing['Title'] .= ', '.$country;
                        ?>
                        <tr>
                            <td>
                                <input name="ej_active[]" type="checkbox" value="<?= $listing['sid'] ?>" id="<?= $listing['sid'] ?>"  class="checkbox1">
                            </td>
                            <td><?php echo $listing['Title']; ?></td>
                            <td class="text-center"><?php
                                if($job_approval_module_status == 1 && $listing["approval_status"] == 'pending') echo 'N/A';
                                else echo reset_datetime( array( 'datetime' => $listing[ $listing['activation_date'] != null && $listing['activation_date'] != '' ? 'activation_date' : 'approval_status_change_datetime'  ], '_this' => $this) );
                            ?></td>
                            <td class="text-center"><?php echo $listing['views']; ?></td>
                            <?php if ($job_approval_module_status == 1) { ?>
                                <td class="text-center">
                                    <?php if ($listing["approval_status"] == 'pending') { ?>
                                        Pending Approval
                                    <?php } elseif ($listing["approval_status"] == 'approved') { ?>
                                        Approved
                                    <?php } elseif ($listing["approval_status"] == 'rejected') { ?>
                                        Rejected
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <?php $function_names = array('add_listing', 'add_listing_advertise', 'add_listing_share', 'clone_listing', 'activate_deactivate_job', 'delete_archive_job'); ?>
                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                <td>
                                    <div class="job-action-btn-wrp arrow-down">
                                        <a class="dropdown-btn" href="javascript:;">Options</a>
                                        <?php ?>
                                        <ul class="action-options">
                                            <?php if ($listing["active"] == 0 || $listing["active"] == 1) { ?>

                                                <?php if (check_access_permissions_for_view($security_details, 'add_listing')) { ?>
                                                    <li><a href="<?= base_url('edit_listing') ?>/<?= $listing['sid'] ?>"><i class="fa fa-pencil-square-o"></i>Edit this Job</a></li>
                                                <?php } ?>

                                                    <li><a href="<?= base_url('preview_listing') ?>/<?= $listing['sid'] ?>"><i class="fa fa-eye"></i>Preview Job</a></li>

                                                <?php if (check_access_permissions_for_view($security_details, 'add_listing_advertise')) {
                                                        if($enable_advertise) { ?>
                                                            <li><a href="<?= base_url('add_listing_advertise') ?>/<?= $listing['sid'] ?>"><i class="fa fa-random"></i> Advertise this Job</a></li>
                                                    <?php }
                                                    } ?>

                                                <?php if (check_access_permissions_for_view($security_details, 'add_listing_share')) { ?>
                                                    <li><a href="<?= base_url('add_listing_share') ?>/<?= $listing['sid'] ?>"><i class="fa fa-share-alt-square"></i> Share this Job</a></li>
                                                <?php } ?>

                                                <?php if (check_access_permissions_for_view($security_details, 'clone_listing')) { ?>
                                                    <li><a href="<?= $session['company_detail']['per_job_listing_charge'] ? base_url('sponsor_listing') : base_url('clone_listing') ?>/<?= $listing['sid'] ?>"><i class="fa fa-clone"></i> Clone this Job</a></li>
                                                <?php } ?>

                                                <?php if (check_access_permissions_for_view($security_details, 'activate_deactivate_job')) { ?>
                                                    <li>
                                                        <?php if ($listing['active']) { ?>
                                                            <a href="javascript:;" id="<?php echo $listing['sid']; ?>" onclick="callFunction('deactive', this.id, 'dropdown');"><i class="fa fa-ban"></i> Deactivate this Job</a>
                                                        <?php } else { ?>
                                                            <?php /* if ($listing["active"] == 0) { */ ?><!--
                                                                <a href="javascript:;" id="<?/*= $listing['sid'] */?>" onclick="callFunction('active', this.id);"><i class="fa fa-check-square-o"></i> Activate this Job</a>
                                                            --><?php /* } */ ?>
                                                        <?php } ?>
                                                    </li>
                                                <?php } ?>

<!--                                                --><?php //if (check_access_permissions_for_view($security_details, 'delete_archive_job')) { ?>
<!--                                                    <li>-->
<!--                                                        <a href="javascript:void(0);" onclick="func_archive_job(--><?php //echo $listing['sid']; ?><!-- );"><i class="fa fa-archive"></i> Archive this Job</a>
<!--                                                    </li>-->
<!--                                                --><?php //} ?>
                                            <?php } ?>

                                            <?php if ($listing["active"] == 2) { ?>
                                                <?php if (check_access_permissions_for_view($security_details, 'clone_listing')) { ?>
                                                    <li><a href=<?= base_url('clone_listing') ?>/<?= $listing['sid'] ?>><i class="fa fa-clone"></i> Clone this Job</a></li>
                                                <?php } ?>

                                                <?php if (check_access_permissions_for_view($security_details, 'delete_archive_job')) { ?>
                                                    <li>
                                                        <a href="javascript:;" id="<?php echo $listing['sid']; ?>"  onclick="myPopup('delete', this.id);"><i class="fa fa-times-circle"></i> Delete this Job</a>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    <?php } else { ?>
        <div class="no-job-found" style="background-color: transparent;">
            <ul>
                <li>
                    <h3 class="no-data">No Jobs found! </h3>
                </li>
            </ul>
        </div>
    <?php } ?>
</div>
