<?php if($document['acknowledgment_required'] != 1 || $document['signature_required'] != 0) return ''; ?>
<?php 
    //
    $ra = [];
    $atitle = 'Document Action: <b>Acknowledgement Required!</b>';
    $adescription = '<b>Acknowledge the receipt of this document</b>';
    $astatus = $document['acknowledged'] == 1 ? '<strong class="text-success">Document Status:</strong> You have successfully Acknowledged this document' : '<strong class="text-danger">Document Status:</strong> You have not yet acknowledged this document';
    $atext = $document['acknowledged'] == 1 ? 'Acknowledged' : 'I Acknowledge';
    $acss = $document['acknowledged'] == 1 ? 'btn-warning' : 'blue-button';
    $aaction = '';
    $adate = '';
?>
<!-- Acknowledge -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading stev_blue">
                <strong><?=$atitle;?></strong>
            </div>
            <div class="panel-body">
                <div class="document-action-required">
                    <?=$adescription;?>
                </div>
                <div class="document-action-required">
                    <?=$astatus;?>
                </div>
                <div class="document-action-required">
                    <?=$adate;?>
                    <a target="_blank" href="<?=$aaction;?>" id="js-acknowledge-click" class="btn <?=$acss;?> pull-right">
                        <?=$atext;?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    
    requiredA = true;

    $('#js-acknowledge-click').click((e) => {
        e.preventDefault();
        //
        if(xhr !== null) return;
        //
        if(aDone === true) return;
        //
        let $this = $(e.target);
        //
        $this.text('Acknowledging...').addClass('disabled');
        //
        markDocument('acknowledge', $this);
    });
</script>