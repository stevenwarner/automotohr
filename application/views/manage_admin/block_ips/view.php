<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--  -->
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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <div class="add-new-promotions">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <a href="javascript:void(0)" type="button" class="btn btn-success pull-right js-ip-view" style="margin-left: 10px; display: none;">View Blocked IPs</a>
                                                <a href="javascript:void(0)" type="button" class="btn btn-success pull-right js-ip-add">Block New IP</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="row" id="js-ip-view-page">
                                        <div class="col-sm-12 js-ip-pagination"></div>
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <div class="cs-table-loader js-table-loader"><i class="fa fa-spinner fa-spin"></i></div>
                                                <table class="table table-bordered table-hover table-striped" id="js-ip-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-3">Blocked By</th>
                                                            <th class="col-xs-4">Blocked IP</th>
                                                            <th class="col-xs-4">Blocked At</th>
                                                            <th class="col-xs-4">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="js-error-msg">
                                                            <td colspan="3">
                                                                <h4 class="alert alert-info text-center">Please, wait while we are loading IPs.</h4>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 js-ip-pagination"></div>
                                    </div>
                                    <!--  -->
                                    <div class="row" id="js-ip-add-page" style="display: none;">
                                        <div class="col-sm-12">
                                            <br />

                                            <div id="js-ip-address-box">
                                                <!-- ROW -->
                                                <div class="js-ip-address js-ip-address-primary" id="js-ip-row-0">
                                                    <div class="row">
                                                        <div class="col-sm-11">
                                                            <label>IP Address <span class="cs-required">*</span></label>
                                                            <input type="text" class="form-control" maxlength="15" />
                                                            <span class="cs-error"></span>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>&nbsp;</label>
                                                            <button class="btn btn-success form-control" id="js-add-ip-address-row"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    <br />
                                                </div>
                                            </div>

                                            <!--  -->
                                        </div>
                                        <div class="col-sm-12">
                                            <br />
                                            <button class="btn btn-success js-save-ip">Save</button>
                                            <button class="btn btn-default js-cancel-ip-btn">Cancel</button>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="row" id="js-ip-add-confirm-page" style="display: none;">
                                        <div class="col-sm-12">
                                            <br />
                                            <div id="js-ip-address-box">
                                                <table class="table table striped table bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Are you sure you want to block these IPs?</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!--  -->
                                        </div>
                                        <div class="col-sm-12">
                                            <br />
                                            <button class="btn btn-success js-confirm-ip">Confirm</button>
                                            <button class="btn btn-default js-cancel-ip"><i class="fa fa-arrow-left"></i>&nbsp; Back</button>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="row" id="js-ip-add-list-page" style="display: none;">
                                        <div class="col-sm-12">
                                            <br />
                                            <div id="js-ip-address-box">
                                                <table class="table table striped table bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Blocked IP</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>

                                            <!--  -->
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
<!--  -->
<script src="<?= base_url('assets'); ?>/calendar/moment.min.js"></script>
<!--  -->
<script>
    $(function() {
        var tableTarget = $('#js-ip-table'),
            addPageBtnRef = $('.js-ip-add'),
            viewPageBtnRef = $('.js-ip-view'),
            addPageRef = $('#js-ip-add-page'),
            addListPageRef = $('#js-ip-add-list-page'),
            viewPageRef = $('#js-ip-view-page'),
            addConfirmPageRef = $('#js-ip-add-confirm-page'),
            errorTarget = $('.js-error-msg'),
            ipList = [],
            // Set pagination object
            pOBJ = {
                'fetchRecords': {
                    page: 1,
                    totalPages: 0,
                    totalRecords: 0,
                    cb: fetchRecords
                }
            },
            current_page = 1;

        addPageBtnRef.click(loadAddPage);
        viewPageBtnRef.click(loadViewPage);
        // TOBE removed after testing
        // addPageBtnRef.trigger('click');
        //
        loader('hide');

        // Add extra interviewer event
        $(document).on('click', '#js-add-ip-address-row', function() {
            var random_id = Math.floor((Math.random() * 1000) + 1),
                new_row = $('#js-ip-row-0').clone();
            //
            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').addClass('btn-danger').addClass('js-remove-ip-address-row').attr('data-id', random_id);
            $(new_row).find('input').val('');
            $(new_row).find('span').text('');
            $(new_row).attr('id', 'js-ip-address-' + random_id);
            $(new_row).addClass('js-ip-address');
            $(new_row).switchClass('js-ip-address-primary', 'js-ip-address-secondary');
            $(new_row).attr('data-id', random_id);
            $(new_row).find('input.js-name').attr('data-rule-required', true);
            //
            $('#js-ip-address-box').append(new_row);
        });
        // Remove extra interviewer event
        $(document).on('click', '.js-remove-ip-address-row', function() {
            $($(this).closest('.js-ip-address').get()).remove();
        });

        $(document).on('keyup', '.js-ip-address input', validateSingleInput);
        $('.js-save-ip').click(validateInputs);
        $('.js-cancel-ip').click(backToAddPage);
        $('.js-confirm-ip').click(saveIpList);
        $('.js-cancel-ip-btn').click(loadViewPage);

        //
        $(document).on('click', ".jsUnBlockIPBtn", function(event) {
            event.preventDefault();
            ipBlockHandler(0, $(this).closest('tr').data('id'))
        });
        //
        $(document).on('click', ".jsBlockIPBtn", function(event) {
            event.preventDefault();
            ipBlockHandler(1, $(this).closest('tr').data('id'))
        });
        // 
        fetchRecords();
        // Fetch ips
        function fetchRecords() {
            table_loader('show');
            $.post("<?= base_url('manage_admin/blocked_ips/handler'); ?>", {
                action: 'fetch_records',
                status: '1',
                page: pOBJ['fetchRecords']['page'],
                total_records: pOBJ['fetchRecords']['totalRecords']
            }, function(resp) {
                if (resp.Status === false && pOBJ['fetchRecords']['page'] == 1) {
                    errorTarget.find('h4').html(resp.Response);
                }
                if (resp.Status === false) {
                    table_loader('hide');
                    return;
                }

                setTable(resp);
            });
        }
        //
        function setTable(resp) {
            var rows = '';
            $.each(resp.Data, function(i, v) {
                rows += '<tr data-id="' + (v.ip_address) + '">';
                rows += '   <td>' + (v.admin_name || "-") + '</td>';
                rows += '   <td>' + (v.ip_address) + '</td>';
                rows += '   <td>' + (moment(v.created_at, 'YYYY-MM-DD hh:mm:ss').format('LLLL')) + '</td>';
                if (v.is_block == 1) {
                    rows += '   <td><button class="btn btn-success jsUnBlockIPBtn" title="Unblock IP" placement="top"><i class="fa fa-shield"></i></button></td>';
                } else {
                    rows += '   <td><button class="btn btn-warning jsBlockIPBtn" title="Block IP" placement="top"><i class="fa fa-ban"></i></button></td>';
                }
                rows += '</tr>';
            });

            //
            if (pOBJ['fetchRecords']['page'] == 1) {
                pOBJ['fetchRecords']['totalPages'] = resp.TotalPages;
                pOBJ['fetchRecords']['totalRecords'] = resp.TotalRecords;
            }
            //
            load_pagination(
                resp.Limit,
                resp.ListSize,
                $('.js-ip-pagination'),
                'fetchRecords'
            );
            // 

            tableTarget.find('tbody').html(rows);

            table_loader('hide');
        }
        //
        function loader(do_show) {
            if (do_show === undefined || do_show === true || do_show.toLowerCase() === 'show') $('.js-loader').show();
            else $('.js-loader').fadeOut(500);
        }
        //
        function table_loader(do_show) {
            if (do_show === undefined || do_show === true || do_show.toLowerCase() === 'show') $('.js-table-loader').show();
            else $('.js-table-loader').fadeOut(500);
        }
        //
        function loadAddPage() {
            ipList = [];
            $('.js-ip-address-secondary').remove();
            $('.js-ip-address-primary input').val('');
            $('.js-ip-address-primary span.cs-error').text('');
            addConfirmPageRef.hide(0);
            addListPageRef.hide(0);
            addPageBtnRef.hide(0);
            viewPageRef.fadeOut(0);
            viewPageBtnRef.show(0);
            addPageRef.fadeIn(300);
            $('.js-ip-address input').focus();
        }
        //
        function backToAddPage() {
            ipList = [];
            addConfirmPageRef.fadeOut(0);
            addPageRef.fadeIn(300);
        }
        //
        function loadViewPage() {
            ipList = [];
            $('.js-ip-address-secondary').remove();
            $('.js-ip-address-primary input').val('');
            $('.js-ip-address-primary span.cs-error').text('');
            //
            pOBJ['fetchRecords'].page = 1;
            pOBJ['fetchRecords'].totalPages = pOBJ['fetchRecords'].totalRecords = 0;
            //
            viewPageBtnRef.hide(0);
            addPageRef.fadeOut(0);
            addConfirmPageRef.fadeOut(0);
            addListPageRef.fadeOut(0);
            //
            fetchRecords();
            //
            addPageBtnRef.show(0);
            viewPageRef.fadeIn(300);
        }
        //
        function loadAddConfirmPage() {
            var rows = '';
            $.each(ipList, function(i, v) {
                rows += '<tr>';
                rows += '   <td>' + (v) + '</td>';
                rows += '</tr>';
            });
            addConfirmPageRef.find('tbody').html(rows);
            //
            addPageRef.fadeOut(0);
            addConfirmPageRef.fadeIn(300);
        }
        //
        function validateIPaddress(ipAddress) {
            if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipAddress)) return true;
            return false;
        }
        //
        function validateSingleInput() {
            var ins = $(this).val().trim();
            if (ins.length === 0) return;
            //
            if (!validateIPaddress(ins)) {
                $(this).parent().find('span.cs-error').text('Invalid IP address. e.g. XXX.XXX.XXX.XXX');
                return false;
            } else {
                $(this).parent().find('span.cs-error').text('');
                return true;
            }
        }
        //
        function validateSingleInputById(_this) {
            var ins = _this.val().trim();
            if (ins.length === 0) return;
            //
            if (!validateIPaddress(ins)) {
                _this.parent().find('span.cs-error').text('Invalid IP address. e.g. XXX.XXX.XXX.XXX');
                return false;
            } else {
                _this.parent().find('span.cs-error').text('');
                return true;
            }
        }
        //
        function validateInputs() {
            var is_error = false;
            $.each($('.js-ip-address input'), function(i, v) {
                if ($(this).val().trim() != '') {
                    if (!validateSingleInputById($(this))) is_error = true;
                    else ipList.push($(this).val());
                }
            });
            //
            if (is_error) return;
            if (ipList.length === 0) {
                alertify.alert('ERROR!', 'IP Address is missing');
                return;
            }
            //
            // loader('show');
            loadAddConfirmPage();
        }
        //
        function saveIpList() {
            loader('show');
            $.post("<?= base_url('manage_admin/blocked_ips/handler') ?>", {
                action: 'save_ips',
                ip_list: ipList
            }, function(resp) {
                //
                if (resp.Status === false) {
                    loader('hide');
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                //
                var rows = '';
                //
                $.each(resp.Data, function(i, v) {
                    rows += '<tr>';
                    rows += '   <td>' + (v.ip_address) + '</td>';
                    rows += '   <td class="text-' + (v.status == 0 ? 'danger' : 'success') + '">' + (v.status == 0 ? 'IP exists' : 'IP added') + '</td>';
                    rows += '</tr>';
                });

                addConfirmPageRef.fadeOut(0);
                addListPageRef.fadeIn(300);

                addListPageRef.find('tbody').html(rows);
                loader('hide');
                addPageBtnRef.show();
                ipList = [];
            });
        }

        function ipBlockHandler(status, ipId) {
            //
            loader(true)
            //
            $.ajax({
                    url: "<?= base_url("ip_status_handler"); ?>",
                    method: "POST",
                    data: {
                        id: ipId,
                        status
                    }
                })
                .success(function() {
                    loader('hide')
                    alertify.alert(
                        'Success',
                        "The IP address '" + (ipId) + "' has been " + (status === 0 ? 'unblocked' : 'blocked') + " successfully.",
                        function() {
                            window.location.reload()
                        })
                });
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
    /*Table loader*/
    .cs-table-loader {
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        background: rgba(255, 255, 255, .5);
    }

    .cs-table-loader i {
        font-size: 30px;
        text-align: center;
        display: block;
        margin-top: 40px;
    }

    /*Pagination*/
    .cs-pagination {
        float: right;
    }

    .cs-pagination li a {
        background-color: #81b431;
        color: #ffffff;
    }

    /**/
    .cs-error {
        font-weight: bolder;
        color: #cc0000;
    }

    /**/
    .cs-loader-file {
        z-index: 1061 !important;
        display: block !important;
        height: 1353px !important;
    }

    .cs-loader-box {
        position: fixed;
        top: 100px;
        bottom: 0;
        right: 0;
        left: 0;
        max-width: 300px;
        margin: auto;
        z-index: 1539;
    }

    .cs-loader-box i {
        font-size: 14em;
        color: #81b431;
    }

    .cs-loader-box div.cs-loader-text {
        display: block;
        padding: 10px;
        color: #000;
        background-color: #fff;
        border-radius: 5px;
        text-align: center;
        font-weight: 600;
        margin-top: 35px;
    }
</style>