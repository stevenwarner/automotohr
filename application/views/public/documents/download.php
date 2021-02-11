<?php if($document['download_required'] != 1) return ''; ?>
<?php

    //
    $dtitle = 'Document Action: <b>Save / Download</b>';
    $ddescription = '<b>Please download this document to Sign / Fill. </b>';
    $dstatus = $document['downloaded'] == 1 ? '<strong class="text-success">Document Status:</strong> You have successfully downloaded this document' : '<strong class="text-danger">Document Status:</strong> You have not yet downloaded this document';
    $ddownload_text = $document['downloaded'] == 1 ? 'Re-Download' : 'Save / Download';
    $dcss = $document['downloaded'] == 1 ? 'btn-warning' : 'blue-button';
    $ddownload_action = $document['downloaded'] == 1 ? '' : '';
    $dprint_action = $document['downloaded'] == 1 ? '' : '';
    $ddate = '';

?>

<!-- Download -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading stev_blue">
                <strong><?=$dtitle;?></strong>
            </div>
            <div class="panel-body">
                <div class="document-action-required">
                    <?=$ddescription;?>
                </div>
                <div class="document-action-required">
                    <?=$dstatus;?>
                </div>
                <div class="document-action-required">
                    <?=$ddate;?>
                    <a target="_blank" href="<?=$ddownload_action;?>" id="js-download-click" class="btn <?=$dcss;?> pull-right">
                        <?=$ddownload_text;?>
                    </a>
                    <?php if($document['document_type'] != 'hybrid_document') { ?>
                        <a target="_blank" href="<?=AWS_S3_BUCKET_URL.$document['document_s3_name'];?>" id="js-print-click" class="btn blue-button pull-right" style="margin-right: 10px;">
                            Print Document
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    requiredD = true;
    
    $('#js-download-click').click((e) => {
        e.preventDefault();
        //
        if(xhr !== null) return;
        //
        let $this = $(e.target);
        //
        $this.addClass('disabled');
        //
        if("<?=$document['document_type'];?>" != 'uploaded'){
            $('#jstopdf').css({
                'padding': '50px 20px'
            }).show();
            generatePDF($('#jstopdf'), $this);
            return;
        }
        //
        markDocument('download', $this);
    });

    $('#js-print-click').click((e) => {
        <?php if($document['document_type'] == 'generated') { ?>
        e.preventDefault();
        printContent('jstopdf');
        <?php } ?>
    });

    function printContent(el){
        var restorepage = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent.html());
        window.print();
        $('body').html(restorepage);
    }


    //
    function generatePDF(target, $this){
        //
        let draw = kendo.drawing;
        //
        draw.drawDOM(target, {
            avoidLinks: true,
            paperSize: "A4",
            margin: { bottom: "1cm" },
            scale: 0.6
        })
        .then(function(root) {
            return draw.exportPDF(root);
        })
        .done(function(pdfdata) {
            //
            target.attr('style', '').hide(0);
            //
            markDocument('download', $this, pdfdata);
        });
    }
</script>