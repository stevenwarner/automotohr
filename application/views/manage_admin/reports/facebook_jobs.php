<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
$tr = '';
$inDraft =0;
$inOpen =0;

foreach($jobs as $job){
    if($job['status'] == 'OPEN'){
        $inOpen++;
    }
    if($job['status'] == 'DRAFT'){
        $inDraft++;
    }
    $tr .= '<tr>';
    $tr .= ' <td>';
    $tr .= '       <a href="https://www.automotosocial.com/display-job/'.$job['job_id'].'" target="_blank">'.$job['Title'].'</a>';
    $tr .= '   </td>';
    $tr .= '   <td>';
    $tr .= '       <a href="https://www.facebook.com/'.$job['external_id'].'" target="_blank">'.$job['external_id'].'</a>';
    $tr .= '   </td>';
    $tr .= '   <td>'.$job['status'].'</td>';
    $tr .= '   <td>'.$job['reason'].'</td>';
    $tr .= '   <td>'.DateTime::createfromformat('Y-m-d H:i:s', $job['created_at'])->format('M d, D Y H:i').'</td>';
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

                                <div class="hr-box">
                                    <div class="hr-box-header bg-header-green">
                                        <span class="pull-left">
                                            <h1 class="hr-registered">Facebook Job Status</h1>
                                        </span>
                                        <span class="pull-right">
                                            <h1 class="hr-registered">Total Records Found :
                                                <?php echo count($jobs);?></h1>
                                        </span>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p><strong>Jobs marked as Draft: </strong><?=$inDraft;?></p>
                                                <p><strong>Jobs marked as Open: </strong><?=$inOpen;?></p>
                                                <hr />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Job Title</th>
                                                                <th>Facebook Job Link</th>
                                                                <th>Status</th>
                                                                <th>Reason</th>
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