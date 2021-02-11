<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$print_url = '';

if (!empty($document_detail['uploaded_document'])) {
    $document_filename = $document_detail['uploaded_document'];
    $filename = explode(".", $document_filename);
    $document_name = $filename[0];
    $document_extension = $filename[1];

    if ($document_extension == 'pdf') {
        $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
    } else if ($document_extension == 'doc') {
        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
    } else if ($document_extension == 'docx') {
        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
    } else if ($document_extension == 'xls') {
        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
    } else if ($document_extension == 'xlsx') {
        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
    } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
        $print_url = base_url('hr_documents_management/print_generated_and_offer_later/submitted/generated/' . $document_sid);
    } else if ($document_extension == 'csv') {
        $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
    }

    $download_url = AWS_S3_BUCKET_URL . urlencode($document_detail['uploaded_document']);
} else {
    $print_url = 'javascript:;';
    $download_url = 'javascript:;';
}
?>
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
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-file-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/accurate_background/activation_orders'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i>Accurate Background</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title page-title">
                                            <h2 class="page-title">Company Name: <?php echo $company_name?></h2>
                                            <a href="<?php echo $download_url; ?>" class="hr-edit-btn pull-right" title="Download this Document">
                                                Download
                                            </a>
                                            <a href="<?php echo $print_url; ?>" class="hr-edit-btn pull-right" target="_blank">
                                                Print
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em; margin-top: 10px;">	
                                        <?php if (!empty($document_detail['uploaded_document'])) { ?>
                                            <?php if (in_array($document_extension, ['pdf', 'csv'])) { ?>
                                                    <iframe src="<?php echo 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"/>
                                            <?php } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx'])) { ?>
                                                    <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" id="preview_iframe" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } ?>
                                        <?php } ?>
                                    </div> 
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h4 class="hr-registered">Manage Accurate Background Document</h4>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="field-row field-row-autoheight">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="field-row">
                                                        <label class="control control--radio">
                                                            Keep Same Document
                                                            <input type="radio" name="document_status" class="document_status" value="not_changed" checked="">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>	
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="field-row">
                                                        <label class="control control--radio">
                                                            Replace Document
                                                            <input type="radio" name="document_status" class="document_status" value="changed">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <form method="post" id="form_replace_document" enctype="multipart/form-data">
                                                    <input type="hidden" name="perform_action" value="perform_action">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <div class="upload-file form-control">
                                                                <input type="file" name="document" id="document" <?php echo!isset($document_info) ? 'required' : ''; ?> onchange="check_upload_document('document')">
                                                                <p id="name_document"></p>
                                                                <a href="javascript:;">Choose File</a>
                                                            </div>    
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                        <input type="button" class="search-btn" id="form-submit" value="Update" name="form-submit">
                                                        <a class="search-btn black-btn" href="<?php echo base_url('manage_admin/accurate_background/manage_document/' . $document_sid); ?>">Cancel</a>
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
    </div>
</div>
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<style type="text/css">
    .file-loader,
.my_loader{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99;
}
.loader-icon-box{
    position: absolute;
    top: 50%;
    left: 50%;
    width: auto;
    z-index: 9999;
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    -o-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
.loader-icon-box i{
    font-size: 14em;
    color: #81b431;
}
.loader-text{
    display: inline-block;
    padding: 10px;
    color: #000;
    background-color: #fff;
    border-radius: 5px;
    text-align: center;
    font-weight: 600;
}
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form_replace_document').hide();
    });
    
    function check_upload_document(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'document') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "xls" && ext != "xlsx" && ext != "PDF" && ext != "DOCX" && ext != "DOC" && ext != "XLS" && ext != "XLSX" && ext != "CSV" && ext != "csv") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    $(".document_status").click(function () {
        var document_status = $(this).val();
        
        if (document_status == 'changed') {
            $('#form_replace_document').show();
        } else if (document_status == 'not_changed') {
            $('#form_replace_document').hide();
        }

    });

    $("#form-submit").click(function () {
        var fileName = $("#document").val();
        if (fileName.length > 0) {
            
            $('#name_document').html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "xls" && ext != "xlsx" && ext != "PDF" && ext != "DOCX" && ext != "DOC" && ext != "XLS" && ext != "XLSX" && ext != "CSV" && ext != "csv") {
                $("#document").val(null);
                $('#name_document').html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
            } else {
                $('#my_loader').show();
                $('#form_replace_document').submit();
            }
        } else {
            $('#name_document').html('<p class="red">Please Select Document to Upload!</p>');
        }
    });
</script>
