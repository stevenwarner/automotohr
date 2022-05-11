<?php 
    $document_center_url = '';
    if ($user_type == 'applicant') {
        $document_center_url = base_url('hr_documents_management/documents_assignment').'/'.$user_type .'/'.$users_sid.'/'.$job_list_sid;
    } else {  
        $document_center_url = base_url('hr_documents_management/documents_assignment').'/'.$user_type .'/'.$users_sid;
    } 

    $action_btn_flag = true;
    if ($pp_flag == 1) {
        $action_btn_flag = false;
    }
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $document_center_url; ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-title-section">
                                    <h2><?php echo $document['document_title']; ?></h2>
                                </div>

                                <div class="col-xs-12">
                                    <?php if($document['document_type'] == 'offer_letter' && $action_btn_flag == true) { ?>
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong>Manage Assign and Signed date</strong>
                                            </div>
                                            <div class="panel-body">
                                                <div class="document-action-required" style="padding: 14px;">
                                                    <b>Add new date for assign and signed offer letter</b>
                                                </div>
                                                <div class="document-action-required">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label>Assign Date:</label>
                                                        <input class="invoice-fields date_time_picker" value="<?php echo date('m-d-Y', strtotime($document['assigned_date'])); ?>" type="text" id="assign_date">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label>Signed Date:</label>
                                                        <input class="invoice-fields date_time_picker" value="<?php echo !empty($document['signature_timestamp']) ? date('m-d-Y', strtotime($document['signature_timestamp'])) : date('m-d-Y'); ?>" type="text" id="signed_date">
                                                    </div> 
                                                    <input type="hidden" value="<?php echo $document['sid']; ?>" id="document_sid">                                   
                                                </div>
                                                <div class="document-action-required" style="padding: 14px;">
                                                    <button type="button" class="btn btn-success pull-right save_offer_letter_date" style="margin-top: 14px;">Save</button>         
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong>Remove Document</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="document-action-required">
                                                <b>Remove This document by clicking Remove Document Button</b>
                                            </div>
                                            <div class="document-action-required">
                                                <strong class="text-danger">Document Status:</strong> Active Document                                          
                                            </div>
                                            <div class="document-action-required">
                                                <button onclick="deactivate_document(<?php echo $document['sid']; ?>)" type="button" class="btn btn-danger pull-right">Remove Document</button>
                                            </div>
                                        </div>
                                    </div>


                                   <?php  if($document['isdoctolibrary']==1){ ?>
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong>Document Center</strong>
                                        </div>
                                        <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <p>Is the document visible to employee on document center?</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                                      <?php 
                                                             if($document['visible_to_document_center']==1){
                                                                    $visibletodocumentcenter1 = 'checked="true"';
                                                                }else{
                                                                    $visibletodocumentcenter0 = 'checked="true"';
                                                          }?>
                                                                    <div class="col-xs-12">
                                                                        <label class="control control--radio font-normal">
                                                                        <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="0" <?php echo $visibletodocumentcenter0;?> />
                                                                            No &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="1" <?php echo $visibletodocumentcenter1;?> />
                                                                            Yes &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                           
                                            <div class="document-action-required">
                                                <button onclick="document_visible(<?php echo $document['sid']; ?>)" type="button" class="btn btn-primary pull-right">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                    <?php if($document['acknowledgment_required'] == 1) { ?>
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong><?php echo $acknowledgment_action_title; ?></strong>
                                            </div>
                                            <div class="panel-body">
                                                <div class="document-action-required">
                                                    <?php echo $acknowledgment_action_desc; ?>
                                                </div>
                                                <div class="document-action-required">
                                                    <?php echo $acknowledgement_status;?>
                                                </div>
                                                <div class="document-action-required">
                                                    <form id="form_acknowledge_document" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" name="perform_action" value="acknowledge_document" />
                                                    </form>
                                                    <?php if($document['acknowledged_date'] != NULL ) {
                                                    echo '<b>Acknowledged On: </b>';
                                                    echo convert_date_to_frontend_format($document['acknowledged_date']);
                                                     } ?>
                                                    <button onclick="<?php echo $acknowledgement_button_action;?>" type="button" class="btn <?php echo $acknowledgement_button_css; ?> pull-right"><?php echo $acknowledgement_button_txt;?></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if($document['download_required'] == 1) { ?>
                                        <div class="panel panel-success">
                                            <div class="panel-heading ">
                                                <strong><?php echo $download_action_title; ?></strong>
                                            </div>
                                            <div class="panel-body">
                                                <div class="document-action-required">
                                                    <?php echo $download_action_desc; ?>
                                                </div>
                                                <div class="document-action-required">
                                                    <?php echo $download_status; ?>
                                                </div>
                                                <div class="document-action-required">
                                                    <form id="func_acknowledge_document_download" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" name="perform_action" value="acknowledge_document_download" />
                                                    </form>
                                                    <?php if ($document['downloaded_date'] != NULL ) {
                                                        echo '<b>Downloaded On: </b>';
                                                        echo convert_date_to_frontend_format($document['downloaded_date']);
                                                        $btn_class = 'btn-warning';
                                                    } else { 
                                                        $btn_class = 'btn-success';
                                                    }  ?>    

                                                    <?php if ($download_button_type == 'button' && $document['document_type'] == 'uploaded') { ?>
                                                        <button onclick="<?php echo $download_button_action; ?>" type="button" class="btn <?php echo $download_button_css; ?> pull-right"><?php echo $download_button_txt;?></button>
                                                    <?php } else if ($download_button_type == 'text' && $document['document_type'] == 'uploaded') { 
                                                        echo '<br>';
                                                        echo '<b>Downloaded Status: </b>';
                                                        echo $download_button_txt;
                                                    } ?> 

                                                    <a target="_blank" href="<?php echo $original_download_url; ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">
                                                        Download Assigned Document
                                                    </a>
                                                    <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                        <a
                                                            target="_blank"
                                                            href="<?=base_url('hr_documents_management/pd/assigned/print/both/'.( $document['sid'] ).'');?>"
                                                            class="btn btn-success pull-right margin-right"
                                                        >
                                                        Print Assigned Document    
                                                        </a>
                                                    <?php } else if ($document['document_type'] == 'generated') { ?>
                                                        <a target="_blank" href="<?php echo $original_print_url; ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">
                                                            Print Assigned Document
                                                        </a>
                                                    <?php } else { ?>
                                                        <?php 
                                                            $document_filename = $document['document_s3_name'];
                                                            $document_file = pathinfo($document_filename); 
                                                            $document_extension = $document_file['extension'];
                                                            $name = explode(".",$document_filename); 
                                                            $url_segment_original = $name[0];
                                                        ?>
                                                        <?php if ($document_extension == 'pdf') { ?>
                                                            <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_original.'.pdf' ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">Print Assigned Document</a>
                                                            
                                                        <?php } else if ($document_extension == 'docx') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edocx&wdAccPdf=0' ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">Print Assigned Document</a> 
                                                        <?php } else if ($document_extension == 'doc') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edoc&wdAccPdf=0' ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">Print Assigned Document</a> 
                                                        <?php } else if ($document_extension == 'xls') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exls' ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">Print Assigned Document</a> 
                                                        <?php } else if ($document_extension == 'xlsx') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exlsx' ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">Print Assigned Document</a>
                                                        <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                            <a target="_blank" href="<?php echo $original_print_url; ?>" class="btn <?php echo $btn_class; ?> pull-right margin-right">
                                                                Print Assigned Document
                                                            </a>
                                                        <?php } else { ?>  
                                                            <a class="btn <?php echo $btn_class; ?> pull-right margin-right"
                                                           href="javascript:void(0);"
                                                           onclick="fLaunchModal(this);"
                                                           data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                           data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                           data-file-name="<?php echo $document_filename; ?>"
                                                           data-document-title="<?php echo $document_filename; ?>"
                                                           data-preview-ext="<?php echo $document_extension ?>">Print Assigned Document</a>
                                                        <?php } ?>        
                                                    <?php } ?>
                                                </div>
                                            </div> 
                                        </div>
                                    <?php } ?>

                                    <?php if($document['signature_required'] == 1) { //need to set parameter for generated documents so that we can upload document for generated document. ?>    
                                        <div class="panel panel-success">
                                            <div class="panel-heading ">
                                                <strong><?php echo $uploaded_action_title; ?></strong>
                                            </div>
                                            <div class="panel-body">
                                                <div class="document-action-required">
                                                    <?php echo $uploaded_action_desc; ?>
                                                </div>
                                                <div class="document-action-required">
                                                    <?php echo $uploaded_status; ?>
                                                </div>
                                                <div class="document-action-required">
                                                    <?php 
                                                        if($document['uploaded_date'] != NULL ) {
                                                            echo '<b>Uploaded On: </b>';
                                                            echo convert_date_to_frontend_format($document['uploaded_date']);
                                                        } 
                                                    ?>

                                                    <form id="form_upload_file" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" name="perform_action" value="upload_document" />
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                <div class="form-wrp">
                                                                    <div class="form-group auto-height">
                                                                        <div class="upload-file form-control">
                                                                            <span class="selected-file" id="name_upload_file">No file selected</span>
                                                                            <input name="upload_file" id="upload_file" type="file" />
                                                                            <a href="javascript:;">Choose File</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <button type="submit" class="btn <?php echo $uploaded_button_css; ?> btn-equalizer btn-block"><?php echo $uploaded_button_txt; ?></button>
                                                                <?php if(!empty($document['uploaded_file'])) { ?>
                                                                    <?php $document_filename = $document['uploaded_file'];?>
                                                                    <?php $document_file = pathinfo($document_filename); ?>
                                                                    <?php $document_extension = $document_file['extension']; ?>
                                                                    <a class="btn btn-success btn-equalizer btn-block"
                                                                       href="javascript:void(0);"
                                                                       onclick="fLaunchModal(this);"
                                                                       data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                       data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                       data-file-name="<?php echo $document_filename; ?>"
                                                                       data-document-title="<?php echo $document_filename; ?>"
                                                                       data-preview-ext="<?php echo $document_extension ?>">Preview Uploaded</a>
                                                                <?php } ?>
                                                                <?php 
                                                                    if (!isset($document['uploaded_file']) && empty($document['uploaded_file'])) {
                                                                        if (!isset($document['submitted_description']) && empty($document['submitted_description'])) {
                                                                            $after_submittion_button = false;
                                                                        } else {
                                                                            $after_submittion_button = true;
                                                                        }
                                                                    } else {
                                                                        $after_submittion_button = true;
                                                                    }
                                                                ?>                                                            
                                                            </div>
                                                            <div class="col-lg-12 text-right">
                                                                <?php if ($after_submittion_button == true) { ?>
                                                                    <a target="_blank" href="<?php echo $submitted_download_url; ?>" class="btn btn-success" >
                                                                        Download Submitted Document
                                                                    </a>
                                                                    <?php if ($document['document_type'] == 'generated') { ?>
                                                                        <?php if(isset($document['uploaded_file']) && !empty($document['uploaded_file'])) {
                                                                            
                                                                            if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) {
                                                                                $document_filename = $document['uploaded_file'];
                                                                                $document_file = pathinfo($document_filename); 
                                                                                $document_extension = $document_file['extension'];
                                                                                $name = explode(".",$document_filename); 
                                                                                $url_segment_submitted = $name[0];

                                                                                if ($document_extension == 'pdf') { ?>
                                                                                    <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_submitted.'.pdf' ?>" class="btn btn-success" >Print Submitted Document</a>
                                                                                
                                                                                <?php } else if ($document_extension == 'docx') { ?>
                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_submitted.'%2Edocx&wdAccPdf=0' ?>" class="btn btn-success" >Print Submitted Document</a> 
                                                                                <?php } else if ($document_extension == 'doc') { ?>
                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_submitted.'%2Edoc&wdAccPdf=0' ?>" class="btn btn-success" >Print Submitted Document</a>
                                                                                <?php } else { ?>  
                                                                                    <a target="_blank" href="<?php echo $submitted_print_url; ?>" class="btn btn-success" >
                                                                                        Print Submitted Document
                                                                                    </a>
                                                                                <?php } ?>
                                                                            <?php } ?>  
                                                                        
                                                                             
                                                                        <?php } else { ?>
                                                                            <a target="_blank" href="<?php echo $submitted_print_url; ?>" class="btn btn-success" >
                                                                            Print Submitted Document
                                                                            </a>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <?php 
                                                                            if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) {
                                                                                $document_filename = $document['uploaded_file'];
                                                                                $document_file = pathinfo($document_filename); 
                                                                                $document_extension = $document_file['extension'];
                                                                                $name = explode(".",$document_filename); 
                                                                                $url_segment_submitted = $name[0];
                                                                            } else {
                                                                                $url_segment_submitted = '';
                                                                            }   
                                                                        ?>
                                                                        <?php if ($document_extension == 'pdf') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_submitted.'.pdf' ?>" class="btn btn-success" >Print Submitted Document</a>
                                                                        <?php } else if ($document_extension == 'doc') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_submitted.'%2Edoc&wdAccPdf=0' ?>" class="btn btn-success" >Print Submitted Document</a>    
                                                                        <?php } else if ($document_extension == 'docx') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_submitted.'%2Edocx&wdAccPdf=0' ?>" class="btn btn-success" >Print Submitted Document</a> 
                                                                        <?php } else { ?>  
                                                                            <a target="_blank" href="<?php echo $submitted_print_url; ?>" class="btn btn-success" >
                                                                                Print Submitted Document
                                                                            </a>
                                                                        <?php } ?>     
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div id="myModal" class="full reveal" data-reveal></div>

<!-- <?php 

    //print_r(explode(".", $document_filename, 2));
?> -->
<script>



<?php  if($document['isdoctolibrary']==1){ ?>
function document_visible(document_sid) {
   
  var visible_to_document_center = document.querySelector('input[name = visibletodocumentcenter]:checked').value;
          alertify.confirm(
            'Are you sure?',
            'Are you sure you want to change This Document visibility?',
            function () {
                var form_data = new FormData();
                form_data.append('document_sid', document_sid);
                form_data.append('visible_to_document_center', visible_to_document_center);
                 $.ajax({
                    url: '<?php echo base_url('hr_documents_management/document_visible'); ?>',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function (data) {
                        var reload_url = '<?php echo base_url('hr_documents_management/manage_document').'/'.$user_type .'/'?>';
                        reload_url = reload_url+document_sid+'/<?php echo $users_sid;?>';
                        window.location=reload_url;
                    },
                    error: function () {
                    }
                });
            },
            function () {
                
            });
   
    }
<?php }?>

    function deactivate_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Remove This Document?',
            function () {
                var form_data = new FormData();
                form_data.append('document_sid', document_sid);
               

                $.ajax({
                    url: '<?php echo base_url('hr_documents_management/deactivate_document'); ?>',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function (data) {
                        var reload_url = '<?php echo $document_center_url; ?>';
                        window.location=reload_url;
                    },
                    error: function () {
                    }
                });
            },
            function () {
                alertify.alert('Cancelled Remove Document Process!');
            });
    }

    function func_acknowledge_document() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Acknowledge this document?',
            function () {
                $('#form_acknowledge_document').submit();
            },
            function () {
                alertify.alert('Cancelled!');
            });
    }
    
    function func_acknowledge_document_download() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Acknowledge Document Download?',
            function () {
                $('#func_acknowledge_document_download').submit();
            },
            function () {
                alertify.alert('Cancelled!');
            });
    }
    
    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = $(source).attr('data-preview-ext');
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';
        
        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default :
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a target="_blank" download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
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
            }
        });
    }
    
    $(document).ready(function () {
        $('#form_upload_file').validate({
            rules: {
                upload_file: {
                    required: true,
                    accept: 'image/png,image/bmp,image/gif,image/jpeg,image/tiff,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                }
            },
            messages: {
                upload_file: {
                    required: 'You must select a file to upload.',
                    accept: 'Only Images, MS Word Documents and PDF files are allowed.'
                }
            }
        });
        
        $('body').on('change', 'input[type=file]', function () {
            var selected_file = $(this).val();
            var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
            var id = $(this).attr('id');
            $('#name_' + id).html(selected_file);
        });

        $(document).on('change keydown keyup', 'div[data-placeholder]', function() {
            if (this.textContent) {
                this.dataset.divPlaceholderContent = 'true';
            }
            else {
                delete(this.dataset.divPlaceholderContent);
            }
        });

        $('.date_time_picker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();

        $(".save_offer_letter_date").on('click', function () {
            var baseURI = "<?=base_url('hr_documents_management/handler');?>";

            var document_sid = $("#document_sid").val();
            var assign_date = $("#assign_date").val();
            var signed_date = $("#signed_date").val();

            var formData = new FormData();
            formData.append('document_sid', document_sid);
            formData.append('assign_date', assign_date);
            formData.append('signed_date', signed_date);
            formData.append('action', 'modify_offer_letter_dates');

            $.ajax({
                url: baseURI,
                data: formData,
                method: 'POST',
                processData: false,
                contentType: false
            }).done(function(resp){
                var successMSG = 'Date update successfully.';
                alertify.alert('SUCCESS!', successMSG, function(){
                        
                });
            });
        });
    });
</script>