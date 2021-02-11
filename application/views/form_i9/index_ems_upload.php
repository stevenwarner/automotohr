<?php
$company_name = ucwords($session['company_detail']['CompanyName']);
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message');?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="<?php echo base_url('hr_documents_management/my_documents'); ?>"
                        class="btn btn-block blue-button"><i class="fa fa-angle-left"></i> Documents</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header"><span class="section-ttile"><?php echo $title; ?></span></div>
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <div class="col-lg-3 pull-right">
                            <a target="_blank"
                               href="<?php echo base_url('hr_documents_management/download_upload_document' .'/' . $pre_form['s3_filename']) ?>"
                               class="btn btn-block blue-button">Download PDF</a>
                        </div>
                        <?php
                            $document_filename = $pre_form['s3_filename'];
                            $document_file = pathinfo($document_filename);
                            $document_extension = $document_file['extension'];
                            $name = explode(".",$document_filename);
                            $url_segment_original = $name[0];?>
<!--                        <div class="col-lg-3 pull-right">-->
<!--                            <a class="btn blue-button btn-sm btn-block"-->
<!--                               href="javascript:void(0);"-->
<!--                               onclick="fLaunchModal(this);"-->
<!--                               data-preview-url="--><?//= AWS_S3_BUCKET_URL . $pre_form['s3_filename']; ?><!--"-->
<!--                               data-download-url="--><?//= AWS_S3_BUCKET_URL . $pre_form['s3_filename']; ?><!--"-->
<!--                               data-file-name="--><?php //echo $pre_form['s3_filename']; ?><!--"-->
<!--                               data-document-title="--><?php //echo $pre_form['s3_filename']; ?><!--"-->
<!--                               data-preview-ext="--><?php //echo $document_extension ?><!--">Preview</a>-->
<!--                            <a target="_blank"-->
<!--                               href="--><?php //echo base_url('form_i9/preview_i9form/' . $pre_form['user_type'] . '/' . $pre_form['user_sid']) ?><!--"-->
<!--                               class="btn blue-button btn-block">Preview</a>-->
<!--                        </div>-->
                        <div class="col-lg-3 pull-right">
                            <?php
                            if ($document_extension == 'pdf') { ?>
                                <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_original.'.pdf' ?>" class="btn blue-button btn-sm btn-block">Print</a>

                            <?php } else if ($document_extension == 'docx') { ?>
                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edocx&wdAccPdf=0' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                            <?php } else if ($document_extension == 'doc') { ?>
                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edoc&wdAccPdf=0' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                            <?php } else if ($document_extension == 'xls') { ?>
                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exls' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                            <?php } else if ($document_extension == 'xlsx') { ?>
                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exlsx' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                            <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                <a target="_blank" href="<?php echo base_url('form_i9/print_i9_img/' . $url_segment_original); ?>" class="btn blue-button btn-sm btn-block">
                                    Print</a>
                            <?php } ?>
<!--                            <a target="_blank" href="--><?php //echo base_url('form_i9/print_i9_form'); ?><!--"-->
<!--                               class="btn btn-block blue-button">-->
<!--                                Print I9 Form-->
<!--                            </a>-->
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="alert alert-info">
                            <strong><?=$pre_form['s3_filename'];?></strong>
                        </div>
                        <div class="hr-innerpadding">
                            <iframe src="<?=AWS_S3_BUCKET_URL.$pre_form['s3_filename'];?>?embedded=true" style="width: 100%; height: 80rem;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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


    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var footer_content_print = '';
        var iframe_url = '';
        var base_url = '<?= base_url()?>';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://docs.google.com/gview?url=' + document_download_url + '&embedded=true">Print</a>';
                    break;
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://view.officeapps.live.com/op/embed.aspx?src=' + document_download_url + '&embedded=true">Print</a>';
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

            footer_content = '<a target="_blank" class="btn btn-success" href="' + base_url + 'hr_documents_management/download_upload_document/' + document_file_name + '">Download</a>';
            footer_content += footer_content_print;
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        // $('#document_modal_footer').html(footer_content);
        $('#document_modal .modal-footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }
</script>