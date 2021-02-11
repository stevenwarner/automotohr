<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard/reports/' . $company_sid); ?>">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back to Reports
                    </a>
                </div>

                <div class="hr-search-criteria opened">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" style="display: block;">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <div class="field-row">
                                <label>Applicant Name</label>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                            <div class="field-row">
                                <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>">
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-2">
                            <a id="btn_apply_filters" class="btn btn-success btn-block" href="<?php echo base_url('reports/applicant_interview_scores_report/' . $company_sid); ?>">Apply Filters</a>
                        </div>
                        <div class="col-xs-2">
                            <a id="btn_clear_filters" class="btn btn-success btn-block" href="<?php echo base_url('reports/applicant_interview_scores_report/' . $company_sid); ?>">Clear Filters</a>
                        </div>
                    </div>
                </div>

                <?php if (isset($companies_applicant_scores) && sizeof($companies_applicant_scores) > 0) { ?>
                    <div class="col-xs-12 col-sm-12 margin-top">
                        <div class="row">
                            <div class="bt-panel">
                                <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                    <i class="fa fa-print" aria-hidden="true"></i> 
                                    Print
                                </a>
                                <form method="post" id="export" name="export">
                                    <input type="hidden" name="submit" value="Export" />
                                    <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export To Excel</button>
                                </form>
                            </div>                                                               
                        </div>
                    </div>
                <?php } ?>
                <!-- table -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="hr-box-header bg-header-green">
                            <span class="label label-default pull-right" style="font-size: 14px; background-color:#518401; padding: 0.5em 0.8em;">
                                Total <?php echo count($companies_applicant_scores); ?> Applicant Interview(s)
                            </span>
                        </div>
                        <div class="add-new-company">
                            <div class="hr-box">
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="hr-box-body">
                                        <div class="table-responsive hr-innerpadding" id="print_div">
                                            <table class="table table-bordered horizontal-scroll">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" rowspan="2">Interview Date</th>
                                                        <th class="text-center" rowspan="2">Applicant</th>
                                                        <th class="text-center" rowspan="2">Conducted By</th>
                                                        <th class="text-center" rowspan="2">For Position</th>
                                                        <th class="text-center col-xs-2" colspan="2">Evaluation Score</th>
                                                        <th class="text-center col-xs-2" colspan="2">Overall Score</th>
                                                        <th class="text-center" rowspan="2">Star Rating</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center col-xs-1">Applicant</th>
                                                        <th class="text-center col-xs-1">Job Relevancy</th>
                                                        <th class="text-center col-xs-1">Applicant</th>
                                                        <th class="text-center col-xs-1">Job Relevancy</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($companies_applicant_scores)) {
                                                        ?>
                                                        <?php foreach ($companies_applicant_scores as $applicant_score) { ?>
                                                            <tr>
                                                                <td class="text-center">
<!--                                                                    --><?php //echo convert_date_to_frontend_format($applicant_score['created_date']); ?>
                                                                    <?php echo reset_datetime(array(
                                                                        'datetime' => $applicant_score['created_date'],
                                                                        // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                                        // 'format' => 'h:iA', //
                                                                        'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                        'from_timezone' => $executive_user['timezone'], //
                                                                        '_this' => $this
                                                                    )) ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo ucwords($applicant_score['first_name'] . ' ' . $applicant_score['last_name']); ?>
                                                                </td>
                                                                <td>
                                                                    <div><strong><?php echo ucwords($applicant_score['conducted_by_first_name'] . ' ' . $applicant_score['conducted_by_last_name']); ?></strong></div>
                                                                    <div><small><?php echo ucwords($applicant_score['conducted_by_job_title']); ?></small></div>
                                                                </td>
                                                                <td>
                                                                    <?php echo ucwords($applicant_score['job_title']); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div><strong><?php echo $applicant_score['candidate_score']; ?></strong> Point(s)</div>
                                                                    <div><small>( Out of 100 )</small></div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div><strong><?php echo $applicant_score['job_relevancy_score']; ?></strong> Point(s)</div>
                                                                    <div><small>( Out of 100 )</small></div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div><strong><?php echo $applicant_score['candidate_overall_score'] * 10; ?></strong> Point(s)</div>
                                                                    <div><small>( Out of 100 )</small></div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div><strong><?php echo $applicant_score['job_relevancy_overall_score'] * 10; ?></strong> Point(s)</div>
                                                                    <div><small>( Out of 100 )</small></div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="star_div star-rating">
                                                                        <input id="star_rating" value="<?php echo $applicant_score['star_rating']; ?>" type="number" class="rating" min=0 max=5 step=0.2 data-size="xs" readonly="readonly">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="9">
                                                                No Applicants Found
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table -->
                <?php if (isset($companies_applicant_scores) && sizeof($companies_applicant_scores) > 0) { ?>
                    <div class="col-xs-12 col-sm-12 margin-top">
                        <div class="row">
                            <div class="bt-panel">
                                <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                    <i class="fa fa-print" aria-hidden="true"></i> 
                                    Print
                                </a>
                                <form method="post" id="export" name="export">
                                    <input type="hidden" name="submit" value="Export" />
                                    <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export To Excel</button>
                                </form>
                            </div>                                                               
                        </div>
                    </div>
                <?php } ?>
            </div>               					
        </div>
    </div>
</div>
<script type="text/javascript">
    function generate_search_url(){

        var keyword = $('#keyword').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';

        var url = '<?php echo base_url('reports/applicant_interview_scores_report/' . $company_sid); ?>' + '/' + keyword;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function () {

        jQuery('.hr-search-criteria').click(function () {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });
        $('#keyword').on('keyup', function () {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');
    });

    function print_page(elem)
    {
        $('table').removeClass('horizontal-scroll');
        $('.star_div').removeClass('star-rating');
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
        $('table').addClass('horizontal-scroll');
        $('.star_div').addClass('star-rating');
    }
</script>