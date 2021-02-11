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
                <!-- search form drop down -->
                <div class="hr-search-criteria opened">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" style="display: block;">
                    <!-- search form -->
                    <form method="GET" action="<?php echo base_url('reports/applicant_status_report/' . $company_sid); ?>" name="search" id="search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="field-row">
                                    <label>Keyword</label>
                                    <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : '';?>
                                    <input type="text" class="invoice-fields" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="field-row">
                                    <label class="text-left">Applicant Status</label>
                                    <?php $applicant_status = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : '';?>
                                    <select name="applicant_status" id="applicant_status">
                                        <?php if (!empty($applicant_statuses)) { ?>
                                            <option value="all">All</option>
                                            <?php foreach ($applicant_statuses as $status) { ?>
                                                <option <?php echo set_select('applicant_status', $status['name'], $applicant_status == $status['name']); ?> value="<?php echo $status['name']; ?>"><?php echo $status['name']; ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option <?php echo $applicant_status == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                            <option <?php echo $applicant_status == 'Not Contacted Yet' ? 'selected="selected"' : ''; ?> value="Not Contacted Yet">Not Contacted Yet</option>
                                            <option <?php echo $applicant_status == 'Left Message' ? 'selected="selected"' : ''; ?> value="Left Message" >Left Message</option>
                                            <option <?php echo $applicant_status == 'Contacted' ? 'selected="selected"' : ''; ?> value="Contacted" >Contacted</option>
                                            <option <?php echo $applicant_status == 'Candidate Responded' ? 'selected="selected"' : ''; ?> value="Candidate Responded">Candidate Responded</option>
                                            <option <?php echo $applicant_status == 'Interviewing' ? 'selected="selected"' : ''; ?> value="Interviewing">Interviewing</option>
                                            <option <?php echo $applicant_status == 'Submitted' ? 'selected="selected"' : ''; ?> value="Submitted">Submitted</option>
                                            <option <?php echo $applicant_status == 'Qualifying' ? 'selected="selected"' : ''; ?> value="Qualifying">Qualifying</option>
                                            <option <?php echo $applicant_status == 'Ready to Hire' ? 'selected="selected"' : ''; ?> value="Ready to Hire">Ready to Hire</option>
                                            <option <?php echo $applicant_status == 'Offered Job' ? 'selected="selected"' : ''; ?> value="Offered Job">Offered Job</option>
                                            <option <?php echo $applicant_status == 'Client Declined' ? 'selected="selected"' : ''; ?> value="Client Declined">Client Declined</option>
                                            <option <?php echo $applicant_status == 'Not In Consideration' ? 'selected="selected"' : ''; ?> value="Not In Consideration">Not In Consideration</option>
                                            <option <?php echo $applicant_status == 'Future Opportunity' ? 'selected="selected"' : ''; ?> value="Future Opportunity">Future Opportunity</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="field-row">
                                    <label class="">Date From</label>
                                    <?php $start_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y');?>
                                    <input class="invoice-fields"
                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                           type="text"
                                           name="start_date_applied"
                                           id="start_date_applied"
                                           value="<?php echo set_value('start_date_applied', $start_date); ?>"/>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="field-row">
                                    <label class="">Date To</label>
                                    <?php $end_date = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : date('m-d-Y');?>
                                    <input class="invoice-fields"
                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                           type="text"
                                           name="end_date_applied"
                                           id="end_date_applied"
                                           value="<?php echo set_value('end_date_applied', $end_date); ?>"/>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-8"></div>
                            <div class="col-xs-2">
                                <a href="#" id="btn_apply_filters" class="btn btn-success btn-block">Apply Filters</a>
                            </div>
                            <div class="col-xs-2">
                                <a href="<?php echo base_url('reports/applicant_status_report/' . $company_sid); ?>" id="btn_clear_filters" class="btn btn-success btn-block">Clear Filters</a>
                            </div>
                        </div>
                    </form>
                    <!-- search form -->
                </div>
                <!-- search form drop down -->
                <?php if (isset($applicants) && !empty($applicants)) { ?>
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
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <span class="pull-left">
                            <h1 class="hr-registered">Applicant Status Report</h1>
                        </span>
                        <span class="pull-right">
                            <h1 class="hr-registered">Total Records Found : <?php echo $applicants_count;?></h1>
                        </span>
                    </div>
                    <div class="hr-innerpadding">

                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-left">
                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count?></p>
                                </span>
                                <span class="pull-right">
                                    <?php echo $page_links; ?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div id="print_div" class="table-responsive">
                                    <table class="table table-bordered table-stripped">
                                        <thead>
                                        <tr>
                                            <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">Application Date</th>
                                            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Applicant Name</th>
                                            <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Job Title</th>
                                            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Email</th>
                                            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (isset($applicants) && !empty($applicants)) { ?>
                                            <?php foreach ($applicants as $applicant) { ?>
                                                <tr>
                                                    <td>
<!--                                                        --><?php //echo my_date_format($applicant['date_applied']); ?>

                                                        <?php echo reset_datetime(array(
                                                            'datetime' => $applicant['date_applied'],
                                                            // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                            // 'format' => 'h:iA', //
                                                            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                            'from_timezone' => $executive_user['timezone'], //
                                                            '_this' => $this
                                                        )) ?>
                                                    </td>
                                                    <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                    <td style="color:<?php echo ($applicant['Title'] != 'Job Deleted' ? 'green' : 'red'); ?>"><?php echo ($applicant['Title'] != '' ? $applicant['Title'] : 'Job Removed From System'); ?></td>
                                                    <td><?php echo $applicant['email']; ?></td>
                                                    <td>
                                                        <div class="contacts_label <?php echo $applicant['css_class']?>">
                                                            <?php echo ucwords($applicant['status']); ?>
                                                        </div>                                                        
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="5">
                                                    <div class="no-data"> No applicants found.</div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-left">
                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count?></p>
                                </span>
                                <span class="pull-right">
                                    <?php echo $page_links; ?>
                                </span>
                            </div>
                        </div>



                    </div>
                </div>
                <!-- table -->
                <?php if (isset($applicants) && !empty($applicants)) { ?>
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

    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    function generate_search_url(){
        var keyword = $('#keyword').val();
        var applicant_status = $('#applicant_status').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        applicant_status = applicant_status != '' && applicant_status != null && applicant_status != undefined && applicant_status != 0 ? encodeURIComponent(applicant_status) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';

        var url = '<?php echo base_url('reports/applicant_status_report/' . $company_sid); ?>' + '/' + keyword + '/' + applicant_status + '/' + start_date_applied + '/' + end_date_applied;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function () {
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        $('#applicant_status').selectize({
            onChange: function (value) {
                generate_search_url();
            }
        });

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());

        $('#keyword').on('keyup', function(){
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        // Search Area Toggle Function    
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });


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