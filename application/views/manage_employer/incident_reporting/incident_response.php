<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php echo $title; ?> </span>
                    </div>
                    <div class="table-responsive table-outer">
                        <?php if (sizeof($incidents_reported) > 0) { ?>
                            <div class="table-responsive table-outer">
                                <table class="table table-bordered table-stripped table-hover " id="example"  data-order='[[ 0, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th>Incident Type</th>
                                            <th>Submitted By</th>
                                            <th>Submitted Date</th>
                                            <th>Action</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php foreach ($incidents_reported as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $value['incident_name']; ?></td>
                                                <td>
                                                    <?php if ($value['report_type'] == 'anonymous') { ?>
                                                        Anonymous
                                                    <?php } else { ?>
                                                        <?php echo $value['first_name'].' '.$value['last_name']; ?>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo  date_format (new DateTime($value['current_date']), 'M d Y h:m a'); ?></td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>incident_reported/<?php echo $value['id'] ?>" class="btn btn-sm btn-success type" title="View Safety Checklist">View</a>
                                                </td>    
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="table-wrp data-table product-detail-area mylistings-wrp text-center">
                                <p>No Incident Reported.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">
