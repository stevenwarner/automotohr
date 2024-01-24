<link rel="stylesheet" href="<?php echo base_url('assets/css/i9-style.css') ?>">
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<?php
$is_readonly = '';

if (!empty($eeo_form_info) && $eeo_form_info["is_expired"] == 1) {
    $is_readonly = 'onclick="return false;"';
}

$eeocFormOptions = get_eeoc_options_status($company_sid);

?>

<body>
    <div class="container">
        <?php if ($eeo_form_info['status'] == 1) { ?>
            <div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right" style="margin-top: 14px;">
                        <?php if ($eeo_form_info["is_expired"] == 1) { ?>
                            <a target="_blank" href="<?php echo $download_url; ?>" class="btn blue-button" title="Download EEOC Form" placement="top">
                                <i class="fa fa-download" aria-hidden="true"></i>
                                Download PDF
                            </a>
                            <a target="_blank" href="<?php echo $print_url; ?>" class="btn blue-button" title="Print EEOC Form" placement="top">
                                <i class="fa fa-print" aria-hidden="true"></i>
                                Print PDF
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <hr>
                <?php $this->load->view('eeo/eeoc_new_form_ems', ['eeocFormOptions' => $eeocFormOptions]); ?>
            </div>
        <?php } ?>
    </div>
</body>
<!--  -->
<?php if ($eeo_form_status == 1 && $eeo_form_info['is_expired'] == 0) { ?>
    <script>
        $(function() {
            //
            $('.jsSaveEEOC').click(function() {
                //
                var citizenFlag = <?php echo $dl_citizen; ?>

                var obj = {
                    id: <?= $id; ?>,
                    citizen: $('input[name="citizen"]:checked').val(),
                    group: $('input[name="group"]:checked').val(),
                    veteran: $('input[name="veteran"]:checked').val(),
                    disability: $('input[name="disability"]:checked').val(),
                    gender: $('input[name="gender"]:checked').val(),
                    location: "<?= $location; ?>"
                };

                //
                if (citizenFlag == 1 && obj.citizen === undefined) {
                    alertify.alert('Please, select a citizen.');
                    return;
                }

                //
                if (obj.group === undefined) {
                    alertify.alert('Please, select a group status.');
                    return;
                }

                //
                <?php if ($eeocFormOptions['dl_vet'] == 1) { ?>
                    if (obj.veteran === undefined) {
                        alertify.alert('Please, select veteran.');
                        return;
                    }

                <?php } ?>

                //
                <?php if ($eeocFormOptions['dl_vot'] == 1) { ?>
                    if (obj.disability === undefined) {
                        alertify.alert('Please, select disability.');
                        return;
                    }
                <?php } ?>

                //
                <?php if ($eeocFormOptions['dl_gen'] == 1) { ?>
                    if (obj.gender === undefined) {
                        alertify.alert('Please, select gender.');
                        return;
                    }
                <?php } ?>

                $.post(
                    "<?= base_url("eeoc_form_submit"); ?>",
                    obj
                ).done(function(resp) {
                    //
                    if (resp === 'success') {
                        alertify.alert('Success!', 'You have successfully submitted the EEOC form.', function() {
                            window.location.reload();
                        });
                        return;
                    }
                    //
                    alertify.alert('Success!', 'You have successfully submitted the EEOC form.');
                });
            });
        });
    </script>
<?php } ?>