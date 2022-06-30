<style>
    .expandheading {
        cursor: pointer;
        font-size: 14x;
        color: #0c0cff;
        text-decoration: underline;

    }

    .expandheadingall {
        font-size: 14x;
        color: #0c0cff;

    }

    hr.employespan {
        border-top: 1px solid #dddddd;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .vam{
        vertical-align: middle;
    }
</style>
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
                                <span class="page-heading down-arrow">
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
                                                        <form action="<?= base_url('reports/employee_document'); ?>" method="POST">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="export_csv" />
                                                            <!-- Filter first row -->
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                    <div class="field-row">
                                                                        <label>Employee</label>
                                                                        <select id="js-employee" name="dd-employee[]" multiple="true"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                    <div class="field-row">
                                                                        <label>Status</label>
                                                                        <select id="js-status-emp" name="dd-status-emp[]" multiple="true">
                                                                            <option value="all">All</option>
                                                                            <option value="confidential">Confidential</option>
                                                                            <option value="authorizedsigners">Authorized Signers</option>
                                                                            <option value="approvalflow">Approval Flow</option>

                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <!-- Filter second row -->

                                                            <!-- Filter Buttons -->
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <br />
                                                                    <div class="pull-right">
                                                                        <a id="js-apply-filter" class="btn btn-success " href="javascript:void()">Apply Filters</a> &nbsp;&nbsp;
                                                                        <a id="js-reset-filter" class="btn btn-success " href="javascript:void()">Reset Filters</a> &nbsp;&nbsp;
                                                                        <button type="submit" id="js-export" class="btn btn-success ">Export CSV</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <hr />
                                                <!-- Pagination -->
                                                <div class="js-ip-pagination"></div>

                                                <hr />

                                                <!-- Content Table -->
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed" id="example">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Employee</th>
                                                                        <th>Document Title</th>
                                                                        <th>Authorized Signers</th>
                                                                        <th>Is Confidential</th>
                                                                        <th>Confidential Employees</th>
                                                                        <th>Visibility</th>
                                                                        <th>Approval Flow</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="js-data-area"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
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
                action: 'get_employee_document',
                employeeSid: 'all',
                employeeStatus: 'all',
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
        // loader(false);

        fetchReport();

        // Capture enter
        $(document).keypress(function(e) {
            if (e.which == 13) {
                // enter pressed
                $('#btn_apply_filters').click();
            }
        });

        // Filter Start

        $('#js-apply-filter').click(applyFilter);
        $('#js-reset-filter').click(resetFilter);

        function resetFilter(e) {
            e.preventDefault();
            is_filter = false;
            $('#js-employee').select2('val', 'all');
            $('#js-status-emp').select2('val', 'all');


            pOBJ['fetchReport']['records'] = [];
            pOBJ['fetchReport']['totalPages'] =
                pOBJ['fetchReport']['totalRecords'] =
                pOBJ['fetchReport']['limit'] = 0;
            pOBJ['fetchReport']['page'] = 1;

            filterOBJ.employeeSid = 'all';
            filterOBJ.employeeStatus = 'all';

            $('.js-ip-pagination').html('');
            dataTarget.html('');

            // fetchReport();
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

            filterOBJ.employeeSid = $('#js-employee').val();
            filterOBJ.employeeStatus = $('#js-status-emp').val();

            $('.js-ip-pagination').html('');
            dataTarget.html('');

            fetchReport();
        }
        //
        function fetchFilter() {
            $.post(baseHandlerURI, {
                action: 'get_employee_status_filter'
            }, function(resp) {
                //
                if (resp.Status === false) {
                    loader(false);
                    console.log('No record found.');
                    return;
                }
                //
                filterData = resp.Data;
                // Set Filter
                var allRow = '<option value="all">All</option>';
                var rows = '';
                rows += allRow;
                if (filterData !== undefined && filterData.length != 0) {
                    filterData.map(function(employee) {
                        localEmployeeONJ[employee.employeeId] = remakeEmployeeName(employee);
                        rows += '<option value="' + (employee.employeeId) + '">' + (localEmployeeONJ[employee.employeeId]) + '</option>';
                    });
                }
                $('#js-employee').html(rows);
                $('#js-employee').select2({
                    closeOnSelect: false
                });
                //  $('#js-status-class').select2();
                $('#js-status-emp').select2({
                    closeOnSelect: false
                });
                $('#js-employee').select2('val', 'all');
                $('#js-status-emp').select2('val', 'all');
            })
        }

        //
        function remakeEmployeeName(
            o,
            d
        ) {
            //
            var r = '';
            //
            if (d === undefined) r += o.first_name + ' ' + o.last_name;
            //
            if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
            //
            r += ' [';
            //
            if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
            //
            if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
            else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
            else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
            else r += o['access_level'];
            //
            r += ']';
            //
            return r;
        }
        // Filter Ends

        //
        function fetchReport() {
            if (filterData.length === 0) {
                intse = setInterval(function() {
                    fetchReport();
                }, 5000);
                return;
            }
            loader(true);
            if (intse !== undefined) clearInterval(intse);
            filterOBJ.page = pOBJ['fetchReport']['page'];

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
        function setTable() {
            if (pOBJ.fetchReport.records.length == 0) return;
            //
            var rows = '';
            //
            pOBJ.fetchReport.records.map(function(record) {
                //
                console.log({record})

                var authSigners = '';
                //
                if(record.authorize_signers){
                    
                }

                rows += '<tr>';
                rows += '   <td class="vam">';
                rows += '       <strong>'+(localEmployeeONJ[record.user_sid])+'</strong>';
                rows += '   </td>';
                rows += '   <td class="vam">';
                rows += '       <span>'+(record.document_title)+'</span>';
                rows += '   </td>';
                rows += '   <td class="vam">';
                rows += '       <span>'+(record.document_title)+'</span>';
                rows += '   </td>';
                rows += '</tr>';
            });
            //
            dataTarget.html(rows);
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




    function print_page(elem) {

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
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
        mywindow.focus();

    }



    $(document).on('click', '.majorpoints', function() {
        $(this).find('.hider').toggle();
    });
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