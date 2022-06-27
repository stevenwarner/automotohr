/**
 * @author: Mubashir Ahmed
 * @version: 1.0
 * @package: Approval flow
 * @description: Upload files using drag an drop / choose from file.
 *               It depends on jQuery.
 * @example:
 * 
 *    $("#reference").documentApprovalFlow({
 *            appCheckbox_idx: '.jsHasApprovalFlow1', //
 *            container_idx: '.jsApproverFlowContainer1', //
 *            addEmployee_idx: '.jsAddDocumentApprovers1', //
 *            intEmployeeBox_idx: '.jsEmployeesadditionalBox1', //
 *            extEmployeeBox_idx: '.jsEmployeesadditionalExternalBox1', //
 *            approverNote_idx: '.jsApproversNote1', // 
 *            employees_list: employee_list, //
 *            prefill:{ // optional
 *                isChecked: true,
 *                approverNote: "Sdasdas",
 *                approversList: [15777, 15778]
 *            },
 *            document_id: 0 //
 *        });
 *  })
 * 
 *  $('#reference').documentApprovalFlow('get'); // It will return the uploaded file object
 *                                                    with an addition index of 'hasError'.
 *                                                    hasError will be true there was an error
 *                                                    with file and false when everything is okay.
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
        options['appCheckbox_idx'] = opt.appCheckbox_idx !== undefined ? opt.appCheckbox_idx : ".jsCheckBox";
        options['container_idx'] = opt.container_idx !== undefined ? opt.container_idx : ".jsMainContainer";
        options['addEmployee_idx'] = opt.addEmployee_idx !== undefined ? opt.addEmployee_idx : ".jsAddApprovers";
        options['intEmployeeBox_idx'] = opt.intEmployeeBox_idx !== undefined ? opt.intEmployeeBox_idx : ".jsIntApproverContainer";
        options['extEmployeeBox_idx'] = opt.extEmployeeBox_idx !== undefined ? opt.extEmployeeBox_idx : ".jsExtApproverContainer";
        options['approverNote_idx'] = opt.approverNote_idx !== undefined ? opt.approverNote_idx : ".jsApproverNoteContainer";
        options['employees_list'] = opt.employees_list !== undefined ? opt.employees_list : [];
        options['document_id'] = opt.document_id !== undefined ? opt.document_id : 0;

        //
        this.makeView = function () {
            //
            var obj = instances[_this.selector];
            //
            $(options['appCheckbox_idx']).prop('checked', obj.isChecked);
            $(options['approverNote_idx']).val(obj.approverNote);
            $(options['intEmployeeBox_idx']).html('');
            $(options['container_idx']).show();
            //
            obj.approversList.map(function (apId) {
                var rowId = options['prefix'] + 'js-employees-' + _this.getRandom();
                var row = _this.generateApproverRow(rowId, 0);
                //
                $(options['intEmployeeBox_idx']).append(row);

                $('#' + rowId).select2();
                //
                $('#' + rowId).select2('val', apId);
                selectedApprovers[apId] = true;
            });
        };

        //
        this.getRandom = function () {
            return Math.round((Math.random() * 10000) + 1);
        };

        this.makeObj = function () {
            // Reset the object
            returnObj = {
                isChecked: false,
                approverNote: "",
                approversList: []
            };
            //
            if ($(options['appCheckbox_idx']).prop("checked")) {
                returnObj.isChecked = true;
                returnObj.approverNote = $(options['approverNote_idx']).val();
                returnObj.approversList = Object.keys(selectedApprovers) || [];
            }

            instances[_this.selector] = returnObj;
        };

        // generates row
        this.generateApproverRow = function (rowId, documentId) {
            //
            var rows = '';
            rows += '<div class="row row_id">';
            rows += '   <br />';
            rows += '    <div class="cs-employee js-employee csMT">';
            rows += '        <div class="col-sm-10 col-sm-offset-0 text-left">';
            rows += '           <select id="' + (rowId) + '" class="jsSelectedEmployee">';
            rows += '               <option value="0" >Please Select an Employee</option>';
            if (options['employees_list'].length) {
                options['employees_list'].map(function (v) {
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

        options['prefix'] = this.getRandom();

        //
        if (opt.prefill !== undefined) {
            //
            instances[_this.selector] = opt.prefill;
            //
            this.makeView();
        }

        // Events
        // 
        $(document).on('click', options['appCheckbox_idx'], function () {
            //
            _this.makeObj();
            //
            if ($(this).prop('checked')) {
                //
                $(options['container_idx']).show();
            } else {
                //
                $(options['container_idx']).hide(0);
                $(options['intEmployeeBox_idx']).html('');
                $(options['approverNote_idx']).val('');
            }
        });

        //
        $(document).on('click', options['addEmployee_idx'], function (event) {
            //
            $(options['approverNote_idx']).show();
            var rowId = options['prefix'] + 'js-employees-' + _this.getRandom();
            var row = _this.generateApproverRow(rowId, 0);
            //
            $(options['intEmployeeBox_idx']).append(row);

            $('#' + rowId).select2({
                closeOnSelect: true,
                allowHtml: true,
                allowClear: true,
            });

        });

        //
        $(document).on('select2:select', '.jsSelectedEmployee', function () {
            //
            selectedApprovers[$(this).val()] = true;
            //
            _this.makeObj();
        });
        //
        $(document).on('keyup', options['approverNote_idx'], _this.makeObj);
        //
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
            });
        });

    };
    //
    $.fn.documentApprovalFlow.__proto__.instances = instances;
    //
    return this;
})(jQuery || $);