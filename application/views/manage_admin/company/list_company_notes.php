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
                                        <h1 class="page-title"><i class="fa fa-users"></i>Company <?php echo $this->uri->segment(4); ?> Notes</h1>    
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Company Dashboard</a>
                                        <a href="<?php echo base_url('manage_admin/companies'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>
                                        
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="">
                                                    <a class="site-btn" href="<?php echo base_url('manage_admin/company_notes/add/' . $company_sid); ?>">Add New Note</a>
                                                </div>
                                                <div class="hr-promotions table-responsive">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-2">Created</th>
                                                                <th class="col-xs-2">Created By</th>
                                                                <th class="col-xs-8">Note Text</th>
                                                                <th colspan="2" class="col-xs-2">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($company_notes)) { ?>
                                                                <?php foreach($company_notes as $note) { ?>
                                                                    <tr>
                                                                        <td><?php echo date('m-d-Y', strtotime(str_replace('-', '/', $note['created_date']))); ?></td>
                                                                        <td><?php echo ucwords($note['admin_first_name'] . ' ' . $note['admin_last_name']); ?></td>
                                                                        <td><?php echo $note['note_text']?></td>
                                                                        <td>
                                                                            <a href="<?php echo base_url('manage_admin/company_note/edit/' . $note['company_sid'] . '/' . $note['sid']); ?>" class="hr-edit-btn invoice-links">Edit</a>
                                                                        </td>
                                                                        <td>
                                                                            <form id="form_delete_note_<?php echo $note['sid']; ?>" action="<?php echo base_url('manage_admin/company_note/delete/')?>" method="post" enctype="multipart/form-data">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_note" />
                                                                                <input type="hidden" id="note_sid" name="note_sid" value="<?php echo $note['sid']; ?>" />
                                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $note['company_sid']; ?>" />
                                                                                <input type="hidden" id="note_type" name="note_type" value="<?php echo $note['note_type']; ?>" />
                                                                                <input type="hidden" id="note_text" name="note_text" value="<?php echo htmlentities($note['note_text']); ?>" />
                                                                                <button type="button" class="hr-delete-btn invoice-links" onclick="fDeleteNote(<?php echo $note['sid']; ?>);">Delete</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td colspan="4">No <?php echo $this->uri->segment(4); ?> notes found!</td>
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