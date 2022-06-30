<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
    $startDate = $this->uri->segment(4) != '' ? $this->uri->segment(4) : date('m-d-Y');
    $endDate   = $this->uri->segment(5) != '' ? $this->uri->segment(5) : date('m-d-Y');
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
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-users"></i><?php echo $page_title; ?> <a href="<?=base_url('manage_admin/copy_applicants');?>" class="btn btn-success pull-right">Copy Applicants</a></h1>
                                    </div>
                                    <!-- Main Page -->
                                    <div id="js-main-page">
                                        <div class="hr-search-criteria">
                                            <strong>Click to modify search criteria</strong>
                                        </div>
                                        <div class="hr-search-main" style="display: block;">
                                            <form method="GET" action="javascript:void(0)" id="js-search-filter">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                        <div class="field-row">
                                                            <label class="">Date From</label>
                                                            <input class="invoice-fields"
                                                                   type="text"
                                                                   readonly="true"
                                                                   name="js-start-date-input"
                                                                   id="js-start-date-input"
                                                                   value="<?=$startDate;?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                        <div class="field-row">
                                                            <label class="">Date To</label>
                                                            <input class="invoice-fields"
                                                                   type="text"
                                                                   readonly="true"
                                                                   name="js-end-date-input"
                                                                   id="js-end-date-input"
                                                                   value="<?=$endDate;?>"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                        <div class="field-row">
                                                            <label class="">&nbsp;</label>
                                                            <a class="btn btn-success btn-block js-apply-filter-btn" href="javascript:void(0)" >Apply Filters</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                        <div class="field-row">
                                                            <label class="">&nbsp;</label>
                                                            <a class="btn btn-success btn-block js-reset-filter" href="javascript:void(0)">Reset Filters</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="pull-left">
                                                    <h1 class="hr-registered">Copy Applicants Report</h1>
                                                </span>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row"><div class="col-sm-12 js-ip-pagination"></div></div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>From Company</th>
                                                                        <th>To Company</th>
                                                                        <th>Copied Date/Time</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="js-data-area">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row"><div class="col-sm-12 js-ip-pagination"></div></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Detail Page -->
                                    <div id="js-detail-page" style="margin-top: 80px; display: none;">
                                        <div class="">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p>Copied applicants from <strong id="js-detail-from"></strong> to <strong id="js-detail-to"></strong>
                                                        <button class="btn btn-default pull-right js-back-btn"><i class="fa fa-arrow-left"></i>&nbsp; Back</button>
                                                    </p>
                                                    <br />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Job Title</th>
                                                                    <th>Copied Applicants</th>
                                                                    <th>Existed Applicants</th>
                                                                    <th>Failed Applicants</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="js-detail-area"></tbody>
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
</div>

<!-- Loader -->
<div class="text-center cs-loader js-loader">
    <div id="file_loader" class="cs-loader-file"></div>
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait...</div>
    </div>
</div>

<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script src="<?= base_url() ?>assets/calendar/moment.min.js"></script>

<script>
    $(function copyApplicantReport(){
        var baseURL = "<?=base_url('manage_admin/report/copy_applicants_report');?>/",
        from_pop = false,
        errorTarget = $('.js-error-msg'),
        //
        pOBJ = { 
            'fetchRecords' : {
                page: 1,
                totalPages: 0,
                records: 0,
                totalRecords: 0,
                cb: fetchRecords
            }
        },
        xhr = null;

        //
        $(document).on('click', '.js-view-detail', detailView);
        $(document).on('click', '.js-back-btn', resetView);
        $(document).on('click', '.js-reset-filter', resetFilter);

        $('.js-apply-filter-btn').click(function(e){
            e.preventDefault();
            //
            pOBJ['fetchRecords']['totalRecords'] = 0;
            pOBJ['fetchRecords']['totalPages'] = 0;
            pOBJ['fetchRecords']['page'] = 1;
            pOBJ['fetchRecords']['records'] = [];
            //
            $('#js-data-area').html('');
            //
            if(from_pop === false)
            history.pushState({url: generate_search_url()}, "", generate_search_url(true));
            // Start fetch process
            fetchRecords();
            from_pop = false;
        });
        //
        $('.js-apply-filter-btn').trigger('click');
        // Datepickers
        $('#js-start-date-input').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (v) { $('#js-end-date-input').datepicker('option', 'minDate', v); }
        })

        $('#js-end-date-input').datepicker({
            dateFormat: 'mm-dd-yy'
        }).datepicker('option', 'minDate', $('#js-start-date-input').val());

        //
        function fetchRecords(){
            if(xhr != null) return;
            $('.js-error-row').remove();
            xhr = $.post(baseURL+'handler', {
                action: 'fetch_records',
                startDate: $('#js-start-date-input').val(),
                endDate: $('#js-end-date-input').val(),
                page: pOBJ['fetchRecords']['page']
            }, function(resp) {
                xhr = null;
                //
                if(resp.Status === false && pOBJ['fetchRecords']['page'] == 1){
                    $('.js-ip-pagination').html('');
                    loader('hide');
                    $('#js-data-area').html('<tr class="js-error-row"><td colspan="4"><p class="alert alert-info text-center">'+( resp.Response )+'</p></td></tr>')
                }
                //
                if(resp.Status === false){
                    loader('hide');
                    $('.js-ip-pagination').html('');
                    return;
                }

                if(pOBJ['fetchRecords']['page'] == 1) {
                    pOBJ['fetchRecords']['totalPages'] = resp.TotalPages;
                    pOBJ['fetchRecords']['totalRecords'] = resp.TotalRecords;
                }
                setTable(resp);
            });
        }

        //
        function setTable(resp){
            var rows = '';
            if(resp.Records.length == 0) return;
            //
            $.each(resp.Records, function(i, v){
                rows += '<tr>';
                rows += '   <td>'+( v.from_company_name )+'</td>';
                rows += '   <td>'+( v.to_company_name )+'</td>';
                rows += '   <td>'+( v.created_at )+'</td>';
                rows += '   <td>';
                rows += '       <a class="btn btn-success js-view-detail" data-from="'+( v.from_company_name )+'" data-to="'+( v.to_company_name )+'"  data-id="'+( v.token )+'">View Report</a>';
                rows += '   </td>';
                rows += '</tr>';
            });

            //
            load_pagination(
                resp.Limit, 
                resp.ListSize,
                $('.js-ip-pagination'),
                'fetchRecords'
            );

            $('#js-data-area').html(rows);
            loader('hide');
        }

        //
        function detailView(e){
            e.preventDefault();
            loader('show');
            fetchDetail($(this));
        }

        var xhr2 = null;
        //
        function fetchDetail(e){
            if(xhr2 !== null) return;
            xhr2 = $.post(baseURL+'handler', {
                action: 'fetch_detail',
                token: e.data('id')
            }, function(resp) {
                xhr2 = null;
                loadView(resp, e);
            });
        }

        //
        function loadView(resp, e){
            //
            $('#js-main-page').fadeOut(0);
            $('#js-detail-page').fadeIn(150);
            //
            $('#js-detail-from').text(e.data('from'));
            $('#js-detail-to').text(e.data('to'));

            var rows = '',
            totalCopiedApplicants = 0,
            totalFailedApplicants = 0,
            totalExistedApplicants = 0;
            $.each(resp.Records, function(i, v){
                totalCopiedApplicants += parseInt(v.copied_applicants);
                totalFailedApplicants += parseInt(v.failed_applicants);
                totalExistedApplicants += parseInt(v.existed_applicants);
                rows += '<tr>';
                rows += '   <td>'+( v.job_title )+'</td>';
                rows += '   <td class="text-success">'+( v.copied_applicants )+'</td>';
                rows += '   <td class="text-warning">'+( v.existed_applicants )+'</td>';
                rows += '   <td class="text-danger">'+( v.failed_applicants )+'</td>';
                rows += '</tr>';
            });

            $('#js-detail-area').html(rows);
            $('#js-detail-area').append('<tr><th></th><th>'+( totalCopiedApplicants )+'</th><th>'+( totalExistedApplicants )+'</th><th>'+( totalFailedApplicants )+'</th></tr>');
            loader('hide');
        }


        //
        function resetView(){
            loader('show');
            $('#js-detail-page').fadeOut(0);
            $('#js-main-page').fadeIn(150);
            $('#js-detail-area').html('');
            loader('hide');
        }

        //
        function generate_search_url(showURL) {
            //
            var startDate = $('#js-start-date-input').val(),
            endDate = $('#js-end-date-input').val();
            //
            startDate = startDate != '' && startDate != null && startDate != undefined && startDate != 0 ? encodeURIComponent(startDate) : 'all';
            endDate = endDate != '' && endDate != null && endDate != undefined && endDate != 0 ? encodeURIComponent(endDate) : 'all';
            return (showURL === undefined ? '' : baseURL)+startDate + '/' + endDate + '/';
        }

        //
        window.onpopstate = function(e) {
            if(e.state == null) return;
            var tmp = e.state.url.split('/');
            from_pop = true;
            if(tmp.length > 0){
                $('.js-start-date').val(""+tmp[0]+"");
                $('.js-end-date').val(""+tmp[1]+"");
                $('.js-apply-filter-btn').trigger('click');
            }
        }

        //
        function resetFilter(){
            window.location = baseURL;
        }

        function loader(do_show){
            if(do_show === undefined || do_show === true || do_show.toLowerCase() === 'show') $('.js-loader').show();
            else $('.js-loader').fadeOut(500);
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
        function load_pagination(limit, list_size, target_ref, page_type){
            //
            var obj = pOBJ[page_type];
            // parsing to int           
            limit = parseInt(limit);
            obj['page'] = parseInt(obj['page']);
            // get paginate array
            var page_array = paginate(obj['totalRecords'], obj['page'], limit, list_size);
            // append the target ul
            // to top and bottom of table
            target_ref.html('<ul class="pagination cs-pagination js-pagination"></ul>');
            // set rows append table
            var target = target_ref.find('.js-pagination');
            // get total items number
            var total_records = page_array.total_pages;
            // load pagination only there
            // are more than one page
            if(obj['totalRecords'] >= limit) {
                // generate li for
                // pagination
                var rows = '';
                // move to one step back
                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == 1 ? '' : 'js-pagination-first')+'">First</a></li>';
                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == 1 ? '' : 'js-pagination-prev')+'">&laquo;</a></li>';
                // generate 5 li
                $.each(page_array.pages, function(index, val) {
                    rows += '<li '+(val == obj['page'] ?  'class="active"' : '')+'><a href="javascript:void(0)" data-page-type="'+(page_type)+'" data-page="'+(val)+'" class="'+(obj['page'] != val ? 'js-pagination-shift' : '')+'">'+(val)+'</a></li>';
                });
                // move to one step forward
                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == page_array.total_pages ? '' : 'js-pagination-next')+'">&raquo;</a></li>';
                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == page_array.total_pages ? '' : 'js-pagination-last')+'">Last</a></li>';
                // append to ul
                target.html(rows);
            }
            // remove showing
            target.find('.js-show-record').remove();
            // append showing of records
            target.before('<span class="pull-left js-show-record" style="margin-top: 27px; padding-right: 10px;">Showing '+(page_array.start_index + 1)+' - '+(page_array.end_index != -1 ? (page_array.end_index + 1) : 1)+' of '+(obj['totalRecords'])+'</span>');
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
        function pagination_event(){
            //
            var i = $(this).data('page-type');
            // When next is press
            if($(this).hasClass('js-pagination-next') === true){
                pOBJ[i]['page'] = pOBJ[i]['page'] + 1;
                pOBJ[i]['cb']($(this));
            } else if($(this).hasClass('js-pagination-prev') === true){
                pOBJ[i]['page'] = pOBJ[i]['page'] - 1;
                pOBJ[i]['cb']($(this));
            } else if($(this).hasClass('js-pagination-first') === true){
                pOBJ[i]['page'] = 1;
                pOBJ[i]['cb']($(this));
            } else if($(this).hasClass('js-pagination-last') === true){
                pOBJ[i]['page'] = pOBJ[i]['totalPages'];
                pOBJ[i]['cb']($(this));
            } else if($(this).hasClass('js-pagination-shift') === true){
                pOBJ[i]['page'] = parseInt($(this).data('page'));
                pOBJ[i]['cb']($(this));
            }
        }
    });
</script>


<style>
    /*Table loader*/
    .cs-table-loader{ position: absolute; top: 0; bottom: 0; right: 0; left: 0; background: rgba(255,255,255,.5); }
    .cs-table-loader i{ font-size: 30px; text-align: center; display: block; margin-top: 40px; }
    /*Pagination*/
    .cs-pagination{ float: right; }
    .cs-pagination li a{ background-color: #81b431; color: #ffffff; }
    /**/
    .cs-error{ font-weight: bolder; color: #cc0000; }
    /**/
    .cs-loader-file{ z-index: 1061 !important; display: block !important; height: 1353px !important; }
    .cs-loader-box{ position: fixed; top: 100px; bottom: 0; right: 0; left: 0; max-width: 300px; margin: auto; z-index: 1539; }
    .cs-loader-box i{ font-size: 14em; color: #81b431; }
    .cs-loader-box div.cs-loader-text{ display: block; padding: 10px; color: #000; background-color: #fff; border-radius: 5px; text-align: center; font-weight: 600; margin-top: 35px; }
</style>