<?php if ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>

    <!--  -->
    <style>
        .cs-inner-loader {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, .5);
            /*  */
            text-align: center;
        }

        .cs-inner-loader i {
            position: relative;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default" style="position: relative;">
                <div class="panel-heading">
                    <span><strong>General Document(s)</strong></span>
                </div>
                <div class="panel-body" style="min-height: 200px;">
                    <!-- Loader -->
                    <div class="cs-inner-loader jsDocumentLoader">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                    </div>
                    <!-- Data -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Document Name</th>
                                    <th class="text-center">Assigned On</th>
                                    <th class="text-center">Completion Status</th>
                                    <th class="text-center">Is Required?</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <!--  -->
                            <tbody id="jsGeneralDocumentBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- General Document Assigned History Model -->
    <div class="modal fade" id="jsGeneralHistoryModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #81b431; color: #fff;">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <div class="table-responsive table-outer">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Action</th>
                                    <th>DateTime</th>
                                </tr>
                            </thead>
                            <tbody id="jsGeneralHistoryModalBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- General Document Assigned Model -->
    <div class="modal fade" id="jsGeneralAssignModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #81b431; color: #fff;">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Note</label>
                        <textarea id="jsGeneralAssignModelNote"></textarea>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form-group">
                        <br />
                        <label>The document is required?</label>
                        <br />
                        <label class="control control--radio">
                            Yes&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="GeneralDocumentRequired" name="GeneralDocumentRequired" value="1" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">
                            No
                            <input type="radio" class="GeneralDocumentRequired" name="GeneralDocumentRequired" value="0" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form-group">
                        <br />
                        <label>Send email notification?</label>
                        <br />
                        <label class="control control--radio">
                            Yes&nbsp;&nbsp;&nbsp;
                            <input type="radio" id="jsGeneralAssignModelSEN" name="jsGeneralAssignModelSEN" value="1" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">
                            No
                            <input type="radio" id="jsGeneralAssignModelSEN" name="jsGeneralAssignModelSEN" value="0" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success jsGeneralAssignModelBTN">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- General Document View Model -->
    <div class="modal fade" id="jsGeneralViewModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #81b431; color: #fff;">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body" style="min-height: 300px;">
                    <div class="jsInLoader" style="position: absolute; left: 0; right: 0; top: 50%; bottom: 0; width: 100%; text-align: center;">
                        <i class="fa fa-circle-o-notch fa-spin" style="font-size: 50px; color: #81b431;"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
    <!--  -->
    <script>
        $(document).ready(function() {
            //
            let docmentOBJ = {};
            let slugToName = {
                'direct_deposit': 'Direct Deposit Information',
                'drivers_license': 'Drivers License Information',
                'occupational_license': 'Occupational License Information',
                'emergency_contacts': 'Emergency Contacts',
                'dependents': 'Dependents',
            };
            let typeToUrl = {
                'direct_deposit': `direct_deposit/<?= $user_type; ?>/<?= $user_sid; ?>`,
                'drivers_license': `drivers_license_info/<?= $user_type; ?>/<?= $user_sid; ?>`,
                'occupational_license': `occupational_license_info/<?= $user_type; ?>/<?= $user_sid; ?>`,
                'emergency_contacts': `emergency_contacts/<?= $user_type; ?>/<?= $user_sid; ?>`,
                'dependents': `dependants/<?= $user_type; ?>/<?= $user_sid; ?>`

            };
            var defaultRequires = <?= json_encode([
                                        'man_d1' => $session['portal_detail']['man_d1'],
                                        'man_d2' => $session['portal_detail']['man_d2'],
                                        'man_d3' => $session['portal_detail']['man_d3'],
                                        'man_d4' => $session['portal_detail']['man_d4'],
                                        'man_d5' => $session['portal_detail']['man_d5']
                                    ]) ?>;
            //
            let _this = null;
            //
            let cacheOBJ = {};
            //
            let xhr = null;

            // ucwords
            String.prototype.ucwords = function() {
                str = this.toLowerCase();
                return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                    function(s) {
                        return s.toUpperCase();
                    });
            };

            // fix
            String.prototype.fix = function() {
                str = this.toLowerCase();
                return str.replace(/_/g, ' ').ucwords();
            };

            //
            setTimeout(() => {
                getGeneralDocumentStatus();
            }, 1000);


            //
            $(document).on(
                'click',
                '.jsRequiredCheckbox',
                function(event) {
                    //
                    var obj = {
                        'document_type': $(this).closest('tr').data('id'),
                        'document_id': $(this).closest('tr').data('key'),
                        'required': $(this).prop('checked') === true ? 'on' : 'off'
                    };
                    //
                    nl();
                    //
                    obj.action = "mark_general_document_mandatory";
                    obj.user_sid = <?= $user_sid; ?>;
                    obj.user_type = "<?= $user_type; ?>";
                    //
                    $.post(
                        "<?= base_url("hr_documents_management/handler"); ?>",
                        obj,
                        (resp) => {
                            //
                            nl(false);
                        }
                    );
                }
            );

            //
            $(document).on('click', '.jsGeneralDocumentView', function(e) {
                //
                e.preventDefault();
                //
                if (xhr !== null) xhr.abort();
                //
                let _this = $(this).closest('tr');
                //
                $('#jsBodyHolder').remove();
                $('.jsInLoader').show();
                $('#jsGeneralViewModel .modal-title').html(`<strong>${_this.data('id').replace(/_/, ' ').ucwords()}</strong>`);
                $('.jsGeneralViewModelBTNs').remove();
                $('#jsGeneralViewModel').modal('show');
                // Let's check the cache first
                if (cacheOBJ[_this.data('id')] !== undefined) {
                    $('.jsInLoader').hide();
                    $('#jsGeneralViewModel .modal-body').append(`<div id="jsBodyHolder">${cacheOBJ[_this.data('id')].html}</div>`);
                    $('#jsGeneralViewModel .modal-footer').prepend(getFooterButtons(_this.data('id')));
                } else {
                    xhr = $.post("<?= base_url('hr_documents_management/handler'); ?>", {
                        action: 'get_general_document_view',
                        userSid: <?= $user_sid; ?>,
                        companySid: <?= $company_sid; ?>,
                        companyName: "<?= $company_name; ?>",
                        userType: "<?= $user_type; ?>",
                        documentType: _this.data('id')
                    }, (resp) => {
                        //
                        xhr = null;
                        cacheOBJ[_this.data('id')] = {
                            html: `<div id="jsBodyHolder">${resp.template}</div>`,
                            expiresAt: moment().add(30, 'minutes')
                        }
                        $('.jsInLoader').hide();
                        $('#jsGeneralViewModel .modal-body').append(`<div id="jsBodyHolder">${resp.template}</div>`);
                        $('#jsGeneralViewModel .modal-footer').prepend(getFooterButtons(_this.data('id')));
                    });
                }
            });

            //
            $('#jsGeneralViewModel').on('hide.bs.modal', () => {
                if (xhr !== null) {
                    xhr.abort();
                    xhr = null;
                }
            });

            //
            function getFooterButtons(
                documentType
            ) {
                //
                return `
                <a target="_blank" href="<?= base_url('hr_documents_management/gpd/print'); ?>/${documentType}/<?= $user_type; ?>/<?= $user_sid; ?>" class="btn btn-success jsGeneralViewModelBTNs">Print</a>
                <a target="_blank" href="<?= base_url('hr_documents_management/gpd/download'); ?>/${documentType}/<?= $user_type; ?>/<?= $user_sid; ?>" class="btn btn-success jsGeneralViewModelBTNs">Download</a>
            `;
            }

            //
            $(document).on('click', '.jsGeneralAssignModelBTN', (e) => {
                e.preventDefault();
                //
                let obj = {};
                //
                obj.note = CKEDITOR.instances['jsGeneralAssignModelNote'].getData();
                obj.sendEmail = $('#jsGeneralAssignModelSEN:checked').val();
                obj.isRequired = $('.GeneralDocumentRequired:checked').val();
                //
                handleAssignDocument(_this, obj);
            });

            // Click on assign button
            $(document).on('click', '.jsGeneralDocumentAssign', function(e) {
                //
                e.preventDefault();
                //
                switch ($(this).data('type').toLowerCase()) {
                    //
                    case "assign":
                        _this = $(this);
                        //
                        if (CKEDITOR.instances['jsGeneralAssignModelNote'] === undefined) CKEDITOR.replace('jsGeneralAssignModelNote');
                        else CKEDITOR.instances['jsGeneralAssignModelNote'].setData('');
                        //
                        $('#jsGeneralAssignModelSEN[value="1"]').prop('checked', true);
                        //
                        $('.GeneralDocumentRequired[value="0"]').prop('checked', true);
                        //
                        $('#jsGeneralAssignModel').find('.modal-title').html(`<strong>Assign ${$(this).closest('tr').data('id').replace(/_/, ' ').ucwords()}</strong>`);
                        $('#jsGeneralAssignModel').modal('show');

                        break;
                        //
                    case "revoke":
                        alertify.confirm(
                            `Do you really want to revoke this document?`,
                            () => {
                                handleRevokeDocument($(this));
                            },
                            () => {}
                        ).set('labels', {
                            ok: 'YES',
                            cancel: 'No'
                        }).set('title', 'CONFIRM!');
                        break;
                }
            });

            // Click on history
            $(document).on('click', '.jsGeneralDocumentHistory', function(e) {
                //
                e.preventDefault();
                //
                let obj = {};
                obj.action = "get_general_document_history";
                obj.companySid = <?= $company_sid; ?>;
                obj.companyName = "<?= $company_name; ?>";
                obj.generalDocumentSid = $(this).closest('tr').data('key');
                obj.userType = "<?= $user_type ?>";
                //
                nl();
                //
                $.post(
                    "<?= base_url("hr_documents_management/handler"); ?>",
                    obj,
                    (resp) => {
                        //
                        let rows = '';
                        //
                        if (resp.Data.length > 0) {
                            resp.Data.map((v) => {
                                rows += `
                                <tr>
                                    <td>${ v.name }</td>
                                    <td class="text-${v.action != 'revoke' ? 'success' : 'danger'}">${v.action.ucwords()}${v.action == 'revoke' ? 'd' : ( v.action == 'assign' ? 'ed' : '')}</td>
                                    <td>${moment(v.created_at).format('MMM Do YYYY, ddd H:m:s')}</td>
                                </tr>
                            `;
                            });
                        } else {
                            rows = `
                        <tr>
                            <td colspan="4">
                                <p class="alert alert-info text-center">No records found!</p>
                            </td>
                        <tr>`;
                        }
                        //
                        $('#jsGeneralHistoryModel').find('.modal-title').html(`<strong>${$(this).closest('tr').data('id').replace(/_/, ' ').ucwords()} History</strong>`);
                        $('#jsGeneralHistoryModalBody').html(rows);
                        $('#jsGeneralHistoryModel').modal('show');
                        //
                        nl(false);
                    }
                );
            });

            //
            function handleAssignDocument(target, obj) {
                //
                nl();
                //
                obj.action = "assign_document";
                obj.documentType = target.closest('tr').data('id');
                obj.companySid = <?= $company_sid; ?>;
                obj.companyName = "<?= $company_name; ?>";
                obj.sid = target.closest('tr').data('key');
                obj.userSid = <?= $user_sid; ?>;
                obj.userType = "<?= $user_type; ?>";
                //
                $('#jsGeneralAssignModel').modal('hide');
                //
                $.post(
                    "<?= base_url("hr_documents_management/handler"); ?>",
                    obj,
                    (resp) => {
                        //
                        nl(false);
                        //
                        if (resp.Status === false) {
                            alertify.alert(
                                'WARNING!',
                                resp.Response,
                                () => {}
                            );
                            return;
                        }
                        //
                        alertify.alert(
                            'SUCCESS!',
                            resp.Response,
                            () => {
                                getGeneralDocumentStatus();
                            }
                        );
                    }
                );
            }

            //
            function handleRevokeDocument(target) {
                //
                nl();
                //
                let obj = {};
                obj.action = "revoke_document";
                obj.documentType = target.closest('tr').data('id');
                obj.companySid = <?= $company_sid; ?>;
                obj.companyName = "<?= $company_name; ?>";
                obj.sid = target.closest('tr').data('key');
                obj.userSid = <?= $user_sid; ?>;
                obj.userType = "<?= $user_type; ?>";
                //
                $.post(
                    "<?= base_url("hr_documents_management/handler"); ?>",
                    obj,
                    (resp) => {
                        //
                        nl(false);
                        //
                        if (resp.Status === false) {
                            alertify.alert(
                                'WARNING!',
                                resp.Response,
                                () => {}
                            );
                            return;
                        }
                        //
                        alertify.alert(
                            'SUCCESS!',
                            resp.Response,
                            () => {
                                getGeneralDocumentStatus();
                            }
                        );
                    }
                );
            }

            //
            function getGeneralDocumentStatus() {
                //
                let obj = {};
                obj.action = "get_documents";
                obj.userSid = <?= $user_sid; ?>;
                obj.companySid = <?= $company_sid; ?>;
                obj.companyName = "<?= $company_name; ?>";
                obj.userType = "<?= $user_type; ?>";
                //
                $.post(
                    "<?= base_url("hr_documents_management/handler"); ?>",
                    obj,
                    (resp) => {
                        //
                        console.log(resp.Data);
                        resetData(resp.Data);
                        setView();
                    }
                );
            }

            //
            function resetData(data) {
                //
                let t = {};
                //
                if (data.length === 0) {
                    t['direct_deposit'] = getDefaultObj('direct_deposit');
                    t['drivers_license'] = getDefaultObj('drivers_license');
                    t['occupational_license'] = getDefaultObj('occupational_license');
                    t['emergency_contacts'] = getDefaultObj('emergency_contacts');
                    t['dependents'] = getDefaultObj('dependents');

                   
                } else {
                    //
                    let t1 = {};
                    //
                    $.each(data, (i, v) => {
                        t1[v.document_type] = v;
                    });
                    //
                    if (t1['direct_deposit'] === undefined) t1['direct_deposit'] = getDefaultObj('direct_deposit');
                    if (t1['drivers_license'] === undefined) t1['drivers_license'] = getDefaultObj('drivers_license');
                    if (t1['occupational_license'] === undefined) t1['occupational_license'] = getDefaultObj('occupational_license');
                    if (t1['emergency_contacts'] === undefined) t1['emergency_contacts'] = getDefaultObj('emergency_contacts');
                    if (t1['dependents'] === undefined) t1['dependents'] = getDefaultObj('dependents');
                   
                    //
                    t = t1;
                }
                //
                const ordered = {};
                Object.keys(t).sort().forEach(function(key) {
                    ordered[key] = t[key];
                });
                //
                documentOBJ = ordered;
            }

            //
            function setView() {
                //
                let rows = '';
                //
                let
                    completedDocs = '',
                    completedDocsCount = 0,
                    notCompletedDocs = '',
                    notCompletedDocsCount = 0;
                //
                $.each(documentOBJ, (i, v) => {
                    //
                    let
                        btnClass = 'btn-success',
                        btnText = 'Assign',
                        btnType = 'assign';
                    //
                    if (v.sid != 0) {
                        if (v.status == 1) {
                            btnClass = 'btn-danger';
                            btnText = 'Revoke';
                            btnType = 'revoke';
                        } else {
                            btnClass = 'btn-warning';
                            btnText = 'Reassign';
                            btnType = 'assign';
                        }
                    }
                    rows += `
                    <tr data-id="${v.document_type}" data-key="${v.sid}">
                        <td>${slugToName[v.document_type]}</td>
                        <td class="text-center jsAssignedOn">
                            ${ v.assigned_at === undefined || v.status == 0 ? '<i class="fa fa-times fa-2x text-danger"></i>' : `<i class="fa fa-check fa-2x text-success"></i> <br />${moment(v.assigned_at).format('MMM Do YYYY, ddd H:m:s')}` }
                        ${v.assigned_by_name!='' && v.assigned_at !== undefined && v.status == 1 ? v.assigned_by_name :'' }
                            </td>
                        <td class="text-center">
                            <img src="<?= base_url('assets/manage_admin/images'); ?>/${v.is_completed == 1 ? "on" : "off"}.gif" class="jsGeneralDocumentStatus" title="${v.is_completed == 1 ? "Completed" : "Pending"}" />
                            <br />
                            <span>${v.is_completed == 1 ? 'Completed on: '+moment(v.updated_at).format('MMM Do YYYY, ddd H:m:s') : ''}</span>
                        </td>
                        <td class="text-center">
                            <label class="control control--checkbox">
                                <input type="checkbox" name="jsRequiredCheckbox${v.sid}" class="${v.sid != 0 ? 'jsRequiredCheckbox' : ''}" ${v.sid == 0 ? "disabled" : ""} ${v.is_required == 1 ? 'checked' : ''} />
                                <div class="control__indicator"></div>
                            </label>
                        </td>
                        <td class="text-center">
                            <button class="btn ${btnClass} jsGeneralDocumentAssign" data-type="${btnType}" title="${btnType.ucwords()} this document">${btnText}</button>
                            ${v.sid == 0 ? '' : '<button class="btn btn-success jsGeneralDocumentHistory" title="Assign/Revoke history">History</button>'}
                            ${v.is_completed == 0 ? '' : '<button class="btn btn-success jsGeneralDocumentView" title="View this document">View</button>'}
                        </td>
                    </tr>
                `;

                    // Set print & download buttons
                    let printBTN = `<a title="Print ${v.document_type.fix()}" href="<?= base_url('hr_documents_management/gpd/print'); ?>/${v.document_type}/<?= $user_type . '/' . $user_sid; ?>" target="_blank" class="btn btn-success btn-sm">Print</a>`;
                    let downloadBTN = `<a title="Download ${v.document_type.fix()}" href="<?= base_url('hr_documents_management/gpd/download'); ?>/${v.document_type}/<?= $user_type . '/' . $user_sid; ?>" target="_blank" class="btn btn-success btn-sm">Download</a>`;

                    //
                    if (v.is_completed == 1) {
                        completedDocs += `
                        <tr class="jsGeneralRowCompleted${v.document_type}">
                            <td class="">
                                ${v.document_type.replace(/_/, ' ').ucwords()} ${v.is_required == 1 ? '<i class="fa fa-asterisk text-danger"></i>' : ''} <br />
                                <strong>Assigned on: </strong> ${moment(v.assigned_at).format('MMM Do YYYY, ddd H:m:s')} 
                                ${v.assigned_by_name} <br />
                                <strong>Completed on: </strong> ${moment(v.updated_at).format('MMM Do YYYY, ddd H:m:s')}
                            </td>
                            <td class="">
                                ${printBTN}
                                ${downloadBTN}
                                <a class="btn btn-success btn-sm" href="<?= base_url(); ?>/${typeToUrl[v.document_type]}">View Doc</a>
                            </td>
                        </tr>
                    `;
                        completedDocsCount++;
                    } else if (v.status == 1) {
                        notCompletedDocs += `
                        <tr class="jsGeneralRowNotCompleted${v.document_type}">
                            <td class="">
                                ${v.document_type.replaceAll(/_/, ' ').ucwords()} ${v.is_required == 1 ? '<i class="fa fa-asterisk text-danger"></i>' : ''}<br />
                                <strong>Assigned on: </strong> ${moment(v.assigned_at).format('MMM Do YYYY, ddd H:m:s')+v.assigned_by_name}
                            </td>
                            <td class="text-center">
                                ${printBTN}
                                ${downloadBTN}
                                <a class="btn btn-success btn-sm" href="<?= base_url(); ?>/${typeToUrl[v.document_type]}">View Doc</a>
                            </td>
                        </tr>
                    `;
                        notCompletedDocsCount++;
                    }
                });

                //
                setCompletedView(completedDocs, completedDocsCount);
                setNotCompletedView(notCompletedDocs, notCompletedDocsCount);

                //
                $('#jsGeneralDocumentBody').html(rows);

                //
                nl(false);
            }

            //
            function getDefaultObj(documentType) {
                return {
                    sid: 0,
                    company_sid: <?= $company_sid; ?>,
                    user_sid: <?= $user_sid; ?>,
                    user_type: "<?= $user_type; ?>",
                    document_type: documentType,
                    status: 0,
                    is_completed: 0
                };
            }

            //
            function nl(s) {
                if (s === undefined) $('.jsDocumentLoader').show();
                else $('.jsDocumentLoader').hide();
            }

            //
            function setCompletedView(rows, cc) {
                if (cc == 0) return;
                //
                if ($('#collapse_general_documents_completed').length > 0) {
                    $('#collapse_general_documents_completed tbody').append(rows);
                    //
                    $('.js-ncd').text(
                        parseInt($('.js-ncd').text()) + cc
                    );
                    return;
                }
                //
                let bb = `
            <div class="rosw jsGDR">
                <div class="col-xs-12">
                    <br />
                    <div class="panel panel-default hr-documents-tab-content">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_general_documents_completed">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    General Document(s)
                                    <div class="pull-right total-records"><b>&nbsp;Total: ${cc}</b></div>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_general_documents_completed" class="panel-collapse collapse">
                            <div class="table-responsive full-width">
                                <table class="table table-plane">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-8">Document Name</th>
                                            <th class="col-lg-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>${rows}</tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            `;
                $('#signed_doc_details').append(bb);
                //
                $('.js-cd').text(
                    parseInt($('.js-cd').text()) + cc
                );
            }

            //
            function setNotCompletedView(rows, cc) {
                if (cc == 0) return;
                //
                if ($('#collapse_general_documents_notcompleted').length > 0) {
                    $('#collapse_general_documents_notcompleted tbody').append(rows);
                    //
                    $('.js-ncd').text(
                        parseInt($('.js-ncd').text()) + cc
                    );
                    return;
                }
                //
                let bb = `
            <div class="rosw jsGDR">
                <div class="col-xs-12">
                    <br />
                    <div class="panel panel-default hr-documents-tab-content">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_general_documents_notcompleted">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    General Document(s)
                                    <div class="pull-right total-records"><b>&nbsp;Total: ${cc}</b></div>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_general_documents_notcompleted" class="panel-collapse collapse in">
                            <div class="table-responsive full-width">
                                <table class="table table-plane">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-8">Document Name</th>
                                            <th class="col-lg-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>${rows}</tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            `;
                $('#in_complete_doc_details').append(bb);
                //
                $('.js-ncd').text(
                    parseInt($('.js-ncd').text()) + cc
                );
            }
        });
    </script>
<?php } ?>