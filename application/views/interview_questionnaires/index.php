<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div id="my_loader" class="text-center my_loader">
                                    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                                    <div class="loader-icon-box">
                                        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                                        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
                                        </div>
                                    </div>
                                </div>
                                <div class="hr-box">
                                    <div class="hr-box-header bg-header-green">
                                        <span style="font-size: 16px;">
                                            Default Interview Questionnaires
                                        </span>
                                    </div>
                                    <div class="hr-box-body hr-innerpadding" style="padding: 10px; float: left; width: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-10 text-left">Questionnaire Title</th>
                                                        <th class="col-xs-2 text-center" colspan="2">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($questionnaires_default)) { ?>
                                                        <?php foreach ($questionnaires_default as $questionnaire) { ?>
                                                            <tr>
                                                                <td><?php echo $questionnaire['title']; ?></td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'preview')) { ?>
                                                                    <td>
                                                                        <button onclick="func_preview_questionnaire(<?php echo $questionnaire['sid']; ?>);" type="button" class="btn btn-success btn-block text-center">
                                                                            Preview
                                                                        </button>
                                                                    </td>
                                                                <?php } if(check_access_permissions_for_view($security_details, 'clone_questionnaire')) {?>
                                                                <td>
                                                                    <button onclick="func_clone_questionnaire(<?php echo $questionnaire['sid']; ?>);" type="button" class="btn btn-success btn-block">
                                                                        Clone
                                                                    </button>
                                                                    <a style='display:none;' id="form_button_<?php echo $questionnaire['sid']; ?>" href="<?php echo base_url('interview_questionnaire/clone_questionnaire/' . $questionnaire['sid']); ?>" class="btn btn-success btn-sm btn-block">Clone</a>
                                                                </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="2">
                                                                <span class="no-data">No Interview Questionnaires</span>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr-box">
                                    <div class="hr-box-header bg-header-green">
                                        <span style="font-size: 16px;"> Custom Interview Questionnaires </span>
                                        <?php if (check_access_permissions_for_view($security_details, 'add_edit_delete_questionnaire')) { ?>
                                            <span class="pull-right"><a href="<?php echo base_url('interview_questionnaire/add_questionnaire'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New Interview Questionnaire</a></span>
                                        <?php } ?>
                                    </div>
                                    <div class="hr-box-body hr-innerpadding" style="padding: 10px; float: left; width: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-10 text-left">Questionnaire Title</th>
                                                        <?php $function_names = array('add_edit_delete_questionnaire', 'manage_questionnaire'); ?>
                                                        <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                            <th class="col-xs-2 text-center" colspan="3">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($questionnaires_company_specific)) { ?>
                                                        <?php foreach ($questionnaires_company_specific as $questionnaire) { ?>
                                                            <tr>
                                                                <td><?php echo $questionnaire['title']; ?></td>
                                                                <?php if (check_access_permissions_for_view($security_details, 'add_edit_delete_questionnaire')) { ?>
                                                                    <td><a href="<?php echo base_url('interview_questionnaire/edit_questionnaire/' . $questionnaire['sid']); ?>" class="btn btn-success btn-sm btn-block"><i class="fa fa-pencil"></i></a></td>
                                                                <?php } ?>
                                                                <?php if (check_access_permissions_for_view($security_details, 'manage_questionnaire')) { ?>
                                                                    <td><a href="<?php echo base_url('interview_questionnaire/manage_questionnaire/' . $questionnaire['sid']); ?>" class="btn btn-success btn-sm btn-block text-center">Manage</a></td>
                                                                <?php } ?>                                                           
                                                                <?php if (check_access_permissions_for_view($security_details, 'add_edit_delete_questionnaire')) { ?>
                                                                    <td>
                                                                        <form id="form_delete_questionnaire_<?php echo $questionnaire['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>" >
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_questionnaire" />
                                                                            <input type="hidden" id="questionnaire_sid" name="questionnaire_sid" value="<?php echo $questionnaire['sid']; ?>" />
                                                                            <button onclick="func_delete_questionnaire(<?php echo $questionnaire['sid']; ?>);" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                                        </form>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="2">
                                                                <span class="no-data">No Interview Questionnaires</span>
                                                            </td>
                                                        </tr>
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
            </div>
        </div>
    </div>
</div>


<!-- bootstrap modal *** START ***
<button style='display:none;' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#form_modal" id="form_button">Open Form Modal</button>
<div id="form_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Clone Interview Questionnaire</h4>
            </div>
            <div class="modal-body">
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box-body hr-innerpadding">
                                <div class="universal-form-style-v2">
                                    <form method="post" id="clone_form" name="clone_form">
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <label for="title">Title <span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" name="title" id="title"/>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label for="short_description">Short Description</label>
                                                <textarea class="invoice-fields-textarea" name="short_description" id="short_description"></textarea>
                                            </li>
                                            <input type='hidden' name='clone_ques_sid' id='clone_ques_sid' />
                                        </ul>
                                        <div class="hr-box-footer hr-innerpadding">
                                            <input type="submit" name="clone_form_submit" id="clone_form_submit" value="Submit" class="btn btn-success" onclick="validate_clone_form();"/>
                                            <a class="btn btn-default" href="<?php echo base_url('interview_questionnaire'); ?>" >Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
 bootstrap modal *** END ***-->
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function func_clone_questionnaire(questionnaire_sid) {
        set_clone_data(questionnaire_sid);
        
        alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to clone this Questionnaire',
                function () {
                    $("#form_button_"+questionnaire_sid)[0].click();
                    console.log('click it');
                },
                function () {
                    alertify.error('Cancelled');
                });
    }
    
    function set_clone_data(questionnaire_sid){
        var my_request;
        var data_to_send = {
            'perform_action': 'preview_questionnaire_clone',
            'questionnaire_sid': questionnaire_sid

        };

        my_request = $.ajax({
            url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType: 'json'
        });

        my_request.done(function (data) {
            data = data[0];
            var sid = data.sid;
            var title = data.title;
            var short_description = data.short_description;
            
            $('#title').val(title);
            $('#short_description').val(short_description);
            $('#clone_ques_sid').val(sid);
        });
    }
    
    function validate_clone_form(){ 
        $('#clone_form').validate({
            ignore: [],
                rules: {
                    title: { 
                        required: true,
                        uniquetitle: true
                    },
                    short_description: {
                        required: true,
                    }
                },
                messages: {
                    title: {
                        required: "Title is required.",
                        uniquetitle: "Please enter a unique title"
                    },
                    short_description: {
                        required: 'Short description is required'
                    }
                }
            });
            
        if($('#clone_form').valid()){
            var questionnaire_sid;
            alert('something');
//            clone_request(questionnaire_sid);
        } 
    }
        
        $(document).ready(function(){
        $.validator.addMethod(
                "uniquetitle",
                function (value, element) {
                    var title = value;
                    var questionnaire_sid = $('#clone_ques_sid').val();
                    var my_request;
                    my_request = $.ajax({
                            url: '<?php echo base_url('interview_questionnaire/ajax_responder'); ?>',
                            type: 'POST',
                            data: {'perform_action': 'validate_clone_title', 'questionnaire_sid': questionnaire_sid, 'title':title},
                            requestType: 'json'
                            });
//                    my_request.done(function (response) {
//                        return response;
//                    });
                    
                    my_request.success(function (response) {
                            if (response == false) {
                                var myresult = 'allow';
                                console.log('I Am allowed');
                            } else {
                                var myresult = 'notallow';
                            }
                        });
                        console.log(myresult);
                        if(myresult=='allow'){
                            return true;
                        } else {
                            return false;
                        }
                },
                $.validator.messages.uniquetitle
                );
        });

    function clone_request(questionnaire_sid) {
        var my_request;
        my_request = $.ajax({
            url: '<?php echo base_url('interview_questionnaire/ajax_responder'); ?>',
            type: 'POST',
            data: {'perform_action': 'clone_questionnaire', 'questionnaire_sid': questionnaire_sid},
            requestType: 'json'
        });

        my_request.done(function (response) {
            if (response.status == 'success') {
                window.location.href = window.location.href;
            } else {
                alertify.error('Could not Clone!');
            }
        });
    }

    function func_delete_questionnaire(questionnaire_sid) {
        alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to delete this Questionnaire?',
                function () {
                    $('#form_delete_questionnaire_' + questionnaire_sid).submit();
                },
                function () {
                    alertify.error('Cancelled');
                });
    }

    function func_preview_questionnaire(questionnaire_sid) {
        $('#popupmodal #popupmodalbody').html('');
        var my_request;
        var data_to_send = {
            'perform_action': 'preview_questionnaire',
            'questionnaire_sid': questionnaire_sid

        };

        func_show_loader();

        my_request = $.ajax({
            url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType: 'json'
        });

        my_request.done(function (response) {
            //console.log(response);
            func_hide_loader();

            $('#popupmodal .modal-dialog').addClass('modal-lg');
            $('#popupmodal .modal-dialog').css('width', '90%');
            $('#popupmodal #popupmodalbody').html(response.html);
            $('#popupmodal #popupmodallabel').html(response.title);
            $('#popupmodal').modal('toggle');
        });
    }

    $(document).ready(function () {
        $('#popupmodal').on('shown.bs.modal', function () {
            $('.rating').rating();
        });

        func_hide_loader();
    });

    function func_hide_loader() {
        $('#file_loader').css("display", "none");
        $('.my_spinner').css("visibility", "hidden");
        $('.loader-text').css("display", "none");
        $('.my_loader').css("display", "none");
    }

    function func_show_loader() {
        $('#file_loader').css("display", "block");
        $('.my_spinner').css("visibility", "visible");
        $('.loader-text').css("display", "block");
        $('.my_loader').css("display", "block");
    }
</script>
