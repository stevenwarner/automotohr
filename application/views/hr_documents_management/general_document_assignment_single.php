<?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1){ ?>
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


<!-- General Document Note Model -->
<div class="modal fade" id="jsGeneralNoteModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #81b431; color: #fff;">
                <h5 class="modal-title">Note</h5>
            </div>
            <div class="modal-body" style="word-break: break-all;">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url('assets/js/moment.min.js');?>"></script>
<!--  -->
<script>
    $(function(){
        //
        let companySid = <?=$companySid;?>;
        let userSid = <?=$userSid;?>;
        let userType = "<?=$userType;?>";
        let docmentOBJ = {};
        let actionType = "<?=$generalActionType;?>";
        let slugToName = {
            'direct_deposit': 'Direct Deposit Information',
            'drivers_license': 'Drivers License Information',
            'occupational_license': 'Occupational License Information',
            'emergency_contacts': 'Emergency Contacts',
            'dependents': 'Dependents'
        };
        let xhr = null;
        let _this = null;
        //
        setTimeout(() => {
            getGeneralDocumentStatus();
        }, 1000);
        //
        $(document).on('click', '.jsGeneralAssignModelBTN', (e) => {
            e.preventDefault();
            //
            let obj = {};
            //
            obj.note = CKEDITOR.instances['jsGeneralAssignModelNote'].getData();
            obj.sendEmail = $('#jsGeneralAssignModelSEN:checked').val();
            //
            handleAssignDocument(_this, obj);
        });

        // Click on assign button
        $(document).on('click', '.jsHandleAssign', function(e){
            //
            e.preventDefault();
            //
            switch($(this).data('type').toLowerCase()){
                //
                case "assign":
                    _this = $(this);
                    //
                    if(CKEDITOR.instances['jsGeneralAssignModelNote'] === undefined) CKEDITOR.replace('jsGeneralAssignModelNote');
                    else CKEDITOR.instances['jsGeneralAssignModelNote'].setData('');
                    //
                    $('#jsGeneralAssignModelSEN[value="1"]').prop('checked', true);
                    //
                    $('#jsGeneralAssignModel').find('.modal-title').html(`<strong>Assign ${actionType.replace(/_/, ' ').ucwords()}</strong>`);
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
        $(document).on('click', '.jsGeneralDocumentHistory', function(e){
            //
            e.preventDefault();
            //
            if(xhr !== null) return;
            //
            let obj = {};
            obj.action = "get_general_document_history";
            obj.generalDocumentSid = $(this).data('sid');
            obj.companySid = companySid;
            obj.userType = userType;
            //
            xhr = $.post(
                "<?=base_url("hr_documents_management/handler");?>",
                obj,
                (resp) => {
                    //
                    xhr = null;
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
                    $('#jsGeneralHistoryModel').find('.modal-title').html(`<strong>${lln} History</strong>`);
                    $('#jsGeneralHistoryModalBody').html(rows);
                    $('#jsGeneralHistoryModel').modal('show');
                }
            );
        });

        //
        function handleAssignDocument(target, obj){
            obj.action = "assign_document";
            obj.documentType = actionType;
            obj.sid = target.data('sid');
            obj.userSid = userSid;
            obj.userType = userType;
            obj.companySid = companySid;
            obj.sendNotificationEmail = true;
            //
            $('#jsGeneralAssignModel').modal('hide');
            //
            $.post(
                "<?=base_url("hr_documents_management/handler");?>",
                obj,
                (resp) => {
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
                    .text(`Revoke ${lln}`)
                    .prop('title', 'Revoke this document')
                    .data('type', 'revoke')
                    .removeClass('btn-success')
                    .removeClass('btn-warning')
                    .addClass('btn-danger');
                }
            );
        }

        //
        function handleRevokeDocument(target){
            //
            let obj = {};
            obj.action = "revoke_document";
            obj.documentType = actionType;
            obj.sid = target.data('sid');
            obj.userSid = userSid;
            obj.companySid = companySid;
            obj.userType = userType;
            //
            $.post(
                "<?=base_url("hr_documents_management/handler");?>",
                obj,
                (resp) => {
                    //
                    
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
                    .text(`Reassign  ${lln}`)
                    .prop('title', 'Reassign this document')
                    .data('type', 'assign')
                    .removeClass('btn-danger')
                    .addClass('btn-warning');
                    //
                    $('.jsNote').remove();
                }
            );
        }

        //
        function getGeneralDocumentStatus(){
            //
            let obj = {};
            obj.action = "get_documents";
            obj.userSid = userSid;
            obj.userType = userType;
            obj.companySid = companySid;
            obj.type = actionType;
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
                t[actionType] = getDefaultObj(actionType);
            } else{
                //
                let t1 = {};
                //
                $.each(data, (i, v) => { t1[v.document_type] = v; });
                //
                if(t1[actionType] === undefined) t1[actionType] = getDefaultObj(actionType);
                //
                t = t1;
            }
            //
            const ordered = {};
            Object.keys(t).sort().forEach(function(key) { ordered[key] = t[key]; });
            //
            documentOBJ = ordered;
        }

        // ucwords
        String.prototype.ucwords = function() {
            str = this.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function(s){
                return s.toUpperCase();
            });
        };
        //
        let lln = actionType.replace(/_/, ' ').ucwords();
        let ssc = null;
        //
        function setView(){
            //
            let rows = '';
            //
            let 
            btnClass = 'btn-success',
            btnText = `Assign  ${lln}`,
            btnTitle = `Assign ${actionType.replace(/_/, ' ')}`,
            btnType = 'assign';
            //
            let ll = documentOBJ[actionType];
            //
            if(ll.sid != 0){
                if(ll.status == 1){
                    btnClass = 'btn-danger';
                    btnText = `Revoke ${lln}`;
                    btnType = 'revoke';
                    btnTitle = `Revoke ${actionType.replace(/_/, ' ')}`;
                } else {
                    btnClass = 'btn-warning';
                    btnText = `Reassign ${lln}`;
                    btnType = 'assign';
                    btnTitle = `Reassign ${actionType.replace(/_/, ' ')}`;
                }
            }
            //
            if(ll.note != '' && ll.note != null && ll.status == 1){
                //
                $('#jsGeneralNoteModel .modal-body').html(ll.note);
                //
                rows += `<button class="btn btn-success jsNote" style="margin-right: 10px;" data-toggle="popover" data-placement="left" data-content="Click to see note"><i class="fa fa-sticky-note-o"></i></button>`;
            }
            //
            rows += `<button class="btn ${btnClass} jsHandleAssign" data-sid="${ll.sid}" data-type="${btnType}" title="${btnTitle}">${btnText}</button>`;
            //
            if(ll.sid != 0)
            rows += `<button  style="margin-left: 10px;" class="btn btn-success jsGeneralDocumentHistory" data-sid="${ll.sid}" title="Assign/Revoke history">History</button>`;
            //
            $('#jsGeneralDocumentArea').html(rows);
            //
            ssc = $('.jsNote');
            ssc.popover('show');
        }

        // data-type="'+slugToName[v.document_type]+'"
        function getDefaultObj(documentType){
            return {
                sid: 0,
                company_sid: companySid,
                user_sid: userSid,
                user_type: userType,
                document_type: documentType,
                status: 0,
                note: '',
                is_completed: 0
            };
        }
        //
        $(document).on('click', '.jsNote', (e) => {
            e.preventDefault();
            $('#jsGeneralNoteModel').modal('show');
        });
        //
        $(document).on('show.bs.modal', '#jsGeneralNoteModel', function(){ ssc.popover('hide'); });
        $(document).on('hide.bs.modal', '#jsGeneralNoteModel', function(){ ssc.popover('show'); });
        //
    });
</script>
<?php } ?>