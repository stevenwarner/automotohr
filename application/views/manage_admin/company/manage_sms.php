<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <!-- Heading -->
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><em class="fa fa-phone"></em> Manage SMS</h1>
                                        <a class="black-btn pull-right"
                                            href="<?php echo base_url('manage_admin/manage_company/'.($sid).''); ?>"><em
                                                class="fa fa-long-arrow-left"></em> Back to Company</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Body -->
                                <div class="col-sm-12">
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <h1 class="page-title"><?=$companyInfo['CompanyName'];?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <!-- Body -->
                                <div class="col-sm-12">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-3 col-xs-12">
                                                <label>Phone Number</label>
                                            </div>
                                            <div class="col-sm-9 col-xs-12">
                                                <input type="text" class="form-control" readonly value="<?=!empty($phoneNumber['phone_number']) ? $phoneNumber['phone_number'] : '';?>"/>
                                                <input type="text" class="form-control" readonly style="margin-top: 3px;" value="<?=!empty($phoneNumber['phone_sid']) ? $phoneNumber['phone_sid'] : '';?>"/>
                                                <br />
                                                <?php if (!isset($phoneNumber['phone_sid']) || empty($phoneNumber['phone_sid'])) { ?>
                                                    <button type="button" class="btn btn-success" id="jsAddNumber">Add a Phone Number</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-3 col-xs-12">
                                                <label>Message Service <br><small style="color: #cc1100;">(Maximum 11 characters allowed)</small></label>
                                            </div>
                                            <div class="col-sm-9 col-xs-12">
                                                <input type="text" class="form-control" id="service_name" value="<?=!empty($phoneNumber['message_service_name']) ? $phoneNumber['message_service_name'] : '';?>"/>
                                                <input type="hidden" id="message_service_code" value="<?=!empty($phoneNumber['phone_sid']) ? $phoneNumber['phone_sid'] : '';?>"/>
                                                <input type="hidden" id="message_service_sid" value="<?=!empty($phoneNumber['message_service_sid']) ? $phoneNumber['message_service_sid'] : 'no';?>"/>
                                                <input type="text" class="form-control" readonly style="margin-top: 3px;" value="<?=!empty($phoneNumber['message_service_sid']) ? $phoneNumber['message_service_sid'] : '';?>"/>
                                                <br />
                                                <button type="button" class="btn btn-success" id="jsMessageService">Update Message Service</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="csIPLoader jsMessageServiceLoader" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i></div>

<style>
    /* Modal */

    .csModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    z-index: 1000;
    background-color: #ffffff;
    }

    .csModal .csModalHeader {
        min-height: 45px;
        color: #333;
        border-bottom: 1px solid #ddd;
        background-color: #ffffff;
    }

    .csModal .csModalHeader .csModalHeaderTitle {
        font-weight: 800;
    }

    .csModal .csModalHeader .csModalHeaderTitle button {
        margin-top: -5px;
    }

    .csModal .csModalBody {
        color: #333;
        padding: 10px;
        overflow-y: auto;
        position: absolute;
        top: 65px;
        right: 0;
        left: 0;
        bottom: 0;
    }

    .csModal .csModalBody label {
        font-size: 16px;
    }

    .csModal .csModalButtonWrap {
        float: right;
    }

    .csModal .csModalButtonWrap button,
    .csModal .csModalButtonWrap a {
        border-radius: 5px !important;
        -webkit-border-radius: 5px !important;
        -moz-border-radius: 5px !important;
        -o-border-radius: 5px !important;
    }


    /* Common loader CSS */

    .csIPLoader {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        width: 100%;
        background: rgba(255, 255, 255, .9);
        z-index: 1;
    }

    .csIPLoader i {
        position: relative;
        top: 50%;
        left: 50%;
        font-size: 50px;
        color: #fd7a2a;
        transform: translate(-50%, -50%);
    }
</style>

<script>
    // Modal
    function Modal(
        options,
        cb
    ) {
        //
        let html = `
        <!-- Custom Modal -->
        <div class="csModal" id="${options.Id}">
            <div class="container-fluid">
                <div class="csModalHeader">
                    <h3 class="csModalHeaderTitle">
                        
                        ${options.Title}
                        <span class="csModalButtonWrap">
                        ${ options.Buttons !== undefined && options.Buttons.length !== 0 ? options.Buttons.join('') : '' }
                            <button class="btn btn-black jsModalCancel" ${options.Ask === undefined ? '' : 'data-ask="no"'} title="Close this window">Cancel</button>
                        </span>
                        <div class="clearfix"></div>
                    </h3>
                </div>
                <div class="csModalBody">
                    <div class="csIPLoader jsIPLoader" data-page="${options.Loader}"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                    ${options.Body}
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        `;
        //
        $(`#${options.Id}`).remove();
        $('body').append(html);
        $(`#${options.Id}`).fadeIn(300);
        $('.jsIPLoader').hide();
        //
        $('body').css('overflow-y', 'hidden');
        $(`#${options.Id} .csModalBody`).css('top', $(`#${options.Id} .csModalHeader`).height() + 50);
        cb();
    }

    //
    $(document).on('click', '.jsModalCancel', (e) => {
        //
        e.preventDefault();
        //
        if ($(e.target).data('ask') != undefined) {
            //
            alertify.confirm(
                'Any unsaved changes will be lost.',
                () => {
                    //
                    $(e.target).closest('.csModal').fadeOut(300);
                    //
                    $('body').css('overflow-y', 'auto');
                    //
                    $('#ui-datepicker-div').remove();
                }
            ).set('labels', {
                ok: 'LEAVE',
                cancel: 'NO, i WILL STAY'
            }).set(
                'title', 'Notice!'
            );
        } else {
            //
            $(e.target).closest('.csModal').fadeOut(300);
            //
            $('body').css('overflow-y', 'auto');
            //
            $('#ui-datepicker-div').remove();
        }
    });

    $('#jsAddNumber').click(function(event){
        //
        event.preventDefault();

        var row = '';
        row += '<div class="col-sm-12">';
        row += '    <div class="col-sm-8">';
        row += '        <div class="form-group">';
        row += '            <label>Zip Code</label>';
        row += '            <input type="text" class="form-control js_zip_code" value="" id="add_zips" />';
        row += '        </div>';
        row += '    </div>';
        row += '    <div class="col-sm-4" style="padding: 28px 0px;">';
        row += '        <button type="button" class="btn btn-success" id="jsFetchNumber">Fetch Number</button>';
        row += '    </div>';
        row += '</div>';
        row += '<div id="listing_section" class="col-sm-12" style="display: none;">';
        row += '    <div class="table-responsive">';
        row += '        <table class="table table-striped">';
        row += '            <thead>';
        row += '                <tr>';
        row += '                    <th>Phone Number</th>';
        row += '                    <th>Postal Code</th>';
        row += '                    <th>Action</th>';
        row += '                </tr>';
        row += '            </thead>';
        row += '            <tbody id="number_listing"></tbody>';
        row += '        </table>';
        row += '    </div>';
        row += '</div>';

        Modal({
            Id: 'jsSMSNumberModal',
            Title: 'Phone number list',
            Body: row,
            Loader: 'jsSMSNumberModalLoader'
        },create_number_list);


    });

    function create_number_list (zip) {
        $('.jsIPLoader').show();

        if (zip == '' || zip == undefined) {
            zip = 951;
        }

        var myurl = "<?php echo base_url('manage_admin/companies/get_Phone_number_list'); ?>"+"/"+zip;
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (resp) {
                if(resp.Status === true){
                    var rows = '';
                    resp.list.map(function(v){
                        rows += '<tr>';
                        rows += '    <td>'+( v.FriendlyName )+'</td>';
                        rows += '    <td>'+( v.PostalCode )+'</td>';
                        rows += '    <td><a href="javascript:;" class="btn btn-success select_ph_no" fpno="'+( v.FriendlyName )+'" pno="'+( v.Number )+'">Purchase Number</a></td>';
                        rows += '</tr>';
                    });
                    $("#listing_section").show();
                    $("#number_listing").html(rows);
                    $('.jsIPLoader').hide();
                } else  if(resp.Status === false){
                    $('.jsIPLoader').hide();
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
            },
            error: function (data) {

            }
        });
    }

    $(document).on("click", "#jsFetchNumber", function() {
        var zip = $("#add_zips").val();

        if (zip != '' && !/^[0-9]+$/.test(zip)) {
             alertify.alert("ERROR!", "Only number are accepted.", function(){ return; });
            return;
        } else {
            create_number_list(zip);
        }    
    });

    $(document).on("click", ".select_ph_no", function() {
        var phone_no = $(this).attr('pno');
        var friendly_phone_no = $(this).attr('fpno');
        alertify.confirm(
            'Do you really want to purchase this phone number "'+friendly_phone_no+'"?', 
            function(){
                $('.jsIPLoader').show();
                var form_data       = new FormData();

                form_data.append('actual_phone_number', phone_no);
                form_data.append('phone_number', friendly_phone_no);
                form_data.append('company_sid', <?php echo $sid; ?>);

                $('#edit_incident_video').modal('hide');
                var myurl = "<?php echo base_url('manage_admin/companies/purchase_phone_number'); ?>";

                $.ajax({
                    url: myurl,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(resp){
                        $(".jsIPLoader").hide();  
                        if(resp.Status === true){
                            alertify.alert('SUCCESS!', resp.Response,function(){
                                location.reload();
                            });
                        } else  if(resp.Status === false){
                            alertify.alert('ERROR!', resp.Response,function(){
                                
                            });
                        } 
                        $("#jsSMSNumberModal").remove();
                        
                    },
                    error: function(){
                    }
                });
            }).set({title:"Confirmation"}).set('labels', {
                'ok': 'Yes',
                'cancel': 'No'
            });
    });

    $("#jsMessageService").on("click", function(){
        var service_name = $("#service_name").val();
        var service_code = $("#message_service_code").val();
        var service_sid = $("#message_service_sid").val();

        if (service_name == '' || service_name == undefined) {
            alertify.alert("WARNING!", "Please enter message service name.", function(){ return; });
            return;
        } else if (service_name.length < 11) {
            alertify.alert("WARNING!", "Maximum 11 characters allowed.", function(){ return; });
            return;
        } else {

            alertify.confirm(
                'Do you really want to create message service?', 
                function(){
                    $(".jsMessageServiceLoader").show();
                    var form_data       = new FormData();

                    form_data.append('service_name', service_name);
                    form_data.append('service_code', service_code);
                    form_data.append('service_sid', service_sid);
                    form_data.append('company_sid', <?php echo $sid; ?>);

                    $('#edit_incident_video').modal('hide');
                    var myurl = "<?php echo base_url('manage_admin/companies/create_message_service'); ?>";

                    $.ajax({
                        url: myurl,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        data: form_data,
                        success: function(resp){
                            $(".messageServiceLoader").hide();  
                            if(resp.Status === true){
                                alertify.alert('SUCCESS!', resp.Response,function(){
                                    location.reload();
                                });
                            } else  if(resp.Status === false){
                                alertify.alert('ERROR!', resp.Response,function(){
                                    
                                });
                            } 
                            $(".jsMessageServiceLoader").hide();
                            
                        },
                        error: function(){
                        }
                    });
                }).set({title:"Confirmation"}).set('labels', {
                    'ok': 'Yes',
                    'cancel': 'No'
                });
        }  
        
    });
</script>