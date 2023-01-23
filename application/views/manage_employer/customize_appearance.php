<style>
ul.select2-selection__rendered li{
    height: auto  !important;
}
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><a class="dashboard-link-btn" href="<?php echo base_url('appearance') ?>"><i class="fa fa-chevron-left"></i>Back To Themes</a>Customize Your Theme</span>
                        </div>
                        <div id="carousel">
                            <div class="carousel">
                                <?php if ($theme["theme_name"] == 'theme-1') { ?>
                                    <div class="customize_portal">
                                        <div class="row">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Heading and Apply Now button:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2">           
                                                        <input type="text"  class="form-control" id="titleColor" name="title_color" value="<?= $theme["title_color"] ?>" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Sub-heading Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control"  name="heading_color" value="<?= $theme["heading_color"] ?>" required="">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Job Description and Requirement Heading Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control"  name="font_color" value="<?= $theme["font_color"] ?>" required="">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Join Our Talent Network button color:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headerFooterColor" name="hf_bgcolor" value="<?= $theme["hf_bgcolor"] ?>" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Footer Color:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headerFooterColor" name="f_bgcolor" value="<?= $theme["f_bgcolor"] ?>" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                
                                                
                                        <?php   if($job_fair_configuration) { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label>
                                                            <span>Enable Job Fair:</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control control--checkbox">&nbsp;
                                                                <input type="checkbox" name="job_fair" <?php echo ($theme['theme4_enable_job_fair_homepage'] == 1 ? 'checked="checked"' : ''); ?> value="1">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                        <?php   } ?>
                                                
                                        <?php   if($job_fair_configuration) { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label>
                                                            <span>Job Fair Forms:</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="input-group"> 
                                                            <div class=" mb-20">
                                                            <select class="jsSelect2" name="job_fair_homepage_page_url[]" multiple>
                                        <?php               if(empty($job_fair_multiple_forms)) { echo '<option value="">Default</option>'; } ?>
                                        <?php                       
                                                                                    $jobFairMultiples = explode(',', $theme['job_fair_homepage_page_url']);
                                                                                    foreach($job_fair_multiple_forms as $jfmf) { ?>
                                                                        <option value="<?php echo $jfmf['page_url']; ?>" <?php echo (in_array($jfmf['page_url'], $jobFairMultiples) ? 'selected' : ''); ?>><?php echo $jfmf['title']; ?></option>
                                        <?php                       } ?>
                                                            </select>
                                                        </div>
                                                        </div>
                                                    </div>
                                        <?php   } else { ?>
                                                    <input type="hidden" name="job_fair_homepage_page_url" value="" >
                                        <?php   } ?>
                                                
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label>
                                                        <span>Header Banner Image:</span>
                                                        <em>Required Image Dimensions: 2000 x 700</em>
                                                    </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <div class="upload-file invoice-fields">
                                                            <span class="selected-file" id="name_pictures">No file selected</span>
                                                            <input type="file" name="pictures" id="pictures" onchange="check_file('pictures')">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (isset($theme['pictures']) && $theme['pictures'] != "") { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label><span>Current Header Image:</span></label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="input-group"> 
                                                            <div id="remove_image" class="logo-box2">
                                                                <img src="<?php echo AWS_S3_BUCKET_URL . $theme['pictures']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-panel">
                                                        <input type="hidden" name="sid" value="<?= $theme['sid'] ?>">
                                                        <input type="hidden" name="action" value="update">
                                                        <input type="hidden" name="theme_name" id="" value="<?= $theme['theme_name']?>">
                                                        <input class="submit-btn" type="submit" value="Apply" name="submit">
                                                        <input class="submit-btn" type="button" id="<?= $theme['sid'] ?>" value="Restore to Default" onclick="restoreDefault(this.id, '<?= $theme['theme_name'] ?>');">
                                                        <a class="submit-btn btn-cancel" href="<?php echo base_url('appearance') ?>">Back</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php } else if ($theme["theme_name"] == 'theme-2') { ?>
                                    <div class="customize_portal">
                                        <div class="row">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Theme Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headingColor" name="body_bgcolor" value="<?= $theme["body_bgcolor"] ?>" required="" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Header Active Menu Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headingColor" name="hf_bgcolor" value="<?= $theme["hf_bgcolor"] ?>" required="" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Text Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control"  name="font_color" value="<?= $theme["font_color"] ?>" required="" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Job Description and Requirement Heading Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control"  name="title_color" value="<?= $theme["title_color"] ?>" required="">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Join Our Talent Network button color:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headerFooterColor" name="heading_color" value="<?= $theme["heading_color"] ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                
                                        <?php   if($job_fair_configuration) { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label>
                                                            <span>Enable Job Fair:</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control control--checkbox">&nbsp;
                                                                <input type="checkbox" name="job_fair" <?php echo ($theme['theme4_enable_job_fair_homepage'] == 1 ? 'checked="checked"' : ''); ?> value="1">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                        <?php   } ?>
                                                
                                                
                                        <?php   if($job_fair_configuration) { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label>
                                                            <span>Job Fair Forms:</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="input-group"> 
                                                            <div class=" mb-20">
                                                            <select class="jsSelect2" name="job_fair_homepage_page_url[]" multiple>
                                        <?php               if(empty($job_fair_multiple_forms)) { echo '<option value="">Default</option>'; } ?>
                                        <?php                       
                                                                                    $jobFairMultiples = explode(',', $theme['job_fair_homepage_page_url']);
                                        foreach($job_fair_multiple_forms as $jfmf) { ?>
                                                                        <option value="<?php echo $jfmf['page_url']; ?>" <?php echo (in_array($jfmf['page_url'], $jobFairMultiples) ? 'selected' : ''); ?>><?php echo $jfmf['title']; ?></option>
                                        <?php                       } ?>
                                                            </select>
                                                        </div>
                                                        </div>
                                                    </div>
                                        <?php   } else { ?>
                                                    <input type="hidden" name="job_fair_homepage_page_url" value="" >
                                        <?php   } ?>
                                                
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label>
                                                        <span>Header Banner Image: </span>
                                                        <em>Required Image Dimensions: 2000 x 467</em>
                                                    </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2 "> 
                                                        <div class="upload-file invoice-fields">
                                                            <span class="selected-file" id="name_pictures">No file selected</span>
                                                            <input type="file" name="pictures" id="pictures" onchange="check_file('pictures')">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (isset($theme["pictures"]) && $theme["pictures"] != "") { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label><span>Current Header Image:</span></label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="input-group"> 
                                                            <div id="remove_image" class="logo-box2">
                                                                <div ><img src="<?php echo AWS_S3_BUCKET_URL . $theme["pictures"]; ?>"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-panel">
                                                        <input type="hidden" name="sid" value="<?= $theme["sid"] ?>">
                                                        <input type="hidden" name="action" value="update">
                                                        <input type="hidden" name="theme_name" id="" value="<?= $theme["theme_name"] ?>">
                                                        <input class="submit-btn" type="submit" value="Apply" name="submit">
                                                        <input class="submit-btn" type="button" id="<?= $theme["sid"] ?>" value="Restore to Default" onclick="restoreDefault(this.id, '<?= $theme["theme_name"] ?>');">
                                                        <a class="submit-btn btn-cancel" href="<?php echo base_url('appearance') ?>">Back</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php } elseif ($theme["theme_name"] == 'theme-3') { ?>
                                    <div class="customize_portal">
                                        <div class="row">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Theme Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headingColor" name="body_bgcolor" value="<?= $theme["body_bgcolor"] ?>" required="" placeholder="Theme Color">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Font Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control"  name="font_color" value="<?= $theme["font_color"] ?>" required="" placeholder="Font Color">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Header Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control"  name="f_bgcolor" value="<?= $theme["f_bgcolor"] ?>" required="" placeholder="Header Color">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Header Active Menu Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headingColor" name="heading_color" value="<?= $theme["heading_color"] ?>" required="" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Job Description and Requirement Heading Color: </span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control"  name="title_color" value="<?= $theme["title_color"] ?>" required="">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label><span>Join Our Talent Network button color:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2"> 
                                                        <input type="text" class="form-control" id="headerFooterColor" name="hf_bgcolor" value="<?= $theme["hf_bgcolor"] ?>" placeholder="Header background Color">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                
                                                
                                        <?php   if($job_fair_configuration) { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label>
                                                            <span>Enable Job Fair:</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control control--checkbox">&nbsp;
                                                                <input type="checkbox" name="job_fair" <?php echo ($theme['theme4_enable_job_fair_homepage'] == 1 ? 'checked="checked"' : ''); ?> value="1">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                        <?php   } ?>
                                                
                                        <?php   if($job_fair_configuration) { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label>
                                                            <span>Job Fair Forms:</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="input-group"> 
                                                            <div class=" mb-20">
                                                            <select class="jsSelect2" name="job_fair_homepage_page_url[]" multiple>
                                        <?php               if(empty($job_fair_multiple_forms)) { echo '<option value="">Default</option>'; } ?>
                                        <?php                       
                                            $jobFairMultiples = explode(',', $theme['job_fair_homepage_page_url']);
                                            
                                        foreach($job_fair_multiple_forms as $jfmf) { ?>
                                                                        <option value="<?php echo $jfmf['page_url']; ?>" <?php echo (in_array($jfmf['page_url'], $jobFairMultiples) ? 'selected' : ''); ?>><?php echo $jfmf['title']; ?></option>
                                        <?php                       } ?>
                                                            </select>
                                                        </div>
                                                        </div>
                                                    </div>
                                        <?php   } else { ?>
                                                    <input type="hidden" name="job_fair_homepage_page_url" value="" >
                                        <?php   } ?>
                                                
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label>
                                                        <span>Banner Image:</span>
                                                        <em>Required Image Dimensions: 1920 x 568</em>
                                                    </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="input-group demo2 "> 
                                                        <div class="upload-file invoice-fields">
                                                            <span class="selected-file" id="name_pictures">No file selected</span>
                                                            <input type="file" name="pictures" id="pictures" onchange="check_file('pictures')">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (isset($theme['pictures']) && $theme['pictures'] != "") { ?>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                        <label><span>Current Background Image:</span></label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                        <div class="input-group"> 
                                                            <div id="remove_image" class="logo-box2">
                                                                <div ><img src="<?php echo AWS_S3_BUCKET_URL . $theme["pictures"]; ?>"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <div class="btn-panel">
                                                        <input type="hidden" name="sid" value="<?= $theme["sid"] ?>">
                                                        <input type="hidden" name="action" value="update">
                                                        <input type="hidden" name="theme_name"  value="<?= $theme["theme_name"] ?>">
                                                        <input class="submit-btn" type="submit" value="Apply" name="submit">
                                                        <input class="submit-btn" type="button" id="<?= $theme["sid"] ?>" value="Restore to Default" onclick="restoreDefault(this.id, '<?= $theme["theme_name"] ?>');">
                                                        <a class="submit-btn btn-cancel" href="<?php echo base_url('appearance') ?>">Back</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php } ?>

                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat magnam recusandae consequatur sequi voluptate iste quis eius, vero laudantium autem corrupti harum quam repellendus nesciunt quidem doloremque omnis adipisci dolores?
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
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/docs.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-colorpicker.min.css " />

<script type="text/javascript">
        function restoreDefault(id, theme_name) {
            url = "<?= base_url() ?>appearance/restore_default";
            alertify.confirm("Confirmation", "Are you sure to Restore this Theme to Default?",
                    function () {
                        $.post(url, {
                            action: "restore",
                            sid: id,
                            theme_name: theme_name
                        })
                                .done(function (data) {
                                    window.location.href = '<?= base_url() ?>appearance'
                                });

                    },
                    function () {
                    });

        }
        $(function(){
            $('.demo2').colorpicker();
        });
        
        function check_file(val) {
            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                
                if (val == 'pictures') {
                    if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                        $("#" + val).val(null);
                        alertify.error("Please select a valid Image format.");
                        $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                        return false;
                    } else
                        return true;
                }
            } else {
                $('#name_' + val).html('No file selected');
            }
        }
         //
    $(document).ready(function(){
        $('.jsSelect2').select2({ closeOnSelect: false});
    });
</script>



<script>
    // Plugin for video uploader
    (function(w, d){
        $.fn.videoOpt = function(opt){
            // Save the current instance
            let _this = this.length > 1 ? this[0] : this;
            //
            if(typeof opt === 'string'){
                switch(opt){
                    case "get": return instances[_this.selector];
                }
                return;
            }
            //
            var 
            oFile = {},
            //
            randKey = Math.ceil(Math.random() * 1000),
            //
            options = {};
            //
            instances[_this.selector] = oFile;
            //
            options['s3'] =  opt !== undefined && opt.s3 || `https://automotohrattachments.s3.amazonaws.com/`;
            options['placeholderImage'] =  opt !== undefined && opt.placeholderImage || '';
            options['fileLimit'] =  -1;
            options['allowedTypes'] = opt !== undefined && opt.allowedTypes || ['mp4']
            options['text'] = opt !== undefined && opt.text || `Click / Drag to upload`;
            options['onSuccess'] = opt !== undefined && opt.onSuccess || function(){};
            options['onError'] = opt !== undefined && opt.onError || function(){};
            options['onClear'] = opt !== undefined && opt.onClear || function(){};
            
            //
            options['mainDivName'] = `jsUploadArea${randKey}`;
            options['mainImageViewer'] = `jsUploadedImageArea${randKey}`;
            options['errorMSG'] = `jsUploadedAreaError${randKey}`;
            options['jsMFUPreviewFile'] = `jsMFUPreviewFile${randKey}`;
            options['jsMFUClearFile'] = `jsMFUClearFile${randKey}`;
            options['jsMFUModal'] = `jsMFUModal${randKey}`;
            options['jsMFUD'] = `jsMFUD${randKey}`;
            
            //
            let errorCodes = {
                size: `File size exceeded from ${options.fileLimit}`,
                type: `Invalid file type.`
            };
        };
    })(window, document);

</script>