<style>
    .panel-group-wrp .panel-title .glyphicon {

        border: 0px solid #000 !important
    }
</style>

<?php
//
$newArray = [];
//
if (!empty($graph1['Records'])) {
    //
    foreach ($graph1['Records'] as $record) {
        //
        if ($record['is_completed'] == 1) {
            continue;
        }
        //
        $url = ($record['is_manager'] ? 'feedback' : 'review') . '/';
        $url .= $record['review_sid'] . '/';
        $url .= $record['reviewee_sid'] . '/';
        $url .= $record['reviewer_sid'];
        //
        if (!isset($newArray[$record['review_sid']])) {
            $newArray[$record['review_sid']] = [
                'Name' => $record['review_title'],
                'Count' => 0,
                'Rows' => ''
            ];
        }
        //
        $newArray[$record['review_sid']]['Count']++;
        //
        $newArray[$record['review_sid']]['Rows'] .= '
            <tr>
                <td style="vertical-align: middle;">
                    <p class="csF16">
                        ' . (ucwords($record['first_name'] . ' ' . $record['last_name'])) . '
                    </p>
                </td>
                <td style="vertical-align: middle;">
                    <p class="csF16">
                        ' . (ucwords($record['reviewee_first_name'] . ' ' . $record['reviewee_last_name'])) . '
                    </p>
                </td>

                  <td style="vertical-align: middle;">
                    <p class="csF14">
                        (' . (formatDateToDB($record['review_start_date'], DB_DATE, DATE)) . ' - 
                        ' . (formatDateToDB($record['review_end_date'], DB_DATE, DATE)) . ')
                    </p>
                </td>
                
                <td style="vertical-align: middle;">
                    <p class="csF16">
                        ' . ($record['is_manager'] ? 'Reporting Manager' : 'Reviewer') . '
                    </p>
                </td>
              
                <td style="vertical-align: middle;" class="js-actions">
                    <a href="' . (purl($url)) . '" class="btn btn-orange csF16" target="_blank">
                        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp; View Review
                    </a>
                </td>
            </tr>';
    }
}

?>
<div class="row ">
    <div class="col-xs-12">
        <div class="panel-group-wrp">
            <div class="panel-group" id="accordion">
                <div class="panel panel-theme">
                    <div class="panel-heading" style="background-color: #81b431; color: #fff;">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <h4 class="panel-title " style="color: #fff; padding-left: 15px;">
                                Advanced Search Filters <span class="glyphicon glyphicon-plus" style="color: #fff;"></span>
                            </h4>
                        </a>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse <?php if (isset($filter_state) && $filter_state == true) {
                                                                                echo 'in';
                                                                            } ?>">
                        <form method="get" enctype="multipart/form-data" action="<?php base_url('performance-management/report') ?>">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                        <div class="field-row">
                                            <label class="">Reviewers</label>

                                            <select id="js-reviewers" name="reviewers[]" multiple="true">
                                                <option value="all">All</option>
                                                <?php
                                                foreach ($reviewers as $review) { ?>
                                                    <option value="<?php echo $review["sid"] ?>"><?php echo remakeEmployeeName($review) ?></option>
                                                <?php   }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                        <div class="field-row">
                                            <label class="">Period Start</label>
                                            <?php $start_date = $periodStart != '' ? formatDateToDB($periodStart, DB_DATE, 'm-d-Y') : ''; ?>
                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="period_start" id="period_start" value="<?php echo set_value('start_date_applied', $start_date); ?>" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                        <div class="field-row">
                                            <label class="">Period End</label>
                                            <?php $end_date = $periodEnd != ''  ? formatDateToDB($periodEnd, DB_DATE, 'm-d-Y') : ''; ?>
                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="period_end" id="period_end" value="<?php echo set_value('end_date_applied', $end_date); ?>" autocomplete="off" />
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="field-row autoheight text-right">
                                            <button type="submit" id="btn_apply_filters" class="btn btn-success">Apply Filters</button>
                                            <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('performance-management/report'); ?>">Reset Filters</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!--  -->
<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
    <!--  -->
    <br />
    <!-- Overall -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #81b431;">
            <div class="row">
                <div class="col-md-11 col-xs-11">
                    <!--  -->
                    <h3 class="csF16 csB7 csW mt0 mb0 pa10 pb10">
                        Report
                    </h3>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <!-- Graph 1 -->
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <canvas id="jsTimeoffPieGraph" height="300px"></canvas>
                    </div>
                    <h3 class="csF16 text-center">
                        <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Based on the started review(s).
                    </h3>
                </div>
                <!-- Graph 3 -->
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div>
                        <canvas id="jsTimeoffPieGraph2" height="300px"></canvas>
                    </div>
                    <h3 class="csF16 text-center">
                        <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Review(s) By Status.
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-body">

            <div class="row" style="margin-bottom: 10px;">
                <div class="col-xs-12">
                    <?php
                    $startDate = $start_date != '' ? $start_date : 'all';
                    $endDate = $end_date != '' ? $end_date : 'all';
                    ?>
                    <div class="form-group">
                        <a href="<?php echo base_url('performance-management/export_report/' . $startDate . '/' . $endDate . '/' . urlencode($selectedReviewers)); ?>" class="submit-btn pull-right">
                            <i class="fa fa-print" aria-hidden="true"></i>
                            Export
                        </a>
                        <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');">
                            <i class="fa fa-print" aria-hidden="true"></i>
                            Print
                        </a>
                        <?php //if ($this->uri->segment(2) != 'sharedreportdetail') { 
                        ?>
                        <!-- <a href="javascript:void(0)" class="submit-btn pull-right" id="jsShareToReviewer"><i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp; Share</a>-->
                        <?php // } 
                        ?>
                    </div>
                </div>
            </div>


            <div id="print_div">
                <?php
                if (!empty($newArray)) {
                    foreach ($newArray as $row) {
                ?>
                        <div class="pane panel-theme">
                            <div class="panel-heading" style="background-color: #81b431;">
                                <h5 class="csF16 csB7 mt0 mb0 csW">
                                    <?= $row['Name']; ?>
                                    <span class="pull-right">
                                        Records Found: <?= $row['Count']; ?>
                                    </span>
                                </h5>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Reviewer</th>
                                            <th scope="col">Reviewee</th>
                                            <th scope="col">Cycle Period</th>
                                            <th scope="col">Review Type</th>
                                            <th scope="col" class="js-actions">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $row['Rows']; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>


        </div>
    </div>
</div>

<script>
    var graph1 = <?= json_encode($graph1); ?>;
    var graph2 = <?= json_encode($graph2); ?>;

    function print_page(elem) {
        $(".js-actions").hide();
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>Reviewer(s) that have-not completed the reviews Reports</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        $(".js-actions").show();
        mywindow.focus();
    }


    //

    /*
    $('#jsShareToReviewer').click(getReviewers);

    function getReviewers(e) {
        Model({
            Id: 'jsShareToReviewerModel',
            Loader: 'jsShareToReviewerLoader',
            Body: '<div class="container"><div id="jsShareToReviewerBody"></div></div>',
            Title: 'Share Report To Reviewers'
        }, );

        <?php
        //  $reviewersList = '<form method="post" id="formsharereport" action="' . base_url('performance-management/reviwers_report_share') . '" ><br><label>Reviewers</label><select id="js-reviewers" name="reviewers[]" multiple="true"><option value"all">All</option>';
        //  foreach ($reviewers as $review) {
        //       $options .= '<option value="' . $review["sid"] . '">' . remakeEmployeeName($review) . '</option>';
        //   }

        //   $reviewersList = $reviewersList . $options . '</select> </form>';
        ?>

        <?php
        // $sendbtn = '<span class = "pull-right" > <a href = "javascript:void(0)"   class = "btn btn-orange"  id = "js-shareReport" > <i class = "fa fa-paper-plane"  aria - hidden = "true" > </i>&nbsp; Sahre</a></span>'; 
        ?>

        $('#jsShareToReviewerBody').html('<?php //echo $reviewersList . $sendbtn; 
                                            ?>');

        ml(false, 'jsShareToReviewerLoader');
        $('#js-reviewers').select2({
            closeOnSelect: false
        });
    }
        */



    $('#js-reviewers').select2({
        closeOnSelect: false
    });

    $('#js-reviewers').select2('val', <?php echo $selectedReviewers ?>);

    //
    $(document).on("click", "#js-shareReport", function() {
        let selectedReviewers = $("#js-reviewers").val();
        if (selectedReviewers == null || selectedReviewers == '') {
            alertify.alert('Please select a reviewer');
            return;
        }
        $('#formsharereport').submit()
    });


    $('#period_start').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:+1",

    }).datepicker('option', 'maxDate', $('#end_date_applied').val());


    $('#period_end').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:+1",

    }).datepicker('option', 'minDate', $('#start_date_applied').val());
</script>