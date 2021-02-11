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
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="field-row">
                                <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                <label>Job Title</label>
                                <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>">
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-2">
                            <a id="btn_apply_filters" class="btn btn-success btn-block" href="<?php echo base_url('reports/time_to_hire_job_report/' . $company_sid); ?>">Apply Filters</a>
                        </div>
                        <div class="col-xs-2">
                            <a id="btn_clear_filters" class="btn btn-success btn-block" href="<?php echo base_url('reports/time_to_hire_job_report/' . $company_sid); ?>">Clear Filters</a>
                        </div>
                    </div>
                </div>

                <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                <div class="bt-panel">
                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                        <i class="fa fa-print" aria-hidden="true"></i> 
                        Print
                    </a>
                    <form method="post" id="export" name="export">
                        <input type="hidden" name="submit" value="Export" />
                        <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                    </form>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <hr>
                </div>
                <div class="page-header-area">
                    <span class="page-heading pull-right">
                        <b><?= 'Total number of jobs:    ' . sizeof($jobs)?></b>
                    </span>
                </div>
                <?php } ?>
                <div class="hr-box">
                    <div id="print_div" class="table-responsive hr-innerpadding">
                        <table class="table table-stripped table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-xs-4">Job Title</th>
                                    <th class="col-xs-1 text-center">Job Date</th>
                                    <th class="col-xs-1 text-center">Applicants</th>
                                    <th class="col-xs-2 text-center">Average Days To Hire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                                    <?php foreach ($jobs as $job) { ?>

                                        <tr>
                                            <td><?php echo $job['Title']; ?></td>
                                            <td class="text-center">
<!--                                                --><?php //echo date_with_time($job['activation_date']); ?>
                                                <?php echo reset_datetime(array(
                                                    'datetime' => $job['activation_date'],
                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                    // 'format' => 'h:iA', //
                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                    'from_timezone' => $executive_user['timezone'], //
                                                    '_this' => $this
                                                )) ?>
                                            </td>
                                            <td class="text-center"><?php echo $job['applicant_count']; ?></td>
                                            <td class="text-center"><?php echo $job['average_days_to_hire']; ?> Day(s)</td>
                                        </tr>

                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="4">
                                            <div class="no-data">No jobs found.</div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                <div class="bt-panel">
                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                        <i class="fa fa-print" aria-hidden="true"></i> 
                        Print
                    </a>
                    <form method="post" id="export" name="export">
                        <input type="hidden" name="submit" value="Export" />
                        <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                    </form>
                </div>


                <?php } ?>
            </div>               					
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    function generate_search_url(){

        var keyword = $('#keyword').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';

        var url = '<?php echo base_url('reports/time_to_hire_job_report/' . $company_sid); ?>' + '/' + keyword;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function () {
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });

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
    }
</script>