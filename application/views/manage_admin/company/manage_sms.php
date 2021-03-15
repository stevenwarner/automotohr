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
                                                <button type="button" class="btn btn-success" id="jsAddNumber">Add a Phone Number</button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-3 col-xs-12">
                                                <label>Message Service <br><small style="color: #cc1100;">(Maximum 11 characters allowed)</small></label>
                                            </div>
                                            <div class="col-sm-9 col-xs-12">
                                                <input type="text" class="form-control" value="<?=!empty($phoneNumber['message_service_name']) ? $phoneNumber['message_service_name'] : '';?>"/>
                                                <input type="text" class="form-control" readonly style="margin-top: 3px;" value="<?=!empty($phoneNumber['message_service_sid']) ? $phoneNumber['message_service_sid'] : '';?>"/>
                                                <br />
                                                <button type="button" class="btn btn-success" id="jsAddNumber">Update Message Service</button>
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

        Modal({
            Id: 'jsSMSNumberModal',
            Title: 'Phone number list',
            Body: '',
            Loader: 'jsSMSNumberModalLoader'
        });
    });
</script>