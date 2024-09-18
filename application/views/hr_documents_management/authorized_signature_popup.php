

<link rel="stylesheet" href="<?php echo base_url('assets/crop/darkroom.css'); ?>">
<style type="text/css">
    .modal-backdrop {
        z-index: 99;
    }
    
    #darkroom-icons {
        position: fixed;
        bottom: 0;
    }

    .cancel_button_black {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #000;
        border: none;
        max-width: 210px;
        min-width: 97px;
        text-align: center;
        margin: 0 5px;
        border-radius: 5px;
        font-weight: 600;
        text-transform: capitalize;
        font-style: italic;
    }

    .cancel_btn_black {
        background-color: #000;
        color: #fff;
    }

    .cancel_btn_black:hover {
        background-color: #6c757d;
        color: #fff;
    }

    #assign_manager_section .select2-container--default .select2-selection--multiple .select2-selection__rendered{ height: auto !important; }
</style>

<!-- E-signature loader -->
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate your E-Signature...
        </div>
    </div>
</div>

<!-- Modal -->
<div id="authorized_e_Signature_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Authorized E-Signature</h4>
            </div>
            <div class="modal-body">
                <div id="authorized_signature_section">
                    <form id="form_authorized_e_signature" enctype="multipart/form-data">
                        <input type="hidden" name="perform_action" value="save_authorized_e_signature" />
                        <input type="hidden" name="user_sid" value="<?php echo $employer_sid; ?>" />
                        <input type="hidden" name="document_sid" value="" id="authorized_document_sid" />
                        <input type="hidden" id="drawn_authorized_signature" name="authorized_signature" value="" />
                        <input type="hidden" id="default_e_signature" value="<?php echo !empty($current_user_signature) ? $current_user_signature : ''; ?>" />

                        <!-- Section to populate current user info -->
                        <div id="default_authorized_signature_section" class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th class="col-xs-4">Name</th>
                                                <td class="col-xs-8"><?php echo ucwords($employer_first_name . ' ' . $employer_last_name); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="col-xs-4">Email</th>
                                                <td class="col-xs-8"><?php echo $employer_email; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="col-xs-4">Assign To</th>
                                                <td class="col-xs-8" id="doc_assign_to"></td>
                                            </tr>
                                            <tr>
                                                <th class="col-xs-4">Signed By</th>
                                                <td class="col-xs-8" id="document_assign_date"></td>
                                            </tr>
                                            <?php 
                                                $default_signature = 'default';
                                                $default_signature_section = 'style="display: none"';
                                            ?>    
                                            <tr id="defaultAuthorizedDocument" <?php echo $default_signature_section; ?>>
                                                <th class="col-xs-4">Default Signature</th>
                                                <td class="col-xs-8">
                                                    <img id="selected_auth_e_signature" style="max-height: <?php echo SIGNATURE_MAX_HEIGHT; ?>" src="<?php echo $default_signature; ?>" />
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="edit_authorized_signature_section" style="display:none;">
                            <!-- Section to select Signature Type -->
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <label class="control control--radio">
                                        Type Signature
                                        <input type="radio" id="active_authorized_signature_typed" checked="checked" name="active_authorized_signature_typed" value="typed"/>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 e_sign_mobile_hide">
                                    <label class="control control--radio">
                                        Draw Signature
                                        <input type="radio" id="active_authorized_signature_drawn" name="active_authorized_signature_typed" value="drawn"/>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Section to select Signature Font family -->
                            <div class="row" id="select_font_family_section" style="margin-bottom: 50px;">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><strong>Please select any font to type your Signature</strong></div>
                                        <div class="panel-body signature-variations">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--radio e_signature_font_family_5">
                                                                    John Doe
                                                                    <input type="radio" class="active_authorized_font_family" name="active_authorized_font_family" value="5" checked="checked">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--radio e_signature_font_family_2">
                                                                    John Doe
                                                                    <input type="radio" class="active_authorized_font_family" name="active_authorized_font_family" value="2">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>    
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--radio e_signature_font_family_3">
                                                                    John Doe
                                                                    <input type="radio" class="active_authorized_font_family" name="active_authorized_font_family" value="3">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div> 
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--radio e_signature_font_family_4">
                                                                    John Doe
                                                                    <input type="radio" class="active_authorized_font_family" name="active_authorized_font_family" value="4">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                         <div class="col-lg-3">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--radio e_signature_font_family_6">
                                                                    John Doe
                                                                    <input type="radio" class="active_authorized_font_family" name="active_authorized_font_family" value="6">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                         <div class="col-lg-3">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--radio e_signature_font_family_11">
                                                                    John Doe
                                                                    <input type="radio" class="active_authorized_font_family" name="active_authorized_font_family" value="11">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section to type the signature and show signature to user  -->
                            <div class="row" id="type_signature_section">
                                <div class="col-xs-12">
                                    <div style="min-height: 88px;" class="field-row">
                                        <?php $signature = isset($e_signature_data['signature']) ? $e_signature_data['signature'] : '';?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                            <div class="form-group autoheight">
                                                <label class="auto-height"><strong>Type Signature</strong></label> 
                                                <p class="domain_message">Hint: Please type your First and Last Name (<small>Max characters limit is 30</small>)</p>
                                                <input data-rule-required="true" type="text" class="form-control" name="signature" id="authorized_e_signature" maxlength="30" autocomplete="off" value=""  placeholder="John Doe"/>
                                            </div>
                                        </div>
                                        <?php echo form_error('signature'); ?>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <p class="e_signature_type_fixed_p e_signature_font_family_5 auto-height" id="authorized_tergit"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section to draw signature and initial signature in canvas  -->
                            <div class="row" id="draw_signature_section">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div style="min-height: 250px;">
                                        <div class="field-row autoheight canvas-wrapper">
                                           
                                            <canvas class="signature-canvas" id="auth_can" width="500" height="200"></canvas>
                                            
                                            <p>Please draw your signature</p>

                                            <button type="button" class="btn btn-danger btn-sm del-signature" onclick="auth_clearArea();"><i class="fa fa-trash"></i></button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>  

                        <hr />

                        <div class="row" >
                            <div class="col-lg-12">
                                <button id="edit_authorized_e_signature_button" type="button" class="btn btn-success break-word-text" >Edit Signature</button> 
                                <button id="replace_authorized_e_signature_button" type="button" class="btn btn-success break-word-text" style="display:none;">Save Signature</button> 
                                <button id="back_to_main" type="button" class="btn cancel_btn_black" style="display:none;">Back</button> 
                            </div>
                        </div>  
                    </form>
                </div>    
                
                <?php if ($this->uri->segment(1) != 'view_assigned_authorized_document') { ?>
                    <div id="assign_manager_section" style="display:none;">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong>Assign Document To Manager</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Select Manager <span class="staric">*</span></label>
                                        <select class="invoice-fields managers" name="manager" multiple="true" id="selected_manager">
                                            <option value=""> Please Select Manager</option>
                                            <?php if(sizeof($managers_list) > 0) { ?>
                                                <?php foreach ($managers_list as $manager) { ?>
                                                    <?php $user_role = remakeAccessLevel(array("access_level_plus"=>$manager['access_level_plus'],"pay_plan_flag"=>$manager['pay_plan_flag'],"access_level"=>$manager['access_level'],"is_executive_admin"=>$manager['is_executive_admin']))?>
                                                    <option id="emp_<?php echo $manager['sid']; ?>" value="<?php echo $manager['sid']; ?>" ><?php echo $manager['first_name'] .' '. $manager['last_name'] .' ( <b>'. $user_role .'</b> )' ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <span>Selected <strong id="js-from">0</strong> out of <strong id="js-total">0</strong></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button id="authorized_document_assign_save_btn" class="btn btn-success pull-right" style="margin-top: 10px" type="button">Assign</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>    
            </div>
            <div class="modal-footer">
                <button id="add_authorized_e_signature_button" type="button" class="btn btn-success break-word-text" style="display:none;">Add Signature</button>
                <button id="save_authorized_e_signature_button" type="button" class="btn btn-success break-word-text" >Save Authorized Signature</button>
                <?php if ($this->uri->segment(1) != 'view_assigned_authorized_document') { ?>
                    <button id="assign_to_manager_button" type="button" class="btn btn-success break-word-text" >Assign Manager</button>
                <?php } ?>
                <button type="button" class="btn cancel_btn_black" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="authorized_editable_date_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Authorized Date</h4>
            </div>
            <div class="modal-body">
                <div id="authorized_signature_section">
                    <form id="form_authorized_editable_date" enctype="multipart/form-data">
                        <input type="hidden" name="perform_action" value="save_authorized_date" />
                        <input type="hidden" name="user_sid" value="<?php echo $employer_sid; ?>" />
                        <input type="hidden" name="document_sid" value="" id="authorized_editable_date_document_sid" />

                        <!-- Section to populate current user info -->
                        <div id="default_authorized_signature_section" class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th class="col-xs-4">Name</th>
                                                <td class="col-xs-8"><?php echo ucwords($employer_first_name . ' ' . $employer_last_name); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="col-xs-4">Email</th>
                                                <td class="col-xs-8"><?php echo $employer_email; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="col-xs-4">Authorized Date</th>
                                                <td class="col-xs-8">
                                                <div class="form-group">
                                                    <input class="form-control"
                                                    type="text"
                                                    name="authorized_date"
                                                    id="jsAuthorizedDate"
                                                    value=""/> 
                                                </div>
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>      
            </div>
            <div class="modal-footer">
                <button id="save_authorized_date_button" type="button" class="btn btn-success">Save Date</button>
                <button type="button" class="btn cancel_btn_black" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value="0" id="assigned_manager_sid" />

<script src="<?php echo base_url('assets/js/html2canvas.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/crop/fabric.js'); ?>"></script>
<script src="<?php echo base_url('assets/crop/darkroom.js'); ?>"></script>
<script type="text/javascript">
    $('#jsAuthorizedDate').datepicker({
        dateFormat: 'mm/dd/yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    });   

    $( "#authorized_e_Signature_Modal" ).on('shown.bs.modal', function(){
        var assign_doc_sid = $("#authorized_document_sid").val();
        var company_sid = "<?php echo $company_sid; ?>";
        var assigned_doc_url = "<?= base_url() ?>Hr_documents_management/get_authorized_document_assigned_user/"+company_sid+"/"+assign_doc_sid;
    
        $.ajax({
            url: assigned_doc_url,
            cache: false,
            contentType: false,
            processData: false,
            type: 'get',
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var assign_to = obj.assign_to;
                var assign_date = obj.assign_date;
                var assign_sid = obj.assign_sid;
                //
                $("#doc_assign_to").html(obj.assign_to_name);
                $("#document_assign_date").html(obj.assign_to);

                $('#selected_manager').select2({
                    closeOnSelect: false
                });
                if (assign_sid != 0) {
                    // $('#assigned_manager_sid').val(assign_sid);
                }
                $('#selected_manager').select2('val', obj.assign_sids);
                $('strong#js-from').text( obj.assign_sids.length );
                $('strong#js-total').text( $('#selected_manager option').length );

            },
            error: function (data) {

            }   
        });

        $('#edit_authorized_signature_section').hide();
        $('#assign_manager_section').hide();
        $('#authorized_signature_section').show();
        $('#default_authorized_signature_section').show();

        $('#replace_authorized_e_signature_button').hide();
        $('#add_authorized_e_signature_button').hide();
        $('#back_to_main').hide();
        $('#edit_authorized_e_signature_button').show();
        $('#save_authorized_e_signature_button').show();
        $('#assign_to_manager_button').show();

        $('#type_signature_section').show();
        $('#authorized_tergit').show();
        $('#select_font_family_section').show();
        $('#active_authorized_signature_typed').prop('checked', true);
        $('#draw_signature_section').hide();
    });

    $(document).on('select2:select', '#selected_manager', function(){
        $('strong#js-from').text( $('#selected_manager').val() !== null ? $('#selected_manager').val().length : 0 );
    });

    $(document).on('select2:unselect', '#selected_manager', function(){
        $('strong#js-from').text( $('#selected_manager').val() !== null ? $('#selected_manager').val().length : 0 );
    });

    $('#edit_authorized_e_signature_button').on('click', function() {
        $('#default_authorized_signature_section').hide();
        $('#edit_authorized_signature_section').show();

        $('#edit_authorized_e_signature_button').hide();
        $('#save_authorized_e_signature_button').hide();
        $('#replace_authorized_e_signature_button').show();
        $('#replace_authorized_e_signature_button').prop('disabled', true);
        $('#back_to_main').show();

        $('#draw_signature_section').hide();
    });

    $('#back_to_main').on('click', function() {
        $('#edit_authorized_signature_section').hide();
        $('#default_authorized_signature_section').show();

        $('#edit_authorized_e_signature_button').show();
        $('#save_authorized_e_signature_button').show();
        $('#replace_authorized_e_signature_button').hide();
        $('#back_to_main').hide();

        $('#type_signature_section').show();
        $('#authorized_tergit').show();
        $('#select_font_family_section').show();
        $('#active_authorized_signature_typed').prop('checked', true);
        $('#draw_signature_section').hide();

        // var default_signature = $('#default_e_signature').val();
        // $('#drawn_authorized_signature').val(default_signature);
    });

    $('#use_default_authorized_e_signature_button').on('click', function() {
        var default_signature = $('#default_e_signature').val();
        $('#drawn_authorized_signature').val(default_signature);

        $('#edit_authorized_signature_section').hide();
        $('#default_authorized_signature_section').show();

        $('#use_default_authorized_e_signature_button').hide();
        $('#edit_authorized_e_signature_button').show();
    });

    $('#assign_to_manager_button').on('click', function() {
        $('#authorized_signature_section').hide();
        $('#assign_manager_section').show();

        $('#save_authorized_e_signature_button').hide();
        $('#assign_to_manager_button').hide();
        $('#add_authorized_e_signature_button').show();

        var assigned_manager_sid = $('#assigned_manager_sid').val();

        if (assigned_manager_sid != 0) {
            $("select[name=manager]").val(assigned_manager_sid).change();
        }
    });

    $('#add_authorized_e_signature_button').on('click', function() {
        var default_signature = $('#default_e_signature').val();
        $('#drawn_authorized_signature').val(default_signature);

        $('#assign_manager_section').hide();
        $('#edit_authorized_signature_section').hide();

        $('#authorized_signature_section').show();
        $('#default_authorized_signature_section').show();

        $('#add_authorized_e_signature_button').hide();
        $('#save_authorized_e_signature_button').show();
        $('#assign_to_manager_button').show();
        $('#edit_authorized_e_signature_button').show();
        $('#replace_authorized_e_signature_button').hide();
        $('#back_to_main').hide();

    });

    $('#active_authorized_signature_typed').on('click', function () {
        $('#type_signature_section').show();
        $('#authorized_tergit').show();
        $('#select_font_family_section').show();
        $('#draw_signature_section').hide();   
    });

    $('#active_authorized_signature_drawn').on('click', function () {
        $('#type_signature_section').hide();
        $('#draw_signature_section').show();
        $('#authorized_tergit').hide();
        $('#select_font_family_section').hide();
    });

    $('.active_authorized_font_family').on('click', function () {
        var authorized_selected_font;
        authorized_selected_font = $(this).val();

        if (authorized_selected_font == 1) {
            $('#authorized_tergit').removeClass();
            $('#authorized_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_1');
        } else if (authorized_selected_font == 2) {
            $('#authorized_tergit').removeClass();
            $('#authorized_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_2');
        } else if (authorized_selected_font == 3) {
            $('#authorized_tergit').removeClass();
            $('#authorized_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_3');
        } else if (authorized_selected_font == 4) {
            $('#authorized_tergit').removeClass();
            $('#authorized_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_4');
        } else if (authorized_selected_font == 5) {
            $('#authorized_tergit').removeClass();
            $('#authorized_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_5');
        } else if (authorized_selected_font == 6) {
            $('#authorized_tergit').removeClass();
            $('#authorized_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_6');
        } else if (authorized_selected_font == 11) {
            $('#authorized_tergit').removeClass();
            $('#authorized_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_11');
        }

        html2canvas(document.querySelector("#authorized_tergit")).then(canvas => {
            $("#drawn_authorized_signature").val(canvas.toDataURL());
        });
    });

    $('#replace_authorized_e_signature_button').on('click', function () {
        var flag_empty = 0;
        if ($('input[name="active_authorized_signature_typed"]:checked').val() == 'drawn') {

            var blank_signature = is_auth_CanvasBlank(document.getElementById('auth_can'));

            if (blank_signature) {
                flag_empty = 1;
                alertify.alert("Please Draw Your Signature");
            } else {
                get_drawn_auth_signature();
            }

        } else if ($('input[name="active_authorized_signature_typed"]:checked').val() == 'typed') {

            var blank_signature = $('#authorized_e_signature').val();

            if (blank_signature == '') {
                flag_empty = 1;
                alertify.alert("Please Type Your Signature");
            } else {

            }

        } 

        if (flag_empty == 0) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to use above authorized signature?',
                function () {
                    $("#my_loader").show();
                    print_E_signature();
                },
                function () {
                    
                }).set('labels', {ok: 'Yes', cancel: 'No'});  
        }
        
    });

    function print_E_signature () {
        var new_signature = $('#drawn_authorized_signature').val();
        console.log(new_signature)

        if (!new_signature) {
            return setTimeout(print_E_signature, 1000)
        }

        $('#default_authorized_signature_section').show();
        $('#type_signature_section').show();
        $('#authorized_tergit').show();
        $('#active_authorized_signature_typed').prop("checked", true);
        $('#draw_signature_section').hide(); 
        $('#edit_authorized_signature_section').hide();

        $('#edit_authorized_e_signature_button').show();
        $('#save_authorized_e_signature_button').show();
        $('#replace_authorized_e_signature_button').hide();
        $('#back_to_main').hide();

        $("#defaultAuthorizedDocument").show();
        $("#selected_auth_e_signature").attr("src",new_signature);
        $("#my_loader").hide();
        
    }

    $('#save_authorized_e_signature_button').on('click', function () {

        var date_flag = 1;
        
        if($('#target_authorized_signature_date').length) {
            var auth_sign_date = $("#target_authorized_signature_date").text(); 

            if (auth_sign_date == '' || auth_sign_date == null) {
                alertify.alert("Please Enter Authorized Signature Date");
                date_flag = 0;
            }
        }

        if (date_flag == 1) {
            var selected_signature = $('#selected_auth_e_signature').attr('src');
            $('#drawn_authorized_signature').val(selected_signature);

            var myurl = "<?= base_url() ?>Hr_documents_management/save_authorized_e_signature";

            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
               
                data: $('#form_authorized_e_signature').serialize(),
                url: myurl,

                success: function(auth_sign_sid){
                    alertify.alert("Authorized signature save successfully!", function() {
                        $('#authorized_e_Signature_Modal').modal('hide');
                        location.reload();
                    }).set({title:"Success"});
                },
                error: function(){

                }
            });
        }

        
    });

    $('#save_authorized_date_button').on('click', function () {

        var date_flag = 1;
        
        var auth_date = $("#jsAuthorizedDate").val(); 
        console.log(auth_date)

        if (auth_date == '' || auth_date == null) {
            alertify.alert("Please Enter Authorized Editable Date");
            date_flag = 0;
            return true;
        }

        if (date_flag == 1) {

            var myurl = "<?= base_url() ?>Hr_documents_management/save_authorized_editable_date";

            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
               
                data: $('#form_authorized_editable_date').serialize(),
                url: myurl,

                success: function(auth_sign_sid){
                    alertify.alert("Authorized editable date save successfully!", function() {
                        $('#authorized_editable_date_Modal').modal('hide');
                        location.reload();
                    }).set({title:"Success"});
                },
                error: function(){

                }
            });
        }

        
    });

    $('#authorized_document_assign_save_btn').on('click', function () {
        var selected_manager = $('#selected_manager').val();

        if (selected_manager == '' || selected_manager == null) {
            alertify.alert('Please select an employee from list!').set({title:"Warning"});
        } else {
            var assign_doc_sid = $("#authorized_document_sid").val();
            var form_data = new FormData();

            form_data.append('document_sid', assign_doc_sid);
            form_data.append('assign_to', selected_manager);

            var save_authorized_document_url = "<?= base_url() ?>Hr_documents_management/assign_authorized_document";

            $.ajax({
                url: save_authorized_document_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(response){
                    alertify.alert("Authorized document assigned successfully!", function() {
                        $('#authorized_e_Signature_Modal').modal('hide');
                        location.reload();
                    }).set({title:"Success"});
                    
                },
                error: function(){

                }
            });
        }
    });
    
    //Draw Signature - Start
    var auth_mousePressed = false;
    var auth_lastX, auth_lastY;
    var auth_ctx;

    var auth_stored_image = '<?php echo isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image']) ? $e_signature_data['signature_bas64_image'] : ''; ?>';
    var auth_canvas_id  = 'auth_can';

    //  This function create signature with cursor movement. 
    function auth_InitThis() {
        
        auth_ctx = document.getElementById(auth_canvas_id).getContext("2d");

        $('#' + auth_canvas_id).mousedown(function (e) {
            auth_mousePressed = true;
            auth_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });

        document.getElementById(auth_canvas_id).addEventListener('touchstart', function (e) {
            auth_mousePressed = true;
            auth_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });

        $('#' + auth_canvas_id).mousemove(function (e) {
            if (auth_mousePressed) {
                auth_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });

        document.getElementById(auth_canvas_id).addEventListener('touchmove', function (e) {
            if (auth_mousePressed) {
                auth_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });

        $('#' + auth_canvas_id).mouseup(function (e) {
            auth_mousePressed = false;
            get_drawn_auth_signature();
        });

        document.getElementById(auth_canvas_id).addEventListener('touchend', function (e) {
            auth_mousePressed = false;
            get_drawn_auth_signature();
        });

        $('#' + auth_canvas_id).mouseleave(function (e) {
            auth_mousePressed = false;
            get_drawn_auth_signature();
        });
    }

    //  This function draw or plot signature into canvas. 
    function auth_Draw(x, y, isDown) {

        if (isDown) {
            auth_ctx.beginPath();
            auth_ctx.strokeStyle = '#000';
            auth_ctx.lineWidth = 2;
            auth_ctx.lineJoin = "round";

            auth_ctx.moveTo(auth_lastX, auth_lastY);
            auth_ctx.lineTo(x, y);

            auth_ctx.closePath();
            auth_ctx.stroke();
        }
        auth_lastX = x; auth_lastY = y;
    }

    //  This function clear signature from signature canvas.
    function auth_clearArea() {
        // Use the identity matrix while clearing the canvas
        auth_ctx.setTransform(1, 0, 0, 1, 0, 0);
        auth_ctx.clearRect(0, 0, auth_ctx.canvas.width, auth_ctx.canvas.height);
    }

    //  This function draw e_signature or e_signature initial
    //  into there canvas.
    function get_drawn_auth_signature(){
        auth_canvas = document.getElementById(auth_canvas_id);
        var dataURL = auth_canvas.toDataURL();

        $('#drawn_authorized_signature').val(dataURL);
    }

    //  On page load initialize the canvas if signature type
    //  is drawn.
    window.onload = auth_InitThis();

    //Draw Signature - End

    //  This function populate signature p tag when user start
    //  typing its signature. 
    var inputBox = document.getElementById('authorized_e_signature');
    inputBox.onkeyup = function(){
        document.getElementById('authorized_tergit').innerHTML = inputBox.value; 
        $('#replace_authorized_e_signature_button').prop('disabled', true);
    }

    //  This function convert p tag into canves and and then convert 
    //  it into base64 image formate for signature.
    $("#authorized_e_signature").on("change paste mouseleave", function() {
        html2canvas(document.querySelector("#authorized_tergit")).then(canvas => {
            $("#drawn_authorized_signature").val(canvas.toDataURL());
            var current_sign = $("#authorized_e_signature").val();

            if(current_sign != '' || current_sign != undefined) {
                $('#replace_authorized_e_signature_button').prop('disabled', false);
            } 
        }); 
    });

    //  This function validate draw signature canvas and
    //  check that is canvas is empty or not.
    function is_auth_CanvasBlank(canvas) {
        var blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;

        return canvas.toDataURL() == blank.toDataURL();
    }

     $( ".get_authorized_sign_date" ).click(function() {
        var date = '<?php echo date('m-d-Y'); ?>';
        $("#target_authorized_signature_date").text(date);
        $('.get_authorized_sign_date').hide();
    });

</script>