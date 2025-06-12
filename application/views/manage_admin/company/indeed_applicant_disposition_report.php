<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <form action="<?= base_url('indeed_applicant_disposition_report'); ?>" id="cookies_form">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><i class="fa fa-users"></i>Indeed Applicant Disposition Status Report</h1>
                                        </div>
                                        <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                            <strong>Click to modify search criteria</strong>
                                        </div>
                                        <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="col-lg-4 col-md-3 col-xs-12 col-sm-3 field-row">
                                                        <label>Start Date</label>
                                                        <input type="text" name="start_date" readonly class="invoice-fields" value="<?= $this->input->get('start_date') ?? ''; ?>">
                                                    </div>

                                                    <div class="col-lg-4 col-md-3 col-xs-12 col-sm-3 field-row">
                                                        <label>End Date</label>
                                                        <input type="text" name="end_date" readonly class="invoice-fields" value="<?= $this->input->get('end_date') ?? ''; ?>">
                                                        <input name="export" id="export" placeholder="" value="0" type="hidden">

                                                    </div>



                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12">

                                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3 field-row">
                                                    </div>
                                                    <div class="col-lg-4 col-md-3 col-xs-12 col-sm-3 field-row">
                                                    </div>

                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="btn btn-success btn-block" style="padding: 9px;">Search</button>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                        <label>&nbsp;</label>
                                                        <a href="<?php echo base_url('indeed_applicant_disposition_report'); ?>" class="btn black-btn btn-block" style="padding: 9px;">Reset Search</a>
                                                    </div>



                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                        <label>&nbsp;</label>
                                                        <button type="button" id="js-export" class="btn btn-success btn-block" style="padding: 9px;">Export CSV</button>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--  -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <strong>(<span class="text-info"><?= count($records); ?></span>) Records found.</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <th>
                                                    Applicant name
                                                </th>
                                                <th>
                                                    Company Name
                                                </th>
                                                <th>
                                                    ATS Status
                                                </th>
                                                <th>
                                                    Indeed Status
                                                </th>
                                                <th>
                                                    Changed By
                                                </th>
                                                <th>
                                                    Action Date
                                                </th>
                                                <th>
                                                    Errors/Success
                                                </th>
                                            </thead>
                                            <tbody>
                                                <?php if ($records) : ?>
                                                    <?php foreach ($records as $record) : ?>
                                                        <tr>
                                                            <td><?php echo $record['first_name'] . ' ' . $record['last_name']; ?></td>
                                                            <td>
                                                                <p><?php echo $record['CompanyName']; ?></p>
                                                            </td>
                                                            <td>
                                                                <?php echo $record['ats_status']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $record['indeed_status']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo  $record['created_by'] != 0 ? getEmployeeOnlyNameBySID($record['created_by']) : ''; ?>
                                                            </td>
                                                            <td>
                                                                <?= formatDateToDB($record['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                            </td>
                                                            <td>

                                                                <?php echo $record['status']; ?>

                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="7">
                                                            <p class="alert alert-info text-center">
                                                                No records found!
                                                            </p>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('input[name="start_date"]').datepicker({
            changeYear: true,
            changeMonth: true
        });
        $('input[name="end_date"]').datepicker({
            changeYear: true,
            changeMonth: true
        });
    })



    //
    $('#js-export').click(function() {

        $("#export").val("1");
        $('#cookies_form').submit();

    });
</script>