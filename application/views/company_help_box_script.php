<!--  -->
<?php
    $get_data_user = $this->session->userdata('logged_in');
    $company_id = $get_data_user['company_detail']['sid'] ;
    $getCompanyHelpboxInfo = get_company_helpbox_info($company_id);
 ?>
<script>
    $(function jsCompanyHelpBox() {
        //
        var xhr = null;
        //
        $('.jsCompanyHelpBoxBtn').click(function(event) {
            //
            event.preventDefault();
            var _model = window.hasOwnProperty('Modal') ? window.Modal : Model;
            //
            _model({
                Id: 'jsCompanyHelpBoxModal',
                Loader: 'jsCompanyHelpBoxModalLoader',
                Title: '<?php echo addslashes($getCompanyHelpboxInfo[0]['box_title']);?>',
                Buttons: ['<button class="btn btn-success jsSubmitCompanyContactBtn">Send Email</button>'],
                Body: '<div class="container-fluid"><div id="jsCompanyHelpBoxModalBody"></div></div>'
            }, function() {
                //
                $.get("<?= base_url('get_support_page'); ?>")
                    .success(function(resp) {
                        $('#jsCompanyHelpBoxModalBody').html(resp.view);
                        CKEDITOR.replace('jsBody');
                        // addons js
                        ml(false, 'jsCompanyHelpBoxModalLoader');
                    })
            });
        });

        //
        $(document).on('click', '.jsSubmitCompanyContactBtn', function(event) {
            event.preventDefault();
            //
            if (xhr != null) {
                return;
            }
            //
            var obj = {
                emailTo: '',
                emailFrom: '',
                subject: $('#jsSubject').val().trim(),
                message: CKEDITOR.instances['jsBody'].getData().trim()
            };
            // validation
            if (obj.subject == '') {
                return alertify.alert('Error', 'Subject is required.', function() {});
            }
            if (obj.message == '') {
                return alertify.alert('Error', 'Message is required.', function() {});
            }
            ml(true, 'jsCompanyHelpBoxModalLoader');

            xhr = $.ajax({
                url: '<?= base_url('compose_message_help') ?>',
                cache: false,
                type: 'post',
                data: obj,
                success: function(resp) {
                    ml(false, 'jsCompanyHelpBoxModalLoader');
                    xhr = null;
                    if (resp.Response.length > 0) {
                        return alertify.alert('Success', 'Message sent successfully!', function() {
                            $('#jsCompanyHelpBoxModal .jsModalCancel').trigger('click');
                            window.location.reload()
                        })
                    }

                },
                fail: function() {
                    ml(false, 'jsCompanyHelpBoxModalLoader');
                    xhr = null;
                }
            });
        });

        //
    });
</script>