<style>
    #tab-nav > .active > a,
    #tab-nav > .active > a:hover,
    #tab-nav > .active > a:focus {
        color: #fff;
        background: #3554dc;
        font-weight: bold;
        font-size: 16px;
    }
    
    .nav-tabs.nav-justified > li > a {
        color: #0000ff;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <!-- <ul class="nav nav-tabs nav-justified"> -->
        <ul class="nav nav-tabs nav-justified" style="background-color: #e0e0e0;" id="tab-nav">    
            <li class="active"><a data-toggle="tab" href="#pending">Pending</a></li>
            <li><a data-toggle="tab" href="#responded">Responded</a></li>
            <!-- <li><a data-toggle="tab" href="#read_only">Read Only</a></li> -->
            <li><a data-toggle="tab" href="#resolved">Resolved</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">
            <div id="pending" class="tab-pane fade in active">
                <h2 class="tab-title">Pending</h2>
                <div class="table-responsive table-outer">
                    <form action="" method="POST" id="xml_form">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Report Date</th>
                                <th>Report Name</th>
                                <th>Report Type</th>
                                <th>Status</th>
                                <th class="text-center last-col" colspan="4">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(sizeof($pending)>0){
                               
                                foreach($pending as $incident){?>
                                    <tr>
                                        <td><?php echo my_date_format($incident['current_date']); ?></td>
                                        <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                        <td><?php echo ucfirst($incident['report_type']); ?></td>
                                        <td><b><?= $incident['status'];?></b></td>
                                        <td class="text-center">
                                            <?php if (isSafetyIncident($incident['incident_type_id'])) { ?>
                                                <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_safety_incident/' . $incident['id']) ?>">Respond</a>
                                            <?php } else { ?>
                                                <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_single_assign/'.$incident['id'])?>"><?php echo $incident['report_type'] == 'confidential' && $incident['status'] != 'Closed' ? 'Respond' : 'Respond'; ?></a>
                                            <?php } ?> 
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-warning btn-block jsMarkItResolved" href="javascript:;" data-incidentId="<?php echo $incident['id']; ?>">Mark it Resolved</a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/2').'/'.$incident['id']; ?>" class="btn btn-info btn-block mb-2" src="<?php echo $incident['id']; ?>"><i class="fa fa-download"></i></a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/1').'/'.$incident['id']; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else{ ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <span class="no-data">No Incident Found</span>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <div id="responded" class="tab-pane fade in">
                <h2 class="tab-title">Responded</h2>
                <div class="table-responsive table-outer">
                    <form action="" method="POST" id="xml_form">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Report Date</th>
                                <th>Report Name</th>
                                <th>Report Type</th>
                                <th>Status</th>
                                <th class="text-center last-col" colspan="4">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(sizeof($responded)>0){
                                foreach($responded as $incident){?>
                                    <tr>
                                        <td><?php echo my_date_format($incident['current_date']); ?></td>
                                        <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                        <td><?php echo ucfirst($incident['report_type']); ?></td>
                                        <td><b><?php echo $incident['status']; ?></b></td>
                                        <td class="text-center">
                                            <?php if (isSafetyIncident($incident['incident_type_id'])) { ?>
                                                <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_safety_incident/' . $incident['id']) ?>">Respond</a>
                                            <?php } else { ?>
                                                <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_single_assign/'.$incident['id'])?>"><?php echo $incident['report_type'] == 'confidential' && $incident['status'] != 'Closed' ? 'Respond' : 'Respond'; ?></a>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-warning btn-block jsMarkItResolved" href="javascript:;" data-incidentId="<?php echo $incident['id']; ?>">Mark it Resolved</a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/2').'/'.$incident['id']; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-download"></i></a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/1').'/'.$incident['id']; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else{ ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <span class="no-data">No Incident Found</span>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <div id="read_only" class="tab-pane fade in">
                <h2 class="tab-title">Read Only</h2>
                <div class="table-responsive table-outer">
                    <form action="" method="POST" id="xml_form">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="col-xs-4">Report Date</th>
                                <th class="col-xs-4">Report Name</th>
                                <th class="col-xs-4">Report Type</th>
                                <th class="col-xs-4">Status</th>
                                <th class="col-xs-2 text-center last-col" width="1%" colspan="3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(sizeof($read_only)>0){
                                foreach($read_only as $incident){?>
                                    <tr>
                                        <td><?php echo my_date_format($incident['current_date']); ?></td>
                                        <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                        <td><?php echo ucfirst($incident['report_type']); ?></td>
                                        <td><b>Read Only</b></td>
                                        <td class="text-center">
                                            <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_single_assign/'.$incident['id'])?>"><?php echo $incident['report_type'] == 'confidential' && $incident['status'] != 'Closed' ? 'Respond' : 'View'; ?></a>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-warning btn-block jsMarkItResolved" href="javascript:;" data-incidentId="<?php echo $incident['id']; ?>">Mark it Resolved</a>
                                        </td>
                                    </tr>
                                <?php }
                            } else{ ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <span class="no-data">No Incident Found</span>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <div id="resolved" class="tab-pane fade in">
                <h2 class="tab-title">Resolved</h2>
                <div class="table-responsive table-outer">
                    <form action="" method="POST" id="xml_form">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Report Date</th>
                                <th>Report Name</th>
                                <th>Report Type</th>
                                <th>Status</th>
                                <th class="text-center last-col" colspan="3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(sizeof($closed)>0){
                                foreach($closed as $incident){?>
                                    <tr style="background-color: #cdf194;">
                                        <td><?php echo my_date_format($incident['current_date']); ?></td>
                                        <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                        <td><?php echo ucfirst($incident['report_type']); ?></td>
                                        <td style="color: #446f00;"><b>Resolved</b></td>
                                        <td class="text-center">
                                            <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_single_assign/'.$incident['id'])?>"><?php echo $incident['report_type'] == 'confidential' && $incident['status'] != 'Closed' ? 'View' : 'View'; ?></a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/2').'/'.$incident['id']; ?>" class="btn btn-info btn-block mb-2" src="<?php echo $incident['id']; ?>"><i class="fa fa-download"></i></a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/1').'/'.$incident['id']; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else{ ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <span class="no-data">No Incident Found</span>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.jsMarkItResolved', function() {
        var iid = $(this).attr('data-incidentId');
        alertify.confirm('Resolved?', 'Are you sure, you want to mark this incident resolved?', function() {
            $.ajax({
                type: 'POST',
                url: '<?= base_url('incident_reporting_system/mark_resolved') ?>',
                data: {
                    id: iid
                },
                success: function(response) {
                    if (response == 'Done') {
                        window.location.href = window.location.href;
                    }
                },
                error: function() {

                }
            });
        }, function() {

        });
    });
</script>



