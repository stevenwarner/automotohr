
<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        // Fetch Settings
        fetchTimeOffSettings();
        //
        $('.js-accural-date').datepicker({ dateFormat: 'mm-dd-yy' });
        //
        $(document).on('click', '#js-save-btn', function(){
            var megaOBJ = {};
            megaOBJ.action = 'edit_settings';
            megaOBJ.defaultTimeslot = $('#js-default-time-slot-hours').val().trim();
            megaOBJ.format = $('#js-formats').val();
            megaOBJ.approvalCheck = Number($('#js-approval-check').prop('checked'));
            megaOBJ.email_send_Check = Number($('#js-send-email-check').prop('checked'));
            megaOBJ.emailCheck = Number($('#js-email-check').prop('checked'));
            megaOBJ.companySid = <?=$company_sid;?>;
            //
            $.post(baseURI+'handler', megaOBJ, function(resp){
                if(resp.Status === false){
                    loader('hide');
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                alertify.alert('SUCCESS!', resp.Response);
                loader('hide');
            });
        });
        //
        function fetchTimeOffSettings(){
            $.post(
                baseURI+'handler',
                {
                    action: 'get_settings_by_company',
                    companySid: "<?=$company_sid;?>"
                },
                function(resp){
                    //
                    if(resp.Data.Formats.length != 0){
                        var rows = '';
                        // rows += '<option value="">[Ple]</option>';
                        $.each(resp.Data.Formats, function(i, v){
                            rows += '<option value="'+( v.format_id )+'">'+( v.title.replace(/:/g, ' ') )+'</option>';
                        });
                        $('#js-formats').html(rows);
                        $('#js-formats').select2();
                    }
                    if(resp.Data.Settings.length != 0){
                        //
                        $('#js-default-time-slot-hours').val(resp.Data.Settings.default_timeslot);
                        $('#js-approval-check').prop('checked', resp.Data.Settings.approval_check == 1 ? true : false);
                        $('#js-email-check').prop('checked', resp.Data.Settings.email_check == 1 ? true : false);
                        $('#js-formats').select2('val', resp.Data.Settings.timeoff_format_sid);
                    }

                    loader('hide');
                }
            );
        }

        $('[data-toggle="popovers"]').popover({
            trigger: 'hover'
        });
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>
    })

</script>
