<?php  if($document['document_type'] != 'uploaded' && $document['document_type'] != 'hybrid_document') return ''; ?>

<div class="row">
    <div class="col-sm-12">
        <?php
            echo $document['file_path'];
            echo $document['document_type'] != 'hybrid_document' ? getInstructions($document) : '';
        ?>
    </div>
</div>

<!--  -->

<?php $this->load->view('iframeLoader'); ?>