<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="emp-info-strip">
    <div class="container">
        <div class="emp-info-box">
            <div class="figure">
                <?php if (isset($executive_user['profile_picture']) && !empty($executive_user['profile_picture'])) { ?>
                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $executive_user['profile_picture']; ?>">
                <?php   } else { ?>
                    <span><?php echo substr($executive_user['first_name'], 0, 1) . substr($executive_user['last_name'], 0, 1); ?></span>
                <?php   } ?>
            </div>
            <div class="text text-white">
                <h3><?php echo $executive_user['first_name'] . ' ' . $executive_user['last_name']; ?>
                    <br><span>Executive Admin</span>
                </h3>
                <ul class="contact-info">
                    <?php if ($executive_user['direct_business_number']) { ?>
                        <li><i class="fa fa-phone"></i> <?php echo $executive_user['direct_business_number']; ?></li>
                    <?php   }

                    if ($executive_user['cell_number']) { ?>
                        <li><i class="fa fa-phone"></i> <?php echo $executive_user['cell_number']; ?></li>
                    <?php   } ?>
                    <li><i class="fa fa-envelope"></i>&nbsp;<?php echo $executive_user['email']; ?></li>
                </ul>
            </div>
            <div class="btn-link-wrp">
                <a href="<?php echo base_url('dashboard/my_profile'); ?>"><i class="fa fa-pencil"></i> my profile</a> <br>
                <?php if ($executive_user['complynet_status']) {
                    echo '<a href="javascript:;" id="admin-complynet-btn" class="btn btn-success btn-block" style="font-size: 18px; color:#fff; margin-top: 10px" > Complynet Dashboard</a>';
                    echo '<a href="javascript:;" id="simple-admin-btn" class="btn btn-success btn-block" style="font-size: 18px;color:#fff; margin-top: 10px; display: none;"> Dashboard</a>';
                } ?>

            </div>
        </div>
    </div>
</div>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $page_title; ?></h1>
                </div>
                <div id="simple-dashboard">
                    <div class="flash-message">
                        <?php $this->load->view('flashmessage/flash_message'); ?>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-8 col-md-6 col-xs-12 col-sm-6">
                            </div>
                            <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label class="hidden-xs">&nbsp;</label>
                                    <?php if ($applysignature == 1) { ?> <a id="apply_signature_btn" href="javascript:;" class="btn btn-success btn-block">Apply E-Signature</a> <?php } ?>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label class="hidden-xs">&nbsp;</label>
                                    <a id="manage_signature_btn" href="e_signature" class="btn btn-success btn-block">Manage E-Signature</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="hr-box">
                        <div class="hr-box-header bg-header-green">
                            <h1 class="hr-registered pull-left"><span class="text-success">Manage Companies</span></h1>
                        </div>
                        <?php $keyword = $this->uri->segment(3); ?>
                        <div class="hr-innerpadding">
                            <div class="hr-search-main display-block">
                                <div class="row">
                                    <div class="col-lg-8 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Company Name:</label>
                                            <input type="text" name="keyword" id="keyword" value="<?php echo urldecode($keyword); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <div class="form-group">
                                            <label class="hidden-xs">&nbsp;</label>
                                            <a id="search_btn" href="javascript:;" class="btn btn-success btn-block">Search Company</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <div class="form-group">
                                            <label class="hidden-xs">&nbsp;</label>
                                            <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-success btn-block">Clear Search</a>
                                        </div>
                                    </div>
                                </div>
                                <?php if (true) { ?>
                                    <div class="filter-form-wrp">
                                        <span>Search Applicants/Employee(s):</span>
                                        <div class="tracking-filter">
                                            <form action="javascript:void(0)" id="js-applicant-search-form">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <input type="text" placeholder="Search Applicant by Name or Email or Phone number" name="keyword" class="invoice-fields search-job" value="">
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <input type="submit" value="Search" class="form-btn">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                            </div>
                        <?php } ?>
                        <div id="js-company-block">
                            <div class="table-responsive full-width">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-4">Company Name</th>
                                            <th class="col-lg-3">Company Website</th>
                                            <th class="col-lg-5 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($executive_user_companies)) { ?>
                                            <tr>
                                                <td class="text-center" colspan="4">No Company is linked with your account.</td>
                                            </tr>
                                        <?php   } else { ?>
                                            <?php foreach ($executive_user_companies as $user_company) { ?>
                                                <tr>
                                                    <td><a><?php echo $user_company['company_name']; ?> <?php if ($user_company['company_status'] == 0) { ?>
                                                                <label class="label label-danger" title="The store is closed." placement="top">
                                                                    Closed
                                                                </label> <?php } ?></a></td>
                                                    <td><?php echo $user_company['company_website']; ?></td>
                                                    <td class="text-center">
                                                        <div class="btn-msg-actions display-block">
                                                            <a class="btn btn-success btn-sm" style="background-color:#0000ff !important;" href="javascript:;" id="<?php echo $user_company['company_sid']; ?>" onclick="return companyLogin(this.id,<?php echo $user_company['logged_in_sid']; ?>);">Login</a>
                                                            <a class="btn btn-success btn-sm" href="<?php echo base_url() . 'dashboard/manage_admin_companies/' . $user_company['company_sid']; ?>">Manage</a>
                                                            <a class="btn btn-success btn-sm" href="<?php echo base_url() . 'dashboard/reports/' . $user_company['company_sid']; ?>">Reports</a>
                                                            <a class="btn btn-success btn-sm" href="<?php echo base_url('private_messages') . '/' . $user_company['company_sid']; ?>">Messages</a>
                                                            <?php if ($user_company['incidentCount'] > 0) { ?>
                                                                <span class="btn btn-sm" data-toggle="tooltip" data-placement="top" title="<?php echo $user_company['incidentCount']; ?> Incident Pending">
                                                                    <a class="" style="" href="javascript:;" id="<?php echo $user_company['company_sid']; ?>" onclick="return companyLogin(this.id,<?php echo $user_company['logged_in_sid']; ?>,1);">
                                                                        <figure><i class="fa fa-exclamation-triangle start_animation " aria-hidden="true"></i></figure>
                                                                    </a>

                                                                </span>
                                                            <?php } ?>

                                                            <?php if ($user_company['message_total'] > 0) { ?>
                                                                <img class="icon-msg-new" src="<?= base_url() ?>assets/images/new_msg.gif">
                                                            <?php } ?>

                                                            <?php 
                                                                $adminPlusData = get_executive_administrator_admin_plus_status($user_company['executive_admin_sid'], $user_company['company_sid']);
                                                                //
                                                                $execAdminAccessLevelPlus = FALSE;
                                                                if (!empty($adminPlusData)) {
                                                                    $execAdminAccessLevelPlus =  $adminPlusData['access_level_plus'] ? TRUE : FALSE;
                                                                }
                                                            ?>

                                                            <?php if ($execAdminAccessLevelPlus && checkIfAppIsEnabled(MODULE_LMS,$user_company['company_sid'])) {?>
                                                                <a class="btn btn-success btn-sm" href="<?php echo base_url() . 'lms_company_report/' . $user_company['company_sid']; ?>">LMS Company Report</a>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="js-applicant-block" style="display: none;">
                            <div class="table-responsive full-width">
                                <div class="js-ip-pagination"></div>
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Contact Name</th>
                                            <th>Job Applied</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="js-ip-pagination"></div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div id="comply-dashboard" style="display: none;">
                    <?php $this->load->view('complynet/complynet_tab_view'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>




<?php if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $parent_base_url = 'http://localhost/ahr';
} else {
    $parent_base_url = (isset($_SERVER['HTTPS']) ? STORE_PROTOCOL_SSL : STORE_PROTOCOL) . $_SERVER['HTTP_HOST'];
} ?>
<script>
    function companyLogin(company_sid, logged_in_sid, incident = 0) {
        url_to = "<?= base_url() ?>dashboard/company_login";
        $.post(url_to, {
                action: "login",
                company_sid: company_sid,
                logged_in_sid: logged_in_sid,
                incident: incident
            })
            .done(function(data) {
                dataCheck = JSON.parse(data)
                if (dataCheck.logedin == 1 && dataCheck.incident == 1) {
                    window.open("<?= $parent_base_url ?>/incident_reporting_system/assigned_incidents", '_blank');
                } else if (dataCheck.logedin == 1 && dataCheck.incident == 0) {
                    window.open("<?= $parent_base_url ?>/dashboard", '_blank');
                } else {
                    alert('Account Is De-Activated');
                }
            });
    }

    $(document).ready(function() {
        $('#keyword').on('keyup', update_url);
        $('#keyword').on('blur', update_url);

        $('#admin-complynet-btn').click(function() {
            $('#simple-dashboard').hide();
            $('#comply-dashboard').show();
            $('#admin-complynet-btn').hide();
            $('#simple-admin-btn').show();
        });
        $('#simple-admin-btn').click(function() {
            $('#comply-dashboard').hide();
            $('#simple-dashboard').show();
            $('#simple-admin-btn').hide();
            $('#admin-complynet-btn').show();
        });
    });

    function update_url() {
        var url = '<?php echo base_url('dashboard/search/'); ?>';
        var keyword = $('#keyword').val();
        keyword = keyword == '' ? '' : keyword;
        url = url + encodeURIComponent(keyword);
        console.log(url);
        $('#search_btn').attr('href', url);
    }


    // 
    $(function() {
        // Set AJAX reference holder
        var XHR = null,
            // Set pagination object
            pOBJ = {
                'fetchSearchedApplicantEmployees': {
                    page: 1,
                    totalPages: 0,
                    totalRecords: 0,
                    query: '',
                    cb: fetchSearchedApplicantEmployees
                }
            };
        // Capture form submission
        $('#js-applicant-search-form').submit(function(e) {
            // Stop default behaviour of form
            e.preventDefault();
            // Get the query
            var query = $(this).find('input.search-job').val().trim();
            // Reset view when query is empty
            $('#js-applicant-block tbody').html('');
            if (query == '') {
                $('#js-applicant-block').hide();
                $('#js-company-block').show();
                return;
            }
            pOBJ['fetchSearchedApplicantEmployees']['query'] = encodeURI(query.replace(/\s/g, '--').toLowerCase());
            pOBJ['fetchSearchedApplicantEmployees']['page'] = 1;
            pOBJ['fetchSearchedApplicantEmployees']['totalPages'] = 0;
            pOBJ['fetchSearchedApplicantEmployees']['totalRecords'] = 0;
            //
            fetchSearchedApplicantEmployees();
        });

        function fetchSearchedApplicantEmployees() {
            if (pOBJ['fetchSearchedApplicantEmployees']['page'] == 1)
                $('#js-applicant-block tbody').html('<tr class="js-loader"><td colspan="' + ($('#js-applicant-block').find('th').length) + '"><i class="fa fa-spinner fa-spin"></i></td></tr>');
            //
            $('#js-company-block').hide();
            $('#js-applicant-block').show();
            // Cancel previous AJAX on new AJAX request
            if (XHR !== null) XHR.abort();
            // Get the data from server
            XHR = $.get("<?= base_url(); ?>search/" + (pOBJ['fetchSearchedApplicantEmployees']['query']) + "/" + (pOBJ['fetchSearchedApplicantEmployees']['page']) + "", function(resp) {
                XHR = null;
                if (resp.Status === false && pOBJ['fetchSearchedApplicantEmployees']['page'] == 1) {
                    $('#js-applicant-block tbody').html('<tr><td colspan="' + ($('#js-applicant-block').find('th').length) + '"><p class="text-center">' + (resp.Response) + '</p></td></tr>');
                    return;
                }
                setTable(resp);
            });
        }

        //
        function setTable(resp) {
            if (resp.Data === undefined) return;
            var rows = '';
            $.each(resp.Data, function(i, v) {
                rows += '<tr>';
                rows += '   <td>' + (v.company_name == null ? 'N/A' : v.company_name) + '</td>';
                rows += '   <td>';
                rows += '       <p>' + (v.user_name) + '</p>';
                rows += '       <p><strong>Email: </strong>' + (v.user_email) + '</p>';
                rows += '       <p><strong>Primary Number: </strong>' + (v.PhoneNumber) + '</p>';

                if (v.user_registration_date != null)
                    rows += '       <p><strong>Joining Date: </strong>' + (v.user_registration_date) + '</p>';
                rows += '   </td>';
                if (v.user_type == 'employee')
                    rows += '   <td>' + (v.last_job_title == '' || v.last_job_title == null ? 'N/A' : v.last_job_title) + '</td>';
                else {
                    var jobs = '';
                    $.each(v.jobs, function(i1, v1) {
                        if (v1['Title'] != null || v1['desired_job_title'] != null) {
                            jobs += '<p>' + (v1['Title'] != null ? v1['Title'] : (v1['desired_job_title'] == null ? 'N/A' : v1['desired_job_title'])) + '</p>';
                        } else {
                            v.job_count--;
                        }
                    })
                    rows += '   <td>' + (v.job_count == 0 ? 'N/A' : 'Applied to <strong>' + (v.job_count) + '</strong> job' + (v.job_count > 1 ? 's' : '') + '.') + ' <br /><a href="javascript:void()" class="js-jobs-event" data-event="show">Show Jobs</a>';
                    rows += '<div class="js-jobs" style="display: none;"><strong>Jobs:</strong><br />' + (jobs) + '</div>';
                    rows += '   </td>';
                }
                rows += '   <td>' + (v.user_type.toUpperCase()) + '</td>';
                rows += '   <td>';
                //    rows += '       <div class="checkbox-inline"><label><input type="checkbox" disabled="true" ' + (v.is_archived == 1 ? 'checked="true"' : "") + '/>Archived</label></div>';
                if (v.user_type == 'employee') {
                    // rows += '       <div class="checkbox-inline"><label><input type="checkbox" disabled="true" ' + (v.is_active == 1 ? 'checked="true"' : "") + '/>Active</label></div>';
                    //  rows += '       <div class="checkbox-inline"><label><input type="checkbox" disabled="true" ' + (v.is_active == 0 ? 'checked="true"' : "") + '/>In-Active</label></div>';
                    //  rows += '       <div class="checkbox-inline"><label><input type="checkbox" disabled="true" ' + (v.is_terminated == 1 ? 'checked="true"' : "") + '/>Terminated</label></div>';
                    rows += '       <div class="checkbox-inline"><label>' + v.newstatus + '</label></div>';

                }

                if (v.user_type == 'applicant') {
                    rows += '       <div class="checkbox-inline"><label>' + v.is_archived == 1 ? 'Archived' : '' + '</label></div>';
                }

                rows += '   </td>';
                rows += '</tr>';
            });

            //
            if (pOBJ['fetchSearchedApplicantEmployees']['page'] == 1) {
                pOBJ['fetchSearchedApplicantEmployees']['totalPages'] = resp.TotalPages;
                pOBJ['fetchSearchedApplicantEmployees']['totalRecords'] = resp.TotalRecords;
                $('#js-applicant-block tbody').html(rows);
            } else $('#js-applicant-block tbody').append(rows);

            pOBJ['fetchSearchedApplicantEmployees']['page']++;
            if (pOBJ['fetchSearchedApplicantEmployees']['totalPages'] >= pOBJ['fetchSearchedApplicantEmployees']['page'])
                fetchSearchedApplicantEmployees();
            else {
                $('.js-popovers').popover({
                    animation: true,
                    html: true,
                    placement: 'top'
                })
            }

        }

        $(document).on('click', '.js-jobs-event', function(e) {
            e.preventDefault();
            if ($(this).data('event') == 'show') {
                $(this).data('event', 'hide');
                $(this).text('Hide Jobs');
                $(this).parent().find('.js-jobs').show();
                return;
            }
            $(this).data('event', 'show');
            $(this).text('Show Jobs');
            $(this).parent().find('.js-jobs').hide();
        });

        // Pagination Script
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
            target_ref.html('<ul class="pagination cs-pagination js-pagination"></ul>');
            // set rows append table
            var target = target_ref.find('.js-pagination');
            // get total items number
            var total_records = page_array.total_pages;
            // load pagination only there
            // are more than one page
            if (obj['totalRecords'] >= limit) {
                // generate li for
                // pagination
                var rows = '';
                // move to one step back
                rows += '<li><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == 1 ? '' : 'js-pagination-first') + '">First</a></li>';
                rows += '<li><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == 1 ? '' : 'js-pagination-prev') + '">&laquo;</a></li>';
                // generate 5 li
                $.each(page_array.pages, function(index, val) {
                    rows += '<li ' + (val == obj['page'] ? 'class="active"' : '') + '><a href="javascript:void(0)" data-page-type="' + (page_type) + '" data-page="' + (val) + '" class="' + (obj['page'] != val ? 'js-pagination-shift' : '') + '">' + (val) + '</a></li>';
                });
                // move to one step forward
                rows += '<li><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == page_array.total_pages ? '' : 'js-pagination-next') + '">&raquo;</a></li>';
                rows += '<li><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['page'] == page_array.total_pages ? '' : 'js-pagination-last') + '">Last</a></li>';
                // append to ul
                target.html(rows);
            }
            // remove showing
            target.find('.js-show-record').remove();
            // append showing of records
            target.before('<span class="pull-left js-show-record" style="margin-top: 27px; padding-right: 10px;">Showing ' + (page_array.start_index + 1) + ' - ' + (page_array.end_index != -1 ? (page_array.end_index + 1) : 1) + ' of ' + (obj['totalRecords']) + '</span>');
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
    })
</script>

<style>
    figure i {
        font-size: 30px !important;
        color: #81b431;
    }

    .start_animation {
        animation-name: icon_alert;
        animation-duration: 0.8s;
        animation-iteration-count: infinite;
    }

    @keyframes icon_alert {
        75% {
            color: #dc3545;
        }
    }


    .filter-form-wrp {
        float: left;
        width: 100%;
        border: 1px solid #b8b8b8;
        border-radius: 5px;
        margin: 10px 0;
    }

    .filter-form-wrp:last-of-type {
        margin-bottom: 0;
    }

    .filter-form-wrp span:not(.highlight) {
        float: left;
        width: 20%;
        height: 44px;
        padding: 12px 5px;
        border-right: 1px solid #b8b8b8;
        text-align: center;
        color: #000;
        font-weight: 600;
        border-radius: 5px 0 0 5px;
        font-style: italic;
        background-color: #f0f0f0;
        position: relative;
    }

    .filter-form-wrp span:not(.highlight):after {
        content: '';
        position: absolute;
        right: -5px;
        top: 50%;
        width: 8px;
        height: 8px;
        margin-top: -4px;
        background-color: #f0f0f0;
        border-right: 1px solid #b8b8b8;
        border-top: 1px solid #b8b8b8;
        -ms-transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .tracking-filter {
        float: left;
        width: 80%;
        padding: 2px 10px;
        background-color: #fff;
        border-radius: 0 5px 5px 0;
    }

    .tracking-filter .hr-select-dropdown:after {
        top: 0;
    }

    .form-btn {
        float: right;
        width: 100%;
        background-color: #81b431;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        border: none;
        padding: 9px 5px;
        border-radius: 5px;
        text-align: center;
    }

    @media (max-width: 1024px) and (min-width: 768px) {
        .tracking-filter {
            width: 100%;
            padding: 0 10px 5px 10px;
            background-color: transparent;
        }
    }

    #js-applicant-block table {
        position: relative;
    }

    .js-loader {
        background-color: rgba(255, 255, 255, .8);
        text-align: center;
    }

    .js-loader i {
        padding: 100px 0;
        font-size: 30px;
    }
</style>


<script>
    $('#apply_signature_btn').on('click', function() {

        var esignature_url = '<?= base_url('e_signature/apply_e_signature') ?>';
        var form_data = new FormData();
        form_data.append('executive_sid', '<?php echo $exadminId ?>');

        $.ajax({
            url: esignature_url,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            beforeSend: function() {
                $('#document_loader').show();
            },
            success: function(resp) {
                $('#document_loader').hide();
                alertify.alert('SUCCESS!', 'Applied Successfully', function() {
                    window.location.reload;
                });
            },
            error: function() {}
        });

    });
</script>