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
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                 <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('safety_checklist'); ?>">
                                        <i class="fa fa-chevron-left"></i>
                                        Back
                                    </a>
                                    <?php echo $title; ?>                               
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <p>Incident Name: <b><?php echo $sub_title; ?></b></p>
                            <p>Submitted by: <b><?php echo $submitted_userName; ?></b></p>
                            <p>Submitted Date: <b><?php echo  date_format (new DateTime($submitted_time), 'M d Y h:m a'); ?></b></p>
                        </div>
                    </div>
                   <?php if($submitted_type == 'submitted'){ ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th class="col-xs-10">Question</th>
                                                <th class="col-xs-2">Answer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($questions as $key => $question) { ?>
                                                <tr>
                                                    <td><?php echo str_replace("_"," ",$question); ?></td>
                                                    <td>
                                                        <?php if(is_array($answer[$key])) { ?>
                                                            <?php $array = $answer[$key]; ?>
                                                            <?php foreach ($answer[$key] as $key => $value) {
                                                                echo $value;
                                                                if( next( $array ) ) {
                                                                    echo ', ';
                                                                }
                                                            } ?>
                                                        <?php } else { ?> 
                                                            <?php echo $answer[$key]; ?>
                                                        <?php } ?>       
                                                    </td>
                                                </tr>
                                            <?php } ?>   
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } elseif($submitted_type == 'uploaded'){ ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="img-thumbnail text-center" style="width: 100%;">
                                    <?php $document_filename = $uploaded_checklist; ?>
                                    <?php $document_file = pathinfo($document_filename); ?>
                                    <?php $dcoument_extension = $document_file['extension']; ?>
                                    <?php if(in_array($dcoument_extension, ['pdf'])){ ?>
                                        <?php $allowed_mime_types = ''?>
                                        <iframe src="<?php echo 'https://docs.google.com/gview?url=' . urlencode(AWS_S3_BUCKET_URL . $document_filename) . '&embedded=true'; ?>" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                    <?php } else if(in_array($dcoument_extension, [ 'jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])){ ?>
                                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
                                    <?php } else if(in_array($dcoument_extension, ['doc', 'docx', 'xlsx'])){ ?>
                                        <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>