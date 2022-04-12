
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">        

        <div class="row">
            <div class="col-xs-12">
                <label>Instructions / Guidance </label>
                <textarea class="invoice-fields autoheight ckeditor" name="document_description" onkeyup="check_length()" id="document_description" cols="54" rows="6"><?php
                if (isset($document_info['document_description'])) {
                    $desc = strip_tags(html_entity_decode($document_info['document_description']));
                        echo set_value('document_description', $desc);
                    } else {
                        echo set_value('document_description');
                    } ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <label>Browse Document<?php echo !isset($document_info) ? '<span class="staric">*</span>' : '';?></label>
                <input type="file" style="display: none;" id="jsFileUpload" />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 margin-top">
                <p>
                    Include in Onboarding: 
                    <strong>
                         <?php 
                        if (isset($document_info['onboarding']) && $document_info['onboarding'] == '1') {
                            echo "Yes";
                        } else {
                            echo "No";
                        }
                    ?>  
                    </strong>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 margin-top">
                <p>
                    Acknowledgment Required: 
                    <strong>
                        <?php 
                            if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '1') {
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                        ?>  
                    </strong>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 margin-top">
                <p>
                    Download Required: 
                    <strong>
                        <?php 
                            if (isset($document_info['download_required']) && $document_info['download_required'] == '1') {
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                        ?>  
                    </strong>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 margin-top">
                <p>
                    Re-Upload Required: 
                    <strong>
                        <?php 
                            if (isset($document_info['signature_required']) && $document_info['signature_required'] == '1') {
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                        ?>  
                    </strong>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 margin-top">
                <p>
                    Sort Order: 
                    <strong>
                        <?php if (isset($document_info['sort_order'])) echo $document_info['sort_order']; ?>  
                    </strong>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="hr-box">
                    <div class="hr-box-header">
                        <strong>Assign Video:</strong>
                    </div>
                    <div class="hr-innerpadding">
                        <div class="universal-form-style-v2">
                            <ul>
                                <?php if (isset($document_info['video_source']) && !empty($document_info['video_source']) && $document_info['video_required'] == 1) { ?>
                                    <input type="hidden" id="old_doc_video_url" value="<?php echo $document_info['video_url']; ?>">
                                    <input type="hidden" id="old_doc_video_source" value="<?php echo $document_info['video_source']; ?>">
                                    <li class="form-col-100 autoheight" style="width:100%; height:500px !important;">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <figure class="">
                                                <?php $source = $document_info['video_source']; ?>
                                                <?php if($source == 'youtube') { ?>
                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $document_info['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                <?php } elseif($source == 'vimeo') { ?>
                                                    <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $document_info['video_url']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                <?php } else {?>
                                                    <video controls style="width:100%; height:500px !important;">
                                                        <source src="<?php echo base_url().'assets/uploaded_videos/'.$document_info['video_url']; ?>" type='video/mp4'>
                                                        <p class="vjs-no-js">
                                                          To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                          <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                                        </p>
                                                    </video>
                                                <?php } ?>
                                            </figure>
                                        </div>
                                    </li>
                                <?php } ?>
                                <li class="form-col-100 autoheight edit_filter">
                                    <label for="video_source">Video Source</label>
                                    <?php $document_video_source = 'not_required';

                                        if(isset($document_info['video_required']) && $document_info['video_required'] == 1){
                                            $document_video_source = $document_info['video_source'];
                                        }
                                    ?>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo NO_VIDEO; ?>
                                        <input class="video_source" type="radio" id="video_source_youtube" name="video_source" <?php echo $document_video_source == 'not_required' ? 'checked="checked"': ''; ?> value="not_required">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo YOUTUBE_VIDEO; ?>
                                        <input class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube" <?php echo $document_video_source == 'youtube' ? 'checked="checked"': ''; ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo VIMEO_VIDEO; ?>
                                        <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo" <?php echo $document_video_source == 'vimeo' ? 'checked="checked"': ''; ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo UPLOAD_VIDEO; ?>
                                        <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload" <?php echo $document_video_source == 'upload' ? 'checked="checked"': ''; ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>
                                <li class="form-col-100" id="yt_vm_video_container">
                                    <input type="text" name="yt_vm_video_url" value="" class="invoice-fields" id="yt_vm_video_url">
                                    <?php echo form_error('yt_vm_video_url'); ?>
                                </li>
                                <li class="form-col-100 autoheight edit_filter" id="up_video_container" style="display: none">
                                        <?php
                                            if (!empty($document_info['video_url']) && $document_info['video_source'] == 'upload') {
                                        ?>
                                                <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $document_info['video_url']; ?>">
                                        <?php
                                            } else {
                                        ?>
                                            <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="">
                                        <?php
                                            }
                                        ?>
                                    <div class="upload-file invoice-fields">
                                        <span class="selected-file" id="name_video_upload"></span>
                                        <input type="file" name="video_upload" id="video_upload" onchange="video_check('video_upload')" >
                                        <a href="javascript:;">Choose Video</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($document_groups)) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="hr-box">
                        <div class="hr-box-header">
                            <label class="control control--checkbox" style="margin-bottom: 16px;">
                                <input type="checkbox" name="jsGroupAll" id="jsGroupAll" />
                                <div class="control__indicator"></div>
                            </label>
                            <script>
                                $('#jsGroupAll').click(function(){
                                    $('input[name="document_group_assignment[]"]').prop('checked', $(this).prop('checked'));
                                });
                            </script>
                            <strong>
                            Document Group Management:</strong>
                        </div>
                        <div class="hr-innerpadding">
                            <div class="universal-form-style-v2">
                                <?php $assigned_documents = array(); ?>
                                <?php foreach ($document_groups as $key => $document) { ?>
                                    <?php $cat_name = 'documents'; ?>
                                    <div class="col-xs-6">
                                        <label class="control control--checkbox font-normal">
                                            <?php echo $document['name']; ?>
                                                <input class="disable_doc_checkbox" name="document_group_assignment[]" type="checkbox" value="<?php echo $document['sid'];?>" <?php echo in_array($document['sid'], $pre_assigned_groups) ? 'checked="checked"' : ''; ?>>
                                                <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if(!empty($active_categories)) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <label>Categories</label><br>
                    <div class="Category_chosen">
                        <select data-placeholder="Please Select" multiple="multiple" onchange="" name="categories[]" id="createcategories" class="categories">
                            <?php if (sizeof($active_categories) > 0) { ?>
                                <?php foreach ($active_categories as $category) { ?>
                                    <option <?= isset($assigned_categories) && in_array($category['sid'],$assigned_categories)? "selected":"" ?> value="<?php echo $category['sid']; ?>" ><?= $category['name'] ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <br>
        <?php } ?>
        <?php if(isset($document_info['sid'])) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <label class="control control--checkbox font-normal">
                        Convert To Pay Plan
                        <input class="disable_doc_checkbox" name="to_pay_plan" type="checkbox" value="yes" />
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <br />
        <?php } ?>
        <?php $this->load->view('hr_documents_management/partials/visibility'); ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="hr-box">
                    <div class="hr-box-header">
                        <strong>Automatically assign document after:</strong>
                    </div>
                    <div class="hr-innerpadding">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="">
                                    <div class="">
                                        <label class="control control--radio">
                                            Days
                                            <input type="radio" name="assign_type" value="days" />
                                            <div class="control__indicator"></div>
                                        </label> &nbsp;
                                        <label class="control control--radio font-normal">
                                            Months
                                            <input type="radio" name="assign_type" value="months" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />

                        <div class="row">
                            <div class="col-xs-6 js-type-days js-type">
                                <div class="universal-form-style-v2">
                                    <div class="input-group pto-time-off-margin-custom">
                                        <input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-days">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 js-type-months js-type">
                                <div class="universal-form-style-v2">
                                    <div class="input-group pto-time-off-margin-custom">
                                        <input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-months">
                                        <span class="input-group-addon">Months</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br />
    </div>
</div>  

<style type="text/css">
    .fort{
            box-sizing: border-box;
    list-style: none;
    margin: 0;
    padding: 0 5px;
    width: 100%;
    }
</style>