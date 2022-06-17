<?php 
    //
    $type = 'generated';
    $content = '';
    $file = '';
    $file_type = '';
    // Check for offer letter
    $type = $document_info['document_type'] == 'offer_letter' ? $type = $document_info['offer_letter_type'] : $document_info['document_type'];
    //
    if($type == 'generated' || $type == 'hybrid_document'){
        //
        $content = $document_info['document_description'];
        //
        $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
        $authorized_signature_image = '------------------------------(Authorized Signature Required)';
        $signature_bas64_image = '------------------------------(Signature Required)';
        $init_signature_bas64_image = '------------------------------(Signature Initial Required)';
        $sign_date = '------------------------------(Sign Date Required)';
        //
        $content = str_replace('{{signature}}', $signature_bas64_image, $content);
        $content = str_replace('{{inital}}', $init_signature_bas64_image, $content);
        $content = str_replace('{{sign_date}}', $sign_date, $content);
        $content = str_replace('{{authorized_signature}}', $authorized_signature_image, $content);
        $content = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $content);
        //
        replaceDocumentContentTags(
            $content,
            $document_info['company_sid'],
            $user_sid,
            $user_type,
            $document_info['document_sid']
        );
    }

    if ($type == 'uploaded' || $type == 'hybrid_document') {
        $documents_links = getUploadedDocumentURL($document_info['document_s3_name']);
        if (!empty($documents_links["image_path"])) {
            $file_type = 'image';
            $file = $documents_links['image_path'];
        } else {
            $file_type = 'document';
            $file = $documents_links['ifram_url'];
        }
    }
?>
<!--  -->
<?php if(!empty ($file)): ?>
    <!--  -->
    <div class="row">
        <br>
        <div class="col-sm-12 col-md-12">
            <h2 class="csF20 csB7">Upload Document</h2>
        </div>
    </div>
    <!--  -->
    <div class="panel panel-success csBG4">
        <div class="panel-body csRadius5">
            <div class="csPageBox csRadius5">
                <div class="csPageBoxBody">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if($file_type == 'image'): ?>
                                <img src="<?=$file;?>" alt="" style="display: block; max-width: 100%; margin: auto;">
                            <?php else: ?>
                                <iframe src="<?=$file;?>" title="Viewer" style="width: 100%; height: 440px; border: 0"></iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>        
    <!--  -->
<?php endif; ?>

<?php if(($type === 'generated' || $type == 'hybrid_document') && !empty ($content)) : ?>
    <!--  -->
    <div class="row">
        <br>
        <div class="col-sm-12 col-md-12">
            <h2 class="csF20 csB7">Document Description</h2>
        </div>
    </div>
    <!--  -->
    <div class="panel panel-success csBG4">
        <div class="panel-body csRadius5">
            <div class="p10 csRadius5 csF16" style="min-height: 440px; background-color: #fff;">
                <?php echo html_entity_decode($content); ?>
            </div>
        </div>
    </div>        
<?php endif; ?> 