<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php echo $title; ?> </span>
                    </div>
                    <div class="table-responsive table-outer">
                        <?php if (sizeof($safety_checklists) > 0) { ?>
                            <div class="table-responsive table-outer">
                                <table class="table table-bordered table-stripped table-hover " id="example"  data-order='[[ 0, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th><a>Safety Checklist Name</a></th>
                                            <th><a>Submitted Name</a></th>
                                            <th><a>Submitted Date</a></th>
                                            <th><a>Action</a></th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php foreach ($safety_checklists as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $value['submitted_checklist_name'] ?></td>
                                                <td><?php echo $value['first_name'].' '.$value['last_name']; ?></td>
                                                <td><?php echo  date_format (new DateTime($value['submitted_time']), 'M d Y h:m a'); ?></td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>safety_checklist/<?php echo $value['sid'] ?>" class="btn btn-sm btn-success type" title="View Safety Checklist">View</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="table-wrp data-table product-detail-area mylistings-wrp text-center">
                                <p>No Safety Checklist Found.</p>
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
