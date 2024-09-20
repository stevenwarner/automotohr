<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?= $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <strong>Advanced Search Filters</strong>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <form action="<?= base_url('reports/indeedJobs'); ?>" method="POST">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="export_csv" />
                                                            <!-- Filter first row -->
                                                            <div class="row">

                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                    <div class="field-row">
                                                                        <label>Status</label>
                                                                        <select id="js-action" name="dd-action">
                                                                            <option value="all">All</option>
                                                                            <option value="completed">Completed</option>
                                                                            <option value="expired">Expired</option>
                                                                            <option value="pending">Pending</option>
                                                                        </select>
                                                                    </div>
                                                                </div>





                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                    <div class="field-row">
                                                                        <label>Job Title</label>
                                                                        <input type="text" id="js-jobtitle" name="jobtitle" value="" class="invoice-fields" />
                                                                    </div>
                                                                </div>


                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <label class="">Start Date</label>
                                                                    <?php $start_date = date('m-1-Y'); ?>
                                                                    <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" autocomplete="off" />
                                                                </div>

                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <label class="">End Date</label>
                                                                    <?php $end_date =  date('m-t-Y'); ?>
                                                                    <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" autocomplete="off" />
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                <div class="field-row" style="margin-left:-12px;">
                                                                    <label>City</label>
                                                                    <input type="text" id="js-jobcity" name="jobcity" value="" class="invoice-fields" />
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                <div class="field-row">
                                                                    <label>State</label>
                                                                    <select id="js-jobstate" name="jobstate">
                                                                        <option value="all">All</option>
                                                                        <?php
                                                                        $states = db_get_active_states(227);
                                                                        foreach ($states as $stateRow) {
                                                                        ?>
                                                                            <option value="<?php echo $stateRow['sid']; ?>"><?php echo $stateRow['state_name']; ?></option>
                                                                        <?php } ?>

                                                                    </select>
                                                                </div>



                                                            </div>

                                                            <div class="row">

                                                            </div>

                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-5 pull-right">
                                                                    <div class="pull-right" style="margin-top: 30px;">
                                                                        <a id="js-apply-filter" class="btn btn-success " href="javascript:void()">Apply Filters</a> &nbsp;&nbsp;
                                                                        <a id="js-reset-filter" class="btn btn-success " href="javascript:void()">Reset Filters</a> &nbsp;&nbsp;
                                                                        <button type="submit" id="js-export" class="btn btn-success ">Export CSV</button>
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

                                <div class="row" id='canvasrow' style="display: none;">
                                    <div class="col-sm-4">
                                        <canvas id="jsJobsCanvas"></canvas>
                                        <br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <strong>Indeed Jobs Report</strong>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <!-- Pagination -->
                                                <div class="js-ip-pagination"></div>
                                                <!-- Content Table -->
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed" id="example">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Job Title</th>
                                                                        <th>Created On</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="js-data-area"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Pagination -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="js-ip-pagination"></div>
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
</div>


<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/moment.js"></script>
<script>
    $(function() {

        var intse = [];
        // Defaults
        var
            dataTarget = $('#js-data-area'),
            loaderTarget = $('#my_loader'),
            baseURI = "<?= base_url('reports') ?>/",
            baseHandlerURI = baseURI + 'handler',
            filterData = [],
            filterActive = null,
            filterOBJ = {
                action: 'get_indeed_joobs',
                employeeSid: 'all',
                page: 1
            },
            localEmployeeONJ = {},
            pOBJ = {
                'fetchReport': {
                    page: 1,
                    totalPages: 0,
                    limit: 0,
                    records: 0,
                    totalRecords: 0,
                    cb: fetchReport
                }
            };

        fetchFilter();
        loader(false);

        dataTarget.html('<tr><td colspan="' + ($('thead tr th').length) + '"><p class="alert alert-info text-center">Please apply filter to generate report</p></td></tr>');

        // Filter Start
        $('#js-apply-filter').click(applyFilter);
        $('#js-reset-filter').click(resetFilter);

        function resetFilter(e) {
            e.preventDefault();
            is_filter = false;
            $('#js-action').select2('val', 'all');
            $('#js-jobtitle').val('');
            $('#start_date_applied').val('');
            $('#end_date_applied').val('');

            $('#js-jobstate').select2('val', 'all');
            $('#js-jobcity').val('');



            pOBJ['fetchReport']['records'] = [];
            pOBJ['fetchReport']['totalPages'] =
                pOBJ['fetchReport']['totalRecords'] =
                pOBJ['fetchReport']['limit'] = 0;
            pOBJ['fetchReport']['page'] = 1;


            filterOBJ.statusAction = $('#js-action').val();
            filterOBJ.jobTitle = $('#js-jobtitle').val();
            filterOBJ.startDate = $('#start_date_applied').val();
            filterOBJ.endDate = $('#end_date_applied').val();

            filterOBJ.jobCity = $('#js-jobcity').val();
            filterOBJ.jobState = $('#js-jobstate').val();


            $('.js-ip-pagination').html('');
            dataTarget.html('');

            fetchReport();
        }
        //
        function applyFilter(e) {
            // loader();
            e.preventDefault();
            is_filter = true;
            pOBJ['fetchReport']['records'] = [];
            pOBJ['fetchReport']['totalPages'] =
                pOBJ['fetchReport']['totalRecords'] =
                pOBJ['fetchReport']['limit'] = 0;
            pOBJ['fetchReport']['page'] = 1;

            filterOBJ.statusAction = $('#js-action').val();
            filterOBJ.jobTitle = $('#js-jobtitle').val();
            filterOBJ.startDate = $('#start_date_applied').val();
            filterOBJ.endDate = $('#end_date_applied').val();
            filterOBJ.jobCity = $('#js-jobcity').val();
            filterOBJ.jobState = $('#js-jobstate').val();

            $('.js-ip-pagination').html('');
            dataTarget.html('');

            fetchReport();
        }
        //
        function fetchFilter() {
            $('#js-action').select2({
                closeOnSelect: true
            });
            $('#js-jobstate').select2({
                closeOnSelect: true
            });
        }

        // Filter Ends
        function fetchReport() {

            loader(true);
            //  filterOBJ.page = pOBJ['fetchReport']['page'];
            //
            $.post(baseHandlerURI, filterOBJ, function(resp) {
                //
                if (resp.Status === false) {
                    loader(false);
                    dataTarget.html('<tr><td colspan="' + ($('thead tr th').length) + '"><p class="alert alert-info text-center">' + (resp.response) + '</p></td></tr>');
                    return;
                }

                if (resp.Data.length == 0) {
                    loader(false);
                    dataTarget.html('<tr><td colspan="' + ($('thead tr th').length) + '"><p class="alert alert-info text-center">No records match</p></td></tr>');
                    return;
                }

                pOBJ['fetchReport']['records'] = resp.Data;
                if (pOBJ['fetchReport']['page'] == 1) {
                    pOBJ['fetchReport']['limit'] = resp.Limit;
                    pOBJ['fetchReport']['totalPages'] = resp.TotalPages;
                    pOBJ['fetchReport']['totalRecords'] = resp.TotalRecords;
                }
                //

                setTable();
                //
                load_pagination(
                    pOBJ['fetchReport']['limit'],
                    50,
                    $('.js-ip-pagination'),
                    'fetchReport'
                );
            });

        }

        //
        <?php
        $totalCompleted = 0;
        $totalPending = 0;
        $totalExpited = 0;
        if (!empty($alljobs)) {
            foreach ($alljobs as $jobRow) {
                if ($jobRow['is_processed'] == 0 && $jobRow['is_expired'] == 0) {
                    $totalPending++;
                }
                if (($jobRowrow['is_processed'] == 1 && $jobRow['is_expired'] == 0) || ($jobRow['is_processed'] == 1 && $jobRow['is_expired'] == 1)) {
                    $totalCompleted++;
                }
                if ($jobRow['is_processed'] == 0 && $jobRow['is_expired'] == 1) {
                    $totalExpited++;
                }
            }
        }

        ?>

        loadHourGraph('jsJobsCanvas', {
            data: {
                labels: ['Completed', 'Pending', 'Expired'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [
                        '<?php echo $totalCompleted ?>',
                        '<?php echo $totalPending ?>',
                        '<?php echo $totalExpited ?>',
                    ],
                    backgroundColor: [
                        '#81b431',
                        '#fd7a2a',
                        '#d9534f',
                    ],
                }]
            },
            textToShow: "Indeed Jobs"
        });



        function setTable() {
            if (pOBJ.fetchReport.records.length == 0) return;
            //
            var rows = '';

            let totalCompleted = 0;
            let totalExpited = 0;
            let totalPending = 0;
            //
            pOBJ.fetchReport.records.map(function(record) {
                //
                var bgColor = '';
                createdAt = moment(record.created_at).format(" MMMM Do  , YYYY , ddd HH:mm:ss");

                var jobStatus = '';
                if (record.is_processed == 0 && record.is_expired == 0) {
                    jobStatus = 'Pending';
                    totalPending++;

                }
                if ((record.is_processed == 1 && record.is_expired == 0) || (record.is_processed == 1 && record.is_expired == 1)) {
                    jobStatus = 'Completed';
                    totalCompleted++;
                }
                if (record.is_processed == 0 && record.is_expired == 1) {
                    jobStatus = 'Expired';
                    bgColor = 'style="background-color: #f2dede;"';
                    totalExpited++;
                }

                rows += '<tr ' + bgColor + '>';
                rows += '   <td class="vam">';
                rows += '       <strong>' + record.Title + '</strong>';
                rows += '   </td>';
                rows += '   <td class="vam">';
                rows += '       <span>' + createdAt + '</span>';
                rows += '   </td>';

                rows += '   <td class="vam">';
                rows += '       <span>' + jobStatus + '</span>';
                rows += '   </td>';

                rows += '   <td class="vam">';
                if (jobStatus == 'Completed' || jobStatus == 'Expired') {
                    rows += '       <a  class="btn btn-success" href="javascript:void()" onclick="jobPreview(' + record.job_sid + ')">View</a>';
                }
                if (jobStatus == 'Pending') {
                    rows += '       <a  class="btn btn-orange" href="javascript:void()">Run</a>';

                }
                rows += '   </td>';
                rows += '</tr>';
            });
            //
            dataTarget.html(rows);

            $("#canvasrow").show();

            loader(false);
        }


        // Pagination
        // Get previous page
        $(document).on('click', '.js-pagination-prev', pagination_event);
        // Get first page
        $(document).on('click', '.js-pagination-first', pagination_event);
        // Get last page
        $(document).on('click', '.js-pagination-last', pagination_event);
        // Get next page
        $(document).on('click', '.js-pagination-next', pagination_event);
        // Get page
        $(document).on('click', '.js-pagination-shift', pagination_event);
        // TODO convert it into a plugin
        function load_pagination(limit, list_size, target_ref, page_type) {
            //
            var obj = pOBJ[page_type];
            // parsing to int
            limit = parseInt(limit);
            obj['page'] = parseInt(obj['page']);
            // get paginate array
            var page_array = paginate(obj['totalRecords'], obj['page'], limit, list_size);
            // append the target ul
            // to top and bottom of table
            var rows = '';
            rows += '<div class="">';
            rows += '   <div class="row">';
            rows += '       <div class="col-sm-4 col-xs-12">';
            rows += '           <div class="pagination-left-content js-showing-target">';
            rows += '               <div class="js-show-record"></div>';
            rows += '           </div>';
            rows += '       </div>';
            rows += '       <div class="col-sm-8 col-xs-12">';
            rows += '           <nav aria-label="Pagination">';
            rows += '               <ul class="pagination cs-pagination js-pagination"></ul>';
            rows += '           </nav>';
            rows += '       </div>';
            rows += '   </div>';
            rows += '</div>';

            target_ref.html(rows);
            // set rows append table
            var target = target_ref.find('.js-pagination');
            var targetShowing = target_ref.find('.js-showing-target');
            // get total items number
            var total_records = page_array.total_pages;
            // load pagination only there
            // are more than one page
            if (obj['totalRecords'] >= limit) {
                // generate li for
                // pagination
                var rows = '';
                // move to one step back
                rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == 1 ? '' : 'js-pagination-first') + '">First</a></li>';
                rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == 1 ? '' : 'js-pagination-prev') + '">&laquo;</a></li>';
                // generate 5 li
                $.each(page_array.pages, function(index, val) {
                    rows += '<li class="' + (val == obj['page'] ? 'active page-item' : '') + '"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" data-page="' + (val) + '" class="' + (obj['page'] != val ? 'js-pagination-shift' : '') + '">' + (val) + '</a></li>';
                });
                // move to one step forward
                rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == page_array.total_pages ? '' : 'js-pagination-next') + '">&raquo;</a></li>';
                rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == page_array.total_pages ? '' : 'js-pagination-last') + '">Last</a></li>';
                // append to ul
                target.html(rows);
            }
            // append showing of records
            targetShowing.html('<p>Showing ' + (page_array.start_index + 1) + ' - ' + (page_array.end_index != -1 ? (page_array.end_index + 1) : 1) + ' of ' + (obj['totalRecords']) + '</p>');
        }
        // Paginate logic
        function paginate(total_items, current_page, page_size, max_pages) {
            // calculate total pages
            var total_pages = Math.ceil(total_items / page_size);

            // ensure current page isn't out of range
            if (current_page < 1) current_page = 1;
            else if (current_page > total_pages) current_page = total_pages;

            var start_page, end_page;
            if (total_pages <= max_pages) {
                // total pages less than max so show all pages
                start_page = 1;
                end_page = total_pages;
            } else {
                // total pages more than max so calculate start and end pages
                var max_pagesBeforecurrent_page = Math.floor(max_pages / 2);
                var max_pagesAftercurrent_page = Math.ceil(max_pages / 2) - 1;
                if (current_page <= max_pagesBeforecurrent_page) {
                    // current page near the start
                    start_page = 1;
                    end_page = max_pages;
                } else if (current_page + max_pagesAftercurrent_page >= total_pages) {
                    // current page near the end
                    start_page = total_pages - max_pages + 1;
                    end_page = total_pages;
                } else {
                    // current page somewhere in the middle
                    start_page = current_page - max_pagesBeforecurrent_page;
                    end_page = current_page + max_pagesAftercurrent_page;
                }
            }

            // calculate start and end item indexes
            var start_index = (current_page - 1) * page_size;
            var end_index = Math.min(start_index + page_size - 1, total_items - 1);

            // create an array of pages to ng-repeat in the pager control
            var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(i => start_page + i);

            // return object with all pager properties required by the view
            return {
                total_items: total_items,
                // current_page: current_page,
                // page_size: page_size,
                total_pages: total_pages,
                start_page: start_page,
                end_page: end_page,
                start_index: start_index,
                end_index: end_index,
                pages: pages
            };
        }
        //
        function pagination_event() {
            //
            var i = $(this).data('page-type');
            // When next is press
            if ($(this).hasClass('js-pagination-next') === true) {
                pOBJ[i]['page'] = pOBJ[i]['page'] + 1;
                pOBJ[i]['cb']($(this));
            } else if ($(this).hasClass('js-pagination-prev') === true) {
                pOBJ[i]['page'] = pOBJ[i]['page'] - 1;
                pOBJ[i]['cb']($(this));
            } else if ($(this).hasClass('js-pagination-first') === true) {
                pOBJ[i]['page'] = 1;
                pOBJ[i]['cb']($(this));
            } else if ($(this).hasClass('js-pagination-last') === true) {
                pOBJ[i]['page'] = pOBJ[i]['totalPages'];
                pOBJ[i]['cb']($(this));
            } else if ($(this).hasClass('js-pagination-shift') === true) {
                pOBJ[i]['page'] = parseInt($(this).data('page'));
                pOBJ[i]['cb']($(this));
            }
        }

        //
        function loader(is_show) {
            if (is_show == true) loaderTarget.fadeIn(500);
            else loaderTarget.fadeOut(500);
        }
    })


    //
    $(document).on('click', '.jsviewdoc', function(e) {
        e.preventDefault();
        $(this).parent().parent().next('tr').toggle();

    });

    function jobPreview(jobSid) {
        window.open('<?php echo base_url('preview_listing_iframe/'); ?>' + jobSid);
    }


    //
    function loadHourGraph(ref, options) {

        const config = {
            type: 'pie',
            data: options.data,

            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: options.textToShow
                    }
                }
            },
        };


        if (window.jobsChart != null) {
            window.jobsChart.destroy();
        }

        window.jobsChart = new Chart(document.getElementById(ref), config);

    }


    $('#start_date_applied').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>",
        onSelect: function(value) {
            $('#end_date_applied').datepicker('option', 'minDate', value);
        }
    }).datepicker('option', 'maxDate', $('#end_date_applied').val());

    $('#end_date_applied').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>",
        onSelect: function(value) {
            $('#start_date_applied').datepicker('option', 'maxDate', value);
        }
    }).datepicker('option', 'minDate', $('#start_date_applied').val());
</script>

<style>
    .select2-container .select2-selection--single .select2-selection__rendered {
        border: 1px solid #ccc;
        padding-left: 8px !important;
    }

    .pagination {
        margin: 0;
    }

    .cs-pagination li a {
        color: #5cb85c;
    }

    .cs-pagination li:hover a,
    .cs-pagination li.active:hover a,
    .cs-pagination li.active a {
        background-color: #81b431;
        color: #fff;
        border-color: #81b431;
    }
</style>