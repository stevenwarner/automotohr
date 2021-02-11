<?php if (!$load_view) { ?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <div class="dashboard-conetnt-wrp">
                                <div class="form-title-section">
                                    <div class="margin-top">
                                        <h2><?php echo $title; ?> <small>( <strong class="messagesCounter"><?php echo $references_count; //count($references); ?></strong> Jobs Referred )</small></h2>
                                        <div class="form-btns">
                                            <a class="submit-btn" href="<?php echo base_url('my_referral_network/add'); ?>" >New</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12">
                                    <?php echo $links;  ?>
                                </div>
                                <?php if ($references) { ?>
                                <div class="table-responsive table-outer">
                                    <div class="table-wrp data-table">
                                        <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                            <thead>
                                            <tr>
                                                <th class="col-xs-2">Referred On</th>
                                                <th class="col-xs-4">Job Title</th>
                                                <th class="col-xs-2">Referred To</th>
                                                <th class="col-xs-4">Reference Email</th>
                                                <!--<th class="col-xs-1"></th>-->
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($references as $reference) { ?>
                                                <tr>
                                                    <td><?=reset_datetime(array($reference['referred_date'], '_this' => $this)); ?></td>
                                                    <td><?php echo $reference['Job_title']; ?></td>
                                                    <td><?php echo $reference['referred_to']; ?></td>
                                                    <td><?php echo $reference['reference_email']; ?></td>
                                                    <!--<td><a class="btn btn-default" href="<?php echo base_url('my_reference_network') . '/view/' . $reference['sid']; ?>"><i class="fa fa-eye"></i></a></td>-->
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php } else { ?>
                                    <div id="show_no_jobs" class="table-wrp">
                                        <span class="applicant-not-found">You haven't Referred any jobs!</span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <?php echo $links;  ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('manage_employer/employee_management/profile_right_menu_personal'); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">
<script>
//    $(document).ready(function () {
//        $('#reference_network_table').DataTable({
//            paging: true,
//            info: false,
//            stateSave: true,
//            order: [[ 0, 'desc' ]]
//        });
//    });
</script>
<?php }  else { ?>
    <?php $this->load->view('reference_network/index_ems'); ?>
<?php } ?>