<style>
    .csRoundImg{
        width: 60px !important;
        height: 60px !important;
        border: 2px solid #ddd;
    }
</style>
<div class="col-md-9 col-sm-12">
    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right">
                <a class="btn btn-black" href="<?php echo purl('courses'); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back To Courses</a>
            </span>    
        </div>
    </div>
    <br>

    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Basic Info&nbsp;
                        <button class="btn btn-success btn-xs csF16 csRadius5" style="font-size: 16px !important">STARTED</button>
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Title
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php echo $basic_info['title']; ?> 
                    </h5>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                    Start date
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php echo date("M d Y, D", strtotime($basic_info['course_start_date'])); ?> 
                    </h5>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Is the course expire after a certain period of time?
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php echo strtoupper($basic_info['is_course_expired']); ?> 
                    </h5>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <?php if ($basic_info['is_course_expired'] == 'yes') { ?> 
                        <h5 class="csF16 csB7" style="font-size: 16px !important">
                        The Course will expire after
                        </h5>
                        <h5 class="csF14" style="font-size: 14px !important">
                            <?php echo $basic_info['course_expired_day'].' '.ucfirst($basic_info['course_expired_type']); ?> 
                        </h5>
                    <?php } ?> 
                </div>

                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Course Status
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php 
                            if ($basic_info['course_status'] == 1) {
                                echo "Active";
                            } else {
                                echo "Inactive";
                            }
                        ?> 
                    </h5>
                </div>
            </div>  
        </div>
    </div>

    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Employee(s)&nbsp;
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="csBoxContentEmpployeeSection">
                        <a href="http://localhost/ahr/employee_profile/3137" target="_blank">
                        <div class="col-sm-1">
                            <img src="https://automotohrattachments.s3.amazonaws.com/images-(7)-lV6bpg.jpg" class="csRoundImg">
                        </div>
                        <div class="col-sm-11 pr0">
                            <p><strong>abc xyz</strong> <br>  (testing) [Employee]</p>
                        </div>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="csBoxContentEmpployeeSection">
                        <a href="http://localhost/ahr/employee_profile/1943" target="_blank">
                        <div class="col-sm-1">
                            <img src="https://automotohrattachments.s3.amazonaws.com/electric_bike-1nLoQe.jpg" class="csRoundImg">
                        </div>
                        <div class="col-sm-11 pr0">
                            <p><strong>Ahsan Salah ud Din</strong> <br>  (UI Engineer) [Manager]</p>
                        </div>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-theme">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Supporting Document(s)&nbsp;
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <table class="table table-striped table-condensed">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="csF16 csB7">Title</th>
                        <th scope="col" class="csF16 csB7">Type</th>
                        <th scope="col" class="csF16 csB7">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($documents)) { ?>
                        <?php foreach($documents as $document){ ?>
                            <tr>
                                <td style="vertical-align: middle">
                                    <?php echo $document['title']; ?>      
                                </td>
                                <td style="vertical-align: middle">
                                    <?php echo $document['ext']; ?>
                                </td>
                                <td style="vertical-align: middle">
                                    <button class="btn btn-orange csF16 show_doc" onclick="preview_supporting_document(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['url']; ?>" data-s3-name="<?php echo $document['url']; ?>" title="View Document" placement="top">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button> 
                                </td>
                            </tr>   
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Visibility&nbsp;
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Role(s)
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        Admin
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        Hiring Manager
                    </h5>
                </div>
            </div> 
            <hr> 
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Department(s)
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        HR
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        Sales
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Team(s)
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        Development
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        Business
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Employee(s)
                    </h5>
                    <div class="csBoxContentEmpployeeSection">
                        <a href="http://localhost/ahr/employee_profile/3137" target="_blank">
                        <div class="col-sm-1">
                            <img src="https://automotohrattachments.s3.amazonaws.com/images-(7)-lV6bpg.jpg" class="csRoundImg">
                        </div>
                        <div class="col-sm-11 pr0">
                            <p><strong>abc xyz</strong> <br>  (testing) [Employee]</p>
                        </div>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="csBoxContentEmpployeeSection">
                        <a href="http://localhost/ahr/employee_profile/1943" target="_blank">
                        <div class="col-sm-1">
                            <img src="https://automotohrattachments.s3.amazonaws.com/electric_bike-1nLoQe.jpg" class="csRoundImg">
                        </div>
                        <div class="col-sm-11 pr0">
                            <p><strong>Ahsan Salah ud Din</strong> <br>  (UI Engineer) [Manager]</p>
                        </div>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Latest Document Modal Start -->
<div id="show_latest_preview_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="latest_document_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="latest-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="latest-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div> 
                <div id="latest_assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="latest_document_modal_footer">
                
            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<script>
    function preview_supporting_document (source) {
        var document_title = 'Attach Document';
        
       
        var preview_document        = 1;
        var model_contant           = '';
        var preview_iframe_url      = '';
        var preview_image_url       = '';
        var document_print_url      = '';
        var document_download_url   = '';

        var file_s3_path            = $(source).attr('data-preview-url');
        var file_s3_name            = $(source).attr('data-s3-name');

        var file_extension          = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
        var document_file_name      = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
        var document_extension      = file_extension.toLowerCase();
        

        switch (file_extension.toLowerCase()) {
            case 'pdf':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.pdf';
                break;
            case 'csv':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.csv';
                break;
            case 'doc':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Edoc&wdAccPdf=0';
                break;
            case 'docx':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Edocx&wdAccPdf=0';
                break;
            case 'ppt':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.ppt';
                break;
            case 'pptx':
                dpreview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.pptx';
                break;
            case 'xls':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Exls';
                break;
            case 'xlsx':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Exlsx';
                break;
            case 'jpg':
            case 'jpe':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'JPG':
            case 'JPE':
            case 'JPEG':
            case 'PNG':
            case 'GIF':
                preview_document = 0;
                preview_image_url = file_s3_path;
                document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>'+'/'+file_s3_name;
                break;
            default : //using google docs
                preview_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                break;
        }

        document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>'+'/'+file_s3_name;

        $('#show_latest_preview_document_modal').modal('show');
        $("#latest_document_modal_title").html(document_title);
        $('#latest-iframe-container').show();

        if (preview_document == 1) {
            model_contant = $("<iframe />")
                .attr("id", "latest_document_iframe")
                .attr("class", "uploaded-file-preview")
                .attr("src", preview_iframe_url);
        } else {
            model_contant = $("<img />")
                .attr("id", "latest_image_tag")
                .attr("class", "img-responsive")
                .css("margin-left", "auto")
                .css("margin-right", "auto")
                .attr("src", preview_image_url);
        }
        

        $("#latest-iframe-holder").append(model_contant);
        //
        if (preview_document == 1) {
            loadIframe(
                    preview_iframe_url,
                    '#latest_document_iframe',
                    true
                );
        }

        footer_content = '<a target="_blank" class="btn btn-success" href="' + document_print_url + '">Print</a>';
        footer_content += '<a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        $("#latest_document_modal_footer").html(footer_content);
        
    }

    $('#show_latest_preview_document_modal').on('hidden.bs.modal', function () {
        $("#latest-iframe-holder").html('');
        $("#latest_document_iframe").remove();
        $("#latest_image_tag").remove();
        $('#latest-iframe-container').hide();
        $('#latest_assigned_document_preview').html('');
        $('#latest_assigned_document_preview').hide();
    });

</script>