<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-chevron-left"></i>Dashboard</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <a href="<?php echo base_url('documents_management/upload_new_document'); ?>" class="btn btn-success">Upload Document</a>
                                    <a href="<?php echo base_url('documents_management/generate_new_document'); ?>" class="btn btn-success">Generate New Document</a>
                                    <a href="<?php echo base_url('documents_management/generate_new_offer_letter'); ?>" class="btn btn-success">Generate New Offer Letter</a>
                                    <a href="<?php echo base_url('documents_management/people_with_pending_documents'); ?>" class="btn btn-success">Employees With Pending Documents</a>
                                </div>
<!--                                <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">
                                    </div>
                                <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">
                                    
                                </div>
                                <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">
                                    
                                </div>-->
                            </div>
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <div class="hr-document-list">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th class="col-xs-4">Document Name</th>
                                            <th class="col-xs-2">Type</th>
                                            <th class="col-xs-2">Uploaded Date</th>
                                            <th class="col-xs-4 text-center" colspan="3">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            
                        <?php           $document_exists = 0;
                                        $generated_document_exists = 0;
                                        $offer_letter_exists = 0;
                                        
                                        if(!empty($documents)) {
                                            $document_exists = 1;
                                            foreach ($documents as $document) { ?>
                                            <tr>
                                                <td><?php echo $document['document_name']; ?></td>
                                                <td><?php echo ucfirst($document['document_type']); ?></td>
                                                <td class="col-xs-9"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $document['date_uploaded'])->format('F j, Y h:i A'); ?></td>
                                                <td class="col-xs-1">
                                                    <a href="<?php echo base_url('documents_management/edit_document_info/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                </td>
                                                <td class="col-xs-1">
                                                    <button class="btn btn-info btn-sm btn-block"
                                                            onclick="fLaunchModal(this);"
                                                            data-preview-url="<?php echo $document["preview_url"]; ?>"
                                                            data-download-url="<?php echo $document["download_url"]; ?>"
                                                            data-file-name="<?php echo $document['document_original_name']; ?>"
                                                            data-document-title="<?php echo $document['document_original_name']; ?>">Preview</button>
                                                </td>
                                                <td class="col-xs-1">
                                                    <form id="form_archive_uploaded_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                    </form>
                                                    <button onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>);" class="btn btn-warning btn-sm btn-block">Archive</button>
                                                </td>
                                            </tr>
                                        <?php }
                                        } 

                                        if(!empty($generated_documents)) {
                                            $generated_document_exists = 1;
                                            foreach ($generated_documents as $document) { ?>
                                                <tr>
                                                    <td><?php echo $document['document_title']; ?></td>
                                                    <td>Online Document</td>
                                                    <td><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $document['created_date'])->format('F j, Y h:i A'); ?></td>
                                                    <td class="col-xs-1">
                                                        <a href="<?php echo base_url('documents_management/edit_document_info/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                    </td>
                                                    <td class="col-xs-1">
                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                    </td>
                                                    <td class="col-xs-1">
                                                        <form id="form_archive_generated_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="archive_generated_document" />
                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                        </form>
                                                        <button onclick="func_archive_generated_document(<?php echo $document['sid']; ?>);" class="btn btn-warning btn-sm btn-block">Archive</button>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } ?>
                        <?php
                                        if(!empty($offer_letters)) {
                                            $offer_letter_exists = 1;
                                            foreach ($offer_letters as $offer_letter) { ?>
                                                <tr>
                                                    <td><?php echo $offer_letter['letter_name']; ?></td>
                                                    <td>Offer Letter</td>
                                                    <td><?php echo $offer_letter['created_date'] != NULL ? DateTime::createFromFormat('Y-m-d H:i:s', $offer_letter['created_date'])->format('F j, Y h:i A') : 'N/A'; ?></td>
                                                    <td class="col-xs-1">
                                                        <a href="<?php echo base_url('documents_management/edit_offer_letter/' . $offer_letter['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                    </td>
                                                    <td class="col-xs-1">
                                                        <button onclick="func_get_generated_document_preview(<?php echo $offer_letter['sid']; ?>,'offer');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                    </td>
                                                    <td class="col-xs-1">
                                                        <button onclick="func_archive_offer_letter(<?php echo $offer_letter['sid'];?>);" type="button" class="btn btn-warning btn-sm btn-block">Archive</button>
                                                        <form id="form_archive_offer_letter_<?php echo $offer_letter['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="archive_offer_letter" />
                                                            <input type="hidden" id="offer_letter_sid" name="offer_letter_sid" value="<?php echo $offer_letter['sid']; ?>" />
                                                        </form>
                                                    </td>
    <!--                                                <td class="col-xs-1">-->
    <!--                                                    <button onclick="func_delete_offer_letter(--><?php //echo $offer_letter['sid'];?><!--//);" type="button" class="btn btn-danger btn-sm btn-block">Delete</button>
    <!--                                                    <form id="form_delete_offer_letter_--><?php //echo $offer_letter['sid']; ?><!--" method="post" enctype="multipart/form-data" action="--><?php //echo current_url(); ?><!--">-->
    <!--                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_offer_letter" />-->
    <!--                                                        <input type="hidden" id="offer_letter_sid" name="offer_letter_sid" value="--><?php //echo $offer_letter['sid']; ?><!--" />-->
    <!--                                                    </form>-->
    <!--                                                </td>-->
                                                </tr>
                                        <?php }
                                        } 
                                        
                                        if($document_exists == 0 && $generated_document_exists ==0 && $offer_letter_exists ==0) { ?>
                                                <tr><td colspan="4" class="text-center">No Documents Found!</td></tr>
                        <?php           } ?>
                                                
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

<script>
    function func_un_archive_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this offer letter?',
            function () {
                $('#form_un_archive_offer_letter_' + offer_letter_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this offer letter?',
            function () {
                $('#form_delete_offer_letter_' + offer_letter_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this offer letter?',
            function () {
                $('#form_archive_offer_letter_' + offer_letter_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this document?',
            function () {
                $('#form_delete_generated_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_unarchive_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this document?',
            function () {
                $('#form_unarchive_generated_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this document?',
            function () {
                $('#form_archive_generated_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_get_generated_document_preview(document_sid, doc_flag = 'generated') {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'source': doc_flag
            }
        });

        my_request.done(function (response) {
            $('#popupmodalbody').html(response);
            $('#popupmodallabel').html('Preview Generated Document');

            $('#popupmodal .modal-dialog').css('width', '60%');
            $('#popupmodal').modal('toggle');
        });
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                    console.log('in office docs check');
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    //console.log('in images check');
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                default :
                    //console.log('in google docs check');
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });
    }

    function func_unarchive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this document?',
            function () {
                $('#form_unarchive_uploaded_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this document?',
            function () {
                $('#form_archive_uploaded_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }


    function func_delete_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this document?',
            function () {
                $('#form_delete_uploaded_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    $(function () {
        $("#settings-tabs").tabs();
        $("#home-accordion").accordion({
            collapsible: true
        });

        $('#file_image').on('change', function () {
            $('#image').val('');
        });

        $(".tab_content").hide();
        $(".tab_content:first").show();

        $("ul.tabs li").click(function () {
            $("ul.tabs li").removeClass("active");
            $(this).addClass("active");
            $(".tab_content").hide();
            var activeTab = $(this).attr("rel");
            $("#" + activeTab).fadeIn();
        });

        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });
</script>

<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
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