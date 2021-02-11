<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
           <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>"><i class="fa fa-chevron-left"></i>Group Management</a>
                                    <?php echo $group_name; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                            <i class="fa fa-file-text"></i>
                                            <?php echo $title; ?>
                                        </div>
                                    <p class="upload-file-type">You can easily Assign documents for group</p>
                                    <div class="form-wrp">
                                        <form id="form_new_document_group" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" name="perform_action" value="assign_documents" />
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <label class="control control--checkbox pull-left">
                                                        <input type="checkbox" id="selectall">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <h4 class="hr-registered pull-left">
                                                        Select Documents
                                                    </h4>
                                                    <div class="text-right">(<span id="count_documents"></span>) Assigned</div>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="row">
                                                        <!-- system documents start -->
                                                        <div class="col-xs-12">
                                                            <h4><strong>System Documents</strong></h4>
                                                        </div> 
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                W4 Fillable
                                                                <input class="doc_checkbox" name="system_documents[]" value="w4" type="checkbox" <?php echo $group['w4'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>    
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                W9 Fillable
                                                                <input class="doc_checkbox" name="system_documents[]" value="w9" type="checkbox" <?php echo $group['w9'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>    
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                I9 Fillable
                                                                <input class="doc_checkbox" name="system_documents[]" value="i9" type="checkbox" <?php echo $group['i9'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>    
                                                            </label>
                                                        </div>
                                                        <!-- system documents end -->

                                                        <!-- company documents start -->
                                                        <div class="col-xs-12">
                                                            <hr>
                                                            <h4><strong>Company Documments</strong></h4>
                                                        </div>    
                                                        <?php if (!empty($documents)) { 
                                                                foreach ($documents as $key => $document) { 
                                                                    $cat_name = 'documents'; ?>
                                                                    <div class="col-xs-6">
                                                                        <label class="control control--checkbox font-normal <?php echo $document['archive'] == 1 ? 'red' :''; ?>">
                                                                            <?php $document_status = $document['archive'] == 1 ? '(Archive)' :'(Active)'; ?>
                                                                            <?php echo $document['document_title']. '' .$document_status; ?>
                                                                            <?php if($document['archive'] != 1) { ?>
                                                                                <input class="doc_checkbox" name="documents[]" value="<?php echo $document['sid']; ?>" type="checkbox" <?php echo in_array($document['sid'], $assigned_documents) ? 'checked="checked"' : ''; ?>>
                                                                                <div class="control__indicator"></div>
                                                                            <?php } else { ?>  
                                                                                <input class="disable_doc_checkbox" name="documents[]" type="checkbox">
                                                                                <div class="control__indicator"></div>
                                                                            <?php } ?>    
                                                                        </label>
                                                                    </div>
                                                        <?php   } ?>
                                                        <?php } else { ?>
                                                            <div class="col-xs-12 text-center">
                                                                <span class="no-data">No Documents</span>
                                                            </div>
                                                        <?php } ?>
                                                        <!-- company documents end -->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <div class="col-xs-12" style="text-align: right;">
                                                        <button type="submit" id="gen_boc_btn" class="btn btn-success" onclick="validate_form();">Save</button>
                                                        <a href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>" class="btn black-btn">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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

<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>

    $(document).ready(function() {
        $('#count_documents').text($('.doc_checkbox:checked').length);
        $(".disable_doc_checkbox").click(function(e) {
            e.preventDefault();
            alertify.error('Archive document not allowed to select!');
        });

        $('input[type="checkbox"]').click(function(){
            $('#count_documents').text($('.doc_checkbox:checked').length);
        });
    });

    $('#selectall').click(function (event) { 
        if (this.checked) { 
            $('.doc_checkbox').each(function () { 
                this.checked = true;  
            });
        } else {
            $('.doc_checkbox').each(function () { 
                this.checked = false;
            });
        }
    });
</script>