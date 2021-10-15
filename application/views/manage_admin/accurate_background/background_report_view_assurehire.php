<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
    //
    $total = 0;
    $completed = 0;
    $completedPercentage = 0;
    //
    $rows = '';
    //
    if(!empty($background_order['order_response'])){
        foreach($background_order['order_response']['summary'] as $summary){
            //
            $total++;
            //
            if($summary['status'] === 'complete'){
                $completed++;
            }
            //
            $rows .= '<tr>';
            $rows .= '  <td>'.($summary['searchId']).'</td>';
            $rows .= '  <td>'.($summary['search']).'</td>';
            $rows .= '  <td>'.($summary['status']).'</td>';
            $rows .= '  <td>'.(!empty($summary['result']) ? $summary['result'] : 'N/A').'</td>';
            $rows .= '  <td>'.($summary['flagged'] == 1 ? 'Yes' : 'No').'</td>';
            $rows .= '</tr>';
        }
        //
        $completedPercentage = ceil($completed*100/ $total);
    }
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"
                                                aria-hidden="true"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right"
                                            href="<?php echo base_url('manage_admin/accurate_background') ?>"><i
                                                class="fa fa-long-arrow-left" aria-hidden="true"></i> Background Check
                                            Orders</a>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered">
                                                        Order Information
                                                        <span class="pull-right">
                                                            <button class="btn btn-success js_view_report" data-url=" <?=$background_order['report_url'];?>">
                                                                View Report
                                                            </button>
                                                        </span>
                                                    </h1>
                                                </div>

                                                <div class="table-responsive hr-innerpadding">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <caption></caption>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Company Name</th>
                                                                <td><?php echo ucwords(strtolower($background_order['company_info']['CompanyName'])); ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Employer Name</th>
                                                                <td><?php echo $background_order['employer_info']['first_name'] . ' ' . $background_order['employer_info']['last_name']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Candidate Name</th>
                                                                <td><?php echo $background_order['candidate_info']['first_name'] . ' ' . $background_order['candidate_info']['last_name']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Candidate Email</th>
                                                                <td><?php echo $background_order['candidate_info']['email'];?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Candidate Type</th>
                                                                <td><?php echo ucwords($background_order['users_type']); ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Order Date</th>
                                                                <td><?php echo convert_date_to_frontend_format($background_order['date_applied'], true); ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Product Name</th>
                                                                <td><?php echo $background_order['product_name']; ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Product Type</th>
                                                                <td><?php echo $background_order['product_type']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Product Brand</th>
                                                                <td><?php echo $background_order['product_brand'] != 'assurehire' ? 'Accurate' : 'AssureHire'; ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Product Price</th>
                                                                <td><?php echo $background_order['product_price']; ?>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered pull-left">Order Status</h1>
                                                    <div class="pull-right">

                                                    </div>
                                                </div>
                                                <div class="table-responsive hr-innerpadding">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <caption></caption>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Accu. Background Search Id</th>
                                                                <td><?=$background_order['external_id'];?></td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Accu. Background Package Id</th>
                                                                <td><?=$background_order['package_id'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Status</th>
                                                                <td><?=
                                                                    !empty($background_order['order_response']) 
                                                                    ? $background_order['order_response']['status']
                                                                    : 'Awaiting Candidate Response'
                                                                    ;?></td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Result</th>
                                                                <td>
                                                                    <a href="<?=$background_order['report_url'];?>" target="_blank">
                                                                        <?=$background_order['report_url'];?>
                                                                    </a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Percent Complete</th>
                                                                <td>
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-success progress-bar-striped"
                                                                            role="progressbar" aria-valuenow=""
                                                                            aria-valuemin="0" aria-valuemax="100"
                                                                            style="width:<?=$completedPercentage;?>%">
                                                                            <?=$completedPercentage;?>%
                                                                            Complete
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Date Completed</th>
                                                                <td><?php echo !empty($background_order['order_response']) 
                                                                ? formatDateToDB(explode('.',$background_order['order_response']['completedDate'])[0], 'Y-m-d\TH:i:s', DATE_WITH_TIME) 
                                                                : ''; ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col" class="col-xs-4">Notes</th>
                                                                <td><?php echo !empty($background_order['package_response']) ? $background_order['package_response']['orderInfo']['specialInstruction'] : ''; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered pull-left">Order Summary</h1>
                                                </div>

                                                <div class="table-responsive hr-innerpadding">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Search Id</th>
                                                                <th scope="col">Search</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col">Result</th>
                                                                <th scope="col">Flagged</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(!empty($rows)) {
                                                                    echo $rows;
                                                                } else{
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <p class="alert alert-info text-center">
                                                                                No summary found
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            ?>
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

<?php $this->load->view('iframeLoader'); ?>

<script>
$('.js_view_report').click(function(e) {
    //
    e.preventDefault();
    //
    var url = $(this).data('url');
    //
    var rows = '';
    rows += `
        <div class="modal fade" id="js-report-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header header-success">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Background Report</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h4>Use the below details to login.</h4>
                                <p><strong>Username:</strong> <?php echo ASSUREHIR_USR ?></p>    
                                <p><strong>Password:</strong> <?php echo ASSUREHIR_PWD ?></p>    
                            </div>
                        </div>
                        
                        
                        <iframe id="js-report-iframe" style="width: 100%; height: 500px;" frameBorder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        `;
    //
    $('#js-report-modal').remove();
    $('body').append(rows);
    $('#js-report-modal').modal(true);
    //
    loadIframe(url, '#js-report-iframe', true);
});
</script>
