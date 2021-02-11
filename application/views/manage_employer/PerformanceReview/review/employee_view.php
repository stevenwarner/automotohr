<?php
$d = [];
if (count($review['reviewees'])) {
    foreach ($review['reviewees'] as $r) {
        if (!isset($d[$r['sid']])) {
            $d[$r['sid']] = $r;
        }
        $d[$r['sid']]['Reviewers'][] = [
            'first_name' => $r['first_name'],
            'last_name' => $r['last_name'],
            'access_level' => $r['access_level'],
            'access_level_plus' => $r['access_level_plus'],
            'is_executive_admin' => $r['is_executive_admin'],
            'job_title' => $r['job_title'],
            'pay_plan_flag' => $r['pay_plan_flag'],
        ];
    }
}
?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/PerformanceReview/sidebar'); ?>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="right-content">
                        <!-- Header -->
                        <?php $this->load->view('manage_employer/PerformanceReview/headerBar', [
                            'Link' => [
                                base_url('dashboard'),
                                'Dashboard',
                            ],
                            'Link2' => [
                                base_url('performance/review/view'),
                                'BACK',
                            ],
                            'Text' => 'Performance Review - Review(s)'
                        ]); ?>

                        <!--  -->
                        <div class="clearfix"></div>

                        <style>
                            .cs-progress-ih{
                                background-color: #81b431;
                                line-height: 30px;
                            }
                        </style>

                        <?php if(count($d)) { ?>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-6">
                                <h5><strong>Reviewed</strong></h5>
                                <div class="progress" style="height: 30px; margin-bottom: 5px;">
                                    <div class="progress-bar cs-progress-ih progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                        style="width: <?=($review['reviewed']['completed'] * 100 / $review['reviewed']['total']);?>%;" aria-valuenow="<?=($review['reviewed']['completed'] * 100 / $review['reviewed']['total'] );?>" aria-valuemin="0" aria-valuemax="<?=$review['reviewed']['total'];?>"><?=$review['reviewed']['completed'];?>%</div>
                                    </div>
                                <p><strong><?=$review['reviewed']['completed'];?></strong> out of <strong><?=$review['reviewed']['total'];?></strong></p>
                            </div>
                            <div class="col-sm-6">
                                <h5><strong>Feedback(s)</strong></h5>
                                <div class="progress" style="height: 30px; margin-bottom: 5px;">
                                    <div class="progress-bar cs-progress-ih progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                        style="width: <?=($review['feedback']['completed'] * 100 / $review['feedback']['total']);?>%;" aria-valuenow="<?=($review['feedback']['completed'] * 100 / $review['feedback']['total'] );?>" aria-valuemin="0" aria-valuemax="<?=$review['feedback']['total'];?>"><?=$review['feedback']['completed'];?>%</div>
                                </div>
                                <p><strong><?=$review['feedback']['completed'];?></strong> out of <strong><?=$review['feedback']['total'];?></strong></p>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Table -->
                        <div class="cs-s">
                            <!-- Loader -->
                            <div class="cs-loader">
                                <i class="fa fa-circle-o-notch fa-spin"></i>
                            </div>
                            <div class="table-responsive table-outer">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>ID</th>
                                        <th>Reviewee Name</th>
                                        <th>Reviewer(s)</th>
                                        <th>Status</th>
                                        <th class="col-sm-2">Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php if (count($d)) { ?>
                                        <?php foreach ($d as $reviewee) { 
                                                $rev = '';
                                                foreach($reviewee['Reviewers'] as $a) $rev .= remakeEmployeeName($a, true).", ";?>
                                        <tr data-id="<?= $reviewee['sid']; ?>">
                                            <td><?= $reviewee['sid']; ?></td>
                                            <td><?= remakeEmployeeName([
                                                            'first_name' => $reviewee['efirst_name'],
                                                            'last_name' => $reviewee['elast_name'],
                                                            'pay_plan_flag' => $reviewee['epay_plan_flag'],
                                                            'access_level' => $reviewee['eaccess_level'],
                                                            'access_level_plus' => $reviewee['eaccess_level_plus'],
                                                            'job_title' => $reviewee['ejob_title'],
                                                        ], true); ?></td>
                                            <td data-container="body" class="js-popover" data-placement="left"
                                                data-content="<?=$rev;?>" data-trigger="hover">
                                                <?= count($reviewee['Reviewers']); ?> Reviewers
                                            </td>
                                            <td><?= $reviewee['is_started'] == 1 ? "Started" : "Pending"; ?></td>
                                            <td>
                                                <?php if ($reviewee['is_started'] == 1) { ?>
                                                <a href="javascript:void(0)" class="btn btn-danger js-end-review" title="End Review"><i
                                                        class="fa fa-ban"></i></a>
                                                <?php } else { ?>
                                                <a href="javascript:void(0)" class="btn btn-success js-start-review" title="Start Review"><i
                                                        class="fa fa-shield"></i></a>
                                                <?php } ?>
                                                <a href="<?=base_url('performance/review/detail').'/'.$review['main']['sid'].'/'.$reviewee['employee_sid'];?>"
                                                    class="btn btn-default" title="View Answers"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        <?php }
                                        } else { ?>
                                        <tr>
                                            <td colspan="5">
                                                <p class="alert alert-info text-center">No records found.</p>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
<?php $this->load->view("manage_employer/PerformanceReview/main.css");
?><?php $this->load->view("manage_employer/PerformanceReview/Pagination.css");
?>
</style>
<script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<?php $this->load->view("manage_employer/PerformanceReview/scripts/common.php"); ?>
<script>
<?php $this->load->view("manage_employer/PerformanceReview/Pagination.js"); ?>
<?php $this->load->view('manage_employer/PerformanceReview/review/scripts/employee_view'); ?>
</script>