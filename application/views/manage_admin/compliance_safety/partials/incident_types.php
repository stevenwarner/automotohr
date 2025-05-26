<div class="hr-box">
    <div class="hr-box-header bg-header-green">
        <div class="hr-registered">
            Compliance Report Incident Types
        </div>
    </div>
    <div class="hr-innerpadding">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="last-col" width="1%" colspan="3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!--All records-->

                    <?php if (sizeof($incident_types) > 0) {
                        foreach ($incident_types as $type) {
                    ?>
                            <tr>
                                <td>
                                    <?= $type['compliance_incident_type_name'] ?>
                                </td>

                                <td id="status-<?= $type['id'] ?>">
                                    <?= $type['status'] == 1 ? 'Active' : 'In Active' ?>
                                </td>

                                <td>
                                    <a title="Edit Type" href="<?php echo base_url() ?>manage_admin/compliance_safety/incident_types/edit/<?php echo $type['id']; ?>" class="btn btn-info btn-sm pencil_useful_link">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($type['status'] == 1) { ?>
                                        <button
                                            class="btn btn-sm btn-danger jsDeactivateIncidentTYpe" id="<?php echo $type['id']; ?>"
                                            title="Disable Type" src="Disable"><i class="fa fa-toggle-off"></i></button>
                                    <?php } else { ?>
                                        <button
                                            class="btn btn-sm btn-primary jsActivateIncidentTYpe" id="<?php echo $type['id']; ?>"
                                            title="Enable Type" src="Enable"><i class="fa fa-toggle-on"></i></button>
                                    <?php } ?>
                                </td>
                                <td class="hidden"><a href="<?php echo base_url('manage_admin/compliance_safety/incident_types/view_incident_questions/' . $type['id']) ?>"
                                        class="btn btn-warning btn-sm"
                                        title="Delete Employer">View Questions</a>
                                </td>

                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <span class="no-data">No Incident Type Found</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>