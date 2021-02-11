<div class="row">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a data-toggle="tab" href="#pending">Pending</a></li>
            <li><a data-toggle="tab" href="#responded">Responded</a></li>
            <li><a data-toggle="tab" href="#read_only">Read Only</a></li>
            <li><a data-toggle="tab" href="#resolved">Resolved</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">
            <div id="pending" class="tab-pane fade in active hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Pending</h2>
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
                                <?php if(sizeof($pending)>0){
                                    foreach($pending as $incident){?>
                                        <tr>
                                            <td><?php echo my_date_format($incident['current_date']); ?></td>
                                            <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                            <td><?php echo ucfirst($incident['report_type']); ?></td>
                                            <td><b><?= $incident['status'];?></b></td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_single_assign/'.$incident['id'])?>"><?php echo $incident['report_type'] == 'confidential' && $incident['status'] != 'Closed' ? 'Respond' : 'View'; ?></a>
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
            <div id="responded" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Responded</h2>
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
                                <?php if(sizeof($responded)>0){
                                    foreach($responded as $incident){?>
                                        <tr>
                                            <td><?php echo my_date_format($incident['current_date']); ?></td>
                                            <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                            <td><?php echo ucfirst($incident['report_type']); ?></td>
                                            <td><b><?php echo $incident['status']; ?></b></td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_single_assign/'.$incident['id'])?>"><?php echo $incident['report_type'] == 'confidential' && $incident['status'] != 'Closed' ? 'Respond' : 'View'; ?></a>
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
            <div id="read_only" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
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
            <div id="resolved" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Resolved</h2>
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
                                <?php if(sizeof($closed)>0){
                                    foreach($closed as $incident){?>
                                        <tr style="background-color: #cdf194;">
                                            <td><?php echo my_date_format($incident['current_date']); ?></td>
                                            <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                            <td><?php echo ucfirst($incident['report_type']); ?></td>
                                            <td style="color: #446f00;"><b>Resolved</b></td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_single_assign/'.$incident['id'])?>"><?php echo $incident['report_type'] == 'confidential' && $incident['status'] != 'Closed' ? 'Respond' : 'View'; ?></a>
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
</div>


