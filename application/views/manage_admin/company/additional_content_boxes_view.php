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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>    
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Company Dashboard</a>
                                        <a href="<?php echo base_url('manage_admin/companies'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>                                   
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="">
                                                    <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/additional_content_boxes/add/' . $company_sid); ?>">Add New Box</a>
                                                </div>
                                                <div class="hr-promotions table-responsive">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-2">Title</th>
                                                                <th class="col-xs-2">Status</th>
                                                                <th class="col-xs-2">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($additional_boxes)) { ?>
                                                                <?php foreach($additional_boxes as $box) { ?>
                                                                    <tr>
<!--                                                                        <td><?php //echo date('m-d-Y', strtotime(str_replace('-', '/', $box['created_date']))); ?></td>-->
                                                                        <td><?php echo ucwords($box['title']); ?></td>
                                                                        <td><?php echo $box['status'] ? 'Active' : 'Inactive'?></td>
                                                                        <td>
                                                                            <a href="<?php echo base_url('manage_admin/additional_content_boxes/edit/' . $box['sid']); ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> |
                                                                            <a href="<?php echo base_url('manage_admin/additional_content_boxes/delete/' . $box['sid'] . '/' . $company_sid); ?>" class="btn btn-danger btn-sm" onclick="return confirm('You really want to delete it?')"><i class="fa fa-times"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="col-lg-6">No additional boxes found!</td>
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
        </div>
    </div>
</div>

<script>
    function fDeleteNote(note_sid){
        alertify.confirm(
            'Are You sure?',
            'Are you sure you want to delete this note?',
            function () {
                //Ok

                $('#form_delete_note_' + note_sid).submit();
            },
            function (){
                //Cancel
            }
        )
    }
</script>