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
                            The Email Templates module enables you to create and customize messages for your outgoing emails.<br>
                            We have created default templates to get you going, and get your creative juices flowing.<br>
                            You can use these or create your own.<br>
                            To modify any email template and make it your own press the Edit button next to it.<br>
                        </div>
                    </div>
                    <div class="dashboard-conetnt-wrp">
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                    <a href="<?php echo base_url('portal_email_templates/add_email_template'); ?>" class="btn btn-success btn-block">Add Custom Template</a>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="table-responsive table-outer">
                                        <table class="table table-bordered table-hover table-stripped">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-7">Template Name</th>
                                                    <th class="col-xs-1 text-center">Type</th>
                                                    <th class="col-xs-4 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($all_templates as $templates){ ?>
                                                    <tr>
                                                        <td class="col-lg-9"><?php echo $templates['template_name']; ?></td>
                                                        <td class="col-lg-1 text-center"><?php if($templates['is_custom']==0){ echo 'Default'; } else { echo 'Custom'; }?></td>
                                                        <td class="col-lg-2 text-center">
                                                            <a class="btn btn-success btn-sm" href="<?= base_url('portal_email_templates/edit_email_template') ?>/<?= $templates['sid'] ?>">Edit</a>
                                                            <?php if($templates['is_custom']==1){ ?>
                                                                <a class="btn btn-danger btn-sm delete" href="javascript:;" data-key="<?= $templates['sid'] ?>">Delete</a>
                                                            <?php } ?>
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


<script>
    $(document).ready(function(){
        $('body').on('change', 'input[type=file]', function () {
            console.log($(this).val());
            var selected_file = $(this).val();
            var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);

            var id = $(this).attr('id');
            $('#name_' + id).html(selected_file);
        });
        $('.delete').click(function(){
            var id = $(this).attr("data-key");
            alertify.confirm('Delete Template!','Do you really want to delete this template?',function(){
                $.ajax({
                    type: 'POST',
                    url:  '<?= base_url('portal_email_templates/ajax_handler'); ?>',
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

    var i = 1;
    function addattachmentblock() {
        var container_id = "message_attachment_container" + i;
        var id = "message_attachment" + i;
        $("<div id='" + container_id + "'><\/div>").appendTo("#dynamicattachment");
        $('#' + container_id).html($('#' + container_id).html() + '<li class="form-col-100 autoheight"><label>Attachment</label><div class="upload-file invoice-fields"><span id="name_'+id+'" class="selected-file">No file selected</span><input type="file" name="'+id+'" id="'+id+'" class="image"><a href="javascript:;">Choose File</a></div><div class="delete-row"><a href="javascript:;" onclick="deleteAnswerBlock(\'' + container_id + '\'); return false;" class="remove">Delete</a></div></li>');
        i++;
    }


</script>