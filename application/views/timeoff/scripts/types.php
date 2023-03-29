<script type="text/javascript">
    var baseURI = "<?= base_url(); ?>timeoff/";
    $(function() {
        var employees = [],
            is_filter = false,
            lastPage = "<?= $page; ?>",
            default_slot = 0,
            fetchStatus = 'active',
            policies = [],
            types = [],
            xhr = null,
            pOBJ = {
                'fetchCompanyTypes': {
                    page: 1,
                    totalPages: 0,
                    limit: 0,
                    records: 0,
                    totalRecords: 0,
                    cb: fetchCompanyTypes
                }
            },
            record = [];

        loader('hide');

        /* FILTER START */
        // Fetch all company policies
        fetchPoliciesList();
        fetchTypesList();
        fetchTypeCreators();

        // Select2
        $('#js-filter-status').select2();
        $('#js-status-add').select2();
        $('#js-status-edit').select2();

        // Datepickers
        $('#js-filter-from-date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(v) {
                $('#js-filter-to-date').datepicker('option', 'minDate', v);
            }
        })
        $('#js-filter-to-date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).datepicker('option', 'minDate', $('#js-filter-from-date').val());

        // Filter buttons
        $(document).on('click', '.js-apply-filter-btn', applyFilter);
        $(document).on('click', '.js-reset-filter-btn', resetFilter);
        /* FILTER END */

        /* TAB CHANGER START*/
        $(".js-tab").click(function() {
            fetchStatus = $(this).data('type');
            fetchCompanyTypes();
        });
        /* TAB CHANGER END*/

        /* VIEW PAGE START */
        //
        function resetFilter(e) {
            e.preventDefault();
            is_filter = false;
            $('#js-filter-types').select2('val', 0);
            $('#js-filter-employee').select2('val', 0);
            $('#js-filter-from-date').val('');
            $('#js-filter-to-date').val('');
            $('#js-filter-status').select2('val', '-1');

            pOBJ['fetchCompanyTypes']['records'] = [];
            pOBJ['fetchCompanyTypes']['totalPages'] =
                pOBJ['fetchCompanyTypes']['totalRecords'] =
                pOBJ['fetchCompanyTypes']['limit'] = 0;
            pOBJ['fetchCompanyTypes']['page'] = 1;

            fetchCompanyTypes();
        }
        //
        function applyFilter(e) {
            loader();
            e.preventDefault();
            is_filter = true;
            pOBJ['fetchCompanyTypes']['records'] = [];
            pOBJ['fetchCompanyTypes']['totalPages'] =
                pOBJ['fetchCompanyTypes']['totalRecords'] =
                pOBJ['fetchCompanyTypes']['limit'] = 0;
            pOBJ['fetchCompanyTypes']['page'] = 1;

            fetchCompanyTypes();
        }
        // Fetch plans
        function fetchCompanyTypes() {
            if (xhr != null) return;
            loader('show');
            $('.js-error-row').remove();
            var megaOBJ = {};
            megaOBJ.page = pOBJ['fetchCompanyTypes']['page'];
            megaOBJ.action = 'get_types_by_company';
            megaOBJ.companySid = <?= $company_sid; ?>;
            megaOBJ.status = is_filter ? $('#js-filter-status').val() : '';
            megaOBJ.typeSid = is_filter ? $('#js-filter-types').val() : '';
            megaOBJ.endDate = is_filter ? $('#js-filter-to-date').val().trim() : '';
            megaOBJ.startDate = is_filter ? $('#js-filter-from-date').val().trim() : '';
            megaOBJ.employeeSid = is_filter ? $('#js-filter-employee').val() : '';
            megaOBJ.fetchType = fetchStatus;

            xhr = $.post(baseURI + 'handler', megaOBJ, function(resp) {
                xhr = null;
                //
                if (resp.Status === false && pOBJ['fetchCompanyTypes']['page'] == 1) {
                    $('.js-ip-pagination').html('');
                    loader('hide');
                    $('#js-data-area').html('<tr class="js-error-row"><td colspan="6"><p class="alert alert-info text-center">' + (resp.Response) + '</p></td></tr>')
                }
                //
                if (resp.Status === false) {
                    loader('hide');
                    $('.js-ip-pagination').html('');
                    return;
                }

                pOBJ['fetchCompanyTypes']['records'] = resp.Data;
                if (pOBJ['fetchCompanyTypes']['page'] == 1) {
                    pOBJ['fetchCompanyTypes']['limit'] = resp.Limit;
                    pOBJ['fetchCompanyTypes']['totalPages'] = resp.TotalPages;
                    pOBJ['fetchCompanyTypes']['totalRecords'] = resp.TotalRecords;
                }
                //
                setTable(resp);
            });
        }
        //
        function setTable(resp) {
            var title = fetchStatus != 'active' ? 'Activate Type' : 'Deactivate Type',
                icon = fetchStatus != 'active' ? 'fa-check-square-o' : 'fa-archive',
                cl = fetchStatus != 'active' ? 'js-activate-type' : 'js-archive-type',
                rows = '';
            if (resp.Data.length == 0) return;
            //
            $.each(resp.Data, function(i, v) {
                rows += '<tr data-id="' + (v.type_id) + '">';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>' + (v.type_name) + '</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p class="js-type-popover" title="Policies" data-placement="right" data-content="' + (getTypeNames(v.Policies)) + '"><abbr>' + (v.Policies.length == 0 ? 'Not Assigned' : v.Policies.length + ' Policies') + ' </abbr></p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td scope="row">';
                rows += '        <div class="employee-info">';
                rows += '            <figure>';
                rows += '                <img src="' + (getImageURL(v.img)) + '" class="img-circle emp-image" />';
                rows += '            </figure>';
                rows += '            <div class="text">';
                rows += '                <h4>' + (v.full_name) + '</h4>';
                rows += '                <p>' + (remakeEmployeeName(v, false)) + '</p>';
                rows += '                <p><a href="<?= base_url('employee_profile'); ?>/' + (v.employee_id) + '" target="_blank">Id: ' + (getEmployeeId(v.employee_id, v.employee_number)) + '</a></p>';
                rows += '            </div>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>' + (moment(v.created_at, 'YYYY-MM-DD').format('MM-DD-YYYY')) + '</p>';
                rows += '        </div>';
                rows += '    </td>';
                // rows += '    <td>';
                // rows += '        <div class="text cs-status-text">';
                // rows += '            <p class="'+( v.status == 1 ? 'cs-success' : 'cs-danger' )+'">'+( v.status == 1 ? 'Active' : 'In-active' )+'</p>';
                // rows += '        </div>';
                // rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="action-employee">';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit Type" class="action-edit js-edit-type-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue"></i></a>';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="' + (title) + '" class="action-activate custom-tooltip ' + (cl) + '"><i class="fa ' + (icon) + ' fa-fw "></i></a>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '</tr>';

            });

            //
            load_pagination(
                pOBJ['fetchCompanyTypes']['limit'],
                5,
                $('.js-ip-pagination'),
                'fetchCompanyTypes'
            );

            $('#js-data-area').html(rows);
            loader('hide');

            //
            $('.js-type-popover').popover({
                html: true,
                trigger: 'hover'
            });

            callDrager();
        }
        //
        $(document).on('click', '.js-archive-type', function(e) {
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to archive this type?', function() {
                var megaOBJ = {};
                megaOBJ.companySid = <?= $company_sid; ?>;
                megaOBJ.action = 'archive_company_type';
                megaOBJ.typeSid = _this.closest('tr').data('id');
                megaOBJ.employeeSid = <?= $employer_sid; ?>;
                //
                loader('show');
                $.post(baseURI + 'handler', megaOBJ, function(resp) {
                    //
                    if (resp.Status === false) {
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response, function() {
                            return;
                        });
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        loadViewPage();
                    });
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            }).set('label', 'WARNING!');
        });
        //
        $(document).on('click', '.js-activate-type', function(e) {
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to activate this type?', function() {
                var megaOBJ = {};
                megaOBJ.companySid = <?= $company_sid; ?>;
                megaOBJ.action = 'activate_company_type';
                megaOBJ.typeSid = _this.closest('tr').data('id');
                megaOBJ.employeeSid = <?= $employer_sid; ?>;
                //
                loader('show');
                $.post(baseURI + 'handler', megaOBJ, function(resp) {
                    //
                    if (resp.Status === false) {
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response, function() {
                            return;
                        });
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        loadViewPage();
                    });
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            }).set('label', 'WARNING!');
        });
        //
        $(document).on('click', '.js-edit-type-btn', function(e) {
            e.preventDefault();
            var sid = $(this).closest('tr').data('id');
            var record = [];
            pOBJ['fetchCompanyTypes']['records'].map(function(r) {
                if (r['type_id'] == sid) record = r;
            });
            //
            $('.js-page').fadeOut(0);
            $('#js-page-edit').fadeIn(500);
            loadEditPage(record);
        });

        /* VIEW PAGE END */


        /* ADD PAGE START*/
        //
        $('#js-save-add-btn').click(function(e) {
            e.preventDefault();
            var megaOBJ = {};
            megaOBJ.companySid = <?= $company_sid; ?>;
            megaOBJ.employerSid = <?= $employer_sid; ?>;
            megaOBJ.action = 'add_company_catgeory';
            megaOBJ.status = 1;
            megaOBJ.category = $('#js-type-add').val().trim();
            megaOBJ.policies = $('#js-policies-add').val();
            megaOBJ.isArchived = Number($('#js-archived-add').prop('checked'));
            //
            if (megaOBJ.category == '') {
                alertify.alert('ERROR!', 'Type name is required.', function() {
                    return;
                });
                return;
            }
            //
            loader('show');
            $.post(baseURI + 'handler', megaOBJ, function(resp) {
                //
                if (resp.Status === false) {
                    loader('hide');
                    alertify.alert('ERROR!', resp.Response, function() {
                        return;
                    });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function() {
                    loadViewPage();
                });
            });
        });
        /* ADD PAGE END*/

        /* EDIT PAGE END*/
        //
        $(".js-edit-type-btn").click(function() {
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-page-edit').fadeIn(500);
            loader('hide');
        });
        //
        $('#js-save-edit-btn').click(function(e) {
            e.preventDefault();
            var megaOBJ = {};
            megaOBJ.companySid = <?= $company_sid; ?>;
            megaOBJ.employerSid = <?= $employer_sid; ?>;
            megaOBJ.action = 'edit_company_catgeory';
            megaOBJ.typeSid = $('#js-type-id-edit').val();
            megaOBJ.status = 1;
            megaOBJ.category = $('#js-type-edit').val().trim();
            megaOBJ.policies = $('#js-policies-edit').val();
            megaOBJ.isArchived = Number($('#js-archived-edit').prop('checked'));
            //
            if (megaOBJ.category == '') {
                alertify.alert('ERROR!', 'Type name is required.', function() {
                    return;
                });
                return;
            }
            //
            loader('show');
            $.post(baseURI + 'handler', megaOBJ, function(resp) {
                //
                if (resp.Status === false) {
                    loader('hide');
                    alertify.alert('ERROR!', resp.Response, function() {
                        return;
                    });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function() {
                    loadViewPage();
                });
            });
        });
        ///
        function loadEditPage(record) {
            lastPage = 'edit';
            loader();
            //
            $('#js-archived-edit').prop('checked', record.is_archived == 1 ? true : false);
            $('#js-type-edit').val(record.type_name);
            $('#js-policies-edit').select2();
            // $('#js-status-edit').select2();

            // $('#js-status-edit').select2('val', record.status);
            $('#js-type-id-edit').val(record.type_id);
            //
            var tmp = policies.map(function(v) {
                return '<option value="' + (v.sid) + '">' + (v.title) + ' (' + (v.is_archived == 1 ? 'Archived' : 'Active') + ')</option>';
            });
            //
            $('#js-policies-edit').html(tmp);
            $('#js-policies-edit').select2MultiCheckboxes({
                templateSelection: function(selected, total) {
                    return "Selected " + ($.inArray('all', $('#js-policies-edit').val()) !== -1 ? total : selected.length) + " of " + total;
                }
            });

            $('#js-policies-edit').select2('val', record.Policies);
            $('.js-archived-edit').prop('checked', record.is_archived);
            //
            loader('hide');
        }
        /* EDIT PAGE START*/

        // Page events
        // Page handlers
        $("#js-add-type-btn").click(function() {
            setHistory('add', baseURI + 'types/add', 'Add Page');
        });
        $(".js-view-type-btn").click(function() {
            setHistory('view', baseURI + 'types/view', 'View Page');
        });
        // Pages
        function loadAddPage() {
            lastPage = 'add';
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-type-add').val('');
            $('#js-policies-add').select2();
            $('#js-policies-add').select2('val', null);
            // $('#js-status-add').select2();
            // $('#js-status-add').select2('val', 1);
            $('#js-type-add-input').val('');
            $('#js-archived-add').prop('checked', false);
            $('#js-page-add').fadeIn(500);
            loader('hide');
        }

        function loadViewPage() {
            lastPage = 'view';
            $('.js-page').fadeOut(0);
            pOBJ['fetchCompanyTypes']['records'] = [];
            pOBJ['fetchCompanyTypes']['page'] = 1;
            pOBJ['fetchCompanyTypes']['limit'] = 0;
            pOBJ['fetchCompanyTypes']['totalPages'] = 0;
            pOBJ['fetchCompanyTypes']['totalRecords'] = 0;
            fetchCompanyTypes();
            $('#js-page-view').fadeIn(500);
        }
        // Set history
        function setHistory(page, pageURL, pageTitle, dataToBind) {
            if (page == lastPage) return;
            if (page === undefined) return;
            if (pageURL === undefined) return;
            if (dataToBind === undefined) dataToBind = {};
            if (pageTitle === undefined) pageTitle = '';
            window.history.pushState({
                fromPage: {
                    title: lastPage
                },
                toPage: {
                    title: page
                }
            }, pageTitle, pageURL);
            switch (page) {
                case 'add':
                    loadAddPage();
                    break;
                case 'view':
                    loadViewPage();
                    break;
            }
            lastPage = page;
            $('html,body').animate({
                scrollTop: 0
            }, 'slow');
        }
        //
        window.onpopstate = function(event) {
            //
            if (event.state == null) {
                <?php if ($page == 'view') { ?>fetchCompanyTypes();
            <?php } else if ($page == 'add') { ?> loadAddPage();
            <?php } else if ($page == 'edit' && $planSid != null) { ?> loadEditPage(<?= $planSid; ?>);
            <?php } ?>
            }
            //
            switch (event.state.toPage.title) {
                case 'add':
                    loadAddPage();
                    break;
                case 'view':
                    loadViewPage();
                    break;
            }
            lastPage = event.state.toPage.title;
        };
        //
        function fetchPoliciesList() {
            $.post(baseURI + 'handler', {
                action: 'get_company_policies_list',
                companySid: <?= $company_sid; ?>
            }, function(resp) {
                //
                if (resp.Status === false) {
                    console.log('failed to load policies list');
                    return;
                }
                //
                policies = resp.Data;
                var tmp = policies.map(function(v) {
                    return '<option value="' + (v.sid) + '">' + (v.title) + ' (' + (v.is_archived == 1 ? 'Archived' : 'Active') + ')</option>';
                });
                $('#js-policies-add').html(tmp);
                $('#js-policies-add').select2MultiCheckboxes({
                    templateSelection: function(selected, total) {
                        return "Selected " + ($.inArray('all', $('#js-policies-add').val()) !== -1 ? total : selected.length) + " of " + total;
                    }
                });
            });
        }
        //
        function fetchTypeCreators() {
            $.post(baseURI + 'handler', {
                action: 'get_company_employees',
                companySid: <?= $company_sid; ?>
            }, function(resp) {
                //
                if (resp.Status === false) {
                    console.log('failed to load employees');
                    return;
                }
                //
                employees = resp.Data;
                var tmp = employees.map(function(v) {
                    return '<option value="' + (v.employee_id) + '">' + (remakeEmployeeName(v)) + '</option>';
                });
                tmp = '<option value="0">[Select an employee]</option>' + tmp;
                $('#js-filter-employee').html(tmp);
                $('#js-filter-employee').select2();
            });
        }
        //
        function fetchTypesList() {
            $.post(baseURI + 'handler', {
                action: 'get_company_types_list',
                companySid: <?= $company_sid; ?>
            }, function(resp) {
                //
                if (resp.Status === false) {
                    console.log('failed to load types');
                    return;
                }
                //
                types = resp.Data;
                var tmp = types.map(function(v) {
                    return '<option value="' + (v.type_id) + '">' + (v.type_name) + '</option>';
                });
                tmp = '<option value="0">[Select an types]</option>' + tmp;
                $('#js-filter-types').html(tmp);
                $('#js-filter-types').select2();
            });
        }

        //
        <?php if ($page == 'view') { ?>fetchCompanyTypes();
    <?php } else if ($page == 'add') { ?> loadAddPage();
    <?php } ?>
    //
    <?php $this->load->view('timeoff/scripts/common'); ?>

    //
    function callDrager() {
        $("#js-data-area").sortable({
            placeholder: "ui-state-highlight"
        });
        $("#js-data-area").disableSelection();
    }

    $("#js-data-area").on("sortstop", callSort);

    function callSort() {
        var
            i = 1,
            s = {},
            l = $('#js-data-area').find('tr').length;
        for (i; i <= l; i++) {
            s[$('#js-data-area').find('tr:nth-child(' + (i) + ')').data('id')] = i;
        }
        updateSortInDb(s);
    }

    function updateSortInDb(s) {
        // loader('show');
        $.post(baseURI + 'handler', {
            sort: s,
            companySid: <?= $company_sid; ?>,
            type: 'categories',
            action: 'update_sort_order'
        }, function(resp) {
            loader('hide');
        });
    }

    //
    function getTypeNames(ids) {
        if (ids.length == 0) return 'Not Assigned';
        if (policies.length == 0) return 'Not Assigned';
        //
        var row = '';
        //
        policies.map(function(v) {
            if ($.inArray(v.sid, ids) !== -1) {
                row += v.title + ', ';
            }
        });

        return row.substring(0, row.length - 2);
    }

    })
</script>
<style>
    #js-data-area tr {
        cursor: move
    }

    ;
</style>
<style>
    .js-archive-type>i {
        color: #a94442 !important;
    }
</style>
<style>
    .js-activate-type>i {
        color: #81b431 !important;
    }
</style>