<!-- Main Start -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="btn-panel">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <a class="page-heading" href="<?php echo base_url('add_hr_document') ?>">+ Add Document</a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <a class="page-heading" href="javascript:;" data-toggle="modal" data-target="#offer_letter_modal">+ Add Offer Letter</a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <?php if ($page == 'all') { ?>
                                    <a class="page-heading" href="<?php echo base_url('archived_document') ?>">Archived Document (<?php echo $archiveDocCount; ?>)</a>

                                <?php } else if ($page == 'archive') { ?>
                                    <a class="page-heading" href="<?php echo base_url('hr_documents') ?>">All Document (<?php echo $allDocCount; ?>)</a>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="create-job-wrap">
                        <?php if (count($documents) == 0 && (isset($offerLetters) &&  count($offerLetters) == 0)) { ?>
                            <div class="archived-document-area">
                                <div class="cloud-icon"><i class="fa fa-cloud-upload"></i></div>
                                <div class="archived-heading-area">
                                    <?php if ($page == 'all') { ?>
                                        <h2>All Documents Have Been Archived... Upload New HR Docs!</h2>
                                    <?php } else if ($page == 'archive') { ?>
                                        <h2>No Archived Document!</h2>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                        } else {
                            if (count($documents) > 0) {
                                ?>
                                <div class="table-responsive">
                                    <h3>HR Documents</h3>
                                    <div class="hr-document-list">
                                        <table class="hr-doc-list-table">
                                            <thead>
                                                <tr>                                                
                                                    <th width="30%">Document Name</th>
                                                    <th width="20%">Type&nbsp;[?]</th>
                                                    <th width="15%">Included In <br>Onboarding&nbsp;[?]</th>
                                                    <th width="15%">Action <br>Required&nbsp;[?]</th>
                                                    <th width="20%"></th>    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($documents as $document) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $document['document_original_name']; ?></td>
                                                        <td><?php echo ucfirst($document['document_type']); ?></td>
                                                        <td>
                                                            <div class="onoffswitch">
                                                                <input type= "checkbox" name ="onoffswitch" class = "onoffswitch-checkbox" onclick="update_data('onboarding', this.id);" id="switch-on-boarding_<?php echo $document['sid'] ?>" <?php if ($document['onboarding'] == '1') { ?> checked <?php } ?>>
                                                                <label class = "onoffswitch-label" for="switch-on-boarding_<?php echo $document['sid'] ?>">
                                                                    <span class = "onoffswitch-inner"></span>
                                                                    <span class = "onoffswitch-switch"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class = "onoffswitch">
                                                                <input type = "checkbox" name = "onoffswitch" class = "onoffswitch-checkbox" onclick="update_data('action', this.id);"  id="action-switch_<?php echo $document['sid'] ?>" <?php if ($document['action_required'] == '1') { ?> checked <?php } ?>>
                                                                <label class = "onoffswitch-label" for = "action-switch_<?php echo $document['sid'] ?>">
                                                                    <span class = "onoffswitch-inner"></span>
                                                                    <span class = "onoffswitch-switch"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class = "archive-btn-wrp">
                                                                <a class = "action-btn more-action-btn" href = "javascript:;">more</a>
                                                                <ul class = "dropdown-menu">
                                                                    <li>
                                                                        <a href="<?php echo base_url('edit_hr_document') ?>/<?php echo $document['sid'] ?>"><i class = "fa fa-pencil"></i>Edit</a>
                                                                        <?php if ($page == 'all') { ?>
                                                                            <a href="javascript:;" id="archive_<?php echo $document['sid'] ?>" onclick="archive_document('archive', 0, this.id)"><i class = "fa fa-trash"></i> Archive</a>
                                                                        <?php } else if ($page == 'archive') { ?>
                                                                            <a href="javascript:;" id="archive_<?php echo $document['sid'] ?>" onclick="archive_document('unarchive', 1, this.id)"><i class = "fa fa-trash"></i>Unarchive</a>
                                                                        <?php } ?>
                                                                    </li>
                                                                </ul>
                                                                <a  href="javascript:;" data-toggle="modal" data-target="#document_<?php echo $document['sid'] ?>" class = "action-btn enable-bs-tooltip" title="View and Download">
                                                                    <i class = "fa fa-download"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }
                             if (isset($offerLetters) &&  count($offerLetters) == 0) { ?>
                                <div class="table-responsive">
                                    <h3>Offer Letters</h3>
                                    <div class="hr-document-list">
                                        <table class="hr-doc-list-table">
                                            <thead>
                                                <tr>                                                
                                                    <th width="40%">Offer Letter Name</th>
                                                    <th width="30%">Type&nbsp;[?]</th>
                                                    <th width="20%"></th>    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($offerLetters as $offerLetter) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $offerLetter['letter_name']; ?></td>
                                                        <td><?php echo "Offer Letter" ?></td>
                                                        <td>
                                                            <div class = "archive-btn-wrp">
                                                                <a class = "action-btn more-action-btn" href = "javascript:;">more</a>
                                                                <ul class = "dropdown-menu">
                                                                    <li>
                                                                        <a href="javascript:;" id="<?php echo $offerLetter['sid'] ?>" onclick="delete_offer_letter(this.id)"><i class = "fa fa-trash"></i> Delete</a>
                                                                    </li>
                                                                </ul>
                                                                <a  href="javascript:;" data-toggle="modal" data-target="#offer_letter_<?php echo $offerLetter['sid'] ?>" class = "action-btn">
                                                                    <i class = "fa fa-pencil"></i>
                                                                    <span class = "btn-tooltip">Edit</span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <form method="POST" action="<?php echo base_url('update_offer_letter') ?>" >
                                                    <div class="modal fade" id="offer_letter_<?php echo $offerLetter['sid']; ?>" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">Offer Letter Template</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="universal-form-style-v2">
                                                                        <ul>
                                                                            <li class="form-col-50-left">
                                                                                <label>Template Name:<span class="staric">*</span></label>
                                                                                <input type="text" class="invoice-fields" name="letter_name" value="<?php echo $offerLetter['letter_name']; ?>">
                                                                            </li>
                                                                            <div class="description-editor template-letter-body">
                                                                                <label>Template Letter Body:<span class="staric">*</span></label>
                                                                                <textarea class="ckeditor"  name="letter_body"  cols="167" rows="6">
                                                                                    <?php echo $offerLetter['letter_body']; ?>           
                                                                                </textarea>
                                                                            </div>
                                                                            <input type="hidden" name="fromPage" value="hr document" >
                                                                            <input type="hidden" name="offer_letter_id" value="<?php echo $offerLetter['sid']; ?>" >
                                                                            <input type="submit" value="Update Offer Letter"  onclick=""  class="submit-btn">
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }
                            if ( isset($employees) && count($employees) > 0) {
                                ?>
                                <div class="table-responsive">
                                    <h3>Employees with Pending Document Actions</h3>
                                    <div class="hr-document-list">
                                        <table class="hr-doc-list-table">
                                            <thead>
                                                <tr>                                                
                                                    <th>Employee Name</th>
                                                    <th>Email</th>
                                                    <th style="text-align: right" >View Document(s)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($employees as $employee) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ucfirst($employee['first_name']); ?> <?php echo $employee['last_name']; ?></td>
                                                        <td><?php echo $employee['email']; ?></td>
                                                        <td>
                                                            <a  href="<?php echo base_url('employee_document'); ?>/<?php echo $employee['sid']; ?>" class="action-btn">
                                                                <i class="fa fa-eye"></i>
                                                                <span class="btn-tooltip">View</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="table-responsive">
                                    <h3>Employees with Pending Document Actions</h3>
                                    <div class="hr-document-list">
                                        <table class="hr-doc-list-table">
                                            <thead>
                                                <tr>                                                
                                                    <th>Employee Name</th>
                                                    <th>Email</th>
                                                    <th style="text-align: right" >View Document(s)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="3">No employee with pending document(s)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <?php if(!empty($sections)) { ?>
                            <?php foreach($sections as $section) { ?>
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <strong class="text-center" style="font-size: 16px;">
                                                    <?php echo $section['title']; ?>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php echo html_entity_decode($section['description']); ?>
                                            </div>
                                        </div>
                                        <?php if($section['video_status'] == 1) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div align="center" class="embed-responsive embed-responsive-16by9">
                                                        <video controls class="embed-responsive-item">
                                                            <source src="https://hr-documents-videos.s3.amazonaws.com/<?php echo $section['video']; ?>" type="video/mp4">
                                                        </video>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                        <?php } ?>

                                        <?php if($section['youtube_video_status'] == 1) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div align="center" class="embed-responsive embed-responsive-16by9">
                                                        <iframe src="https://www.youtube.com/embed/<?php echo $section['youtube_video']; ?>" frameborder="0" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>



                        <div class = "tick-list-box width-100">
                            <h2>About HR Docs</h2>
                            <ul>
                                <li>Automated HR document distribution</li>
                                <li>Tracking of receipt/acknowledgment</li>
                                <li>Upload docs for new hires and employees</li>
                                <li>Unlimited document storage</li>
                                <li>Revokes access for terminated employees</li>
                                <li>Supports Adobe PDF&reg;
                                    and Microsoft Word&reg;
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Main End -->
<div class="modal fade" id="offer_letter_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Offer Letter Template</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo base_url('save_offer_letter') ?>" id="offer_letter">
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <label>Template Name:<span class="staric">*</span></label>
                                <input type="text" class="invoice-fields" name="letter_name" value="">
                            </li>
                            <div class="description-editor template-letter-body">
                                <div class="row">
                                    <div class="col-md-8 col-xs-12">
                                        <label>Template Letter Body:<span class="staric">*</span></label>
                                        <textarea class="ckeditor"  name="letter_body"  cols="167" rows="6">
                                            <?php echo $dummyOfferLetter['letter_body']; ?>           
                                        </textarea>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="offer-letter-help-widget">
                                            <div class="how-it-works-insturction">
                                                <strong>How it's Works :</strong>
                                                <p class="how-works-attr">1. Add your offer letter text</p>
                                                <p class="how-works-attr">2. Add data from tags below</p>
                                                <p class="how-works-attr">3. Save the template</p>
                                            </div>

                                            <div class="tags-arae">
                                                <strong>Tags :</strong> (select tag from below)
                                                <ul class="tags">
                                                    <li>{{company_name}}</li>
                                                    <li>{{date}}</li>
                                                    <li>{{firstname}}</li>
                                                    <li>{{position}}</li>
                                                    <li>{{start_date}}</li>
                                                    <li>{{expire_date}}</li>
                                                </ul>
                                            </div>

                                            <div class="how-it-works-insturction">
                                                <strong>Have an offer letter in Word or PDF ?</strong>
                                                <p class="how-works-attr">1. Copy and paste your text into this editor</p>
                                                <p class="how-works-attr">2. Insert tags where applicable</p>
                                                <p class="how-works-attr">3. Save the template</p>
                                            </div>

                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="submit" value="Save Offer Letter"  onclick="validate_form();"  class="submit-btn">
                                </div>
                            </div>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php   if(count($documents)) {
            foreach ($documents as $document) { ?>
                <div id="document_<?php echo $document['sid'] ?>" class="modal fade file-uploaded-modal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo substr($document['document_original_name'], 0, 19); ?> </h4>
                                <a href="<?php echo AWS_S3_BUCKET_URL . $document['document_name'] ?>" download="download" >Download</a>
                            </div>
                            <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . urlencode($document['document_name']); ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
<?php       }
        } ?>
<script type = "text/javascript">
    //$('.ckeditor').ckeditor();

    function validate_form() {
        $("#offer_letter").validate({
            ignore: ":hidden:not(select)",
            rules: {
                letter_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                }
            },
            messages: {
                letter_name: {
                    required: 'Offter letter name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    
    $(document).ready(function () {
        $('.more-action-btn').click(function () {
            $(this).next().slideToggle();
            $(".more-action-btn").not(this).next().slideUp();
        });
    });

    function update_data(type, longId) {
        res = longId.split("_");
        id = res[1];
        url = "<?= base_url() ?>hr_documents/document_tasks";
        value = $('#' + longId + ':checked').val();
        if (value == 'on') {
            $.post(url, {type: type, sid: id, value: 1})
                    .done(function (data) {
                        alertify.success('Changes saved successfully');
                    });
        } else {
            $.post(url, {type: type, sid: id, value: 0})
                .done(function (data) {
                    alertify.success('Changes saved successfully');
                });
        }
    }

    function archive_document(action, value, longId) {
        res = longId.split("_");
        id = res[1];
        url = "<?= base_url() ?>hr_documents/document_tasks";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Document?",
        function () {
            $.post(url, {type: 'archive', sid: id, value: value, action: action})
                    .done(function (data) {
                        location.reload();
                    });
        },
        function () {
        });
    }

    function delete_offer_letter(id) {
        url = "<?= base_url() ?>hr_documents/delete_offer_letter";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Offer Letter?",
        function () {
            $.post(url, {sid: id, action: 'delete'})
            .done(function (data) {
                location.reload();
            });
        },
        function () {
        });
    }
</script>