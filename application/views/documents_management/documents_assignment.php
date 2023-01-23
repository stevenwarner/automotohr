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

                                    <a class="dashboard-link-btn" href="<?php echo base_url('documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>

                                    <?php echo $title; ?>

                                </span>

                            </div>

                            <div class="row">

                                <div class="col-xs-12">

                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-xs-12">

                                    <div class="hr-box">

                                        <div class="hr-box-header">

                                            <?php echo ucwords($user_type); ?> Information

                                        </div>

                                        <div class="hr-innerpadding">

                                            <div class="table-responsive">

                                                <table class="table table-bordered table-hover table-stripped">

                                                    <tbody>

                                                        <tr><th class="col-xs-2">Name</th><td class="col-xs-10"><?php echo ucwords($user_info['first_name'] . ' ' . $user_info['last_name']); ?></td></tr>

                                                        <tr><th class="col-xs-2">Email</th><td class="col-xs-10"><?php echo strtolower($user_info['email']); ?></td></tr>

                                                        <tr><th class="col-xs-2">Phone</th><td class="col-xs-10"><?php echo strtolower($user_info['phone']); ?></td></tr>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-xs-12">

                                    <div class="hr-box">

                                        <div class="hr-box-header">

                                            Uploaded Documents

                                        </div>

                                        <div class="hr-innerpadding">

                                            <div class="table-responsive">

                                                <table class="table table-condensed table-bordered table-hover table-striped">

                                                    <thead>

                                                        <tr>

                                                            <th class="col-xs-9">Document</th>

                                                            <th class="col-xs-3 text-center" colspan="3">Actions</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php if(!empty($uploaded_documents)) { ?>

                                                            <?php foreach($uploaded_documents as $document) { ?>

                                                                <tr>

                                                                    <td>

                                                                        <strong><?php echo $document['document_name']; ?></strong>

                                                                        <p><small><?php echo $document['document_description']; ?></small></p>

                                                                    </td>

                                                                    <td>

                                                                        <?php if(!in_array($document['sid'], $assign_u_doc_sids)) { ?>

                                                                            <form id="form_assign_document_uploaded_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">

                                                                                <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />

                                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />

                                                                                <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />

                                                                                <input type="hidden" id="document_type" name="document_type" value="uploaded" />

                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />

                                                                                <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />

                                                                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $user_sid; ?>" />

                                                                            </form>

                                                                            <button onclick="func_assign_document('uploaded', <?php echo $document['sid']; ?>);" class="btn btn-success btn-block btn-sm">Assign</button>

                                                                        <?php } else { ?>

                                                                            <form id="form_remove_document_uploaded_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">

                                                                                <input type="hidden" id="perform_action" name="perform_action" value="remove_document" />

                                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />

                                                                                <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />

                                                                                <input type="hidden" id="document_type" name="document_type" value="uploaded" />

                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />

                                                                                <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />

                                                                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $user_sid; ?>" />

                                                                            </form>

                                                                            <button onclick="func_remove_document('uploaded', <?php echo $document['sid']; ?>);" class="btn btn-danger btn-block btn-sm">Remove</button>

                                                                        <?php } ?>

                                                                    </td>

                                                                    <td>

                                                                        <button class="btn btn-info btn-sm btn-block"

                                                                                onclick="fLaunchModal(this);"

                                                                                data-preview-url="<?php echo $document["preview_url"]; ?>"

                                                                                data-download-url="<?php echo $document["download_url"]; ?>"

                                                                                data-file-name="<?php echo $document['document_original_name']; ?>"

                                                                                data-document-title="<?php echo $document['document_original_name']; ?>">View Original</button>

                                                                    </td>

                                                                    <td>

                                                                        <?php if(in_array($document['sid'], $assign_u_doc_sids)) { ?>

                                                                            <button class="btn btn-info btn-sm btn-block"

                                                                                    onclick="fLaunchModal(this);"

                                                                                    data-preview-url="<?php echo $assign_u_doc_urls[$document['sid']]; ?>"

                                                                                    data-download-url="<?php echo $assign_u_doc_urls[$document['sid']]; ?>"

                                                                                    data-file-name="<?php echo $document['document_original_name']; ?>"

                                                                                    data-document-title="<?php echo $document['document_original_name']; ?>">View Assigned</button>

                                                                        <?php } else { ?>

                                                                            <button type="button" class="btn btn-info btn-sm btn-block disabled" disabled="disabled">Not Assigned</button>

                                                                        <?php }?>

                                                                    </td>

                                                                </tr>

                                                            <?php } ?>

                                                        <?php } else { ?>

                                                            <tr>

                                                                <td class="text-center">

                                                                    <span class="no-data">No Uploaded Documents</span>

                                                                </td>

                                                            </tr>

                                                        <?php }?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-xs-12">

                                    <div class="hr-box">

                                        <div class="hr-box-header">

                                            Generated Documents

                                        </div>

                                        <div class="hr-innerpadding">

                                            <div class="table-responsive">

                                                <table class="table table-condensed table-bordered table-hover table-striped">

                                                    <thead>

                                                    <tr>

                                                        <th class="col-xs-9">Document</th>

                                                        <th class="col-xs-3 text-center" colspan="3">Actions</th>

                                                    </tr>

                                                    </thead>

                                                    <tbody>

                                                    <?php if(!empty($generated_documents)) { ?>

                                                        <?php foreach($generated_documents as $document) { ?>

                                                            <tr>

                                                                <td>

                                                                    <strong><?php echo $document['document_title']; ?></strong>

                                                                </td>

                                                                <td>

                                                                    <?php if(!in_array($document['sid'], $assign_g_doc_sids)) { ?>

                                                                        <form id="form_assign_document_generated_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">

                                                                            <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />

                                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />

                                                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />

                                                                            <input type="hidden" id="document_type" name="document_type" value="generated" />

                                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />

                                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />

                                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $user_sid; ?>" />

                                                                        </form>

                                                                        <button onclick="func_assign_document('generated', <?php echo $document['sid']; ?>);" class="btn btn-success btn-block btn-sm">Assign</button>

                                                                    <?php } else { ?>

                                                                        <form id="form_remove_document_generated_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">

                                                                            <input type="hidden" id="perform_action" name="perform_action" value="remove_document" />

                                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />

                                                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />

                                                                            <input type="hidden" id="document_type" name="document_type" value="generated" />

                                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />

                                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />

                                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $user_sid; ?>" />

                                                                        </form>

                                                                        <button onclick="func_remove_document('generated', <?php echo $document['sid']; ?>);" class="btn btn-danger btn-block btn-sm">Remove</button>

                                                                    <?php } ?>

                                                                </td>

                                                                <td>

                                                                    <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>);" class="btn btn-info btn-sm btn-block">View Original</button>

                                                                </td>

                                                                <td>

                                                                    <?php if(in_array($document['sid'], $assign_g_doc_sids)) { ?>

                                                                        <a href="<?php echo base_url('documents_management/view_g_document/' . $user_type . '/' . $user_sid . '/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">View Assigned</a>

                                                                        <!--<button onclick="func_get_generated_document_preview(<?php /*echo $document['sid']; */?>, 'assigned');" class="btn btn-info btn-sm btn-block">View Assigned</button>-->

                                                                    <?php } else { ?>

                                                                        <button type="button" class="btn btn-info btn-sm btn-block disabled" disabled="disabled">Not Assigned</button>

                                                                    <?php }?>

                                                                </td>

                                                            </tr>

                                                        <?php } ?>

                                                    <?php } else { ?>

                                                        <tr>

                                                            <td class="text-center">

                                                                <span class="no-data">No Generated Documents</span>

                                                            </td>

                                                        </tr>

                                                    <?php }?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-xs-12">

                                    <div class="hr-box">

                                        <div class="hr-box-header">

                                            I9 & W4 Form

                                        </div>

                                        <div class="hr-innerpadding">

                                            <div class="table-responsive">

                                                <table class="table table-condensed table-bordered table-hover table-striped">

                                                    <thead>

                                                    <tr>

                                                        <th class="col-xs-9">Document</th>

                                                        <th class="col-xs-3 text-center" colspan="3">Actions</th>

                                                    </tr>

                                                    </thead>

                                                    <tbody>

                                                    <?php if(!empty($i9_form)) { ?>

                                                        <tr>

                                                            <td>

                                                                <strong>I9 Form</strong>

                                                            </td>

                                                            <td>

                                                                <?php if($user_type == 'applicant'){?>

                                                                    <a href="<?php echo base_url('form_i9/' . $user_type . '/' . $user_sid . '/' . $job_list_sid); ?>" class="btn btn-success btn-sm btn-block">View Form</a>

                                                                <?php } else{?>

                                                                    <a href="<?php echo base_url('form_i9/' . $user_type . '/' . $user_sid); ?>" class="btn btn-success btn-sm btn-block">View Form</a>

                                                                <?php }?>

                                                            </td>

                                                        </tr>



                                                    <?php } if(!empty($w4_form)) { ?>

                                                        <tr>

                                                            <td>

                                                                <strong>W4 Form</strong>

                                                            </td>

                                                            <td>

                                                                <?php if($user_type == 'applicant'){?>

                                                                    <a href="<?php echo base_url('form_w4/' . $user_type . '/' . $user_sid . '/' . $job_list_sid); ?>" class="btn btn-success btn-sm btn-block">View Form</a>

                                                                <?php } else{?>

                                                                    <a href="<?php echo base_url('form_w4/' . $user_type . '/' . $user_sid); ?>" class="btn btn-success btn-sm btn-block">View Form</a>

                                                                <?php }?>

                                                            </td>

                                                        </tr>

                                                    <?php } if(empty($i9_form) && empty($w4_form)) { ?>

                                                        <tr colspan="12">

                                                            <td class="text-center">

                                                                <span class="no-data">No Forms Found</span>

                                                            </td>

                                                        </tr>

                                                    <?php }?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-xs-12">

                                    <div class="hr-box">

                                        <div class="hr-box-header">

                                            Old Documents

                                        </div>

                                        <div class="hr-innerpadding">

                                            <div class="table-responsive">

                                                <table class="table table-bordered table-striped table-hover table-condensed">

                                                    <thead>

                                                        <tr>

                                                            <th class="col-xs-3">Name</th>

                                                            <th class="col-xs-4">Document</th>

                                                            <th class="col-xs-1">Sent</th>

                                                            <th class="col-xs-1 text-center">Acknowledge</th>

                                                            <th class="col-xs-1 text-center">Downloaded</th>

                                                            <th class="col-xs-1 text-center">Uploaded</th>

                                                            <th class="col-xs-1 text-center">Actions</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                    <?php if(!empty($old_pending_documents)) {

                                                        foreach ($old_pending_documents as $old_pending_document) {

                                                            ?>

                                                            <tr>

                                                                <td><?php echo !empty($old_pending_document['user']) ? $old_pending_document['user']['first_name'] . ' ' . $old_pending_document['user']['last_name'] : 'Not Available'; ?></td>

                                                                <td><?php echo $old_pending_document['document']['document_original_name'];?></td>

                                                                <td><?php echo date_with_time($old_pending_document['sent_on']);?></td>

                                                                <td class="text-center"><?php echo $old_pending_document['acknowledged'] == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ;?></td>

                                                                <td class="text-center"><?php echo $old_pending_document['downloaded'] == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ;?></td>

                                                                <td class="text-center"><?php echo $old_pending_document['uploaded'] == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ;?></td>

                                                                <td class="text-center"><a target="_blank" href="<?php echo base_url('edit_hr_document/' . $old_pending_document['document_sid']); ?>" class="btn btn-info btn-sm btn-block">View</a></td>

                                                            </tr>

                                                        <?php }

                                                    }else { ?>

                                                        <tr>

                                                            <td colspan="12" class="text-center">

                                                                <span class="no-data">No Documents Found</span>

                                                            </td>

                                                        </tr>

                                                    <?php }?>

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



<!-- Modal -->

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



<script>



    function func_remove_document(type, document_sid) {

        alertify.confirm(

            'Are you sure?',

            'Are you sure you want to assign this document?',

            function () {

                $('#form_remove_document_' + type + '_' + document_sid).submit();

            },

            function () {

                alertify.error('Cancelled!');

            });

    }







    function func_assign_document(type, document_sid) {

        alertify.confirm(

            'Are you sure?',

            'Are you sure you want to assign this document?',

            function () {

                $('#form_assign_document_' + type + '_' + document_sid).submit();

            },

            function () {

                alertify.error('Cancelled!');

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



    function func_get_generated_document_preview(document_sid, source = 'original'){

        var my_request;

        my_request = $.ajax({

            'url': '<?php echo base_url('documents_management/ajax_responder'); ?>',

            'type': 'POST',

            'data': {

                'perform_action': 'get_generated_document_preview',

                'document_sid' : document_sid,

                'user_type': '<?php echo $user_type; ?>',

                'user_sid': <?php echo $user_sid; ?>,

                'source': source

            }

        });



        my_request.done(function(response){

            $('#popupmodalbody').html(response);

            $('#popupmodallabel').html('Preview Generated Document');



            $('#popupmodal .modal-dialog').css('width', '60%');

            $('#popupmodal').modal('toggle');

        });

    }

</script>
<?php $this->load->view('form_i9/pop_up_info'); ?>
<script>

$('.modal').on('show.bs.modal', function(){
    $('body').css('overflow-y', 'hidden');
});
$('#update_i9_employer_section_modal').on('hidden.bs.modal', function(){
    $('body').css('overflow-y', 'auto');
});

$('.modalShow').click(function(event){       
    event.preventDefault();
    var info_id = $(this).attr("src");
    var title_string = $(this).parent().text(); 
    var model_title = title_string.replace("*", "");
    if (info_id == "section_2_alien_number") {
        if($('#alien_authorized_to_work').is(':checked')) { 
            info_id = 'section_21_alien_number'; 
        }
    }
    var model_content =  $('#'+info_id).html(); 
    var mymodal = $('#myModal');
    mymodal.find('.modal-title').text(model_title);
    mymodal.find('.modal-body').html(model_content);
    mymodal.modal('show');
});
</script>

<style>.modal{ overflow-y: auto; }</style>