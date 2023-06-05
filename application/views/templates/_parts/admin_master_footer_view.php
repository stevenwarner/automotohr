<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<footer class="footer">
    <div class="copyright">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="copy-right-text">
                        <p>&copy; 2015 - <?php echo date('Y') . ' ' . STORE_NAME; ?>. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="clear"></div>
</div>
<!-- Files Preview Modal *** Start *** -->
<div id="file_preview_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- Files Preview Modal *** End *** -->
<span id="get_footer_url" style="display:none;"><?php echo base_url(); ?></span>
<!-- Scripts -->
<script>
    var baseURI = "<?= rtrim(base_url(), '/'); ?>/"

    <?php if (isset($apiURL)) { ?>
        apiURL = "<?=$apiURL;?>";
    <?php } ?>
    <?php if (isset($apiAccessToken)) { ?>
        apiAccessToken = "<?=$apiAccessToken;?>";
    <?php } ?>
</script>
<?php if (isset($PageScripts)) : ?>
    <!-- Scripts -->
    <?= GetScripts($PageScripts); ?>
<?php endif; ?>
</body>

</html>