<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php echo $title; ?></span>
                        </div>
                        <div class="info-text">

                            The SMS Templates module enables you to create and customize messages for your outgoing SMS.<br>
                            We have created default templates to get you going, and get your creative juices flowing.<br>
                            You can use these or create your own.<br>
                            To modify any sms template and make it your own press the Edit button next to it.<br>
                        </div>
                    </div>
                    <div class="dashboard-conetnt-wrp">
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                    <div class="field-row">
                                        <label for="">Phone Nummber</label>
                                        <input type="text" value="<?php echo $sma_info['phone_number']; ?>" class="invoice-fields" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                    <div class="field-row">
                                        <label for="">Service Name</label>
                                        <input type="text" value="<?php echo $sma_info['message_service_name']; ?>" class="invoice-fields" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                    <label for="">&nbsp;</label>
                                    <a href="<?php echo base_url('portal_sms_templates/add_sms_template'); ?>" class="btn btn-success btn-block">Add Custom Template</a>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="table-responsive table-outer">
                                        <table class="table table-bordered table-hover table-stripped">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-3">Template Name</th>
                                                    <th class="col-xs-5">Template Body</th>
                                                    <th class="col-xs-1 text-center">Type</th>
                                                    <th class="col-xs-1 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(sizeof($all_templates) > 0) {
                                                    foreach($all_templates as $templates){ ?>
                                                        <tr>
                                                            <td><?php echo $templates['template_name']; ?></td>
                                                            <td><?php echo nl2br($templates['sms_body']); ?></td>
                                                            <td><?php if($templates['is_custom']==0){ echo 'Default'; } else { echo 'Custom'; }?></td>
                                                            <td>
                                                                <a class="btn btn-success btn-sm" href="<?= base_url('portal_sms_templates/edit_sms_template') ?>/<?= $templates['sid'] ?>">Edit</a>
                                                                <?php if($templates['is_custom']==1){ ?>
                                                                    <a class="btn btn-danger btn-sm delete" href="javascript:;" data-key="<?= $templates['sid'] ?>">Delete</a>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }else{ ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center">No template found</td>
                                                    </tr>
                                                <?php }?>
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


<script>
    $(document).ready(function(){
        $('.delete').click(function(){
            var id = $(this).attr("data-key");
            alertify.confirm('Delete Template!','Do you really want to delete this template?',function(){
                $.ajax({
                    type: 'POST',
                    url:  '<?= base_url('portal_sms_templates/ajax_handler'); ?>',
                    data: {
                        id: id
                    },
                    success: function(data){
                        if(data){
                            window.location.href = window.location.href;
                        }else{
                            alertify.error('Wrong Request');
                        }
                    },
                    error: function (){
                        alertify.error('Something went wrong');
                    }
                });
            }, function(){
                alertify.error('Cancelled');
            });
        });

    });


</script>