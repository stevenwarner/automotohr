<?php 
//
$active_job_list = $inactive_job_list = '';
foreach ($jobs as $job) {
    $class   = $job['active'] == 1 ? 'ats_search_filter_active' :  'ats_search_filter_inactive';
    $text  = $job['title'];
    $text .= ' - ( '.(reset_datetime(array('datetime' => $job['activation_date'], '_this' => $this, 'format' => 'm-d-y h:i a' ))).' )';
    // $text .= ' - ( '.(DateTime::createFromFormat('Y-m-d H:i:s', $job['activation_date'])->format('m-d-y h:i a')).' )';

    if($job['active']){
        $active_job_list .= '<option class="ats_search_filter_active" value="'.($job['sid']).'">'.($text).'</option>';
        continue;
    }
    $inactive_job_list .= '<option class="ats_search_filter_inactive" value="'.($job['sid']).'">'.($text).'</option>';
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="well">
                                <div class="help-block">
                                    <h3><strong>Download Applicant Resumes: </strong></h3>
                                    <h4>Please select the Job Title, desired month, year and press "Fetch Resumes" button. It will list all the applicants who applied for the job as well as attached their resumes.</h4>
                                    <h4>You can now easily download resumes for all of the applicants that have applied to your jobs in a ZIP format.</h4>
                                    <h4>To download the resumes please press the "Download" button and you will get two options</h4>
                                    <h4>1- Download Selected; it will download the resumes for the selected applicants. </h4>
                                    <h4>2- Download All; it will download the resumes of all the applicant. </h4>
                                    <h4><strong>Depending on the number of resumes you are requesting this could take a bit of time to download, <span class="text-success">Please be patient.</span></strong></h4>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <?php echo form_open('', array('id' => 'downloadzip', 'name' => 'js-search-form')); ?>
                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_zip">

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 auto-height">
                                            <label>Select Job: </label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields"  id="job_sid" name="job_sid">
                                                    <!--<option value="all">All</option>-->
                                                    <?=$active_job_list;?>
                                                    <?=$inactive_job_list;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-5 col-lg-5">
                                            <br />
                                            <label>Month: </label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields"  id="month" name="month">
                                                    <?php foreach ($months as $key => $month) { ?>
                                                        <option value="<?php echo $key; ?>" <?=($key == date('m') ? 'selected="selected"': '');?>><?php echo $month; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-5 col-lg-5">
                                            <br />
                                            <label>Year: </label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields"  id="year" name="year">
                                                    <?php for ($iCount = 2015; $iCount <= intval(date('Y')); $iCount++) { ?>
                                                        <option value="<?php echo $iCount; ?>" <?=($iCount == date('Y') ? 'selected="selected"': '');?>><?php echo $iCount; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
                                            <br />
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-success btn-block" id="js-fetch-records">Fetch Resumes</button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>

                            <div id="loader" class="hr-box" style="display: none;">
                                <div class="hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="loader" >
                                                <h3 class="text-center">
                                                    <strong>
                                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i> Please wait while the resumes are processed.....
                                                    </strong>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="response_box" style="display:none;" class="hr-box">
                                <div class="hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="help-block">
                                                <h3 class="text-center">
                                                    <strong id="response_message"></strong>
                                                </h3>
                                                <!-- Added on  -->
                                                <!-- wrapper for applocant table and download button  -->
                                                <!-- 19-03-2019  -->
                                                <div class="js-applicant-wrap"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <?php echo form_open(base_url('bulk_resume_download/download'), array('id' => 'js-download-zip-form', 'method' => 'post')); ?>
                                            <?php echo form_close(); ?>
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


<!-- Added on  -->
<!-- 19-03-2019  -->
<style>
    input{ width: 25px; height: 25px; }
</style>

<script>
    $(function () {
        //
        var page = 1, total_records;
        // select/unselect all
        // checkboxes
        $(document).on('click', 'input.js_select_all', function() {
            $('input.js_select').prop('checked', !$(this).prop('checked') ? '' : 'checked');
        });

        // capture click on every input
        // checkbox
        $(document).on('click', 'input.js_select', function() {
            // default active count
            var active_count = 0;
            // get active checboxs
            $.each($('input.js_select:checked'), function() {
                // skip select_all input
                if(!$(this).hasClass('js_select_all'))
                active_count++;
            });
            // get all inputs
            var count = $('input.js_select').length - 1;
            // change status
            $('input.js_select_all').prop('checked', active_count == count   ? 'checked' : '');
        });

        // download selected
        // handler
        $(document).on('click', '.js-download-selected', function(event) {
            event.preventDefault();
            var list = [];
            $.each($('input.js_select:checked'), function() {
                if($(this).val() == '*') return true;
                list.push($(this).val());
            });
            if(list.length == 0){ alert('Please, select atleast one applicant for download.'); return; }
            // send ajax request to download selected
            download_process('selected', list);
        });

        // download all
        // handler
        $(document).on('click', '.js-download-all', function(event) {
            event.preventDefault();
            // send ajax request to download all
            download_process('all', ['*']);
        });

        // download single
        // handler
        $(document).on('click', '.js-download-single', function(event) {
            event.preventDefault();
            // send ajax request to download single
            download_process('single', [$(this).parent().parent().data('key')]);
        });

        // fetch records binder
        $('button#js-fetch-records').click(function(event) {
            page = 1;
            // append table structure
            $('.js-applicant-wrap').html(get_download_block()+get_applicant_block());
            $('.table-outer').after(get_download_block());
            // fetch records
            get_records();
        });

        // get previous page
        $(document).on('click', '.js-pagination-prev', function(event) {
            event.preventDefault();
            page--;
            get_records();
        });

        // move to first page
        $(document).on('click', '.js-pagination-first', function(event) {
            event.preventDefault();
            page = 1;
            get_records();
        });

        // move to last page
        $(document).on('click', '.js-pagination-last', function(event) {
            event.preventDefault();
            page = total_records;
            get_records();
        });
        
        // get next page
        $(document).on('click', '.js-pagination-next', function(event) {
            event.preventDefault();
            page++;
            get_records();
        });
       
        // get page
        $(document).on('click', '.js-pagination-shift', function(event) {
            event.preventDefault();
            page = $(this).data('page');
            get_records();
        });

        // request handler
        // type; selected, all
        // list; sids
        function download_process(type, list){
            // move to message div
            $('html,body').animate({scrollTop: $('#downloadzip').offset().top},'slow');
            // ajax Object
            var my_request = null;
            type = type === undefined ? 'selected' : type;
            overlay(true);
            // set obj
            var OBJ = Object.assign({}, get_form(), {page:page,dtype:type,list:list});
            // send ajax request
            my_request = $.ajax({
                url: '<?php echo base_url('bulk_resume_download/generate_resumes'); ?>',
                type: 'POST',
                data: OBJ
            });
            // on done event
            my_request.done(function (response) {
                if(response.Status === false || response.Zip_generated === false){
                    show_error(response.Response); 
                    overlay(false);
                    return;
                }
                download_zip(response);
            });
            // on fail
            my_request.fail(function () {
                show_error('Oops! Something went wrong. Please, try again in a a few moments.'); 
                overlay(false);
            });
        }

        // generate html for download block
        function get_download_block(is){
            return (
                '<div class="col-sm-12">'+
                    '<div class="row">'+
                        '<div class="col-sm-6 '+( is === undefined ? 'js-top-table-bar' : '')+' pl0"></div>'+
                        '<div class="col-sm-6">'+
                            '<!-- download button -->'+
                            '<div class="btn-group pull-right" style="padding-bottom: 20px; margin-top: 27px">'+
                                '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Download <span class="caret"></span>'+
                                '</button>'+
                                '<ul class="dropdown-menu" title="Select an option (Download Selected, Download All) to download resumes. ">'+
                                    '<li><a href="#" class="js-download-selected" title="It will download the resumes for the selected applicants.">Download Selected</a></li>'+
                                    '<li><a href="#" class="js-download-all" title="It will download the resumes of all the applicant">Download All (<span>0</span>)</a></li>'+
                                '</ul>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );
        }

        // generate html for applicants
        function get_applicant_block(){
            var rows = '';
            rows += '<!-- table -->';
            rows += '<div class="table-responsive table-outer">';
            rows += '    <div class="table-wrp data-table">';
            rows += '        <table class="table table-striped table-bordered">';
            rows += '            <thead>';
            rows += '                <tr>';
            rows += '                    <th width="30"><input type="checkbox" class="js_select js_select_all" name="txt_select[]" value="*"></th>'; 
            rows += '                    <th>Applicant Name</th>';
            rows += '                    <th>Resume</th>';
            rows += '               </tr>';
            rows += '            </thead>';
            rows += '            <tbody>';
            rows += '            </tbody>';
            rows += '        </table>';
            rows += '    </div>';
            rows += '</div>';
            rows += '<div class="js-download-btn-block"></div>';

            return rows;
        }

        // get applicants records
        function get_records(){
            $('input.js_select_all').prop('checked', '');
            overlay(true);
            OBJ = Object.assign({}, get_form(), {page:page});

            my_request = $.ajax({
                url: '<?php echo base_url('bulk_resume_download/fetch_applicants'); ?>',
                type: 'POST',
                data: OBJ
            });

            my_request.done(function (resp) {
                // convert json to object
                resp = $.parseJSON(resp);
                // check if any error occurs
                if(resp.Status === false){
                    show_error(resp.Response); overlay(false); return;
                }else{
                    load_records(resp.Response, resp.Limit, resp.Total, resp.ListSize);
                }
            });
        }

        // get form data
        function get_form(){
            // get form reference
            var form_REF = $('form[name="js-search-form"]');
            return { 
                job_id : form_REF.find('select[name="job_sid"]').val(),
                month  : form_REF.find('select[name="month"]').val(),
                year   : form_REF.find('select[name="year"]').val()
            };
        }

        // show errors
        function show_error(error_msg){
            overlay(false);
            $('#response_message').html(error_msg).show();
            $('.js-applicant-wrap').hide();

        }

        // load messages
        function load_records(records, limit, total_records, list_size){
            //
            var rows = '';
            //
            $.each(records, function(i,v) {
                rows += '<tr data-key="'+(v.sid)+'">';
                rows += '   <td><input type="checkbox" class="js_select" name="txt_select[]" value="'+(v.sid)+'" /></td>';
                rows += '   <td>'+(v.fullname)+'</td>';
                rows += '   <td><button class="btn btn-success js-download-single" title="It will download the resume of this applicant">Download Resume</button></td>';
                rows += '</tr>';
            });
            //
            $('table > tbody').html(rows);
            //
            $('.js-applicant-wrap').show();
            //
            $('#response_message').html('').hide();

            $('.js-download-all > span').text(total_records);

            load_pagination(limit, total_records, page, records.length, list_size);
            overlay(false);
        }

        // pagination
        function load_pagination(limit, total, current_page, record_length, list_size){
            // parsing to int           
            limit = parseInt(limit);
            total = parseInt(total);
            current_page = parseInt(current_page);
            // get pageinate array
            var page_array = paginate(total, current_page, limit, list_size);
            // append the target ul
            // to top and bottom of table
            $('.js-top-table-bar').html('<ul class="pagination js-pagination pull-left"></ul>');
            // set rows append table
            var target = $('.js-pagination');
            // get total items number
            total_records = page_array.total_pages;
            // load pagination only there
            // are more than one page
            if(total >= limit) {
                // generate li for
                // pagination
                var rows = '';
                // move to one step back
                rows += '<li><a href="javascript:void(0)" class="'+(current_page == 1 ? '' : 'js-pagination-first')+'">First</a></li>';
                rows += '<li><a href="javascript:void(0)" class="'+(current_page == 1 ? '' : 'js-pagination-prev')+'">&laquo;</a></li>';
                // generate 5 li
                $.each(page_array.pages, function(index, val) {
                    rows += '<li '+(val == current_page ?  'class="active"' : '')+'><a href="javascript:void(0)" data-page="'+(val)+'" class="'+(current_page != val ? 'js-pagination-shift' : '')+'">'+(val)+'</a></li>';
                });
                // move to one step forward
                rows += '<li><a href="javascript:void(0)" class="'+(current_page == page_array.total_pages ? '' : 'js-pagination-next')+'">&raquo;</a></li>';
                rows += '<li><a href="javascript:void(0)" class="'+(current_page == page_array.total_pages ? '' : 'js-pagination-last')+'">Last</a></li>';
                // append to ul
                target.html(rows);
            }
            // remove showing
            $('.js-show-record').remove();
            // append showing of records
            target.before('<span class="pull-left js-show-record" style="margin-top: 27px; padding-right: 10px;">Showing '+(page_array.start_index + 1)+' - '+(page_array.end_index + 1)+' of '+(total)+'</span>');
        }

        // download zip file
        function download_zip(resp){
            // append to form 
            var form_REF = $('form#js-download-zip-form');
            form_REF.append('<input type="hidden" name="txt_file" value="'+(resp.Directory)+'/'+(resp.Archive)+'" />');
            form_REF.append('<input type="hidden" name="txt_company_name" value="'+(resp.Company_name)+'" />');
            form_REF.submit();
            overlay(false);
        }

        // HIDE AND SHOW
        function overlay( show ){
            show = show === undefined ? true : show;
            if(show){
                $('#response_box').show();
                $('#loader').show();
                $('.btn').prop('disabled', true);
                $('.btn').addClass('disabled');
            }else{
                $('#loader').hide();
                $('.btn').prop('disabled', false);
                $('.btn').removeClass('disabled');
            }
        }

        // polyfill OBject.assign
        if (typeof Object.assign != 'function') {
            // Must be writable: true, enumerable: false, configurable: true
            Object.defineProperty(Object, "assign", {
                value: function assign(target, varArgs) { // .length of function is 2
                    'use strict';
                    if (target == null) { // TypeError if undefined or null
                        throw new TypeError('Cannot convert undefined or null to object');
                    }

                    var to = Object(target);

                    for (var index = 1; index < arguments.length; index++) {
                        var nextSource = arguments[index];

                        if (nextSource != null) { // Skip over if undefined or null
                            for (var nextKey in nextSource) {
                                // Avoid bugs when hasOwnProperty is shadowed
                                if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                                    to[nextKey] = nextSource[nextKey];
                                }
                            }
                        }
                    }
                    return to;
                },
                writable: true,
                configurable: true
            });
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
        // move to message div
        $(window).on('load', function(){$('html,body').animate({scrollTop: $('body').offset().top},'fast');});

        $('#job_sid').select2();
    });
</script>

<style>
    .select2-container--default .select2-selection--single{ border: 1px solid #aaaaaa !important;  padding: 3px 5px !important; }
</style>