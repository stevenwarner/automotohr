<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('hr_documents_management'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Document Management
                        </a>
                        <?php echo $title; ?>
                    </span>
                </div>
                <div class="btn-panel text-right">
                    <div class="row">
                        <a class="btn btn-success" href="<?php echo base_url('hr_documents_management/add_edit_document_category_management'); ?>">+ Document Category</a>
                    </div>
                </div>
                <div class="dashboard-conetnt-wrp">
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">   
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-lg-3">Category Name</th>
                                        <th class="col-lg-3">Category Description</th> 
                                        <th class="col-lg-3">Status</th>
                                        <th class="col-lg-3 text-center" colspan="3">Actions</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php if (!isset($categories) || empty($categories)) { ?>
                                        <tr>
                                            <span class="applicant-not-found">No document category found</span>
                                        <tr>
                                    <?php } else { 
                                            foreach ($categories as $category) { ?>
                                            <tr id='row_<?php echo $category['sid']; ?>'>
                                                <td><?php echo ucwords($category['name']); ?></td>
                                                <td><?php echo html_entity_decode($category['description']); ?></td>
                                                <td><?php echo $category['status'] == 1 ? '<b class="paid">Active</b>' : '<b class="unpaid">Inactive</b>'; ?></td>
                                                <!-- <td>
                                                    <?php //if ($category['document_status'] == 1 || $category['w4'] == 1 || $category['w9'] == 1 || $category['i9'] == 1) { ?>
                                                        <button onclick="func_assign_document_category(<?php echo $category['sid']; ?>)" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                    <?php //} else { ?>
                                                        No Document Found
                                                    <?php //} ?>
                                                </td> -->
                                                <?php if($category['sid'] != PP_CATEGORY_SID){?>
                                                    <td>
                                                        <a href="<?php echo base_url('hr_documents_management/add_edit_document_category_management').'/'.$category['sid']; ?>" class="btn btn-info btn-sm">
                                                            Edit Category
                                                        </a>
                                                    </td>
                                                <?php }?>
                                                <td>
                                                    <a href="<?php echo base_url('hr_documents_management/document_2_category').'/'.$category['sid']; ?>" class="btn btn-success btn-sm" >
                                                        Assign Document
                                                    </a> 
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($categories) || !empty($categories)) { ?>
    <div id="bulk_assign_document_category_model" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content full-width">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="generated_document_title">Bulk Assign This Document Category</h4>
                </div>
                <div class="modal-body autoheight">
                    <div class="row">
                        <form method='post' id='uploaded-form' action="<?= current_url(); ?>">
                            <input type="hidden" name="perform_action" value="bulk_assign_category" />
                            <input type="hidden" name="category_sid" id="assign_category_sid" />
                            <div class="col-md-12">
                                <div class="form-group" style="min-height: 300px">
                                    <label>Employees<span class="hr-required red"> * </span></label>
                                    <select require multiple="multiple" name="employees[]" id="uploaded-employees" style="display: block"><option value="" selected>Please Select Employee</option></select>
                                </div>
                            </div>
                            <div class="col-lg-12" id="uploaded-empty-emp" style="display: none;"><span class="hr-required red">This Document Category Is Assigned To All Employees!</span></div>
                            <div class="col-md-12">
                                <div class="message-action-btn">
                                    <input type="submit" value="Bulk Assign This Category" id="send-up-doc" class="submit-btn">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.css')?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.bootstrap3.css')?>">
<script src="<?= base_url('assets/js/selectize.min.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#uploaded-form').validate({
            rules: {
                employees: {
                    required: true
                }
            },
            messages: {
                employees: {
                    required: 'Employee(s) Required'
                }
            },
            submitHandler:function(form){
                var up_emp = $('#uploaded-employees').val();
                
                if(up_emp != '' && up_emp != null) {
                    form.submit();
                } else {
                    alertify.error('Please select Employee(s)');
                }
            }
        });
    });

    var upemployees = $('#uploaded-employees').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption:false,
        persist: true,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
    var up_emp = upemployees[0].selectize;

    function func_assign_document_category (category_sid) {
        $('#bulk_assign_document_category_model').modal('show');
        $.ajax({
            type:'POST',
            url: '<?= base_url('hr_documents_management/get_all_company_employees')?>',
            success: function (data) {
                var employees = JSON.parse(data);
                
                if(employees.length == 0){
                    up_emp.clearOptions();
                    up_emp.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select Employee'
                        }
                        callback(arr);
                        up_emp.addItems('');
                        up_emp.refreshItems();
                    });
                    
                    $('#uploaded-empty-emp').show();
                    $('#send-up-doc').hide();
                    up_emp.disable();
                } else{
                    $('#uploaded-empty-emp').hide();
                    $('#send-up-doc').show();
                    up_emp.enable();
                    up_emp.clearOptions();
                    up_emp.load(function(callback) {
                        var arr = [{}];
                        var j = 0;

                        for (var i = 0; i < employees.length; i++) {
                            arr[j++] = {
                                value: employees[i].sid,
                                text: employees[i].first_name + ' ' + employees[i].last_name
                            }
                        }

                        callback(arr);
                        up_emp.refreshItems();
                    });
                }
            },
            error: function () {

            }
        });
        $('#assign_category_sid').val(category_sid);
    }
</script>
