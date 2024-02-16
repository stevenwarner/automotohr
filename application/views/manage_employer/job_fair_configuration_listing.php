<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('job_fair_configuration'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Job Fair Configuration</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="btn-wrp">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8"></div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <a href="<?php echo base_url(); ?>job_fair_configuration/add_new" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Form</a><br />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-outer">
                        <!--                        <strong id="invoiceCount">Placed Orders:</strong>-->
                        <table class="table table-stripped table-hover table-bordered" data-order='[[ 0, "desc" ]]'>
                            <thead>
                                <tr>
                                    <th class="col-lg-8">Form Title</th>
                                    <th class="col-lg-1 text-center">Type</th>
                                    <!--                                    <th class="col-lg-1 text-center">Status</th>-->
                                    <th class="col-lg-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($job_fair_forms as $jff) { ?>
                                    <tr>
                                        <td><?php echo $jff['form_name']; ?></td>
                                        <td class="text-capitalize text-center"><?php echo $jff['form_type']; ?></td>
                                        <!--<td class="text-capitalize text-center status-tab" id="<?php //echo $jff['sid']; 
                                                                                                    ?>"><?php //echo ($jff['status'] == 1 ? 'Active' : '<a class="btn btn-primary btn-block activate" data-sid="'.$jff['sid'].'" title="Make Active">Make Active</a>'); 
                                                                                                                                    ?></td>-->
                                        <td><?php if ($jff['form_type'] != 'default') { ?>
                                                <a class="btn btn-success btn-block" href="<?php echo base_url(); ?>job_fair_configuration/view_edit/<?php echo $jff['sid']; ?>" title="View / Edit">View / Edit</a>
                                                <a class="btn btn-success btn-block clone" href="javascript:void()" title="Clone" data-id="<?php echo $jff['sid']; ?>">Clone</a>

                                            <?php } else { ?>
                                                <a class="btn btn-danger btn-block" id="default-type" title="Modifications not allowed">View / Edit</a>
                                            <?php } ?>
                                    </tr>
                                    </td>
                                <?php   } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.activate', function(e) {
        $(this).removeClass('activate');
        $(this).attr('disabled', 'disabled');
        var sid = $(this).attr('data-sid');
        var url = '<?php echo base_url('job_fair_configuration/update_status'); ?>';
        $.ajax({
            url: url,
            type: 'post',
            data: {
                sid: sid
            },
            success: function(data) {

                if (data != '') {
                    $('#' + sid).html('Active');
                    $('#' + data).html('<a class="btn btn-primary btn-block activate" data-sid="' + data + '" title="Make Active">Make Active</a>');
                    alertify.success('Job Fair Status Updated');
                }
            },
            error: function() {
                alert('Something went wrong');
            }
        })
    });
    $('#default-type').on('click', function() {
        alertify.alert("Warning", "Default Job Fair Form Isn't Editable!");
    });


    //
    $('.clone').click(function(event) {
        //
        event.preventDefault();
        //
        var jobId = $(this).data("id");
        //
        alertify.confirm('Do you really want to clone selected job fair?', function() {
            //
            $('#my_loader').show();
            //
            $.post("<?= base_url('job_fair_configuration/job_clone'); ?>", {
                jobId: jobId
            }).done(function() {
                //
                $('#my_loader').hide();
                //
                alertify.alert('Success!', 'You have successfully cloned this selected job fair.', function() {
                    window.location.reload();
                    return;
                })
            });
        }).set({title:"Confirm"}).set({labels:{ok:'Yes', cancel: 'No'}});

    });
</script>