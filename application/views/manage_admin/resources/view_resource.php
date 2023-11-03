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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>View Resource</h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/resources/') ?>"><i class="fa fa-long-arrow-left"></i> Back to Resources</a>
                                    </div>

                                    <div class="hr-search-main" style="display: block;">
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="hr-box" style="margin: 15px 0 0;">
                                                    <div class="hr-box-header">
                                                        <h1 class="hr-registered pull-left">Resource Details</h1>
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Meta Title:</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="meta_title" id="meta_title" value="<?php echo $page_data['meta_title']; ?>" readonly />
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Meta Description:</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="meta_description" id="meta_description" rows="4" cols="60" readonly><?php echo $page_data['meta_description']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Meta Keywords:</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="meta_key_word" id="meta_key_word" rows="4" cols="60" readonly><?php echo $page_data['meta_key_word']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="hr-box" style="margin: 15px 0 0;">
                                                    <div class="hr-box-header">
                                                        <h1 class="hr-registered pull-left">Resource Details</h1>
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Title:</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="title" id="title" value="<?php echo $page_data['title']; ?>" readonly />
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Slug:</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="slug" id="slug" value="<?php echo $page_data['slug']; ?>" readonly />
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Description:</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="description" id="description" rows="4" cols="60" readonly><?php echo $page_data['description']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Status:</label><b class="text-danger"> *</b>
                                                        <div class="hr-select-dropdown">
                                                            <select name="status" id="status" class="invoice-fields" disabled="true">>
                                                                <option value="0" <?php echo $page_data['status'] == 0 ? "Selected" : '' ?>>Unpublish</option>
                                                                <option value="1" <?php echo $page_data['status'] == 1 ? "Selected" : '' ?>>Publish</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label for="feature_image">Feature Image:</label>
                                                        <div>
                                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $page_data['feature_image']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Resource Type:</label>

                                                        <?php $resourceTypeArray = explode(',', $page_data['resource_type']) ?>

                                                        <label class="control control--checkbox">
                                                            <input type="checkbox" name="resourcetype[]" value="Videos" <?php echo in_array("Videos", $resourceTypeArray) ? 'checked' : '' ?> disabled="true"> Videos
                                                            <div class="control__indicator"></div>
                                                        </label>

                                                        <label class="control control--checkbox">
                                                            <input type="checkbox" name="resourcetype[]" value="Webinars" <?php echo in_array("Webinars", $resourceTypeArray) ? 'checked' : '' ?> disabled="true"> Webinars
                                                            <div class="control__indicator"></div>
                                                        </label>


                                                        <label class="control control--checkbox">
                                                            <input type="checkbox" name="resourcetype[]" value="Articles" <?php echo in_array("Articles", $resourceTypeArray) ? 'checked' : '' ?> disabled="true"> Articles
                                                            <div class="control__indicator"></div>
                                                        </label>

                                                        <label class="control control--checkbox">
                                                            <input type="checkbox" name="resourcetype[]" value="eBooks" <?php echo in_array("eBooks", $resourceTypeArray) ? 'checked' : '' ?> disabled="true"> eBooks
                                                            <div class="control__indicator"></div>
                                                        </label>

                                                        <label class="control control--checkbox">
                                                            <input type="checkbox" name="resourcetype[]" value="Other" <?php echo in_array("Other", $resourceTypeArray) ? 'checked' : '' ?> disabled="true"> Other
                                                            <div class="control__indicator"></div>
                                                        </label>

                                                    </div>

                                                    <div class="col-xs-12 form-group">
                                                        <label>Resources </label>
                                                        <?php
                                                        $t = explode('.', $page_data['resources']);
                                                        $document_extension = $t[sizeof($t) - 1];
                                                        $video_url = base_url("assets/uploaded_videos/resourses/");
                                                        ?>

                                                        <?php if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif'])) { ?>
                                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $page_data['resources']; ?>" />
                                                        <?php } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx', 'pptx', 'ppt'])) { ?>
                                                            <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $page_data['resources']); ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                        <?php } else if (in_array($document_extension, ['mp4', 'm4a', 'm4v', 'f4v', 'f4a', 'm4b', 'm4r', 'f4b', 'mov'])) { ?>
                                                            <video controls style="width:100%; height:auto;">
                                                                <source src="<?php echo $video_url . $page_data['resources']; ?>" type='video/mp4'>
                                                            </video>
                                                        <?php } else { ?>
                                                            <iframe src="<?php echo 'https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $page_data['resources']); ?>&embedded=true" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                        <?php } ?>

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
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>