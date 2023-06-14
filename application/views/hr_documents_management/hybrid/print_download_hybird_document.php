<div class="row">
    <div class="col-xs-12">
        <div style="padding: 30px 50px; background-color: lightgrey; height: 600px; overflow-x: hidden; overflow-y: scroll;" class="document_body_container">
            <div style="padding: 20px; background-color: white; min-height: 900px;" class="document_body">
            <?php
            
                $document_filename = !empty($s3_path) ? $s3_path : '';
                $document_file = pathinfo($document_filename);
                $document_extension = strtolower($document_extension);

                //
                $t = explode('.', $document_filename);
                $de = $t[sizeof($t) - 1];
                //
                if ($de != $document_extension) $document_extension = $de;
                ?>

                <?php if (in_array($document_extension, ['csv'])) { ?>
                    <iframe src="<?php echo 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>

                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
                <?php } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx', 'pptx', 'ppt'])) { ?>
                    <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                <?php } else { ?>
                    <iframe src="<?php echo 'https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $document_filename); ?>&embedded=true" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div style="padding: 30px 50px; background-color: lightgrey; height: 600px; overflow-x: hidden; overflow-y: scroll;" class="document_body_container">
            <div style="padding: 20px; background-color: white; min-height: 900px;" class="document_body">
                <?php echo html_entity_decode(html_entity_decode($document_body)); ?>
            </div>
        </div>
    </div>
</div>