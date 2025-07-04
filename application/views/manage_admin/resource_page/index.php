<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><i class="fa fa-files-o"></i><?php echo $page_title; ?></h1>
                                                    </div>
                                                    <div class="hr-setting-page">
                                                        <form enctype="multipart/form-data" id="form_dynamic_page" method="post" action="<?php echo current_url(); ?>">
                                                            <?php $temp = isset($page_data['sid']) ?  $page_data['sid'] : '0'; ?>
                                                            <input type="hidden" id="perform_action" name="perform_action" value="save_page_data" />
                                                            <input type="hidden" id="page_sid" name="page_sid" value="<?php echo $temp; ?>" />
                                                            <input type="hidden" id="page_name" name="page_name" value="resources" />
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row field-row-autoheight">
                                                                        <?php $temp = isset($page_data['sid']) ?  $page_data['page_title'] : ''; ?>
                                                                        <?php echo form_label('Page Title', 'page_title')?>
                                                                        <?php echo form_input('page_title', set_value('page_title', $temp), 'class="hr-form-fileds" id="page_title"'); ?>
                                                                        <?php echo form_error('page_title'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row field-row-autoheight">
                                                                        <?php $temp = isset($page_data['sid']) ? html_entity_decode($page_data['page_content']) : ''; ?>
                                                                        <?php echo form_label('Page Content', 'page_content')?>
                                                                        <?php echo form_textarea('page_content', set_value('page_content', $temp, false), 'class="ckeditor" id="page_content" rows="5"'); ?>
                                                                        <?php echo form_error('page_content'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row field-row-autoheight">
                                                                        <?php $temp = isset($page_data['sid']) ?  $page_data['page_status'] : '0'; ?>
                                                                        <?php echo form_label('Page Status', 'page_status')?>
                                                                        <label class="control control--radio admin-access-level">
                                                                            <?php $default_checked = $temp == 1 ? true : false ;?>
                                                                            <?php echo form_radio('page_status', 1, set_radio('page_status', 1, $default_checked));?>
                                                                            Active
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio admin-access-level">
                                                                            <?php $default_checked = $temp == 0 ? true : false ;?>
                                                                            <?php echo form_radio('page_status', 0, set_radio('page_status', 0, $default_checked));?>
                                                                            In-Active
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row field-row-autoheight">
                                                                        <?php /*echo form_label('Page Banner');*/?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="well well-sm">
                                                                        <?php /*$temp = isset($page_data['sid']) ?  $page_data['page_banner_image'] : ''; */?>
                                                                        <div class="row">
                                                                            <div class="col-xs-12">
                                                                                <img src="<?php /*echo AWS_S3_BUCKET_URL . $temp*/?>" class="img-responsive" alt="Page Banner Image"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php /*echo form_label('Page Image', 'page_banner_image');*/?>
                                                                    <input type="file" class="" id="page_banner_image" name="page_banner_image" />
                                                                    <?php /*echo form_error('page_banner_image'); */?>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <?php /*$temp = isset($page_data['sid']) ?  $page_data['page_banner_video'] : ''; */?>
                                                                    <?php /*if(!empty($temp)) { */?>
                                                                        <div class="well well-sm">
                                                                            <div class="row">
                                                                                <div class="col-xs-12">
                                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php /*echo $temp; */?>"></iframe>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php /*} */?>
                                                                    <div class="field-row field-row-autoheight">
                                                                        <?php /*$temp = !empty($temp) ? 'https://www.youtube.com/watch?v=' . $temp : ''; */?>
                                                                        <?php /*echo form_label('Page Video', 'page_banner_video')*/?>
                                                                        <?php /*echo form_input('page_banner_video', set_value('page_banner_video', $temp), 'class="hr-form-fileds" id="page_banner_video"'); */?>
                                                                        <?php /*echo form_error('page_banner_video'); */?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row field-row-autoheight">
                                                                        <?php /*$temp = isset($page_data['sid']) ?  $page_data['page_banner_type'] : 'image'; */?>
                                                                        <?php /*echo form_label('Page Banner Type', 'page_banner_type')*/?>
                                                                        <label class="control control--radio admin-access-level">
                                                                            <?php /*$default_checked = $temp == 'disabled' ? true : false ;*/?>
                                                                            <?php /*echo form_radio('page_banner_type', 'disabled', set_radio('page_banner_type', 'disabled', $default_checked));*/?>
                                                                            No Banner
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio admin-access-level">
                                                                            <?php /*$default_checked = $temp == 'image' ? true : false ;*/?>
                                                                            <?php /*echo form_radio('page_banner_type', 'image', set_radio('page_banner_type', 'image', $default_checked));*/?>
                                                                            Display Image
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio admin-access-level">
                                                                            <?php /*$default_checked = $temp == 'video' ? true : false ;*/?>
                                                                            <?php /*echo form_radio('page_banner_type', 'video', set_radio('page_banner_type', 'video', $default_checked));*/?>
                                                                            Display Video
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            -->


                                                        <?php if (check_access_permissions_for_view($security_details, 'save_resources')) { ?>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row field-row-autoheight">
                                                                        <br />
                                                                        <button type="submit" id="btn_submit" class="btn btn-success">Save</button>
                                                                        <a href="" class="btn black-btn">Cancel</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php } ?>
                                                        </form>

                                                        <hr />

                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php if(isset($page_data['sid'])) { ?>
                                                                    <div class="hr-box">
                                                                        <div class="hr-box-header">
                                                                            <strong>Page Sections</strong>
                                                                            <?php if (check_access_permissions_for_view($security_details, 'add_resources')) { ?>
                                                                                <a href="<?php echo base_url('manage_admin/resource_page/add_section/' . $page_data['sid']);?>" class="btn btn-sm btn-success pull-right">Add Section</a>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="hr-innerpadding">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered table-hover table-striped">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th class="col-xs-8">Section Title</th>
                                                                                        <th class="col-xs-1">Status</th>
                                                                                        <th class="col-xs-1">Sort Order</th>
                                                                                        <th class="col-xs-2" colspan="2">Actions</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php if(!empty($page_data['sections'])) { ?>
                                                                                        <?php foreach($page_data['sections'] as $section) { ?>
                                                                                            <tr>
                                                                                                <td><?php echo $section['title']; ?></td>
                                                                                                <td><?php echo $section['status'] == 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">In-Active</span>'; ?></td>
                                                                                                <td><?php echo $section['sort_order']; ?></td>
                                                                                                <?php if (check_access_permissions_for_view($security_details, 'edit_resources')) { ?>
                                                                                                    <td>
                                                                                                        <a href="<?php echo base_url('manage_admin/resource_page/edit_section/' . $section['sid']); ?>" class="btn btn-success btn-sm">Edit</a>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                                <?php if (check_access_permissions_for_view($security_details, 'delete_resources')) { ?>
                                                                                                    <td>
                                                                                                        <form id="form_delete_section_<?php echo $section['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url();?>">
                                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_section" />
                                                                                                            <input type="hidden" id="section_sid" name="section_sid" value="<?php echo $section['sid']; ?>" />
                                                                                                        </form>
                                                                                                        <button onclick="func_delete_section(<?php echo $section['sid']; ?>);" type="button" class="btn btn-danger btn-sm">Delete</button>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <tr>
                                                                                            <td class="text-center" colspan="4">
                                                                                                <span class="no-data">No Sections</span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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

<script>
    function func_delete_section(section_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this section?',
            function() {
                $('#form_delete_section_' + section_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }
</script>