/**
 * @author: Mubashir Ahmed
 * @version: 1.0
 * @package: File uploader (documentApproval)
 * @description: Upload files using drag an drop / choose from file.
 *               It depends on jQuery.
 * @example:
 * 
 *  $('#fileInputReference').documentApproval({
 *      fileLimit: '2MB', // Default is '2MB', Use -1 for no limit (Optional)
 *      allowedTypes: ['jpg','jpeg','png','gif','pdf','doc','docx','rtf','ppt','xls','xlsx','csv'],  (Optional)
 *      text: 'Click / Drag to upload', // (Optional)
 *      onSuccess: (file, event) => {}, // file will the uploaded file object and event will be the actual event  (Optional)
 *      onError: (errorCode, event) => {}, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
 *      placeholderImage: '' // Default is empty ('') but can be set any image  (Optional)
 *  })
 * 
 *  $('#fileInputReference').documentApproval('get'); // It will return the uploaded file object
 *                                                    with an addition index of 'hasError'.
 *                                                    hasError will be true there was an error
 *                                                    with file and false when everything is okay.
 */

(function($) {
    //
    let instances = {};
    //
    $.fn.documentApproval = function(opt) {
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
        options['employeeList'] = opt.employeesList !== undefined ? opt.employeesList : [];
        options['prefix'] = getRandom();

        // 
        console.log(options)
        $(document).on('click',  options['appCheckbox_idx'], function() {makeObj()
            //
            console.log($(this).prop('checked'))
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
        $(document).on('click', options['addEmployee_idx'], function(event) {
            //
            $(options['approverNote_idx']).show();
            var rowId = options['prefix']+'js-employees-' + getRandom();
            var row = generateApproverRow(rowId, 0);
            //
            $(options['intEmployeeBox_idx']).append(row);

            $('#'+rowId).select2({
                closeOnSelect: false,
                allowHtml: true,
                allowClear: true,
            });

        });
        //
        $(document).on('select2:select', '.jsSelectedEmployee', function(event) {
            //
            selectedApprovers[$(this).val()] = true;
        });
        //
        $(document).on('click', '.js-employee-delete-btn', function(e) {
            //
            e.preventDefault();
            //
            if ($(this).closest('.row_id').find('.jsSelectedEmployee').val() == 0) {
                return $(this).closest('.row_id').remove();
            }
            //
            var _this = $(this);
            //
            alertify.confirm('Do you want to delete this approver?', function() {
                _this.closest('.row_id').remove();
                delete selectedApprovers[_this.closest('.row_id').find('.jsSelectedEmployee').val()];
            });
        });
        //
        function getRandom () {
            return Math.round((Math.random() * 10000) + 1);
        }
        //
        // generates row
        function generateApproverRow(rowId, documentId) {
            //
            var rows = '';
            rows += '<div class="row row_id">';
            rows += '<br />';
            rows += '    <div class="cs-employee js-employee csMT">';
            rows += '        <div class="col-sm-10 col-sm-offset-0 text-left">';
            rows += '           <select id="' + (rowId) + '" name="assigner[]" class="jsSelectedEmployee">';
            rows += '               <option value="0" >Please Select an Employee</option>';
            if(options['employeeList'].length){
                options['employeeList'].map(function(v){
                    rows +='<option value="'+(v['sid'])+'">'+(remakeEmployeeName(v))+'</option>';
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
                rows += '         <a href="javascript:;" class="btn btn-success btn-xs jsApproverViewStatus" data-doc_sid="'+documentId+'" data-app_sid="'+approverID+'" data-row_sid="'+rowId+'"><i class="fa fa-eye" style="font-size: 12px;"></i> Show Detail</a>';
                rows += '    </div>';
                rows += '    <div class="col-sm-12 jsApproverViewStatusBox" id="jsApproverViewStatus-'+rowId+'" style="display:none;">';
                rows += '    </div>';
            }
            rows += '</div>';

            //
            return rows;
        }

        function addApproverRow(row, rowId){
            $('.jsEmployeesadditionalBox').prepend(row);
            //
            if (Object.keys(selectedApprovers).length) {
                Object.keys(selectedApprovers).map(function(sa) {
                    $('.js-employee-' + (rowId) + ' option[value="' + (sa) + '"]').remove();
                });
            }
            //
            $('#js-employees-' + rowId).select2({
                closeOnSelect: false,
                allowHtml: true,
                allowClear: true,
            });
        }

        function remakeEmployeeName(o, i) {
            //
            var r = '';
            //
            if (i == undefined) r += o.first_name + ' ' + o.last_name;
            //
            if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
            //
            r += ' [';
            //
            if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
            //
            if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
            else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
            else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
            else r += o['access_level'];
            //
            r += ']';
            //
            return r;
        }

        function makeObj () {

            returnObj = {
                isChecked: false,
                approverNote: "",
                approversList: []
            };
            //
            if ($(options['appCheckbox_idx']).prop("checked")) {
                returnObj.isChecked = true;
                returnObj.approverNote = $(options['approverNote_idx']).val();
                returnObj.approversList = selectedApprovers;
            }
            //
            console.log(returnObj)
        }
    };
    //
    return this;
    //
    $.fn.documentApproval.__proto__.instances = instances;
})(jQuery || $);