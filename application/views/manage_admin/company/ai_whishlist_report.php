<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <form action="<?= base_url('ai_whishlist_data_report'); ?>">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><i class="fa fa-users"></i>AI Recruiter Wait-list Report</h1>
                                        </div>
                                        <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                            <strong>Click to modify search criteria</strong>
                                        </div>
                                        <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="col-lg-4 col-md-3 col-xs-12 col-sm-3 field-row">
                                                        <label>Start Date</label>
                                                        <input type="text" name="start_date" readonly class="invoice-fields" placeholder="M/D/Y" value="<?= $this->input->get('start_date') ?? date('m/d/Y'); ?>">
                                                    </div>

                                                    <div class="col-lg-4 col-md-3 col-xs-12 col-sm-3 field-row">
                                                        <label>End Date</label>
                                                        <input type="text" name="end_date" readonly class="invoice-fields" placeholder="M/D/Y" value="<?= $this->input->get('end_date') ?? date('m/d/Y'); ?>">
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="btn btn-success btn-block" style="padding: 9px;">Search</button>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                        <label>&nbsp;</label>
                                                        <a href="<?php echo base_url('ai_whishlist_data_report'); ?>" class="btn black-btn btn-block" style="padding: 9px;">Reset Search</a>
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
                                                    Name
                                                </th>
                                                <th>
                                                    Email
                                                </th>
                                                <th>
                                                    Date
                                                </th>
                                            </thead>
                                            <tbody>
                                                <?php if ($records) : ?>
                                                    <?php foreach ($records as $record) : ?>
                                                        <tr>
                                                            <td><?php echo $record['name']; ?></td>
                                                            <td>
                                                                <p><?php echo $record['email']; ?></p>
                                                            </td>
                                                            <td>
                                                                <?= formatDateToDB($record['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="3">
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
</script>