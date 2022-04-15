<?php 
    //
    $documentData = json_decode($document_info['flow_json'], true);
    $type = 'generated';
    $content = '';
    // Check for offer letter
    $type = $documentData['document_type'] == 'offer_letter' ? $type = $documentData['offer_letter_type'] : $documentData['document_type'];
    //
    if($type == 'generated'){
        //
        $content = $documentData['document_description'];
        //
        replaceDocumentContentTags(
            $content,
            $documentData['company_sid'],
            $user_sid,
            $user_type,
            $documentData['document_sid']
        );
    } else{
        $content = AWS_S3_BUCKET_URL.$documentData['document_s3_name'];
    }
?>
<!--  -->
<div class="panel panel-success csBG4">
    <div class="panel-body csRadius5">
        <?php if($type === 'generated'): ?>
            <div class="p10 csRadius5 csF16" style="min-height: 440px; background-color: #fff;">
                <?php echo html_entity_decode($content); ?>
            </div>
        <?php elseif(isImage($content)): ?>
            <img src="<?=$content;?>" alt="" style="display: block; max-width: 100%; margin: auto;">
        <?php else: ?>
            <iframe src="" id="jsDocumentLoadArea" title="Viewer" style="width: 100%; height: 440px; border: 0"></iframe>
        <?php endif; ?>
    </div>
</div>
<?php if($type === 'uploaded'): ?>
    <?php $this->load->view('iframeLoader'); ?>

    <script>
        loadIframe(
            "<?=$content;?>",
            '#jsDocumentLoadArea',
            true
        );
    </script>
<?php endif; ?>