<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fa fa-long-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
                <!-- search form drop down -->
                <div class="hr-search-criteria <?php echo isset($flag) && $flag == true ? 'opened' : ''; ?>">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" <?php echo isset($flag) && $flag == true ? 'style="display:block"' : ''; ?>>
                    <!-- search form -->
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <div class="field-row">
                                <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                <label>Document Title</label>
                                <input placeholder="" class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <div class="field-row">
                                <label class="text-left">Company</label>
                                <select name="company_sid" id="company_sid">
                                    <?php if (!empty($executive_user_companies)) { ?>
                                        <option value="all">All</option>
                                        <?php foreach ($executive_user_companies as $company) { ?>
                                            <option value="<?php echo $company['company_sid']; ?>" <?php echo $this->uri->segment(3) == $company['company_sid'] ? " selected" : "" ?>><?php echo $company['company_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <div class="field-row">
                                <label class="">Date From</label>
                                <?php $start_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y', strtotime('-17 days')) ; ?>
                                <?php //_e($start_date,true);?>
                                <input class="invoice-fields "
                                    placeholder="<?php echo date('Y-m-d', strtotime('-17 days')); ?>"
                                    type="text"
                                    name="start_date_assigned"
                                    id="start_date_assigned"
                                    value="<?php echo set_value('start_date_assigned', $start_date); ?>" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <div class="field-row">
                                <label class="">Date To</label>
                                <?php $end_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y'); ?>
                                <input class="invoice-fields"
                                    placeholder="<?php echo date('m-d-Y'); ?>"
                                    type="text"
                                    name="end_date_assigned"
                                    id="end_date_assigned"
                                    value="<?php echo set_value('end_date_assigned', $end_date); ?>" />
                            </div>
                        </div>

                        <div class="field-row field-row-autoheight col-lg-12 text-right">
                            <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                            <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('reports/admin_report'); ?>">Reset Filters</a>
                        </div>
                    </div>
                </div>

                <!-- table -->
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <span class="pull-left">
                            <h1 class="hr-registered">Activity Report</h1>
                        </span>
                        <span class="pull-right">
                            <h1 class="hr-registered">Total Records Found : <?php echo count($assigneddocuments); ?></h1>
                        </span>
                    </div>
                    <div class="hr-innerpadding">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive" id="print_div">
                                    <table class="table table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Employee Name</th>
                                                <th>Document Title</th>
                                                <th>Assigned On </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($assigneddocuments)) { ?>
                                                <?php foreach ($assigneddocuments as $document) { ?>
                                                    <tr>
                                                        <td><?php echo $document['document_title']; ?></td>
                                                        <td><?php echo $document['first_name']." ".$document['last_name'] ; ?></td>
                                                        <td><?php echo $document['companyName']; ?></td>
                                                        <td><?php echo  reset_datetime(array(
                                                                'datetime' => $document['assigned_date'],
                                                                'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                'from_timezone' => $executive_user['timezone'], //
                                                                '_this' => $this
                                                            )); ?></td>

                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td class="text-center" colspan="10">
                                                        <div class="no-data">No record found.</div>
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
<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        var keyword = $('#keyword').val();
        var company_sid = $('#company_sid').val();
        var start_date_assigned = $('#start_date_assigned').val();
        var end_date_assigned = $('#end_date_assigned').val();
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        start_date_assigned = start_date_assigned != '' && start_date_assigned != null && start_date_assigned != undefined && start_date_assigned != 0 ? encodeURIComponent(start_date_assigned) : 'all';
        end_date_assigned = end_date_assigned != '' && end_date_assigned != null && end_date_assigned != undefined && end_date_assigned != 0 ? encodeURIComponent(end_date_assigned) : 'all';

        var url = '<?php echo base_url('reports/admin_report'); ?>' + '/' + company_sid + '/' + keyword + '/' + start_date_assigned + '/' + end_date_assigned;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {
        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#company_sid').selectize({
            onChange: function(value) {
                generate_search_url();
            }
        });


        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        // Search Area Toggle Function    
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        }).val();

        $('#start_date_assigned').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                //console.log(value);
                $('#end_date_assigned').datepicker('option', 'minDate', value);

                generate_search_url();
            }
        });

        $('#end_date_assigned').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                //console.log(value);
                $('#start_date_assigned').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_assigned').val());

    });

    function print_page(elem) {
        $("table").removeClass("horizontal-scroll");

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();

        $("table").addClass("horizontal-scroll");
    }
</script>