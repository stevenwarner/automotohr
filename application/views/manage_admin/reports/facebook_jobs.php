<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
$tr = '';
$Pending =
$Rejected =
$Deleted =
$Approved = 0;

//
foreach($jobs as $job){
    //
    if($job['is_deleted']){
        $Deleted++;
        continue;
    }
    //
    $cl = "text-success";
    
    if($job['status'] == 'PENDING'){
        $cl = "text-warning";
        $Pending++;
    }
    if($job['status'] == 'REJECTED'){
        $cl = "text-danger";
        $Rejected++;
    }
    if($job['status'] == 'APPROVED'){
        $Approved++;
    }
    $tr .= '<tr class="jsRows" data-type="'.($job['status']).'">';
    $tr .= ' <td>';
    $tr .= '       <a href="https://www.automotosocial.com/display-job/'.$job['job_id'].'" target="_blank">'.$job['Title'].'</a>';
    $tr .= '   </td>';
    $tr .= '   <td>';
    $tr .= '       <a href="https://www.facebook.com/'.$job['external_id'].'" target="_blank">'.$job['external_id'].'</a>';
    $tr .= '   </td>';
    $tr .= '   <td class="'.($cl).'"><b>'.$job['status'].'</b></td>';
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
                                            <b><i>Click the links below to filter the jobs</i></b>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-2 col-xs-12" style="padding: 10px 20px;">
                                                <p style="cursor: pointer;" data-type="PENDING" class="text-warning jsTypeClick"><strong>Pending Jobs: </strong><?=$Pending;?></p>
                                            </div>
                                            <div class="col-sm-2 col-xs-12" style="padding: 10px 20px;">
                                                <p style="cursor: pointer;" data-type="REJECTED" class="text-danger jsTypeClick"><strong>Rejected Jobs: </strong><?=$Rejected;?></p>
                                            </div>
                                            <div class="col-sm-2 col-xs-12" style="padding: 10px 20px;">
                                                <p style="cursor: pointer;" data-type="APPROVED" class="text-success jsTypeClick"><strong>Approved Jobs: </strong><?=$Approved;?></p>
                                            </div>
                                            <div class="col-sm-2 col-xs-12" style="padding: 10px 20px;">
                                                <p style="cursor: pointer;" data-type="TOTAL" class="text-warning jsTypeClick"><strong>Total Jobs: </strong><?=$Pending+$Rejected+$Approved+;?></p>
                                            </div>
                                            <div class="col-sm-2 col-xs-12" style="padding: 10px 20px;">
                                                <p class="text-danger"><strong>Deleted Jobs: </strong><?=$Deleted;?></p>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Job Title</th>
                                                                <th scope="col">Facebook Job Link</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col">Reason</th>
                                                                <th scope="col">Last Updated On</th>
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

<script>
    $(function(){
        $('.jsTypeClick').click(function(event){
            //
            event.preventDefault();
            //
            if($(this).data('type') == 'TOTAL'){
                $('tr.jsRows').show();
                return;
            }
            //
            $('tr.jsRows').hide();
            $('tr.jsRows[data-type="'+($(this).data('type'))+'"]').show();
        });
    })
</script>