<!--  -->
<style>
    .cs-inner-loader{
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(255,255,255,.5);
        /*  */
        text-align: center;
    }
    .cs-inner-loader i{
        position: relative;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>


<script src="<?=base_url('assets/js/moment.min.js');?>"></script>
<!--  -->
<script>
    $(document).ready(function(){
        //
        var direct_deposit_flag = '<?php echo checkGeneralDocumentActive('direct_deposit_flag') ? 1 : 0; ?>';
        var drivers_license_flag = '<?php echo checkGeneralDocumentActive('drivers_license_flag') ? 1 : 0; ?>';
        var occupational_license_flag = '<?php echo checkGeneralDocumentActive('occupational_license_flag') ? 1 : 0; ?>';
        var emergency_contacts_flag = '<?php echo checkGeneralDocumentActive('emergency_contacts_flag') ? 1 : 0; ?>';
        var dependents_flag = '<?php echo checkGeneralDocumentActive('dependents_flag') ? 1 : 0; ?>';
        //    
        let docmentOBJ = {};
        let slugToName = {
            'direct_deposit': 'Direct Deposit Information',
            'drivers_license': 'Drivers License Information',
            'occupational_license': 'Occupational License Information',
            'emergency_contacts': 'Emergency Contacts',
            'dependents': 'Dependents'
        };
        let typeToUrl = {
            'direct_deposit': `onboarding/general_information/<?=$unique_sid;?>/direct_deposit`,
            'drivers_license': `onboarding/general_information/<?=$unique_sid;?>/drivers_license`,
            'occupational_license': `onboarding/general_information/<?=$unique_sid;?>/occupational_license`,
            'emergency_contacts': `onboarding/general_information/<?=$unique_sid;?>/emergency_contacts`,
            'dependents': `onboarding/general_information/<?=$unique_sid;?>/dependents`
        }

        // ucwords
        String.prototype.ucwords = function() {
            str = this.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function(s){
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

        // Click on assign button
        $(document).on('click', '.jsGeneralDocumentAssign', function(e){
            //
            e.preventDefault();
            //
            switch($(this).data('type').toLowerCase()){
                //
                case "assign":
                    alertify.confirm(
                        `Do you really want to assign this document?`, 
                        () => {
                            handleAssignDocument($(this));
                        },
                        () => {}
                    ).set('labels', {
                        ok: 'YES',
                        cancel: 'No'
                    }).set('title', 'CONFIRM!');
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
        $(document).on('click', '.jsGeneralDocumentHistory', function(e){
            //
            e.preventDefault();
            //
            let obj = {};
            obj.action = "get_general_document_history";
            obj.companySid = <?=$company_sid;?>;
            obj.companyName = "<?=$company_name;?>";
            obj.generalDocumentSid = $(this).closest('tr').data('key');
            obj.userType = "<?=$user_type?>";
            //
            nl();
            //
            $.post(
                "<?=base_url("hr_documents_management/handler");?>",
                obj,
                (resp) => {
                    //
                    let rows = '';
                    //
                    if(resp.Data.length > 0 ){
                        resp.Data.map((v) => {
                            rows += `
                                <tr>
                                    <td>${ v.name }</td>
                                    <td class="text-${v.action != 'revoke' ? 'success' : 'danger'}">${v.action.ucwords()}${v.action == 'revoke' ? 'd' : ( v.action == 'assign' ? 'ed' : '')}</td>
                                    <td>${moment(v.created_at).format('MMM Do YYYY, ddd H:m:s')}</td>
                                </tr>
                            `;
                        });
                    } else{
                        rows = `
                        <tr>
                            <td colspan="${$('#jsGeneralHistoryModel').find('thead tr').length}">
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
        function handleAssignDocument(target){
            //
            nl();
            let obj = {};
            obj.action = "assign_document";
            obj.documentType = target.closest('tr').data('id');
            obj.companySid = <?=$company_sid;?>;
            obj.companyName = "<?=$company_name;?>";
            obj.sid = target.closest('tr').data('key');
            obj.userSid = <?=$user_sid;?>;
            obj.userType = "<?=$user_type;?>";
            //
            $.post(
                "<?=base_url("hr_documents_management/handler");?>",
                obj,
                (resp) => {
                    //
                    nl(false);
                    //
                    if(resp.Status === false){
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
                        () => {}
                    );
                    //
                    target
                    .text('Revoke')
                    .prop('title', 'Revoke this document')
                    .data('type', 'revoke')
                    .removeClass('btn-success')
                    .removeClass('btn-warning')
                    .addClass('btn-danger');
                    //
                    target
                    .closest('tr')
                    .find('img')
                    .prop('src', "<?=base_url('assets/manage_admin/images');?>/off.gif")
                    .prop('title', 'Pending');
                    //
                    target
                    .closest('tr')
                    .find('.jsAssignedOn')
                    .html(`<i class="fa fa-check fa-2x text-success"></i> <br />${moment(resp.Date).format('MMM Do YYYY, ddd H:m:s')}`);
                    //
                    let o = {
                        c: $(`.jsGeneralRowCompleted${obj.documentType}`).length,
                        n: $(`.jsGeneralRowNotCompleted${obj.documentType}`).length
                    };
                    //
                    $('.js-ncd').text( parseInt($('.js-ncd').text()) - o.n );
                    $('.js-cd').text( parseInt($('.js-cd').text()) - o.c );
                    //
                    //
                    $(`.jsGeneralRowCompleted${obj.documentType}`).remove();
                    $(`.jsGeneralRowNotCompleted${obj.documentType}`).remove();
                    //
                    let row = `
                    <tr class="jsGeneralRowCompleted${obj.documentType}">
                        <td>
                            ${obj.documentType.replace(/_/, ' ').ucwords()} <br />
                            <strong>Assigned on: </strong> ${moment(resp.Date).format('MMM Do YYYY, ddd H:m:s')}
                        </td>
                        <td>
                            <a class="btn btn-success btn-sm btn-block" href="<?=base_url();?>/${typeToUrl[obj.documentType]}">View Doc</a>
                        </td>
                    </tr>
                    `;
                    //
                    setNotCompletedView(row, 1);
                    //
                    if( $('#collapse_general_documents_notcompleted tbody tr').length == 0 ){
                        $('#collapse_general_documents_notcompleted').closest('.jsGDR').remove();
                    }
                    //
                    if( $('#collapse_general_documents_completed tbody tr').length == 0 ){
                        $('#collapse_general_documents_completed').closest('.jsGDR').remove();
                    }
                }
            );
        }

        //
        function handleRevokeDocument(target){
            //
            nl();
            //
            let obj = {};
            obj.action = "revoke_document";
            obj.companySid = <?=$company_sid;?>;
            obj.companyName = "<?=$company_name;?>";
            obj.documentType = target.closest('tr').data('id');
            obj.sid = target.closest('tr').data('key');
            obj.userSid = <?=$user_sid;?>;
            obj.userType = "<?=$user_type;?>";
            //
            $.post(
                "<?=base_url("hr_documents_management/handler");?>",
                obj,
                (resp) => {
                    //
                    nl(false);
                    //
                    if(resp.Status === false){
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
                        () => {}
                    );
                    //
                    target
                    .text('Reassign')
                    .prop('title', 'Assign this document')
                    .data('type', 'assign')
                    .removeClass('btn-danger')
                    .addClass('btn-warning');
                    //
                    target
                    .closest('tr')
                    .find('img')
                    .prop('src', "<?=base_url('assets/manage_admin/images');?>/off.gif")
                    .prop('title', 'Pending');
                    //
                    target
                    .closest('tr')
                    .find('img')
                    .parent()
                    .find('span')
                    .remove();
                    //
                    target
                    .closest('tr')
                    .find('.jsAssignedOn')
                    .html(`<i class="fa fa-close fa-2x text-danger"></i>`);
                    //
                    let o = {
                        c: $(`.jsGeneralRowCompleted${obj.documentType}`).length,
                        n: $(`.jsGeneralRowNotCompleted${obj.documentType}`).length
                    };
                    //
                    $('.js-ncd').text( parseInt($('.js-ncd').text()) - o.n );
                    $('.js-cd').text( parseInt($('.js-cd').text()) - o.c );
                    //
                    $(`tr.jsGeneralRowCompleted${obj.documentType}`).remove();
                    $(`tr.jsGeneralRowNotCompleted${obj.documentType}`).remove();
                    //
                    if( $('#collapse_general_documents_notcompleted tbody tr').length == 0 ){
                        $('#collapse_general_documents_notcompleted').closest('.jsGDR').remove();
                    }
                    //
                    if( $('#collapse_general_documents_completed tbody tr').length == 0 ){
                        $('#collapse_general_documents_completed').closest('.jsGDR').remove();
                    }
                }
            );
        }

        //
        function getGeneralDocumentStatus(){
            //
            let obj = {};
            obj.action = "get_documents";
            obj.companySid = <?=$company_sid;?>;
            obj.companyName = "<?=$company_name;?>";
            obj.userSid = <?=$user_sid;?>;
            obj.userType = "<?=$user_type;?>";
            //
            $.post(
                "<?=base_url("hr_documents_management/handler");?>",
                obj,
                (resp) => {
                    //
                    resetData(resp.Data);
                    setView();
                }
            );
        }

        //
        function resetData(data){
            //
            let t = {};
            //
            if(data.length === 0){
                if (direct_deposit_flag == 1) {
                    t['direct_deposit'] = getDefaultObj('direct_deposit');
                }
                if (drivers_license_flag == 1) {
                    t['drivers_license'] = getDefaultObj('drivers_license');
                }
                if (occupational_license_flag == 1) {
                    t['occupational_license'] = getDefaultObj('occupational_license');
                }
                if (emergency_contacts_flag == 1) {    
                    t['emergency_contacts'] = getDefaultObj('emergency_contacts');
                }
                if (dependents_flag == 1) {
                    t['dependents'] = getDefaultObj('dependents');
                }  
            } else{
                //
                let t1 = {};
                //
                $.each(data, (i, v) => { t1[v.document_type] = v; });
                //
                if (direct_deposit_flag == 1) {
                        if (t1['direct_deposit'] === undefined) t1['direct_deposit'] = getDefaultObj('direct_deposit');
                }
                if (drivers_license_flag == 1) {
                    if (t1['drivers_license'] === undefined) t1['drivers_license'] = getDefaultObj('drivers_license');
                }
                if (occupational_license_flag == 1) {
                    if (t1['occupational_license'] === undefined) t1['occupational_license'] = getDefaultObj('occupational_license');
                }
                if (emergency_contacts_flag == 1) {    
                    if (t1['emergency_contacts'] === undefined) t1['emergency_contacts'] = getDefaultObj('emergency_contacts');
                }
                if (dependents_flag == 1) {
                    if (t1['dependents'] === undefined) t1['dependents'] = getDefaultObj('dependents');
                } 
                //
                t = t1;
            }
            //
            const ordered = {};
            Object.keys(t).sort().forEach(function(key) { ordered[key] = t[key]; });
            //
            documentOBJ = ordered;
        }
        
        //
        function setView(){
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
                if(v.sid != 0){
                    if(v.status == 1){
                        btnClass = 'btn-danger';
                        btnText = 'Revoke';
                        btnType = 'revoke';
                    } else {
                        btnClass = 'btn-warning';
                        btnText = 'Reassign';
                        btnType = 'assign';
                    }
                }

                // Set print & download buttons
                let printBTN = `<a title="Print ${v.document_type.fix()}" href="<?=base_url('hr_documents_management/gpd/print');?>/${v.document_type}/<?=$user_type.'/'.$user_sid;?>" target="_blank" class="btn btn-info">Print</a>`;
                let downloadBTN = `<a title="Download ${v.document_type.fix()}" href="<?=base_url('hr_documents_management/gpd/print');?>/${v.document_type}/<?=$user_type.'/'.$user_sid;?>" target="_blank" class="btn btn-info">Download</a>`;

                //
                if(v.is_completed == 1){
                    completedDocs += `
                        <tr class="jsGeneralRowCompleted${v.document_type}">
                            <td class="">
                                ${v.document_type.fix()} ${v.is_required == 1 ? ' <i class="fa fa-asterisk jsTooltip" title="You must complete this document to finish the onboarding process." style="color: #cc1100;" aria-hidden="true"></i>' : ''} <br />
                                <strong>Assigned on: </strong> ${moment(v.assigned_at).format('MMM Do YYYY, ddd H:m:s')} <br />
                                <strong>Completed on: </strong> ${moment(v.updated_at).format('MMM Do YYYY, ddd H:m:s')}
                            </td>
                            <td class="">
                                ${printBTN}
                                ${downloadBTN}
                                <a class="btn btn-info" href="<?=base_url();?>/${typeToUrl[v.document_type]}">View Sign</a>
                            </td>
                        </tr>
                    `;
                    completedDocsCount++;
                } else if(v.status == 1){
                    notCompletedDocs += `
                        <tr class="jsGeneralRowNotCompleted${v.document_type}">
                            <td class="">
                                ${v.document_type.replace(/_/, ' ').ucwords()} ${v.is_required == 1 ? ' <i class="fa fa-asterisk jsTooltip" title="You must complete this document to finish the onboarding process." style="color: #cc1100;" aria-hidden="true"></i>' : ''} <br />
                                <strong>Assigned on: </strong> ${moment(v.assigned_at).format('MMM Do YYYY, ddd H:m:s')}
                            </td>
                            <td class="">
                                ${printBTN}
                                ${downloadBTN}
                                <a class="btn btn-info" href="<?=base_url();?>/${typeToUrl[v.document_type]}">View Sign</a>
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
            //
            loadTT();
        }

        //
        function getDefaultObj(documentType){
            return {
                sid: 0,
                company_sid: <?=$company_sid;?>,
                user_sid: <?=$user_sid;?>,
                user_type: "<?=$user_type;?>",
                document_type: documentType,
                status: 0,
                is_completed: 0
            };
        }

        //
        function nl(s){
            if(s === undefined) $('.jsDocumentLoader').show();
            else $('.jsDocumentLoader').hide();
        }


        //
        function setCompletedView(rows, cc){
            if(cc == 0) return;
             //
             if($('#collapse_general_documents_completed').length > 0){
                $('#collapse_general_documents_completed tbody').append(rows);
                //
                $('.js-ncd').text(
                    parseInt($('.js-ncd').text()) + cc
                );
                return;
            }
            //
            let bb = `
            <div class="row jsGDR">
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
                    </div>
                </div>
            </div>
            `;
            $('#completed_doc_details').append(bb);
            $('#signed_doc_details').append(bb);
            //
            $('.js-cd').text(
                parseInt($('.js-cd').text()) + cc
            );
        }
        
        //
        function setNotCompletedView(rows, cc){
            if(cc == 0) return;
            //
            if($('#collapse_general_documents_notcompleted').length > 0){
                $('#collapse_general_documents_notcompleted tbody').append(rows);
                //
                $('.js-ncd').text(
                    parseInt($('.js-ncd').text()) + cc
                );
                return;
            }
            //
            let bb = `
            <div class="row jsGDR">
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

                        <div id="collapse_general_documents_notcompleted" class="panel-collapse collapse  in">
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