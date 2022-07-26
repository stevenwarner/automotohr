/**
 * @author: Mubashir Ahmed
 * @version: 1.0
 * @package: Approval flow
 * @description: .
 * @example:
 * 
 *  $("#reference").documentApprovalFlow({
 *     appCheckboxIdx: '.jsHasApprovalFlow1', //
 *     containerIdx: '.jsApproverFlowContainer1', //
 *     addEmployeeIdx: '.jsAddDocumentApprovers1', //
 *     intEmployeeBoxIdx: '.jsEmployeesadditionalBox1', //
 *     extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBox1', //
 *     approverNoteIdx: '.jsApproversNote1', // 
 *     employeesList: employee_list, //
 *     prefill:{ // optional
 *        isChecked: true,
 *        approverNote: "Sdasdas",
 *        approversList: [15777, 15778]
 *     },
 *     document_id: 0 //
 *  });
 *
 * 
 *  $('#reference').documentApprovalFlow('get'); // It will return the approver object which includes .
 */

(function ($) {
    //
    let instances = {};
    //
    $.fn.documentApprovalFlow = function (opt) {
        // Save the current instance
        let _this = this.length > 1 ? this[0] : this;
        //
        if (typeof opt === 'string') {
            switch (opt) {
                case "get":
                    return instances[_this.selector];
                case "clear":
                    return delete instances[_this.selector];
            }
            return;
        }
        //
        let
            returnObj = {
                isChecked: false,
                approverNote: "",
                approversList: []
            },
            //
            selectedApprovers = {},
            //
            options = {};
        //
        instances[_this.selector] = returnObj;
        //
        options['appCheckboxIdx'] = opt.appCheckboxIdx !== undefined ? opt.appCheckboxIdx : ".jsCheckBox";
        options['containerIdx'] = opt.containerIdx !== undefined ? opt.containerIdx : ".jsMainContainer";
        options['addEmployeeIdx'] = opt.addEmployeeIdx !== undefined ? opt.addEmployeeIdx : ".jsAddApprovers";
        options['intEmployeeBoxIdx'] = opt.intEmployeeBoxIdx !== undefined ? opt.intEmployeeBoxIdx : ".jsIntApproverContainer";
        options['extEmployeeBoxIdx'] = opt.extEmployeeBoxIdx !== undefined ? opt.extEmployeeBoxIdx : ".jsExtApproverContainer";
        options['approverNoteIdx'] = opt.approverNoteIdx !== undefined ? opt.approverNoteIdx : ".jsApproverNoteContainer";
        options['employeesList'] = opt.employeesList !== undefined ? opt.employeesList : [];
        options['documentId'] = opt.documentId !== undefined ? opt.documentId : 0;
        //
        // makeView is a function which is used to prefill previous approver data
        this.makeView = function () {
            //
            var obj = instances[_this.selector];
            //
            $(options['appCheckboxIdx']).prop('checked', obj.isChecked);
            $(options['approverNoteIdx']).val(obj.approverNote);
            $(options['intEmployeeBoxIdx']).html('');
            $(options['containerIdx']).show();
            //
                console.log(obj.approversList)
            if (obj.approversList.length > 0) {    
                obj.approversList.map(function (apId) {
                    if (apId.length > 0) {
                        var rowId = options['prefix'] + 'js-employees-' + _this.getRandom();
                        var row = _this.generateApproverRow(rowId, options['documentId'], apId);
                        //
                        $(options['intEmployeeBoxIdx']).append(row);

                        $('#' + rowId).select2();
                        //
                        $('#' + rowId).select2('val', apId);
                        selectedApprovers[apId] = true;
                    }
                });
            }    
        };
        //
        // Only generate rendom keyword
        this.getRandom = function () {
            return Math.round((Math.random() * 10000) + 1);
        };
        //
        // Collect all Approver flow info and rape into object
        this.makeObj = function () {
            // Reset the object
            returnObj = {
                isChecked: false,
                approverNote: "",
                approversList: []
            };
            //
            if ($(options['appCheckboxIdx']).prop("checked")) {
                returnObj.isChecked = true;
                returnObj.approverNote = $(options['approverNoteIdx']).val();
                returnObj.approversList = Object.keys(selectedApprovers) || [];
            }

            instances[_this.selector] = returnObj;
        };
        //
        // generates Employee select box
        this.generateApproverRow = function (rowId, documentId, approverID) {
            //
            var rows = '';
            rows += '<div class="row row_id">';
            rows += '   <br />';
            rows += '    <div class="cs-employee js-employee csMT">';
            rows += '        <div class="col-sm-10 col-sm-offset-0 text-left">';
            rows += '           <select id="' + (rowId) + '" name="approvers_list[]" class="jsSelectedEmployee">';
            rows += '               <option value="0" >Please Select an Employee</option>';
            if (options['employeesList'].length) {
                options['employeesList'].map(function (v) {
                    if (selectedApprovers[v.sid] === undefined) {
                        rows += '<option value="' + (v['sid']) + '">' + (_this.remakeEmployeeName(v)) + '</option>';
                    }
                });
            }
            rows += '           </select>';
            rows += '        </div>';
            rows += '        <div class="col-sm-2 col-sm-offset-0 text-right">';
            rows += '            <a href="javascript:;" class="btn btn-danger js-employee-delete-btn"><i class="fa fa-trash"></i>delete </a>';
            rows += '        </div>';
            rows += '    </div>';
            rows += '<br />';
            rows += '<br />';
            if (documentId != 0 && documentId != undefined) {
                rows += '    <div class="col-sm-12" style="margin-top:8px;">';
                rows += '         <a href="javascript:;" class="btn btn-success btn-xs jsApproverViewStatus" data-doc_sid="' + documentId + '" data-app_sid="' + approverID + '" data-row_sid="' + rowId + '"><i class="fa fa-eye" style="font-size: 12px;"></i> Show Detail</a>';
                rows += '    </div>';
                rows += '    <div class="col-sm-12 jsApproverViewStatusBox" id="jsApproverViewStatus-' + rowId + '" style="display:none;">';
                rows += '    </div>';
            }
            rows += '</div>';

            //
            return rows;
        };
        //
        // Remake Employee Name
        this.remakeEmployeeName = function (o, i) {
            //
            var r = '';
            //
            if (i == undefined) r += o.first_name + ' ' + o.last_name;
            //
            if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
            //
            r += ' [';
            //
            if (typeof (o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
            //
            if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
            else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
            else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
            else r += o['access_level'];
            //
            r += ']';
            //
            return r;
        };
        //
        options['prefix'] = this.getRandom();
        //
        if (opt.prefill !== undefined) {
            //
            instances[_this.selector] = opt.prefill;
            //
            this.makeView();
        }
        //
        // Events
        // This is document approval checkbox event
        $(document).on('click', options['appCheckboxIdx'], function () {
            //
            _this.makeObj();
            //
            if ($(this).prop('checked')) {
                //
                $(options['containerIdx']).show();
            } else {
                //
                $(options['containerIdx']).hide(0);
                $(options['intEmployeeBoxIdx']).html('');
                $(options['approverNoteIdx']).val('');
            }
        });
        //
        // This is add new approver event
        // $(document).on('click', options['addEmployeeIdx'], function (event) {
        $(options['addEmployeeIdx']).on('click', function (event) {
            //
            $(options['approverNoteIdx']).show();
            var rowId = options['prefix'] + 'js-employees-' + _this.getRandom();
            var row = _this.generateApproverRow(rowId, 0, 0);
            //
            $(options['intEmployeeBoxIdx']).append(row);
            //
            $('#' + rowId).select2({
                closeOnSelect: true,
                allowHtml: true,
                allowClear: true,
            });
        });
        //
        // This is an event which trigger when we select an approver form employee list
        $(document).on('select2:select', '.jsSelectedEmployee', function () {
            //
            selectedApprovers[$(this).val()] = true;
            //
            _this.makeObj();
        });
        //
        // This is an event when some one write note for approvers
        $(document).on('keyup', options['approverNoteIdx'], _this.makeObj);
        //
        // this is an event to delete selectd approver or delete employee row
        $(document).on('click', '.js-employee-delete-btn', function (e) {
            //
            e.preventDefault();
            //
            if ($(this).closest('.row_id').find('.jsSelectedEmployee').val() == 0) {
                return $(this).closest('.row_id').remove();
            }
            //
            var __this = $(this);
            //
            alertify.confirm('Do you want to delete this approver?', function () {
                __this.closest('.row_id').remove();
                delete selectedApprovers[__this.closest('.row_id').find('.jsSelectedEmployee').val()];
                _this.makeObj();
                console.log(instances[_this.selector]);
            });
        });

    };
    //
    $.fn.documentApprovalFlow.__proto__.instances = instances;
    //
    return this;
})(jQuery || $);