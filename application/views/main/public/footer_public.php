<?=$template["footer"];?>
<!-- JS -->
    <?php if (!$loadJsFiles) { ?>
        <script src="<?= main_url("public/v1/plugins/jquery/jquery-3.7.1.min.js?v=3.0"); ?>"></script>
    <?php } ?>
    <script src="<?= main_url("public/v1/plugins/bootstrap/js/bootstrap.min.js?v=3.0"); ?>"></script>
    <script src="<?= main_url("public/v1/plugins/moment/moment.min.js?v=3.0"); ?>"></script>
    <script src="<?= main_url("public/v1/plugins/moment/moment-timezone.min.js?v=3.0"); ?>"></script>
    <!-- JS Bundles -->
    <?= $pageJs ? GetScripts($pageJs) : ""; ?>
    <?= bundleJs([
        "js/app_helper",
        "v1/app/js/global",
    ], "public/v1/app/", "global", true); ?>
    <!--  -->
    <?= $appJs ?? ""; ?>
    </div>
    </body>

    </html>