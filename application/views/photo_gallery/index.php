<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                </div>
                <!-- insert option button -->
                <div class="btn-panel text-right">
                    <div class="row">
                        <a class="btn btn-success" href="<?php echo base_url('photo_gallery/add'); ?>">+ Add New Photo</a>
                    </div>
                </div>
                <!-- insert option button -->
                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">
                            <?php if (!isset($pictures) || empty($pictures)) { ?>
                                <span class="applicant-not-found">No photos found</span>
                            <?php } else { ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-1">Thumbnail</th>
                                            <th>Image Title</th>
                                            <th>URL</th>  
                                            <th class="col-lg-2 text-center">Actions</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pictures as $picture) { ?>
                                            <tr id='row_<?php echo $picture['sid']; ?>'>
                                                <td>
                                                    <figure>
                                                        <img src="<?php echo STORE_PROTOCOL_SSL . CLOUD_GALLERY .'.s3.amazonaws.com/' . $picture['uploaded_name']; ?>" width="100"/>
                                                    </figure>
                                                </td>
                                                <td><?php echo ucwords($picture['title']); ?></td>
                                                <td><?php echo STORE_PROTOCOL_SSL . CLOUD_GALLERY .'.s3.amazonaws.com/' . $picture['uploaded_name']; ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" id='<?php echo $picture['sid']; ?>' onclick="fLaunchModal(this);" class="btn btn-default btn-sm" data-preview-url="<?php echo STORE_PROTOCOL_SSL . CLOUD_GALLERY .'.s3.amazonaws.com/' . $picture['uploaded_name']; ?>" data-preview-title="<?php echo ucwords($picture['title']); ?>" >
                                                        View
                                                    </a>
                                                    <button title="delete" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm" id="delete_photo" onclick='confirm_delete(<?php echo $picture['sid']; ?>);'>
                                                        <i class="fa fa-remove"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?> 
                        </div>
                    </div>
                </div>
                <!-- main table view end -->
            </div>
        </div>
    </div>
</div>

<!-- image modal -->
<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- image modal -->

<script>
    function fLaunchModal(source) {
        var image_path  = $(source).attr('data-preview-url');
        var image_title = $(source).attr('data-preview-title');

        $('#document_modal_body').html('<img src="'+image_path+'" style="width:100%;">');
        $('#document_modal_title').html(image_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {});
    }
    
    function confirm_delete(photo_sid) {
       alertify.confirm(
            'Delete Photo',
            '<p>Are you sure you want to delete this photo?</p>',
            function (event) {
                var myUrl = '<?php echo base_url("photo_gallery/ajax_responder")?>';

                var dataToPost = {
                    'perform_action': 'delete_photo',
                    'sid': photo_sid
                };

                var myRequest;
                myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: dataToPost
                });

                myRequest.done(function (response) {
                    if (response) {
                        $('#row_' + photo_sid).hide();
                        alertify.success('Photo deleted successfully');
                    } else {
                        alertify.error('An unknown error occured. Please try again.');
                    }
                });

            },
            function () {
                alertify.error('Cancelled');
            }
        );
    }
</script>
