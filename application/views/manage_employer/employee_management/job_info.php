<style>
    .normal {
        font-weight: normal;

    }
</style>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                    <div class="form-title-section"><br>
                        <h2>Job Information</h2>
                        <div class="text-right">
                            <a href="<?php echo base_url('job_info_add') . '/employee/' . $employer["sid"]; ?>" class="btn btn-success ">Add New Job</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default ems-documents js-search-header">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#primary_job_title" aria-expanded="true">
                                            <span class="glyphicon glyphicon-minus"></span>
                                            <strong> Primary </strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="primary_job_title" class="panel-collapse collapse in" aria-expanded="true">

                                    <div class="panel-body">

                                        <?php

                                      //  _e($primaryJobData, true);
                                        if (!empty($primaryJobData)) { ?>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 ">

                                                    <h4 style="margin-top: 0">
                                                    </h4>
                                                    <p class="csF16 normal">
                                                        <strong>Job Title:</strong> <?php echo $primaryJobData[0]['title'] ?>
                                                    </p>


                                                    <p class="csF16 normal">
                                                        <strong> Week Days Off: </strong> <?php echo $primaryJobData[0]['week_days_off'] ?>
                                                    </p>

                                                </div>

                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5 form-group">

                                                    <p class="csF16 normal">
                                                        <strong>Shift: </strong> <?php echo $primaryJobData[0]['shift_start_time'] ?> - <?php echo $primaryJobData[0]['shift_end_time'] ?>
                                                    </p>

                                                    <p class="csF16 normal">
                                                        <strong>Break: </strong> <?php echo $primaryJobData[0]['break_hour'] ?>:<?php echo $primaryJobData[0]['break_minutes'] ?>
                                                    </p>


                                                </div>

                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1 form-group">

                                                    <p class="csF16">
                                                        <strong><a href="<?php echo base_url('job_info_edit/' . $primaryJobData[0]['sid']) ?>" class="btn btn-success btn-sm" title="Edit Job"><i class="fa fa-pencil"></i></a></strong>
                                                    </p>
                                                    <p class="csF16">
                                                    </p>

                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default ems-documents js-search-header">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#active_job_title" aria-expanded="false">
                                            <span class="glyphicon glyphicon-plus"></span>
                                            <strong>Active</strong>
                                            <div class="pull-right total-records"><b>Total: <?php echo count($activeJobData); ?></b></div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="active_job_title" class="panel-collapse collapse " aria-expanded="false">

                                    <div class="panel-body">
                                        <?php
                                        // _e($activeJobData, true);
                                        foreach ($activeJobData as $activeDataRow) { ?>

                                            <table class="table table-bordered table-striped fixTable-header">

                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 ">

                                                                    <h4 style="margin-top: 0">
                                                                    </h4>
                                                                    <p class="csF16 normal">
                                                                        <strong>Job Title:</strong> <?php echo $activeDataRow['title'] ?>
                                                                    </p>


                                                                    <p class="csF16 normal">
                                                                        <strong> Week Days Off: </strong> <?php echo $activeDataRow['week_days_off'] ?>
                                                                    </p>

                                                                </div>

                                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5 form-group">

                                                                    <p class="csF16 normal">
                                                                        <strong>Shift: </strong> <?php echo $activeDataRow['shift_start_time'] ?> - <?php echo $activeDataRow['shift_end_time'] ?>
                                                                    </p>

                                                                    <p class="csF16 normal">
                                                                        <strong>Break: </strong> <?php echo $activeDataRow['break_hour'] ?>:<?php echo $activeDataRow['break_minutes'] ?>
                                                                    </p>


                                                                </div>

                                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1 form-group">

                                                                    <p class="csF16">
                                                                        <strong><a href="<?php echo base_url('job_info_edit/' . $activeDataRow['sid']) ?>" class="btn btn-success btn-sm" title="Edit Job"><i class="fa fa-pencil"></i></a></strong>
                                                                    </p>
                                                                    <p class="csF16">
                                                                        <strong><a href="javascript:;" class="btn btn-danger btn-sm" title="Delete Job" onclick="deleteJob('<?php echo $activeDataRow['sid'] ?>')"><i class="fa fa-times"></i></a></strong>
                                                                    </p>

                                                                </div>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        <?php  } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default ">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle " data-toggle="collapse" data-parent="#accordion" href="#inactive_job_title" aria-expanded="false">
                                            <span class="glyphicon glyphicon-plus"></span>
                                            <strong> In-Active</strong>
                                            <div class="pull-right total-records"><b>Total: <?php echo count($inActiveJobData); ?></b></div>
                                        </a>
                                    </h4>

                                </div>
                                <div id="inactive_job_title" class="panel-collapse collapse " aria-expanded="false">

                                    <div class="panel-body">
                                        <?php

                                        foreach ($inActiveJobData as $inactiveDataRow) { ?>

                                            <table class="table table-bordered table-striped fixTable-header">

                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 ">

                                                                    <h4 style="margin-top: 0">
                                                                    </h4>
                                                                    <p class="csF16 normal">
                                                                        <strong>Job Title:</strong> <?php echo $inactiveDataRow['title'] ?>
                                                                    </p>


                                                                    <p class="csF16 normal">
                                                                        <strong> Week Days Off: </strong> <?php echo $inactiveDataRow['week_days_off'] ?>
                                                                    </p>

                                                                </div>

                                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5 form-group">

                                                                    <p class="csF16 normal">
                                                                        <strong>Shift: </strong> <?php echo $inactiveDataRow['shift_start_time'] ?> - <?php echo $inactiveDataRow['shift_end_time'] ?>
                                                                    </p>

                                                                    <p class="csF16 normal">
                                                                        <strong>Break: </strong> <?php echo $inactiveDataRow['break_hour'] ?>:<?php echo $inactiveDataRow['break_minutes'] ?>
                                                                    </p>


                                                                </div>

                                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1 form-group">

                                                                    <p class="csF16">
                                                                        <strong><a href="<?php echo base_url('job_info_edit/' . $inactiveDataRow['sid']) ?>" class="btn btn-success btn-sm" title="Edit Job"><i class="fa fa-pencil"></i></a></strong>
                                                                    </p>
                                                                    <p class="csF16">
                                                                        <strong><a href="javascript:;" class="btn btn-danger btn-sm" title="Delete Job" onclick="deleteJob('<?php echo $inactiveDataRow['sid'] ?>')"><i class="fa fa-times"></i></a></strong>
                                                                    </p>

                                                                </div>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteJob(sid) {
        alertify.confirm('Confirmation', 'Are you sure you want to delete this job', function() {
           window.location = "<?php echo base_url('job_info_delete') ?>/" + sid;

        }, function() {

        });

    }
</script>