<?php if ($document_type == 'generated') { ?>
    <div class="row">
        <div class="col-xs-12">
            <div style="padding: 30px 50px; background-color: lightgrey; height: 600px; overflow-x: hidden; overflow-y: scroll;" class="document_body_container">
                <div style="padding: 20px; background-color: white; min-height: 900px;" class="document_body">
                    <?php echo html_entity_decode(html_entity_decode($document_body)); ?>
                </div>
            </div>
        </div>
    </div>
<?php } else if ($document_type == 'uploaded') { ?>

    <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">
        <?php
        $document_filename = !empty($document_path) ? $document_path : '';
        $document_file = pathinfo($document_filename);
        $document_extension = strtolower($document_ext);
        //
        $t = explode('.', $document_filename);
        $de = $t[sizeof($t) - 1];
        //
        if ($de != $document_extension) $document_extension = $de;

        if (in_array($document_extension, ['pdf', 'csv'])) {
            $allowed_mime_types = ''; ?>
            <iframe src="<?php echo  'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
        <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
        <?php } else if (in_array($document_extension, ['doc', 'docx', 'xls', 'xlsx'])) { ?>
            <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
        <?php } ?>
    </div>

<?php } elseif ($document_type == 'hybrid_document') { ?>
    <div class="row">
        <div class="col-xs-12">
            <div style="padding: 30px 50px; background-color: lightgrey; height: 600px; overflow-x: hidden; overflow-y: scroll;" class="document_body_container">
                <div style="padding: 20px; background-color: white; min-height: 900px;" class="document_body">
                    <?php echo html_entity_decode(html_entity_decode($document_body)); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">
        <?php
        $document_filename = !empty($document_path) ? $document_path : '';
        $document_file = pathinfo($document_filename);
        $document_extension = strtolower($document_ext);

        //
        $t = explode('.', $document_filename);
        $de = $t[sizeof($t) - 1];
        //
        if ($de != $document_extension) $document_extension = $de;

        if (in_array($document_extension, ['pdf', 'csv'])) {
            $allowed_mime_types = ''; ?>
            <iframe src="<?php echo  'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
        <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
        <?php } else if (in_array($document_extension, ['doc', 'docx', 'xls', 'xlsx'])) { ?>
            <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
        <?php } ?>
    </div>

<?php } ?>