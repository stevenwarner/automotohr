
<!-- Scripts -->
<script src="<?=base_url('assets/employee_panel/js/jquery.validate.js');?>"></script>
<script src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

<script>
    var 
    requiredA = false,
    requiredD = false,
    requiredC = false,
    xhr = null;

    var 
    aDone = "<?=$document['acknowledged'] != 1 ? false : true; ?>",
    dDone = "<?=$document['downloaded'] != 1 ? false : true; ?>",
    cDone = "<?=$document['user_consent'] != 1 ? false : true; ?>";
</script>

<style>
    #jstopdf p{
        word-wrap: break-word;
    }
</style>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p style="color: #cc0000;"><b><i>We suggest that you only use Google Chrome to access your account
                            and use its Features. Internet Explorer is not supported and may cause certain feature
                            glitches and security issues.</i></b></p>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile"><?=$document['document_title'];?></h1>
                </div>
                <?php $this->load->view('public/documents/complete_upload_document_public');?>
                <?php $this->load->view('public/documents/complete_generate_document_public');?>


                <!-- Acknowledgment Block -->
                <?php $this->load->view('public/documents/acknowledge');?>
                <?php $this->load->view('public/documents/download');?>
            </div>
        </div>
    </div>
</div>


<script>

    if($('.js-document-loader-iframe').length !== 0){
        loadIframe(
            $('.js-document-loader-iframe').prop('src'),
            $('.js-document-loader-iframe'),
            true
        );
    }

    //
    function markDocument(type, $this, pdfdata){
        //
        xhr = $.post("<?=base_url('onboarding/mark_document');?>", {
            action : type,
            assignedDocumentSid: <?=$document['sid'];?>,
            pdf: pdfdata
        }, (resp) => {
            //
            xhr = null;
            //
            if(type == 'acknowledge'){
                aDone = true;
                $this.text('Acknowledged')
                .removeClass('disabled')
                .removeClass('blue-button')
                .addClass('btn-warning');
                if(resp.Status === false){
                    alertify.alert('WARNING!', resp.Response, () => {});
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, () => {
                    checkDocumentComplete();
                });
            }
            //
            if(type == 'download'){
                dDone = true;
                $this.text('Re-Download')
                .removeClass('disabled')
                .removeClass('blue-button')
                .addClass('btn-warning');
                if(resp.Status === true){
                    window.location.href = "<?php echo base_url("onboarding/download/".($document['sid'])."");?>";
                    // setTimeout( checkDocumentComplete, 5000);
                }
            }
            return;
        });
    }

    //
    function checkDocumentComplete(){
        if(requiredC == true) {
            if(cDone)  window.location.reload();
        }
        else if(requiredA == true && requiredD == true) {
            if(aDone == true && dDone == true){
                window.location.reload();
            }
        }
        else if(requiredD == true) {
            if(dDone)  window.location.reload();
        }
        else if(requiredA == true) {
            if(aDone) window.location.reload();
        }

    }
</script>