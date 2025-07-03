<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php

$tr = '';
$complaint = 0;
$bounced = 0;
foreach ($reportData as $job) {
    //
    if ($job['is_complaint']) {
        $complaint++;
    }
    if ($job['is_bounced']) {
        $bounced++;
    }
    $tr .= '<tr>';
    $tr .= '    <td>' . $job['email'] . '</td>';
    $tr .= '    <td class="text-' . ($job['is_bounced'] == 1 ? "success" : "danger") . '"><strong>' . ($job['is_bounced'] == 1 ? "YES" : "NO") . '<strong></td>';
    $tr .= '    <td class="text-' . ($job['is_complaint'] == 1 ? "success" : "danger") . '"><strong>' . ($job['is_complaint'] == 1 ? "YES" : "NO") . '<strong></td>';
    $tr .= '    <td>' . DateTime::createfromformat((strpos($job['note'], '.') === false ? 'Y-m-d\TH:i:sP' : 'Y-m-d\TH:i:s.uP'), $job['note'])->format('M d, D Y H:i') . '</td>';
    $tr .= '</tr>';
} ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">








                                <div class="hr-box" id="print_div">
                                    <div class="hr-box-header bg-header-green">
                                        <span class="pull-left">
                                            <h1 class="hr-registered">Facebook Job Status</h1>
                                        </span>
                                        <span class="pull-right">
                                            <h1 class="hr-registered">Total Records Found :
                                                <?php echo $data_count; ?>
                                            </h1>
                                        </span>
                                    </div>
                                    <div class="hr-innerpadding">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p><strong>Complaints: </strong><?= $complaint; ?></p>
                                                <p><strong>Bounced: </strong><?= $bounced; ?></p>
                                                <hr />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <span class="pull-left">
                                                    <p>Showing <?php echo $from_records; ?> to
                                                        <?php echo $to_records; ?> out of
                                                        <?php echo $data_count ?>
                                                    </p>
                                                </span>
                                                <span class="pull-right"
                                                    style="margin-top: 20px; margin-bottom: 20px;">
                                                    <?php echo $page_links ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive hr-innerpadding">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Email</th>
                                                                <th>Bounce</th>
                                                                <th>Complaint</th>
                                                                <th>Last Updated On</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                       <?php echo $tr; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <span class="pull-right">
                                                    <?php echo $page_links ?>
                                                </span>
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
    </div>
</div>